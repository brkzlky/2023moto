<?php

namespace App\Http\Controllers\site;

use App;
use Str;
use Auth;
use Session;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Listing;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Location;
use App\Models\UserType;
use App\Models\ModelTrim;
use App\Models\BrandModel;
use Illuminate\Http\Request;
use App\Models\AttributeGroup;
use App\Models\ListingAttribute;
use App\Http\Controllers\Controller;

class ListingController extends Controller
{
    //Category listigns
    public function listing_category($loc, $category, Request $r)
    {
        Session::put('current_location', $loc);
        $location = Location::where("subdomain", $loc)->first();
        if (is_null($location)) {
            return redirect()->back();
        }
        Session::put('current_location_guid', $location->location_guid);
        $d['category'] = Category::where("slug", $category)->first();
        if ($r->has('jtsearch')) {
            $kmmin = str_replace(" KM", "", $r->km_min);
            $kmmin = str_replace(".", "", $kmmin);
            $kmmax = str_replace(" KM", "", $r->km_max);
            $kmmax = str_replace(".", "", $kmmax);

            $filter['location'] = $location->location_guid;
            $filter['category'] = $d['category']->category_guid;
            $filter['subcategory'] = null;
            $filter['type'] = null;
            $filter['situation'] = null;
            $filter['brand'] = array('id' => $r->brand_guid, 'text' => $r->brand);
            $filter['model'] = array('id' => $r->model_guid, 'text' => $r->model);
            $filter['price'] = array('min' => null, 'max' => null);
            $filter['year'] = array('min' => $r->year_min, 'max' => $r->year_max);
            $filter['km'] = array('min' => $kmmin, 'max' => str_replace(" KM", "", $kmmax));
            $filter['color'] = array('id' => null, 'text' => null);;
            $filter['fuel_type'] = null;
            $filter['seller_type'] = array('id' => null, 'text' => null);;
            $d['jtsearch'] = $filter;
            $d['listings'] = $listings = Listing::where("status", "1")->where(function ($q) use ($r) {
                if (!is_null($r->location)) {
                    $q->where("location_guid", $r->location);
                }
                if (!is_null($r->category)) {
                    $q->where("category_guid", $r->category);
                }
                if (!is_null($r->brand_guid)) {
                    $q->where("brand_guid", $r->brand_guid);
                }
                if (!is_null($r->model_guid)) {
                    $q->where("model_guid", $r->model_guid);
                }
                if (!is_null($r->year_min)) {
                    $q->where("year", ">=", $r->year_min);
                }
                if (!is_null($r->km_min)) {
                    $q->where("mileage", ">=", $r->km_min);
                }
            })->where("expired", "0")->count();
        } else {
            $d['listings'] = Listing::where("category_guid", $category)->where("location_guid", $location)->where("status", "1")->where("expired", "0")->count();
            $d['jtsearch'] = null;
        }

        return view('site.page.listing_category', $d);
    }

