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
use App\Awugo\HotelAuth\HotelRoomPhoto;
use Image;
use View;
use DB;
use Validator;
use Debugbar;
use Carbon;
use File;

class RoomSetController extends Controller
{
    // 客房設定清單
    public function list($country, $hotel_id){
        //取出現有設定
        $RoomSet =HotelRoomSet::where('hotel_room_set.hotel_id', substr($hotel_id, 1))->get();
        $RoomPhotosArray =array();
        $DeviceArray =array();
        $RoomBedsArray =array();
        foreach ($RoomSet as $key => $room) {
            if(HotelRoomPhoto::where('hotel_id', substr($hotel_id, 1))->where('room_id', $room->nokey)->count() >0){
            $RoomPhoto =HotelRoomPhoto::where('hotel_id', substr($hotel_id, 1))->where('room_id', $room->nokey)->OrderBy('sort','desc')->first();
            array_push($RoomPhotosArray,$RoomPhoto->photo);
            }else{
                array_push($RoomPhotosArray,'');
            }
            $DeviceArray[$key] =explode(',',$room->room_device);
            //取出已選房型
            $BedsSet =HotelBedList::leftJoin('bed_name_list', 'hotel_bed_list.bed_id', '=', 'bed_name_list.nokey')->where('hotel_bed_list.hotel_id', substr($hotel_id, 1))->where('hotel_bed_list.room_id',$room->nokey);
            if($BedsSet->count() >0){
                foreach ($BedsSet->get(['bed_name_list.name as bed_ch', 'hotel_bed_list.count as bed_count']) as $k => $beds) {
                    $RoomBedsArray[$key][$k] =$beds->bed_count.'個'.$beds->bed_ch;
                    if($beds->bed_count==0){
                        $RoomBedsArray[$key][$k]='';
                    }
                }
            }else{
                $RoomBedsArray[$key] =null;
            }
        }

        // dd($RoomBedsArray);
        // exit;
        //取出設施
        $Device =Room_Installation::where('is_group',0)->get();
        //取出床型
        $Beds =Bed_Name::get();
        // 取出飯店檔案
        $Hotel =Hotel::find(substr($hotel_id, 1));

        $binding =[
            'Title' => '客房清單',
            'Nav_ID' => 10,  //功能按鈕編號  
            'Hotel_ID' => $hotel_id,
            'Hotel' =>$Hotel,
            'RoomSet' =>$RoomSet,
            'RoomPhotosArray' =>$RoomPhotosArray,
            'Device' =>$Device,
            'DeviceArray' =>$DeviceArray,
            'Beds' =>$Beds,
            'RoomBeds' =>$RoomBedsArray,
            'Country' => $country,
        ];
        return view('hotel_auth.room_set_list',$binding);
    }
	// 客房設定介面
    public function main($country, $hotel_id, $room_id){
        // 新增房型
        if($room_id =='add'){
            $addSet =new HotelRoomSet;
            $addSet->hotel_id =substr($hotel_id, 1);
            $addSet->save();
            $getSet =HotelRoomSet::where('hotel_id',substr($hotel_id, 1))->OrderBy('nokey','desc')->first();
            $addBed =new HotelBedList;
            $addBed->hotel_id=substr($hotel_id, 1);
            $addBed->room_id=$getSet->nokey;
            $addBed->bed_id=42;
            $addBed->count=0;
            $addBed->save();
            //
            // $photo_info =new HotelRoomPhoto;
            // $photo_info->hotel_id =substr($hotel_id, 1);
            // $photo_info->room_id =$getSet->nokey;
            // $photo_info->photo ="";
            // $photo_info->save();
            $room_id =$getSet->nokey;
        }
        // exit;
        // 
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
        $Beds =Bed_Name::OrderBy('sort','desc')->get();
        //讀取床資訊
        $Beds_Type =HotelBedList::where('room_id',$room_id)->get();
        // 取出房間照片
        $RoomPhotos =HotelRoomPhoto::where('room_id',$room_id)->OrderBy('sort','desc')->get();
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
            'RoomPhotos' =>$RoomPhotos,
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
        $RoomSet =HotelRoomSet::where('nokey', $room_id);
        if($RoomSet->count() ==0){
            $RoomSet =new HotelRoomSet;
        }else{
            $RoomSet =HotelRoomSet::where('nokey', $room_id)->first();
        }
        
        //exit;
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
        return redirect()->to("/tw/auth/h".substr($hotel_id, 1)."/room_set/".$room_id);
    }

