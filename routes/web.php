<?php

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
Route::middleware('web')->group(function () {
    Route::get('/', 'Frontend\PagesController@index')->name("home");
    Route::get('/dashboard', 'Frontend\PagesController@dashboard')->name("dashboard");
    Route::get('/order-completed/{orderId?}', 'Frontend\PagesController@orderCompleted')->name("order-completed");
    Route::get('/abonnement', 'Frontend\PagesController@subscription')->name('subscription');
    Route::get('/partner', 'Frontend\PagesController@partner');
    Route::get('/om-oss', 'Frontend\PagesController@about')->name("about");
    Route::get('/support', 'Frontend\PagesController@support')->name('support');
    Route::get('/kontakt-oss', 'Frontend\PagesController@contact')->name('contact');
    Route::get('/kundeservice', 'Frontend\PagesController@customerService')->name('customer-service');
    Route::get('/sitemap', 'Frontend\PagesController@sitemap')->name("sitemap");
    Route::get('/search/{q?}', 'Frontend\PagesController@search')->name('search');
    Route::get('/salgs-og-leveringsbetingelser', 'Frontend\PagesController@tos')->name("tos");
    
    
    Route::get('/nettbutikk/{type?}/{category?}/{page?}', 'Frontend\StoresController@index')->name('store');
    Route::get('/handlekurv', 'Frontend\StoresController@cart')->name("checkout");
    Route::get('/produkt/{identifier?}', 'Frontend\StoresController@product')->name('product');
    Route::get('/betaling', 'Frontend\StoresController@payment')->name("payment");
    
    Route::get('/logg-inn', 'Frontend\UsersController@login')->name('login');
    Route::get('/passord', 'Frontend\UsersController@forgotPassword')->name('forgot-password');
    Route::get('/logout', 'Frontend\UsersController@logout');
    Route::get('/bli-kunde', 'Frontend\UsersController@register')->name('register');

    Route::get('/users/me', 'Api\UsersController@me');
    Route::post('/users/authenticate', 'Api\UsersController@authenticate');
    Route::post('/users/join', 'Api\UsersController@join');
    Route::post('/orders/place', 'Api\OrdersController@place');


    // 301 Redirect
    Route::get('/site-map', 'Frontend\LegacyController@sitemap');
    Route::get('/partner/login', 'Frontend\LegacyController@partnerLogin');
    Route::get('/login', 'Frontend\LegacyController@memberLogin');
    Route::get('/become-member', 'Frontend\LegacyController@becomeMember');
    Route::get('/cart', 'Frontend\LegacyController@cart');
    Route::get('/home', 'Frontend\LegacyController@home');
    Route::get('/store/residential', 'Frontend\LegacyController@residentialStore');
    Route::get('/store/industry', 'Frontend\LegacyController@industrialStore');
    Route::get('/kategori/{type?}/{category?}/{slug?}', 'Frontend\LegacyController@filters');
    Route::get('/subkategori/{type?}/{slug?}', 'Frontend\LegacyController@subcategory');
    Route::get('/produkt/{id?}/{slug?}', 'Frontend\LegacyController@filter');
    Route::get('/subscription', 'Frontend\LegacyController@subscription');
    Route::get('/about-us', 'Frontend\LegacyController@about');
    Route::get('/contact', 'Frontend\LegacyController@contact');
    Route::get('/customer-support', 'Frontend\LegacyController@customerService');
    Route::get('/payment', 'Frontend\LegacyController@payment');
    Route::get('/tos', 'Frontend\LegacyController@tos');
    
    
});
