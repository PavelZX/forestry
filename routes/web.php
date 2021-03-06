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

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('Admin')->name('admin/')->group(static function() {
        Route::prefix('admin-users')->name('admin-users/')->group(static function() {
            Route::get('/',                                             'AdminUsersController@index')->name('index');
            Route::get('/create',                                       'AdminUsersController@create')->name('create');
            Route::post('/',                                            'AdminUsersController@store')->name('store');
            Route::get('/{adminUser}/impersonal-login',                 'AdminUsersController@impersonalLogin')->name('impersonal-login');
            Route::get('/{adminUser}/edit',                             'AdminUsersController@edit')->name('edit');
            Route::post('/{adminUser}',                                 'AdminUsersController@update')->name('update');
            Route::delete('/{adminUser}',                               'AdminUsersController@destroy')->name('destroy');
            Route::get('/{adminUser}/resend-activation',                'AdminUsersController@resendActivationEmail')->name('resendActivationEmail');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('Admin')->name('admin/')->group(static function() {
        Route::get('/profile',                                      'ProfileController@editProfile')->name('edit-profile');
        Route::post('/profile',                                     'ProfileController@updateProfile')->name('update-profile');
        Route::get('/password',                                     'ProfileController@editPassword')->name('edit-password');
        Route::post('/password',                                    'ProfileController@updatePassword')->name('update-password');
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('Admin')->name('admin/')->group(static function() {
        Route::prefix('wood-species')->name('wood-species/')->group(static function() {
            Route::get('/',                                             'WoodSpecieController@index')->name('index');
            Route::get('/create',                                       'WoodSpecieController@create')->name('create');
            Route::post('/',                                            'WoodSpecieController@store')->name('store');
            Route::get('/{woodSpecie}/edit',                            'WoodSpecieController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'WoodSpecieController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{woodSpecie}',                                'WoodSpecieController@update')->name('update');
            Route::delete('/{woodSpecie}',                              'WoodSpecieController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('Admin')->name('admin/')->group(static function() {
        Route::prefix('forest-resources')->name('forest-resources/')->group(static function() {
            Route::get('/',                                             'ForestResourcesController@index')->name('index');
            Route::get('/create',                                       'ForestResourcesController@create')->name('create');
            Route::post('/',                                            'ForestResourcesController@store')->name('store');
            Route::get('/{forestResource}/edit',                        'ForestResourcesController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'ForestResourcesController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{forestResource}',                            'ForestResourcesController@update')->name('update');
            Route::delete('/{forestResource}',                          'ForestResourcesController@destroy')->name('destroy');
        });
    });
});


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('Admin')->name('admin/')->group(static function() {
        Route::prefix('forestry-indicators')->name('forestry-indicators/')->group(static function() {
            Route::get('/', 'ForestryIndicatorController@index')->name('index');
            Route::get('/calculate', 'ForestryIndicatorController@calculate')->name('calculate');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('Admin')->name('admin/')->group(static function() {
        Route::prefix('cutting-areas')->name('cutting-areas/')->group(static function() {
            Route::get('/', 'CuttingAreaController@index')->name('index');
            Route::get('/calculate', 'CuttingAreaController@calculate')->name('calculate');
        });
    });
});

Route::middleware(['auth:' . config('admin-auth.defaults.guard')])->group(static function () {
    Route::namespace('Admin')->group(static function () {
        Route::view('/admin', 'admin.homepage.index')->name('brackets/admin-auth::admin');
    });
});

Route::middleware(['auth:' . config('admin-auth.defaults.guard')])->group(static function () {
    Route::prefix('info')->name('info/')->group(static function() {
        Route::view('/structure', 'info.structure')->name('structure');
        Route::view('/services', 'info.services')->name('services');
        Route::view('/products', 'info.products')->name('products');
        Route::view('/contacts', 'info.contacts')->name('contacts');
    });
});
