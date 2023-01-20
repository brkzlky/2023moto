<?php

namespace App\Http\Controllers\api;

use DB;
use Carbon\Carbon;
use App\Models\City;
use App\Models\User;
use App\Models\Brand;
use App\Models\Color;
use App\Models\State;
use App\Models\Country;
use App\Models\Listing;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Favorite;
use App\Models\Location;
use App\Models\UserType;
use App\Models\ModelTrim;
use App\Models\BrandModel;
use Illuminate\Support\Str;
use App\Models\ListingImage;
use Illuminate\Http\Request;
use App\Models\AttributeGroup;
use App\Models\ListingAttribute;
use App\Models\LocationCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;



class ListingController extends Controller
{
    /** @OA\Info(title="Front API", version="0.1") */
    /**
     * @OA\POST(

     *  path="api.motovago.com/categories",

     *  operationId="categories",

     *  summary="Gets all categories in database",
     * @OA\Parameter(
     *    description="location_guid",
     *    in="query",
     *    name="location_guid",
     *    required=true,
     *    example="123123-4123141-23123123",
     * ),
     * @OA\Response( response=200,description="Success",
     *  @OA\JsonContent(
     *      @OA\Property(property="status", type="string", example="200"),
     *      @OA\Property(property="categories", type="array",
     *    @OA\Items(type="object", format="query",
     *      @OA\Property(property="id", type="string", example="12"),
     *      @OA\Property(property="category_guid", type="string", example="d4adbd65-9903-4df0-aaaf-2aa491e99123"),
     *      @OA\Property(property="name_en", type="string", example="Cars"),
     *      @OA\Property(property="name_ar", type="string", example="سيارات"),
     *      @OA\Property(property="slug", type="string", example="cars"),
     *      @OA\Property(property="image", type="string", example="https://api.motovago.test/storage/images/locations/pc_photo"),
     *      @OA\Property(property="queue", type="string", example="1"),
     *      @OA\Property(property="status", type="string", example="1"),
     *  @OA\Property(property="rent_listings", type="array",
     *    @OA\Items(type="object",format="query",
     *      @OA\Property(property="id", type="string", example="5"),
     *      @OA\Property(property="listing_guid", type="string", example="a43013a8-dd46-4055-b9df-9024c1092169"),
     *      @OA\Property(property="category_guid", type="string", example="a43013a8-4055-b9df-9024c1092169"),
     *      @OA\Property(property="subcategory_guid", type="string", example="232323-4055-b9df-9024c1092169"),
     *      @OA\Property(property="listing_no", type="string", example="47382643"),
     *      @OA\Property(property="price", type="string", example="500.000"),
     *     @OA\Property(property="main_image_api", type="array",
     *    @OA\Items(type="object",format="query",
     *      @OA\Property(property="listing_guid", type="string", example="6f1ceeac-9719-43ec-9403-b4f8bbbc0adbc"),
     *      @OA\Property(property="name", type="string", example="https://motovago.com/storage/listings/78603483/2.jpeg"),
     * ))
     *
     * )),
     * @OA\Property(property="sale_listings", type="array",
     *    @OA\Items(type="object",format="query",
     *      @OA\Property(property="id", type="string", example="6"),
     *      @OA\Property(property="listing_guid", type="string", example="232332-dd46-4055-b9df-9024c1092169"),
     *      @OA\Property(property="category_guid", type="string", example="121212-4055-b9df-9024c1092169"),
     *      @OA\Property(property="subcategory_guid", type="string", example="4141414-4055-b9df-9024c1092169"),
     *      @OA\Property(property="name_en", type="string", example="BORUSAN ÇIKIŞLI 2018 MODEL RANGE ROVER SPORT HATASIZ 69000 KM"),
     *      @OA\Property(property="name_ar", type="string", example="null"),
     *      @OA\Property(property="listing_no", type="string", example="11111"),
     *      @OA\Property(property="price", type="string", example="233.000"),
     *    @OA\Property(property="main_image_api", type="array",
     *    @OA\Items(type="object",format="query",
     *      @OA\Property(property="listing_guid", type="string", example="6f1ceeac-9719-43ec-9403-b4f8bbbc0adc"),
     *      @OA\Property(property="name", type="string", example="https://motovago.com/storage/listings/78603483/1.jpeg"),
     * ))
     * )),
     *      @OA\Property(property="listing_count", type="string", example="8"),
     * )
     *),
     *  ),
     *     )
     * )
     *
     */

    public function getCategories(Request $r)
    {
        if ($r->has('location_guid')) {
            $location = $r->location_guid;
            $all_categories = LocationCategory::where("location_guid", $location)->whereHas("category", function ($q) {
                $q->whereNull("related_to")->where("status", "1")->withCount('listings');
            })->get();
            $categories = [];
            foreach ($all_categories as $ac) {
                $rents = Listing::where("category_guid", $ac->category_guid)->where("location_guid", $location)->where("expired", "0")->where("status", "1")->where("listing_type", "rent")->with('main_image_api', 'currency')->inRandomOrder()->limit(10)->get();
                $sales = Listing::where("category_guid", $ac->category_guid)->where("location_guid", $location)->where("expired", "0")->where("status", "1")->where("listing_type", "sell")->with('main_image_api', 'currency')->inRandomOrder()->limit(10)->get();
                foreach ($rents as $re) {
                    if (!is_null($re['main_image_api'])) {
                        $re['main_image_api']['name'] = config('api.main_url') . '/storage/listings/' . $re->listing_no . '/' . $re->main_image_api->name;
                    } else {
                        continue;
                    }
                }
                foreach ($sales as $sa) {
                    if (!is_null($sa['main_image_api'])) {
                        $sa['main_image_api']['name'] = config('api.main_url') . '/storage/listings/' . $sa->listing_no . '/' . $sa->main_image_api->name;
                    } else {
                        continue;
                    }
                }
                $ac->category['rent_listings'] = $rents;
                $ac->category['sale_listings'] = $sales;
                $count = Listing::where("category_guid", $ac->category_guid)->where("location_guid", $location)->where("expired", "0")->where("status", "1")->count();

                $ac->category['listing_count'] = $count;
                $ac->category['image'] = config('api.main_url') . '/storage/images/categories/' . $ac->category->image;
                $categories[]  = $ac->category;
            }

            $d['categories'] = $categories;

            $d['status'] = 200;
            return response()->json($d, 200);
        } else {
            $d['msg'] = 'Wrong parameter sended please try again.';
            $d['status'] = 400;
            return response()->json($d, 400);
        }
    }

    /**
     * @OA\Post(
     *  path="api.motovago.com/brands",
     *  operationId="brands",
     *  summary="Gets all brands in database",
     *     @OA\Parameter(
     *    description="category_guid",
     *    in="query",
     *    name="category_guid",
     *    required=true,
     *    example="123123-41232323-23123123",
     * ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *   @OA\Property(property="brands", type="array",
     *    @OA\Items(type="object", format="query",
     *       @OA\Property(property="name_en", type="string", example="Ferrari"),
     *        )))
     *     ),
     *  @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="Wrong parameter sended please try again."),
     *
     *        )
     *     ),
     *
     * )
     *
     */

