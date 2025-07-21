<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Master\MasterPortCountryController;
use App\Http\Controllers\Master\MasterKapalController;
use App\Http\Controllers\Master\MasterItemController;
use App\Http\Controllers\Master\MasterLayoutController;
use App\Http\Controllers\GetDataController;
use App\Http\Controllers\Invoice\InvoiceController;


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');


Route::prefix('/userSystem')->controller(UserController::class)->group(function(){
    Route::get('/index', 'index')->name('user.index');
    Route::get('/indexData', 'indexData')->name('user.indexData');
    Route::post('/userData', 'userData')->name('user.data');
    Route::post('/userStore', 'userStore')->name('user.store');
    Route::post('/userDelete', 'userDelete')->name('user.delete');
});

Route::prefix('/master')->name('master.')->group(function() {
    Route::controller(MasterPortCountryController::class)->group(function() {
        Route::prefix('/port')->name('port.')->group(function(){
            Route::get('/index', 'indexPort')->name('index');
            Route::get('/data', 'dataPort')->name('data');
            Route::post('/edit', 'editPort')->name('edit');
            Route::post('/post', 'postPort')->name('post');
            Route::post('/delete', 'deletePort')->name('delete');
            Route::post('/postFile', 'postFilePort')->name('postFile');
        });

        Route::prefix('/country')->name('country.')->group(function(){
            Route::get('/index', 'indexCountry')->name('index');
            Route::get('/data', 'dataCountry')->name('data');
            Route::post('/edit', 'editCountry')->name('edit');
            Route::post('/post', 'postCountry')->name('post');
        });
    });

    Route::prefix('/vessel')->name('vessel.')->controller(MasterKapalController::class)->group(function() {
        Route::get('/index', 'index')->name('index');
        Route::get('/data', 'data')->name('data');
        Route::post('/edit', 'edit')->name('edit');
        Route::post('/post', 'post')->name('post');
        Route::post('/file', 'file')->name('file');
        Route::post('/delete', 'delete')->name('delete');
    });

    Route::controller(MasterItemController::class)->group(function() {
        Route::prefix('/item')->name('item.')->group(function () {
            Route::get('/index', 'indexItem')->name('index');
            Route::get('/data', 'dataItem')->name('data');
            Route::post('/post', 'postItem')->name('post');
            Route::post('/delete', 'deleteItem')->name('delete');
            Route::post('/edit', 'editItem')->name('edit');
        });
        Route::prefix('/variable')->name('variable.')->group(function() {
            Route::post('/post', 'postVariable')->name('post');
            Route::get('/get', 'getVariable')->name('get');
        });
    });

    Route::prefix('/layout')->name('layout.')->controller(MasterLayoutController::class)->group(function() {
        Route::get('/index', 'index')->name('index');
        Route::post('/delete', 'delete')->name('delete');
        Route::post('/create', 'create')->name('create');

        Route::prefix('/detil')->group(function() {
            Route::get('/index/{id?}', 'indexDetil')->name('indexDetil');
            Route::get('/profile/{id?}', 'profileDetil')->name('profileDetil');
            Route::get('/content/{id?}', 'contentDetil')->name('contentDetil');
            Route::post('/profile/udpate', 'profileUpdate')->name('profileUpdate');
            Route::post('/postLayoutMain', 'postLayoutMain')->name('postLayoutMain');
            Route::post('/editLayoutMain', 'editLayoutMain')->name('editLayoutMain');
            Route::post('/deleteLayoutMain', 'deleteLayoutMain')->name('deleteLayoutMain');
            Route::post('/updateMainOrder', 'updateMainOrder')->name('updateMainOrder');
            Route::post('/updateDetilOrder', 'updateDetilOrder')->name('updateDetilOrder');
            Route::post('/submitItem', 'submitItem')->name('submitItem');
            Route::post('/deleteItem', 'deleteItem')->name('deleteItem');
            Route::post('/updateDetilAmount', 'updateDetilAmount')->name('updateDetilAmount');
        });
    });

});

Route::prefix('/invoice')->name('invoice.')->group(function() {
    Route::controller(InvoiceController::class)->group(function() {
        Route::get('/index', 'index')->name('index');
        Route::get('/dataTable', 'dataTable')->name('dataTable');
        Route::post('/create', 'createFirst')->name('create');
        Route::post('/cancel', 'cancelFirst')->name('cancel');
        Route::post('/reactive', 'reactiveFirst')->name('reactive');

        Route::prefix('/form/{id}')->name('form.')->group(function() {
            Route::get('/index', 'formIndex')->name('index');
            Route::post('/submit', 'formSubmit')->name('submit');
        });

        Route::prefix('/print/{id?}/')->name('print.')->group(function() {
            Route::get('/pdf', 'printPDF')->name('pdf');
        });
    });
}); 

Route::prefix('/getData')->name('getData.')->controller(GetDataController::class)->group(function() {
    Route::post('/mitem', 'getItem')->name('masterItem');
    Route::post('/master/layout/item', 'getLayoutItem');

    Route::post('/getVessel', 'getVessel')->name('vessel');
});
