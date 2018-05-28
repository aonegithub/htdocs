<?php

namespace App\Http\Controllers\HotelAuth;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use App\Awugo\Auth\HotelManagers;
use Image;
use View;
use DB;

class ManagerController extends Controller
{
	// 首頁儀錶板
    public function main($country, $hotel_id){
        // exit;
        $Manager =HotelManagers::where('id',session()->get('hotel_manager_id'))->firstOrFail()->toArray();
        //切分帳號權限
        $auth_array =explode(',', session()->get('hotel_manager_auth'));
        $binding =[
            'Title' => '最新消息',
            'Nav_ID' => 10,  //功能按鈕編號  
            'Manager' => $Manager,
            'Auths' => $auth_array,
            'Hotel_ID' => $hotel_id,
            'Country' => $country,
        ];
    	return view('hotel_auth.main', $binding);
    }
}
