<?php

namespace App\Http\Controllers\panel;

use App\Http\Controllers\Controller;
use App\Imports\LoanImport;
use App\Models\LoanRequest;
use Illuminate\Http\Request;
use DB;
use Maatwebsite\Excel\Facades\Excel;


class LoanController extends Controller
{
    public function home()
    {
        $d['page_title'] = 'Loan Requests';
        $d['loan_requests'] = LoanRequest::get();

        return view('panel.pages.loan_requests', $d);
    }
    public function detail($loan_request_guid)
    {
        $d['loan_request'] = LoanRequest::where('loan_request_guid', $loan_request_guid)->first();

        return view('panel.pages.loan_requests_detail', $d);
    }
    public function delete(Request $r)
    {

        try {
            DB::beginTransaction();
            $l = LoanRequest::where('loan_request_guid', $r->loan_request_guid)->delete();
            DB::commit();
            return redirect()->route('admin.loan.home')->with('loanDeleteSuccess', 'loanDeleteSuccess');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('admin.loan.home')->with('loanDeleteError', 'loanDeleteError');
        }
    }

    public function example_excel(Request $r)
    {
        $a = Excel::toArray(new LoanImport, $r->file('file'));
    }
}
