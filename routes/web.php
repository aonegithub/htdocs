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

Route::get('/', 'IndexController@main');
// 總管理區路由
Route::group(['prefix'=>'auth'], function(){
    Route::get('login', 'ManagerController@login');
	Route::post('login', 'ManagerController@login_post');
	Route::get('logout', 'ManagerController@logout');
});


Route::get('/dt', function () {
    return date("Y-m-d H:i:s");
});
