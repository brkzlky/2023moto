<?php

namespace App\Http\Controllers\panel;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Location;
use App\Models\LocationCategory;
use App\Models\LocationUserType;
use App\Models\UserType;
use Illuminate\Http\Request;
use Str;
use Validator;
use DB;
use Illuminate\Support\Facades\Storage;

class LocationController extends Controller
{
    public function home(Request $r)
    {
        // Locations list
        $d['page_title'] = 'Locations List';
        if ($r->name_en == null) {
            $d['locations'] = Location::orderBy('id', 'DESC')
                ->withCount('location_categories', 'location_user_types', 'listings')
                ->with('location_categories', 'location_user_types', 'listings')
                ->paginate(10);
        }

        if ($r->name_en) {
            $d['locations'] = Location::orderBy('id', 'DESC')
                ->where('name_en', 'like', '%' . $r->name_en . '%')
                ->withCount('location_categories', 'location_user_types', 'listings')
                ->with('location_categories', 'location_user_types', 'listings')
                ->paginate(10);
        }

        $d['countries'] = Country::where("status", "1")->orderBy('name', 'ASC')->get();

        return view('panel.pages.locations', $d);
    }

    public function add(Request $r)
    {
        // Location add if city_guid exist
        if (isset($r->city_guid)) {
            try {
                DB::beginTransaction();
                $location = new Location();
                $location->location_guid = Str::uuid();
                $location->name_en = $r->name_en;
                $location->subdomain = $r->subdomain;
                $location->country_guid = $r->country_guid;
                $location->city_guid = $r->city_guid;
                $location->save();
                DB::commit();
                return redirect()->route('admin.locations.detail', $location->location_guid)->with('successAddLocation', 'successAddLocation');
            } catch (\Throwable $th) {

                DB::rollback();
                return redirect()->back();
            }
        } else {
            // Location add if city_guid null
            try {
                DB::beginTransaction();
                $location = new Location();
                $location->location_guid = Str::uuid();
                $location->name_en = $r->name_en;
                $location->subdomain = $r->subdomain;
                $location->country_guid = $r->country_guid;
                if ($location->city_guid != null) {
                    $location->city_guid = null;
                }
                $location->save();
                DB::commit();
                return redirect()->route('admin.locations.detail', $location->location_guid)->with('successAddLocation', 'successAddLocation');
            } catch (\Throwable $th) {

                DB::rollback();
                return redirect()->back();
            }
        }
    }

    public function detail($location_guid, Request $r)
    {
        // Location with categories , Location with user types
        $location = Location::where('location_guid', $location_guid)
            ->withCount('location_categories', 'location_user_types', 'listings')
            ->with('location_categories', 'location_user_types', 'listings')
            ->first();

        // Category guid attached to the location
        $array = [];
        $array =  $location->location_categories->map(function ($cat) {
            return $cat->category_guid;
        });
        $d['location'] = $location;

        // Category guid not attached to the location
        $d['categories'] = Category::whereNotIn('category_guid', $array)->get();

        // User types guid attached to the location
        $array2 = [];
        $array2 =  $location->location_user_types->map(function ($type) {
            return $type->type_guid;
        });

        // User types guid not attached to the location
        $d['user_types'] = UserType::whereNotIn('type_guid', $array2)->get();

        // Currencies
        $d['currencies'] = Currency::get();

        // Countries
        $d['countries'] = Country::where("status", "1")->orderBy('name', 'ASC')->get();

        // City if exist
        if ($location->city_guid != null) {
            $d['cities'] = City::where('country_guid', $location->country_guid)
                ->orderBy('name', 'ASC')
                ->get();
        }

        // Location category search
        if ($r->name_en_category == null) {
            // Category attached to the location
            $d['location_categories'] = Location::where('location_guid', $location_guid)
                ->first()
                ->location_categories()
                ->paginate(10, ["*"], "category");
            $d['search_categories'] = 0;
        }

        if ($r->name_en_category) {
            // Category attached to the location
            $d['location_categories'] = Location::where('location_guid', $location_guid)
                ->first()
                ->location_categories()
                ->where('name_en', 'like', '%' . $r->name_en_category . '%')
                ->paginate(10, ["*"],  "category");
            $d['search_categories'] = 1;
        }

        // Location user types search
        if ($r->name_en_user_types == null) {
            // User types attached to the location
            $d['location_user_types'] = Location::where('location_guid', $location_guid)
                ->first()
                ->location_user_types()
                ->paginate(10, ["*"],  "user_types");
            $d['search_user_types'] = 0;
        }

        if ($r->name_en_user_types) {
            // User types attached to the location
            $d['location_user_types'] = Location::where('location_guid', $location_guid)
                ->first()
                ->location_user_types()
                ->where('name_en', 'like', '%' . $r->name_en_user_types . '%')
                ->paginate(10, ["*"],  "user_types");
            $d['search_user_types'] = 1;
        }

        // Count
        $d['locations_count'] = Location::count();

        $d['page_title'] = $location->name_en . ' Detail';

        return view('panel.pages.locations_detail', $d);
    }

