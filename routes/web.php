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

/**
 * Routes for BackEnds
 */
Route::group(
    [
        'namespace' => 'Backends'
    ],
    function () {
        Route::get('/login', 'Auth\LoginController@showLogin')->name('login');
        Route::post('/login', 'Auth\LoginController@Login')->name('login.post');
        Route::post('/logout', 'Auth\LoginController@Logout')->name('logout');
        // middleware
        Route::group(['middleware' => 'auth'], function () {
            Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
            Route::get('/', function () {
                return redirect()->route('dashboard');
            });
            // User Route
            Route::group(['prefix' => 'users', 'as' => 'user.'], function () {
                Route::get('/', 'UsersController@index')
                    ->name('index');
                Route::get('/create', 'UsersController@create')
                    ->name('create');
                Route::post('/store', 'UsersController@store')
                    ->name('store');
                Route::get('/show/{id}', 'UsersController@show')
                    ->name('show');
                Route::get('/edit/{id}', 'UsersController@edit')
                    ->name('edit');
                Route::post('/update/{id}', 'UsersController@update')
                    ->name('update');
                Route::post('/destroy', 'UsersController@destroy')
                    ->name('destroy');
            });
            // Category Route
            Route::group(['prefix' => 'categories', 'as' => 'category.'], function () {
                Route::get('/', 'CategoriesController@index')
                    ->name('index');
                Route::get('/create', 'CategoriesController@create')
                    ->name('create');
                Route::post('/store', 'CategoriesController@store')
                    ->name('store');
                Route::get('/show/{id}', 'CategoriesController@show')
                    ->name('show');
                Route::get('/edit/{id}', 'CategoriesController@edit')
                    ->name('edit');
                Route::post('/update/{id}', 'CategoriesController@update')
                    ->name('update');
                Route::post('/destroy', 'CategoriesController@destroy')
                    ->name('destroy');
            });
            //  Product Route
            Route::group(['prefix' => 'products', 'as' => 'product.'], function () {
                Route::get('/', 'ProductsController@index')
                    ->name('index');
                Route::get('/create', 'ProductsController@create')
                    ->name('create');
                Route::post('/store', 'ProductsController@store')
                    ->name('store');
                Route::get('/show/{id}', 'ProductsController@show')
                    ->name('show');
                Route::get('/edit/{id}', 'ProductsController@edit')
                    ->name('edit');
                Route::post('/update/{id}', 'ProductsController@update')
                    ->name('update');
                Route::post('/destroy', 'ProductsController@destroy')
                    ->name('destroy');
            });
            // Customers
            Route::group(['prefix' => 'customers', 'as' => 'customer.'], function() {
                Route::get('/', 'CustomersController@index')->name('index');
                Route::get('/create', 'CustomersController@create')->name('create');
                Route::post('/store', 'CustomersController@store')->name('store');
                Route::get('/show/{id}', 'CustomersController@show')->name('show');
                Route::get('/edit/{id}', 'CustomersController@edit')->name('edit');
                Route::post('/update/{id}', 'CustomersController@update')->name('update');
                Route::post('/destroy', 'CustomersController@destroy')->name('destroy');
            });
            // Staffs
            Route::group(['prefix' => 'staffs', 'as' => 'staff.'], function() {
                Route::get('/', 'StaffsController@index')->name('index');
                Route::get('/create', 'StaffsController@create')->name('create');
                Route::post('/store', 'StaffsController@store')->name('store');
                Route::get('/show/{id}', 'StaffsController@show')->name('show');
                Route::get('/edit/{id}', 'StaffsController@edit')->name('edit');
                Route::post('/update/{id}', 'StaffsController@update')->name('update');
                Route::post('/destroy', 'StaffsController@destroy')->name('destroy');
                // create group staff
                Route::post('group/store', 'GroupStaffsController@store')
                    ->name('group.store');
                Route::post('group/update', 'GroupStaffsController@update')
                    ->name('group.update');
                // create group with multiple staff
                Route::get('group/create/{id}', 'GroupStaffsController@groupCreate')
                    ->name('group.create');
                Route::get('group/product/create/', 'GroupStaffsController@groupCreateProduct')
                    ->name('group.product.create');
                Route::post('group/update_ids', 'GroupStaffsController@groupUpdateByStaffIds')
                    ->name('group.update.staffIds');
            });
            // Staffs
            Route::group(['prefix' => 'sales', 'as' => 'sale.'], function() {
                Route::get('/', 'SalesController@index')->name('index');
                Route::get('/create', 'SalesController@create')->name('create');
                Route::post('/store', 'SalesController@store')->name('store');
                Route::get('/show/{id}', 'SalesController@show')->name('show');
                Route::get('/edit/{id}', 'SalesController@edit')->name('edit');
                Route::post('/update/{id}', 'SalesController@update')->name('update');
                Route::post('/destroy', 'SalesController@destroy')->name('destroy');
                Route::get('/product', 'SalesController@getProductByCategory')->name('product');
                Route::get('/invoiceSalePDF/{id}', 'SalesController@downloadInvoiceSalePDF')->name('downloadPDF');
                Route::get('/invoice-view-sale-pdf/{id}', 'SalesController@viewInvoiceSalePDF')->name('viewPDF');
            });
            // Setting Route
            Route::group(['prefix' => 'settings', 'as' => 'setting.'], function () {
                Route::get('/', 'SettingsController@index')
                    ->name('index');
                Route::get('/customer-of-staff', 'SettingsController@customerOwnerByStaff')
                    ->name('staff_to_customer');
                Route::post('/storeOrUpdate', 'SettingsController@storeOrUpdate')
                    ->name('storeOrUpdate');
            });
            // customer type Route
            Route::group(['prefix' => 'customer_types', 'as' => 'customer_type.'], function () {
                Route::get('/', 'CustomerTypesController@index')
                    ->name('index');
                Route::get('/create', 'CustomerTypesController@create')
                    ->name('create');
                Route::post('/store', 'CustomerTypesController@store')
                    ->name('store');
                Route::get('/show/{id}', 'CustomerTypesController@show')
                    ->name('show');
                Route::get('/edit/{id}', 'CustomerTypesController@edit')
                    ->name('edit');
                Route::post('/update/{id}', 'CustomerTypesController@update')
                    ->name('update');
                Route::post('/destroy', 'CustomerTypesController@destroy')
                    ->name('destroy');
            });
            // customer owed Route
            Route::group(['prefix' => 'customer_oweds', 'as' => 'customer_owed.'], function () {
                Route::get('/', 'CustomerOwedsController@index')
                    ->name('index');
                Route::get('/edit/{id}', 'CustomerOwedsController@edit')
                    ->name('edit');
                Route::get('/edit/pay_all/{id}', 'CustomerOwedsController@editPayAll')
                    ->name('edit_pay_all');
                Route::post('/update/{id}', 'CustomerOwedsController@update')
                    ->name('update');
                Route::post('/update_pay_day', 'CustomerOwedsController@updatePayModal')
                    ->name('update.pay_day');
                Route::post('/update_set_date', 'CustomerOwedsController@updateSetDateModal')
                    ->name('update.set_date');
                Route::get('/sale', 'CustomerOwedsController@getSaleByCustomer')
                    ->name('sale');
            });
            // customer maps
            Route::group(['prefix' => 'customer_maps', 'as' => 'customer_map.'], function () {
                Route::get('/', 'CustomerMapsController@index')
                    ->name('index');
            });

        });
    }
);
// route laravel filemanager
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
/**
 * Routes for FrontEnds
 */
Route::get('/list_medecin', 'ProductRRPSController@pageProduct')->name('product_rrps');