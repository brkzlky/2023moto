<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandModel;
use App\Models\Expertise;
use App\Models\ModelTrim;
use Illuminate\Http\Request;
use Session;
use Str;

class ExpertiseController extends Controller
{
    /**
     * @OA\Post(
     *  path="api.motovago.com/expertiseBrands",
     *  operationId="key",
     *  summary="Gets brands  ",
     * @OA\Parameter(
     *    description="Language",
     *    in="query",
     *    name="lang",
     *    required=true,
     *    example="ar/en",
     * ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="brand_guid", type="string", example="ee6ccc96-3a38-11eb-ac2e-f2d9687e41c8"),
     *       @OA\Property(property="name", type="string", example="Renault Samsung"),
     *        )
     *     ),
     * )
     */
    public function expertiseBrands(Request $r)
    {

        $lang = $r->lang;
        if ($lang == 'ar') {
            $allbrands = Brand::select("brand_guid", "name_ar as name")->where("status", "1")->get();
        } else {
            $allbrands = Brand::select("brand_guid", "name_en as name")->where("status", "1")->get();
        }

        if (count($allbrands) == 0) {
            $allbrands = [];
        }

        return response()->json($allbrands, 200);
    }

    /**
     * @OA\Post(
     *  path="api.motovago.com/expertiseModels",
     *  operationId="key1",
     *  summary="Gets models related to posted key. ",
     * @OA\Parameter(
     *    description="Brand",
     *    in="query",
     *    name="brand",
     *    required=true,
     *    example="ee6ccc96-3a38-11eb-ac2e-f2d9687e41c8",
     * ),
     *     @OA\Parameter(
     *    description="Language",
     *    in="query",
     *    name="lang",
     *    required=true,
     *    example="ar/en",
     * ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="model_guid", type="string", example="ee6ccc96-3a38-11eb-ac2e-f2d9687e41c8"),
     *       @OA\Property(property="name", type="string", example="Clio V6"),
     *        )
     *     ),
     * )
     */
    public function expertiseModels(Request $r)
    {
        $lang = $r->lang;
        $brand = $r->brand;
        if ($lang == 'ar') {
            $allmodels = BrandModel::select("model_guid", "name_ar as name")->where("brand_guid", $brand)->where("status", "1")->get();
        } else {
            $allmodels = BrandModel::select("model_guid", "name_en as name")->where("brand_guid", $brand)->where("status", "1")->get();
        }

        if (count($allmodels) == 0) {
            $allmodels = [];
        }

        return response()->json($allmodels, 200);
    }

    /**
     * @OA\Post(
     *  path="api.motovago.com/expertiseTrims",
     *  operationId="key2",
     *  summary="Gets trims related to posted key. ",
     *     @OA\Parameter(
     *    description="Language",
     *    in="query",
     *    name="lang",
     *    required=true,
     *    example="ar/en",
     * ),
     *           @OA\Parameter(
     *    description="Model",
     *    in="query",
     *    name="model",
     *    required=true,
     *    example="ee6ccc96-3a38-11eb-ac2e-f2d9687e41c8",
     * ),
     *     @OA\Parameter(
     *    description="Year",
     *    in="query",
     *    name="year",
     *    required=true,
     *    example="2018",
     * ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="model_guid", type="string", example="ee6ccc96-3a38-11eb-ac2e-f2d9687e41c8"),
     *       @OA\Property(property="name", type="string", example="GT 1.2 AT (120 Hp) (2013)"),
     *        )
     *     ),
     * )
     */
    public function expertiseTrims(Request $r)
    {
        $year = $r->year;
        $model = $r->model;
        $lang = $r->lang;
        if ($lang == 'ar') {
            $trims = ModelTrim::select("trim_guid", "name_ar as name", "year")->where('year', $year)->where("model_guid", $model)->where("status", "1")->get();
        } else {
            $trims = ModelTrim::select("trim_guid", "name_en as name", "year")->where('year', $year)->where("model_guid", $model)->where("status", "1")->get();
        }
        if (count($trims) == 0) {
            $alltrims = [];
        } else {
            foreach ($trims as $t) {
                $alltrims[] = array(
                    "name" => $t->name ,
                    "trim_guid" => $t->trim_guid
                );
            }
        }

        return response()->json($alltrims, 200);
    }

    /**
     * @OA\Post(
     *  path="api.motovago.com/expertiseForm",
     *  operationId="expertiseForm",
     *  summary="Posts entered data to save database. ",
     *   
     *   @OA\Parameter(
     *    description="fullname",
     *    in="query",
     *    name="fullname",
     *    required=true,
     *    example="Suleyman Mutlu",
     * ),
     *      @OA\Parameter(
     *    description="Email",
     *    in="query",
     *    name="email",
     *    required=true,
     *    example="suleyman@appricot.com.tr",
     * ),
     *      @OA\Parameter(
     *    description="phone",
     *    in="query",
     *    name="phone",
     *    required=true,
     *    example="5548271717",
     * ),
     * @OA\Parameter(
     *    description="brand_guid",
     *    in="query",
     *    name="brand_guid",
     *    required=true,
     *    example="ee6ccc96-3a38-11eb-ac2e-f2d9687e41c8",
     * ),
     *  @OA\Parameter(
     *    description="model_guid",
     *    in="query",
     *    name="model_guid",
     *    required=true,
     *    example="ee6ccc96-3a38-11eb-ac2e-f2d9687e41c8",
     * ),
     *  @OA\Parameter(
     *    description="trim_guid",
     *    in="query",
     *    name="trim_guid",
     *    required=true,
     *    example="ee6ccc96-3a38-11eb-ac2e-f2d9687e41c8",
     * ),
     * @OA\Parameter(
     *    description="model_year",
     *    in="query",
     *    name="model_year",
     *    required=true,
     *    example="2020",
     * ),
     * @OA\Parameter(
     *    description="mileage",
     *    in="query",
     *    name="mileage",
     *    required=true,
     *    example="200.000",
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="200"),
     *       @OA\Property(property="expertise_request", type="boolean", example="true"),
     *       @OA\Property(property="title", type="string", example="ee6ccc96-3a38-11eb-ac2e-f2d9687e41c8"),
     *       @OA\Property(property="text", type="string", example="Your expertise request has been successfully received. You will be returned as soon as possible."),
     *     )
     *     ),
     * )
     */
    public function expertiseForm(Request $r)
    {
        $exp = new Expertise();
        $exp->expertise_guid = Str::uuid();
        $exp->fullname = $r->fullname;
        $exp->email=$r->email;
        $exp->phone=$r->phone;
        $exp->brand_guid = $r->brand_guid;
        $exp->model_guid = $r->model_guid;
        $exp->trim_guid = $r->trim_guid;
        $exp->model_year = $r->model_year;
        $exp->mileage = $r->mileage;
        $exp->save();

        $success['expertise_request'] = true;
        $success['title'] = "Success";
        $success['msg'] = "Your expertise request has been successfully received. You will be returned as soon as possible.";
        $success['status'] = 200;
        return response()->json($success, 200);
    }
}
