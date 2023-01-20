<?php

namespace App\Observers;

use App\Models\Attribute;
use App\Models\AttributeMapping;
use Illuminate\Support\Facades\DB;

class AttributeObserver
{
    /**
     * Handle the Attribute "created" event.
     *
     * @param  \App\Models\Attribute  $attribute
     * @return void
     */
    public function created(Attribute $attribute)
    {
        //
    }

    /**
     * Handle the Attribute "updated" event.
     *
     * @param  \App\Models\Attribute  $attribute
     * @return void
     */
    public function updated(Attribute $attribute)
    {
        //
    }

    /**
     * Handle the Attribute "deleted" event.
     *
     * @param  \App\Models\Attribute  $attribute
     * @return void
     */
    public function deleted(Attribute $attribute)
    {
        $attribute_mappings = AttributeMapping::where('attribute_guid', $attribute->attribute_guid)->get();
        if ($attribute_mappings) {
            foreach ($attribute_mappings as $attribute_mapping) {
                try {
                    DB::beginTransaction();
                    $attribute_mapping->delete();
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollback();
                }
            }
        }
    }

    /**
     * Handle the Attribute "restored" event.
     *
     * @param  \App\Models\Attribute  $attribute
     * @return void
     */
    public function restored(Attribute $attribute)
    {
        //
    }

    /**
     * Handle the Attribute "force deleted" event.
     *
     * @param  \App\Models\Attribute  $attribute
     * @return void
     */
    public function forceDeleted(Attribute $attribute)
    {
        //
    }
}
