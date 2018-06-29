<?php

namespace App\Http\Controllers\HotelAuth;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Awugo\Auth\HotelManagers;
use App\Awugo\Auth\Hotel;
use App\Awugo\Auth\Room_Installation;
use App\Awugo\Auth\Room_Name;
use App\Awugo\Auth\Bed_Name;
use App\Awugo\Auth\HotelBedList;
use App\Awugo\HotelAuth\HotelRoomSet;
use Image;
use View;
use DB;
use Validator;
use Debugbar;
use Carbon;
use File;

class RoomSetController extends Controller
{
	// 客房設定介面
    public function main($country, $hotel_id, $room_id){
        app('debugbar')->enable();
        // 取出飯店檔案
        $Hotel =Hotel::find(substr($hotel_id, 1));
        //取出房間設備資料-群組
        $DeviceGroup =Room_Installation::where('is_group',1)->get();
        //取出房間設備資料-元素
        $DeviceItem =Room_Installation::where('is_group',0)->get();
        // 取出房間名稱
        $RoomNames =Room_Name::all();
        //取出現有設定
        $RoomSet =HotelRoomSet::where('hotel_id', substr($hotel_id, 1))->where('nokey', $room_id)->first();
        //$RoomSet->bed .=',';
        $RoomDevice =array();
        if($RoomSet !=null){
            $RoomDevice =explode(',', $RoomSet->room_device);
        }
        //
        //取出床型
        $Beds =Bed_Name::get();
        //讀取床資訊
        $Beds_Type =HotelBedList::where('room_id',$room_id)->get();
        // print_r();
        // exit;
        $binding =[
            'Title' => '客房設定',
            'Nav_ID' => 10,  //功能按鈕編號  
            'Hotel_ID' => $hotel_id,
            'Hotel' =>$Hotel,
            'RoomID' =>$room_id,
            'DeviceGroup' =>$DeviceGroup,
            'DeviceItem' =>$DeviceItem,
            'Beds' =>$Beds,
            'Beds_Type' =>$Beds_Type,
            'RoomSet' =>$RoomSet,
            'RoomDevice' =>$RoomDevice,
            'RoomNames' =>$RoomNames,
            'Country' => $country,
        ];
    	return view('hotel_auth.room_set',$binding);
    }

    // 客房設定介面
    public function mainPost($country, $hotel_id, $room_id){
        //取得勾選值
        $request =request()->all();
        $created_id=session()->get('hotel_manager_id');
        $created_name=session()->get('hotel_manager_name');
        //
        $RoomSet =HotelRoomSet::where('hotel_id', substr($hotel_id, 1));
        if($RoomSet->count() ==0){
            $RoomSet =new HotelRoomSet;
        }else{
            $RoomSet =HotelRoomSet::where('hotel_id', substr($hotel_id, 1))->first();
        }
        //勾選設施
        $chk_service =(!empty($request['service']))?$request['service']:'';
        $chk_str ='';
        foreach ($chk_service as $key => $service) {
            $chk_str .=$service.',';
        }

        //收集床型
        $beds_array =(!empty($request['beds']))?$request['beds']:'';
        $beds_csv =implode(',', $beds_array);
        //收集數量
        $count_array =(!empty($request['count']))?$request['count']:'';
        $count_csv =implode(',', $count_array);
        // 清除舊床型資料
        $old_bed =HotelBedList::where('room_id',$room_id);
        $old_bed->delete();
        foreach ($beds_array as $key => $id) {
            $new_bed =new HotelBedList;
            $new_bed->hotel_id =substr($hotel_id, 1);
            $new_bed->room_id =$room_id;
            $new_bed->bed_id =$id;
            $new_bed->count =$count_array[$key];
            $new_bed->creator_id =$created_id;
            $new_bed->creator_name =$created_name;
            $new_bed->save();
        }
        //
        $RoomSet->room_device =substr($chk_str, 0, -1);
        $RoomSet->name =$request['name'];
        // $RoomSet->bed =str_replace(' ','',substr($request['bed_csv'], 0, -1));
        $RoomSet->min_people =$request['min_people'];
        $RoomSet->sale_people =$request['sale_people_csv'];
        // $RoomSet->max_people =$request['max_people'];
        $RoomSet->sale =(!empty($request['sale']))?$request['sale']:0;
        $RoomSet->room_count =$request['room_count'];
        $RoomSet->room_open_count =$request['room_open_count'];
        $RoomSet->room_area =$request['room_area'];
        $RoomSet->room_feature =$request['room_feature'];
        $RoomSet->creator_id =$created_id;
        $RoomSet->creator_name =$created_name;
        $RoomSet->hotel_id =substr($hotel_id, 1);


        $RoomSet->save();
        //
        return redirect()->back();
    }

    
}
