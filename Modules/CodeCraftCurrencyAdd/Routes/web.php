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
use Modules\CodeCraftCurrencyAdd\Http\Controllers\CodeCraftCurrencyAddController;

Route::middleware(['web', 'SetSessionData', 'auth', 'language', 'timezone', 'AdminSidebarMenu'])
    ->prefix('codecraftcurrencyadd')
    ->group(function() {
        Route::get('/', [CodeCraftCurrencyAddController::class, 'index']);
        Route::get('/install', 'InstallController@index');
        Route::post('/install', 'InstallController@install');
        Route::get('/install/uninstall', 'InstallController@uninstall');
        Route::get('/install/update', 'InstallController@update');
        
        Route::get('/index', [CodeCraftCurrencyAddController::class, 'index'])->name('index');
        
        Route::get('/product-to-currency', [CodeCraftCurrencyAddController::class, 'getCurrency'])->name('getCurrency');
        Route::get('/currency-row/{currency_id}', [CodeCraftCurrencyAddController::class, 'currency_row'])->name('currency_row');
        Route::get('/create-currency', [CodeCraftCurrencyAddController::class, 'create_currency'])->name('create-currency');
        Route::post('/store-currency', [CodeCraftCurrencyAddController::class, 'store_currency'])->name('store-currency');
        
        Route::get('/taza_s', [CodeCraftCurrencyAddController::class, 'taza_s'])->name('taza_s');
        Route::get('/tazas', [CodeCraftCurrencyAddController::class, 'tazas'])->name('tazas');
        Route::get('/create-tazas', [CodeCraftCurrencyAddController::class, 'create_tazas'])->name('create-tazas');
        Route::post('/store-taza', [CodeCraftCurrencyAddController::class, 'store_tz'])->name('store-taza');
        
        
        Route::delete('/destroy/{id}', [CodeCraftCurrencyAddController::class, 'destroy'])->name('destroy');
        Route::delete('/destroy_taza/{id}', [CodeCraftCurrencyAddController::class, 'destroy_taza'])->name('destroy_taza');
        
    });
