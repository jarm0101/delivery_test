<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DeliveryController;
use App\Http\Controllers\Api\ProviderController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VehicleTypeController;
use App\Http\Controllers\Api\WorkerController;
use App\Models\Delivery;
use App\Models\Provider;
use App\Models\Worker;
use App\Models\Role;

// Email verification
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['signed'])->name('verification.verify');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Public routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Protected routes
Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::resource('/users', UserController::class)->except(['create', 'edit']);

    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('/roles', [RoleController::class, 'index'])->can('viewAny', Role::class);

    Route::group(['prefix' => 'workers'], function () {
        Route::get('/', [WorkerController::class, 'index'])->can('viewAny', Worker::class);
        Route::get('/{worker}', [WorkerController::class, 'show'])->can('view', 'worker');
        Route::post('/', [WorkerController::class, 'store'])->can('create', Worker::class);
        Route::delete('/{worker}', [WorkerController::class, 'destroy'])->can('delete', Worker::class);
    });

    Route::group(['prefix' => 'providers'], function () {
        Route::get('/', [ProviderController::class, 'index'])->can('viewAny', Provider::class);
        Route::get('/{provider}', [ProviderController::class, 'show'])->can('view', 'provider');
        Route::post('/', [ProviderController::class, 'store'])->can('create', Provider::class);
        Route::delete('/{provider}', [ProviderController::class, 'destroy'])->can('delete', Provider::class);
    });

    Route::group(['prefix' => 'deliveries'], function () {
        Route::get('/', [DeliveryController::class, 'index'])->can('viewAny', Delivery::class);
        Route::get('/{delivery}', [DeliveryController::class, 'show'])->can('view', 'delivery');
        Route::post('/', [DeliveryController::class, 'store']);
        Route::put('/{delivery}', [DeliveryController::class, 'update'])->can('update', 'delivery');
        Route::delete('/{delivery}', [DeliveryController::class, 'destroy'])->can('delete', 'delivery');
        Route::get('/export/pdf', [DeliveryController::class, 'exportPdf'])->can('export', Delivery::class);
    });

    Route::resource('/vehicles', VehicleTypeController::class)->except(['create', 'edit']);
});
