<?php

use App\Http\Controllers\Api\AdminCustomer\AdminCustomerController;
use App\Http\Controllers\Api\Announcement\AnnouncementTextController;
use App\Http\Controllers\Api\Auth\AdminAuthController;
use App\Http\Controllers\Api\Auth\ManagePermissionController;
use App\Http\Controllers\Api\Category\CategoryController;
use App\Http\Controllers\Api\CustomerFetch\FetchController;
use App\Http\Controllers\Api\Icon\IconController;
use App\Http\Controllers\Api\Product\ProductController;
use App\Http\Controllers\Api\TestApiController;

use App\Http\Controllers\Api\Customers\CustomerAuthController;
use App\Http\Controllers\Api\Customers\CustomerController;
use App\Http\Controllers\Api\Customers\AddToCartController;
use App\Http\Controllers\Api\Customers\OrderController;

use App\Http\Controllers\Api\Exports\ExportController;
use App\Http\Controllers\Api\Imports\ImportController;
use App\Services\Auth\Admin\Product\CustomerProductFetchService;

use Illuminate\Support\Facades\Route;

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

//This is changes to dev
Route::group(['prefix' => 'v1'],function(){

    Route::post('/admin/login',[AdminAuthController::class,'login']);
    Route::post('/admin/register',[AdminAuthController::class,'register']);

    Route::post('/customer/login', [CustomerAuthController::class, 'customer_login']);
    Route::post('/customer/register', [CustomerAuthController::class, 'customer_register']);
    Route::post('/customer/forgot_password', [CustomerAuthController::class, 'customer_forgot_password']);
    Route::post('/customer/reset_password', [CustomerAuthController::class, 'customer_reset_password']);


    Route::group(['prefix' => 'exports'],function()
        {
            $export = ExportController::class;
            Route::get('/products',[$export,'products']);
            Route::get('/customers',[$export,'customers']);
        });

    Route::group(['prefix' => 'imports'],function()
        {
            $import = ImportController::class;

            Route::post('/products',[$import,'products']);
        });

    Route::middleware(['auth:sanctum'])->group(function () {

        // Admin.............................
            Route::group(['prefix' => 'admin'],function()
        {
            $adminAuth = AdminAuthController::class;

            Route::post('/logout',[$adminAuth,'logout']);
            Route::get('/profile',[$adminAuth,'profile']);
            Route::get('/login_history',[$adminAuth,'getLoginHistory']);

            //Routes registered under this middleware need to be has permission to perform task with routes
            Route::middleware(['has_permission'])->group(function()
            {
                //icons section
                Route::group(['prefix' => 'icons'],function()
                {
                    $icon = IconController::class;
                    Route::get('/',[$icon,'index']);
                    Route::post('/store/{icon}',[$icon,'upsert']);
                    Route::post('/store',[$icon,'upsert']);
                    Route::post('/destroy',[$icon,'destroy']);
                });

                //categories section
                Route::group(['prefix' => 'categories'],function()
                {
                    $category = CategoryController::class;
                    Route::get('/',[$category,'index']);
                    Route::post('/store/{category}',[$category,'upsert']);
                    Route::post('/store',[$category,'upsert']);
                    Route::post('/destroy',[$category,'destroy']);
                });

                //products section
                Route::group(['prefix' => 'products'],function()
                {
                    $product = ProductController::class;
                    Route::get('/',[$product,'index']);
                    Route::get('/{product}',[$product,'show']);
                    Route::put('/store/{product}',[$product,'upsert']);
                    Route::post('/store',[$product,'upsert']);
                    Route::post('/destroy',[$product,'destroy']);
                });

                //customer section
                Route::group(['prefix' => 'customers'],function()
                {
                    $customer = AdminCustomerController::class;
                    Route::get('/',[$customer,'index']);
                    Route::get('/{id}',[$customer,'show']);

                });

                Route::group(['prefix' => 'announcements'],function()
                {
                    Route::group(['prefix' => 'text'],function()
                    {
                    $announcement = AnnouncementTextController::class;
                    Route::get('/',[$announcement,'index']);
                    Route::post('/store',[$announcement,'upsert']);
                    Route::post('/store/{announcement}',[$announcement,'upsert']);
                    Route::get('/{announcement}',[$announcement,'destroy']);
                    });
                });

                //export section

                //import section

                //Announcement

                Route::get('/test',[TestApiController::class,'test']);
            });

                //Routes where admin manage permission
                Route::middleware(['admin_manage_permission'])->group(function()
                {
                    $managePermission = ManagePermissionController::class;
                    Route::get('/role/{role}',[$managePermission,'index']);
                    Route::post('/role/{role}',[$managePermission,'store']);

                    Route::get('/route_lists',[$managePermission,'routeList']);
                    Route::post('/routes',[$managePermission,'requirePermission']);
                });

        });



        // Customer............................
                Route::group(['prefix' => 'customer'], function (){

                    $customerAuthController = CustomerAuthController::class;
                    $customerController = CustomerController::class;



                    //customer section
                    Route::group(['prefix' => 'fetch'],function()
                    {
                        $customer = FetchController::class;
                        //categories
                        Route::get('/categories',[$customer,'categories']);

                        //icons
                        Route::get('/icons',[$customer,'icons']);

                        //products
                        Route::get('/products',[$customer,'products']);
                        Route::get('/products/{product}',[$customer,'showProduct']);

                    });

                    //auth section
                    Route::get('/logout', [$customerAuthController, 'customer_logout']);
                    Route::get( '/logout_all', [$customerAuthController, 'customer_logout_all']);
                    Route::get('/logout_all_except_current', [$customerAuthController, 'customer_logout_all_except_current']);
                    Route::get('/tokens', [$customerAuthController, 'tokens']);

                    // put customerId in empty {} when generate route for find with "id";
                    Route::resource('/management', $customerController)->except(['create', 'store', 'edit']);
                    Route::get('/management/force_delete/{id}', [$customerController, 'force_delete']);
                    /*Route::get('/deleted_customers', [$customerController, 'deleted_customers']);*/
                    Route::get('/management/restore/{id}', [$customerController, 'restore']);

                    //add to card section
                    $customerAddToCart = AddToCartController::class;
                    Route::resource('/add_to_cart', $customerAddToCart)->except(['create', 'edit']);

                    //order
                    $customerOrder = OrderController::class;
                    Route::get('/request_order', [$customerOrder, 'request_order']);
                    Route::get('/orders', [$customerOrder, 'orders']);
                    Route::post('/confirm_order', [$customerOrder, 'confirm_order']);
                    Route::get('/confirm_payment/{order_id}', [$customerOrder, 'confirm_payment']);
            });

    });

});

