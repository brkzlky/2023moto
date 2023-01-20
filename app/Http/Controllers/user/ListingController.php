<?php

namespace App\Http\Controllers\user;

use DB;
use Str;
use Auth;
use Hash;
use Session;
use Validator;
use App\Models\City;
use App\Models\Brand;
use App\Models\Color;
use App\Models\State;
use App\Models\Country;
use App\Models\Listing;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Location;
use App\Models\UserType;
use App\Models\ModelTrim;
use App\Models\BrandModel;
use App\Models\ListingImage;
use Illuminate\Http\Request;
use App\Models\AttributeGroup;
use App\Models\ListingAttribute;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class ListingController extends Controller
{

    public function __construct()
    {
        return $this->middleware("auth.member:member");
    }

    //Client listings
    public function listings()
    {
        if(is_null(Session::get('current_location'))) {
            return Redirect::to('selection');
        }
        $user = Auth::guard('member')->user();
        $usertype = UserType::where("type_guid",$user->type_guid)->first();
        $listings = Listing::where("user_guid",$user->user_guid)->with("main_image","category")->withCount("favorite")->get();
        $total_listings = count($listings);
        $active_listings = 0;
        $passive_listings = 0;
        $total_views = 0;
        $favs = 0;
        $actives = [];
        $passives = [];
        foreach($listings as $li) {
            if($li->status == '1') {
                $active_listings = $active_listings + 1;
                $actives[] = $li;
            }else {
                $passive_listings = $passive_listings + 1;
                $passives[] = $li;
            }
            $favs = $favs + $li->favorite_count;
            $total_views = $total_views + $li->viewed;
        }
        $d['user'] = $user;
        $d['listings'] = $listings;
        $d['total_listings'] = $total_listings;
        $d['active_passive'] = $active_listings.' / '.$passive_listings;
        $d['total_views'] = $total_views;
        $d['favs'] = $favs;
        $d['active_listings'] = $actives;
        $d['passive_listings'] = $passives;
        $d['current_limit'] = $active_listings;
        $d['listing_limit'] = $usertype->free_listing;
        return view('user.page.listings', $d);
    }

    //Client add new listing
    public function new_listing()
    {
        $d['listing_no'] = 'New';
        return view('user.page.create_listing', $d);
    }

    //Client add new listing datas api
    public function new_listing_datas()
    {
        $d['vehicles'] = Category::whereNull("related_to")->where("status","1")->with("children")->get();
        $d['locations'] = Location::select("location_guid","currency_guid","country_guid","name_en","name_ar")->with("currency","country")->where("status","1")->get();
        $d['currencies'] = Currency::where("status","1")->get();
        $d['colors'] = Color::where("status","1")->get();
        return response()->json($d, 200);
    }

    //Client complete new listing
    public function complete_listing(Request $r)
    {
        $user = Auth::guard('member')->user();
        $usertype = UserType::where("type_guid",$user->type_guid)->first();
        $listings = Listing::where("user_guid",$user->user_guid)->with("main_image","category")->withCount("favorite")->get();

        $active_listings = 0;
        foreach($listings as $li) {
            if($li->status == '1') {
                $active_listings = $active_listings + 1;
            }
        }

        if($usertype->free_listing <= $active_listings) {
            $d['error'] = __('alert.listing_limit_reached');
            return redirect()->back()->with($d);
        }

        $validity_duration = Setting::where("slug","validity-date")->first()->setting_value;
        if($validity_duration == '' || is_null($validity_duration)) {
            $validity_duration = 30;
        }

        $location = Location::where("location_guid",$r->location_guid)->first();

        $list = new Listing();
        $list->listing_guid = Str::uuid();
        $list->user_guid = $user->user_guid;
        $list->location_guid = $r->location_guid;
        $list->category_guid = $r->category_guid;
        $list->subcategory_guid = $r->subcategory_guid;
        $list->brand_guid = $r->brand_guid;
        $list->model_guid = $r->model_guid;
        if($r->trim_guid != '' && !is_null($r->trim_guid)) {
            $list->trim_guid = $r->trim_guid;
        }
        $list->listing_no = random_int(10000000, 99999999);
        $list->name_en = $r->name_en;
        $list->slug_en = Str::slug($r->name_en);
        $list->description = $r->description;
        $list->year = $r->year;
        $list->mileage = $r->mileage;
        $list->currency_guid = $location->currency_guid;
        $list->price = $r->price;
        $list->situation = $r->situation;
        $list->listing_type = $r->listing_type;
        $list->color_guid = $r->color_guid;
        $list->fuel_type = $r->fuel_type;
        $list->country_guid = $r->country;
        $list->state_guid = $r->state;
        $list->city_guid = $r->city;
        $list->latitude = $r->latitude;
        $list->longitude = $r->longitude;
        $list->expire_at = date('Y-m-d H:i:s', strtotime('+'.$validity_duration.' days'));
        $list->save();

        foreach($r->images as $k => $im) {
            $imgs = new ListingImage();
            $imgs->image_guid = Str::uuid();
            $imgs->listing_guid = $list->listing_guid;
            $imgs->name = $im->getClientOriginalName();
            $im->move(storage_path('app/public/listings/'.$list->listing_no.'/'), $imgs->name);
            if($k == 0) {
                $imgs->is_main = '1';
            } else {
                $imgs->is_main = '0';
            }
            $imgs->save();
        }

        foreach($r->extras as $k => $extras) {
            foreach($extras as $e) {
                $attr = new ListingAttribute();
                $attr->la_guid = Str::uuid();
                $attr->listing_guid = $list->listing_guid;
                $attr->ag_guid = $k;
                $attr->attribute_guid = $e;
                $attr->save();
            }
        }

        $d['success'] = __('alert.listing_created_success');
        return redirect()->route('member.listings')->with($d);
    }

    //Client listing detail
    public function listing_detail($listing_no)
    {
        $d['listing_no'] = $listing_no;
        return view('user.page.listing_detail', $d);
    }

    //Get listing details api
    public function get_listing_details(Request $r)
    {
        $listing_no = $r->listing_no;
        $user = Auth::guard('member')->user();
        $listing = Listing::where("user_guid",$user->user_guid)->where("listing_no",$listing_no)->with("images","category","subcategory","color","location","brand","model","trim","country","state","city")->first();
        $attribute_groups = AttributeGroup::where("category_guid",$listing->category_guid)->with("attribute_info")->get();
        $listing_attributes_raw = ListingAttribute::where("listing_guid",$listing->listing_guid)->get();
        $listing_attributes = [];
        foreach($listing_attributes_raw as $lar) {
            $listing_attributes[] = $lar->attribute_guid;
        }
        $d['user'] = $user;
        $d['listing'] = $listing;
        $d['attribute_groups'] = $attribute_groups;
        $d['listing_attributes'] = $listing_attributes;
        $d['vehicles'] = Category::whereNull("related_to")->where("status","1")->with("children")->get();
        $d['locations'] = Location::select("location_guid","name_en","name_ar")->where("status","1")->get();
        $d['currencies'] = Currency::where("status","1")->get();
        $d['brands'] = Brand::where("status","1")->get();

        return response()->json($d, 200);
    }

    //Save Listing Detail
    public function edit_listing(Request $r)
    {
        $user = Auth::guard('member')->user();
        $list = Listing::where("listing_no",$r->listing_no)->where("user_guid",$user->user_guid)->first();
        if(is_null($list)) {
            $d['error'] = __('alert.listing_not_found');
            return redirect()->back()->with($d);
        }
        $list->name_en = $r->name_en;
        $list->slug_en = Str::slug($r->name_en);
        $list->description = $r->description;
        $list->currency_guid = $r->currency;
        $list->price = $r->price;
        $list->country_guid = $r->country;
        $list->state_guid = $r->state;
        $list->city_guid = $r->city;
        $list->latitude = $r->latitude;
        $list->longitude = $r->longitude;
        $list->update();

        $images = [];

        if($r->has('newphotos')) {
            foreach($r->newphotos as $np) {
                $imgs = new ListingImage();
                $imgs->image_guid = Str::uuid();
                $imgs->listing_guid = $list->listing_guid;
                $imgs->name = $np->getClientOriginalName();
                $np->move(storage_path('app/public/listings/'.$list->listing_no.'/'), $imgs->name);
                $imgs->save();

                $images[] = $imgs->image_guid;
            }
        }

        if($r->has('photos') && count($r->photos) > 0) {
            foreach($r->photos as $p) {
                $images[] = $p;
            }
        }

        ListingImage::where("listing_guid",$list->listing_guid)->whereNotIn("image_guid",$images)->delete();

        $limages = ListingImage::where("listing_guid",$list->listing_guid)->get();
        foreach($limages as $k => $lim) {
            if($k == 0) {
                $lim->is_main = '1';
                $lim->update();
            }
        }

        if($r->has('extras') && count($r->extras) > 0) {
            ListingAttribute::where("listing_guid",$list->listing_guid)->delete();
            foreach($r->extras as $k => $extras) {
                foreach($extras as $e) {
                    $attr = new ListingAttribute();
                    $attr->la_guid = Str::uuid();
                    $attr->listing_guid = $list->listing_guid;
                    $attr->ag_guid = $k;
                    $attr->attribute_guid = $e;
                    $attr->save();
                }
            }
        }


        $d['success'] = __('alert.listing_updated_success');
        return redirect()->route('member.listings')->with($d);
    }

    //Enable Listing
    public function enable_listing(Request $r)
    {
        $user = Auth::guard('member')->user();
        $usertype = UserType::where("type_guid",$user->type_guid)->first();
        $listing_limit = $usertype->free_listing;
        $current_limit = Listing::where("user_guid",$user->user_guid)->where("status","1")->count();
        if($current_limit >= $listing_limit) {
            $d['error'] = __('alert.listing_limit_reached');
            return redirect()->back()->with($d);
        }

        $list = Listing::where("listing_no",$r->listing_no)->where("user_guid",$user->user_guid)->first();
        if(is_null($list)) {
            $d['error'] = __('alert.listing_not_found');
            return redirect()->back()->with($d);
        }

        $list->status = '1';
        $list->update();

        $d['success'] = __('alert.listing_enabled_success');
        return redirect()->route('member.listings')->with($d);
    }

    //Disnable Listing
    public function disable_listing(Request $r)
    {
        $user = Auth::guard('member')->user();
        $list = Listing::where("listing_no",$r->listing_no)->where("user_guid",$user->user_guid)->first();
        if(is_null($list)) {
            $d['error'] = __('alert.listing_not_found');
            return redirect()->back()->with($d);
        }

        $list->status = '0';
        $list->update();

        $d['success'] = __('alert.listing_disabled_success');
        return redirect()->route('member.listings')->with($d);
    }

    //Delete Listing
    public function delete_listing(Request $r)
    {
        $user = Auth::guard('member')->user();
        $list = Listing::where("listing_no",$r->listing_no)->where("user_guid",$user->user_guid)->first();
        if(is_null($list)) {
            $d['error'] = __('alert.listing_not_found');
            return redirect()->back()->with($d);
        }

        $list->status = '0';
        $list->update();
        $list->delete();
        ListingAttribute::where("listing_guid",$list->listing_guid)->delete();
        ListingImage::where("listing_guid",$list->listing_guid)->delete();

        $d['success'] = __('alert.listing_deleted_success');
        return redirect()->route('member.listings')->with($d);
    }

    //Get Brands
    public function listing_brands(Request $r)
    {
        $allbrands = [];

        if($r->has('category') && !is_null($r->category)) {
            $category = $r->category;
            if($r->has('brand')) {
                $key = $r->brand;
            } else {
                $key = null;
            }
            $lang = Session::get('current_language');
            if($lang == 'ar') {
                $brands = Brand::select("brand_guid","name_ar as text")->where("status","1")->where("category_guid",$category)->where("name_ar","like","%".$key."%")->get();
            } else {
                $brands = Brand::select("brand_guid","name_en as text")->where("status","1")->where("category_guid",$category)->where("name_en","like","%".$key."%")->get();
            }

            if(count($brands) > 0) {
                foreach($brands as $br) {
                    $allbrands[] = array('id' => $br->brand_guid, 'text' => $br->text);
                }
            }
        }

        return response()->json($allbrands, 200);
    }

    //Get Brand Models
    public function listing_models(Request $r)
    {
        $allmodels = [];

        if($r->has('brand') && !is_null($r->brand)) {
            if($r->has('model')) {
                $key = $r->model;
            } else {
                $key = null;
            }
            $brand = $r->brand;
            $lang = Session::get('current_language');
            if($lang == 'ar') {
                $models = BrandModel::select("model_guid","name_ar as text")->where("brand_guid",$brand)->where("status","1")->where("name_ar","like","%".$key."%")->get();
            } else {
                $models = BrandModel::select("model_guid","name_en as text")->where("brand_guid",$brand)->where("status","1")->where("name_en","like","%".$key."%")->get();
            }

            if(count($models) > 0) {
                foreach($models as $md) {
                    $allmodels[] = array('id' => $md->model_guid, 'text' => $md->text);
                }
            }
        }

        return response()->json($allmodels, 200);
    }

    //Get Brand Model Trims
    public function listing_trims(Request $r)
    {
        $alltrims = [];

        if($r->has('model') && !is_null($r->model) && $r->has('year') && !is_null($r->year)) {
            if($r->has('trim')) {
                $key = $r->trim;
            } else {
                $key = null;
            }
            $model = $r->model;
            $year = $r->year;
            $lang = Session::get('current_language');
            if($lang == 'ar') {
                $trims = ModelTrim::select("trim_guid","name_ar as name","year")->where("model_guid",$model)->where("year",$year)->where("status","1")->where("name_ar","like","%".$key."%")->get();
            } else {
                $trims = ModelTrim::select("trim_guid","name_en as name","year")->where("model_guid",$model)->where("year",$year)->where("status","1")->where("name_en","like","%".$key."%")->get();
            }

            if(count($trims) > 0) {
                foreach($trims as $t) {
                    $alltrims[] = array('id' => $t->trim_guid, 'text' => $t->name.' ('.$t->year.')');
                }
            }
        }

        return response()->json($alltrims, 200);
    }

    //Get Countries
    public function listing_countries(Request $r)
    {
        $allcountries = [];

        if($r->has('country') && !is_null($r->country)) {
            $key = $r->country;
        } else {
            $key = null;
        }
        $countries = Country::where("status","1")->where("name","like","%".$key."%")->get();

        if(count($countries) > 0) {
            foreach($countries as $c) {
                $allcountries[] = array('id' => $c->country_guid, 'text' => $c->name, 'lat' => $c->latitude, 'lng' => $c->longitude);
            }
        }

        return response()->json($allcountries, 200);
    }

    //Get States
    public function listing_states(Request $r)
    {
        $allstates = [];

        if($r->has('country') && !is_null($r->country)) {
            $country = $r->country;
            if($r->has('state') && !is_null($r->state)) {
                $key = $r->state;
            } else {
                $key = null;
            }

            $states = State::where("country_guid",$country)->where("name","like","%".$key."%")->get();

            if(count($states) > 0) {
                foreach($states as $s) {
                    $allstates[] = array('id' => $s->state_guid, 'text' => $s->name, 'lat' => $s->latitude, 'lng' => $s->longitude);
                }
            }
        }

        return response()->json($allstates, 200);
    }

    //Get Cities
    public function listing_cities(Request $r)
    {
        $allcities = [];

        if($r->has('state') && !is_null($r->state)) {
            $state = $r->state;
            if($r->has('city') && !is_null($r->city)) {
                $key = $r->city;
            } else {
                $key = null;
            }
            $cities = City::where("state_guid",$state)->where("name","like","%".$key."%")->get();

            if(count($cities) > 0) {
                foreach($cities as $c) {
                    $allcities[] = array('id' => $c->city_guid, 'text' => $c->name, 'lat' => $c->latitude, 'lng' => $c->longitude);
                }
            }
        }

        return response()->json($allcities, 200);
    }

    //Get Listing Category Attributes
    public function listing_category_attributes(Request $r)
    {
        $all_attributes = [];
        if($r->has('category') && !is_null($r->category)) {
            $category = $r->category;
            $all_attributes = AttributeGroup::where("category_guid",$category)->with("attribute_info")->get();
        }

        return response()->json($all_attributes, 200);
    }
}
