<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use App\Awugo\Auth\Authority;
use App\Awugo\Auth\Managers;
use App\Awugo\Auth\Hotel;
use App\Awugo\Auth\Areas;
// use Illuminate\Http\Request;
use Request;
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
        // $queryString =  Request::only([ 'id', 'author', 'title', 'order', 'sort' ]); 
        //讀取飯店清單
        $page_row = 5;
        $Hotel =Hotel::leftJoin('manager_list','hotel_list.created_manager_id', '=', 'manager_list.id')
        ->select('hotel_list.*' ,'manager_list.name as m_name', 'manager_list.department')
        ->OrderBy('hotel_list.state','asc')->OrderBy('hotel_list.nokey','desc')->paginate($page_row);
        // $Hotel = DB::table('hotel_list')->leftJoin('manager_list', 'manager_list.id', '=', 'hotel_list.created_manager_id')->OrderBy('state','asc')->OrderBy('hotel_list.nokey','desc')->paginate($page_row);
        // $Hotel = Hotel::OrderBy('state','asc')->OrderBy('nokey','desc')->paginate($page_row);
        //讀取管理者資訊
        $Manager =Managers::where('id',session()->get('manager_id'))->firstOrFail()->toArray();
        $auth_array =explode(',', session()->get('manager_auth'));
        $binding =[
            'Title' => $this->menu_item_text,
            'Nav_ID' => $this->menu_item_code,  //功能按鈕編號  
            'Manager' => $Manager,
            'Auths' => $auth_array,
            'Country' => $country,
            'Hotels' => $Hotel,
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
    public function addPost($country){
        //寫入登入資訊
        $request =request()->all();
        //讀取管理者資訊
        $Manager =Managers::where('id',session()->get('manager_id'))->firstOrFail();
        $hotel =new Hotel;
        $hotel->name=$request['name'];                                  //飯店名稱
        $hotel->version=$request['ver'];                                //合作模式版本
        $hotel->state=$request['state'];                                //上線狀態
        $hotel->url=$request['url'];                                    //飯店網址
        $hotel->deposit=$request['deposit'];                            //合作模式訂金
        $hotel->control=$request['control'];                            //房管控(立即、排房)
        $hotel->area_level1=$request['area_level1'];                    //所在區域層級1
        $hotel->area_level2=$request['area_level2'];                    //所在區域層級2
        $hotel->area_level3=$request['area_level3'];                    //所在區域層級3
        $hotel->zip_code=$request['zip_code'];                          //飯店郵遞區號
        $hotel->address=$request['address'];                            //剩餘地址
        $hotel->fees_c=$request['fees_c'];                              //C版手續費
        $hotel->fees_c_bonus=$request['fees_c_bonus'];                  //C版紅利
        $hotel->fees_ab=$request['fees_ab'];                            //AB版手續費
        $hotel->fees_ab_bonus=$request['fees_ab_bonus'];                //AB版紅利
        $hotel->fees_d=$request['fees_d'];                              //D版手續費
        $hotel->fees_d_bonus=$request['fees_d_bonus'];                  //D版紅利
        $hotel->fees_sale_bonus=$request['fees_sale_bonus'];            //經銷版紅利
        //經銷版勾選狀態
        $hotel->fees_sale_state=(!empty($request['fees_sale_state']))?$request['fees_sale_state']:0;
        $hotel->fees_roll_bonus=$request['fees_roll_bonus'];            //住宿卷版紅利
        //住宿卷版勾選狀態
        $hotel->fees_roll_state=(!empty($request['fees_roll_state']))?$request['fees_roll_state']:0;   
        //合法旅館勾選狀態
        $hotel->license_hotel=(!empty($request['license_hotel']))?$request['license_hotel']:0;
        //合法民宿勾選狀態
        $hotel->license_homestay=(!empty($request['license_homestay']))?$request['license_homestay']:0;
        //好客民宿勾選狀態
        $hotel->license_hospitable=(!empty($request['license_hospitable']))?$request['license_hospitable']:0;         
        $hotel->tel1=$request['tel1'];                                  //飯店電話
        $hotel->tel2=$request['tel2'];                                  //飯店電話(備用)
        $hotel->fax1=$request['fax1'];                                  //飯店傳真
        $hotel->fax2=$request['fax2'];                                  //飯店傳真(備用)
        $hotel->email1=$request['email1'];                              //飯店信箱
        $hotel->email2=$request['email2'];                              //飯店信箱(備用)
        $hotel->track=$request['track'];                                //追蹤是否
        $hotel->track_comm=$request['track_comm'];                      //追蹤備註
        $hotel->app_line=$request['app_line'];                          //通訊LINE
        $hotel->app_wechat=$request['app_wechat'];                      //通訊WECHAT
        $hotel->checkout=$request['checkout'];                          //結帳方式日、月
        $hotel->booking_day=$request['booking_day'];                    //可開訂日期範圍
        $hotel->invoice_type=$request['invoice_type'];                  //發票開立方式(甲乙丙)
        $hotel->coordinate=$request['coordinate'];                      //飯店配合度
        $hotel->type_scale=$request['type_scale'];                      //飯店規模
        $hotel->type_level=$request['type_level'];                      //飯店星級
        $hotel->type_room=$request['type_room'];                        //飯店房數
        $hotel->local_police=$request['local_police'];                  //顯示當地警方
        $hotel->local_police_comm=$request['local_police_comm'];        //當地警方資訊
        $hotel->invoice=$request['invoice'];                            //是否開發票0是1收據2無
        $hotel->seo_title=$request['seo_title'];                        //SEO標題
        $hotel->seo_keyword=$request['seo_keyword'];                    //SEO關鍵字
        $hotel->seo_descript=$request['seo_descript'];                  //SEO描述
        $hotel->reg_name=$request['reg_name'];                          //立案名稱
        $hotel->reg_no=$request['reg_no'];                              //立案統編
        $hotel->credit_card=$request['credit_card'];                    //刷卡服務0=可1=含國旅卡2=否
        $hotel->display_tel=$request['display_tel'];                    //顯示飯店電話
        $hotel->bank_name=$request['bank_name'];                        //銀行名稱
        $hotel->bank_code=$request['bank_code'];                        //銀行代碼
        $hotel->bank_account=$request['bank_account'];                  //銀行帳戶
        $hotel->bank_account_name=$request['bank_account_name'];        //銀行帳戶名稱
        $hotel->point=$request['point'];                                //飯店優點
        $hotel->contact_name=$request['contact_name'];                  //聯絡人名稱
        $hotel->contact_job=$request['contact_job'];                    //聯絡人職稱
        $hotel->contact_tel=$request['contact_tel'];                    //聯絡人電話
        $hotel->contact_mobile=$request['contact_mobile'];              //聯絡人手機
        $hotel->contact_line=$request['contact_line'];                  //聯絡人Line
        $hotel->contact_wechat=$request['contact_wechat'];              //聯絡人wechat
        $hotel->contact_email=$request['contact_email'];                //聯絡人email
        $hotel->manage_url=$request['manage_url'];                      //飯店管理後台網址
        $hotel->manage_surl=$request['manage_surl'];                    //飯店管理後台簡易網址
        $hotel->c_url=$request['c_url'];                                //C版網址
        $hotel->c_surl=$request['c_surl'];                              //C版簡易網址
        $hotel->d_url=$request['d_url'];                                //D版網址
        $hotel->d_surl=$request['d_surl'];                              //D版簡易網址
        //D版勾選狀態
        $hotel->d_enable=(!empty($request['d_enable']))?$request['d_enable']:0;
        $hotel->ab_url=$request['ab_url'];                              //AB版網址
        $hotel->login_name=$request['login_name'];                      //登錄者名稱
        $hotel->login_com=$request['login_com'];                        //登錄者公司
        $hotel->login_job=$request['login_job'];                        //登錄者職稱
        $hotel->login_addr_level1=$request['login_area_level1'];        //登錄者地區層級1
        $hotel->login_addr_level2=$request['login_area_level2'];        //登錄者地區層級2
        $hotel->login_addr_level3=$request['login_area_level3'];        //登錄者地區層級3
        $hotel->login_addr=$request['login_addr'];                      //登錄者地址
        $hotel->login_tel=$request['login_tel'];                        //登錄者電話
        $hotel->login_mobile=$request['login_mobile'];                  //登錄者手機
        $hotel->login_email=$request['login_email'];                    //登錄者信箱
        $hotel->login_id=$request['login_id'];                          //登錄者帳號
        if(!empty($request['login_passwd'])){
            $request['login_passwd']=Hash::make($request['login_passwd']);
        }
        $hotel->login_passwd=$request['login_passwd'];                  //登錄者密碼HASH
        $hotel->login_is_group=$request['login_is_group'];              //登錄者是否集團
        $hotel->login_group_name=$request['login_group_name'];          //登錄者集團名稱
        $hotel->login_group_url=$request['login_group_url'];            //登錄者集團網址
        $hotel->login_group_count=$request['login_group_count'];        //登錄者子公司數量
        $hotel->expire=$request['expire'];                              //到期日
        $hotel->created_manager_name=session()->get('manager_name');    //寫入者
        $hotel->created_manager_id=session()->get('manager_id');        //寫入者外鍵
        $hotel->save();

        return redirect()->to('/'. $country .'/auth/manager/hotel_add')->with('controll_back_msg', 'ok');
    }

}
