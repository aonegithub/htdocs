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
// 中介層驗證登入資訊
Route::group(['prefix'=>'auth/manager'], function(){
	Route::group(['middleware'=>'auth.manager.login'], function(){
		Route::get('main', 'Auth\ManagerController@main');
	});
});
Route::group(['prefix'=>'auth'], function(){
	Route::post('login', 'SignController@login_post');
	Route::get('login', 'SignController@login');
	Route::get('logout', 'SignController@logout');
});



Route::get('/dt', function () {
    return date("Y-m-d H:i:s");
});
