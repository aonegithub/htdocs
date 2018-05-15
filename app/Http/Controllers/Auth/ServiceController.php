<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use App\Awugo\Auth\Authority;
use App\Awugo\Auth\Managers;
use App\Awugo\Auth\Hotel;
use App\Awugo\Auth\Service;
use App\Awugo\Auth\Areas;
use Carbon\Carbon;
// use Illuminate\Http\Request;
use Request;
use Image;
use View;
use DB;
use Validator;

class ServiceController extends Controller
{
    private $menu_item_code =44;
    private $menu_item_text ='設施與服務';
    // private $auth_array =explode(',', session()->get('manager_auth'));
// 服務管理預設清單
    public function main(Request $request,$country){
        //每頁筆數
        $page_row = 1;
        //
        $group_q =Request::input('group');            //群組查詢
        $group_s =($group_q !='-1')?$group_q:'%';
        $queryString =['group'=>$group_q];
        //讀取設施與服務(群組)
        $Service_Groups =Service::where('is_group','1')->get();
        //讀取設施與服務(項目)
        $Service_Items =Service::where('service_list.parent','LIKE',$group_s)->leftjoin('service_list as sl','sl.nokey', '=', 'service_list.parent')->select('service_list.*', 'sl.service_name as sl_name')->OrderBy('service_list.updated_at','desc')->paginate($page_row)->appends($queryString);
        //讀取管理者資訊
        $Manager =Managers::where('id',session()->get('manager_id'))->firstOrFail()->toArray();
        $auth_array =explode(',', session()->get('manager_auth'));
        $binding =[
            'Title' => $this->menu_item_text,
            'Nav_ID' => $this->menu_item_code,  //功能按鈕編號  
            'Manager' => $Manager,
            'Auths' => $auth_array,
            'Country' => $country,
            'Service_Groups' => $Service_Groups,
            'Service_Items' => $Service_Items,
            'Group_Query' => $group_q,
        ];
        return view('auth.service_list', $binding);
    }
// 新增服務 ajax
    public function addPost(Request $request,$country){
        $request =request()->all();
        //
        $service =new Service;
        $service->service_name = $request['name'];
        $is_group=0;
        if($request['parent']=='-1'){
            $is_group=1;
        }
        $service->parent = $request['parent'];
        $service->is_group = $is_group;
        $service->created_id = session()->get('manager_id');
        $service->created_name = session()->get('manager_name');
        $service->save();

        return 'test';
    }
}
