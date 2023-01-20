<?php

namespace App\Http\Controllers\api;

use Illuminate\Mail\Mailable;
use Str;
use Auth;
use Hash;
use Session;
use Validator;
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
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;


class AuthController extends Controller
{
    /**
     * @OA\Post(
     *  path="https://api.motovago.com/do_login",
     *  operationId="login",
     * description="Login by email & password",
     *  summary="User Login",
     * @OA\Parameter(
     *    description="Email",
     *    in="path",
     *    name="email",
     *    required=true,
     *    example="efe@appricot.com.tr",
     * ),@OA\Parameter(
     *    description="Password",
     *    in="path",
     *    name="password",
     *    required=true,
     *    example="758412",
     * ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="name", type="string", example="Efe Bayol"),
     *       @OA\Property(property="phone", type="string", example="5324124098"),
     *       @OA\Property(property="email", type="string", example="efe@appricot.com.tr"),
     *       @OA\Property(property="status", type="tinyint", example="1"),
     *       @OA\Property(property="token", type="string", example="6f261b5ee8d2517d9c04670abecca99b"),
     *  @OA\Property(property="type", type="array",
     * @OA\Items(type="object",
     * format="query",
     *       @OA\Property(property="name_en", type="string", example="Standard"),
     * )),
     *       @OA\Property(property="country_guid", type="string", example="128631-12319283-123123192-213123"),
     *  @OA\Property(property="country", type="array",
     * @OA\Items(type="object",
     * format="query",
     *       @OA\Property(property="name", type="string", example="Kuwait"),
     * )),
     *
     *
     *        )
     *     ),
     *  @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="There is an error occured while logging in please try again"),
     *        )
     *     ),
     * )
     *
     */

    public function doLogin(Request $r)
    {
        $checkUser = User::where('email', $r->email)->with('type_api', 'country_api')->first();

        if ($checkUser && $checkUser->status == 1) {
            $remember = $r->has('remember_me') ? true : false;

            if (Auth::guard('member')->attempt(['email' => $r->email, 'password' => $r->password], $remember)) {
                $token = md5(sha1($checkUser->user_guid . '||' . $checkUser->email . '||' . date('Y-m-d H:i:s')));
                $checkUser->update(['token' => $token]);

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
                $d['status'] = 200;
                return response()->json($d, 200);
            } else {
                $d['msg'] = "There is an error occured while logging in please try again";
                $d['status'] = 400;
                return response()->json($d, 400);
            }
        } else {
            $d['msg'] = "Invalid email or password";
            $d['status'] = 400;
            return response()->json($d, 400);
        }
    }
    /**
     * @OA\Post(
     *  path="https://api.motovago.com/do_recovery",
     *  operationId="Account recovery",
     * description="Post email parameter ",
     *  summary="Account Recovery",
     * @OA\Parameter(
     *    description="Account Email",
     *    in="query",
     *    name="email",
     *    required=true,
     *    example="efe@appricot.com.tr",
     * ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="200"),
     *       @OA\Property(property="msg", type="string", example="Recovery mail sended successfully."),
     *        )
     *     ),
     *  @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="Invalid E-mail"),
     *
     *        )
     *     ),
     *    @OA\Response(
     *    response=401,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="401"),
     *       @OA\Property(property="msg", type="string", example="Password recovery attempt reached limit try again later."),
     *
     *        )
     *     ),
     *     @OA\Response(
     *    response=402,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="402"),
     *       @OA\Property(property="msg", type="string", example="Password recovery time reached limit try again."),
     *
     *        )
     *     ),
     *
     * )
     *
     */
    public function doRecovery(Request $r)
    {
        $recovery_code = random_int(100000, 999999);

        $user = User::where('email', $r->email)->first();

        if (!isset($user)) {
            $d['status'] = 400;
            $d['msg'] = "Invalid E-mail";
            return response()->json($d, 400);
        }

        if ($user->pw_recovery_attempt >= 3) {
            $d['msg'] = "Password recovery attempt reached limit try again later.";
            $d['status'] = 401;

            return response()->json($d, 401);
        }
        if (!is_null($user->pw_recovery_code) && $user->pw_recovery_validity > Carbon::now()->timestamp) {
            $d['msg'] = "Password recovery time reached limit try again.";
            $d['status'] = 402;
            return response()->json($d, 402);
        }

        $user->pw_recovery_code = $recovery_code;
        $user->pw_recovery_validity = Carbon::now()->addMinutes('15');
        $user->pw_recovery_attempt = $user->pw_recovery_attempt + 1;
        $user->update();

        Mail::to($user->email)->queue(new PasswordRecoveryCode($user));

        $d['msg'] = "Recovery mail sended successfully";
        $d['status'] = 200;
        return response()->json($d, 200);
    }
    /**
     * @OA\Post(
     *  path="https://api.motovago.com/recovery_code",
     *  operationId="Recovery Code",
     * description="Post e-mail and recovery code ",
     *  summary="Get Recovery Code",
     * @OA\Parameter(
     *    description="Email",
     *    in="query",
     *    name="email",
     *    required=true,
     *    example="efe@appricot.com.tr",
     * ),
     *   @OA\Parameter(
     *    description="Recovery Code",
     *    in="query",
     *    name="recovery_code",
     *    required=true,
     *    example="921347",
     * ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="pwr_email_address", type="string", example="efe@appricot.com.tr"),
     *       @OA\Property(property="pwr_recovery_code", type="integer", example="921347"),
     *       @OA\Property(property="pwr_user_guid", type="string", example="54cddd50-8369-4255-bfbf-3b30c9e49f89"),
     *
     *        )
     *     ),
     *  @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="error", type="string", example="Invalid Email or Recovery code please try again."),
     *
     *        )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="401"),
     *       @OA\Property(property="msg", type="string", example="Please fill recovery code area."),
     *
     *        )
     *     ),
     *
     * )
     *
     */

