<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use App\Awugo\Auth\Managers;
use Image;
use View;
use DB;

class ManagerController extends Controller
{
	// 登入口
    public function login(){
    	return view('auth/login');
    }
    // 登入處理
    public function login_post(){
        DB::enableQueryLog();
    	$input = request()->all();
        // 加密密碼用於比對
        // $input['inputPassword'] = Hash::make($input['inputPassword']);
        $Manager =Managers::where('id',$input['inputID'])->firstOrFail();
        $aa =Hash::check($input['inputPassword'], $Manager->passwd);
        // 比對加密密碼
        //echo Hash::make($input['inputPassword']);
        var_dump($aa);
        //var_dump(DB::getQueryLog());
    	exit;
    	// return var_dump(DB::getQueryLog());
    }
    // 登出口
    public function logout(){
    	return "logout";
    }
}
