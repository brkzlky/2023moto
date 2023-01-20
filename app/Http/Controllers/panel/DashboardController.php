<?php

namespace App\Http\Controllers\panel;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Listing;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{

    public function dashboard()
    {
        // Location
        $d['locations_count'] = Location::count();

        // Categories
        $d['categories_count'] = Category::count();

        // Users
        $d['users_count'] = User::count();

        // Listings
        $d['listings_count'] = Listing::count();

        $d['page_title'] = 'Dashboard';
        return view('panel.pages.dashboard', $d);
    }
}
