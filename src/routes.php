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

Route::group(['prefix' => 'ajtarragona/batches','middleware' => ['web','language'],'as'=>'tgn-batches.'	], function () {
    Route::get('/login', 'Ajtarragona\TBatches\Controllers\TBatchesBackendController@login')->name('login');
    Route::post('/login', 'Ajtarragona\TBatches\Controllers\TBatchesBackendController@dologin')->name('dologin');
    Route::get('/logout', 'Ajtarragona\TBatches\Controllers\TBatchesBackendController@logout')->name('logout');
    Route::get('/monitor/{batch}', 'Ajtarragona\TBatches\Controllers\TBatchesController@monitor')->name('monitor');

});

Route::group(['prefix' => 'ajtarragona/batches','middleware' => ['web','language','tbatches-backend'],'as'=>'tgn-batches.'	], function () {

    Route::get('/', 'Ajtarragona\TBatches\Controllers\TBatchesBackendController@home')->name('home');
    
});