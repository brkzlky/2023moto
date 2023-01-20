<?php

namespace App\Providers;

use App\Models\Advertisement;
use Request;
use Session;
use App\Models\User;
use App\Models\Listing;
use App\Models\Category;
use App\Models\Location;
use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\Observers\UserObserver;
use App\Models\LocationCategory;
use App\Observers\ListingObserver;
use App\Observers\CategoryObserver;
use App\Observers\LocationObserver;
use App\Observers\AttributeObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\AttributeGroupObserver;
use Illuminate\Support\Facades\Redirect;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Listing::observe(ListingObserver::class);
        Attribute::observe(AttributeObserver::class);
        AttributeGroup::observe(AttributeGroupObserver::class);
        Category::observe(CategoryObserver::class);
        Location::observe(LocationObserver::class);
        User::observe(UserObserver::class);

        $locations = Location::where("status","1")->orderBy('queue')->get();
        view()->share('locations', $locations);

        view()->composer('*', function ($view) {
            $url = Request::path();
            $adv = Advertisement::where("page_url", $url)->first();
            view()->share('page_advertise', $adv);

            $current_location = Session::get('current_location');
            view()->share('current_location', $current_location);

            $current_location_guid = Session::get('current_location_guid');
            view()->share('current_location_guid', $current_location_guid);

            $location = Location::where("location_guid", $current_location_guid)->first();
            view()->share('current_location_name', !is_null($location) ? $location->name_en : '');

            $cats = LocationCategory::select("category_guid")->where("location_guid",$current_location_guid)->whereHas("category", function($q) {
                $q->whereNull("related_to")->where("status","1");
            })->get();

            $catguids = [];
            foreach($cats as $c) {
                $catguids[] = $c->category_guid;
            }

            $categories = Category::whereIn("category_guid",$catguids)->whereNull("related_to")->where("status","1")->orderBy('queue')->withCount('listings')->get();
            view()->share('categories', $categories);
        });
    }
}
