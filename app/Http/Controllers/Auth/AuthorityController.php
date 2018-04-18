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
    private $menu_item_code =29;
    private $menu_item_text ='權限管理';
    // private $auth_array =explode(',', session()->get('manager_auth'));
// 權限管理員清單
    public function main(){
        $auth_key ='33'; //管理員瀏覽權限碼
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
            return redirect('/auth/manager/main')->withErrors($errors)->withInput();
            //exit;
        }
        // 每頁筆數
        $page_row =2;
        $Manager_pagerow =Managers::OrderBy('enable','desc')->OrderBy('nokey','asc')->paginate($page_row);

        $binding =[
            'Title' => $this->menu_item_text,
            'Nav_ID' => $this->menu_item_code,  //功能按鈕編號  
            'Managers' => $Manager_pagerow,
            'Auths' => $auth_array,
        ];
        return view('auth.authority_list', $binding);
    }
// 權限管理員新增頁
    public function add(){
        $auth_key ='34'; //新增管理員權限碼
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
            return redirect('/auth/manager/authority_list')->withErrors($errors)->withInput();
            //exit;
        }
        //取上層權限
        $Authority_root =Authority::where('auth_parent','-1')->get();
        //取下層權限
        $Authority_sub =Authority::where('auth_parent','<>',"-1")->get();
        $binding =[
            'Title' => $this->menu_item_text,
            'Nav_ID' => $this->menu_item_code,  //功能按鈕編號 
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

        $Manager->save();
        // exit;
        return redirect()->to('/auth/manager/authority_add')->with('controll_back_msg', 'ok');
    }
// 權限管理編輯頁
    public function edit($manager_nokey){
        // DB::enableQueryLog();
        $auth_key ='35'; //飯店瀏覽權限碼
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
            return redirect('/auth/manager/authority_list')->withErrors($errors)->withInput();
            //exit;
        }
        //取上層權限
        $Authority_root =Authority::where('auth_parent','-1')->get();
        //取下層權限
        $Authority_sub =Authority::where('auth_parent','<>',"-1")->get();
        //取管理者權限資料
        $Manager =Managers::where('nokey',$manager_nokey)->firstOrFail();
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
        ];
    	return view('auth.authority_edit', $binding);
    }
// 權限管理修改
    public function editAuth($manager_nokey){
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
            return redirect('/auth/manager/authority_edit/$manager_nokey')->withErrors($validator)->withInput();
        }
        $Manager =Managers::where('nokey',$manager_nokey)->firstOrFail();
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
        $Manager->save();
        // exit;
        return redirect()->to('/auth/manager/authority_edit/'.$manager_nokey)->with('controll_back_msg', 'ok');
    }

// (清單)權限管理員啟動管理 Ajax
    public function enable($manager_key){
        $auth_key ='35'; //管理員編輯權限碼
        //var_dump($auth_array);
        // $auth_array =explode(',', session()->get('manager_auth'));
        // if(!in_array($auth_key,$auth_array)){
        //     $errors =['權限不足返回'];
        //     $Manager =Managers::where('id',session()->get('manager_id'))->firstOrFail()->toArray();
        //     $binding =[
        //         'Title' => $this->menu_item_text,
        //         'Nav_ID' => $this->menu_item_code,  //功能按鈕編號  
        //         'Manager' => $Manager,
        //     ];
        //     // return view('auth.main',$binding)->withErrors($errors);
        //     //exit;
        // }
        $request =request()->all();
        $enable =$request['enable'];
        $Manager =Managers::where('nokey',$manager_key)->firstOrFail();
        $Manager->enable =$enable;
        $Manager->save();
        return "管理員啟動管理--".$enable;
    }
}
