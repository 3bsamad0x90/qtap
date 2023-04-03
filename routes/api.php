<?php

use App\Http\Controllers\api\AuthinticationController;
use App\Http\Controllers\api\CardController;
use App\Http\Controllers\api\ContactMessagesController;
use App\Http\Controllers\api\OrdersController;
use App\Http\Controllers\api\PagesController;
use App\Http\Controllers\api\ProductsController;
use App\Http\Controllers\api\ReviewController;
use App\Http\Controllers\api\StaticPagesController;
use App\Http\Controllers\api\UserController;
use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware'=> ['api']], function () {
    Route::get('/mainpage',[StaticPagesController::class,'mainpage']);
    Route::get('/contactus',[StaticPagesController::class, 'contactus']);
    //products
    Route::get('/products',[ProductsController::class, 'products']);
    Route::get('/products/{product}/details',[ProductsController::class, 'productDetails']);


    //Terms & Conditions
    Route::get('/aboutus',[StaticPagesController::class, 'aboutus']);
    Route::get('/termsConditions',[PagesController::class, 'termsConditions']);
    //Privacy Policy
    Route::get('/privacyPolicy',[PagesController::class, 'privacyPolicy']);
    //Contact Us
    Route::post('/sendContactMessage',[ContactMessagesController::class, 'sendContactMessage']);


});

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/review', [ReviewController::class, 'store'])->name('review.store');
    //orders routes
    Route::get('/orders',[OrdersController::class, 'getCart']);
    Route::post('/add',[OrdersController::class, 'addToCart']);
    Route::post('/updateCart/{id}',[OrdersController::class, 'updateCart']);
    Route::post('/removeItem/{id}',[OrdersController::class, 'removeItem']);
    Route::post('/clearCart',[OrdersController::class, 'clearCart']);
    //user
    Route::get('/myProfile',[UserController::class, 'myProfile']);
    Route::post('/UpdateMyProfile', [UserController::class, 'UpdateMyProfile']);
    //logout
    Route::post('/logout', [AuthinticationController::class, 'logout']);

    //change password
    Route::post('/changePassword', [AuthinticationController::class, 'changePassword']);
   //user Dashboard
    Route::post('/create', [CardController::class, 'create']);
    Route::get('/userCards', [CardController::class, 'userCards']);
    Route::get('/show/{card}', [CardController::class, 'show']);
    Route::post('/update/{card}', [CardController::class, 'update']);
});


/**
 *
 * //authintication routes
 *
 */
Route::post('/user/register',[AuthinticationController::class,'register']);
Route::post('/user/login',[AuthinticationController::class,'login']);

//forgot password
Route::post('/user/forgotPassword',[AuthinticationController::class, 'forgotPassword']);
//reset password
Route::post('/user/resetPassword',[AuthinticationController::class, 'resetPassword']);
