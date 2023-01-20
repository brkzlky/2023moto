<?php

namespace App\Http\Controllers\panel;

use App\Models\Bank;
use App\Models\BankRate;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
class BankManagement extends Controller
{
    public function home(Request $r)
    {
        $d['page_title'] = 'Bank Management';
        $query = Bank::query();

        if ($r->name) {
            $d['banks'] = $query->OrderBy('id', 'DESC')->where('name', 'like', '%' . $r->name . '%')
                ->paginate(10);
        } else if ($r->name == null) {
            $d['banks'] = $query->OrderBy('id', 'DESC')
                ->paginate(10);
        }

        return view('panel.pages.bank_management', $d);
    }
    public function add(Request $r)
    {
        $validated = Validator::make($r->all(), [
            'name' => 'required|min:2|unique:banks,name',
        ]);
        if ($validated->fails()) {
            return redirect()->route('admin.bank_management.home')->with('errorValidate', 'errorValidate');
        } else {
            try {
                DB::beginTransaction();
                $n = new Bank();
                $n->bank_guid = Str::uuid();
                $n->name = $r->name;
                $n->slug = Str::slug($r->name);
                $n->status = '1';
                $n->save();
                DB::commit();
                return redirect()->route('admin.bank_management.detail', $n->slug);
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->route('admin.bank_management.home')->with('errorValidate', 'errorValidate');
            }
        }
    }
    public function detail($slug)
    {
        $d['page_title']='Bank Detail';
        $d['bank'] = Bank::Where('slug', $slug)->first();
        $d['rates']=BankRate::where('bank_guid',$d['bank']->bank_guid)->get();
        return view('panel.pages.bank_management_detail', $d);
    }
    public function update(Request $r)
    {
        try {
            $u = Bank::where('bank_guid', $r->bank_guid)->first();
            $u->name = $r->name;
            $u->slug = Str::slug($r->name);
            if (!is_null($r->logo)) {
                $file_name = $r->file('logo')->getClientOriginalName();
                $r->file('logo')->move(storage_path('app/public/bank_logos/'), $file_name);
                $u->logo = $file_name;
                $u->update();
                return redirect()->route('admin.bank_management.detail', $u->slug)->with('successBankUpdate', 'successBankUpdate');
            } else {
                $u->update();
                return redirect()->route('admin.bank_management.detail', $u->slug)->with('successBankUpdate', 'successBankUpdate');
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorBankUpdate', 'errorBankUpdate');
        }
    }
    public function delete(Request $r)
    {

        try {
            DB::beginTransaction();
            $delete = Bank::where('bank_guid', $r->bank_guid)->first();
            $delete->delete();
            DB::commit();
            return redirect()->route('admin.bank_management.home')->with('successBankDelete', 'successBankDelete');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('admin.bank_management.home');
        }
    }
    public function rate_add(Request $r)
    {
        $validated = Validator::make($r->all(), [
            'rate' => 'required',
            'period' =>'required'
        ]);
        if ($validated->fails()) {
            return redirect()->back()->with('errorRateAdd', 'errorRateAdd');
        }else{
            try {
                DB::beginTransaction();
                $n=new BankRate();
                $n->rate_guid=Str::uuid();
                $n->bank_guid=$r->bank_guid;
                $n->rate=$r->rate;
                $n->period=$r->period;
                $n->period_type=$r->period_type;
                $n->status=$r->status;
                $n->save();
                DB::commit();
                return redirect()->back()->with('rateSuccess','rateSuccess');
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->back()->with('rateError','rateError');
            }
        }
    }
    public function rate_delete(Request $r)
    {

        try {
            DB::beginTransaction();
            $delete = BankRate::where('rate_guid', $r->rate_guid)->first();
            $delete->delete();
            DB::commit();
            return redirect()->route('admin.bank_management.detail',$r->slug)->with('rateDelete', 'rateDelete');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('admin.bank_management.detail',$r->slug)->with('rateDeleteError','rateDeleteError');
        }
    }
}
