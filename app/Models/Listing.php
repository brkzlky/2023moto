<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Listing extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "listings";

    public function category()
    {
        return $this->hasOne(Category::class, 'category_guid', 'category_guid');
    }

    public function subcategory()
    {
        return $this->hasOne(Category::class, 'category_guid', 'subcategory_guid');
    }

    public function location()
    {
        return $this->hasOne(Location::class, 'location_guid', 'location_guid');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'user_guid', 'user_guid');
    }

    public function currency()
    {
        return $this->hasOne(Currency::class, 'currency_guid', 'currency_guid');
    }

    public function images()
    {
        return $this->hasMany(ListingImage::class, 'listing_guid', 'listing_guid');
    }

    public function main_image()
    {
        return $this->hasOne(ListingImage::class, 'listing_guid', 'listing_guid')->where("is_main", "1");
    }
    public function main_image_api()
    {
        return $this->hasOne(ListingImage::class, 'listing_guid', 'listing_guid')->select('listing_guid', 'name')->where("is_main", "1");
    }

    public function favorite()
    {
        return $this->hasMany(Favorite::class, 'listing_guid', 'listing_guid');
    }

    public function messages()
    {
        return $this->hasMany(UserChat::class, 'listing_guid', 'listing_guid');
    }

    public function attributes()
    {
        return $this->hasMany(ListingAttribute::class, 'listing_guid', 'listing_guid');
    }

    public function brand()
    {
        return $this->hasOne(Brand::class, 'brand_guid', 'brand_guid');
    }

    public function model()
    {
        return $this->hasOne(BrandModel::class, 'model_guid', 'model_guid');
    }

    public function trim()
    {
        return $this->hasOne(ModelTrim::class, 'trim_guid', 'trim_guid');
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'country_guid', 'country_guid');
    }

    public function state()
    {
        return $this->hasOne(State::class, 'state_guid', 'state_guid');
    }

    public function city()
    {
        return $this->hasOne(City::class, 'city_guid', 'city_guid');
    }

    public function color()
    {
        return $this->hasOne(Color::class, 'color_guid', 'color_guid');
    }
}
