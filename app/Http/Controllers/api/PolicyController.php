<?php

namespace App\Http\Controllers\api;

use App\Models\Policy;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PolicyController extends Controller
{
    /**
     * @OA\POST(
     *  path="api.motovago.com/getPolicy",
     *  operationId="policy",
     *  summary="Gets policy detail from policies table",
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="text", type="string", example="<h2><span>Personal Data Protection and Data Privacy Policy<br></span></h2>...."),
     *        )
     *     ),
     *    @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="Error occured while getting policy info."),
     *        )
     *     )
     * )
     *
     */
    public function getPolicy(Request $r)
    {
        $all_locations = [];
        if(!$r->has('type')) {
            $d['status'] = 200;
            $d['msg'] = "Error occured while getting policy.";
            return response()->json($d, 400);
        }

        $policySlug = 'privacy-policy';

        if($r->type == 'dataProtectionPolicy') {
            $policySlug = 'information-on-protection-of-personal-data';
        }

        try {
            $policy = Policy::where("slug", $policySlug)->first()->text;

            return response()->json($policy, 200);
        } catch (\Throwable $th) {
            $d['status'] = 200;
            $d['msg'] = "Error occured while getting policy info.";
            $d['err'] = json_encode($th);
            return response()->json($d, 400);
        }
    }

    /**
     * @OA\POST(
     *  path="api.motovago.com/getEula",
     *  operationId="eula",
     *  summary="Gets eula text from settings table",
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="text", type="string", example="<h2><span>Personal Data Protection and Data Privacy Policy<br></span></h2>...."),
     *        )
     *     ),
     *    @OA\Response(
     *    response=400,
     *    description="Error",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="400"),
     *       @OA\Property(property="msg", type="string", example="Error occured while getting eula info."),
     *        )
     *     )
     * )
     *
     */
    public function getEula(Request $r)
    {
        try {
            $eula = Setting::where("slug", "eula")->first()->setting_value;

            return response()->json($eula, 200);
        } catch (\Throwable $th) {
            $d['status'] = 200;
            $d['msg'] = "Error occured while getting eula info.";
            $d['err'] = json_encode($th);
            return response()->json($d, 400);
        }
    }
}
