<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Awugo\Auth\Authority;
use App\Awugo\Auth\Managers;
use App\Awugo\Auth\Hotel;
use App\Awugo\Auth\HotelAuthority;
use App\Awugo\Auth\HotelManagers;
use Carbon\Carbon;
// use Illuminate\Http\Request;
use Image;
use View;
use DB;
use Validator;

class HotelAuthController extends Controller
{
    private $menu_item_code =1;
    private $menu_item_text ='飯店人員權限管理';
    /**
     * 飯店權限清單.
     */
    public function main(Request $request,$country,$hotel_key)
    {
        //取得飯店管理人員清單
        $manager_list =HotelManagers::where('hotel_list_id',$hotel_key)->paginate(30);
        //取得飯店基本資料
        $hotel_profile =Hotel::find($hotel_key);
        //
        //讀取管理者資訊
        $Manager =Managers::where('id',session()->get('manager_id'))->firstOrFail()->toArray();
        $auth_array =explode(',', session()->get('manager_auth'));
        $binding =[
            'Title' => $this->menu_item_text,
            'Nav_ID' => $this->menu_item_code,  //功能按鈕編號  
            'Manager' => $Manager,
            'Managers' => $manager_list,
            'Auths' => $auth_array,
            'Country' => $country,
            'Hotel' => $hotel_profile,
        ];
        return view('auth.hotel_manager_list', $binding);
    }
    //新增管理員
    public function add(Request $request,$country,$hotel_key){
        //取得飯店管理人員清單
        $manager_list =HotelManagers::where('hotel_list_id',$hotel_key)->paginate(30);
        //取得飯店基本資料
        $hotel_profile =Hotel::find($hotel_key);
        //
        //取上層權限
        $Authority_root =HotelAuthority::where('auth_parent','-1')->get();
        //取下層權限
        $Authority_sub =HotelAuthority::where('auth_parent','<>',"-1")->get();
        //讀取管理者資訊
        $Manager =Managers::where('id',session()->get('manager_id'))->firstOrFail()->toArray();
        $auth_array =explode(',', session()->get('manager_auth'));
        $binding =[
            'Title' => $this->menu_item_text,
            'Nav_ID' => $this->menu_item_code,  //功能按鈕編號  
            'Manager' => $Manager,
            'Managers' => $manager_list,
            'Auths' => $auth_array,
            'Country' => $country,
            'Hotel' => $hotel_profile,
            'Auth_root' => $Authority_root,
            'Auth_sub' => $Authority_sub,
        ];
        return view('auth.hotel_manager_add', $binding);
    }
    //編輯管理員
    public function edit(Request $request,$country,$hotel_key,$mem_key){
        return 'editPage';
    }
}
