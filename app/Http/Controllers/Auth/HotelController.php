<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use App\Awugo\Auth\Authority;
use App\Awugo\Auth\Managers;
use App\Awugo\Auth\Hotel;
use App\Awugo\Auth\Areas;
use Image;
use View;
use DB;
use Validator;

class HotelController extends Controller
{
    private $menu_item_code =1;
    private $menu_item_text ='飯店管理';
    // private $auth_array =explode(',', session()->get('manager_auth'));
// 飯店管理預設清單
    public function main($country){
        //讀取管理者資訊
        $Manager =Managers::where('id',session()->get('manager_id'))->firstOrFail()->toArray();
        $auth_array =explode(',', session()->get('manager_auth'));
        $binding =[
            'Title' => $this->menu_item_text,
            'Nav_ID' => $this->menu_item_code,  //功能按鈕編號  
            'Manager' => $Manager,
            'Auths' => $auth_array,
            'Country' => $country,
        ];
        return view('auth.hotel_list', $binding);
    }
// 飯店管理新增介面View
    public function add($country){
        $auth_key ='2'; //新增管理員權限碼
        //讀取管理者資訊
        $Manager =Managers::where('id',session()->get('manager_id'))->firstOrFail()->toArray();
        $auth_array =explode(',', session()->get('manager_auth'));
        if(!in_array($auth_key,$auth_array)){
            $errors =['權限不足返回'];
            $Manager =Managers::where('id',session()->get('manager_id'))->firstOrFail()->toArray();
            $binding =[
                'Title' => $this->menu_item_text,
                'Nav_ID' => $this->menu_item_code,  //功能按鈕編號  
                'Manager' => $Manager,
                'Country' => $country,
            ];
            return redirect('/'. $country .'/auth/manager/hotel_list')->withErrors($errors)->withInput();
            //exit;
        }
        //帶入縣市
        //二級清單
        $Areas_level2 =Areas::where('area_level','2')->where('area_code', '=', session()->get('manager_country'))->get(); //二級區域

        $binding =[
            'Title' => $this->menu_item_text,
            'Nav_ID' => $this->menu_item_code,  //功能按鈕編號  
            'Manager' => $Manager,
            'Auths' => $auth_array,
            'Country' => $country,
            'Areas_level2' => $Areas_level2,
        ];
        return view('auth.hotel_add', $binding);
    }
// 飯店新增POST
    public function addPost(){
        return "ok";
    }

}
