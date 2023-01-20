<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\PolicyController;
use App\Http\Controllers\api\FinanceController;
use App\Http\Controllers\api\ListingController;
use App\Http\Controllers\api\MessageController;
use App\Http\Controllers\api\ProfileController;
use App\Http\Controllers\api\LocationController;
use App\Http\Controllers\api\ExpertiseController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//COUNTRIES API

Route::get('/getCountries', [AuthController::class, 'getCountries']);

//CATEGORIES API
Route::post('/categories', [ListingController::class, 'getCategories']);
Route::post('/category-listings', [ListingController::class, 'getCategoryListings']);

//LISTING DETAIL

Route::post('/listing-detail', [ListingController::class, 'getListingDetail']);


//BRANDS API
Route::post('/brands', [ListingController::class, 'getBrands']);

Route::get('/getLocations', [LocationController::class, 'getLocation']);
//LOGIN API
Route::any('/do_login', [AuthController::class, 'doLogin']);
Route::post('/google-login', [AuthController::class, 'googleLogin']);
Route::post('/instagram-login', [AuthController::class, 'instaLogin']);
Route::post('/apple-login', [AuthController::class, 'appleLogin']);

//CHANGE PASSWORD API
Route::post('/do_recovery', [AuthController::class, 'doRecovery']);
Route::any('/recovery_code', [AuthController::class, 'recoveryCode']);
Route::post('/set_password', [AuthController::class, 'setPassword']);

//REGISTER API
Route::post('/do_register', [AuthController::class, 'doRegister']);


//FINANCE API

Route::post('/loan_calculate', [FinanceController::class, 'loanCalculate']);
Route::get('/getFinanceRates', [FinanceController::class, 'getFinanceRates']);
Route::post('/loanRequest', [FinanceController::class, 'loanRequest']);

//EXPERTISE API

Route::post('/expertiseBrands', [ExpertiseController::class, 'expertiseBrands']);
Route::post('/expertiseModels', [ExpertiseController::class, 'expertiseModels']);
Route::post('/expertiseTrims', [ExpertiseController::class, 'expertiseTrims']);
Route::post('/expertiseForm', [ExpertiseController::class, 'expertiseForm']);

//MESSAGES API

Route::post('/getMessages', [MessageController::class, 'getMessages']);

Route::post('/getMessagesDetail', [MessageController::class, 'getMessagesDetail']);
Route::post('/createMessage', [MessageController::class, 'createMessage']);
Route::post('/sendMessage', [MessageController::class, 'sendMessage']);
Route::post('/deleteMessage', [MessageController::class, 'deleteMessage']);
Route::post('/blockUser', [MessageController::class, 'blockUser']);
Route::post('/unblockUser', [MessageController::class, 'unblockUser']);


//Profile API
Route::post('/changePassword', [ProfileController::class, 'changePassword']);
Route::post('/updateProfileInfo', [ProfileController::class, 'updateProfileInfo']);
Route::post('/deleteProfile', [ProfileController::class, 'deleteProfile']);


//User Listing API
Route::post('/newListing', [ListingController::class, 'newListing']);
Route::post('/newListingBrands', [ListingController::class, 'newListingBrands']);
Route::post('/newListingModels', [ListingController::class, 'newListingModels']);
Route::post('/newListingTrims', [ListingController::class, 'newListingTrims']);
Route::get('/newListingCountries', [ListingController::class, 'newListingCountries']);
Route::post('/newListingStates', [ListingController::class, 'newListingStates']);
Route::post('/newListingCities', [ListingController::class, 'newListingCities']);
Route::post('/newListingCategoryAttributes', [ListingController::class, 'newListingCategoryAttributes']);
Route::post('/editListingDetail', [ListingController::class, 'editListingDetail']);
Route::post('/myListings', [ProfileController::class, 'myListings']);
Route::post('/editListing', [ProfileController::class, 'editListing']);
Route::post('/createListing', [ProfileController::class, 'createListing']);
Route::post('/passiveListing', [ProfileController::class, 'passiveListing']);
Route::post('/activeListing', [ProfileController::class, 'activeListing']);
Route::post('/listingFilter', [ListingController::class, 'listingFilter']);
Route::post('/deleteListing', [ListingController::class, 'deleteListing']);
Route::post('/addToFav', [ListingController::class, 'addToFav']);
Route::post('/removeFav', [ListingController::class, 'removeFav']);
Route::post('/getFavorites', [ProfileController::class, 'getFavorites']);
Route::post('/searchListing', [ListingController::class, 'searchListing']);
Route::post('/sellerProfile', [ListingController::class, 'sellerProfile']);

//Policy API
Route::post('/getPolicy', [PolicyController::class, 'getPolicy']);
Route::post('/getEula', [PolicyController::class, 'getEula']);
