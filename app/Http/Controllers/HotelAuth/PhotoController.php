<?php

namespace App\Http\Controllers\HotelAuth;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use App\Awugo\Auth\HotelManagers;
use App\Awugo\Auth\Hotel;
use App\Awugo\Picture;
use Image;
use View;
use DB;
use Validator;
use Debugbar;
use Carbon;

class PhotoController extends Controller
{
	// 照片上傳介面
    public function main($country, $hotel_id){
        // exit;
        $Manager =HotelManagers::where('id',session()->get('hotel_manager_id'))->firstOrFail()->toArray();
        // 取出照片
        $Photos =Picture::where('hotel_list_id',substr(session()->get('hotel_id'),1))->get();
        // 取出飯店檔案
        $Hotel =Hotel::find(substr($hotel_id, 1));
        //切分帳號權限
        $auth_array =explode(',', session()->get('hotel_manager_auth'));
        $binding =[
            'Title' => '照片上傳',
            'Nav_ID' => 10,  //功能按鈕編號  
            'Manager' => $Manager,
            'Auths' => $auth_array,
            'Hotel_ID' => $hotel_id,
            'Country' => $country,
            'Hotel' =>$Hotel,
            'Photos' =>$Photos,
        ];
    	return view('hotel_auth.upload_photo', $binding);
    }
    //上傳照片平台
    public function plan($country, $hotel_id){
        app('debugbar')->disable();
        return view('hotel_auth.upload_photo_plan');
    }
    // 上傳照片POST
    public function mainPost($country, $hotel_id){
        
    }
}
