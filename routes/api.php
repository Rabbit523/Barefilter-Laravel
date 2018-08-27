<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('cors')->group(function () {
    Route::get('/', 'Api\ApiController@index')->name("barefilter-api");
    Route::get('tasks', 'Api\ApiController@tasks');
    Route::post('contact', 'Api\ApiController@contact');
    Route::post('technical-service', 'Api\ApiController@bookTechnicalService');

    Route::get('bring-postal-code/{pnr?}', 'Api\ThirdPartyApisController@lookupPostalCode');

    Route::get('netaxept/terminal/{transactionId}/{isMobile?}', 'Api\ThirdPartyApisController@terminal');
    Route::get('netaxept/query/{transactionId?}', 'Api\ThirdPartyApisController@query');
    Route::post('netaxept/register', 'Api\ThirdPartyApisController@register');
    Route::post('netaxept/auth', 'Api\ThirdPartyApisController@auth');
    Route::post('netaxept/capture', 'Api\ThirdPartyApisController@capture');

    Route::get('cargonizer/transport-agreement', 'Api\ThirdPartyApisController@transportAgreement');
    Route::get('cargonizer/pickup-points/{postCode?}', 'Api\ThirdPartyApisController@pickupPoints');
    Route::get('cargonizer/printers', 'Api\ThirdPartyApisController@printers');
    Route::get('cargonizer/labels/{consignmentIds?}', 'Api\ThirdPartyApisController@labelsPDF');
    Route::get('cargonizer/billway/{consignmentIds?}', 'Api\ThirdPartyApisController@billwayPDF');
    Route::get('cargonizer/goods-declaration/{consignmentIds?}', 'Api\ThirdPartyApisController@goodsDeclarationPDF');
    Route::post('cargonizer/consignement', 'Api\ThirdPartyApisController@consignement');
    Route::post('cargonizer/estimate-shipping', 'Api\ThirdPartyApisController@estimateShipping');
    
    Route::get('users/init', 'Api\UsersController@init');
    Route::get('users/members/{q?}', 'Api\UsersController@members');
    Route::get('users/partners/{q?}', 'Api\UsersController@partners');
    Route::get('users/registered/{email?}', 'Api\UsersController@registered');
    Route::get('users/password-code/{email?}', 'Api\UsersController@getPasswordResetCode');
    Route::get('users/addresses/{uid?}', 'Api\UsersController@myAddresses');
    Route::get('users/profile/{uid?}', 'Api\UsersController@profile');
    Route::post('users/add-member', 'Api\UsersController@addMember');
    Route::post('users/add-partner', 'Api\UsersController@addPartner');
    Route::post('users/add-address', 'Api\UsersController@addAddress');
    Route::post('users/delete-address', 'Api\UsersController@deleteAddress');
    Route::post('users/password', 'Api\UsersController@password');
    Route::post('users/reset-password', 'Api\UsersController@resetPassword');
    Route::post('users/update', 'Api\UsersController@update');
    Route::post('users/delete', 'Api\UsersController@delete');
    
    
    
    // Route::get('stores/product/{handle?}', 'Api\StoresController@product');
    // Route::get('stores/cart/{items?}', 'Api\StoresController@cart');
    // Route::get('stores/discount/{code?}', 'Api\StoresController@discount');
    // Route::get('stores/search/{q?}', 'Api\StoresController@search');
    // Route::get('stores/search-categories/{q?}', 'Api\StoresController@searchCategories');
    // Route::get('stores/advanced-search/', 'Api\StoresController@advancedSearch');
    
    // Route::get('stores/categories/', 'Api\StoresController@categories');
    // Route::post('stores/product-check', 'Api\StoresController@productCheck');
    // Route::post('stores/category-check', 'Api\StoresController@categoryCheck');
    // Route::post('stores/create-category/', 'Api\StoresController@createCategory');
    // Route::post('stores/update-category/', 'Api\StoresController@updateCategory');
    // Route::post('stores/delete-category/', 'Api\StoresController@deleteCategory');
    // Route::get('stores/discounts/', 'Api\StoresController@discounts');
    // Route::post('stores/create-discount/', 'Api\StoresController@createDiscount');
    // Route::post('stores/update-discount/', 'Api\StoresController@updateDiscount');
    // Route::get('stores/products/', 'Api\StoresController@products');
    // Route::post('stores/create-product/', 'Api\StoresController@createProduct');
    // Route::post('stores/update-product/', 'Api\StoresController@updateProduct');
    // Route::post('stores/add-product-image/', 'Api\StoresController@addProductImage');
    // Route::post('stores/delete-product-image/', 'Api\StoresController@deleteProductImage');
    
    // Route::post('stores/category-image/', 'Api\StoresController@manageCategoryImage');
    // Route::post('stores/category-item-image/', 'Api\StoresController@manageCategoryItemImage');
    // Route::post('stores/check-stock/', 'Api\StoresController@stockCheck');
    // Route::post('stores/partner-logo/', 'Api\StoresController@managePartnerLogo');



    Route::post('stores/delete-product/', 'Api\StoresController@deleteProduct');
    Route::get('stores/product/{handle?}', 'Api\StoresController@product');
    Route::get('stores/cart/{items?}', 'Api\StoresController@cart');
    Route::get('stores/discount/{code?}', 'Api\StoresController@discount');
    Route::get('stores/search/{q?}', 'Api\StoresController@search');
    Route::get('stores/search-categories/{q?}', 'Api\StoresController@searchCategories');
    Route::get('stores/advanced-search/', 'Api\StoresController@advancedSearch');
    Route::get('stores/categories/', 'Api\StoresController@categories');
    Route::get('stores/maincategories/', 'Api\StoresController@maincategories');
    Route::post('stores/product-check', 'Api\StoresController@productCheck');
    Route::post('stores/category-check', 'Api\StoresController@categoryCheck');
    Route::post('stores/create-category/', 'Api\StoresController@createCategory');
    Route::post('stores/save-subcategory/', 'Api\StoresController@saveSubCategory');
    Route::post('stores/subcategories/', 'Api\StoresController@getAllSubCategories');
    Route::post('stores/update-category/', 'Api\StoresController@updateCategory');
    Route::post('stores/delete-category/', 'Api\StoresController@deleteCategory');
    Route::get('stores/discounts/', 'Api\StoresController@discounts');
    Route::post('stores/create-discount/', 'Api\StoresController@createDiscount');
    Route::post('stores/update-discount/', 'Api\StoresController@updateDiscount');
    Route::get('stores/products/', 'Api\StoresController@products');
    Route::post('stores/getParentId/', 'Api\StoresController@getParentID');
    Route::post('stores/getSubID/', 'Api\StoresController@getSubID');
    Route::post('stores/create-product/', 'Api\StoresController@createProduct');
    Route::post('stores/update-product/', 'Api\StoresController@updateProduct');
    Route::post('stores/add-product-image/', 'Api\StoresController@addProductImage');
    Route::post('stores/delete-product-image/', 'Api\StoresController@deleteProductImage');
    Route::post('stores/category-banner-image/', 'Api\StoresController@manageCategoryBannerImage');
    Route::post('stores/sub-category-banner-image/', 'Api\StoresController@manageCategorySubBannerImage');
    Route::post('stores/category-cat-image/', 'Api\StoresController@manageCategoryCatImage');
    Route::post('stores/sub-category-cat-image/', 'Api\StoresController@manageCategorySubCatImage');
    Route::post('stores/partner-logo/', 'Api\StoresController@managePartnerLogo');
    Route::post('stores/check-stock/', 'Api\StoresController@stockCheck');
    Route::get('stores/mainTypecategories/', 'Api\StoresController@mainTypecategories');
    Route::post('stores/main-category-banner-image/', 'Api\StoresController@manageMainCategoryBannerImage');
    Route::post('stores/main-category-cat-image/', 'Api\StoresController@manageMainCategoryCatImage');

    
    Route::get('subscriptions/today', 'Api\OrdersController@today');
    Route::get('subscriptions/monthly', 'Api\OrdersController@monthly');
    Route::get('orders/search/{id}', 'Api\OrdersController@search');
    Route::get('orders/dashboard/{startDate?}/{endDate?}', 'Api\OrdersController@dashboard');
    Route::get('orders/productlist/{startDate?}/{endDate?}', 'Api\OrdersController@productlist');
    Route::get('orders/subscription-types', 'Api\OrdersController@subscriptionTypes');
    Route::get('orders/profile/{id?}', 'Api\OrdersController@profile');
    Route::get('orders/history/{uid?}', 'Api\OrdersController@history');
    Route::get('orders/timeframed-history/{uid?}/{sid?}/{startDate?}/{endDate?}', 'Api\OrdersController@timeframedHistory');
    Route::get('orders/one-time-transactions/{uid?}', 'Api\OrdersController@oneTimeTransactions');
    Route::get('orders/subscriptions/{uid?}', 'Api\OrdersController@subscriptions');
    Route::get('orders/browse-subscriptions/{startDate?}/{endDate?}', 'Api\OrdersController@browseSubscriptions');
    
    Route::post('orders/place', 'Api\OrdersController@place');
    Route::post('orders/transfer-subscription', 'Api\OrdersController@transferSubscription');
    Route::post('orders/cancel-subscription', 'Api\OrdersController@cancelSubscription');
    Route::post('orders/delete', 'Api\OrdersController@delete');
    Route::post('orders/delete-subscription-order', 'Api\OrdersController@deleteSubscriptionOrder');

    Route::get('buildings/mine/{uid?}', 'Api\BuildingsController@mine');
    Route::get('buildings/profile/{id?}', 'Api\BuildingsController@profile');
    Route::post('buildings/add', 'Api\BuildingsController@add');
    Route::post('buildings/add-facility', 'Api\BuildingsController@addFacility');


    Route::get('settings', 'Api\SettingsController@get');
    Route::post('settings/update', 'Api\SettingsController@update');



    Route::get('content', 'Api\ContentController@listPages');
    Route::get('content/{handle?}', 'Api\ContentController@getPage');
    Route::post('content/update', 'Api\ContentController@updatePage');

    Route::get('migrations/restore', 'Api\MigrationsController@restore');
    Route::get('migrations/categories', 'Api\MigrationsController@categories');
    Route::get('migrations/products', 'Api\MigrationsController@products');
    Route::get('migrations/product-sizes', 'Api\MigrationsController@productSizes');
    Route::get('migrations/images', 'Api\MigrationsController@productImages');
    Route::get('migrations/members', 'Api\MigrationsController@members');
    Route::get('migrations/orders', 'Api\MigrationsController@orders');
    Route::get('migrations/order-products', 'Api\MigrationsController@orderProducts');
    Route::get('migrations/discover-subscriptions', 'Api\MigrationsController@discoverSubscriptions');
    Route::get('migrations/patch-products/{handle}', 'Api\MigrationsController@patchProducts');
});

Route::middleware('web')->group(function(){
    Route::get('orders/export-to-excel/{uid?}/{sid?}/{startDate?}/{endDate?}', 'Api\OrdersController@exportToExcel');
});