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
		// 儀表板(最新消息)
		Route::get('main', 'Auth\ManagerController@main');
		// 管理員與權限管理
		// 清單
		Route::get('authority_list', 'Auth\AuthorityController@main');
		// 新增
		Route::get('authority_add', 'Auth\AuthorityController@add');
		Route::post('authority_add', 'Auth\AuthorityController@addAuth');
		// 編輯
		Route::get('authority_edit', 'Auth\AuthorityController@edit');
		Route::post('authority_edit', 'Auth\AuthorityController@editAuth');
		// 刪除
		Route::get('authority_del', 'Auth\AuthorityController@del');
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
