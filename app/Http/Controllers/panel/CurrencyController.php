<?php

namespace App\Http\Controllers\panel;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use DB;
use Str;

class CurrencyController extends Controller
{
    public function home()
    {
        $d['currencies'] = Currency::get();
        $d['page_title'] = 'Currencies';
        return view('panel.pages.currency', $d);
    }
    public function detail($currency_guid)
    {
        $d['currency'] = Currency::where('currency_guid', $currency_guid)->with('rates.to_name','rates.from_name')->first();
        $a=$d['currency']['rates'][0]->validity_date;
        $s=explode((' '),$a);
        $d['validity_date']=$s[0];
        return view('panel.pages.currency_detail', $d);
    }
    public function update(Request $r)
    {
        $validated = Validator::make($r->all(), [
            'name' => 'required',
            'label' => 'required',
            'symbol' => 'required',
            'status' => 'required',

        ]);
        if ($validated->fails()) {
            return redirect()->back()->with('errorCurrencyValidate', 'errorCurrencyValidate');
        } else {
            $update = Currency::where('currency_guid', $r->currency_guid)->first();
            try {
                DB::beginTransaction();
                $update->name = $r->name;
                $update->label = $r->label;
                $update->symbol = $r->symbol;
                $update->status = $r->status;
                $update->update();
                DB::commit();
                return redirect()->route('admin.currency.detail', $update->currency_guid)->with('successUpdateCurrency', 'successUpdateCurrency');
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->route('admin.currency.detail', $r->currency_guid)->with('errorUpdateCurrency', 'errorUpdateCurrency');
            }
        }
    }
}
