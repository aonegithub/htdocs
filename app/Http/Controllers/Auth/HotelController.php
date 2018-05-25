<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use App\Awugo\Auth\Authority;
use App\Awugo\Auth\Managers;
use App\Awugo\Auth\Hotel;
use App\Awugo\Auth\Hotel_Comm;
use App\Awugo\Auth\HotelManagers;
use App\Awugo\Auth\HotelAuthority;
use App\Awugo\Auth\Areas;
use Carbon\Carbon;
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
    public function main(Request $request,$country){
        //生成查詢字串
        $state_q =Request::input('state');              //狀態
        $ver_q =Request::input('ver');                  //版本
        $country_q =Request::input('country');          //國家
        $area2_q =Request::input('area2');              //縣市
        $area3_q =Request::input('area3');              //區域
        $ctrl_q =Request::input('ctrl');                //控管
        $c_type_q =Request::input('c_type');            //合作
        $room_count_q =Request::input('room_count');    //房間
        $holiday_q =Request::input('holiday');          //連假房價
        $search_q =trim(Request::input('search'));      //關鍵字
        $queryString =['state'=>$state_q,'ver'=>$ver_q,'country'=>$country_q,'area2'=>$area2_q,'area3'=>$area3_q,'ctrl'=>$ctrl_q,'c_type'=>$c_type_q,'room_count'=>$room_count_q,'holiday'=>$holiday_q,'search'=>$search_q];
        // 資料庫用
        $state_s =($state_q==null ||$state_q=='-1')?'%':$state_q;                           //狀態
        $ver_s =($ver_q==null ||$ver_q=='-1')?'%':$ver_q;                                   //版本
        $country_s =($country_q==null ||$country_q=='-1')?'%':$country_q;                   //國家
        $area2_s =($area2_q==null ||$area2_q=='-1')?'%':$area2_q;                           //縣市
        $area3_s =($area3_q==null ||$area3_q=='-1')?'%':$area3_q;                           //區域
        $ctrl_s =($ctrl_q==null ||$ctrl_q=='-1')?'%':$ctrl_q;                               //控管
        $c_type_s =($c_type_q==null ||$c_type_q=='-1')?'%':$c_type_q;                       //合作種類
        $holiday_s =($holiday_q==null ||$holiday_q=='-1')?'%':$holiday_q;                   //連假房價
        $room_count_s =($room_count_q==null ||$room_count_q=='-1')?'%':$room_count_q;       //房間數量
        //房間數量字串切換為區間
        $room_arr =array();
        if($room_count_s =='100'){
            $room_arr=array(100,999);
        }else if($room_count_s =='50-99'){
            $room_arr=array(50,99);
        }else if($room_count_s =='15-49'){
            $room_arr=array(15,49);
        }else if($room_count_s =='1-14'){
            $room_arr=array(1,14);
        }else{
            $room_arr=array(1,999);
        }
        $search_s =($search_q==null ||$search_q=='-1')?'%':$search_q;                       //關鍵字
                \Debugbar::error(Carbon::now());
               // exit;
        //讀取飯店清單
        $page_row = 20;
        // $Hotel =Hotel::leftJoin('manager_list','hotel_list.created_manager_id', '=', 'manager_list.id')
        // ->select('hotel_list.*' ,'manager_list.name as m_name', 'manager_list.department')
        // ->OrderBy('hotel_list.nokey','desc')->paginate($page_row)->appends($queryString);
        // $Hotel = DB::table('hotel_list')->leftJoin('manager_list', 'manager_list.id', '=', 'hotel_list.created_manager_id')->OrderBy('state','asc')->OrderBy('hotel_list.nokey','desc')->paginate($page_row);
        $Hotel = Hotel::where('hotel_list.state','LIKE',$state_s)->where('hotel_list.version','LIKE',$ver_s)->where('hotel_list.area_level1','LIKE',$country_s)->where('hotel_list.area_level2','LIKE',$area2_s)->where('hotel_list.area_level3','LIKE',$area3_s)->where('hotel_list.control','LIKE',$ctrl_s)->where('hotel_list.control','LIKE',$ctrl_s)->where('hotel_list.cooperation','LIKE',$c_type_s)->where('hotel_list.holiday','LIKE',$holiday_s)->whereBetween('hotel_list.type_room',$room_arr)->where('hotel_list.name','LIKE','%'.$search_s.'%')->leftJoin('manager_list','hotel_list.created_manager_id', '=', 'manager_list.id')->select('hotel_list.nokey','hotel_list.name','hotel_list.state','hotel_list.invoice_type','hotel_list.version','hotel_list.fees_c','hotel_list.fees_c_bonus','hotel_list.type_room','hotel_list.cooperation','hotel_list.control', 'hotel_list.deposit')->OrderBy('hotel_list.nokey','desc')->paginate($page_row)->appends($queryString);
        
        //帶入縣市
        //二級清單
        $Areas_level2 =Areas::where('area_level','2')->where('area_code', '=', session()->get('manager_country'))->get(); //二級區域
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
            'Areas_level2' => $Areas_level2,
            'QueryArray' => $queryString,
        ];
        return view('auth.hotel_list', $binding);
    }