    public function update(Request $r)
    {
        // Location general settings update
        $validated = Validator::make($r->all(), [
            'name_en' => 'required|min:2',
        ]);

        if ($validated->fails()) {
            return redirect()->route('admin.locations.detail', $r->location_guid)->with('errorValidate', 'errorValidate');
        } else {
            try {
                DB::beginTransaction();
                $location = Location::where('location_guid', $r->location_guid)->first();
                $location->name_en = $r->name_en;
                $location->name_ar = $r->name_ar;
                $location->status = +$r->status;
                $location->queue = +$r->queue;
                $location->currency_guid = $r->currency_guid;
                if ($r->hasFile('pc_photo')) {
                    if (file_exists('storage/images/locations/pc_photo/' . $location->pc_photo)) {
                        if ($location->pc_photo != null) {
                            Storage::delete('storage/images/locations/pc_photo/' . $location->pc_photo);
                        }
                    }
                    $file_name = $r->location_guid .  Str::slug(pathinfo($r->file('pc_photo')->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $r->pc_photo->getClientOriginalExtension();
                    $r->file('pc_photo')->move(storage_path('app/public/images/locations/pc_photo'), $file_name);
                    $location->pc_photo = $file_name;
                }
                if ($r->hasFile('mobile_photo')) {
                    if (file_exists('storage/images/locations/mobile_photo/' . $location->mobile_photo)) {
                        if ($location->mobile_photo != null) {
                            Storage::delete('public/images/locations/mobile_photo/' . $location->mobile_photo);
                        }
                    }
                    $file_name = $r->location_guid .  Str::slug(pathinfo($r->file('mobile_photo')->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $r->mobile_photo->getClientOriginalExtension();
                    $r->file('mobile_photo')->move(storage_path('app/public/images/locations/mobile_photo'), $file_name);
                    $location->mobile_photo = $file_name;
                }
                // Location city settings update if city_guid exist
                if (isset($r->city_guid)) {
                    try {
                        DB::beginTransaction();
                        $location->country_guid = $r->country_guid;
                        $location->city_guid = $r->city_guid;
                        DB::commit();
                    } catch (\Throwable $th) {
                        DB::rollback();
                        return redirect()->back();
                    }
                } else {
                    // Location country settings update if city_guid null
                    try {
                        DB::beginTransaction();
                        $location->country_guid = $r->country_guid;
                        if ($location->city_guid != null) {
                            $location->city_guid = null;
                        }
                        DB::commit();
                    } catch (\Throwable $th) {
                        DB::rollback();
                        return redirect()->back();
                    }
                }
                $location->save();
                DB::commit();
                return redirect()->route('admin.locations.detail', $r->location_guid)->with('successUpdateLocation', 'successUpdateLocation');
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->back();
            }
        }
    }

    public function delete(Request $r)
    {
        try {
            // Location delete
            DB::beginTransaction();
            $location = Location::where('location_guid', $r->location_guid)->first();
            if ($location) {
                if (file_exists('storage/images/locations/pc_photo/' . $location->pc_photo)) {
                    if ($location->pc_photo != null) {
                        Storage::delete('storage/images/locations/pc_photo/' . $location->pc_photo);
                    }
                }
                if (file_exists('storage/images/locations/mobile_photo/' . $location->mobile_photo)) {
                    if ($location->mobile_photo != null) {
                        Storage::delete('storage/images/locations/mobile_photo/' . $location->mobile_photo);
                    }
                }
            }
            $location->delete();
            DB::commit();
            return redirect()->route('admin.locations.home')->with('successDeleteLocations', 'successDeleteLocations');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('admin.locations.home');
        }
    }

    public function select_country(Request $r)
    {
        // Cities by country guid
        $cities = City::where('country_guid', $r->country_guid)
            ->orderBy('name', 'ASC')
            ->get();
        return response()->json(['cities' => $cities], 200);
    }

    // public function user_types_add(Request $r)
    // {
    //     // Adding an user types to the location
    //     if (isset($r->types_guid)) {
    //         foreach ($r->types_guid as $key => $type_guid) {
    //             try {
    //                 DB::beginTransaction();
    //                 $category = new LocationUserType();
    //                 $category->type_guid = $type_guid;
    //                 $category->location_guid = $r->location_guid;
    //                 $category->save();
    //                 DB::commit();
    //             } catch (\Throwable $th) {
    //                 DB::rollback();
    //                 return redirect()->back();
    //             }
    //         }
    //         return redirect()->route('admin.locations.detail', $r->location_guid)->with('successAddedUserType', 'successAddedUserType');
    //     }
    //     return redirect()->back();
    // }

    // public function user_types_delete(Request $r)
    // {
    //     // Deleted an user types to the location
    //     if (isset($r->types_guid)) {
    //         foreach ($r->types_guid as $type_guid) {
    //             try {
    //                 DB::beginTransaction();
    //                 LocationUserType::where('type_guid', $type_guid)->where('location_guid', $r->location_guid)->delete();
    //                 DB::commit();
    //             } catch (\Throwable $th) {
    //                 DB::rollback();
    //                 return redirect()->back();
    //             }
    //         }
    //         return redirect()->route('admin.locations.detail', $r->location_guid)->with('successDeletedUserType', 'successDeletedUserType');
    //     }
    //     return redirect()->back();
    // }

    public function categories_add(Request $r)
    {
        // Adding an categories to the location
        if (isset($r->categories_guid)) {
            foreach ($r->categories_guid as $key => $category_guid) {
                try {
                    DB::beginTransaction();
                    $category = new LocationCategory();
                    $category->category_guid = $category_guid;
                    $category->location_guid = $r->location_guid;
                    $category->save();
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollback();
                    return redirect()->back();
                }
            }
            return redirect()->route('admin.locations.detail', $r->location_guid)->with('successAddedCategory', 'successAddedCategory');
        }
        return redirect()->back();
    }

    public function categories_delete(Request $r)
    {
        // Deleted an categories to the location
        if (isset($r->categories_guid)) {
            foreach ($r->categories_guid as $category_guid) {
                try {
                    DB::beginTransaction();
                    LocationCategory::where('category_guid', $category_guid)->where('location_guid', $r->location_guid)->delete();
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollback();
                    return redirect()->back();
                }
            }
            return redirect()->route('admin.locations.detail', $r->location_guid)->with('successDeletedCategory', 'successDeletedCategory');
        }
        return redirect()->back();
    }
}
