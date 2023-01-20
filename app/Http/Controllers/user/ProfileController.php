<?php

namespace App\Http\Controllers\user;

use Str;
use Auth;
use Hash;
use Validator;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function __construct()
    {
        return $this->middleware("auth.member:member");
    }

    public function profile()
    {
        $d['user'] = Auth::guard('member')->user();
        $d['countries'] = Country::where("status","1")->get();
        return view('user.page.profile', $d);
    }

    //Profile information update
    public function profile_info_update(Request $r)
    {
        $valid = Validator::make($r->all(), [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email'
        ]);

        if($valid->fails()) {
            return redirect()->back()->withErrors($valid)->with('ptab','info');
        }

        $user = Auth::guard('member')->user();
        if($user->type->id == '1') {
            $user->name = $r->name;
            $user->phone = $r->phone;
            $user->whatsapp = $r->whatsapp;
            $user->email = $r->email;
            $user->gender = $r->gender;
            $user->birthday = $r->birthday;
            $user->country_guid = $r->country;
            $user->update();
        }
        if($user->type->id == '2') {
            $user->name = $r->name;
            $user->phone = $r->phone;
            $user->whatsapp = $r->whatsapp;
            $user->email = $r->email;
            $user->country_guid = $r->country;
            if($r->hasFile('logo')) {
                $user->logo = $r->file('logo')->getClientOriginalName();
                $r->file('logo')->move(storage_path('app/public/user/'), $user->logo);
            }
            if($r->hasFile('background')) {
                $user->background = $r->file('background')->getClientOriginalName();
                $r->file('background')->move(storage_path('app/public/user/'), $user->background);
            }
            $user->description = $r->description;
            $user->update();
        }

        $d['success'] = __('alert.profile_update_success');
        $d['ptab'] = 'info';
        return redirect()->back()->with($d);
    }

    //Password change
    public function profile_pw_change(Request $r)
    {
        $valid = Validator::make($r->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed'
        ]);

        if($valid->fails()) {
            return redirect()->back()->withErrors($valid)->with('ptab','profile');
        }

        $user = Auth::guard('member')->user();
        $check = Hash::check($r->old_password, $user->password);
        if(!$check) {
            $d['ptab'] = 'profile';
            $d['error'] = __('alert.current_pw_false');
            return redirect()->back()->with($d);
        }

        $user->password = Hash::make($r->new_password);
        $user->update();

        $d['ptab'] = 'profile';
        $d['success'] = __('alert.password_success');
        return redirect()->back()->with($d);
    }
}
