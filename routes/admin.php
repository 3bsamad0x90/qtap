<?php

use App\Http\Controllers\admin\AdminPanelController;
use App\Http\Controllers\admin\AdminUsersController;
use App\Http\Controllers\admin\ClientUsersController;
use App\Http\Controllers\admin\ContactMessagesController;
use App\Http\Controllers\admin\OrdersController;
use App\Http\Controllers\admin\PagesController;
use App\Http\Controllers\admin\ProductsController;
use App\Http\Controllers\admin\RolesController;
use App\Http\Controllers\admin\SettingsController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix'=>'AdminPanel','middleware'=>['isAdmin','auth']], function(){
    Route::get('/',[AdminPanelController::class,'index'])->name('admin.index');

    Route::get('/read-all-notifications', [AdminPanelController::class, 'readAllNotifications'])->name('admin.notifications.readAll');
    Route::get('/notification/{id}/details',[AdminPanelController::class, 'notificationDetails'])->name('admin.notification.details');

    Route::get('/my-profile',[AdminPanelController::class, 'EditProfile'])->name('admin.myProfile');
    Route::post('/my-profile',[AdminPanelController::class, 'UpdateProfile'])->name('admin.myProfile.update');
    Route::get('/my-password',[AdminPanelController::class, 'EditPassword'])->name('admin.myPassword');
    Route::post('/my-password',[AdminPanelController::class, 'UpdatePassword'])->name('admin.myPassword.update');
    Route::get('/notifications-settings',[AdminPanelController::class, 'EditNotificationsSettings'])->name('admin.notificationsSettings');
    Route::post('/notifications-settings',[AdminPanelController::class, 'UpdateNotificationsSettings'])->name('admin.notificationsSettings.update');

    Route::group(['prefix'=>'admins'], function(){
        Route::get('/',[AdminUsersController::class, 'index'])->name('admin.adminUsers');
        Route::get('/create', [AdminUsersController::class, 'create'])->name('admin.adminUsers.create');
        Route::post('/create', [AdminUsersController::class, 'store'])->name('admin.adminUsers.store');
        Route::get('/{id}/block/{action}', [AdminUsersController::class, 'blockAction'])->name('admin.adminUsers.block');
        Route::get('/{id}/edit', [AdminUsersController::class, 'edit'])->name('admin.adminUsers.edit');
        Route::post('/{id}/edit', [AdminUsersController::class, 'update'])->name('admin.adminUsers.update');
        Route::get('/{id}/delete', [AdminUsersController::class, 'delete'])->name('admin.adminUsers.delete');
    });

    Route::group(['prefix'=>'clients'], function(){
        Route::get('/',[ClientUsersController::class, 'index'])->name('admin.clientUsers');
        Route::get('/create',[ClientUsersController::class, 'create'])->name('admin.clientUsers.create');
        Route::post('/create',[ClientUsersController::class, 'store'])->name('admin.clientUsers.store');
        Route::get('/{id}/edit',[ClientUsersController::class, 'edit'])->name('admin.clientUsers.edit');
        Route::post('/{id}/edit',[ClientUsersController::class, 'update'])->name('admin.clientUsers.update');
        Route::get('/{id}/delete',[ClientUsersController::class, 'delete'])->name('admin.clientUsers.delete');
    });

    Route::group(['prefix'=>'roles'], function(){
        Route::get('/',[RolesController::class, 'index'])->name('admin.roles');
        Route::post('/create', [RolesController::class, 'store'])->name('admin.roles.store');
        Route::post('/{id}/edit', [RolesController::class, 'update'])->name('admin.roles.update');
        Route::get('/{id}/delete', [RolesController::class, 'delete'])->name('admin.roles.delete');
    });

    Route::group(['prefix'=>'pages'], function(){
        Route::get('/',[PagesController::class, 'index'])->name('admin.pages');
        Route::post('/create', [PagesController::class, 'store'])->name('admin.pages.store');
        Route::post('/{id}/edit', [PagesController::class, 'update'])->name('admin.pages.update');
        Route::get('/{id}/delete', [PagesController::class, 'delete'])->name('admin.pages.delete');
    });

    Route::group(['prefix'=>'contact-messages'], function(){
        Route::get('/',[ContactMessagesController::class, 'index'])->name('admin.contactmessages');
        Route::get('/{id}/details', [ContactMessagesController::class, 'details'])->name('admin.contactmessages.details');
        Route::get('/{id}/delete', [ContactMessagesController::class, 'delete'])->name('admin.contactmessages.delete');
    });

    Route::group(['prefix'=>'settings'], function(){
        Route::get('/',[SettingsController::class, 'generalSettings'])->name('admin.settings.general');
        Route::post('/',[SettingsController::class, 'updateSettings'])->name('admin.settings.update');
        Route::get('/{key}/deletePhoto', [SettingsController::class, 'deleteSettingPhoto'])->name('admin.settings.deletePhoto');
    });


    Route::group(['prefix'=>'orders'], function(){
        Route::get('/', [OrdersController::class, 'index'])->name('admin.orders');
        Route::post('/{order}', [OrdersController::class, 'editStatus'])->name('admin.orders.editStatus');
        Route::get('/{id}/details', [OrdersController::class, 'details'])->name('admin.orders.details');
    });


    Route::group(['prefix'=>'products'], function(){
        Route::get('/',[ProductsController::class, 'index'])->name('admin.products');
        Route::post('/create', [ProductsController::class, 'store'])->name('products.store');
        Route::post('/{product}/edit', [ProductsController::class, 'update'])->name('products.update');
        Route::post('/{product}/updateImages', [ProductsController::class, 'updateImages'])->name('products.updateImages');
        Route::get('/{product}/delete', [ProductsController::class, 'delete'])->name('products.delete');
    });

});
