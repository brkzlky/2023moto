<?php

namespace App\Http\Controllers\panel;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandModel;
use App\Models\Category;
use App\Models\Color;
use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Models\Listing;
use App\Models\ListingAttribute;
use App\Models\ListingImage;
use App\Models\Location;
use App\Models\ModelTrim;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Str;

class ListingController extends Controller
{
    public function home(Request $r)
    {
        // Categories list
        $query = Listing::query();

        // Categories list search
        if ($r->search) {
            $query->where(function ($q) use ($r) {
                $q->where('name_en', 'like', "%$r->search%")
                    ->orWhere('listing_no', 'like', "%$r->search%");
            });
        }
        if ($r->category_guid) {
            $query->where('category_guid', $r->category_guid);
        }
        if ($r->location_guid) {
            $query->where('location_guid', $r->location_guid);
        }
        if ($r->status || $r->status == '0') {
            $query->where('status', +$r->status);
        }

        $d['listings'] = $query
            ->with('category', 'location', 'user')
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $d['listing_count'] = $d['listings']->count();

        // Categories
        $query = Category::query();
        $d['categories'] = $query
            ->orderBy('id', 'DESC')
            ->get();

        // Locations
        $query = Location::query();
        $d['locations'] = $query
            ->orderBy('id', 'DESC')
            ->get();

        $d['page_title'] = 'Listings List';
        return view('panel.pages.listings', $d);
    }

    public function detail($listing_guid)
    {
        // Listing detail
        $query = Listing::query();
        $d['listing'] = $query->where('listing_guid', $listing_guid)
            ->withCount('user', 'currency', 'images', 'favorite', 'messages', 'color', 'brand', 'model', 'trim')
            ->first();

        // Attribute groups on listings category
        $category_guid = $d['listing']->category_guid;
        $d['attribute_groups'] = Category::where('category_guid', $category_guid)
            ->first()
            ->attr_groups_info()
            ->where('category_guid', $category_guid)
            ->get();

        // Attribute attached to listings
        $d['attribute_count'] = 0;
        foreach ($d['attribute_groups'] as $attribute_group) {
            foreach ($attribute_group->attribute_info as $attribute) {
                $attribute['checked'] = ListingAttribute::where('listing_guid', $listing_guid)
                    ->where('attribute_guid', $attribute->attribute_guid)
                    ->where('ag_guid', $attribute_group->ag_guid)
                    ->first() !== null ? true : false;
                if ($attribute['checked'] == true) {
                    $d['attribute_count'] += 1;
                }
            }
        }

        // Category attached to listings
        $query = Category::query();
        $d['categories'] = $query
            ->orderBy('id', 'DESC')
            ->get();

        // Location attached to listings
        $query = Location::query();
        $d['locations'] = $query
            ->orderBy('id', 'DESC')
            ->get();

        // Images attached to listings
        $query = Listing::query();
        $d['images'] = $query->where('listing_guid', $listing_guid)
            ->first()
            ->images()
            ->paginate(10, ['*'], 'images');

        //Setting
        $d['setting'] = Setting::where('slug', 'expiry_day')->first();

        // Color
        $d['colors'] = Color::orderBy('name_en', 'ASC')->get();

        // Brand
        $d['brands'] = Brand::orderBy('name_en', 'ASC')->get();

        if ($d['listing']->brand != null) {
            // Model
            $d['models'] = $d['listing']->brand->brand_models()->get();
        }

        if ($d['listing']->model != null) {
            // Trims
            $d['trims'] = $d['listing']->model->model_trims()->get();
        }

        $d['now'] = Carbon::now();
        $created_at = Carbon::parse(date('01:00'));
        $d['diffHours'] = $created_at->diffInHours($d['now']);

        $d['page_title'] = $d['listing']->name_en . ' Detail';

        return view('panel.pages.listings_detail', $d);
    }

