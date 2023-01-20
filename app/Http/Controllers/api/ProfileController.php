<?php

namespace App\Http\Controllers\api;

use DB;
use Str;
use App\Models\User;
use App\Models\Listing;
use App\Models\Setting;
use App\Models\Location;
use App\Models\UserType;
use App\Models\ListingImage;
use Illuminate\Http\Request;
use App\Models\ListingAttribute;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Support\Facades\Hash;
use OneSignal;

class ProfileController extends Controller
{

    /**
     * @OA\Post(
     *  path="api.motovago.com/updateProfileInfo",
     *  operationId="updateProfileInfo",
     *  summary="Update user profile general infos",
     *  @OA\Parameter(
     *    description="Token",
     *    in="query",
     *    name="token",
     *    required=true,
     *    example="6f191b5c98d2517d9c04670abecca99c",
     *  ),
     *  @OA\Parameter(
     *    description="Fullname",
     *    in="query",
     *    name="fullname",
     *    required=true,
     *    example="Jhon Doe",
     *  ),
     *  @OA\Parameter(
     *    description="Email",
     *    in="query",
     *    name="email",
     *    required=true,
     *    example="example@test.com",
     *  ),
     *  @OA\Parameter(
     *    description="Phone",
     *    in="query",
     *    name="phone",
     *    required=true,
     *    example="+34123187238",
     *  ),
     *  @OA\Parameter(
     *    description="Country",
     *    in="query",
     *    name="country",
     *    required=true,
     *    example="03d6cbf2-a43f-4143-8f9c-288485de12e4",
     *  ),
     *  @OA\Parameter(
     *    description="Logo (Kurumsal İse)",
     *    in="query",
     *    name="logo",
     *    required=true,
     *    example="logo.jpg",
     *  ),
     *  @OA\Parameter(
     *    description="Background (Kurumsal İse)",
     *    in="query",
     *    name="background",
     *    required=true,
     *    example="background.jpg",
     *  ),
     *  @OA\Parameter(
     *    description="Description (Kurumsal İse)",
     *    in="query",
     *    name="description",
     *    required=true,
     *    example="Lorem ipsum dolor sit amed",
     *  ),
     *  @OA\Parameter(
     *    description="Gender (Bireysel İse)",
     *    in="query",
     *    name="gender",
     *    required=true,
     *    example="m/f",
     *  ),
     *  @OA\Parameter(
     *    description="Birthday (Bireysel İse)",
     *    in="query",
     *    name="birthday",
     *    required=true,
     *    example="1985-03-03",
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="200"),
     *       @OA\Property(property="msg", type="string", example="Success message"),
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

    public function updateProfileInfo(Request $r)
    {
        $token = $r->token;
        $user = User::where('token', $token)->where('status', '1')->first();

        if (is_null($user)) {
            $d['status'] = 400;
            $d['msg'] = 'User not found!';
            return response()->json($d, 400);
        }
        $user_guid = $user->user_guid;

        if ($user->type->id == '1') {
            $user->name = $r->name;
            $user->slug = Str::slug($r->name);
            $user->phone = $r->phone;
            $user->email = $r->email;
            $user->gender = $r->gender;
            $user->birthday = $r->birthday;
            $user->update();
        }
        if ($user->type->id == '2') {
            $user->name = $r->name;
            $user->slug = Str::slug($r->name);
            $user->phone = $r->phone;
            $user->email = $r->email;

            if($r->logo != "null" && $r->logo != null) {
                $logoname = random_int(10000000000000, 99999999999999) . '.png';
                $logo = base64_decode($r->logo[0]);
                file_put_contents(storage_path('app/public/user/' . $logoname), $logo);
                $user->logo = $logoname;
            }

            if($r->background != "null" && $r->background != null) {
                $bannername = random_int(10000000000000, 99999999999999) . '.png';
                $banner = base64_decode($r->background[0]);
                file_put_contents(storage_path('app/public/user/' . $bannername), $banner);
                $user->background = $bannername;
            }


            $user->description = $r->description;
            $user->update();
        }

        $checkUser = User::where('token', $r->token)->select('id', 'name', 'phone', 'email', 'type_guid', 'birthday', 'gender', 'logo', 'background', 'description', 'status', 'country_guid', 'token', 'updated_at')->with('type_api', 'country_api')->first();

        $user = $checkUser;

        if(!is_null($checkUser->logo)) {
            $user["logo"] = config('api.main_url') . '/storage/user/'.$checkUser->logo;
        }
        if(!is_null($checkUser->background)) {
            $user["banner"] = config('api.main_url') . '/storage/user/'.$checkUser->background;
        } else {
            $user["banner"] = null;
        }

        $user["usertype"] = $checkUser->type_api->name_en !== "Commercial" ? "standard" : "commercial";

        $d['user'] = $user;
        $d['msg'] = __('alert.profile_update_success');
        $d['status'] = 200;
        return response()->json($d, 200);
    }

    /**
     * @OA\Post(
     *  path="api.motovago.com/changePassword",
     *  operationId="changePassword",
     *  summary="Chage password",
     *  @OA\Parameter(
     *    description="Token",
     *    in="query",
     *    name="token",
     *    required=true,
     *    example="6f191b5c98d2517d9c04670abecca99c",
     *  ),
     *  @OA\Parameter(
     *    description="Current Password",
     *    in="query",
     *    name="old_password",
     *    required=true,
     *    example="123123",
     *  ),
     *  @OA\Parameter(
     *    description="New Password",
     *    in="query",
     *    name="new_password",
     *    required=true,
     *    example="abc123",
     *  ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="200"),
     *       @OA\Property(property="msg", type="string", example="Success message"),
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

    public function changePassword(Request $r)
    {
        $token = $r->token;
        $user = User::where('token', $token)->where('status', '1')->first();

        if (is_null($user)) {
            $d['status'] = 400;
            $d['msg'] = 'User not found!';
            return response()->json($d, 400);
        }
        $user_guid = $user->user_guid;

        if (Hash::check($r->old_password, $user->password)) {
            $user->password = Hash::make($r->new_password);
            $user->update();


            $d['status'] = 200;
            $d['msg'] = 'Password successfully updated.';
            return response()->json($d, 200);
        } else {
            $d['status'] = 400;
            $d['msg'] = 'Password not matched please try again.';
            return response()->json($d, 400);
        }
    }

    /**
     * @OA\Post(
     *  path="api.motovago.com/myListings",
     *  operationId="mylisting",
     *  summary="mylisting",
     *  @OA\Parameter(
     *    description="Token",
     *    in="query",
     *    name="token",
     *    required=true,
     *    example="6f191b5c98d2517d9c04670abecca99c",
     *  ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
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
     *  @OA\Property(property="actives", type="array",
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

    public function myListings(Request $r)
    {
        $actives = [];
        $passives = [];
        $user = User::where('token', $r->token)->first();
        if (!$user) {
            $d['msg'] = 'User doesnt exist';
            $d['status'] = 400;
            return response()->json($d, 400);
        }

        $listings = Listing::where('user_guid', $user->user_guid)->with('main_image_api', 'brand')->withCount('favorite','messages')->get();
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

        $d['actives'] = $actives;
        $d['passives'] = $passives;
        $d['listings'] = $listings;

        return response()->json($d, 200);
    }

    /**
     * @OA\Post(
     *  path="api.motovago.com/editListing",
     *  operationId="editListing",
     *  summary="editListing",
     *  @OA\Parameter(
     *    description="Token",
     *    in="query",
     *    name="token",
     *    required=true,
     *    example="6f191b5c98d2517d9c04670abecca99c",
     *  ),
     *     @OA\Parameter(
     *    description="name_en",
     *    in="query",
     *    name="name_en",
     *    required=true,
     *    example="Clio Sahibinden",
     *  ),
     *          @OA\Parameter(
     *    description="listing_guid",
     *    in="query",
     *    name="listing_guid",
     *    required=true,
     *    example="qweqwewqe1qweqwe1233",
     *  ),
     *    @OA\Parameter(
     *    description="description",
     *    in="query",
     *    name="description",
     *    required=true,
     *    example="Clio Sahibinden satılık",
     *  ),
     *    @OA\Parameter(
     *    description="description",
     *    in="query",
     *    name="description",
     *    required=true,
     *    example="Clio Sahibinden satılık",
     *  ),
     *
     *    @OA\Parameter(
     *    description="price",
     *    in="query",
     *    name="price",
     *    required=true,
     *    example="123.0000",
     *  ),
     *    @OA\Parameter(
     *    description="currency_guid",
     *    in="query",
     *    name="currency_guid",
     *    required=true,
     *    example="123123-14ase3123-qdac11",
     *  ),
     *    @OA\Parameter(
     *    description="price",
     *    in="query",
     *    name="price",
     *    required=true,
     *    example="123.0000",
     *  ),
     *    @OA\Parameter(
     *    description="country_guid",
     *    in="query",
     *    name="country_guid",
     *    required=true,
     *    example="123123123asaasas0",
     *  ),
     *    @OA\Parameter(
     *    description="state_guid",
     *    in="query",
     *    name="state_guid",
     *    required=true,
     *    example="123123123asaasaas111sas0",
     *  ),
     *    @OA\Parameter(
     *    description="city_guid",
     *    in="query",
     *    name="city_guid",
     *    required=true,
     *    example="123123zxczxc123asaasaas111sas0",
     *  ),
     *    @OA\Parameter(
     *    description="latitude",
     *    in="query",
     *    name="latitude",
     *    required=true,
     *    example="11111",
     *  ),
     *    @OA\Parameter(
     *    description="longitude",
     *    in="query",
     *    name="longitude",
     *    required=true,
     *    example="1112341414",
     *  ),
     *  @OA\Parameter(
     *    description="images",
     *    in="query",
     *    name="images",
     *    required=true,
     *    example="array",
     *  ),
     *  @OA\Parameter(
     *    description="new_images",
     *    in="query",
     *    name="new_images",
     *    required=true,
     *    example="array",
     *  ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="200"),
     *       @OA\Property(property="msg", type="string", example="Listing successfully updated."),
     *        )
     *     ),
     *    @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="Error ocurred while updating listing please try again."),
     *        )
     *     )
     * )
     *
     */

