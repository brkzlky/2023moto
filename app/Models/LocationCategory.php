<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocationCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function category()
    {
        return $this->hasOne(Category::class, 'category_guid', 'category_guid');
    }
    public function active_category()
    {
        return $this->hasOne(Category::class, 'category_guid', 'category_guid')->where('status', 1)->orderBy('queue','ASC');
    }

    public function listings()
    {
        return $this->hasMany(Listing::class, 'category_guid', 'category_guid')->where('status', 1);
    }
}
