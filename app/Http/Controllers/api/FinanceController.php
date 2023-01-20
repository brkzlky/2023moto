<?php

namespace App\Http\Controllers\api;

use Str;
use Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\BankRate;
use App\Models\Listing;
use App\Models\LoanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{

    /**
     * @OA\Post(
     *  path="api.motovago.com/loan_calculate",
     *  operationId="listing_guid",
     *  summary="Gets detail of listing which you posted listing_guid. ",
     *  @OA\Parameter(
     *    description="loan",
     *    in="query",
     *    name="loan",
     *    required=true,
     *    example="120000",
     * ),
     * @OA\Parameter(
     *    description="maturity",
     *    in="query",
     *    name="maturity",
     *    required=true,
     *    example="m-6",
     * ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="loan_result", type="array",
     * @OA\Items(type="object",
     * format="query",
     *  @OA\Property(property="main_loan", type="string", example="120.000" ),
     *        @OA\Property(property="bank", type="string", example="Motovago"),
     *       @OA\Property(property="monthly_loan", type="string", example="10.75"),
     *       @OA\Property(property="period", type="string", example="12 Months"),
     *       @OA\Property(property="rate", type="string", example="13.650"),
     *       @OA\Property(property="total_loan", type="string", example="129.0564"),
     * ) )
     *        )
     *     ),
     * )
     */
    public function loanCalculate(Request $r)
    {
        $loan = $r->loan;
        $periodraw = explode("-", $r->maturity);
        $period = $periodraw[1];
        $rate = BankRate::where("status", "1")->where("period_type", $periodraw[0])->where("period", $periodraw[1])->with("bank")->first();
        $monthlyrate = ($rate->rate / $rate->period) / 100;
        $monthlyrateOver = pow((1 + $monthlyrate), $period);

        $monthly_loan = $loan * (($monthlyrate * $monthlyrateOver) / ($monthlyrateOver - 1));
        $recomendeds = Listing::whereBetween("price", [$loan - 2000, $loan + 2000])->has("main_image")->with("main_image", "currency", "location")->inRandomOrder()->limit(10)->get();

        $d['result'] = 200;
        $d['loanresult']['main_loan'] = $loan;
        $d['loanresult']['bank'] = $rate->bank->name;
        $d['loanresult']['monthly_loan'] = round($monthly_loan, 4);
        $d['loanresult']['period'] = $period . ' Months';
        $d['loanresult']['rate'] = $rate->rate;
        $d['loanresult']['total_loan'] = round($monthly_loan, 4) * $period;
        $d['recomendeds'] = $recomendeds;

        return response()->json($d, 200);
    }
    /**
     * @OA\Get(
     *  path="api.motovago.com/getFinanceRates",
     *  operationId="getFinanceRates",
     *  summary="Gets finance rates. ",
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *  @OA\Property(property="status", type="string", example="200" ),
     *       @OA\Property(property="rates", type="array",
     * @OA\Items(type="object",
     * format="query",
     *  @OA\Property(property="rate", type="string", example="12.000" ),
     *        @OA\Property(property="perid", type="string", example="6"),
     *       @OA\Property(property="period_type", type="string", example="m"),
     *       @OA\Property(property="status", type="string", example="1"),
     * ) )
     *        )
     *     ),
     * )
     */

    public function getFinanceRates()
    {
        $rates = BankRate::where("status", "1")->get();
        $d['result'] = 200;
        $d['rates'] = $rates;
        return response()->json($d, 200);
    }
    /**
     * @OA\Post(
     *  path="api.motovago.com/loanRequest",
     *  operationId="loan request",
     *  summary="Save request data to database. ",
     *  @OA\Parameter(
     *    description="fullname",
     *    in="query",
     *    name="fullname",
     *    required=true,
     *    example="Suleyman Mutlu",
     * ),
     *   @OA\Parameter(
     *    description="identity",
     *    in="query",
     *    name="identity",
     *    required=true,
     *    example="18238172381",
     * ),
     *      @OA\Parameter(
     *    description="email",
     *    in="query",
     *    name="email",
     *    required=true,
     *    example="suleyman@appricot.com.tr",
     * ),
     *  @OA\Parameter(
     *    description="phone",
     *    in="query",
     *    name="phone",
     *    required=true,
     *    example="5542231223",
     * ),
     *  @OA\Parameter(
     *    description="loan",
     *    in="query",
     *    name="loan",
     *    required=true,
     *    example="2400.00",
     * ),
     *  @OA\Parameter(
     *    description="bank",
     *    in="query",
     *    name="bank",
     *    required=true,
     *    example="Motovago",
     * ),
     *  @OA\Parameter(
     *    description="rate",
     *    in="query",
     *    name="rate",
     *    required=true,
     *    example="13.65",
     * ),
     *  @OA\Parameter(
     *    description="period",
     *    in="query",
     *    name="period",
     *    required=true,
     *    example="12 Months",
     * ),
     *  @OA\Parameter(
     *    description="monthly_loan",
     *    in="query",
     *    name="monthly_loan",
     *    required=true,
     *    example="215.09",
     * ),
     *  @OA\Parameter(
     *    description="total_loan",
     *    in="query",
     *    name="total_loan",
     *    required=true,
     *    example="2581.13",
     * ),
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *    @OA\Property(property="status", type="string", example="200" ),
     *    @OA\Property(property="msg", type="string", example="Loan requested successfully."),
     *    )
     * ),
     *)
     */
    public function loanRequest(Request $r)
    {
        $validated = Validator::make($r->all(), [
            'fullname' => 'required|min:3',
            'identity' => 'required|min:10',
            'email' => 'required|email',
            'phone' => 'required|min:5',
        ]);

        if ($validated->fails()) {
            $d['status'] = 400;
            $d['msg'] = "Please fill all areas correctly!";
            return response()->json($d, 400);
        } else {
            try {
                DB::beginTransaction();
                $loan = new LoanRequest();
                $loan->loan_request_guid = Str::uuid();
                $loan->fullname = $r->fullname;
                $loan->identity = $r->identity;
                $loan->email = $r->email;
                $loan->phone = $r->phone;
                $loan->loan = $r->loan;
                $loan->bank = $r->bank;
                $loan->rate = $r->rate;
                $loan->period = $r->period;
                $loan->monthly_loan = $r->monthly_loan;
                $loan->total_loan = $r->total_loan;
                $loan->save();
                DB::commit();
                $d['status'] = 200;
                $d['msg'] = "Loan requested successfully.";
                return response()->json($d, 200);
            } catch (\Throwable $th) {
                DB::rollback();
                $d['status'] = 400;
                $d['msg'] = "There is a problem occurred while requesting loan please try again.".json_encode($th);
                return response()->json($d, 400);
            }   
        }
    }
}