    public function editListing(Request $r)
    {

        $user = User::where('token', $r->token)->first();
        if (!$user) {
            $d['msg'] = 'User doesnt exist';
            $d['status'] = 400;
            return response()->json($d, 400);
        }

        $listing = Listing::where('listing_guid', $r->listing_guid)->where('user_guid', $user->user_guid)->first();
        if (!$listing) {
            $d['msg'] = 'Listing not found';
            $d['status'] = 400;
            return response()->json($d, 400);
        }
        if ($r->price) {
            $favorites = Favorite::where('listing_guid', $listing->listing_guid)->get();
            if ($listing->price != $r->price) {
                if (count($favorites) > 0) {
                    foreach ($favorites as $favorite) {
                        $user_guid = User::where('user_guid', $favorite->user_guid)->first()->user_guid;
                        if ($user_guid) {
                            $array[] = ["field" => "tag", "key" => "user_guid", "relation" => "=", "value" => $user_guid];
                            $array[] = ["operator" => "OR"];
                        }
                    }
                    array_pop($array);
                    $type['type'] = 'favorite';
                    OneSignal::sendNotificationUsingTags(
                        "The price of the product you added to favorites has changed.",
                        $array,
                        $url = null,
                        $data = $type,
                        $buttons = null,
                        $schedule = null
                    );
                }
            }
        }
        try {
            DB::beginTransaction();
            $listing->name_en = $r->name_en;
            $listing->slug_en = Str::slug($r->name_en);
            $listing->description = $r->description;
            $listing->price = $r->price;
            $listing->currency_guid = $r->currency_guid;
            $listing->country_guid = $r->country_guid;
            $listing->state_guid = $r->state_guid;
            if ($r->has('city_guid')) {
                $listing->city_guid = $r->city_guid;
            }
            $listing->latitude = $r->latitude;
            $listing->longitude = $r->longitude;
            $listing->update();

            ListingImage::where('listing_guid', $listing->listing_guid)->delete();
            if (!is_null($r->images)) {
                $photos = $r->images;
                foreach ($photos as $k => $np) {
                    $exp = explode('/', $np);
                    $img = $exp[count($exp) - 1];
                    $imgs = new ListingImage();
                    $imgs->image_guid = Str::uuid();
                    $imgs->listing_guid = $listing->listing_guid;
                    $imgs->name = $img;
                    if ($k == 0) {
                        $imgs->is_main = '1';
                    } else {
                        $imgs->is_main = '0';
                    }
                    $imgs->save();
                }
            }

            if (!is_null($r->new_images)) {
                $newphotos = $r->new_images;
                foreach ($newphotos as $k => $im) {

                    $imgname = random_int(10000000000000, 99999999999999) . '.png';
                    $img = base64_decode($im);
                    file_put_contents(storage_path('app/public/listings/' . $listing->listing_no . '/' . $imgname), $img);

                    $imgs = new ListingImage();
                    $imgs->image_guid = Str::uuid();
                    $imgs->listing_guid = $listing->listing_guid;
                    $imgs->name = $imgname;
                    if (count($photos) == 0) {
                        if ($k == 0) {
                            $imgs->is_main = '1';
                        } else {
                            $imgs->is_main = '0';
                        }
                    }
                    $imgs->save();
                }
            }

            if ($r->has('extras') && count($r->extras) > 0) {
                ListingAttribute::where("listing_guid", $listing->listing_guid)->delete();
                foreach ($r->extras as $k => $extras) {
                    foreach ($extras as $e) {
                        $attr = new ListingAttribute();
                        $attr->la_guid = Str::uuid();
                        $attr->listing_guid = $listing->listing_guid;
                        $attr->ag_guid = $k;
                        $attr->attribute_guid = $e;
                        $attr->save();
                    }
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            $d['msg'] = 'Error ocurred while updating listing please try again.';
            $d['status'] = 400;
            return response()->json($d, 400);
        }

        $d['msg'] = 'Listing successfully updated.';
        $d['status'] = 200;
        return response()->json($d, 200);
    }

    /**
     * @OA\Post(
     *  path="api.motovago.com/createListing",
     *  operationId="createListing",
     *  summary="createListing",
     *  @OA\Parameter(
     *    description="Token",
     *    in="query",
     *    name="token",
     *    required=true,
     *    example="6f191b5c98d2517d9c04670abecca99c",
     *  ),
     *    @OA\Parameter(
     *    description="location_guid",
     *    in="query",
     *    name="location_guid",
     *    required=true,
     *    example="123123-123123-123213",
     *  ),
     *  @OA\Parameter(
     *    description="catregory_guid",
     *    in="query",
     *    name="category_guid",
     *    required=true,
     *    example="12341123-14141-1414",
     *  ),
     * @OA\Parameter(
     *    description="subcategory_guid",
     *    in="query",
     *    name="subcategory_guid",
     *    required=true,
     *    example="123123-4141411414-1",
     *  ),
     * @OA\Parameter(
     *    description="brand_guid",
     *    in="query",
     *    name="brand_guid",
     *    required=true,
     *    example="qw13q131qwe123131-4141",
     *  ),
     * @OA\Parameter(
     *    description="model_guid",
     *    in="query",
     *    name="model_guid",
     *    required=true,
     *    example="131313-414141-4141414",
     *  ),
     * @OA\Parameter(
     *    description="trim_guid",
     *    in="query",
     *    name="trim_guid",
     *    required=false,
     *    example="14141-141414-4141-",
     *  ),
     * @OA\Parameter(
     *    description="name",
     *    in="query",
     *    name="name",
     *    required=true,
     *    example="Satilik ilan",
     *  ),
     * @OA\Parameter(
     *    description="lang",
     *    in="query",
     *    name="lang",
     *    required=true,
     *    example="en/ar",
     *  ),
     * @OA\Parameter(
     *    description="year",
     *    in="query",
     *    name="year",
     *    required=true,
     *    example="2018",
     *  ),
     * @OA\Parameter(
     *    description="mileage",
     *    in="query",
     *    name="mileage",
     *    required=true,
     *    example="123000",
     *  ),
     * @OA\Parameter(
     *    description="price",
     *    in="query",
     *    name="price",
     *    required=true,
     *    example="120.000",
     *  ),
     * @OA\Parameter(
     *    description="location_guid",
     *    in="query",
     *    name="location_guid",
     *    required=true,
     *    example="location_guid",
     *  ),
     * @OA\Parameter(
     *    description="description",
     *    in="query",
     *    name="description",
     *    required=true,
     *    example="araba cok acil satilik",
     *  ),
     * @OA\Parameter(
     *    description="color_guid",
     *    in="query",
     *    name="color_guid",
     *    required=true,
     *    example="123123-44414-1-111",
     *  ),
     * @OA\Parameter(
     *    description="fuel_type",
     *    in="query",
     *    name="fuel_type",
     *    required=true,
     *    example="gasoline",
     *  ),
     * @OA\Parameter(
     *    description="situation",
     *    in="query",
     *    name="situation",
     *    required=true,
     *    example="used",
     *  ),
     * @OA\Parameter(
     *    description="listing_type",
     *    in="query",
     *    name="listing_type",
     *    required=true,
     *    example="sell",
     *  ),
     * @OA\Parameter(
     *    description="country_guid",
     *    in="query",
     *    name="country_guid",
     *    required=true,
     *    example="19191919-1313139139",
     *  ),
     * @OA\Parameter(
     *    description="state_guid",
     *    in="query",
     *    name="state_guid",
     *    required=true,
     *    example="123913289-348928934-2349",
     *  ),
     * @OA\Parameter(
     *    description="city_guid",
     *    in="query",
     *    name="city_guid",
     *    required=true,
     *    example="148914891489-143913498134-139813489",
     *  ),
     * @OA\Parameter(
     *    description="images",
     *    in="query",
     *    name="images",
     *    required=true,
     *    example="['iVBORw0KGgoAAAANSUhEUgAAAOcAAAH0CAYAA.....']",
     *  ),
     * @OA\Parameter(
     *    description="longitude",
     *    in="query",
     *    name="longitude",
     *    required=true,
     *    example="133.411",
     *  ),
     * @OA\Parameter(
     *    description="latitude",
     *    in="query",
     *    name="latitude",
     *    required=true,
     *    example="144.1411",
     *  ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="200"),
     *       @OA\Property(property="msg", type="string", example="Listing successfully created."),
     *        )
     *     ),
     *    @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="There is an error occurred while creating listing please try again."),
     *        )
     *     )
     * )
     *
     */

    public function createListing(Request $r)
    {
        $user = User::where('token', $r->token)->where('status', '1')->first();
        $userType = UserType::where('type_guid', $user->type_guid)->first();
        $active_listings = Listing::where("user_guid", $user->user_guid)->where("status", "1")->count();


        if (!$user) {
            $d['status'] = 400;
            $d['msg'] = 'User not found !';
            return response()->json($d, 400);
        }

        if ($userType->free_listing <= $active_listings) {
            $d['status'] = 400;
            $d['msg'] = 'Listing create limit is reached !';
            return response()->json($d, 400);
        }

        $validity_duration = Setting::where("slug", "validity-date")->first()->setting_value;
        if ($validity_duration == '' || is_null($validity_duration)) {
            $validity_duration = 30;
        }

        $location = Location::where('location_guid', $r->location_guid)->first();

        try {
            DB::beginTransaction();
            $newListing = new Listing();
            $newListing->listing_guid = Str::uuid();
            $newListing->user_guid = $user->user_guid;
            $newListing->location_guid = $r->location_guid;
            $newListing->category_guid = $r->category_guid;
            $newListing->subcategory_guid = $r->subcategory_guid;
            $newListing->brand_guid = $r->brand_guid;
            $newListing->model_guid = $r->model_guid;
            if ($r->trim_guid != '' && !is_null($r->trim_guid)) {
                $newListing->trim_guid = $r->trim_guid;
            }
            $newListing->listing_no = random_int(10000000, 99999999);
            if ($r->lang == 'en') {
                $newListing->name_en = $r->name;
                $newListing->slug_en = Str::slug($r->name);
            }
            if ($r->lang == 'ar') {
                $newListing->name_ar = $r->name;
                $newListing->slug_ar = Str::slug($r->name);
            }
            $newListing->year = $r->year;
            $newListing->mileage = $r->mileage;
            $newListing->price = $r->price;
            $newListing->currency_guid = $location->currency_guid;
            $newListing->description = $r->description;
            $newListing->color_guid = $r->color_guid;
            $newListing->fuel_type = $r->fuel_type;
            $newListing->expire_at = date('Y-m-d H:i:s', strtotime('+' . $validity_duration . ' days'));
            $newListing->situation = $r->situation;
            $newListing->listing_type = $r->listing_type;
            $newListing->country_guid = $r->country_guid;
            $newListing->state_guid = $r->state_guid;
            $newListing->city_guid = $r->city;
            $newListing->latitude = $r->latitude;
            $newListing->longitude = $r->longitude;
            $newListing->save();

            $k = 0;
            foreach ($r->images as $im) {
                $path = storage_path('app/public/listings/' . $newListing->listing_no);
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $imgname = random_int(10000000000000, 99999999999999) . '.png';
                $img = base64_decode($im);
                file_put_contents(storage_path('app/public/listings/' . $newListing->listing_no . '/' . $imgname), $img);

                $imgs = new ListingImage();
                $imgs->image_guid = Str::uuid();
                $imgs->listing_guid = $newListing->listing_guid;
                $imgs->name = $imgname;
                if ($k == 0) {
                    $imgs->is_main = '1';
                } else {
                    $imgs->is_main = '0';
                }
                $imgs->save();
                $k++;
            }

            DB::commit();
        } catch (\Throwable $th) {
            Log::warning("Hata geldi");
            Log::warning(json_encode($th));
            DB::rollback();
            $d['status'] = 400;
            $d['msg'] = 'There is an error occurred while creating listing please try again.';
            return response()->json($d, 400);
        }


        $d['msg'] = "Listing successfully created.";
        $d['status'] = 200;
        return response()->json($d, 200);
    }
    /**
     * @OA\Post(
     *  path="api.motovago.com/passiveListing",
     *  operationId="passiveListing",
     *  summary="passiveListing",
     *  @OA\Parameter(
     *    description="Token",
     *    in="query",
     *    name="token",
     *    required=true,
     *    example="6f191b5c98d2517d9c04670abecca99c",
     *  ),
     *  @OA\Parameter(
     *    description="listing_guid",
     *    in="query",
     *    name="listing_guid",
     *    required=true,
     *    example="123213123123",
     *  ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="200"),
     *       @OA\Property(property="msg", type="string", example="Listing status is successfully updated."),
     *        )
     *     ),
     *    @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="There is an error occurred while creating listing please try again."),
     *        )
     *     )
     * )
     *
     */

    public function passiveListing(Request $r)
    {
        $user = User::where('token', $r->token)->first();

        if (!$user) {
            $d['status'] = 400;
            $d['msg'] = 'User not found !';
            return response()->json($d, 400);
        }

        $listing = Listing::where('listing_guid', $r->listing_guid)->where('user_guid', $user->user_guid)->where('status', '1')->first();

        if (!$listing) {
            $d['msg'] = 'Listing doesnt exists or doesnt belong to you or its already passive ';
            $d['status'] = 400;
            return response()->json($d, 400);
        }

        try {
            DB::beginTransaction();
            $listing->status = '0';
            $listing->update();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            $d['msg'] = 'An error occurred while updating listing please try again. ';
            $d['status'] = 400;
            return response()->json($d, 400);
        }

        $d['msg'] = 'Listing status is successfully updated.';
        $d['status'] = 200;
        return response()->json($d, 200);
    }
    /**
     * @OA\Post(
     *  path="api.motovago.com/activeListing",
     *  operationId="activeListing",
     *  summary="activeListing",
     *  @OA\Parameter(
     *    description="Token",
     *    in="query",
     *    name="token",
     *    required=true,
     *    example="6f191b5c98d2517d9c04670abecca99c",
     *  ),
     *  @OA\Parameter(
     *    description="listing_guid",
     *    in="query",
     *    name="listing_guid",
     *    required=true,
     *    example="123213123123",
     *  ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="200"),
     *       @OA\Property(property="msg", type="string", example="Listing status is successfully updated."),
     *        )
     *     ),
     *    @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="There is an error occurred while creating listing please try again."),
     *        )
     *     )
     * )
     *
     */
    public function activeListing(Request $r)
    {
        $user = User::where('token', $r->token)->first();

        if (!$user) {
            $d['status'] = 400;
            $d['msg'] = 'User not found !';
            return response()->json($d, 400);
        }

        $listing = Listing::where('listing_guid', $r->listing_guid)->where('user_guid', $user->user_guid)->where('status', '0')->first();

        if (!$listing) {
            $d['msg'] = 'Listing doesnt exists or doesnt belong to you or its already active ';
            $d['status'] = 400;
            return response()->json($d, 400);
        }
        try {
            DB::beginTransaction();
            $listing->status = '1';
            $listing->update();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            $d['msg'] = 'An error occurred while updating listing please try again. ';
            $d['status'] = 400;
            return response()->json($d, 400);
        }

        $d['msg'] = 'Listing status is successfully updated.';
        $d['status'] = 200;
        return response()->json($d, 200);
    }

    /**
     * @OA\Post(
     *  path="api.motovago.com/getFavorites",
     *  operationId="getFavorites",
     *  summary="getFavorites",
     *  @OA\Parameter(
     *    description="Token",
     *    in="query",
     *    name="token",
     *    required=true,
     *    example="6f191b5c98d2517d9c04670abecca1399c",
     *  ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *  @OA\Property(property="favorites", type="array",
     *        @OA\Items(type="object", format="query",
     *       @OA\Property(property="status", type="string", example="200"),
     *       @OA\Property(property="image", type="string", example="https://motovago.com/storage/listings/43513900/629631313981896559.png"),
     *       @OA\Property(property="listing_no", type="string", example="123123"),
     *       @OA\Property(property="listing_guid", type="string", example="qeqe1eqwe23rq-123qwe-14123"),
     *       @OA\Property(property="name_en", type="string", example="qqqq"),
     *       @OA\Property(property="name_ar", type="string", example="eqeqeqe"),
     *       @OA\Property(property="price", type="string", example="131313"),
     *       @OA\Property(property="currency", type="string", example="QAR"),
     *       @OA\Property(property="brand_en", type="string", example="AC"),
     *       @OA\Property(property="brand_ar", type="string", example="AC"),
     *        )))
     *     ),
     *    @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="There is an error occurred while creating listing please try again."),
     *        )
     *     )
     * )
     *
     */

    public function getFavorites(Request $r)
    {
        $user = User::where('token', $r->token)->where('status', '1')->first();

        if ($user) {
            $favItem = Favorite::where('user_guid', $user->user_guid)->has('listing.main_image')->with('listing.main_image', 'listing.currency', 'listing.brand')->get();
            if (count($favItem) > 0) {
                foreach ($favItem as $key => $fi) {
                    $d['favorites'][$key]['image'] = config('api.main_url') . '/storage/listings/' . $fi->listing->listing_no . '/' . $fi->listing->main_image->name;
                    $d['favorites'][$key]['listing_no'] = $fi->listing->listing_no;
                    $d['favorites'][$key]['listing_guid'] = $fi->listing->listing_guid;
                    $d['favorites'][$key]['name_en'] = $fi->listing->name_en;
                    $d['favorites'][$key]['name_ar'] = $fi->listing->name_ar;
                    $d['favorites'][$key]['price'] = $fi->listing->price;
                    $d['favorites'][$key]['currency'] = $fi->listing->currency->label;
                    $d['favorites'][$key]['brand_en'] = $fi->listing->brand->name_en;
                    $d['favorites'][$key]['brand_ar'] = $fi->listing->brand->name_ar;
                }
                return response()->json($d, 200);
            } else {
                $d['status'] = 200;
                $d['favorites'] = [];
                return response()->json($d, 200);
            }
        } else {
            $d['msg'] = 'User doesnt exist.';
            $d['status'] = 400;
            return response()->json($d, 400);
        }
    }

    public function deleteProfile(Request $r)
    {
        $user = User::where('token', $r->token)->first();
        if (!$user) {
            $d['msg'] = 'User doesnt exist.';
            $d['status'] = 400;
            return response()->json($d, 400);
        }

        $user->delete();
        $d['msg'] = 'success';
        $d['status'] = 200;
        return response()->json($d, 200);
    }
}