// 飯店管理新增介面View
    public function add($country){
        $auth_key ='2'; //權限碼
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
        $hotel->contract_no=$request['contract_no'];                    //合約編號
        $hotel->name=$request['name'];                                  //飯店名稱
        $hotel->version=$request['ver'];                                //合作模式版本
        $hotel->cooperation=$request['cooperation'];                    //合作種類
        $hotel->state=$request['state'];                                //上線狀態
        $hotel->url=$request['url'];                                    //飯店網址
        $hotel->deposit=$request['deposit'];                            //合作模式訂金
        $hotel->control=$request['control'];                            //房管控(立即、排房)
        $hotel->area_level1=$request['area_level1'];                    //所在區域層級1
        $hotel->area_level2=$request['area_level2'];                    //所在區域層級2
        $hotel->area_level3=$request['area_level3'];                    //所在區域層級3
        $hotel->zip_code=$request['zip_code'];                          //飯店郵遞區號
        $hotel->address=$request['address'];                            //剩餘地址
        //C版手續費
        $hotel->fees_c=(!empty($request['fees_c']))?$request['fees_c']:0;                 
        //C版紅利             
        $hotel->fees_c_bonus=(!empty($request['fees_c_bonus']))?$request['fees_c_bonus']:0;
        //AB版手續費
        $hotel->fees_ab=(!empty($request['fees_ab']))?$request['fees_ab']:0;    
        //AB版紅利                        
        $hotel->fees_ab_bonus=(!empty($request['fees_ab_bonus']))?$request['fees_ab_bonus']:0;
        //D版手續費
        $hotel->fees_d=(!empty($request['fees_d']))?$request['fees_d']:0;                  
        //D版紅利            
        $hotel->fees_d_bonus=(!empty($request['fees_d_bonus']))?$request['fees_d_bonus']:0;
        //經銷版紅利
        $hotel->fees_sale_bonus=(!empty($request['fees_sale_bonus']))?$request['fees_sale_bonus']:0;
        //經銷版勾選狀態
        $hotel->fees_sale_state=(!empty($request['fees_sale_state']))?$request['fees_sale_state']:0;
        //住宿卷版紅利
        $hotel->fees_roll_bonus=(!empty($request['fees_roll_bonus']))?$request['fees_roll_bonus']:0;
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
        $hotel->contact_text=$request['contact_text'];                  //無限聯絡人csv
        $hotel->manage_url=$request['manage_url'];                      //飯店管理後台網址
        $hotel->manage_surl=$request['manage_surl'];                    //飯店管理後台簡易網址
        $hotel->c_url=$request['c_url'];                                //C版網址
        $hotel->c_surl=$request['c_surl'];                              //C版簡易網址
        $hotel->d_url=$request['d_url'];                                //D版網址
        $hotel->d_surl=$request['d_surl'];                              //D版簡易網址
        $hotel->d_display_tel=$request['d_display_tel'];                //D版顯示電話
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
        $hotel->holiday=$request['holiday'];                            //連假房價
        $hotel->expire=$request['expire'];                              //到期日
        $hotel->sort=$request['sort'];                                  //前台排序
        $hotel->created_manager_name=session()->get('manager_name');    //寫入者
        $hotel->created_manager_id=session()->get('manager_id');        //寫入者外鍵
        $hotel->save();
        //寫入權限管理員表
        if ($request['login_id'] !='') {
            //取得權限總索引
            $h_auth =HotelAuthority::get(['nokey'])->toArray();
            $auth_string='';
            //因為產生無法Array to String的例外，改用手動組拚
            foreach($h_auth as $auth){
                $auth_string .=$auth['nokey'].',';
            }
            $auth_string =substr($auth_string,0,-1);                     //去除最後的逗號(手動組拚的缺陷)
            // echo $auth_string;
            //
            $hotel_menager =new HotelManagers;
            $hotel_menager->id=$request['login_id'];
            $hotel_menager->passwd=Hash::make($request['login_passwd']);
            $hotel_menager->hotel_list_id=$hotel->nokey;
            $hotel_menager->name=$request['login_name'];
            $hotel_menager->department=$request['login_com'];
            $hotel_menager->auth=$auth_string;
            $hotel_menager->ip=Request::ip();
            $hotel_menager->created_id=session()->get('manager_id');
            $hotel_menager->created_name=session()->get('manager_name');
            $hotel_menager->save();
        }
        return redirect()->to('/'. $country .'/auth/manager/hotel_add')->with('controll_back_msg', 'ok');
    }