    public function recoveryCode(Request $r)
    {
        if ($r->has('recovery_code')) {
            $checkUser = User::where('pw_recovery_code', $r->recovery_code)->where('email', $r->email)->first();
            if ($checkUser) {
                $info = [];
                $info['pwr_email_address'] = $r->email;
                $info['pwr_recovery_code'] = $r->recovery_code;
                $info['pwr_user_guid'] = $checkUser->user_guid;
                $info['status'] = 200;
                return response()->json($info, 200);
            } else {
                $d['status'] = 400;
                $d['msg'] = 'Recovery code wrong.';
                return response()->json($d, 400);
            }
        }
        $d['msg'] = "Please fill recovery code area.";
        $d['status'] = 401;
        return response()->json($d, 400);
    }

    /**
     * @OA\Post(
     *  path="https://api.motovago.com/set_password",
     *  operationId="password.",
     * description="Set new password ",
     *  summary="Set new password",
     * @OA\Parameter(
     *    description="New Password",
     *    in="query",
     *    name="password",
     *    required=true,
     *    example="1123241123asd",
     * ),

     *    @OA\Parameter(
     *    description="user_guid",
     *    in="query",
     *    name="user_guid",
     *    required=true,
     *    example="1231237-23123213-4124124",
     * ),

     *
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="200"),
     *       @OA\Property(property="success", type="string", example="Password Updated Successfully."),
     *        )
     *     ),
     *  @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="Password not confirmed."),
     *
     *        )
     *     ),
     *   @OA\Response(
     *    response=401,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="401"),
     *       @OA\Property(property="msg", type="string", example="Please fill the password area."),
     *
     *        )
     *     ),
     *
     * )
     *
     */

    public function setPassword(Request $r)
    {

        if ($r->has('password')) {
            if ($r->has('user_guid')) {

                $user_guid = $r->user_guid;

                $checkUser = User::where('user_guid', $user_guid)->first();
                if ($checkUser) {
                    $checkUser->password = Hash::make($r->password);
                    $checkUser->pw_recovery_code = null;
                    $checkUser->pw_recovery_validity = null;
                    $checkUser->pw_recovery_attempt = 0;
                    $checkUser->update();

                    Mail::to($checkUser->email)->queue(new PasswordChanged($checkUser));


                    $d['msg'] = "Password updated successfully.";
                    $d['status'] = 200;
                    return response()->json($d, 200);
                }
            } else {
                $d['msg'] = 'User not found.';
                $d['status'] = 400;
                return response()->json($d, 400);
            }
        }
        $d['msg'] = "Please fill the password area.";
        $d['status'] = 401;
        return response()->json($d, 400);
    }


