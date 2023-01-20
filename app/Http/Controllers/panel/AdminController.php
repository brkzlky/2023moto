<?php

namespace App\Http\Controllers\panel;

use Str;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class AdminController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth.admin:admin');
    }

    public function home(Request $r)
    {

        $query = Admin::query();
        if ($r->name) {
            $d['admins'] = $query->OrderBy('id', 'ASC')->where('name', 'like', '%' . $r->name . '%')->paginate(10);
        } elseif (is_null($r->name)) {
            $d['admins'] = $query->orderBy('id', 'ASC')->paginate(10);
        }

        $d['page_title'] = 'Admins';
        return view('panel.pages.admin', $d);
    }
    public function add(Request $r)
    {
        $validated = Validator::make($r->all(), [
            'name' => 'required|min:3',
            'username' => 'required|min:3|unique:admins',
            'password' => 'required|min:5',
            'type' => 'required',
            'email' => 'required|email|unique:admins'
        ]);
        if ($validated->fails()) {
            return redirect()->route('admin.admin.home')->with('errorValidate', 'errorValidate');
        } else {
            try {
                DB::beginTransaction();
                $n = new Admin();
                $n->name = $r->name;
                $n->email = $r->email;
                $n->username = $r->username;
                $a = Hash::make($r->password);
                $n->password = $a;
                $n->type = $r->type;

                $n->save();
                DB::commit();
                return redirect()->route('admin.admin.home')->with('successAdminAdd', 'successAdminAdd');
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->route('admin.admin.home')->with('errorAdminAdd', 'errorAdminAdd');
            }
        }
    }
    public function delete(Request $r)
    {
        try {
            DB::beginTransaction();
            $d = Admin::where('id', $r->id)->delete();
            DB::commit();
            return redirect()->route('admin.admin.home')->with('successDeleteAdmin', 'successDeleteAdmin');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('admin.admin.home')->with('errorDeleteAdmin', 'errorDeleteAdmin');
        }
    }
    public function detail($username)
    {
        $d['admin'] = Admin::where('username', $username)->first();
        return view('panel.pages.admin_detail', $d);
    }
    public function update_admin(Request $r)
    {
        $validated = Validator::make($r->all(), [
            'name' => 'required|min:3',
            'username' => 'required|min:3',
            'email' => 'required|email'
        ]);
        if ($validated->fails()) {
            return redirect()->route('admin.admin.detail', $r->username)->with('errorAdminUpdate', 'errorAdminUpdate');
        } else {
            try {
                DB::beginTransaction();
                $update = Admin::where('id', $r->id)->first();
                $update->name=$r->name;
                $update->username=$r->username;
                $update->email=$r->email;
                $update->update();
                DB::commit();
                return redirect()->route('admin.admin.detail', $r->username)->with('successAdminUpdate', 'successAdminUpdate');
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->route('admin.admin.detail', $r->username)->with('errorAdminUpdate', 'errorAdminUpdate');
            }
        }
    }
    public function update_admin_password(Request $r)
    {

        $d['admin']=Admin::where('id',$r->id)->first();
        $chck=Hash::check($r->old_password, $d['admin']->password);

        if ($chck==true && $r->new_password===$r->re_new_password) {

            DB::beginTransaction();
            $d['admin']->password=Hash::make($r->new_password);
            $d['admin']->update();
            DB::commit();
            return redirect()->route('admin.admin.detail',$d['admin']->username)->with('adminPasswordSuccess','adminPasswordSuccess');
        }else {
            return redirect()->route('admin.admin.detail',$d['admin']->username)->with('adminPasswordError','adminPasswordError');
        }
    }
}
