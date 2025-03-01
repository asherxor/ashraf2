<?php

use Modules\CodeCraftPlusPOS\Http\Controllers\CodeCraftPlusPOSController;
use Modules\CodeCraftPlusPOS\Http\Controllers\SellPosController;
use Modules\CodeCraftPlusPOS\Http\Controllers\HomeController;

//Route::get('/home', [HomeController::class, 'index'])->name('home');


Route::middleware(['web', 'SetSessionData', 'auth', 'language', 'timezone', 'AdminSidebarMenu'])
    ->prefix('codecraftpluspos')
    ->group(function() {
        Route::get('/', [CodeCraftPlusPOSController::class, 'index']);
        Route::get('/install', 'InstallController@index');
        Route::post('/install', 'InstallController@install');
        Route::get('/install/uninstall', 'InstallController@uninstall');
        Route::get('/install/update', 'InstallController@update');
        
        Route::get('/index', [CodeCraftPlusPOSController::class, 'index'])->name('index');
        Route::get('/create', [CodeCraftPlusPOSController::class, 'create'])->name('create');
        Route::get('/store', [CodeCraftPlusPOSController::class, 'store'])->name('store');
        
        Route::resource('pos', SellPosController::class);
        Route::get('/sellpos/create_register', [SellPosController::class, 'create_register'])->name('sellpos.create_register');
        Route::post('/sellpos/store_register', [SellPosController::class, 'store_register'])->name('sellpos.store_register');
        
    
    });