    public function update(Request $r)
    {
        $listing = Listing::where('listing_guid', $r->listing_guid)->first();

        if ($listing) {
            try {
                if ($r->brand_guid != $listing->brand_guid) {
                    if ($r->brand_guid != null && $r->model_guid != null) {
                        $listing->brand_guid = $r->brand_guid;
                        $listing->model_guid = $r->model_guid;
                        $listing->trim_guid = $r->trim_guid;
                        $listing->year = +$r->year;
                    } else {
                        return redirect()->route('admin.listings.detail', $r->listing_guid)->with('errorUpdateListing', 'errorUpdateListing');
                    }
                }

                $listing->price = $r->price;
                $listing->name_en = $r->name_en;
                $listing->name_ar = $r->name_ar;
                $listing->status = +$r->status;
                $listing->mileage = +$r->mileage;
                $listing->color_guid = $r->color_guid;
                $listing->fuel_type = $r->fuel_type;
                $listing->situation = $r->situation;
                $listing->listing_type = $r->listing_type;
                $listing->description = $r->description;
                $listing->subcategory_guid = $r->subcategory_guid;
                $listing->latitude = $r->latitude;
                $listing->longitude = $r->longitude;

                if ($r->category_guid != $listing->category_guid) {
                    $listing_attributes = ListingAttribute::where('listing_guid', $listing->listing_guid)->get();
                    if ($listing_attributes) {
                        foreach ($listing_attributes as $listing_attribute) {
                            try {
                                DB::beginTransaction();
                                $listing_attribute->delete();
                                DB::commit();
                            } catch (\Throwable $th) {
                                DB::rollback();
                            }
                        }
                    }
                    $listing->category_guid = $r->category_guid;
                }

                if ($r->location_guid != $listing->location_guid) {
                    $location = Location::where('location_guid', $r->location_guid)->first();
                    $currency = Currency::where('currency_guid', $location->currency_guid)->first();
                    $exchange_rates = ExchangeRate::where('from', $listing->currency_guid)
                        ->where('to', $currency->currency_guid)
                        ->orderBy('id', 'DESC')
                        ->first();
                    $new_price = $exchange_rates->price * $listing->price;
                    $listing->price = $new_price;
                    $listing->location_guid = $r->location_guid;
                    $listing->currency_guid = $currency->currency_guid;
                }
                $listing->save();
                return redirect()->route('admin.listings.detail', $r->listing_guid)->with('successUpdateListing', 'successUpdateListing');
            } catch (\Throwable $th) {
                return redirect()->back();
            }
        }
        return redirect()->back();
    }

    public function expiry_day(Request $r)
    {
        $listing = Listing::where('listing_guid', $r->listing_guid)->first();

        $setting = Setting::where('slug', 'validity-date')->first();
        if ($setting) {
            try {
                DB::beginTransaction();
                $listing->expire_at = Carbon::now()->add($setting->setting_value, 'day');
                $listing->save();
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollback();
            }
            return redirect()->route('admin.listings.detail', $r->listing_guid)->with('successUpdateExpiryDay', 'successUpdateExpiryDay');
        }
    }

    public function image_delete(Request $r)
    {
        try {
            DB::beginTransaction();
            $delete = ListingImage::where('image_guid', $r->image_guid)->first();
            $delete->delete();
            DB::commit();
            return redirect()->route('admin.listings.detail', $r->listing_guid)->with('successDeleteImage', 'successDeleteImage');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('admin.listings.detail', $r->listing_guid);
        }
    }

    public function attribute_update(Request $r)
    {
        try {
            DB::beginTransaction();
            $listing_attributes = ListingAttribute::where('listing_guid', $r->listing_guid)
                ->where('ag_guid', $r->ag_guid)
                ->get();
            if ($listing_attributes) {
                foreach ($listing_attributes as $listing_attribute) {
                    $listing_attribute->delete();
                }
                if ($r->attributes_guid) {
                    foreach ($r->attributes_guid as $attribute_guid) {
                        $listing_attr = new ListingAttribute();
                        $listing_attr->la_guid = Str::uuid();
                        $listing_attr->listing_guid = $r->listing_guid;
                        $listing_attr->ag_guid = $r->ag_guid;
                        $listing_attr->attribute_guid = $attribute_guid;
                        $listing_attr->save();
                    }
                }
            }
            DB::commit();
            return redirect()->route('admin.listings.detail', $r->listing_guid)->with('successUpdateAttributeGroup' . $r->ag_guid, 'successUpdateAttributeGroup' . $r->ag_guid)->with('successUpdateAttributeGroup', 'successUpdateAttributeGroup');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('admin.listings.detail', $r->listing_guid);
        }
    }

    public function delete(Request $r)
    {
        try {
            DB::beginTransaction();
            $delete = Listing::where('listing_guid', $r->listing_guid)->first();
            $delete->delete();
            DB::commit();
            return redirect()->route('admin.listings.home')->with('successDeleteListings', 'successUpdateListings');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('admin.listings.home');
        }
    }

    public function select_brand(Request $r)
    {
        $models = BrandModel::where('brand_guid', $r->brand_guid)->get();
        return response($models);
    }

    public function select_model(Request $r)
    {
        $trims = ModelTrim::where('model_guid', $r->model_guid)
            ->where('year', $r->year)
            ->get();
        return response($trims);
    }

    public function select_category(Request $r)
    {
        $categories = Category::where('related_to', $r->category_guid)->get();
        return response($categories);
    }
}
