<?php

namespace App\Http\Controllers\site;

use App;
use Str;
use Session;
use App\Models\Faq;
use App\Models\User;
use App\Models\Brand;
use App\Models\Policy;
use App\Models\Listing;
use App\Models\BankRate;
use App\Models\Category;
use App\Models\Location;
use App\Models\BrandModel;
use App\Models\LoanRequest;
use Illuminate\Http\Request;
use App\Models\LocationCategory;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function comingsoon()
    {
        return view('comingsoon');
    }

    public function selection()
    {
        $d['locations'] = Location::withCount('listings','location_categories')->orderBy('queue')->get();
        return view('site.page.selection',$d);
    }

    //Home Page
    public function home($location)
    {
        Session::put('current_location',$location);
        $locinfo = Location::where("subdomain",$location)->first();
        Session::put('current_location_guid',$locinfo->location_guid);
        $all_categories = LocationCategory::where("location_guid",$locinfo->location_guid)->whereHas("category", function($q) {
            $q->whereNull("related_to")->where("status","1");
        })->get();
        $categories = [];
        foreach($all_categories as $ac) {
            $rents = Listing::where("category_guid",$ac->category_guid)->where("location_guid",$locinfo->location_guid)->where("expired","0")->where("status","1")->where("listing_type","rent")->inRandomOrder()->limit(10)->get();
            $sales = Listing::where("category_guid",$ac->category_guid)->where("location_guid",$locinfo->location_guid)->where("expired","0")->where("status","1")->where("listing_type","sell")->inRandomOrder()->limit(10)->get();
            $count = Listing::where("category_guid",$ac->category_guid)->where("location_guid",$locinfo->location_guid)->where("expired","0")->where("status","1")->count();
            $ac->category['rent_listings'] = $rents;
            $ac->category['sale_listings'] = $sales;
            $ac->category['listing_count'] = $count;
            $categories[] = $ac->category;
        }

        $d['all_categories'] = $categories;
        return view('site.page.home', $d);
    }

    //Seller Detail
    public function seller_detail($location, $slug, Request $r)
    {
        Session::put('current_location',$location);
        $slugexp = explode("-",$slug);
        $id = $slugexp[count($slugexp) - 1];
        array_pop($slugexp);
        $slug = implode("-",$slugexp);

        $user = User::where("slug",$slug)->where("id",$id)->with("country")->first();
        if(is_null($user)) {
            return redirect()->back();
        }
        if($user->phone[0] == '+') {
            $phone = $user->phone;
        } else {
            if(!is_null($user->country)) {
                $phone = '+'.$user->country->phonecode.''.$user->phone;
            } else {
                $phone = $user->phone;
            }
        }

        $phone = str_replace('(','',$phone);
        $phone = str_replace(')','',$phone);
        $phone = str_replace(' ','',$phone);

        $categories = Category::whereNull("related_to")->where("status","1")->get();
        $listings = [];

        foreach($categories as $c) {
            if($r->has('filter')) {
                if($r->filter == 'year-desc') {
                    $listings[$c->slug] = Listing::where("category_guid",$c->category_guid)->where("user_guid",$user->user_guid)->where("expired","0")->where("status","1")->withCount("favorite")->orderBy("year","desc")->get();
                } else if($r->filter == 'year-asc') {
                    $listings[$c->slug] = Listing::where("category_guid",$c->category_guid)->where("user_guid",$user->user_guid)->where("expired","0")->where("status","1")->withCount("favorite")->orderBy("year","asc")->get();
                } else if($r->filter == 'mileage-desc') {
                    $listings[$c->slug] = Listing::where("category_guid",$c->category_guid)->where("user_guid",$user->user_guid)->where("expired","0")->where("status","1")->withCount("favorite")->orderBy("mileage","desc")->get();
                } else if($r->filter == 'mileage-asc') {
                    $listings[$c->slug] = Listing::where("category_guid",$c->category_guid)->where("user_guid",$user->user_guid)->where("expired","0")->where("status","1")->withCount("favorite")->orderBy("mileage","asc")->get();
                } else {
                    $listings[$c->slug] = Listing::where("category_guid",$c->category_guid)->where("user_guid",$user->user_guid)->where("expired","0")->where("status","1")->withCount("favorite")->get();
                }
            } else {
                $listings[$c->slug] = Listing::where("category_guid",$c->category_guid)->where("user_guid",$user->user_guid)->where("expired","0")->where("status","1")->withCount("favorite")->get();
            }
        }

        $d['user'] = $user;
        $d['phone'] = $phone;
        $d['categories'] = $categories;
        $d['listings'] = $listings;

        return view('site.page.seller',$d);
    }

    //Search in Listings
    public function search_in_listings(Request $r)
    {
        $search_result = [];
        if($r->has('key') && !is_null($r->key)) {
            $key = $r->key;
            $current_location = Session::get('current_location');
            $locinfo = Location::where("subdomain",$current_location)->first();
            $listings = Listing::where(function($q) use ($key) {
                $q->where("listing_no","like","%".$key."%");
                $q->orWhere("name_en","like","%".$key."%");
                $q->orWhere("name_ar","like","%".$key."%");
            })->where("location_guid", $locinfo->location_guid)->where("expired","0")->where("status","1")->with("category")->limit(20)->get();
            if(count($listings) > 0) {
                foreach($listings as $l) {
                    $name = Session::get('current_language') == 'ar' ? $l->name_ar : $l->name_en;
                    $cat = Session::get('current_language') == 'ar' ? $l->category->name_ar : $l->category->name_en;
                    $search_result[] = array(
                        "listing_no" => $l->listing_no,
                        "name" => $name,
                        "slug" => $l->listing_no,
                        "category" => $cat,
                        "catslug" => $l->category->slug
                    );
                }
            } else {
                $search_result[] = array(
                    "listing_no" => null,
                    "name" => Session::get('current_language') == 'ar' ? 'نتائج البحث غير موجودة' : 'Search results not found.',
                    "slug" => null,
                    "category" => null,
                    "catslug" => null
                );
            }

            return response()->json($search_result, 200);
        } else {
            $search_result[] = array(
                "listing_no" => null,
                "name" => Session::get('current_language') == 'ar' ? 'نتائج البحث غير موجودة' : 'Search results not found.',
                "slug" => null,
                "category" => null,
                "catslug" => null
            );
            return response()->json($search_result, 200);
        }
    }

    //Change Language
    public function change_language($language)
    {
        $languages = ['ar','en'];
        if(in_array($language,$languages)) {
            Session::put('current_locale',$language);
            App::setLocale($language);
        }

        return redirect()->back();
    }

    //Jumbotron Search Brand
    public function jumbotron_brands(Request $r)
    {
        $key = $r->key;
        $category = $r->category;
        $allbrands = [];
        $lang = Session::get('current_language');
        if($lang == 'ar') {
            $brands = Brand::select("brand_guid","name_ar as text")->where("category_guid",$category)->where("status","1")->where("name_ar","like","%".$key."%")->get();
        } else {
            $brands = Brand::select("brand_guid","name_en as text")->where("category_guid",$category)->where("status","1")->where("name_en","like","%".$key."%")->get();
        }

        if(count($brands) > 0) {
            foreach($brands as $br) {
                $allbrands[] = array('id' => $br->brand_guid, 'text' => $br->text);
            }
        }

        return response()->json($allbrands, 200);
    }

    //Jumbotron Search Brand Models
    public function jumbotron_models(Request $r)
    {
        $key = $r->key;
        $brand = $r->brand;
        $lang = Session::get('current_language');
        $allmodels = [];
        if($lang == 'ar') {
            $models = BrandModel::select("model_guid","name_ar as text")->where("brand_guid",$brand)->where("status","1")->where("name_ar","like","%".$key."%")->get();
        } else {
            $models = BrandModel::select("model_guid","name_en as text")->where("brand_guid",$brand)->where("status","1")->where("name_en","like","%".$key."%")->get();
        }

        if(count($models) > 0) {
            foreach($models as $m) {
                $allmodels[] = array('id' => $m->model_guid, 'text' => $m->text);
            }
        }

        return response()->json($allmodels, 200);
    }

    //Accept Cookie
    public function accept_cookie(Request $r)
    {
        Session::put('mtvg_cookie',true);

        return response()->json('Success', 200);
    }

    //Privacy Policy
    public function privacy_policy()
    {
        $d['policy'] = Policy::where("slug","privacy-policy")->first()->text;

        return view('site.page.policies', $d);
    }

    //Terms of Use
    public function terms_of_use()
    {
        $d['policy'] = Policy::where("slug","terms-of-use")->first()->text;

        return view('site.page.policies', $d);
    }

    //Terms of Use
    public function information_on_protection_of_personal_data()
    {
        $d['policy'] = Policy::where("slug","information-on-protection-of-personal-data")->first()->text;

        return view('site.page.policies', $d);
    }
}
