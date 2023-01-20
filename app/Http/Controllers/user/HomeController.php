<?php

namespace App\Http\Controllers\user;

use Auth;
use App\Models\Listing;
use App\Models\UserChat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
        return $this->middleware("auth.member:member");
    }
    
    public function dashboard()
    {
        $user = Auth::guard('member')->user();
        $d['active_listing'] = Listing::where("user_guid",$user->user_guid)->where("status","1")->count();
        $d['passive_listing'] = Listing::where("user_guid",$user->user_guid)->where("status","0")->count();
        $d['messages'] = UserChat::where("user_own_guid",$user->user_guid)->orWhere("user_opposite_guid",$user->user_guid)->count();

        return view('user.page.dashboard', $d);
    }
}
