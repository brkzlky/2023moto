<?php

namespace App\Observers;

use App\Models\AttributeGroup;
use App\Models\AttributeMapping;
use App\Models\CategoryAttributeGroup;
use Illuminate\Support\Facades\DB;

class AttributeGroupObserver
{
    /**
     * Handle the AttributeGroup "created" event.
     *
     * @param  \App\Models\AttributeGroup  $attributeGroup
     * @return void
     */
    public function created(AttributeGroup $attributeGroup)
    {
        //
    }

    /**
     * Handle the AttributeGroup "updated" event.
     *
     * @param  \App\Models\AttributeGroup  $attributeGroup
     * @return void
     */
    public function updated(AttributeGroup $attributeGroup)
    {
        //
    }

    /**
     * Handle the AttributeGroup "deleted" event.
     *
     * @param  \App\Models\AttributeGroup  $attributeGroup
     * @return void
     */
    public function deleted(AttributeGroup $attributeGroup)
    {
        $attribute_mappings = AttributeMapping::where('ag_guid', $attributeGroup->ag_guid)->get();
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
     * Handle the AttributeGroup "restored" event.
     *
     * @param  \App\Models\AttributeGroup  $attributeGroup
     * @return void
     */
    public function restored(AttributeGroup $attributeGroup)
    {
        //
    }

    /**
     * Handle the AttributeGroup "force deleted" event.
     *
     * @param  \App\Models\AttributeGroup  $attributeGroup
     * @return void
     */
    public function forceDeleted(AttributeGroup $attributeGroup)
    {
        //
    }
}
