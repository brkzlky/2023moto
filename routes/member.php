<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\AuthController;
use App\Http\Controllers\user\HomeController;
use App\Http\Controllers\user\MessageController;
use App\Http\Controllers\user\ProfileController;
use App\Http\Controllers\user\ListingController;
use App\Http\Controllers\user\FavouriteController;
use App\Http\Controllers\site\HomeController as SiteHomeController;

Route::get('/', function () {
    return redirect()->route('member.login');
})->name('member.home');

//Auth
Route::get('/login',[AuthController::class,'login'])->name('member.login');
Route::get('/login-with-{social}',[AuthController::class,'loginSocial'])->name('member.loginSocial');
Route::post('/do-login',[AuthController::class,'doLogin'])->name('member.doLogin');
Route::get('/logout',[AuthController::class,'logout'])->name('member.logout');
Route::get('/forget-password',[AuthController::class,'forgetPassword'])->name('member.forgetPassword');
Route::post('/do-recovery',[AuthController::class,'doRecovery'])->name('member.doRecovery');
Route::any('/recovery-code',[AuthController::class,'recoveryCode'])->name('member.recoveryCode');
Route::any('/set-password',[AuthController::class,'setPassword'])->name('member.setPassword');
Route::get('/register',[AuthController::class,'register'])->name('member.register');
Route::post('/do-register',[AuthController::class,'doRegister'])->name('member.doRegister');
Route::get('/corporate-register',[AuthController::class,'corporateRegister'])->name('member.corporateRegister');
Route::post('/do-corporate-register',[AuthController::class,'doCorporateRegister'])->name('member.doCorporateRegister');
Route::get('/google-login',[AuthController::class,'googleLogin'])->name('member.googleLogin');
Route::get('/tiktok-login',[AuthController::class,'tiktokLogin'])->name('member.tiktokLogin');
Route::post('/instagram-login',[AuthController::class,'instagramLogin'])->name('member.instaLogin');
Route::post('/instagram-logout',[AuthController::class,'instagramLogout'])->name('member.instaLogout');

//Pages
Route::get('/dashboard',[HomeController::class,'dashboard'])->name('member.dashboard');
Route::get('/messages',[MessageController::class,'messages'])->name('member.messages');
Route::get('/profile',[ProfileController::class,'profile'])->name('member.profile');
Route::get('/listings',[ListingController::class,'listings'])->name('member.listings');
Route::get('/create-listings',[ListingController::class,'new_listing'])->name('member.new_listing');
Route::get('/listings/{listing_no}',[ListingController::class,'listing_detail'])->name('member.listing_detail');
Route::get('/favourites',[FavouriteController::class,'favourites'])->name('member.favourites');

//Post Actions
Route::post('/profile-pw', [ProfileController::class, 'profile_pw_change'])->name('member.profile_pw_change');
Route::post('/profile-info-update', [ProfileController::class, 'profile_info_update'])->name('member.profile_info_update');
Route::post('/edit-listing',[ListingController::class,'edit_listing'])->name('member.edit_listing');
Route::post('/create-listing',[ListingController::class,'complete_listing'])->name('member.complete_listing');
Route::post('/enable-listing',[ListingController::class,'enable_listing'])->name('member.enable_listing');
Route::post('/disable-listing',[ListingController::class,'disable_listing'])->name('member.disable_listing');
Route::post('/delete-listing',[ListingController::class,'delete_listing'])->name('member.delete_listing');
Route::post('/delete-favourite', [FavouriteController::class, 'delete'])->name('member.delete_fav');

//API
Route::post('/messages',[MessageController::class,'getAllMessages']);
Route::post('/messages-detail',[MessageController::class,'getMessages']);
Route::post('/create-message',[MessageController::class,'createMessage']);
Route::post('/send-message',[MessageController::class,'sendMessage']);
Route::post('/delete-message',[MessageController::class,'deleteMessage']);
Route::post('/block-user',[MessageController::class,'blockUser']);
Route::post('/unblock-user',[MessageController::class,'unblockUser']);
Route::post('/finance-rates', [SiteHomeController::class,'finance_rates']);
Route::post('/listing-detail', [ListingController::class,'get_listing_details']);
Route::post('/listing-specs', [ListingController::class,'new_listing_datas']);
Route::post('/listing-brands', [ListingController::class, 'listing_brands']);
Route::post('/listing-models', [ListingController::class, 'listing_models']);
Route::post('/listing-trims', [ListingController::class, 'listing_trims']);
Route::post('/listing-countries', [ListingController::class, 'listing_countries']);
Route::post('/listing-states', [ListingController::class, 'listing_states']);
Route::post('/listing-cities', [ListingController::class, 'listing_cities']);
Route::post('/listing-category-attributes', [ListingController::class, 'listing_category_attributes']);
Route::post('/add-to-fav', [FavouriteController::class, 'add_to_fav']);
Route::post('/remove-from-fav', [FavouriteController::class, 'remove_from_fav']);


//Error
Route::get('/{any}', function() {
    return view('error');
});