    //Listing detail
    public function listing_detail($loc, $category, $id)
    {
        Session::put('current_location', $loc);
        $d['category'] = Category::where("slug", $category)->withCount("listings")->first();
        $category_attribute_groups = AttributeGroup::where("category_guid", $d['category']->category_guid)->groupBy("ag_guid")->get();
        $listing = Listing::where("listing_no", $id)->where("expired", "0")->where("status", "1")->with("images", "user.country")->first();
        if (is_null($listing)) {
            return redirect()->back();
        }
        $listing->viewed = $listing->viewed + 1;
        $listing->update();

        $listing_user_phone = null;

        if (!is_null($listing->user)) {
            if (!is_null($listing->user->phone) && $listing->user->phone[0] == '+') {
                $listing_user_phone = $listing->user->phone;
                $listing_user_whatsapp = $listing->user->whatsapp;
            } else {
                if (!is_null($listing->user->country)) {
                    $listing_user_phone = '+' . $listing->user->country->phonecode . '' . $listing->user->phone;
                    $listing_user_whatsapp = '+' . $listing->user->country->phonecode . '' . $listing->user->whatsapp;
                } else {
                    $listing_user_phone = $listing->user->phone;
                    $listing_user_whatsapp = $listing->user->whatsapp;
                }
            }

            $listing_user_phone = str_replace('(', '', $listing_user_phone);
            $listing_user_phone = str_replace(')', '', $listing_user_phone);
            $listing_user_phone = str_replace(' ', '', $listing_user_phone);

            $listing_user_whatsapp = str_replace('(', '', $listing_user_whatsapp);
            $listing_user_whatsapp = str_replace(')', '', $listing_user_whatsapp);
            $listing_user_whatsapp = str_replace(' ', '', $listing_user_whatsapp);
        }

        $checkfav = null;
        if (Auth::guard("member")->check()) {
            $userguid = Auth::guard("member")->user()->user_guid;
            $checkfav = Favorite::where("user_guid", $userguid)->where("listing_guid", $listing->listing_guid)->first();
        }

        $attribute_groups = ListingAttribute::where("listing_guid", $listing->listing_guid)->groupBy("ag_guid")->with("attribute_group")->get();
        foreach ($attribute_groups as $ag) {
            $ags = [];
            $attrs = ListingAttribute::where("listing_guid", $listing->listing_guid)->where("ag_guid", $ag->ag_guid)->with("attribute_info")->get();
            foreach ($attrs as $k => $atr) {
                if (!in_array($atr->attribute_guid, $ags)) {
                    $ags[$k]['attribute_guid'] = $atr->attribute_guid;
                    $ags[$k]['name'] = Session::get('current_language') == 'ar' ? $atr->attribute_info->name_ar : $atr->attribute_info->name_en;
                }
            }
            $ag['attributes'] = $ags;
        }
        $current_location = $loc;
        $location = Location::where("subdomain", $current_location)->with("currency")->first();
        $d['currency_label'] = is_null($location->currency) ? 'AED' : $location->currency->label;
        $d['listing'] = $listing;
        $d['listing_user_phone'] = $listing_user_phone;
        $d['listing_user_whatsapp'] = $listing_user_whatsapp;
        $d['is_fav'] = is_null($checkfav) ? false : true;
        $d['attribute_groups'] = $attribute_groups;
        return view('site.page.listing_detail', $d);
    }

