<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function attributes_info()
    {
        return $this->hasManyThrough(
            AttributeGroup::class,
            AttributeMapping::class,
            'attribute_guid',
            'ag_guid',
            'attribute_guid',
            'ag_guid'
        );
    }
}
