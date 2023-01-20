<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\panel\AuthController;
use App\Http\Controllers\panel\BankManagement;
use App\Http\Controllers\panel\LoanController;
use App\Http\Controllers\panel\UserController;
use App\Http\Controllers\panel\AdminController;
use App\Http\Controllers\panel\BrandController;
use App\Http\Controllers\panel\ColorController;
use App\Http\Controllers\panel\PolicyController;
use App\Http\Controllers\panel\CountryController;
use App\Http\Controllers\panel\ListingController;
use App\Http\Controllers\panel\MessageController;
use App\Http\Controllers\panel\SettingController;
use App\Http\Controllers\panel\CategoryController;
use App\Http\Controllers\panel\CurrencyController;
use App\Http\Controllers\panel\LocationController;
use App\Http\Controllers\panel\UserTypeController;
use App\Http\Controllers\panel\AttributeController;
use App\Http\Controllers\panel\DashboardController;
use App\Http\Controllers\panel\ExchangeRateController;
use App\Http\Controllers\panel\AdvManagementController;
use App\Http\Controllers\panel\AttributeGroupController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/login', [AuthController::class, 'login'])->name('admin.login');
Route::post('/check-login', [AuthController::class, 'check_login'])->name('admin.check_login');
Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::group(['middleware' => 'auth.admin:admin'], function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('admin.dashboard');

    // Attribute Management
    Route::get('/attributes', [AttributeController::class, 'home'])->name('admin.attributes.home');
    Route::post('/attributes/add', [AttributeController::class, 'add'])->name('admin.attributes.add');
    Route::post('/attributes/multiple-add', [AttributeController::class, 'multiple_add'])->name('admin.attributes.multiple_add');
    Route::get('/attributes/detail/{guid}', [AttributeController::class, 'detail'])->name('admin.attributes.detail');
    Route::post('/attributes/detail/update', [AttributeController::class, 'update'])->name('admin.attributes.update');
    Route::post('/attributes/detail/delete', [AttributeController::class, 'delete'])->name('admin.attributes.delete');

    // Attribute Groups Management
    Route::get('/attribute_groups', [AttributeGroupController::class, 'home'])->name('admin.attribute_groups.home');
    Route::post('/attribute_groups/add', [AttributeGroupController::class, 'add'])->name('admin.attribute_groups.add');
    Route::get('/attribute_groups/detail/{guid}', [AttributeGroupController::class, 'detail'])->name('admin.attribute_groups.detail');
    Route::post('/attribute_groups/detail/update', [AttributeGroupController::class, 'update'])->name('admin.attribute_groups.update');
    Route::post('/attribute_groups/detail/delete', [AttributeGroupController::class, 'delete'])->name('admin.attribute_groups.delete');
    Route::post('/attribute_groups/detail/attributes/add', [AttributeGroupController::class, 'attribute_add'])->name('admin.attribute_groups.attribute.add');
    Route::post('/attribute_groups/detail/attributes/delete', [AttributeGroupController::class, 'attribute_delete'])->name('admin.attribute_groups.attribute.delete');

    // Categories Management
    Route::get('/categories', [CategoryController::class, 'home'])->name('admin.categories.home');
    Route::post('/categories/add', [CategoryController::class, 'add'])->name('admin.categories.add');
    Route::get('/categories/detail/{guid}', [CategoryController::class, 'detail'])->name('admin.categories.detail');
    Route::post('/categories/detail/update', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::post('/categories/detail/delete', [CategoryController::class, 'delete'])->name('admin.categories.delete');
    Route::post('/categories/detail/delete_inside', [CategoryController::class, 'delete_inside'])->name('admin.categories.delete_inside');

    // Locations Management
    Route::get('/locations', [LocationController::class, 'home'])->name('admin.locations.home');
    Route::post('/locations/add', [LocationController::class, 'add'])->name('admin.locations.add');
    Route::post('/locations/add/select_country', [LocationController::class, 'select_country'])->name('admin.locations.select_country');
    Route::get('/locations/detail/{guid}', [LocationController::class, 'detail'])->name('admin.locations.detail');
    Route::post('/locations/detail/update', [LocationController::class, 'update'])->name('admin.locations.update');
    Route::post('/locations/detail/delete', [LocationController::class, 'delete'])->name('admin.locations.delete');
    // Route::post('/locations/detail/user_types/add', [LocationController::class, 'user_types_add'])->name('admin.locations.user_types.add');
    // Route::post('/locations/detail/user_types/delete', [LocationController::class, 'user_types_delete'])->name('admin.locations.user_types.delete');
    Route::post('/locations/detail/categories/add', [LocationController::class, 'categories_add'])->name('admin.locations.categories.add');
    Route::post('/locations/detail/categories/delete', [LocationController::class, 'categories_delete'])->name('admin.locations.categories.delete');

    // User Types Management
    Route::get('/user_types', [UserTypeController::class, 'home'])->name('admin.user_types.home');
    Route::post('/user_types/add', [UserTypeController::class, 'add'])->name('admin.user_types.add');
    Route::get('/user_types/detail/{guid}', [UserTypeController::class, 'detail'])->name('admin.user_types.detail');
    Route::post('/user_types/detail/update', [UserTypeController::class, 'update'])->name('admin.user_types.update');
    Route::post('/user_types/detail/delete', [UserTypeController::class, 'delete'])->name('admin.user_types.delete');

    // Users Management
    Route::get('/users', [UserController::class, 'home'])->name('admin.users.home');
    Route::get('/users/detail/{guid}', [UserController::class, 'detail'])->name('admin.users.detail');
    Route::post('/users/detail/update', [UserController::class, 'update'])->name('admin.users.update');
    Route::post('/users/detail/delete', [UserController::class, 'delete'])->name('admin.users.delete');
    Route::post('/users/detail/delete_listing', [UserController::class, 'delete_listing'])->name('admin.users.delete_listing');
    Route::post('/users/detail/delete_messages', [UserController::class, 'delete_messages'])->name('admin.users.delete_messages');
    Route::post('/users/detail/chats', [UserController::class, 'chats'])->name('admin.users.chats');
    Route::get('/users/detail/status/{guid}', [UserController::class, 'status'])->name('admin.users.status');
    Route::post('/users/detail/unblock', [UserController::class, 'unblock'])->name('admin.users.unblock');

    // Listings Management
    Route::get('/listings', [ListingController::class, 'home'])->name('admin.listings.home');
    Route::get('/listings/detail/{guid}', [ListingController::class, 'detail'])->name('admin.listings.detail');
    Route::post('/listings/detail/update', [ListingController::class, 'update'])->name('admin.listings.update');
    Route::post('/listings/detail/delete', [ListingController::class, 'delete'])->name('admin.listings.delete');
    Route::post('/listings/detail/image_delete', [ListingController::class, 'image_delete'])->name('admin.listings.image_delete');
    Route::post('/listings/detail/expiry_day', [ListingController::class, 'expiry_day'])->name('admin.listings.expiry_day');
    Route::post('/listings/detail/attribute_update', [ListingController::class, 'attribute_update'])->name('admin.listings.attribute_update');
    Route::post('/listings/detail/select_brand', [ListingController::class, 'select_brand'])->name('admin.listings.select_brand');
    Route::post('/listings/detail/select_model', [ListingController::class, 'select_model'])->name('admin.listings.select_model');
    Route::post('/listings/detail/select_category', [ListingController::class, 'select_category'])->name('admin.listings.select_category');

    // Messages Management
    Route::get('/messages', [MessageController::class, 'home'])->name('admin.messages.home');
    Route::post('/messages/delete', [MessageController::class, 'delete'])->name('admin.messages.delete');

    // Countries Management
    Route::get('/countries', [CountryController::class, 'home'])->name('admin.countries.home');
    Route::get('/countries/detail/{guid}', [CountryController::class, 'detail'])->name('admin.countries.detail');
    Route::post('/countries/update', [CountryController::class, 'update'])->name('admin.countries.update');
    Route::get('/countries/status/{guid}', [CountryController::class, 'status'])->name('admin.countries.status');

    //BRAND MANAGEMENT

    Route::get('/brands', [BrandController::class, 'home'])->name('admin.brands.home');
    Route::post('/brands-add', [BrandController::class, 'add'])->name('admin.brands.add');
    Route::get('/brand-detail/{brand_guid}', [BrandController::class, 'detail'])->name('admin.brands.detail');
    Route::post('/brand-update', [BrandController::class, 'update'])->name('admin.brands.update');

    //MODEL MANAGEMENT

    Route::Post('/model-add', [BrandController::class, 'model_add'])->name('admin.brands.model.add');
    Route::get('/brand-model/{slug}', [BrandController::class, 'model_detail'])->name('admin.brands.model.detail');
    Route::post('/brand-model-update', [BrandController::class, 'model_detail_update'])->name('admin.brands.model.detail.update');


    //TRIM MANAGEMENT

    Route::post('/trim-add', [BrandController::class, 'trim_add'])->name('admin.brands.trim.add');
    Route::get('/model-trim/{slug}', [BrandController::class, 'trim_detail'])->name('admin.brands.trim.detail');
    Route::post('/trim-update', [BrandController::class, 'trim_update'])->name('admin.brands.trim.update');

    //BANK MANAGEMENT

    Route::get('/bank-management', [BankManagement::class, 'home'])->name('admin.bank_management.home');
    Route::post('/bank-management-add', [BankManagement::class, 'add'])->name('admin.bank_management.add');
    Route::get('/bank-detail/{slug}', [BankManagement::class, 'detail'])->name('admin.bank_management.detail');
    Route::Post('/bank-detail-update', [BankManagement::class, 'update'])->name('admin.bank_management.detail.update');
    Route::Post('/bank-delete', [BankManagement::class, 'delete'])->name('admin.bank_management.delete');


    Route::Post('/bank-rate-add', [BankManagement::class, 'rate_add'])->name('admin.bank_rate.add');
    Route::post('/bank-rate-delete', [BankManagement::class, 'rate_delete'])->name('admin.bank_rate.delete');

    //ADMIN MANAGEMENT

    Route::get('/admins', [AdminController::class, 'home'])->name('admin.admin.home');
    Route::post('/admin-add', [AdminController::class, 'add'])->name('admin.admin.add');
    Route::post('/admin-delete', [AdminController::class, 'delete'])->name('admin.admin.delete');
    Route::get('/admin-detail/{username}', [AdminController::class, 'detail'])->name('admin.admin.detail');
    Route::post('/admin-detail-update', [AdminController::class, 'update_admin'])->name('admin.admin.detail_update');
    Route::post('/admin-password-update', [AdminController::class, 'update_admin_password'])->name('admin.admin.update_password');

    //CURRENCY MANAGEMENT

    Route::get('/currencies', [CurrencyController::class, 'home'])->name('admin.currency.home');
    Route::get('/currency/{currency_guid}', [CurrencyController::class, 'detail'])->name('admin.currency.detail');
    Route::post('/currency-update', [CurrencyController::class, 'update'])->name('admin.currency.update');

    //EXCHANGE RATES MANAGEMENT
    Route::get('/exchange-rates', [ExchangeRateController::class, 'home'])->name('admin.exchange_rates.home');


    //POLICIES
    Route::get('/policies', [PolicyController::class, 'home'])->name('admin.policies.home');
    Route::get('/policies/{policy_guid}', [PolicyController::class, 'detail'])->name('admin.policies.detail');
    Route::post('/policy-update', [PolicyController::class, 'update'])->name('admin.policies.update');


    //LOAN REQUEST
    Route::get('/loan-requests', [LoanController::class, 'home'])->name('admin.loan.home');
    Route::get('/loan-detail/{loan_request_guid}', [LoanController::class, 'detail'])->name('admin.loan.detail');
    Route::post('/loan-delete', [LoanController::class, 'delete'])->name('admin.loan.delete');

    //COLORS MANAGEMENT
    Route::get('/colors', [ColorController::class, 'home'])->name('admin.colors.home');
    Route::post('/color-add', [ColorController::class, 'add'])->name('admin.colors.add');
    Route::get('/color-detail/{color_guid}', [ColorController::class, 'detail'])->name('admin.colors.detail');
    Route::post('/color-update', [ColorController::class, 'update'])->name('admin.colors.update');
    Route::post('/color-delete', [ColorCOntroller::class, 'delete'])->name('admin.colors.delete');

    //SETTINGS
    Route::get('/settings', [SettingController::class, 'home'])->name('admin.settings.home');
    Route::post('/setting-add', [SettingController::class, 'add'])->name('admin.settings.add');
    Route::post('/setting-delete', [SettingController::class, 'delete'])->name('admin.settings.delete');
    Route::get('/setting-detail/{setting_guid}', [SettingController::class, 'detail'])->name('admin.settings.detail');
    Route::post('/setting-update', [SettingController::class, 'update'])->name('admin.settings.update');

    //ADV MANAGEMENT
    Route::get('/adv-management', [AdvManagementController::class, 'home'])->name('admin.advm.home');
    Route::get('/adv-management-detail/{advm_guid}', [AdvManagementController::class, 'detail'])->name('admin.advm.detail');
    Route::post('/adv-management-add', [AdvManagementController::class, 'add'])->name('admin.advm.add');
    Route::post('/adv-management-update', [AdvManagementController::class, 'update'])->name('admin.advm.update');
    Route::post('/adv-management-delete', [AdvManagementController::class, 'delete'])->name('admin.advm.delete');


    //EXCEL DENEME
    Route::post('/excel-post', [LoanController::class, 'example_excel'])->name('admin.example_excel');


    //Error
    Route::get('/{any}', function() {
        return view('error');
    });
});
