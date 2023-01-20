<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expertise extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function brand()
    {
        return $this->hasOne(Brand::class,'brand_guid','brand_guid');
    }

    public function model()
    {
        return $this->hasOne(BrandModel::class,'model_guid','model_guid');
    }

    public function trim()
    {
        return $this->hasOne(ModelTrim::class,'trim_guid','trim_guid');
    }
}
