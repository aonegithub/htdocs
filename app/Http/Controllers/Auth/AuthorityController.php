<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use App\Awugo\Auth\Authority;
use App\Awugo\Auth\Managers;
use Image;
use View;
use DB;

class AuthorityController extends Controller
{
	// 權限管理
    public function main(){
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
        ];
    	return view('auth.authority', $binding);
    }
    // 權限管理修改
    public function editAuth(){
        $Manager =Managers::findOrFail(session()->get('manager_id'));
        $request =request()->all();
        $Manager->auth =implode(',',$request['auth_chk']);
        $Manager->save();
        return redirect()->to('/auth/manager/authority')->with('controll_back_msg', '完成');
        // return redirect('/auth/manager/authority');
    }
}
