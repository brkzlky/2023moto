<?php

namespace App\Observers;

use App\Models\Location;
use App\Models\LocationCategory;
use App\Models\LocationUserType;
use Illuminate\Support\Facades\DB;

class LocationObserver
{
    /**
     * Handle the Location "created" event.
     *
     * @param  \App\Models\Location  $location
     * @return void
     */
    public function created(Location $location)
    {
        //
    }

    /**
     * Handle the Location "updated" event.
     *
     * @param  \App\Models\Location  $location
     * @return void
     */
    public function updated(Location $location)
    {
        //
    }

    /**
     * Handle the Location "deleted" event.
     *
     * @param  \App\Models\Location  $location
     * @return void
     */
    public function deleted(Location $location)
    {
        $location_categories = LocationCategory::where('location_guid', $location->location_guid)->get();
        if ($location_categories) {
            foreach ($location_categories as $location_category) {
                try {
                    DB::beginTransaction();
                    $location_category->delete();
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollback();
                }
            }
        }
        // $location_user_types = LocationUserType::where('location_guid', $location->location_guid)->get();
        // if ($location_user_types) {
        //     foreach ($location_user_types as $location_user_type) {
        //         try {
        //             DB::beginTransaction();
        //             $location_user_type->delete();
        //             DB::commit();
        //         } catch (\Throwable $th) {
        //             DB::rollback();
        //         }
        //     }
        // }
    }

    /**
     * Handle the Location "restored" event.
     *
     * @param  \App\Models\Location  $location
     * @return void
     */
    public function restored(Location $location)
    {
        //
    }

    /**
     * Handle the Location "force deleted" event.
     *
     * @param  \App\Models\Location  $location
     * @return void
     */
    public function forceDeleted(Location $location)
    {
        //
    }
}
