<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;


    public function children()
    {
        return $this->hasMany(Category::class, 'related_to', 'category_guid')->with('children')->orderBy('id', 'ASC');
    }

    public function children_api()
    {
        return $this->hasMany(Category::class, 'related_to', 'category_guid')->with('children')->orderBy('id', 'ASC')->select("category_guid","name_en","name_ar","related_to");
    }

    public function listings()
    {
        return $this->hasMany(Listing::class, 'category_guid', 'category_guid');
    }

    public function category_ag_guid()
    {
        return $this->hasMany(CategoryAttributeGroup::class, 'category_guid', 'category_guid');
    }

    public function attr_groups_info()
    {
        return $this->hasMany(AttributeGroup::class, 'category_guid', 'category_guid');
    }

    public function location_info()
    {
        return $this->hasManyThrough(
            Location::class,
            LocationCategory::class,
            'category_guid',
            'location_guid',
            'category_guid',
            'location_guid'
        );
    }
}
