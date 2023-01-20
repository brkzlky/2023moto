<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\site\HomeController;
use App\Http\Controllers\site\ListingController;
use App\Http\Controllers\site\FinanceController;
use App\Http\Controllers\site\ExpertiseController;

Route::get('/selection', [HomeController::class,'selection'])->name('site.selection');
Route::get('/expertise', [ExpertiseController::class,'expertise'])->middleware('location')->name('site.expertise');
Route::get('/finance', [FinanceController::class,'finance'])->middleware('location')->name('site.finance');
Route::get('/privacy-policy', [HomeController::class,'privacy_policy'])->name('site.privacy_policy');
Route::get('/terms-of-use', [HomeController::class,'terms_of_use'])->name('site.terms_of_use');
Route::get('/information-on-protection-of-personal-data', [HomeController::class,'information_on_protection_of_personal_data'])->name('site.information_on_protection_of_personal_data');

Route::post('/finance', [FinanceController::class,'finance_offer_form'])->name('site.finance_offer_form');
Route::post('/expertise', [ExpertiseController::class,'expertise_form'])->name('site.expertise_form');

Route::domain('{location}.motovago.com')->group(function ($location) {
    Route::get('/', [HomeController::class, 'home'])->name('location.home');
    Route::get('/contact', [HomeController::class,'contact'])->middleware('location')->name('site.contact');
    Route::get('/about-us', [HomeController::class,'aboutus'])->middleware('location')->name('site.aboutus');
    Route::any('/listings/{category}', [ListingController::class,'listing_category'])->middleware('location')->name('site.listing_category');
    Route::get('/listings/{category}/{listing_no}', [ListingController::class,'listing_detail'])->middleware('location')->name('site.listing_detail');
    Route::get('/seller/{slug}', [HomeController::class,'seller_detail'])->middleware('location')->name('site.seller_detail');
});


//Route::get('/', [HomeController::class, 'comingsoon'])->name('site.home');
Route::get('/', function () {
    return redirect()->route('site.selection');
})->name('site.home');
Route::get('/change-language/{language}', [HomeController::class,'change_language'])->name('site.change_language');

//Api's
Route::post('/finance-rates', [FinanceController::class,'finance_rates']);
Route::post('/mtvg-calculate-loan', [FinanceController::class,'calculate_loan']);
Route::post('/expertise-brands', [ExpertiseController::class, 'expertise_brands']);
Route::post('/expertise-models', [ExpertiseController::class, 'expertise_models']);
Route::post('/expertise-trims', [ExpertiseController::class, 'expertise_trims']);
Route::post('/search', [HomeController::class,'search_in_listings']);
Route::post('/listing-filter', [ListingController::class, 'listing_filter']);
Route::post('/listing-brands', [ListingController::class, 'listing_brands']);
Route::post('/listing-models', [ListingController::class, 'listing_models']);
Route::post('/listing-trims', [ListingController::class, 'listing_trims']);
Route::post('/jumbotron-brands', [HomeController::class, 'jumbotron_brands']);
Route::post('/jumbotron-models', [HomeController::class, 'jumbotron_models']);
Route::post('/accept-cookie', [HomeController::class,'accept_cookie']);
