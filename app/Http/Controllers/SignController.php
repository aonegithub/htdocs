<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use App\Awugo\Auth\Managers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Image;
use View;
use DB;
use Validator;

class SignController extends Controller
{
	// 登入口
    public function login(){
    	return view('auth/login');
    }
    // 登入處理
    public function login_post(){
        // DB::enableQueryLog();
    	$input = request()->all();
        // 驗證規則
        $rules =[
            //帳號
            'inputID'=>[
                'required',
                'min:3',
            ],
            //密碼
            'inputPassword'=>[
                'required',
                'min:3',
            ],
        ];
        // 驗證登入資料
        $validator =Validator::make($input, $rules);
        //驗證失敗
        if($validator->fails()){
            return redirect('/auth/login')->withErrors($validator)->withInput();
        }
        // 加密密碼用於比對
        // $input['inputPassword'] = Hash::make($input['inputPassword']);
        try {
          $Manager =Managers::where('id',$input['inputID'])->firstOrFail()->toArray();
            //判斷是否為管理者
            $Manager['if_manager'] =Hash::check($input['inputPassword'], $Manager['passwd']);
            if($Manager['if_manager']){
                session()->put('manager_id', $input['inputID']);
                session()->put('manager_name', $Manager['name']);
            }else{
                throw (new ModelNotFoundException);
            }
        } catch (ModelNotFoundException $ex) {
            $validator =['無此帳號或帳號密碼錯誤'];
            return redirect('/auth/login')->withErrors($validator)->withInput();;
        }
        
        //session()->flush();
        // 比對加密密碼
        //echo Hash::make($input['inputPassword']);
        // var_dump($Manager);
        // exit;
        //var_dump(DB::getQueryLog());
        // $binding =[
        //     'Title' => '主頁',
        //     'Nav_ID' => 4,  //功能按鈕編號  
        //     'Manager' => $Manager,
        // ];
    	return redirect()->to('/auth/manager/main');
    	// return var_dump(DB::getQueryLog());
    }
    // 登出口
    public function logout(){
        session()->flush();
    	return redirect()->to('/auth/manager/main');
    }
}
