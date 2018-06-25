<?php

namespace App\Http\Controllers\HotelAuth;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Awugo\Auth\HotelManagers;
use App\Awugo\Auth\Hotel;
use App\Awugo\HotelAuth\HotelRoomSet;
use Image;
use View;
use DB;
use Validator;
use Debugbar;
use Carbon;
use File;

class RoomSetController extends Controller
{
	// 客房設定介面
    public function main($country, $hotel_id){

        // 取出飯店檔案
        $Hotel =Hotel::find(substr($hotel_id, 1));
        
        // print_r();
        // exit;
        $binding =[
            'Title' => '客房設定',
            'Nav_ID' => 10,  //功能按鈕編號  
            'Hotel_ID' => $hotel_id,
            'Hotel' =>$Hotel,
            'Country' => $country,
        ];
    	return view('hotel_auth.room_set',$binding);
    }

    
}