    //Listing filter datas
    public function listing_filter(Request $r)
    {
        $category = $r->category;
        $location = $r->location;
        $d['category'] = Category::where("category_guid", $category)->first();
        if ($r->has('jtsearch')) {
            $f = (object)json_decode($r->filter);
            $filter['location'] = $location;
            $filter['category'] = $d['category']->category_guid;
            $filter['subcategory'] = null;
            $filter['type'] = null;
            $filter['situation'] = null;
            $filter['brand'] = array('id' => $f->brand->id, 'text' => $f->brand->text);
            $filter['model'] = array('id' => $f->model->id, 'text' => $f->model->text);
            $filter['price'] = array('min' => $f->price->min, 'max' => $f->price->max);
            $filter['year'] = array('min' => $f->year->min, 'max' => $f->year->max);
            $filter['km'] = array('min' => $f->km->min, 'max' => $f->km->max);
            $filter['color'] = array('id' => $f->color->id, 'text' => $f->color->text);
            $filter['fuel_type'] = null;
            $filter['seller_type'] = array('id' => null, 'text' => null);
            $d['filter'] = $filter;
            $listings = Listing::where("status", "1")->where(function ($q) use ($r) {
                if (!is_null($r->location)) {
                    $q->where("location_guid", $r->location);
                }
                if (!is_null($r->category)) {
                    $q->where("category_guid", $r->category);
                }
                if (!is_null($r->brand_guid)) {
                    $q->where("brand_guid", $r->brand_guid);
                }
                if (!is_null($r->model_guid)) {
                    $q->where("model_guid", $r->model_guid);
                }
                if (!is_null($r->year_min)) {
                    $q->where("year", ">=", $r->year_min);
                }
                if (!is_null($r->km_min)) {
                    $q->where("mileage", ">=", $r->km_min);
                }
            })->where("expired", "0")->with("main_image", "currency", "location")->withCount("favorite")->orderBy("created_at", "desc")->get();
        } else if ($r->has('filter')) {
            $f = (object)json_decode($r->filter);
            $filter['location'] = $r->location;
            $filter['category'] = $f->category;
            $filter['subcategory'] = $f->subcategory;
            $filter['type'] = $f->type;
            $filter['situation'] = $f->situation;
            $filter['brand'] = array('id' => $f->brand->id, 'text' => $f->brand->text);
            $filter['model'] = array('id' => $f->model->id, 'text' => $f->model->text);
            $filter['price'] = array('min' => $f->price->min, 'max' => $f->price->max);
            $filter['year'] = array('min' => $f->year->min, 'max' => $f->year->max);
            $filter['km'] = array('min' => $f->km->min, 'max' => $f->km->max);
            $filter['color'] = array('id' => $f->color->id, 'text' => $f->color->text);
            $filter['fuel_type'] = $f->fuel_type;
            $filter['seller_type'] = array('id' => $f->seller_type->id, 'text' => $f->seller_type->text);
            $d['filter'] = $filter;
            $listings = Listing::where("status", "1")->where(function ($q) use ($f) {
                if (!is_null($f->location)) {
                    $q->where("location_guid", $f->location);
                }
                if (!is_null($f->category)) {
                    $q->where("category_guid", $f->category);
                }
                if (!is_null($f->subcategory)) {
                    $q->where("subcategory_guid", $f->subcategory);
                }
                if (!is_null($f->situation)) {
                    $q->where("situation", $f->situation);
                }
                if (!is_null($f->type)) {
                    $q->where("listing_type", $f->type);
                }
                if (!is_null($f->color->id)) {
                    $q->where("color_guid", $f->color->id);
                }
                if (!is_null($f->fuel_type)) {
                    $q->where("fuel_type", $f->fuel_type);
                }
                if (!is_null($f->seller_type->id)) {
                    $q->whereHas('user', function ($qr) use ($r) {
                        $qr->where("type_guid", $f->seller_type->id);
                    });
                }
                if (!is_null($f->brand->id)) {
                    $q->where("brand_guid", $f->brand->id);
                }
                if (!is_null($f->model->id)) {
                    $q->where("model_guid", $f->model->id);
                }
                if (!is_null($f->price->min)) {
                    if (!is_null($f->price->max)) {
                        $q->whereBetween("price", [$f->price->min, $f->price->max]);
                    } else {
                        $q->where("price", ">=", $f->price->min);
                    }
                } else {
                    if (!is_null($f->price->max)) {
                        $q->where("price", "<=", $f->price->max);
                    }
                }
                if (!is_null($f->year->min)) {
                    if (!is_null($f->year->max)) {
                        $q->whereBetween("year", [$f->year->min, $f->year->max]);
                    } else {
                        $q->where("year", ">=", $f->year->min);
                    }
                } else {
                    if (!is_null($f->year->max)) {
                        $q->where("year", "<=", $f->year->max);
                    }
                }
                if (!is_null($f->km->min)) {
                    if (!is_null($f->km->max)) {
                        $q->whereBetween("mileage", [$f->km->min, $f->km->max]);
                    } else {
                        $q->where("mileage", ">=", $f->km->min);
                    }
                } else {
                    if (!is_null($f->km->max)) {
                        $q->where("mileage", "<=", $f->km->max);
                    }
                }
            })->where("expired", "0")->with("main_image", "currency", "location")->withCount("favorite")->orderBy("created_at", "desc")->get();
        } else {
            $filter['location'] = $location;
            $filter['category'] = $category;
            $filter['subcategory'] = null;
            $filter['type'] = null;
            $filter['situation'] = null;
            $filter['brand'] = array('id' => null, 'text' => null);
            $filter['model'] = array('id' => null, 'text' => null);
            $filter['price'] = array('min' => null, 'max' => null);
            $filter['year'] = array('min' => null, 'max' => null);
            $filter['km'] = array('min' => null, 'max' => null);
            $filter['color'] = array('id' => null, 'text' => null);;
            $filter['fuel_type'] = null;
            $filter['seller_type'] = array('id' => null, 'text' => null);;
            $d['filter'] = $filter;
            $listings = Listing::where("category_guid", $category)->where("location_guid", $location)->where("status", "1")->where("expired", "0")->with("main_image", "currency", "location")->withCount("favorite")->orderBy("created_at", "desc")->get();
        }

        $e['locs'] = [];
        $e['cats'] = [];
        $e['subcats'] = [];
        $e['colors'] = [];
        $e['fuels'] = [];
        $e['types'] = [];
        $e['situs'] = [];
        $e['sellers'] = [];
        $e['brands'] = [];
        $e['models'] = [];

        foreach ($listings as $l) {
            if (!in_array($l->location_guid, $e['locs'])) {
                $e['locs'][] = $l->location_guid;
            }
            if (!in_array($l->category_guid, $e['cats'])) {
                $e['cats'][] = $l->category_guid;
            }
            if (!in_array($l->subcategory_guid, $e['subcats'])) {
                $e['subcats'][] = $l->subcategory_guid;
            }
            if (!in_array($l->color_guid, $e['colors'])) {
                $e['colors'][] = $l->color_guid;
            }
            if (!in_array($l->fuel_type, $e['fuels'])) {
                $e['fuels'][] = $l->fuel_type;
            }
            if (!in_array($l->listing_type, $e['types'])) {
                $e['types'][] = $l->listing_type;
            }
            if (!in_array($l->situation, $e['situs'])) {
                $e['situs'][] = $l->situation;
            }
            if (!in_array($l->user->type->type_guid, $e['sellers'])) {
                $e['sellers'][] = $l->user->type->type_guid;
            }
            if (!in_array($l->brand_guid, $e['brands'])) {
                $e['brands'][] = $l->brand_guid;
            }
            if (!in_array($l->model_guid, $e['models'])) {
                $e['models'][] = $l->model_guid;
            }
        }

        if (count($listings) > 0) {
            $d['categories'] = Category::whereIn("category_guid", $e['cats'])->whereNull("related_to")->where("status", "1")->with("children")->get();
            $d['locations'] =  Location::whereIn("location_guid", $e['locs'])->where("status", "1")->get();
            $d['colors'] = Color::whereIn("color_guid", $e['colors'])->where("status", "1")->get();
        } else {
            $d['categories'] = Category::whereNull("related_to")->where("status", "1")->with("children")->get();
            $d['locations'] =  Location::where("status", "1")->get();
            $d['colors'] = Color::where("status", "1")->get();
        }
        $d['user_types'] = UserType::get();
        $d['available_filters'] = $e;
        $d['filter'] = $filter;
        $d['listings'] = $listings;
        return response()->json($d, 200);
    }