// 飯店管理編輯介面View
    public function edit($country,$hotelKey){
        $auth_key ='3'; //權限碼
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
        // 讀取飯店資料
        $Hotel =Hotel::where('nokey',$hotelKey)->firstOrFail();
        //帶入縣市
        //二級清單
        $Areas_level2 =Areas::where('area_level','2')->where('area_code', '=', session()->get('manager_country'))->get(); //二級區域
        //帶入已選行政區域(飯店地址)
        $Addr_level3 =Areas::where('area_parent',$Hotel->area_level2)->where('area_code', '=', session()->get('manager_country'))->get(); //三級區域
        //帶入已選行政區域
        $Login_addr_level3 =Areas::where('area_parent',$Hotel->login_addr_level2)->where('area_code', '=', session()->get('manager_country'))->get(); //三級區域
        //切聯絡人csv
        $contact_column_count =7;
        $contact_arr =explode(',', $Hotel->contact_text);
        $contact_arr_rang =floor(count($contact_arr)/$contact_column_count)-1;  //計算機本維度(減一去除空行)
        $Contact_Array =array();
        //切割生成多維聯絡人陣列
        for($i=0; $i<$contact_arr_rang; $i++){
            for($j=0; $j<$contact_column_count; $j++){
                if($i==0){
                    $Contact_Array[$i][$j] = $contact_arr[$j];
                }else{
                    $Contact_Array[$i][$j] = $contact_arr[($j+($i*$contact_column_count))];
                }
                
            }
        }
        // print_r($Contact_Array);
        // exit;
        //
        $binding =[
            'Title' => $this->menu_item_text,
            'Nav_ID' => $this->menu_item_code,  //功能按鈕編號  
            'Manager' => $Manager,
            'Auths' => $auth_array,
            'Country' => $country,
            'Areas_level2' => $Areas_level2,
            'Hotel' => $Hotel,
            'Addr_level3' => $Addr_level3,
            'Login_addr_level3' => $Login_addr_level3,
            'Contact' => $Contact_Array,
        ];
        return view('auth.hotel_edit', $binding);
    }
// 飯店編輯POST
    public function editPost($country,$hotelKey){
        //寫入登入資訊
        $request =request()->all();
        //讀取管理者資訊
        $Manager =Managers::where('id',session()->get('manager_id'))->firstOrFail();
        $hotel =Hotel::find($hotelKey);
        $hotel->contract_no=$request['contract_no'];                    //合約編號
        $hotel->name=$request['name'];                                  //飯店名稱
        $hotel->version=$request['ver'];                                //合作模式版本
        $hotel->cooperation=$request['cooperation'];                    //合作種類
        $hotel->state=$request['state'];                                //上線狀態
        $hotel->url=$request['url'];                                    //飯店網址
        $hotel->deposit=$request['deposit'];                            //合作模式訂金
        $hotel->control=$request['control'];                            //房管控(立即、排房)
        $hotel->area_level1=$request['area_level1'];                    //所在區域層級1
        $hotel->area_level2=$request['area_level2'];                    //所在區域層級2
        $hotel->area_level3=$request['area_level3'];                    //所在區域層級3
        $hotel->zip_code=$request['zip_code'];                          //飯店郵遞區號
        $hotel->address=$request['address'];                            //剩餘地址
        //C版手續費
        $hotel->fees_c=(!empty($request['fees_c']))?$request['fees_c']:0;                 
        //C版紅利             
        $hotel->fees_c_bonus=(!empty($request['fees_c_bonus']))?$request['fees_c_bonus']:0;
        //AB版手續費
        $hotel->fees_ab=(!empty($request['fees_ab']))?$request['fees_ab']:0;    
        //AB版紅利                        
        $hotel->fees_ab_bonus=(!empty($request['fees_ab_bonus']))?$request['fees_ab_bonus']:0;
        //D版手續費
        $hotel->fees_d=(!empty($request['fees_d']))?$request['fees_d']:0;                  
        //D版紅利            
        $hotel->fees_d_bonus=(!empty($request['fees_d_bonus']))?$request['fees_d_bonus']:0;
        //經銷版紅利
        $hotel->fees_sale_bonus=(!empty($request['fees_sale_bonus']))?$request['fees_sale_bonus']:0;            
        //經銷版勾選狀態
        $hotel->fees_sale_state=(!empty($request['fees_sale_state']))?$request['fees_sale_state']:0;
        //住宿卷版紅利
        $hotel->fees_roll_bonus=(!empty($request['fees_roll_bonus']))?$request['fees_roll_bonus']:0;
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
        $hotel->contact_text=$request['contact_text'];                  //無限聯絡人csv
        $hotel->manage_url=$request['manage_url'];                      //飯店管理後台網址
        $hotel->manage_surl=$request['manage_surl'];                    //飯店管理後台簡易網址
        $hotel->c_url=$request['c_url'];                                //C版網址
        $hotel->c_surl=$request['c_surl'];                              //C版簡易網址
        $hotel->d_url=$request['d_url'];                                //D版網址
        $hotel->d_surl=$request['d_surl'];                              //D版簡易網址
        //D版勾選狀態
        $hotel->d_enable=(!empty($request['d_enable']))?$request['d_enable']:0;
        $hotel->d_display_tel=$request['d_display_tel'];                //D版顯示電話
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
        
        $hotel->login_is_group=$request['login_is_group'];              //登錄者是否集團
        $hotel->login_group_name=$request['login_group_name'];          //登錄者集團名稱
        $hotel->login_group_url=$request['login_group_url'];            //登錄者集團網址
        $hotel->login_group_count=$request['login_group_count'];        //登錄者子公司數量
        $hotel->expire=$request['expire'];                              //到期日
        $hotel->holiday=$request['holiday'];                            //連假房價
        $hotel->sort=$request['sort'];                                  //前台排序
        $hotel->created_manager_name=session()->get('manager_name');    //寫入者
        $hotel->created_manager_id=session()->get('manager_id');        //寫入者外鍵
        $hotel->save();

        return redirect()->to('/'. $country .'/auth/manager/hotel_list');
    }