    //修改照片排序
    public function roomPhotoDel($country, $hotel_id){
        $request =request()->all();
        $RoomPhoto =HotelRoomPhoto::where('hotel_id',substr($hotel_id,1))->where('nokey',$request['nokey'])->first();
        $RoomPhoto->delete();
        return redirect()->back();
    }
    //修改照片排序
    public function roomPhotoEdit($country, $hotel_id){
        $request =request()->all();
        $RoomPhoto =HotelRoomPhoto::where('hotel_id',substr($hotel_id,1))->where('nokey',$request['nokey'])->first();
        $RoomPhoto->sort=$request['sort'];
        $RoomPhoto->save();

        return redirect()->back();
    }
    //刪除房間設定
    public function delRoom($country, $hotel_id, $room_id){
        $Room =HotelRoomSet::where('nokey', $room_id)->first();
        $Room->delete();

        return redirect()->back();
    }

    //照片上傳處理
    public function photoPlanUpload(Request $request, $country, $hotel_id, $room_id){
        app('debugbar')->enable();
        ini_set('memory_limit', '256M');
        $photos_path = public_path('/photos/room');
        //
        $hotel_id =substr(session()->get('hotel_id'),1);
        $created_id=session()->get('hotel_manager_id');
        $created_name=session()->get('hotel_manager_name');
        //判斷是否已有照片，如有就是取代
        // $photo_record =HotelServicePhotos::where('hotel_list_id',$hotel_id)->where('hotel_service_id',$service_id)->count();
        //
        $photos = $request->file('room_photo');

        if (!is_dir($photos_path)) {
            mkdir($photos_path, 0777);
        }
        $photo = $photos;
        $width = 0;
        $height = 0;
        list($width, $height) = getimagesize($photos);
        
        
        // $name = sha1(date('YmdHis') . str_random(30));
        $name =date("YmdHis").'_'.$hotel_id.'_'.(explode(' ', microtime())[0]*100000000);
        $save_name = $name . '.' . $photo->getClientOriginalExtension();
        // if($photo_record >0){
        //     $photo_info =HotelServicePhotos::where('hotel_list_id',$hotel_id)->where('hotel_service_id',$service_id)->first();
        //     if(!empty($photo_info->photo) && $photo_info->photo !=null){
        //         $save_name =$photo_info->photo;
        //     }
        // }
        $sub_name =$photo->getClientOriginalExtension();
        // $resize_name = $name . str_random(2) . '.' . $photo->getClientOriginalExtension();
        // 直立照片
        if($height >$width){
            $ss1_dir =85;$s1_dir =null;
            $ss2_dir =250;$s2_dir =null;
            $ss3_dir =600;$s3_dir =null;
        }else{
        // 橫立照片
            //各尺寸縮圖資料夾
            $s1_dir =100;$ss1_dir =null;
            $s2_dir =250;$ss2_dir =null;
            $s3_dir =800;$ss3_dir =null;
        }
        //縮圖1  60
        Image::make($photo)
            ->resize($s1_dir, $ss1_dir, function ($constraints) {
                $constraints->aspectRatio();
            })
            ->save($photos_path . '/100/' . $save_name);
            // $photo->move($photos_path. '/'.$s1_dir.'/', $save_name);
        //縮圖2  250
        Image::make($photo)
            ->resize($s2_dir, $ss2_dir, function ($constraints) {
                $constraints->aspectRatio();
            })
            ->save($photos_path . '/250/' . $save_name);
            // $photo->move($photos_path. '/'.$s2_dir.'/', $save_name);
        //縮圖3  800
        Image::make($photo)
            ->resize($s3_dir, $ss3_dir, function ($constraints) {
                $constraints->aspectRatio();
            })
            ->save($photos_path . '/800/' . $save_name);
            // $photo->move($photos_path. '/'.$s3_dir.'/', $save_name);
        //
        $photo->move($photos_path, $save_name);
        //
        // $photo_info =null;
        // if($photo_record >0){
        //     $photo_info =HotelServicePhotos::where('hotel_list_id',$hotel_id)->where('hotel_service_id',$service_id)->first();
        // }else{
        //     $photo_info =new HotelServicePhotos;
        // }
        $photo_info =new HotelRoomPhoto;
        $photo_info->hotel_id =$hotel_id;
        $photo_info->room_id =$room_id;
        $photo_info->photo =$save_name;
        $photo_info->creator_name =$created_name;
        $photo_info->creator_id =$created_id;
        $photo_info->save();
        // echo $service_id.'<br/>';
        // echo $hotel_id.'<br/>';
        // echo $created_id.'<br/>';
        // echo $created_name.'<br/>';
        // echo $save_name.'<br/>';
        //
        // return redirect()->back();
        return redirect()->to("/tw/auth/h".$hotel_id."/room_set/".$room_id);
    }
}
