<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function listings()
    {
        return $this->hasMany(Listing::class, 'location_guid', 'location_guid');
    }

    public function location_user_types()
    {
        return $this->hasManyThrough(
            UserType::class,
            LocationUserType::class,
            'location_guid',
            'type_guid',
            'location_guid',
            'type_guid'
        );
    }

    public function location_categories()
    {
        return $this->hasManyThrough(
            Category::class,
            LocationCategory::class,
            'location_guid',
            'category_guid',
            'location_guid',
            'category_guid'
        );
    }

    public function currency()
    {
        return $this->hasOne(Currency::class, 'currency_guid', 'currency_guid');
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'country_guid', 'country_guid');
    }
}
