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
if(strrpos($_SERVER['HTTP_HOST'], "awugo.com")){
	app('debugbar')->disable();
}
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
			Route::post('area_get_zipcode', 'Auth\AreaController@getZipCode');  //取得郵遞區號(ajax)
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
			Route::get('hotel_edit/{hotelkey}', 'Auth\HotelController@edit');
			Route::post('hotel_edit/{hotelkey}', 'Auth\HotelController@editPost');
			Route::post('hotel_disable/{hotelkey}', 'Auth\HotelController@disableAjax'); //異步關閉(ajax)
			Route::get('hotel_browse/{hotelkey}', 'Auth\HotelController@browse');
			Route::post('hotel_comm_add/{hotelkey}', 'Auth\HotelController@addCommAjax'); //異步寫入備註(協調內容)(ajax)

		//飯店管理/內部人員權限管理
			Route::get('hotel_auth_list/{hotel_key}', 'Auth\HotelAuthController@main');
			Route::get('hotel_auth_add/{hotel_key}', 'Auth\HotelAuthController@add');
			Route::post('hotel_auth_add/{hotel_key}', 'Auth\HotelAuthController@addPost');
			Route::get('hotel_auth_edit/{hotel_key}/{mem_key}', 'Auth\HotelAuthController@edit'); 
			Route::post('hotel_auth_edit/{hotel_key}/{mem_key}', 'Auth\HotelAuthController@editPost'); 
			Route::post('hotel_auth_del/{hotel_key}/{mem_key}', 'Auth\HotelAuthController@delPost'); 

		//設施與服務
			Route::get('service', 'Auth\ServiceController@main');
			Route::post('service_add', 'Auth\ServiceController@addPost'); 	//ajax
			Route::post('service_edit', 'Auth\ServiceController@editPost'); //ajax
			Route::post('service_del', 'Auth\ServiceController@delPost'); 	//ajax

		//客房設施
			Route::get('room_installation', 'Auth\RoomInstallationController@main');
			Route::post('room_installation_add', 'Auth\RoomInstallationController@addPost'); 	//ajax
			Route::post('room_installation_edit', 'Auth\RoomInstallationController@editPost'); //ajax
			Route::post('room_installation_del', 'Auth\RoomInstallationController@delPost'); 	//ajax
		//房型名稱
			Route::get('room_name', 'Auth\RoomNameController@main');
			Route::post('room_name_add', 'Auth\RoomNameController@addPost'); 	//ajax
			Route::post('room_name_edit', 'Auth\RoomNameController@editPost'); //ajax
			Route::post('room_name_del', 'Auth\RoomNameController@delPost'); 	//ajax
		//床型名稱
			Route::get('bed_name', 'Auth\BedNameController@main');
			Route::post('bed_name_add', 'Auth\BedNameController@addPost'); 	//ajax
			Route::post('bed_name_edit', 'Auth\BedNameController@editPost'); //ajax
			Route::post('bed_name_del', 'Auth\BedNameController@delPost'); 	//ajax

	});
});
Route::group(['prefix'=>'/{country}/auth'], function(){
	Route::post('login', 'SignController@login_post');
	Route::get('login', 'SignController@login')->name('login');
	Route::get('logout', 'SignController@logout');
});

//業者後台
Route::group(['prefix'=>'/{country}/auth'], function(){
	//防空門訪問
	Route::get('/', function(){
		return view('errors.404');
	});
	//登入塊
		Route::get('{hotel_id}/', 'HotelAuth\SignController@login');
		Route::post('{hotel_id}/', 'HotelAuth\SignController@login_post');
		// Route::post('{hotel_id}/login', 'HotelAuth\SignController@login_post');
		Route::get('{hotel_id}/logout', 'HotelAuth\SignController@logout');
	//業者後台主route
	Route::group(['middleware'=>'auth.hotel.login'], function(){

	// 飯店管理(基本資料)
		Route::get('{hotel_id}/main', 'HotelAuth\ManagerController@main');
		Route::post('{hotel_id}/main', 'HotelAuth\ManagerController@mainPost');
	// 照片上傳
		Route::get('{hotel_id}/photos', 'HotelAuth\PhotoController@main');
		Route::get('{hotel_id}/photos_plan', 'HotelAuth\PhotoController@plan');
		Route::post('{hotel_id}/photos', 'HotelAuth\PhotoController@mainPost');
		Route::post('{hotel_id}/photos_del', 'HotelAuth\PhotoController@delPic');
		Route::post('{hotel_id}/photos_edit', 'HotelAuth\PhotoController@editPic');
	});
});

//API
Route::group(['prefix'=>'/{country}/api'], function(){
	//取得地區層級1(如國家)
	Route::post('getArea1', 'Api\AreaApiController@getArea1');		
	//取得地區層級2(如縣市)	
	Route::post('getArea2/{in_country}', 'Api\AreaApiController@getArea2');	
	//取得地區層級3(如區域)
	Route::post('getArea3/{parent}', 'Api\AreaApiController@getArea3');	
	//取得地區層級4(區域蛋黃區)		
	Route::post('getArea4/{parent}', 'Api\AreaApiController@getArea4');		
	//取得郵遞區號		
	Route::post('getZipCode/{area_key}', 'Api\AreaApiController@getZipCode');		

////////////////////////////////////////////////////////////////////////////////////////

	//上傳圖片接收(POST)
	Route::get('up', 'Api\UploadApiController@imageUploadView');
	Route::post('image', 'Api\UploadApiController@imageUpload');
	//上傳檔案接收(POST)
	Route::post('file', 'Api\UploadApiController@fileUpload');
});


Route::get('/dt', function () {
    return date("YmdHis").'_512_'.explode(' ', microtime())[0]*100000000;
});
