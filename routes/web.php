<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\BillController;
use App\Http\Controllers\API\InvestmentController;
use App\Http\Controllers\AuthController;

//routes view one
Route::get('/', function () {
    return view('login');
})->name('login');
Route::get('/register', function () {
    return view('register');
})->name('register');
//route login functions 

Route::post('login', [AuthController::class, 'login']);
Route::post('users', [UserController::class, 'store']);
Route::group(['middleware' => 'auth'], function () {
    //route view two
    Route::get('/usuarios', function () {
        return view('users.index');
    })->middleware('permission:seguridad');
    Route::get('/roles', function () {
        return view('roles.index');
    })->middleware('permission:seguridad');;
    Route::get('/permisos', function () {
        return view('permissions.index');
    })->middleware('permission:seguridad');;
    Route::get('/perfil', function () {
        return view('users.profile');
    });

    Route::get('/bills', function () {
        return view('bills.index');
    });

    Route::get('/investments', function () {
        return view('investments.index');
    });


    Route::prefix('api')->group(function () {
        Route::group(['middleware' => ['getAuth']], function () {

            //routes app fuctions
            //routes users
            Route::get('/users', [UserController::class, 'index']);
            Route::get('/user', [UserController::class, 'index_login']);
            Route::post('users', [UserController::class, 'store']);
            Route::put('/user/password/{id}', [UserController::class, 'updatePassword'])->where('id', '[0-9]+');
            Route::put('/users/{id}', [UserController::class, 'update']);
            Route::put('/users/available/{id}', [UserController::class, 'available']);
            Route::put('/users/locked/{id}', [UserController::class, 'locked']);
            Route::post('logout', [AuthController::class, 'logout'])->name('logout');
            //end
            //roles
            Route::get('/roles', [RoleController::class, 'index']);
            Route::post('roles', [RoleController::class, 'store']);
            Route::put('/roles/{id}', [RoleController::class, 'update']);
            Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
            //end
            //permissions
            Route::get('/permissions', [PermissionController::class, 'index']);
            Route::post('permissions', [PermissionController::class, 'store']);
            Route::put('/permissions/{id}', [PermissionController::class, 'update']);
            Route::delete('/permissions/{id}', [PermissionController::class, 'destroy']);

            //bills
            Route::post('bills', [BillController::class, 'store']);
            Route::get('/bills/{date}/{date2}', [BillController::class, 'index']);
            Route::get('/billsTot/{date}/{date2}', [BillController::class, 'sumTot']);
            Route::put('/bills/{id}', [BillController::class, 'update']);
            Route::delete('/bills/{id}', [BillController::class, 'destroy'])->where('id', '[0-9]+');
            //investments
            Route::post('investments', [InvestmentController::class, 'store']);
            Route::get('/investments/{date}/{date2}', [InvestmentController::class, 'index']);
            Route::get('/investmentsTot/{date}/{date2}', [InvestmentController::class, 'sumTot']);
            Route::put('/investments/{id}', [InvestmentController::class, 'update']);
            Route::delete('/investments/{id}', [InvestmentController::class, 'destroy'])->where('id', '[0-9]+');
        });
    });
});
