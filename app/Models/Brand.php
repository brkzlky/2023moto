<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "brands";
    public function brand_models()
    {
        return $this->hasMany(BrandModel::class, 'brand_guid', 'brand_guid');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'category_guid', 'category_guid');
    }
}
