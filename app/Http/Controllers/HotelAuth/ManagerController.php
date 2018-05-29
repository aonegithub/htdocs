<?php

namespace App\Http\Controllers\HotelAuth;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use App\Awugo\Auth\HotelManagers;
use App\Awugo\Auth\Hotel;
use App\Awugo\Auth\Areas;
use Image;
use View;
use DB;

class ManagerController extends Controller
{
	// 首頁儀錶板
    public function main($country, $hotel_id){
        // exit;
        $Manager =HotelManagers::where('id',session()->get('hotel_manager_id'))->firstOrFail()->toArray();
        // 取出飯店檔案
        $Hotel =Hotel::find(substr($hotel_id, 1));
        //二級清單
        $Areas_level2 =Areas::where('area_level','2')->where('area_code', '=', session()->get('manager_country'))->get(); //二級區域
        //帶入已選行政區域(飯店地址)
        $Addr_level3 =Areas::where('area_parent',$Hotel->area_level2)->where('area_code', '=', session()->get('manager_country'))->get(); //三級區域
        //切分帳號權限
        $auth_array =explode(',', session()->get('hotel_manager_auth'));
        $binding =[
            'Title' => '最新消息',
            'Nav_ID' => 10,  //功能按鈕編號  
            'Manager' => $Manager,
            'Auths' => $auth_array,
            'Hotel_ID' => $hotel_id,
            'Country' => $country,
            'Hotel' =>$Hotel,
            'Areas_level2' => $Areas_level2,
            'Addr_level3' => $Addr_level3,
            'Login_addr_level3' => $Addr_level3,
        ];
    	return view('hotel_auth.main', $binding);
    }
}