    public function getBrands(Request $r)
    {
        if ($r->has('category_guid')) {
            $category = $r->category_guid;
            $d['brands'] = Brand::where('category_guid', $category)->where('status', '1')->get();
            $d['status'] = 200;
            return response()->json($d, 200);
        } else {
            $d['status'] = 400;
            $d['msg'] = 'Wrong parameters sended please try again.';
            return response()->json($d, 400);
        }
    }
    /**
     * @OA\Post(
     *  path="api.motovago.com/category-listings",
     *  operationId="category-listings",
     *  summary="Gets all category listings related to location guid ",
     *     @OA\Parameter(
     *    description="category_guid",
     *    in="query",
     *    name="category_guid",
     *    required=true,
     *    example="123123-41232323-23123123",
     * ),
     *     @OA\Parameter(
     *    description="location_guid",
     *    in="query",
     *    name="location_guid",
     *    required=true,
     *    example="1231223-41232323-23123123",
     * ),
     *  @OA\Parameter(
     *    description="subcategory_guid",
     *    in="query",
     *    name="subcategory_guid",
     *    required=false,
     *    example="12314144-141414-4124124",
     * ),
     *  @OA\Parameter(
     *    description="listing_type",
     *    in="query",
     *    name="listing_type",
     *    required=false,
     *    example="sell/rent",
     * ),
     * @OA\Parameter(
     *    description="situation",
     *    in="query",
     *    name="situation",
     *    required=false,
     *    example="used/new",
     * ),
     * @OA\Parameter(
     *    description="brand_guid",
     *    in="query",
     *    name="brand_guid",
     *    required=false,
     *    example="brand_guid",
     * ),
     * @OA\Parameter(
     *    description="model_guid",
     *    in="query",
     *    name="model_guid",
     *    required=false,
     *    example="model_guid",
     * ),
     * @OA\Parameter(
     *    description="price_min",
     *    in="query",
     *    name="price_min",
     *    required=false,
     *    example="23.000",
     * ),
     * @OA\Parameter(
     *    description="price_max",
     *    in="query",
     *    name="price_max",
     *    required=false,
     *    example="1200.000",
     * ),
     * @OA\Parameter(
     *    description="year_min",
     *    in="query",
     *    name="year_min",
     *    required=false,
     *    example="2005",
     * ),
     * @OA\Parameter(
     *    description="year_max",
     *    in="query",
     *    name="year_max",
     *    required=false,
     *    example="2010",
     * ),
     * @OA\Parameter(
     *    description="km_min",
     *    in="query",
     *    name="km_min",
     *    required=false,
     *    example="120000",
     * ),
     * @OA\Parameter(
     *    description="km_max",
     *    in="query",
     *    name="km_max",
     *    required=false,
     *    example="300000",
     * ),
     * @OA\Parameter(
     *    description="color_guid",
     *    in="query",
     *    name="color_guid",
     *    required=false,
     *    example="color_guid",
     * ),
     * @OA\Parameter(
     *    description="fuel_type",
     *    in="query",
     *    name="fuel_type",
     *    required=false,
     *    example="gasoline/diesel/lpg/electric",
     * ),
     * @OA\Parameter(
     *    description="seller_type",
     *    in="query",
     *    name="seller_type",
     *    required=false,
     *    example="seller_type",
     * ),
     *  @OA\Parameter(
     *    description="quick_filter",
     *    in="query",
     *    name="quick_filter",
     *    required=false,
     *    example="yes/no",
     * ),
     * @OA\Parameter(
     *    description="filtered",
     *    in="query",
     *    name="filtered",
     *    required=false,
     *    example="yes/no",
     * ),
     *
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="listing_guid", type="string", example="0d59fc2a-2357-11ec-9621-0242ac130002"),
     *       @OA\Property(property="listing_no", type="string", example="02523718"),
     *       @OA\Property(property="price", type="string", example="24000.0000"),
     *       @OA\Property(property="currency_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d"),
     *       @OA\Property(property="category_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae3555d"),
     *       @OA\Property(property="location_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae39d"),
     *       @OA\Property(property="user_guid", type="string", example="0cc869aa-3b01-4abb-8490-a25d"),
     *   @OA\Property(property="main_image_api", type="array",
     *    @OA\Items(type="object",format="query",
     *      @OA\Property(property="listing_guid", type="string", example="6f1ceeac-9719-43ec-9403-b4f8bbbc0adc"),
     *      @OA\Property(property="name", type="string", example="https://motovago.com/storage/listings/78603483/1.jpeg"),
     * )),
     *        )
     *     ),
     *  @OA\Response(
     *    response=400,
     *    description="error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="There are no listings to show"),
     *        )
     *     )
     *
     * )
     *
     */

