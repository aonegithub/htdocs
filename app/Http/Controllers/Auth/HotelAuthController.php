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
use Request as UseRequest;
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
// 權限管理員新增POST
    public function addPost(Request $request,$country,$hotel_key){
        // DB::enableQueryLog();

        $request =request()->all();
        //修改規則驗證
        $rules =[
            //登入帳號
            'inputID'=>[
                'required',
                'min:3',
            ],
            //密碼
            'exampleInputPassword1'=>[
                'required',
                'same:exampleInputPassword2',
                'min:3',
            ],
            //使用人
            'inputUserID'=>[
                'required',
                'min:2',
            ],
            //部門
            'inputDepartment'=>[
                'required',
                'min:2',
            ],
        ];
        // 驗證修改資料
        $validator =Validator::make($request, $rules);
        //驗證失敗
        if($validator->fails()){
            return redirect('/'. $country .'/auth/manager/hotel_auth_add/'.$hotel_key)->withErrors($validator)->withInput();
        }

        $Manager = new HotelManagers;

        //判斷權限都沒勾選的狀態下給予空值，避免判斷失常
        if(!isset($request['auth_chk'])){
            $request['auth_chk'] ="";
            $Manager->auth =$request['auth_chk'];
        }else{
            $Manager->auth =implode(',',$request['auth_chk']);
        }
        $Manager->id = $request['inputID'];
        $Manager->name = $request['inputUserID'];
        $Manager->passwd = Hash::make($request['exampleInputPassword1']);
        $Manager->department = $request['inputDepartment'];
        $mEnable=0;
        if(isset($request['enableAccount'])){
            $mEnable=1;
        }
        $Manager->enable = $mEnable;   
        $Manager->hotel_list_id=$hotel_key;
        $Manager->ip=UseRequest::ip();
        $Manager->created_id=session()->get('manager_id');
        $Manager->created_name=session()->get('manager_name').'/awugo';

        $Manager->save();
        // exit;
        return redirect()->to('/'. $country .'/auth/manager/hotel_auth_list/'.$hotel_key)->with('controll_back_msg', 'ok');
    }
    //編輯管理員
    public function edit(Request $request,$country,$hotel_key,$mem_key){
        // DB::enableQueryLog();
        //取得管理員權限總索引
        $h_auth =HotelManagers::find($mem_key)->get(['nokey'])->toArray();
        $auth_string='';
        //因為產生無法Array to String的例外，改用手動組拚
        foreach($h_auth as $auth){
            $auth_string .=$auth['nokey'].',';
        }
        $auth_string =substr($auth_string,0,-1);                     //去除最後的逗號(手動組拚的缺陷)
        $auth_array =explode(',', session()->get('manager_auth'));
        //取上層權限
        $Authority_root =HotelAuthority::where('auth_parent','-1')->get();
        //取下層權限
        $Authority_sub =HotelAuthority::where('auth_parent','<>',"-1")->get();
        //取管理者權限資料
        $Manager =HotelManagers::where('nokey',$mem_key)->firstOrFail();
        $Manager_auth =explode(',', $Manager->auth);
        // var_dump(DB::getQueryLog());
        // print_r($Authority_sub);

        // exit;
        $binding =[
            'Title' => $this->menu_item_text,
            'Nav_ID' => $this->menu_item_code,  //功能按鈕編號  
            'Auth_root' => $Authority_root,
            'Auth_sub' => $Authority_sub,
            'Manager_auth' => $Manager_auth,
            'Manager' => $Manager,
            'Auths' => $auth_array,
            'Country' => $country,
        ];
        return view('auth.hotel_manager_edit', $binding);
    }
    //編輯管理員  POST
    public function editPost(Request $request,$country,$hotel_key,$mem_key){
        // DB::enableQueryLog();
        $request =request()->all();
        //修改規則驗證
        $rules =[
            //密碼
            'exampleInputPassword1'=>[
                'required',
                'same:exampleInputPassword2',
                'min:3',
            ],
            //使用人
            'inputUserID'=>[
                'required',
                'min:2',
            ],
            //部門
            'inputDepartment'=>[
                'required',
                'min:2',
            ],
        ];
        // 無勾選密碼則不驗證密碼
        if(!isset($request['editPW'])){
            unset($rules['exampleInputPassword1']);
        }
        // 驗證修改資料
        $validator =Validator::make($request, $rules);
        //驗證失敗
        if($validator->fails()){
            return redirect('/'. $country .'/auth/manager/hotel_manager_edit/'.$mem_key)->withErrors($validator)->withInput();
        }
        $Manager =HotelManagers::where('nokey',$mem_key)->firstOrFail();
        //判斷權限都沒勾選的狀態下給予空值，避免判斷失常
        if(!isset($request['auth_chk'])){
            $request['auth_chk'] ="";
            $Manager->auth =$request['auth_chk'];
        }else{
            $Manager->auth =implode(',',$request['auth_chk']);
        }
        // 勾選修改密碼才動密碼
        if(isset($request['editPW'])){
            $Manager->passwd = Hash::make($request['exampleInputPassword1']);
        }
        // $Manager->id = $request['inputID'];
        $Manager->name = $request['inputUserID'];
        $Manager->department = $request['inputDepartment'];
        $mEnable=0;
        if(isset($request['enableAccount'])){
            $mEnable=1;
        }
        $Manager->enable = $mEnable;   
        $Manager->ip=UseRequest::ip();
        $Manager->created_id=session()->get('manager_id');
        $Manager->created_name=session()->get('manager_name').'/awugo';
        $Manager->save();
        // exit;
        return redirect()->to('/'. $country .'/auth/manager/hotel_auth_list/'.$hotel_key)->with('controll_back_msg', 'ok');
    }
}
