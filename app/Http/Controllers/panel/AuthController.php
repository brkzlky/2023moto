<?php

namespace App\Http\Controllers\panel;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Auth;
use Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        return $this->middleware('guest.admin:admin')->except('logout');
    }

    public function login()
    {
        return view('panel.modules.auth.login');
    }

    public function check_login(Request $r)
    {

        // User And Password Validate Check

        $rules = [
            'user' => 'required',
            'password' => 'required',
        ];

        $validate =  Validator::make($r->all(), $rules);
        if ($validate->fails()) {
            return redirect()->route('admin.login')->with('errorBlank', 'errorBlank');
        }


        // Mail And Password Exist Check

        $checkAdminMail = Admin::where('email', $r->user)->first();
        if ($checkAdminMail) {

            if (Auth::guard('admin')->attempt(['email' => $r->user, 'password' => $r->password], true)) {
                return redirect()->route('admin.dashboard')->with('success', 'success');
            } else {
                return redirect()->route('admin.login')->with('errorPassword', 'errorPassword');
            }
        } else {

            // Username And Password Exist Check

            $checkAdminUserName = Admin::where('username', $r->user)->first();
            if ($checkAdminUserName) {

                if (Auth::guard('admin')->attempt(['username' => $r->user, 'password' => $r->password], true)) {
                    return redirect()->route('admin.dashboard')->with('success', 'success');
                } else {
                    return redirect()->route('admin.login')->with('errorPassword', 'errorPassword');
                }
            } else {
                return redirect()->route('admin.login')->with('errorInfo', 'errorInfo');
            }
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        return redirect()->route('admin.login');
    }
}
