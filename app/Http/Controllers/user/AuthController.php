<?php

namespace App\Http\Controllers\user;

use Str;
use Auth;
use Hash;
use Session;
use Validator;
use Config;
use Carbon\Carbon;
use App\Models\Log;
use App\Models\User;
use App\Models\Country;
use App\Mail\UserRegistered;
use Illuminate\Http\Request;
use App\Mail\PasswordChanged;
use App\Mail\PasswordRecoveryCode;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Mail\SendChatNotification;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function __construct()
    {
        return $this->middleware('guest.member:member')->except('logout');
    }

    public function login()
    {
        if(Session::has('_previous')) {
            $prev = Session::get('_previous');
            if(str_contains($prev['url'], 'motovago')) {
                if(str_contains($prev['url'], '/member/login')) {
                    $d['prevurl'] = null;
                } else if(str_contains($prev['url'], '/member/register')) {
                    $d['prevurl'] = null;
                } else {
                    $d['prevurl'] = $prev['url'];
                }
            } else {
                $d['prevurl'] = null;
            }
        } else {
            $d['prevurl'] = null;
        }
        return view('user.auth.login', $d);
    }

    public function loginSocial($social)
    {
        return Socialite::driver($social)->redirect();
    }

    public function tiktokLogin(Request $r)
    {
        try {
            $tt = Socialite::driver('tiktok')->user();
            $user = User::where("tiktok_id",$tt->id)->first();
            if(is_null($user)) {
                $user = new User();
                $user->user_guid = Str::uuid();
                $user->type_guid = "c8423db6-300e-42fa-a103-c5b62e388f98";
                $user->name = $tt->name;
                $user->slug = Str::slug($tt->name);
                $user->email = $tt->email;
                $user->tiktok_id = $tt->id;
                $user->token = md5(sha1($user->user_guid.'||'.$user->slug.'||'.date('Y-m-d H:i:s')));
                $user->save();

                Auth::guard('member')->login($user);

                return redirect()->route('site.selection');
            } else {
                Auth::guard('member')->login($user);

                return redirect()->route('site.selection');
            }
        } catch (\Exception $e) {
            $d['error'] = __('alert.tiktok_error');
            return redirect()->route('member.login')->with($d);
        }
    }

    public function instagramLogin(Request $r)
    {
        try {
            $in = Socialite::driver('instagram')->user();
            $user = User::where("instagram_id",$in->id)->first();
            if(is_null($user)) {
                $user = new User();
                $user->user_guid = Str::uuid();
                $user->type_guid = "c8423db6-300e-42fa-a103-c5b62e388f98";
                $user->name = $in->name;
                $user->slug = Str::slug($in->name);
                $user->instagram_id = $in->id;
                $user->token = md5(sha1($user->user_guid.'||'.$user->slug.'||'.date('Y-m-d H:i:s')));
                $user->save();

                Auth::guard('member')->login($user);

                return redirect()->route('site.selection');
            } else {
                Auth::guard('member')->login($user);

                return redirect()->route('site.selection');
            }
        } catch (\Exception $e) {
            $d['error'] = __('alert.insta_error');
            return redirect()->route('member.login')->with($d);
        }
    }

    public function googleLogin(Request $r)
    {
        try {
            $gg = Socialite::driver('google')->user();
            $user = User::where("google_id",$gg->id)->first();
            if(is_null($user)) {
                $mailcheck = User::where("email",$gg->email)->first();
                if(is_null($mailcheck)) {
                    $user = new User();
                    $user->user_guid = Str::uuid();
                    $user->type_guid = "c8423db6-300e-42fa-a103-c5b62e388f98";
                    $user->name = $gg->name;
                    $user->slug = Str::slug($gg->name);
                    $user->email = $gg->email;
                    $user->google_id = $gg->id;
                    $user->token = md5(sha1($user->user_guid.'||'.$user->email.'||'.date('Y-m-d H:i:s')));
                    $user->save();

                    Auth::guard('member')->login($user);

                    return redirect()->route('site.selection');
                } else {
                    $mailcheck->google_id = $gg->id;
                    $mailcheck->update();

                    Auth::guard('member')->login($mailcheck);
                    return redirect()->route('site.selection');
                }
            } else {
                Auth::guard('member')->login($user);

                return redirect()->route('site.selection');
            }
        } catch (\Exception $e) {
            $d['error'] = __('alert.google_error');
            return redirect()->route('member.login')->with($d);
        }
    }

    public function doLogin(Request $r)
    {
        $v = $r->validate([
            'email' => 'required|email:rfc,dns,spoof',
            'password' => 'required'
        ]);

        $checkUser = User::where('email',$r->email)->first();

        if($checkUser && $checkUser->status == 1){
            $remember = $r->has('remember_me') ? true : false;

            if (Auth::guard('member')->attempt(['email'=>$r->email,'password'=>$r->password],$remember)) {
                if($r->has('prevurl') && !is_null($r->prevurl)) {
                    return redirect($r->prevurl);
                } else {
                    if(is_null(Session::get('current_location'))) {
                        return redirect()->route('site.selection');
                    }
                    return redirect()->route('location.home',['location' => Session::get('current_location')]);
                }
            } else {
                $e['result']['error'] = __('alert.cridentials_wrong');
                return redirect()->back()->withErrors($e)->withInput(['email'=>$r->email]);
            }

        } else if($checkUser && $checkUser->status == 2) {
            $e['result']['error'] = __('alert.account_not_approved');
            return redirect()->back()->withErrors($e)->withInput(['email'=>$r->email]);
        } else {
            $e['result']['error'] = __('alert.account_not_found');
            return redirect()->back()->withErrors($e)->withInput(['email'=>$r->email]);
        }
    }

    public function register()
    {
        $d['countries'] = Country::select("country_guid","name")->where("status","1")->get();
        return view('user.auth.register', $d);
    }

    public function doRegister(Request $r)
    {
        $v = $r->validate([
            'fullname' => 'required|min:5|max:200',
            'phone' => 'required|unique:users,phone',
            'email' => 'required|unique:users,email|email:rfc,dns,spoof',
            'password' => 'required|confirmed',
            'agreement' => 'accepted',
            'data_protection' => 'accepted'
        ]);

        DB::beginTransaction();

        try {

            $user_guid = Str::uuid();
            $user = new User();
            $user->user_guid = $user_guid;
            $user->name = $r->fullname;
            $user->slug = Str::slug($r->fullname);
            $user->phone = $r->phone;
            $user->email = $r->email;
            $user->status = 1;
            $user->type_guid = 'c8423db6-300e-42fa-a103-c5b62e388f98';
            $user->password = Hash::make($r->password);
            $user->token = md5(sha1($user_guid.'||'.$user->email.'||'.date('Y-m-d H:i:s')));
            $user->save();

            $id = $user->id;

            Mail::to($user->email)->queue(new UserRegistered($user));

            Auth::guard('member')->loginUsingId($id);

            DB::commit();

            return redirect()->route('location.home',['location' => Session::get('current_location')]);

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            report($th);
            $e['result']['error'] = __('alert.check_register_cridentials');
            return redirect()->back()->withErrors($e);
        }
    }

    public function corporateRegister()
    {
        $d['countries'] = Country::select("country_guid","name")->where("status","1")->get();
        return view('user.auth.corporate_register', $d);
    }

    public function doCorporateRegister(Request $r)
    {
        $v = $r->validate([
            'fullname' => 'required|max:200',
            'phone' => 'required|unique:users,phone',
            'email' => 'required|unique:users,email|email:rfc,dns,spoof',
            'password' => 'required|confirmed',
            'country' => 'required',
            'agreement' => 'accepted',
            'data_protection' => 'accepted'
        ]);

        DB::beginTransaction();

        try {

            $user_guid = Str::uuid();
            $user = new User();
            $user->user_guid = $user_guid;
            $user->name = $r->fullname;
            $user->phone = $r->phone;
            $user->email = $r->email;
            $user->country_guid = $r->country;
            $user->status = 2;
            $user->type_guid = '5d840a0f-c539-4257-955d-a375215ea307';
            $user->password = Hash::make($r->password);
            $user->token = md5(sha1($user_guid.'||'.$user->email.'||'.date('Y-m-d H:i:s')));
            $user->save();

            $id = $user->id;

            Mail::to($user->email)->queue(new UserRegistered($user));

            DB::commit();

            return redirect()->route('member.login');

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            report($th);
            $e['result']['error'] = __('alert.check_register_cridentials');
            return redirect()->back()->withErrors($e);
        }
    }

    public function forgetPassword()
    {
        return view('user.auth.forget_password');
    }

    public function doRecovery(Request $r)
    {
        $v = $r->validate([
            'email' => 'required|exists:users,email|email:rfc,dns,spoof',
        ]);

        $recovery_code = random_int(100000, 999999);

        $user = User::where('email',$r->email)->first();

        if(!isset($user)){
            return redirect()->back();
        }

        if($user->pw_recovery_attempt >= 3){
            return redirect()->back();
        }

        if(!is_null($user->pw_recovery_code) && $user->pw_recovery_validity > Carbon::now()->timestamp){
            return redirect()->back();
        }

        $user->pw_recovery_code = $recovery_code;
        $user->pw_recovery_validity = Carbon::now()->addMinutes('15');
        $user->pw_recovery_attempt = $user->pw_recovery_attempt + 1;
        $user->update();

        Mail::to($r->email)->queue(new PasswordRecoveryCode($user));

        return redirect()->route('member.recoveryCode');
    }

    public function recoveryCode(Request $r)
    {
        if($r->has('recovery_code')){
            $v = $r->validate([
                'email' => 'required|exists:users,email|email:rfc,dns,spoof',
                'recovery_code' => 'required|exists:users,pw_recovery_code',
            ]);

            $checkUser = User::where('pw_recovery_code',$r->recovery_code)->where('email',$r->email)->first();

            if($checkUser){
                Session::put('pwr_email_address',$r->email);
                Session::put('pwr_recovery_code',$r->recovery_code);
                Session::put('pwr_user_guid',$checkUser->user_guid);

                return redirect()->route('member.setPassword');
            }


        }
        return view('user.auth.recovery_code');
    }

    public function setPassword(Request $r)
    {
        if($r->has('set_password')){
            $v = $r->validate([
                'password' => 'required|confirmed'
            ]);

            if(Session::has('pwr_user_guid')){
                $code = Session::get('pwr_recovery_code');
                $email = Session::get('pwr_email_address');
                $user_guid = Session::get('pwr_user_guid');

                $checkUser = User::where('pw_recovery_code',$code)->where('email',$email)->where('user_guid',$user_guid)->first();
                if($checkUser){
                    $checkUser->password = Hash::make($r->password);
                    $checkUser->pw_recovery_code = null;
                    $checkUser->pw_recovery_validity = null;
                    $checkUser->pw_recovery_attempt = 0;
                    $checkUser->update();

                    Mail::to($checkUser->email)->queue(new PasswordChanged($checkUser));

                    Session::forget("pwr_recovery_code");
                    Session::forget("pwr_email_address");
                    Session::forget("pwr_user_guid");

                    return redirect()->route('member.login');
                }
            }

        }
        return view('user.auth.set_password');
    }

    public function logout(Request $request)
    {
        $member_guid = Auth::guard('member')->user()->user_guid;
        $current_location = Session::get('current_location');

        $l = new Log();
        $l->log_guid = Str::uuid();
        $l->member_guid = $member_guid;
        $l->type = 'logout';
        $l->ip_address = $request->ip();
        $l->desc = 'log.member_logout';
        $l->save();

        Auth::guard('member')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Session::put('current_location', $current_location);

        return redirect()->route('member.login');
    }


}
