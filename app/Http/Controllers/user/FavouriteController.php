<?php

namespace App\Http\Controllers\user;

use Str;
use Auth;
use Hash;
use Validator;
use App\Models\Listing;
use App\Models\Favorite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FavouriteController extends Controller
{
    public function __construct()
    {
        return $this->middleware("auth.member:member");
    }

    //User favourite listings
    public function favourites()
    {
        $user = Auth::guard('member')->user();
        $favourites = Favorite::where("user_guid",$user->user_guid)->has('listing')->get();
        $d['user'] = $user;
        $d['favorites'] = $favourites;
        return view('user.page.favourites', $d);
    }

    //Delete favourite listings
    public function delete(Request $r)
    {
        $user = Auth::guard('member')->user();
        $listing = Favorite::where("listing_guid",$r->listing_guid)->where("user_guid",$user->user_guid)->first();
        if(is_null($listing)) {
            $d['error'] = __('alert.fav_listing_not_found');
            return redirect()->back()->with($d);
        }

        $listing->delete();

        $d['success'] = __('alert.fav_listing_deleted_success');
        return redirect()->back()->with($d);
    }

    //Add to fav API
    public function add_to_fav(Request $r)
    {
        if(!Auth::guard('member')->check()) {
            $d['result'] = 403;
            $d['msg'] = __('alert.not_authorized_person');
            return response()->json($d, 403);
        }

        $listing = Listing::where("listing_no",$r->listing)->where("status","1")->where("expired","0")->first();
        if(is_null($listing)) {
            $d['result'] = 400;
            $d['msg'] = __('alert.listing_not_found');
            return response()->json($d, 400);
        }

        $userguid = Auth::guard('member')->user()->user_guid;

        $checkfav = Favorite::where("listing_guid",$listing->listing_guid)->where("user_guid",$userguid)->first();
        if(!is_null($checkfav)) {
            $d['result'] = 400;
            $d['msg'] = __('alert.fav_exist_msg');
            return response()->json($d, 400);
        }

        $fav = new Favorite();
        $fav->favorite_guid = Str::uuid();
        $fav->listing_guid = $listing->listing_guid;
        $fav->user_guid = $userguid;
        $fav->save();
        
        $d['result'] = 200;
        $d['msg'] = __('alert.fav_add_success');
        return response()->json($d, 200);
    }

    //Remove from fav API
    public function remove_from_fav(Request $r)
    {
        if(!Auth::guard('member')->check()) {
            $d['result'] = 403;
            $d['msg'] = __('alert.not_authorized_person');
            return response()->json($d, 403);
        }

        $listing = Listing::where("listing_no",$r->listing)->where("status","1")->where("expired","0")->first();
        if(is_null($listing)) {
            $d['result'] = 400;
            $d['msg'] = __('alert.listing_not_found');
            return response()->json($d, 400);
        }

        $userguid = Auth::guard('member')->user()->user_guid;

        $checkfav = Favorite::where("listing_guid",$listing->listing_guid)->where("user_guid",$userguid)->first();
        if(is_null($checkfav)) {
            $d['result'] = 400;
            $d['msg'] = __('alert.fav_not_exist_msg');
            return response()->json($d, 400);
        }

        $checkfav->delete();
        
        $d['result'] = 200;
        $d['msg'] = __('alert.fav_remove_success');
        return response()->json($d, 200);
    }
}
