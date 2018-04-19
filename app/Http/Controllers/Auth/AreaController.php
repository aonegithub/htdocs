<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use App\Awugo\Auth\Authority;
use App\Awugo\Auth\Managers;
use App\Awugo\Auth\Areas;
use Image;
use View;
use DB;
use Validator;

class AreaController extends Controller
{
    private $menu_item_code =14;
    private $menu_item_text ='景點地區設定';
    // private $auth_array =explode(',', session()->get('manager_auth'));
// 地區管理清單
    public function main(){
        //切分帳號權限
        $auth_array =explode(',', session()->get('manager_auth'));
        //管理者基本資訊
        $Manager =Managers::where('id',session()->get('manager_id'))->firstOrFail()->toArray();
        //地區清單
        $Area_country =Areas::where('area_parent','0')->get(); //國家
        $Area_parent =Areas::where('area_parent','-1')->get(); //縣市區
        $Area_level =Areas::where('area_parent','>','0')->get();  //地方區

        $binding =[
            'Title' => $this->menu_item_text,
            'Nav_ID' => $this->menu_item_code,  //功能按鈕編號  
            'Manager' => $Manager,
            'Auths' => $auth_array,
            'Area_country' => $Area_country,
            'Area_parent' => $Area_parent,
            'Area_level' => $Area_level,
        ];
        return view('auth.area_list', $binding);
    }
// 地區新增介面
    public function add(){
        $auth_key ='40'; //新增管理員權限碼
        //var_dump($auth_array);
        $auth_array =explode(',', session()->get('manager_auth'));
        if(!in_array($auth_key,$auth_array)){
            $errors =['權限不足返回'];
            $Manager =Managers::where('id',session()->get('manager_id'))->firstOrFail()->toArray();
            $binding =[
                'Title' => $this->menu_item_text,
                'Nav_ID' => $this->menu_item_code,  //功能按鈕編號  
                'Manager' => $Manager,
            ];
            return redirect('/auth/manager/area_list')->withErrors($errors)->withInput();
            //exit;
        }
        
        return "新增地區";
    }
// 地區新增POST
    public function addArea(){
        // DB::enableQueryLog();

        // exit;
        return "新增地區POST";
    }
// 地區修改介面
    public function edit($area_nokey){
        // DB::enableQueryLog();
        $auth_key ='41'; //飯店瀏覽權限碼
        //var_dump($auth_array);
        $auth_array =explode(',', session()->get('manager_auth'));
        if(!in_array($auth_key,$auth_array)){
            $errors =['權限不足返回'];
            $Manager =Managers::where('id',session()->get('manager_id'))->firstOrFail()->toArray();
            $binding =[
                'Title' => $this->menu_item_text,
                'Nav_ID' => $this->menu_item_code,  //功能按鈕編號  
                'Manager' => $Manager,
            ];
            return redirect('/auth/manager/area_list')->withErrors($errors)->withInput();
            //exit;
        }
        
    	return "地區編輯";
    }
// 地區修改POST
    public function editArea($area_nokey){
        // DB::enableQueryLog();
        
        // exit;
        return "地區編輯POST";
    }

// (清單)刪除地區 Ajax
    public function delArea($area_key){
        $auth_key ='42'; //刪除權限碼

        return "地區刪除";
    }
}
