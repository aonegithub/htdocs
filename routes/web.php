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
// app('debugbar')->disable();
Route::get('/', 'IndexController@main');
// 總管理區路由
// 中介層驗證登入資訊
Route::group(['prefix'=>'/{country}/auth/manager'], function(){
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
			Route::get('authority_edit/{managerkey}', 'Auth\AuthorityController@edit');
			Route::post('authority_edit/{managerkey}', 'Auth\AuthorityController@editAuth');
			// 啟動帳號與關閉
			Route::post('authority_enable/{managerkey}', 'Auth\AuthorityController@enable');
		//景點設定
		//地區設定
			Route::get('area_list', 'Auth\AreaController@main');
			Route::post('area_get', 'Auth\AreaController@getSubArea');  //取得子區域(ajax)
			Route::get('area_add', 'Auth\AreaController@add');
			Route::post('area_add', 'Auth\AreaController@addArea');
			Route::get('area_edit', 'Auth\AreaController@edit');
			Route::post('area_edit', 'Auth\AreaController@editArea');
			Route::post('area_edit/{areakey}', 'Auth\AreaController@editArea');
			Route::post('area_del', 'Auth\AreaController@delArea');
		//飯店管理
			Route::get('hotel_list', 'Auth\HotelController@main');
			Route::post('hotel_list/{search_str}', 'Auth\HotelController@search');
			Route::get('hotel_add', 'Auth\HotelController@add');
			Route::post('hotel_add', 'Auth\HotelController@addPost');
	});
});
Route::group(['prefix'=>'/{country}/auth'], function(){
	Route::post('login', 'SignController@login_post');
	Route::get('login', 'SignController@login')->name('login');
	Route::get('logout', 'SignController@logout');
});



Route::get('/dt', function () {
    return date("Y-m-d H:i:s");
});
