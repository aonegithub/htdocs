<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use App\Awugo\Auth\Authority;
use App\Awugo\Auth\Managers;
use Image;
use View;
use DB;
use Validator;

class AuthorityController extends Controller
{
// 權限管理員清單
    public function main(){
        $binding =[
            'Title' => '權限管理',
            'Nav_ID' => 29,  //功能按鈕編號  
        ];
        return view('auth.authority_list', $binding);
    }
// 權限管理員新增頁
    public function add(){
        //取上層權限
        $Authority_root =Authority::where('auth_parent','-1')->get();
        //取下層權限
        $Authority_sub =Authority::where('auth_parent','<>',"-1")->get();
        $binding =[
            'Title' => '權限管理',
            'Nav_ID' => 29,  //功能按鈕編號  
            'Auth_root' => $Authority_root,
            'Auth_sub' => $Authority_sub,
        ];
        return view('auth.authority_add', $binding);
    }
// 權限管理員新增POST
    public function addAuth(){
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
                'min:3',
            ],
            //部門
            'inputDepartment'=>[
                'required',
                'min:3',
            ],
        ];
        // 驗證修改資料
        $validator =Validator::make($request, $rules);
        //驗證失敗
        if($validator->fails()){
            return redirect('/auth/manager/authority_add')->withErrors($validator)->withInput();
        }
        $Manager = new Managers;

        $Manager->auth =implode(',',$request['auth_chk']);
        $Manager->id = $request['inputID'];
        $Manager->name = $request['inputUserID'];
        $Manager->passwd = Hash::make($request['exampleInputPassword1']);
        $Manager->department = $request['inputDepartment'];
        $mEnable=0;
        if(isset($request['enableAccount'])){
            $mEnable=1;
        }
        $Manager->enable = $mEnable;   
        $Manager->save();
        // exit;
        return redirect()->to('/auth/manager/authority_add')->with('controll_back_msg', 'ok');
    }
// 權限管理編輯頁
    public function edit(){
        // DB::enableQueryLog();
        //取上層權限
        $Authority_root =Authority::where('auth_parent','-1')->get();
        //取下層權限
        $Authority_sub =Authority::where('auth_parent','<>',"-1")->get();
        //取管理者權限資料
        $Manager =Managers::where('id',session()->get('manager_id'))->firstOrFail();
        $Manager_auth =explode(',', $Manager->auth);
        // var_dump(DB::getQueryLog());
        // print_r($Authority_sub);

        // exit;
        $binding =[
            'Title' => '權限管理',
            'Nav_ID' => 29,  //功能按鈕編號  
            'Auth_root' => $Authority_root,
            'Auth_sub' => $Authority_sub,
            'Manager_auth' => $Manager_auth,
            'Manager' => $Manager,
        ];
    	return view('auth.authority_edit', $binding);
    }
// 權限管理修改
    public function editAuth(){
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
                'min:3',
            ],
            //部門
            'inputDepartment'=>[
                'required',
                'min:3',
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
            return redirect('/auth/manager/authority_edit')->withErrors($validator)->withInput();
        }
        $Manager =Managers::where('nokey',session()->get('manager_nokey'))->firstOrFail();

        $Manager->auth =implode(',',$request['auth_chk']);
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
        $Manager->save();
        // exit;
        return redirect()->to('/auth/manager/authority_edit')->with('controll_back_msg', 'ok');
    }

// 權限管理員刪除
    public function del(){
        return "管理員刪除";
    }
}
