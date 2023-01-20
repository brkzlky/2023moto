<?php

namespace App\Observers;

use App\Models\AttributeGroup;
use App\Models\Category;
use App\Models\CategoryAttributeGroup;
use App\Models\LocationCategory;
use Illuminate\Support\Facades\DB;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function created(Category $category)
    {
        //
    }

    /**
     * Handle the Category "updated" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function updated(Category $category)
    {
        //
    }

    /**
     * Handle the Category "deleted" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function deleted(Category $category)
    {
        $location_categories = LocationCategory::where('category_guid', $category->category_guid)->get();
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
        $category_attribute_groups = AttributeGroup::where('category_guid', $category->category_guid)->get();
        if ($category_attribute_groups) {
            foreach ($category_attribute_groups as $category_attribute_group) {
                try {
                    DB::beginTransaction();
                    $category_attribute_group->delete();
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollback();
                }
            }
        }
    }

    /**
     * Handle the Category "restored" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function restored(Category $category)
    {
        //
    }

    /**
     * Handle the Category "force deleted" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function forceDeleted(Category $category)
    {
        //
    }
}
