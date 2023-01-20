<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * @OA\Get(
     *  path="api.motovago.com/getLocations",
     *  operationId="locations",
     *  summary="Gets all locations in locations table where status is '1'",
     *   @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="image", type="string", example="https://api.motovago.test/storage/images/locations/pc_photo/qatar.jpeg"),
     *       @OA\Property(property="location_guid", type="string", example="03d6cbbb-a43f-4143-8f9c-288485de12e4"),
     *       @OA\Property(property="name_en", type="string", example="QATAR"),
     *       @OA\Property(property="name_ar", type="string", example="دولة قطر"),
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
    public function getLocation()
    {
        $all_locations = [];
        try {
            $locations = Location::where('status', '1')->select('location_guid', 'name_en', 'name_ar', 'pc_photo')->orderBy('created_at', 'desc')->get();
            foreach ($locations as  $k => $loc) {
                $all_locations[$k]['image'] = config('api.main_url').'/storage/images/locations/pc_photo/' . $loc->pc_photo;
                $all_locations[$k]['location_guid'] = $loc->location_guid;
                $all_locations[$k]['name_en'] = $loc->name_en;
                $all_locations[$k]['name_ar'] = $loc->name_ar;
            }
            return response()->json($all_locations, 200);
        } catch (\Throwable $th) {
            $d['status'] = 200;
            $d['msg'] = "Error occured while getting locations list.";
            return response()->json($d, 400);
        }
    }
}
