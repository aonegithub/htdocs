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
use Request as RQ;

class PriceSetController extends Controller
{
    // 全部房價
    public function price($country, $hotel_id){
        
        // 取出飯店檔案
        $Hotel =Hotel::find(substr($hotel_id, 1));

        $binding =[
            'Title' => '全部房價',
            'Nav_ID' => 10,  //功能按鈕編號  
            'Hotel_ID' => $hotel_id,
            'Hotel' =>$Hotel,
            'Country' => $country,
        ];
        return view('hotel_auth.price',$binding);
    }

    // 一般房價
    public function price_normal($country, $hotel_id){
        
        // 取出飯店檔案
        $Hotel =Hotel::find(substr($hotel_id, 1));
        // 取出已設定房型
        $RoomList =HotelRoomSet::where('hotel_id', substr($hotel_id, 1))->get();
        $RoomSale =substr($RoomList[0]->sale_people, 0, -1);
        $RoomSaleArray =explode(',', $RoomSale);
        // dd($RoomArray);
        // exit;
        $binding =[
            'Title' => '全部房價',
            'Nav_ID' => 10,  //功能按鈕編號  
            'Hotel_ID' => $hotel_id,
            'Hotel' =>$Hotel,
            'RoomList' =>$RoomList,
            'RoomSaleArray' =>$RoomSaleArray,
            'Country' => $country,
        ];
        return view('hotel_auth.price_normal',$binding);
    }
}
