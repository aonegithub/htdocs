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
    public function login($country){
        // abort(404);
    	return view('auth/login');
    }
    // 登入處理
    public function login_post($country){
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
            return redirect('/'. $country .'/auth/login')->withErrors($validator)->withInput();
        }
        // 加密密碼用於比對
        // $input['inputPassword'] = Hash::make($input['inputPassword']);
        try {
          $Manager =Managers::where('id',$input['inputID'])->firstOrFail()->toArray();
            //判斷是否為管理者
            $Manager['if_manager'] =Hash::check($input['inputPassword'], $Manager['passwd']);
            if($Manager['if_manager'] && $Manager['enable']=='1'){
                // 更新最後登入時間
                $Manager_update =Managers::where('id',$input['inputID'])->firstOrFail();
                $Manager_update->updated_at =date ("Y-m-d H:i:s");
                $Manager_update->save();
                //寫入登入資訊
                session()->put('manager_id', $input['inputID']);
                session()->put('manager_nokey', $Manager['nokey']);
                session()->put('manager_name', $Manager['name']);
                session()->put('manager_auth', $Manager['auth']);
                session()->put('manager_country', $country);
            }else{
                throw (new ModelNotFoundException);
            }
        } catch (ModelNotFoundException $ex) {
            $validator =['無此帳號或帳號密碼錯誤'];
            return redirect('/'. $country .'/auth/login')->withErrors($validator)->withInput();
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
    	return redirect()->to('/'. session()->get('manager_country') .'/auth/manager/main');
    	// return var_dump(DB::getQueryLog());
    }
    // 登出口
    public function logout($country){
        session()->flush();
    	return redirect()->to('/'. $country .'/auth/manager/main');
    }
}