    /**
     * @OA\Post(
     *  path="https://api.motovago.com/do_register",
     *  operationId="Register.",
     * description="Register new user",
     *  summary="",
     * @OA\Parameter(
     *    description="Name",
     *    in="query",
     *    name="fullname",
     *    required=true,
     *    example="Suleyman Mutlu",
     * ),
     * @OA\Parameter(
     *    description="Phone Number",
     *    in="query",
     *    name="phone",
     *    required=true,
     *    example="5548271212",
     * ),
     *  @OA\Parameter(
     *    description="Email",
     *    in="query",
     *    name="email",
     *    required=true,
     *    example="suleyman@appricot.com.tr",
     * ),
     *    @OA\Parameter(
     *    description="Password",
     *    in="query",
     *    name="password",
     *    required=true,
     *    example="123123123",
     * ),
     *    @OA\Parameter(
     *    description="Agreement",
     *    in="query",
     *    name="agreement",
     *    required=true,
     *    example="true",
     * ),
     *    @OA\Parameter(
     *    description="Data protection",
     *    in="query",
     *    name="data_protection",
     *    required=true,
     *    example="true",
     * ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="msg", type="string", example="User registered successfully."),
     *       @OA\Property(property="status", type="string", example="200"),
     *  @OA\Property(property="user", type="array",
     * @OA\Items(type="object",
     * format="query",
     *  @OA\Property(property="type", type="string", example="commercial/empty" ),
     *  @OA\Property(property="user_guid", type="string", example="71803f6e-b588-4181-b69d-8bd910ff5166" ),
     *  @OA\Property(property="name", type="string", example="Süleyman Mutlu" ),
     *  @OA\Property(property="slug", type="string", example="suleyman-mutlu" ),
     *  @OA\Property(property="phone", type="string", example="5548271072" ),
     *  @OA\Property(property="email", type="string", example="suleyman@appricot.com.tr" ),
     *  @OA\Property(property="status", type="tinyint", example="1" ),
     *  @OA\Property(property="type_guid", type="string", example="71803f6e-b588-4181-b69d-8bd910ff5166" ),
     *  @OA\Property(property="token", type="string", example="e031117a3268209e8e3ea697bf67ae9c" ),
     *  @OA\Property(property="id", type="int", example="42" ),
     *  @OA\Property(property="created_at", type="date", example="2021-11-18T07:06:36.000000Z" ),
     *  @OA\Property(property="updated_at", type="date", example="2021-11-18T07:06:36.000000Z" ),
     *
     *
     * ))
     *        )
     *     ),
     *  @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="Please fill areas correctly."),
     *
     *        )
     *     ),
     *
     * )
     *
     */

