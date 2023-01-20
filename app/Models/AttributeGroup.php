<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeGroup extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function category_info()
    {
        return $this->hasOne(Category::class, 'category_guid', 'category_guid',);
    }

    public function attribute_info()
    {
        return $this->hasManyThrough(
            Attribute::class,
            AttributeMapping::class,
            'ag_guid',
            'attribute_guid',
            'ag_guid',
            'attribute_guid'
        );
    }
}