    //Get Brands
    public function listing_brands(Request $r)
    {
        $allbrands = [];

        if ($r->has('category') && !is_null($r->category)) {
            $category = $r->category;
            if ($r->has('brand')) {
                $key = $r->brand;
            } else {
                $key = null;
            }
            $availables = json_decode($r->ac);
            $lang = Session::get('current_language');
            if ($lang == 'ar') {
                $brands = Brand::select("brand_guid", "name_ar as text")->whereIn("brand_guid", $availables)->where("status", "1")->where("category_guid", $category)->where("name_ar", "like", "%" . $key . "%")->get();
            } else {
                $brands = Brand::select("brand_guid", "name_en as text")->whereIn("brand_guid", $availables)->where("status", "1")->where("category_guid", $category)->where("name_en", "like", "%" . $key . "%")->get();
            }

            if (count($brands) > 0) {
                foreach ($brands as $br) {
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

        if ($r->has('brand') && !is_null($r->brand)) {
            if ($r->has('model')) {
                $key = $r->model;
            } else {
                $key = null;
            }
            $brand = $r->brand;
            $availables = json_decode($r->ac);
            $lang = Session::get('current_language');
            if ($lang == 'ar') {
                $models = BrandModel::select("model_guid", "name_ar as text")->whereIn("model_guid", $availables)->where("brand_guid", $brand)->where("status", "1")->where("name_ar", "like", "%" . $key . "%")->get();
            } else {
                $models = BrandModel::select("model_guid", "name_en as text")->whereIn("model_guid", $availables)->where("brand_guid", $brand)->where("status", "1")->where("name_en", "like", "%" . $key . "%")->get();
            }

            if (count($models) > 0) {
                foreach ($models as $md) {
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

        if ($r->has('model') && !is_null($r->model) && $r->has('trim') && !is_null($r->trim) && $r->has('year') && !is_null($r->year)) {
            $key = $r->trim;
            $model = $r->model;
            $year = $r->year;
            $lang = Session::get('current_language');
            if ($lang == 'ar') {
                $trims = ModelTrim::select("trim_guid", "name_ar as name", "year")->where("model_guid", $model)->where("year", $year)->where("status", "1")->where("name_ar", "like", "%" . $key . "%")->get();
            } else {
                $trims = ModelTrim::select("trim_guid", "name_en as name", "year")->where("model_guid", $model)->where("year", $year)->where("status", "1")->where("name_en", "like", "%" . $key . "%")->get();
            }

            if (count($trims) > 0) {
                foreach ($trims as $t) {
                    $alltrims[] = array('id' => $t->trim_guid, 'text' => $t->name . ' (' . $t->year . ')');
                }
            }
        }

        return response()->json($alltrims, 200);
    }
}