    public function doRegister(Request $r)
    {
        $email_check = User::where('email', $r->email)->first();
        if (!is_null($email_check)) {
            $d['msg'] = "Email is already taken please try another email.";
            $d['status'] = 400;
            return response()->json($d, 400);
        }

        $phone_check = User::where('phone', $r->phone)->first();
        if (!is_null($phone_check)) {
            $d['msg'] = "Phone number is already taken please try another phone number.";
            $d['status'] = 400;
            return response()->json($d, 400);
        }

        if ($r->has('type') && $r->type == 'commercial') {

            DB::beginTransaction();
            try {
                $user_guid = Str::uuid();
                $user = new User();
                $user->user_guid = $user_guid;
                $user->name = $r->fullname;
                $user->slug = Str::slug($r->fullname);
                $user->phone = $r->phone;
                $user->email = $r->email;
                $user->type_guid = '5d840a0f-c539-4257-955d-a375215ea307';
                $user->status = '1';
                $user->password = Hash::make($r->password);
                $user->token = md5(sha1($user_guid . '||' . $user->email . '||' . date('Y-m-d H:i:s')));
                $user->save();
                DB::commit();

                $userinfo = $user;
                $userinfo["usertype"] = "commercial";
                $userinfo["logo"] = null;
                $userinfo["banner"] = null;
                $userinfo["description"] = null;
                $userinfo["birthday"] = "1992-01-10";
                $userinfo["gender"] = "m";

                $d['msg'] = "User registered successfully";
                $d['status'] = 200;
                $d['user'] = $userinfo;
                return response()->json($d, 200);
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollback();
                report($th);
                $d['msg'] = 'There is an error occurred while registering user please try again.';
                $d['status'] = 400;
                return response()->json($d, 400);
            }
        } else {
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
                $user->token = md5(sha1($user_guid . '||' . $user->email . '||' . date('Y-m-d H:i:s')));
                $user->save();

                $id = $user->id;

                Mail::to($user->email)->queue(new UserRegistered($user));

                $userinfo = $user;
                $userinfo["usertype"] = "standard";
                $userinfo["logo"] = null;
                $userinfo["banner"] = null;
                $userinfo["description"] = null;
                $userinfo["birthday"] = date('Y-m-d');
                $userinfo["gender"] = "m";

                DB::commit();
                $d['msg'] = "User registered successfully";
                $d['status'] = 200;
                $d['user'] = $userinfo;
                return response()->json($d, 200);
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollback();
                report($th);
                $d['msg'] = 'There is an error occurred while registering user please try again.';
                $d['status'] = 400;
                return response()->json($d, 400);
            }
        }
    }

    /**
     * @OA\Get(
     *  path="api.motovago.com/getCountries",
     *  operationId="countries",
     *  summary="Gets all countries",
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *     @OA\Property(property="countries", type="array",
     * @OA\Items(type="object",
     * format="query",
     *       @OA\Property(property="id", type="string", example="1"),
     *       @OA\Property(property="country_guid", type="string", example="ebb2a4d9-30ad-11ec-82eb-005056ac9834"),
     *       @OA\Property(property="name", type="string", example="Afghanistan"),
     *       @OA\Property(property="phonecode", type="string", example="93"),
     *     ))   )
     *     ),
     * )
     *
     */

    public function getCountries()
    {
        $d['countries'] = Country::select('id', 'country_guid', 'name', 'phonecode')->get();
        return response()->json($d, 200);
    }


    /**
     * @OA\Post(
     *  path="https://api.motovago.com/google-login",
     *  operationId="googleLogin",
     * description="Login by google",
     *  summary="User Login with Google",
     * @OA\Parameter(
     *    description="Email",
     *    in="path",
     *    name="email",
     *    required=true,
     *    example="example@motovago.com",
     * ),@OA\Parameter(
     *    description="Name",
     *    in="path",
     *    name="name",
     *    required=true,
     *    example="Jhon Doe",
     * ),@OA\Parameter(
     *    description="ID",
     *    in="path",
     *    name="id",
     *    required=true,
     *    example="1847118732817312",
     * ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="name", type="string", example="Jhon Doe"),
     *       @OA\Property(property="phone", type="string", example="5450002211"),
     *       @OA\Property(property="email", type="string", example="example@motovago.com"),
     *       @OA\Property(property="status", type="tinyint", example="1"),
     *       @OA\Property(property="token", type="string", example="6f261b5ee8d2517d9c04670abecca99b"),
     *  @OA\Property(property="type", type="array",
     * @OA\Items(type="object",
     * format="query",
     *       @OA\Property(property="name_en", type="string", example="Standard"),
     * )),
     *       @OA\Property(property="country_guid", type="string", example="128631-12319283-123123192-213123"),
     *  @OA\Property(property="country", type="array",
     * @OA\Items(type="object",
     * format="query",
     *       @OA\Property(property="name", type="string", example="Kuwait"),
     * )),
     *
     *
     *        )
     *     ),
     *  @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="There is an error occured while logging in please try again"),
     *        )
     *     ),
     * )
     *
     */

    public function googleLogin(Request $r)
    {
        $checkUser = User::where("google_id",$r->id)->select('id', 'name', 'phone', 'email', 'type_guid', 'logo', 'background', 'birthday', 'gender', 'description', 'status', 'country_guid', 'token', 'updated_at')->with('type_api', 'country_api')->first();

        if (!is_null($checkUser)) {
            if($checkUser->status == 1) {
                $checkUser->token = md5(sha1($checkUser->user_guid . '||' . $checkUser->email . '||' . date('Y-m-d H:i:s')));
                $checkUser->update();
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
                $d['status'] = 200;
                return response()->json($d, 200);
            } else {
                $d['msg'] = "Your account has been suspended. Please contact support.";
                $d['status'] = 400;
                return response()->json($d, 400);
            }
        } else {
            $checkMail = User::where("email",$r->email)->select('id', 'name', 'phone', 'email', 'type_guid', 'logo', 'background', 'birthday', 'gender', 'description', 'status', 'country_guid', 'token', 'updated_at','google_id')->with('type_api', 'country_api')->first();
            if(!is_null($checkMail)) {
                if($checkMail->status == 1) {
                    $checkMail->google_id = $r->id;
                    $checkMail->token = md5(sha1($checkMail->user_guid . '||' . $checkMail->email . '||' . date('Y-m-d H:i:s')));
                    $checkMail->update();

                    $user = $checkMail;

                    if(!is_null($checkMail->logo)) {
                        $user["logo"] = config('api.main_url') . '/storage/user/'.$checkMail->logo;
                    }
                    if(!is_null($checkMail->background)) {
                        $user["banner"] = config('api.main_url') . '/storage/user/'.$checkMail->background;
                    } else {
                        $user["banner"] = null;
                    }

                    $user["usertype"] = $checkMail->type_api->name_en !== "Commercial" ? "standard" : "commercial";

                    $d['user'] = $user;
                    $d['status'] = 200;
                    return response()->json($d, 200);
                } else {
                    $d['msg'] = "Your account has been suspended. Please contact support.";
                    $d['status'] = 400;
                    return response()->json($d, 400);
                }
            } else {
                $user = new User();
                $user->user_guid = Str::uuid();
                $user->type_guid = 'c8423db6-300e-42fa-a103-c5b62e388f98';
                $user->status = '1';
                $user->name = $r->name;
                $user->slug = Str::slug($r->name);
                $user->email = $r->email;
                $user->google_id = $r->id;
                $user->token = md5(sha1($user->user_guid . '||' . $user->email . '||' . date('Y-m-d H:i:s')));
                $user->save();

                $userinfo = $user;
                $userinfo["image"] = null;
                $userinfo["banner"] = null;
                $userinfo["usertype"] = "standard";

                $d['user'] = $userinfo;
                $d['status'] = 200;
                return response()->json($d, 200);
            }
        }
    }

    public function appleLogin(Request $r)
    {
        $checkUser = User::where("apple_id",$r->id)->select('id', 'name', 'phone', 'email', 'logo', 'background', 'description', 'birthday', 'gender', 'type_guid', 'status', 'country_guid', 'token', 'updated_at')->with('type_api', 'country_api')->first();

        if (!is_null($checkUser)) {
            if($checkUser->status == 1) {
                $checkUser->token = md5(sha1($checkUser->user_guid . '||' . $checkUser->email . '||' . date('Y-m-d H:i:s')));
                $checkUser->update();

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
                $d['status'] = 200;
                return response()->json($d, 200);
            } else {
                $d['msg'] = "Your account has been suspended. Please contact support.";
                $d['status'] = 400;
                return response()->json($d, 400);
            }
        } else {
            if(!is_null($r->email)) {
                $checkMail = User::where("email",$r->email)->select('id', 'name', 'phone', 'email', 'type_guid', 'logo', 'background', 'birthday', 'gender', 'description', 'status', 'country_guid', 'token', 'updated_at','google_id')->with('type_api', 'country_api')->first();
            } else {
                $checkMail = null;
            }

            if(!is_null($checkMail)) {
                if($checkMail->status == 1) {
                    $checkMail->apple_id = $r->id;
                    $checkMail->token = md5(sha1($checkMail->user_guid . '||' . $checkMail->email . '||' . date('Y-m-d H:i:s')));
                    $checkMail->update();

                    $user = $checkMail;

                    if(!is_null($checkMail->logo)) {
                        $user["logo"] = config('api.main_url') . '/storage/user/'.$checkMail->logo;
                    }
                    if(!is_null($checkMail->background)) {
                        $user["banner"] = config('api.main_url') . '/storage/user/'.$checkMail->background;
                    } else {
                        $user["banner"] = null;
                    }

                    $user["usertype"] = $checkMail->type_api->name_en !== "Commercial" ? "standard" : "commercial";

                    $d['user'] = $user;
                    $d['status'] = 200;
                    return response()->json($d, 200);
                } else {
                    $d['msg'] = "Your account has been suspended. Please contact support.";
                    $d['status'] = 400;
                    return response()->json($d, 400);
                }
            } else {
                $user = new User();
                $user->user_guid = Str::uuid();
                $user->type_guid = 'c8423db6-300e-42fa-a103-c5b62e388f98';
                $user->status = '1';
                $user->name = $r->name;
                $user->slug = Str::slug($r->name);
                $user->email = $r->email;
                $user->apple_id = $r->id;
                $user->token = md5(sha1($user->user_guid . '||' . $user->email . '||' . date('Y-m-d H:i:s')));
                $user->save();

                $userinfo = $user;
                $userinfo["image"] = null;
                $userinfo["banner"] = null;
                $userinfo["usertype"] = "standard";

                $d['user'] = $userinfo;
                $d['status'] = 200;
                return response()->json($d, 200);
            }
        }
    }

    public function instaLogin(Request $r)
    {
        $token = $r->access_token;

        $userInfo = $this->instaUserInfo($token);
        if(is_null($userInfo)) {
            $d['msg'] = "Instagram login error. Please try again later.";
            $d['status'] = 400;
            return response()->json($d, 400);
        } else {
            $d['msg'] = "Infrastructure is being prepared...";
            $d['status'] = 400;
            return response()->json($d, 400);
        }
    }

    public function instaUserInfo($token)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.instagram.com/v1/users/self?access_token='.$token,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Cookie: csrftoken=uyjor8SuEx1fu1wkLFJ4DD9jRNntnOlI; mid=YH_YqwAEAAGyN00MtD0P5ESznJiL; ig_did=27A2DF3D-E1B2-4832-88C0-52379660A882; ig_nrcb=1'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $json = json_decode($response);

        if($json->meta->code == 400) {

        }
    }
}