// 飯店瀏覽介面View
    public function browse($country,$hotelKey){
        $auth_key ='3'; //權限碼
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
        // 讀取飯店備註
        $Hotel_Comm =Hotel_Comm::where('hotel_id',$hotelKey)->OrderBy('updated_at','desc')->get();
        // 讀取飯店資料
        $Hotel =Hotel::where('nokey',$hotelKey)->firstOrFail();
        //帶入縣市
        //二級清單
        $Areas_level2 =Areas::where('area_level','2')->where('area_code', '=', session()->get('manager_country'))->get(); //二級區域
        //帶入已選行政區域(飯店地址)
        $Addr_level3 =Areas::where('area_parent',$Hotel->area_level2)->where('area_code', '=', session()->get('manager_country'))->get(); //三級區域
        //帶入已選行政區域
        $Login_addr_level3 =Areas::where('area_parent',$Hotel->login_addr_level2)->where('area_code', '=', session()->get('manager_country'))->get(); //三級區域
        //切聯絡人csv
        $contact_column_count =7;
        $contact_arr =explode(',', $Hotel->contact_text);
        $contact_arr_rang =floor(count($contact_arr)/$contact_column_count)-1;  //計算機本維度(減一去除空行)
        $Contact_Array =array();
        //切割生成多維聯絡人陣列
        for($i=0; $i<$contact_arr_rang; $i++){
            for($j=0; $j<$contact_column_count; $j++){
                if($i==0){
                    $Contact_Array[$i][$j] = $contact_arr[$j];
                }else{
                    $Contact_Array[$i][$j] = $contact_arr[($j+($i*$contact_column_count))];
                }
                
            }
        }
        // print_r($Hotel->contact_text);
        // exit;
        //
        $binding =[
            'Title' => $this->menu_item_text,
            'Nav_ID' => $this->menu_item_code,  //功能按鈕編號  
            'Manager' => $Manager,
            'Auths' => $auth_array,
            'Country' => $country,
            'Areas_level2' => $Areas_level2,
            'Hotel' => $Hotel,
            'Addr_level3' => $Addr_level3,
            'Login_addr_level3' => $Login_addr_level3,
            'Contact' => $Contact_Array,
            'Hotel_Comm' => $Hotel_Comm,
        ];
        return view('auth.hotel_browse', $binding);
    }
//Ajax 關閉飯店
    public function disableAjax($country,$hotelKey){
        $hotel =Hotel::find($hotelKey);
        $hotel->state =2;
        $hotel->save();
        return 'OK';
    }
//Ajax 寫入飯店歷程記錄
    public function addCommAjax($country,$hotelKey){
        $request =request()->all();
        $hotel_comm =new Hotel_Comm;
        $hotel_comm->hotel_id =$hotelKey;
        $hotel_comm->comm =$request['comm'];
        $hotel_comm->write_id =session()->get('manager_id');
        $hotel_comm->write_name =session()->get('manager_name');
        $hotel_comm->save();
        return 'OK';
    }
}
