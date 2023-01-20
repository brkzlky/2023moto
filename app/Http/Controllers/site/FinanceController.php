<?php

namespace App\Http\Controllers\site;

use App;
use Session;
use Str;
use App\Models\Faq;
use App\Models\Listing;
use App\Models\BankRate;
use App\Models\Location;
use App\Models\LoanRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FinanceController extends Controller
{
    //Finance Page
    public function finance()
    {
        
        $current_location = Session::get('current_location');;
        $location = Location::where("subdomain",$current_location)->with("currency")->first();
        $d['currency']['label'] = $location->currency->label;
        $d['currency']['symbol'] = $location->currency->symbol;
        $d['currency']['currency_guid'] = $location->currency->currency_guid;
        $d['faqs'] = Faq::where("type","finance")->get();
        return view('site.page.finance', $d);
    }

    //Finance Form 
    public function finance_offer_form(Request $r)
    {
        $loanresult = json_decode($r->loanresult);

        $lr = new LoanRequest();
        $lr->loan_request_guid = Str::uuid();
        $lr->fullname = $r->fullname;
        $lr->identity = $r->identity;
        $lr->email = $r->email;
        $lr->phone = $r->phone;
        $lr->bank = $loanresult->bank;
        $lr->loan = $loanresult->main_loan;
        $lr->rate = $loanresult->rate;
        $lr->period = $loanresult->period;
        $lr->monthly_loan = $loanresult->monthly_loan;
        $lr->total_loan = $loanresult->total_loan;
        $lr->save();

        $d['finance_offer'] = true;
        $d['success_title'] = __('alert.success_title');
        $d['success_msg'] = __('alert.loan_request_success');
        return redirect()->back()->with($d);
    }

    //Get Finance Rates
    public function finance_rates(Request $r)
    {
        $rates = BankRate::where("status","1")->get();

        $d['result'] = 200;
        $d['rates'] = $rates;
        return response()->json($d, 200);
    }

    //Calculate Loan
    public function calculate_loan(Request $r)
    {
        $loan = $r->loan;
        $periodraw = explode("-",$r->maturity);
        $period = $periodraw[1];
        $rate = BankRate::where("status","1")->where("period_type",$periodraw[0])->where("period",$periodraw[1])->with("bank")->first();
        $monthlyrate = ($rate->rate/$rate->period) / 100;
        $monthlyrateOver = pow((1 + $monthlyrate), $period);

        $monthly_loan = $loan * (($monthlyrate * $monthlyrateOver) / ($monthlyrateOver - 1));
        $recomendeds = Listing::where("expired","0")->where("status","1")->whereBetween("price",[$loan-2000,$loan+2000])->has("main_image")->with("main_image","currency","location")->inRandomOrder()->limit(10)->get();

        $d['result'] = 200;
        $d['loanresult']['main_loan'] = $loan;
        $d['loanresult']['bank'] = $rate->bank->name;
        $d['loanresult']['monthly_loan'] = round($monthly_loan, 4);
        $d['loanresult']['period'] = $period.' Months';
        $d['loanresult']['rate'] = $rate->rate;
        $d['loanresult']['total_loan'] = round($monthly_loan, 4) * $period;
        $d['recomendeds'] = $recomendeds;
        $d['recomendpath'] = asset('storage/listings/');
        return response()->json($d, 200);
    }
}