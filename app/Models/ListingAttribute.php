<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListingAttribute extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function attribute_group()
    {
        return $this->hasOne(AttributeGroup::class, 'ag_guid', 'ag_guid');
    }

    public function attribute_info()
    {
        return $this->hasOne(Attribute::class, 'attribute_guid', 'attribute_guid');
    }
}
