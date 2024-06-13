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

Route::group(['prefix' => 'ajtarragona/jobs','middleware' => ['web','language'],'as'=>'tgn-jobs.'	], function () {
    Route::get('/login', 'Ajtarragona\TJobs\Controllers\TJobsBackendController@login')->name('login');
    Route::post('/login', 'Ajtarragona\TJobs\Controllers\TJobsBackendController@dologin')->name('dologin');
    Route::get('/logout', 'Ajtarragona\TJobs\Controllers\TJobsBackendController@logout')->name('logout');

});

Route::group(['prefix' => 'ajtarragona/jobs','middleware' => ['web','language','reports-backend'],'as'=>'tgn-jobs.'	], function () {

    Route::get('/', 'Ajtarragona\Reports\Controllers\TJobsBackendController@home')->name('home');
    
});