    public function getCategoryListings(Request $r)
    {

        if ($r->has("filtered") && $r->filtered == 'yes') {
            $filter['location'] = $r->location_guid;
            $filter['category'] = $r->category_guid;
            $filter['subcategory'] = $r->subcategory_guid;
            $filter['type'] = $r->listing_type;
            $filter['situation'] = $r->situation;
            $filter['brand'] = $r->brand_guid;
            $filter['model'] = $r->model_guid;
            $filter['priceMin'] = $r->price_min;
            $filter['priceMax'] = $r->price_max;
            $filter['yearMin'] = $r->year_min;
            $filter['yearMax'] = $r->year_max;
            $filter['kmMin'] = $r->km_min;
            $filter['kmMax'] = $r->km_max;
            $filter['color'] = $r->color;
            $filter['fuelType'] = $r->fuel_type;
            $filter['sellerType'] = $r->seller_type;
        } elseif ($r->has('quick_filter') && $r->quick_filter == 'yes') {
            $filter['location'] = $r->location_guid;
            $filter['category'] = $r->category_guid;
            $filter['subcategory'] = null;
            $filter['type'] = null;
            $filter['situation'] = null;
            $filter['brand'] = $r->brand_guid;
            $filter['model'] = $r->model_guid;
            $filter['priceMin'] = null;
            $filter['priceMax'] = null;
            $filter['yearMin'] = $r->year_min;
            $filter['yearMax'] = $r->year_max;
            $filter['kmMin'] = $r->km_min;
            $filter['kmMax'] = $r->km_max;
            $filter['color'] = null;
            $filter['fuelType'] = null;
            $filter['sellerType'] = null;
        } else {
            $filter['location'] = $r->location_guid;
            $filter['category'] = $r->category_guid;
            $filter['subcategory'] = null;
            $filter['type'] = null;
            $filter['situation'] = null;
            $filter['brand'] = null;
            $filter['model'] = null;
            $filter['priceMin'] = null;
            $filter['priceMax'] = null;
            $filter['yearMin'] = null;
            $filter['yearMax'] = null;
            $filter['kmMin'] = null;
            $filter['kmMax'] = null;
            $filter['color'] = null;
            $filter['fuelType'] = null;
            $filter['sellerType'] = null;
        }


        $listings = Listing::where('status', 1)->where(function ($q) use ($filter) {
            if (!is_null($filter["category"])) {
                $q->where('category_guid', $filter["category"]);
            }
            if (!is_null($filter["location"])) {
                $q->where('location_guid', $filter["location"]);
            }
            if (!is_null($filter["subcategory"])) {
                $q->where("subcategory_guid", $filter["subcategory"]);
            }
            if (!is_null($filter["situation"])) {
                $q->where("situation", $filter["situation"]);
            }
            if (!is_null($filter["type"])) {
                $q->where("listing_type", $filter["type"]);
            }
            if (!is_null($filter["color"])) {
                $q->where("color_guid", $filter["color"]);
            }
            if (!is_null($filter["fuelType"])) {
                $q->where("fuel_type", $filter["fuelType"]);
            }
            if (!is_null($filter["sellerType"])) {
                $q->whereHas('user', function ($qr) use ($r) {
                    $qr->where("type_guid", $filter["sellerType"]);
                });
            }
            if (!is_null($filter["brand"])) {
                $q->where("brand_guid", $filter["brand"]);
            }
            if (!is_null($filter["model"])) {
                $q->where("model_guid", $filter["model"]);
            }
            if (!is_null($filter["priceMin"])) {
                if (!is_null($filter["priceMax"])) {
                    $q->whereBetween("price", [$filter["priceMin"], $filter["priceMax"]]);
                } else {
                    $q->where("price", ">=", $filter["priceMin"]);
                }
            } else {
                if (!is_null($filter["priceMax"])) {
                    $q->where("price", "<=", $filter["priceMax"]);
                }
            }
            if (!is_null($filter["yearMin"])) {
                if (!is_null($filter["yearMax"])) {
                    $q->whereBetween("year", [$filter["yearMin"], $filter["yearMax"]]);
                } else {
                    $q->where("year", ">=", $filter["yearMin"]);
                }
            } else {
                if (!is_null($filter["yearMax"])) {
                    $q->where("year", "<=", $filter["yearMax"]);
                }
            }
            if (!is_null($filter["kmMin"])) {
                if (!is_null($filter["kmMax"])) {
                    $q->whereBetween("mileage", [$filter["kmMin"], $filter["kmMax"]]);
                } else {
                    $q->where("mileage", ">=", $filter["kmMin"]);
                }
            } else {
                if (!is_null($filter["kmMax"])) {
                    $q->where("mileage", "<=", $filter["kmMax"]);
                }
            }
        })->with('main_image_api', 'location', 'currency')->get();

        $e['cats'] = [];
        $e['subcats'] = [];
        $e['colors'] = [];
        $e['fuels'] = [];
        $e['types'] = [];
        $e['situs'] = [];
        $e['sellers'] = [];
        $e['brands'] = [];
        $e['models'] = [];



        $d["brands"] = [];
        $d["models"] = [];
        $d["fuelTypes"] = [];
        $d["types"] = [];
        $d["situations"] = [];
        $d["sellers"] = UserType::select("type_guid", "name_en", "name_ar")->get();


        foreach ($listings as $l) {
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

        $d["fuelTypes"][] = array(
            "name_en" => "Gasoline",
            "name_ar" => "Gasoline",
            "value" => "gasoline"
        );
        $d["fuelTypes"][] = array(
            "name_en" => "Diesel Fuel",
            "name_ar" => "Diesel Fuel",
            "value" => "diesel"
        );
        $d["fuelTypes"][] = array(
            "name_en" => "LPG Gas",
            "name_ar" => "LPG Gas",
            "value" => "lpg"
        );
        $d["fuelTypes"][] = array(
            "name_en" => "Electric Car",
            "name_ar" => "Electric Car",
            "value" => "electric"
        );


        if (count($e["sellers"]) > 0) {
            $d['sellers'] = UserType::select("type_guid", "name_en", "name_ar")->whereIn("type_guid", $e["sellers"])->get();
        }

        if (count($e["brands"]) > 0) {
            $d["brands"] = Brand::whereIn("brand_guid", $e["brands"])->select("brand_guid", "name_en", "name_ar")->get();
        }

        if (!is_null($filter["brand"])) {
            if (count($e["models"]) > 0) {
                $d["models"] = BrandModel::whereIn("model_guid", $e["models"])->select("model_guid", "name_en", "name_ar")->get();
            }
        }

        if (count($listings) > 0) {
            $d['categories'] = Category::select("category_guid", "name_en", "name_ar")->whereIn("category_guid", $e['cats'])->whereNull("related_to")->where("status", "1")->with("children_api")->get();
            $d['colors'] = Color::select("color_guid", "name_en", "name_ar")->whereIn("color_guid", $e['colors'])->where("status", "1")->get();

            foreach ($listings as $l) {
                if (!is_null($l['main_image_api'])) {
                    $l['main_image_api']['name'] = config('api.main_url') . '/storage/listings/' . $l->listing_no . '/' . $l->main_image_api->name;
                } else {
                    continue;
                }
            }
        } else {
            $d['categories'] = Category::select("category_guid", "name_en", "name_ar")->whereNull("related_to")->where("status", "1")->with("children_api")->get();
            $d['colors'] = Color::select("color_guid", "name_en", "name_ar")->where("status", "1")->get();
        }

        $d['filter'] = $filter;
        $d['listings'] = $listings;
        return response($d, 200);
    }

    /**
     * @OA\Post(
     *  path="api.motovago.com/listing-detail",
     *  operationId="listing_guid detail",
     *  summary="Gets detail of listing which you posted listing_guid. ",
     *    @OA\Parameter(
     *    description="Listing guid gönderilecek",
     *    in="query",
     *    name="listing_guid",
     *    required=true,
     *    example="123123-41232323-234442",
     *    ),
     *    @OA\Parameter(
     *    description="Language",
     *    in="query",
     *    name="language",
     *    required=true,
     *    example="'en','ar'",
     *    ),
     *    @OA\Parameter(
     *    description="Token (giriş yapmış ise)",
     *    in="query",
     *    name="token",
     *    required=false,
     *    example="123123asd12w233132",
     *    ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="listing_guid", type="string", example="0d59fc2a-2357-11ec-9621-0242ac130002"),
     *       @OA\Property(property="listing_no", type="string", example="02523718"),
     *       @OA\Property(property="price", type="string", example="24000.0000"),
     *       @OA\Property(property="currency_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d"),
     *       @OA\Property(property="category_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae3555d"),
     *       @OA\Property(property="location_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae39d"),
     *       @OA\Property(property="user_guid", type="string", example="0cc869aa-3b01-4abb-8490-a25d"),
     *       @OA\Property(property="is_mine", type="string", example="yes/no"),
     *
     *        )
     *     ),
     *  @OA\Response(
     *    response=400,
     *    description="error",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="There is no listing to show"),
     *        )
     *     )
     * )
     */

    public function getListingDetail(Request $r)
    {
        $listing = null;
        $detail = Listing::where('listing_guid', $r->listing_guid)->with('location', 'user.country_api', 'user.type_api', 'images', 'attributes', 'category', 'subcategory', 'brand', 'model', 'trim', 'color', 'currency')->first();
        if (!is_null($detail)) {

            $attribute_groups = ListingAttribute::where("listing_guid", $r->listing_guid)->groupBy("ag_guid")->with("attribute_group")->get();
            foreach ($attribute_groups as $ag) {
                $ags = [];
                $attrs = ListingAttribute::where("listing_guid", $r->listing_guid)->where("ag_guid", $ag->ag_guid)->with("attribute_info")->get();
                foreach ($attrs as $k => $atr) {
                    if (!in_array($atr->attribute_guid, $ags)) {
                        $ags[$k]['attribute_guid'] = $atr->attribute_guid;
                        $ags[$k]['name'] = $r->language == 'ar' ? $atr->attribute_info->name_ar : $atr->attribute_info->name_en;
                    }
                }
                $ag['attributes'] = $ags;
            }

            if ($r->has('token')) {
                if (!is_null($r->token) && $r->token != '') {
                    $user = User::where('token', $r->token)->first();
                    if (!is_null($user)) {
                        if ($user->user_guid == $detail->user_guid) {
                            $listing["is_mine"] = 'yes';
                        } else {
                            $listing["is_mine"] = 'no';
                        }
                    } else {
                        $listing["is_mine"] = 'no';
                    }
                } else {
                    $listing["is_mine"] = 'no';
                }
            } else {
                $listing["is_mine"] = 'no';
            }

            $listing["username"] = $detail->user->name;
            $listing["user_guid"] = $detail->user->user_guid;
            $listing["user_location"] = !is_null($detail->user->country_api) ? $detail->user->country_api->name : "-";
            $listing["user_created_at"] = $detail->user->created_at;
            if (!is_null($detail->user->logo)) {
                $listing["user_logo"] = config('api.main_url') . '/storage/user/' . $detail->user->logo;
            } else {
                $listing["user_logo"] = null;
            }
            $listing["user_type"] =  $detail->user->type_api->name_en;
            $listing["listing_no"] = $detail->listing_no;
            $listing["name_en"] = $detail->name_en;
            $listing["name_ar"] = $detail->name_ar;
            $listing["year"] = $detail->year;
            $listing["mileage"] = $detail->mileage;
            $listing["price"] = $detail->price;
            $listing["currency"] = $detail->currency->label;
            $listing["description"] = $detail->description;
            $listing["fueltype"] = $detail->fueltype;
            $listing["viewed"] = $detail->viewed;
            $listing["situation"] = $detail->situation;
            $listing["listing_type"] = $detail->listing_type;
            $listing["latitude"] = $detail->latitude;
            $listing["longtitude"] = $detail->longitude;
            $listing["expired"] = $detail->expired;
            $listing["status"] = $detail->status;
            $listing["location"]['name_en'] = $detail->location->name_en;
            $listing["location"]['name_ar'] = $detail->location->name_ar;
            $listing["created_at"] = $detail->created_at;
            $listing["category"]["name_en"] = $detail->category->name_en;
            $listing["category"]["name_ar"] = $detail->category->name_ar;

            $listing["sub_category"]["name_en"] = $detail->subcategory->name_en;
            $listing["sub_category"]["name_ar"] = $detail->subcategory->name_ar;

            if (count($detail->images) > 0) {
                foreach ($detail->images as $im) {
                    $listing["images"][] = config('api.main_url') . '/storage/listings/' . $detail->listing_no . '/' . $im->name;
                }
            } else {
                $listing["images"] = [];
            }

            if ($r->has("token")) {
                $user = User::where('token', $r->token)->where('status', '1')->first();
                if (!is_null($user)) {
                    $checkfav = Favorite::where("user_guid", $user->user_guid)->where("listing_guid", $detail->listing_guid)->first();
                } else {
                    $checkfav = null;
                }
            } else {
                $checkfav = null;
            }

            if (is_null($checkfav)) {
                $listing["isFav"] = 'no';
            } else {
                $listing["isFav"] = 'yes';
            }

            if (!is_null($detail->brand)) {
                $listing["brand"]["name_en"] = $detail->brand->name_en;
                $listing["brand"]["name_ar"] = $detail->brand->name_ar;
            } else {
                $listing["brand"]["name_en"] = "-";
                $listing["brand"]["name_ar"] = "-";
            }
            if (!is_null($detail->model)) {
                $listing["model"]["name_en"] = $detail->model->name_en;
                $listing["model"]["name_ar"] = $detail->model->name_ar;
            } else {
                $listing["model"]["name_en"] = "-";
                $listing["model"]["name_ar"] = "-";
            }
            if (!is_null($detail->trim)) {
                $listing["trim"]["name_en"] = $detail->trim->name_en;
                $listing["trim"]["name_ar"] = $detail->trim->name_ar;
            } else {
                $listing["trim"]["name_en"] = "-";
                $listing["trim"]["name_ar"] = "-";
            }
            if (!is_null($detail->color)) {
                $listing["color"]["name_en"] = $detail->color->name_en;
                $listing["color"]["name_ar"] = $detail->color->name_ar;
            } else {
                $listing["color"]["name_en"] = "-";
                $listing["color"]["name_ar"] = "-";
            }





            $d['detail'] = $listing;
            $d['attribute_groups'] = $attribute_groups;

            return response()->json($d, 200);
        } else {
            $error = 'There is no detail to show.';
            return response()->json($error, 400);
        }
    }

    /**
     * @OA\Post(
     *  path="api.motovago.com/newListing",
     *  operationId="newListing",
     *  summary="Gets create listing required datas",
     *  @OA\Parameter(
     *    description="Language",
     *    in="query",
     *    name="lang",
     *    required=true,
     *    example="en/ar",
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="vehicles", type="array",
     *        @OA\Items(type="object", format="query",
     *          @OA\Property(property="vehicle_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="title", type="string", example="Cars"),
     *        ),
     *        @OA\Items(type="object", format="query",
     *          @OA\Property(property="vehicle_guid", type="string", example="24e869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="title", type="string", example="Motocycles"),
     *        ),
     *      ),
     *      @OA\Property(property="locations", type="array",
     *        @OA\Items(type="object", format="query",
     *          @OA\Property(property="location_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae55d3"),
     *          @OA\Property(property="title", type="string", example="Qatar"),
     *          @OA\Property(property="currency_guid", type="string", example="12341151c-14141cqwe-123123"),
     *          @OA\Property(property="name", type="string", example="Qatari Riyal"),
     *          @OA\Property(property="label", type="string", example="QAR"),
     *          @OA\Property(property="symbol", type="string", example="ر. ق;"),
     *
     *
     *        ),
     *        @OA\Items(type="object", format="query",
     *          @OA\Property(property="location_guid", type="string", example="24e869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="title", type="string", example="Dubai"),
     *        ),
     *      ),
     *
     *      @OA\Property(property="colors", type="array",
     *        @OA\Items(type="object", format="query",
     *          @OA\Property(property="color_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="title", type="string", example="White"),
     *        ),
     *        @OA\Items(type="object", format="query",
     *          @OA\Property(property="color_guid", type="string", example="24e869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="title", type="string", example="Silver"),
     *        ),
     *      )
     *    ),
     *  ),
     *  @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="Wrong parameter sended please try again."),
     *
     *        )
     *     ),
     *
     *  )
     *
     */

    public function newListing(Request $r)
    {
        $lang = $r->lang;

        $vehicles = [];
        $locations = [];
        $currencies = [];
        $colors = [];
        $allVehicles = Category::whereNull("related_to")->where("status", "1")->with("children")->get();
        $allLocations = Location::select("location_guid", "name_en", "name_ar", "currency_guid")->where("status", "1")->with('currency')->get();
        $allColors = Color::where("status", "1")->get();

        foreach ($allVehicles as $key => $av) {
            $vehicles[] = array('category_guid' => $av->category_guid, 'title' => $lang == 'ar' ? $av->name_ar : $av->name_en, 'image' => config('api.main_url') . '/storage/images/categories/' . $av->image, 'types' => $av->children);
        }

        foreach ($allLocations as $al) {
            $locations[] = array('location_guid' => $al->location_guid, 'title' => $lang == 'ar' ? $al->name_ar : $al->name_en, 'currency_guid' => $al->currency->currency_guid, 'name' => $al->currency->name, 'label' => $al->currency->label, 'symbol' => $al->currency->symbol);
        }

        foreach ($allColors as $ac) {
            $colors[] = array('color_guid' => $ac->color_guid, 'title' => $lang == 'ar' ? $ac->name_ar : $ac->name_en);
        }



        $d['vehicles'] = $vehicles;
        $d['locations'] = $locations;
        $d['colors'] = $colors;
        return response()->json($d, 200);
    }


    /**
     * @OA\Post(
     *  path="api.motovago.com/newListingBrands",
     *  operationId="newListingBrands",
     *  summary="Gets brands for create listing",
     *  @OA\Parameter(
     *    description="Language",
     *    in="query",
     *    name="lang",
     *    required=true,
     *    example="en/ar",
     *  ),
     *  @OA\Parameter(
     *    description="Category",
     *    in="query",
     *    name="category",
     *    required=true,
     *    example="0cc869aa-3b01-4abb-8490-a29fae555d3",
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="brands", type="array",
     *        @OA\Items(type="object", format="query",
     *          @OA\Property(property="brand_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="text", type="string", example="BMW"),
     *        )
     *      )
     *    ),
     *  ),
     *  @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="Wrong parameter sended please try again."),
     *     )
     *  )
     * )
     *
     */
    //Get Brands
    public function newListingBrands(Request $r)
    {
        $allbrands = [];

        if ($r->has('category') && !is_null($r->category)) {
            $category = $r->category;
            $lang = $r->lang;
            if ($lang == 'ar') {
                $brands = Brand::select("brand_guid", "name_ar as text")->where("status", "1")->where("category_guid", $category)->get();
            } else {
                $brands = Brand::select("brand_guid", "name_en as text")->where("status", "1")->where("category_guid", $category)->get();
            }

            if (count($brands) > 0) {
                foreach ($brands as $br) {
                    $allbrands[] = array('id' => $br->brand_guid, 'text' => $br->text);
                }
            }
        }

        return response()->json($allbrands, 200);
    }

    /**
     * @OA\Post(
     *  path="api.motovago.com/newListingModels",
     *  operationId="newListingModels",
     *  summary="Gets models for create listing",
     *  @OA\Parameter(
     *    description="Language",
     *    in="query",
     *    name="lang",
     *    required=true,
     *    example="en/ar",
     *  ),
     *  @OA\Parameter(
     *    description="Brand",
     *    in="query",
     *    name="brand",
     *    required=true,
     *    example="0cc869aa-3b01-4abb-8490-a29fae555d3",
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="models", type="array",
     *        @OA\Items(type="object", format="query",
     *          @OA\Property(property="model_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="text", type="string", example="X5"),
     *        )
     *      )
     *    ),
     *  ),
     *  @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="Wrong parameter sended please try again."),
     *     )
     *  )
     * )
     *
     */
    //Get Brand Models
    public function newListingModels(Request $r)
    {
        $allmodels = [];

        if ($r->has('brand') && !is_null($r->brand)) {
            $brand = $r->brand;
            $lang = $r->lang;
            if ($lang == 'ar') {
                $models = BrandModel::select("model_guid", "name_ar as text")->where("brand_guid", $brand)->where("status", "1")->get();
            } else {
                $models = BrandModel::select("model_guid", "name_en as text")->where("brand_guid", $brand)->where("status", "1")->get();
            }

            if (count($models) > 0) {
                foreach ($models as $md) {
                    $allmodels[] = array('id' => $md->model_guid, 'text' => $md->text);
                }
            }
        }

        return response()->json($allmodels, 200);
    }


    /**
     * @OA\Post(
     *  path="api.motovago.com/newListingTrims",
     *  operationId="newListingTrims",
     *  summary="Gets trims for create listing",
     *  @OA\Parameter(
     *    description="Language",
     *    in="query",
     *    name="lang",
     *    required=true,
     *    example="en/ar",
     *  ),
     *  @OA\Parameter(
     *    description="Model",
     *    in="query",
     *    name="model",
     *    required=true,
     *    example="0cc869aa-3b01-4abb-8490-a29fae555d3",
     *  ),
     *  @OA\Parameter(
     *    description="Year",
     *    in="query",
     *    name="year",
     *    required=true,
     *    example="2020",
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="models", type="array",
     *        @OA\Items(type="object", format="query",
     *          @OA\Property(property="model_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="text", type="string", example="2.0d Xdrive AT"),
     *        )
     *      )
     *    ),
     *  ),
     *  @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="Wrong parameter sended please try again."),
     *     )
     *  )
     * )
     *
     */
    //Get Brand Model Trims
    public function newListingTrims(Request $r)
    {
        $alltrims = [];

        if ($r->has('model') && !is_null($r->model) && $r->has('year') && !is_null($r->year)) {
            $model = $r->model;
            $year = $r->year;
            $lang = $r->lang;
            if ($lang == 'ar') {
                $trims = ModelTrim::select("trim_guid", "name_ar as name", "year")->where("model_guid", $model)->where("year", $year)->where("status", "1")->get();
            } else {
                $trims = ModelTrim::select("trim_guid", "name_en as name", "year")->where("model_guid", $model)->where("year", $year)->where("status", "1")->get();
            }

            if (count($trims) > 0) {
                foreach ($trims as $t) {
                    $alltrims[] = array('id' => $t->trim_guid, 'text' => $t->name . ' (' . $t->year . ')');
                }
            }
        }

        return response()->json($alltrims, 200);
    }


    /**
     * @OA\Get(
     *  path="api.motovago.com/newListingCountries",
     *  operationId="newListingCountries",
     *  summary="Gets countries for create listing",
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="countries", type="array",
     *        @OA\Items(type="object", format="query",
     *          @OA\Property(property="country_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="text", type="string", example="Dubai"),
     *        )
     *      )
     *    ),
     *  ),
     *  @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="Wrong parameter sended please try again."),
     *     )
     *  )
     * )
     *
     */
    //Get Countries
    public function newListingCountries()
    {
        $allcountries = [];
        $countries = Country::where("status", "1")->get();
        if (count($countries) > 0) {
            foreach ($countries as $c) {
                $allcountries[] = array('id' => $c->country_guid, 'text' => $c->name, 'lat' => $c->latitude, 'lng' => $c->longitude);
            }
        }

        return response()->json($allcountries, 200);
    }

    /**
     * @OA\Post(
     *  path="api.motovago.com/newListingStates",
     *  operationId="newListingStates",
     *  summary="Gets states for create listing",
     *  @OA\Parameter(
     *    description="Country",
     *    in="query",
     *    name="country",
     *    required=true,
     *    example="0cc869aa-3b01-4abb-8490-a29fae555d3",
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="states", type="array",
     *        @OA\Items(type="object", format="query",
     *          @OA\Property(property="state_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="text", type="string", example="Dubai"),
     *        )
     *      )
     *    ),
     *  ),
     *  @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="Wrong parameter sended please try again."),
     *     )
     *  )
     * )
     *
     */
    //Get States
    public function newListingStates(Request $r)
    {
        $allstates = [];
        if ($r->has('country') && !is_null($r->country)) {
            $country_guid = $r->country;

            $states = State::where("country_guid", $country_guid)->get();
            if (count($states) > 0) {
                foreach ($states as $s) {
                    $allstates[] = array('id' => $s->state_guid, 'text' => $s->name, 'lat' => $s->latitude, 'lng' => $s->longitude);
                }
            }
        }
        return response()->json($allstates, 200);
    }

    /**
     * @OA\Post(
     *  path="api.motovago.com/newListingCities",
     *  operationId="newListingCities",
     *  summary="Gets states for create listing",
     *  @OA\Parameter(
     *    description="State",
     *    in="query",
     *    name="state",
     *    required=true,
     *    example="0cc869aa-3b01-4abb-8490-a29fae555d3",
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="cities", type="array",
     *        @OA\Items(type="object", format="query",
     *          @OA\Property(property="city_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="text", type="string", example="Dubai"),
     *        )
     *      )
     *    ),
     *  ),
     *  @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="Wrong parameter sended please try again."),
     *     )
     *  )
     * )
     *
     */
    //Get Cities
    public function newListingCities(Request $r)
    {
        $allcities = [];

        if ($r->has('state') && !is_null($r->state)) {
            $state = $r->state;
            $cities = City::where("state_guid", $state)->get();

            if (count($cities) > 0) {
                foreach ($cities as $c) {
                    $allcities[] = array('id' => $c->city_guid, 'text' => $c->name, 'lat' => $c->latitude, 'lng' => $c->longitude);
                }
            }
        }

        return response()->json($allcities, 200);
    }

    /**
     * @OA\Post(
     *  path="api.motovago.com/newListingCategoryAttributes",
     *  operationId="newListingCategoryAttributes",
     *  summary="Gets category attribute categories with attributes for create listing",
     *  @OA\Parameter(
     *    description="Category",
     *    in="query",
     *    name="category",
     *    required=true,
     *    example="0cc869aa-3b01-4abb-8490-a29fae555d3",
     *  ),@OA\Parameter(
     *    description="Language",
     *    in="query",
     *    name="lang",
     *    required=true,
     *    example="en/ar",
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="attributes", type="array",
     *        @OA\Items(type="object", format="query",
     *          @OA\Property(property="ag_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="category_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="text", type="string", example="Security"),
     *        )
     *      )
     *    ),
     *  ),
     *  @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="Wrong parameter sended please try again."),
     *     )
     *  )
     * )
     *
     */
    //Get Listing Category Attributes
    public function newListingCategoryAttributes(Request $r)
    {
        $all_attributes = [];
        $lang = $r->lang;
        if ($r->has('category') && !is_null($r->category)) {
            $category = $r->category;
            $attributes = AttributeGroup::where("category_guid", $category)->with("attribute_info")->get();
            foreach ($attributes as $atr) {
                $all_attributes[] = array(
                    'ag_guid' => $atr->ag_guid,
                    'title' => $lang == 'ar' ? $atr->name_ar : $atr->name_en,
                    'attributes' => $atr->attribute_info
                );
            }
        }

        return response()->json($all_attributes, 200);
    }


    /**
     * @OA\Post(
     *  path="api.motovago.com/editListingDetail",
     *  operationId="editListingDetail",
     *  summary="Gets listing details for edit",
     *  @OA\Parameter(
     *    description="Token",
     *    in="query",
     *    name="token",
     *    required=true,
     *    example="6f191b5c98d2517d9c04670abecca99c",
     *  ),
     *  @OA\Parameter(
     *    description="Language",
     *    in="query",
     *    name="lang",
     *    required=true,
     *    example="en/ar",
     *  ),
     *  @OA\Parameter(
     *    description="listing_guid",
     *    in="query",
     *    name="listing_guid",
     *    required=true,
     *    example="41390293",
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="listing", type="array",
     *        @OA\Items(type="object", format="query",
     *          @OA\Property(property="user_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="username", type="string", example="Jhon Doe"),
     *          @OA\Property(property="usertype", type="string", example="personal"),
     *          @OA\Property(property="location_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="location", type="string", example="Qatar"),
     *          @OA\Property(property="category_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="category", type="string", example="Cars"),
     *          @OA\Property(property="brand_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="brand", type="string", example="BMW"),
     *          @OA\Property(property="model_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="model", type="string", example="X5"),
     *          @OA\Property(property="trim_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="trim", type="string", example="2.0d Xdrive"),
     *          @OA\Property(property="color_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="color", type="string", example="White"),
     *          @OA\Property(property="country_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="country", type="string", example="Qatar"),
     *          @OA\Property(property="state_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="state", type="string", example="Qatar"),
     *          @OA\Property(property="city_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="city", type="string", example="Qatar"),
     *          @OA\Property(property="title", type="string", example="Lorem ipsum dolor sit amed"),
     *          @OA\Property(property="description", type="string", example="Lorem ipsum dolor sit amed"),
     *          @OA\Property(property="listing_type", type="string", example="sell/rent"),
     *          @OA\Property(property="situation", type="string", example="used/new"),
     *          @OA\Property(property="mileage", type="string", example="63092"),
     *          @OA\Property(property="year", type="string", example="2020"),
     *          @OA\Property(property="price", type="string", example="24500"),
     *          @OA\Property(property="viewed", type="string", example="240"),
     *          @OA\Property(property="fuel_type", type="string", example="diesel"),
     *        )
     *      ),
     *      @OA\Property(property="vehicles", type="array",
     *        @OA\Items(type="object", format="query",
     *          @OA\Property(property="category_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="text", type="string", example="Cars"),
     *        )
     *      ),
     *      @OA\Property(property="locations", type="array",
     *        @OA\Items(type="object", format="query",
     *          @OA\Property(property="location_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="text", type="string", example="Qatar"),
     *        )
     *      ),
     *      @OA\Property(property="colors", type="array",
     *        @OA\Items(type="object", format="query",
     *          @OA\Property(property="color_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="text", type="string", example="White"),
     *        )
     *      ),
     *      @OA\Property(property="currencies", type="array",
     *        @OA\Items(type="object", format="query",
     *          @OA\Property(property="currency_guid", type="string", example="0cc869aa-3b01-4abb-8490-a29fae555d3"),
     *          @OA\Property(property="text", type="string", example="AED"),
     *        )
     *      )
     *    ),
     *  ),
     *  @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="Wrong parameter sended please try again."),
     *     )
     *  )
     * )
     *
     */
    //Get edit listing detail api
    public function editListingDetail(Request $r)
    {
        $lang = $r->lang;
        $token = $r->token;
        $user = User::where('token', $token)->where('status', '1')->first();

        if (is_null($user)) {
            $d['status'] = 400;
            $d['msg'] = 'User not found!';
            return response()->json($d, 400);
        }
        $user_guid = $user->user_guid;

        $listing = [];
        $attribute_groups = [];
        $listing_guid = $r->listing_guid;
        $listingInfo = Listing::where("user_guid", $user->user_guid)->where("listing_guid", $listing_guid)->with("images", "category", "subcategory", "color", "location", "brand", "model", "trim", "country", "state", "city", 'currency', 'user')->first();
        $all_attribute_groups = AttributeGroup::where("category_guid", $listingInfo->category_guid)->with("attribute_info")->get();
        foreach ($all_attribute_groups as $aag) {
            $aag_attributes = [];
            foreach ($aag->attribute_info as $aaga) {
                $aag_attributes[] = array(
                    'attribute_guid' => $aaga->attribute_guid,
                    'ag_guid' => $aag->ag_guid,
                    'title' => $lang == 'ar' ? $aaga->name_ar : $aaga->name_en
                );
            }
            $attribute_groups[] = array(
                'ag_guid' => $aag->ag_guid,
                'title' => $lang == 'ar' ? $aag->name_ar : $aag->name_en,
                'attributes' => $aag_attributes
            );
        }

        if (count($listingInfo->images) > 0) {
            foreach ($listingInfo->images as $im) {
                $listing["images"][] = config('api.main_url') . '/storage/listings/' . $listingInfo->listing_no . '/' . $im->name;
            }
        } else {
            $listing["images"] = [];
        }

        $listing_attributes_raw = ListingAttribute::where("listing_guid", $listingInfo->listing_guid)->get();
        $listing_attributes = [];
        foreach ($listing_attributes_raw as $lar) {
            $listing_attributes[] = $lar->attribute_guid;
        }
        $listing["listing_guid"] = $listingInfo->listing_guid;
        $listing["listing_no"] = $listingInfo->listing_no;
        $listing["user_guid"] = $listingInfo->user_guid;
        $listing["username"] = $listingInfo->user->name;
        $listing["usertype"] = $listingInfo->user->type->id == '1' ? 'personal' : 'commercial';
        if ($listingInfo->user->type->id == '2') {
            $listing["userlogo"] = config('api.main_url') . '/storage/user/' . $listingInfo->user->logo;
            $listing["userbanner"] = config('api.main_url') . '/storage/user/' . $listingInfo->user->background;
        }
        $listing["location_guid"] = $listingInfo->location_guid;
        $listing["location"] = $lang == 'ar' ? $listingInfo->location->name_ar : $listingInfo->location->name_en;
        $listing["category_guid"] = $listingInfo->category_guid;
        $listing["category"] = $lang == 'ar' ? $listingInfo->category->name_ar : $listingInfo->category->name_en;
        $listing["subcategory_guid"] = $listingInfo->subcategory_guid;
        $listing["subcategory"] = $lang == 'ar' ? $listingInfo->subcategory->name_ar : $listingInfo->subcategory->name_en;
        $listing["brand_guid"] = $listingInfo->brand_guid;
        $listing["brand"] = $lang == 'ar' ? $listingInfo->brand->name_ar : $listingInfo->brand->name_en;
        $listing["model_guid"] = $listingInfo->model_guid;
        $listing["model"] = $lang == 'ar' ? $listingInfo->model->name_ar : $listingInfo->model->name_en;
        $listing["trim_guid"] = $listingInfo->trim_guid;
        if ($listingInfo->trim) {
            $listing["trim"] = $lang == 'ar' ? $listingInfo->trim->name_ar : $listingInfo->trim->name_en;
        }
        $listing["currency_guid"] = $listingInfo->currency_guid;
        $listing["currency"] = $listingInfo->currency->label;
        $listing["currency_symbol"] = $listingInfo->currency->symbol;
        $listing["color_guid"] = $listingInfo->color_guid;
        $listing["color"] = $lang == 'ar' ? $listingInfo->color->name_ar : $listingInfo->color->name_en;
        $listing["country_guid"] = $listingInfo->country_guid;
        $listing["country"] = $listingInfo->country->name;
        $listing["state_guid"] = $listingInfo->state_guid;
        $listing["state"] = $listingInfo->state->name;
        $listing["city_guid"] = $listingInfo->city_guid;
        if ($listingInfo->city) {
            $listing["city"] = $listingInfo->city->name;
        } else {
            $listing["city"] = null;
        }
        $listing["title"] = $listingInfo->name_en;
        $listing["year"] = $listingInfo->year;
        $listing["mileage"] = $listingInfo->mileage;
        $listing["price"] = $listingInfo->price;
        $listing["description"] = $listingInfo->description;
        $listing["fuel_type"] = $listingInfo->fuel_type;
        $listing["situation"] = $listingInfo->situation;
        $listing["listing_type"] = $listingInfo->listing_type;
        $listing["viewed"] = $listingInfo->viewed;
        $listing["latitude"] = $listingInfo->latitude;
        $listing["longitude"] = $listingInfo->longitude;

        $d['listing'] = $listing;
        $d['attribute_groups'] = $attribute_groups;
        $d['listing_attributes'] = $listing_attributes;
        $vehicles = [];
        $locations = [];
        $currencies = [];
        $colors = [];
        $allVehicles = Category::whereNull("related_to")->where("status", "1")->with("children")->get();
        $allLocations = Location::select("location_guid", "name_en", "name_ar")->where("status", "1")->get();
        $allCurrencies = Currency::where("status", "1")->get();
        $allColors = Color::where("status", "1")->get();

        foreach ($allVehicles as $av) {
            $vehicles[] = array('category_guid' => $av->category_guid, 'title' => $lang == 'ar' ? $av->name_ar : $av->name_en);
        }

        foreach ($allLocations as $al) {
            $locations[] = array('location_guid' => $al->location_guid, 'title' => $lang == 'ar' ? $al->name_ar : $al->name_en);
        }

        foreach ($allColors as $ac) {
            $colors[] = array('color_guid' => $ac->color_guid, 'title' => $lang == 'ar' ? $ac->name_ar : $ac->name_en);
        }

        foreach ($allCurrencies as $acu) {
            $currencies[] = array('currency_guid' => $acu->currency_guid, 'title' => $acu->label);
        }

        $d['vehicles'] = $vehicles;
        $d['locations'] = $locations;
        $d['currencies'] = $currencies;
        $d['colors'] = $colors;

        return response()->json($d, 200);
    }




    /**
     * @OA\Post(
     *  path="api.motovago.com/deleteListing",
     *  operationId="deleteListing",
     *  summary="Delete listing.",
     *  @OA\Parameter(
     *    description="token",
     *    in="query",
     *    name="token",
     *    required=true,
     *    example="0cc869aa3b014abb8490a29fae555d3",
     *  ),@OA\Parameter(
     *    description="listing_guid",
     *    in="query",
     *    name="listing_guid",
     *    required=true,
     *    example="1234141412",
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="attributes", type="array",
     *        @OA\Items(type="object", format="query",
     *          @OA\Property(property="status", type="string", example="200"),
     *          @OA\Property(property="msg", type="string", example="Listing deleted successfully."),
     *
     *        )
     *      )
     *    ),
     *  ),
     *  @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="There is an error occurred while deleting listing please try again."),
     *     )
     *  )
     * )
     *
     */

    public function deleteListing(Request $r)
    {
        $user = User::where('token', $r->token)->first();
        if (!$user) {
            $d['msg'] = 'User not found';
            $d['status'] = 400;
            return response()->json($d, 200);
        }
        $list = Listing::where("listing_guid", $r->listing_guid)->where("user_guid", $user->user_guid)->first();
        if (is_null($list)) {
            $d['msg'] = 'Listing not found';
            $d['status'] = 400;
            return response()->json($d, 200);
        }

        try {
            DB::beginTransaction();
            $list->delete();
            DB::commit();
            $d['msg'] = 'Listing successfully deleted.';
            $d['status'] = 200;
            return response()->json($d, 200);
        } catch (\Throwable $th) {
            DB::rollback();
            $d['msg'] = 'There is an error occurred while deleting listing please try again.';
            $d['status'] = 200;
            return response()->json($d, 200);
        }
    }


    /**
     * @OA\Post(
     *  path="api.motovago.com/addToFav",
     *  operationId="addToFav",
     *  summary="Add a listing to fav list",
     *     @OA\Parameter(
     *    description="token",
     *    in="query",
     *    name="token",
     *    required=true,
     *    example="123123-41232323-23123123",
     * ),
     * @OA\Parameter(
     *    description="listing",
     *    in="query",
     *    name="listing_guid",
     *    required=true,
     *    example="123123-41232323-23123123",
     * ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="attributes", type="array",
     *        @OA\Items(type="object", format="query",
     *          @OA\Property(property="status", type="string", example="200"),
     *          @OA\Property(property="msg", type="string", example="Listing successfully added to favourites."),
     *
     *        )
     *      )
     *    ),
     *  ),
     *  @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="Wrong parameter sended please try again."),
     *        )
     *     ),
     *
     * )
     *
     */

    public function addToFav(Request $r)
    {
        $user = User::where('token', $r->token)->first();
        if (!$user) {
            $d['msg'] = 'User not found';
            $d['status'] = 400;
            return response()->json($d, 200);
        }
        $list = Listing::where("listing_guid", $r->listing_guid)->first();
        if (is_null($list)) {
            $d['msg'] = 'Listing not found';
            $d['status'] = 400;
            return response()->json($d, 200);
        }

        $checkfav = Favorite::where("listing_guid", $list->listing_guid)->where("user_guid", $user->userguid)->first();
        if (!is_null($checkfav)) {
            $d['result'] = 400;
            $d['msg'] = __('alert.fav_exist_msg');
            return response()->json($d, 400);
        }

        $fav = new Favorite();
        $fav->favorite_guid = Str::uuid();
        $fav->listing_guid = $list->listing_guid;
        $fav->user_guid = $user->user_guid;
        $fav->save();

        $d['result'] = 200;
        $d['msg'] = __('alert.fav_add_success');
        return response()->json($d, 200);
    }

    /**
     * @OA\Post(
     *  path="api.motovago.com/removeFav",
     *  operationId="removeFav",
     *  summary="Remove a listing from fav list",
     *     @OA\Parameter(
     *    description="token",
     *    in="query",
     *    name="token",
     *    required=true,
     *    example="123123-41232323-23123123",
     * ),
     * @OA\Parameter(
     *    description="listing",
     *    in="query",
     *    name="listing_guid",
     *    required=true,
     *    example="123123-41232323-23123123",
     * ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="attributes", type="array",
     *        @OA\Items(type="object", format="query",
     *          @OA\Property(property="status", type="string", example="200"),
     *          @OA\Property(property="msg", type="string", example="Listing successfully added to favourites."),
     *
     *        )
     *      )
     *    ),
     *  ),
     *  @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="Wrong parameter sended please try again."),
     *        )
     *     ),
     *
     * )
     *
     */

    public function removeFav(Request $r)
    {
        $user = User::where('token', $r->token)->first();
        if (!$user) {
            $d['msg'] = 'User not found';
            $d['status'] = 400;
            return response()->json($d, 200);
        }
        $list = Listing::where("listing_guid", $r->listing_guid)->first();
        if (is_null($list)) {
            $d['msg'] = 'Listing not found';
            $d['status'] = 400;
            return response()->json($d, 200);
        }

        $checkfav = Favorite::where("listing_guid", $list->listing_guid)->where("user_guid", $user->user_guid)->first();
        if (is_null($checkfav)) {
            $d['result'] = 400;
            $d['msg'] = __('alert.fav_not_exist_msg');
            return response()->json($d, 400);
        } else {
            $checkfav->delete();
            $d['result'] = 200;
            $d['msg'] = __('alert.fav_remove_success');
            return response()->json($d, 200);
        }
    }

    /**
     * @OA\Post(
     *  path="api.motovago.com/searchListing",
     *  operationId="searchListing",
     *  summary="searchListing",
     *     @OA\Parameter(
     *    description="keywords",
     *    in="query",
     *    name="keywords",
     *    required=false,
     *    example="keywords",
     * ),

     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="attributes", type="array",
     *        @OA\Items(type="object", format="query",
     *          @OA\Property(property="status", type="string", example="200"),
     *          @OA\Property(property="msg", type="string", example="Listing successfully added to favourites."),
     *
     *        )
     *      )
     *    ),
     *  ),
     *  @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="Wrong parameter sended please try again."),
     *        )
     *     ),
     *
     * )
     *
     */

    public function searchListing(Request $r)
    {
        $filter['keywords'] = $r->keywords;

        $filter['location'] = null;
        $filter['category'] = null;
        $filter['subcategory'] = null;
        $filter['type'] = null;
        $filter['situation'] = null;
        $filter['brand'] = null;
        $filter['model'] = null;
        $filter['priceMin'] = null;
        $filter['priceMax'] = null;
        $filter['yearMin'] = null;
        $filter['yearMax'] = null;
        $filter['kmMin'] = null;
        $filter['kmMax'] = null;
        $filter['color'] = null;
        $filter['fuelType'] = null;
        $filter['sellerType'] = null;

        $listings = Listing::where('status', 1)->where(function ($q) use ($filter) {
            if (!is_null($filter['keywords'])) {
                $q->orWhere('name_en', 'LIKE', '%' . $filter['keywords'] . '%');
            }
            if (!is_null($filter['keywords'])) {
                $q->orWhere('name_ar', 'LIKE', '%' . $filter['keywords'] . '%');
            }
            if (!is_null($filter['keywords'])) {
                $q->orWhere('listing_no', 'LIKE', '%' . $filter['keywords'] . '%');
            }
            if (!is_null($filter["category"])) {
                $q->where('category_guid', $filter["category"]);
            }
            if (!is_null($filter["location"])) {
                $q->where('location_guid', $filter["location"]);
            }
            if (!is_null($filter["subcategory"])) {
                $q->where("subcategory_guid", $filter["subcategory"]);
            }
            if (!is_null($filter["situation"])) {
                $q->where("situation", $filter["situation"]);
            }
            if (!is_null($filter["type"])) {
                $q->where("listing_type", $filter["type"]);
            }
            if (!is_null($filter["color"])) {
                $q->where("color_guid", $filter["color"]);
            }
            if (!is_null($filter["fuelType"])) {
                $q->where("fuel_type", $filter["fuelType"]);
            }
            if (!is_null($filter["sellerType"])) {
                $q->whereHas('user', function ($qr) use ($r) {
                    $qr->where("type_guid", $filter["sellerType"]);
                });
            }
            if (!is_null($filter["brand"])) {
                $q->where("brand_guid", $filter["brand"]);
            }
            if (!is_null($filter["model"])) {
                $q->where("model_guid", $filter["model"]);
            }
            if (!is_null($filter["priceMin"])) {
                if (!is_null($filter["priceMax"])) {
                    $q->whereBetween("price", [$filter["priceMin"], $filter["priceMax"]]);
                } else {
                    $q->where("price", ">=", $filter["priceMin"]);
                }
            } else {
                if (!is_null($filter["priceMax"])) {
                    $q->where("price", "<=", $filter["priceMax"]);
                }
            }
            if (!is_null($filter["yearMin"])) {
                if (!is_null($filter["yearMax"])) {
                    $q->whereBetween("year", [$filter["yearMin"], $filter["yearMax"]]);
                } else {
                    $q->where("year", ">=", $filter["yearMin"]);
                }
            } else {
                if (!is_null($filter["yearMax"])) {
                    $q->where("year", "<=", $filter["yearMax"]);
                }
            }
            if (!is_null($filter["kmMin"])) {
                if (!is_null($filter["kmMax"])) {
                    $q->whereBetween("mileage", [$filter["kmMin"], $filter["kmMax"]]);
                } else {
                    $q->where("mileage", ">=", $filter["kmMin"]);
                }
            } else {
                if (!is_null($filter["kmMax"])) {
                    $q->where("mileage", "<=", $filter["kmMax"]);
                }
            }
        })->with('main_image_api', 'location', 'category')->orderBy("created_at", "desc")->get();

        $e['cats'] = [];
        $e['subcats'] = [];
        $e['colors'] = [];
        $e['fuels'] = [];
        $e['types'] = [];
        $e['situs'] = [];
        $e['sellers'] = [];
        $e['brands'] = [];
        $e['models'] = [];



        $d["brands"] = [];
        $d["models"] = [];
        $d["fuelTypes"] = [];
        $d["types"] = [];
        $d["situations"] = [];
        $d["sellers"] = UserType::select("type_guid", "name_en", "name_ar")->get();


        foreach ($listings as $l) {
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

        $d["fuelTypes"][] = array(
            "name_en" => "Gasoline",
            "name_ar" => "Gasoline",
            "value" => "gasoline"
        );
        $d["fuelTypes"][] = array(
            "name_en" => "Diesel Fuel",
            "name_ar" => "Diesel Fuel",
            "value" => "diesel"
        );
        $d["fuelTypes"][] = array(
            "name_en" => "LPG Gas",
            "name_ar" => "LPG Gas",
            "value" => "lpg"
        );
        $d["fuelTypes"][] = array(
            "name_en" => "Electric Car",
            "name_ar" => "Electric Car",
            "value" => "electric"
        );


        if (count($e["sellers"]) > 0) {
            $d['sellers'] = UserType::select("type_guid", "name_en", "name_ar")->whereIn("type_guid", $e["sellers"])->get();
        }

        if (count($e["brands"]) > 0) {
            $d["brands"] = Brand::whereIn("brand_guid", $e["brands"])->select("brand_guid", "name_en", "name_ar")->get();
        }

        if (!is_null($filter["brand"])) {
            if (count($e["models"]) > 0) {
                $d["models"] = BrandModel::whereIn("model_guid", $e["models"])->select("model_guid", "name_en", "name_ar")->get();
            }
        }

        if (count($listings) > 0) {
            $d['categories'] = Category::select("category_guid", "name_en", "name_ar")->whereIn("category_guid", $e['cats'])->whereNull("related_to")->where("status", "1")->with("children_api")->get();
            $d['colors'] = Color::select("color_guid", "name_en", "name_ar")->whereIn("color_guid", $e['colors'])->where("status", "1")->get();

            foreach ($listings as $l) {
                if (!is_null($l['main_image_api'])) {
                    $l['main_image_api']['name'] = config('api.main_url') . '/storage/listings/' . $l->listing_no . '/' . $l->main_image_api->name;
                } else {
                    continue;
                }
            }
        } else {
            $d['categories'] = Category::select("category_guid", "name_en", "name_ar")->whereNull("related_to")->where("status", "1")->with("children_api")->get();
            $d['colors'] = Color::select("color_guid", "name_en", "name_ar")->where("status", "1")->get();
        }

        $d['filter'] = $filter;
        $d['listings'] = $listings;
        return response($d, 200);
    }

    /**
     * @OA\Post(
     *  path="api.motovago.com/sellerProfile",
     *  operationId="sellerProfile",
     *  summary="Get commercial user profile and listings",
     *  @OA\Parameter(
     *    description="Seller Guid",
     *    in="query",
     *    name="seller_guid",
     *    required=true,
     *    example="13cd8e24-6314-81723871-1827387123",
     *  ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="title", type="string", example="Motovago Seller"),
     *       @OA\Property(property="email", type="string", example="example@motovago.com"),
     *       @OA\Property(property="phone", type="string", example="5351239000"),
     *       @OA\Property(property="description", type="string", example="<p>Lorem ipsum dolor sit amed.</p>"),
     *       @OA\Property(property="logo", type="string", example="https://motovago.com/storage/user/logo.png"),
     *       @OA\Property(property="banner", type="string", example="https://motovago.com/storage/user/banner.png"),
     *  @OA\Property(property="actives", type="array",
     *       @OA\Items(type="object", format="query",
     *       @OA\Property(property="id", type="string", example="9"),
     *       @OA\Property(property="listing_guid", type="string", example="123-412-4123-41"),
     *       @OA\Property(property="user_guid", type="string", example="124123-12312312-12312341"),
     *       @OA\Property(property="location_guid", type="string", example="12312341-1231234123-4123"),
     *       @OA\Property(property="listing_no", type="string", example="234234324324"),
     *       @OA\Property(property="name_en", type="string", example="Urgent!!last chance for this price"),
     *       @OA\Property(property="name_ar", type="string", example="zxczxczxc"),
     *       @OA\Property(property="slug_en", type="string", example="urgent-last-chance-for-this-price"),
     *       @OA\Property(property="slug_ar", type="string", example="asdaszczcxcx"),
     *       @OA\Property(property="year", type="string", example="2008"),
     *       @OA\Property(property="mileage", type="string", example="76903"),
     *       @OA\Property(property="price", type="string", example="123.000"),
     *       @OA\Property(property="currency_guid", type="string", example="12312314-1414141414-1"),
     *       @OA\Property(property="description", type="string", example="Car is new car car car"),
     *       @OA\Property(property="fuel_type", type="string", example="gasoline"),
     *       @OA\Property(property="viewved", type="string", example="2"),
     *       @OA\Property(property="situation", type="string", example="used"),
     *       @OA\Property(property="listing_type", type="string", example="sell"),
     *       @OA\Property(property="latitude", type="string", example="25.284849"),
     *       @OA\Property(property="longitude", type="string", example="23.424141"),
     *       @OA\Property(property="expired", type="string", example="0"),
     *       @OA\Property(property="status", type="string", example="1"),
     *       @OA\Property(property="expire_control", type="string", example="null"),
     *       @OA\Property(property="created_at", type="string", example="2021-11-16T08:56:27.000000Z"),
     *        )
     *      ),
     *    @OA\Property(property="passives", type="array",
     *        @OA\Items(type="object", format="query",
     *       @OA\Property(property="id", type="string", example="9"),
     *       @OA\Property(property="listing_guid", type="string", example="123-412-4123-41"),
     *       @OA\Property(property="user_guid", type="string", example="124123-12312312-12312341"),
     *       @OA\Property(property="location_guid", type="string", example="12312341-1231234123-4123"),
     *       @OA\Property(property="listing_no", type="string", example="234234324324"),
     *       @OA\Property(property="name_en", type="string", example="Urgent!!last chance for this price"),
     *       @OA\Property(property="name_ar", type="string", example="zxczxczxc"),
     *       @OA\Property(property="slug_en", type="string", example="urgent-last-chance-for-this-price"),
     *       @OA\Property(property="slug_ar", type="string", example="asdaszczcxcx"),
     *       @OA\Property(property="year", type="string", example="2008"),
     *       @OA\Property(property="mileage", type="string", example="76903"),
     *       @OA\Property(property="price", type="string", example="123.000"),
     *       @OA\Property(property="currency_guid", type="string", example="12312314-1414141414-1"),
     *       @OA\Property(property="description", type="string", example="Car is new car car car"),
     *       @OA\Property(property="fuel_type", type="string", example="gasoline"),
     *       @OA\Property(property="viewved", type="string", example="2"),
     *       @OA\Property(property="situation", type="string", example="used"),
     *       @OA\Property(property="listing_type", type="string", example="sell"),
     *       @OA\Property(property="latitude", type="string", example="25.284849"),
     *       @OA\Property(property="longitude", type="string", example="23.424141"),
     *       @OA\Property(property="expired", type="string", example="0"),
     *       @OA\Property(property="status", type="string", example="1"),
     *       @OA\Property(property="expire_control", type="string", example="null"),
     *       @OA\Property(property="created_at", type="string", example="2021-11-16T08:56:27.000000Z"),
     *        )
     *      ),
     *
     *
     *        )
     *     ),
     *    @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="Error occured while getting locations list."),
     *        )
     *     )
     * )
     *
     */

    public function sellerProfile(Request $r)
    {
        $actives = [];
        $passives = [];
        /*$user = User::where('token', $r->token)->first();
        if (!$user) {
            $d['msg'] = 'User doesnt exist';
            $d['status'] = 400;
            return response()->json($d, 400);
        }*/

        $seller = User::where("user_guid", $r->seller_guid)->first();

        $listings = Listing::where('user_guid', $r->seller_guid)->with('main_image_api', 'brand', 'currency')->get();
        if ($listings == null) {
            $listings = [];
        }

        foreach ($listings as $key => $l) {
            if ($l->status == '1') {
                if (!is_null($l->main_image_api)) {
                    $l->main_image_api->name = config('api.main_url') . '/storage/listings/' . $l->listing_no . '/' . $l->main_image_api->name;
                }
                $actives[] = $l;
            } else {
                if (!is_null($l->main_image_api)) {
                    $l->main_image_api->name = config('api.main_url') . '/storage/listings/' . $l->listing_no . '/' . $l->main_image_api->name;
                }
                $passives[] = $l;
            }
        }

        $d['seller']["title"] = $seller->name;
        $d['seller']["phone"] = $seller->phone;
        $d['seller']["email"] = $seller->email;
        $d['seller']["description"] = $seller->description;
        if (!is_null($seller->logo)) {
            $d['seller']["logo"] = config("api.main_url") . '/storage/user/' . $seller->logo;
        } else {
            $d['seller']["logo"] = config("api.main_url") . '/site/assets/images/no-listing.png';
        }
        if (!is_null($seller->background)) {
            $d['seller']["banner"] = config("api.main_url") . '/storage/user/' . $seller->background;
        } else {
            $d['seller']["banner"] = config("api.main_url") . '/site/assets/images/cover-img.png';
        }

        $d['actives'] = $actives;
        $d['passives'] = $passives;
        $d['listings'] = $listings;

        return response()->json($d, 200);
    }
}
