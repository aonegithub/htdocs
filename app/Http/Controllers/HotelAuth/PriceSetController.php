<?php

namespace App\Http\Controllers\HotelAuth;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Awugo\Auth\HotelManagers;
use App\Awugo\Auth\Hotel;
use App\Awugo\HotelAuth\HotelRoomSet;
use App\Awugo\HotelAuth\HotelPriceNormal;

use Image;
use View;
use DB;
use Validator;
use Debugbar;
use Carbon;
use File;
use Request as RQ;

class PriceSetController extends Controller
{
    // 全部房價
    public function price($country, $hotel_id){
        
        // 取出飯店檔案
        $Hotel =Hotel::find(substr($hotel_id, 1));

        $binding =[
            'Title' => '全部房價',
            'Nav_ID' => 10,  //功能按鈕編號  
            'Hotel_ID' => $hotel_id,
            'Hotel' =>$Hotel,
            'Country' => $country,
        ];
        return view('hotel_auth.price',$binding);
    }

    // 一般房價
    public function price_normal($country, $hotel_id){
        
        // 取出飯店檔案
        $Hotel =Hotel::find(substr($hotel_id, 1));
        // 取出已設定房型
        $RoomList =HotelRoomSet::where('hotel_id', substr($hotel_id, 1))->get();
        $RoomSale =substr($RoomList[0]->sale_people, 0, -1);
        $RoomSaleArray =explode(',', $RoomSale);
        // 取出房價
        $PriceNormal =HotelPriceNormal::where('hotel_id',substr($hotel_id, 1))->where('room_id',$RoomList[0]->nokey)->OrderBy('merge','asc')->get();
        // 取出最後組合碼
        $MergeLastNo =0;
            $MergeNo =HotelPriceNormal::where('hotel_id',substr($hotel_id, 1))->where('room_id',$RoomList[0]->nokey)->OrderBy('merge','desc')->select('merge')->first();
        if(isset($MergeNo)){
            $MergeLastNo =$MergeNo->merge;
        }

        // dd($RoomArray);
        // dd($MergeNo->merge);
        // exit;
        $binding =[
            'Title' => '全部房價',
            'Nav_ID' => 10,  //功能按鈕編號  
            'Hotel_ID' => $hotel_id,
            'Hotel' =>$Hotel,
            'RoomList' =>$RoomList,
            'RoomSaleArray' =>$RoomSaleArray,
            'PriceNormal' =>$PriceNormal,
            'MergeLastNo' =>$MergeLastNo,
            'Country' => $country,
        ];
        return view('hotel_auth.price_normal',$binding);
    }

    // 一般房價(接收資料)
    public function price_normal_post($country, $hotel_id){
        //寫入登入資訊
        $request =request()->all();
        //
        echo "房間編號：".$request['room_list']."<br/>";
        echo "房價組數：".$request['totalPriceSet']."<br/>";
        echo "優惠組數：".$request['totalSalePeople']."<br/>";
        $totalSet =$request['totalPriceSet']*$request['totalSalePeople'];
        $j=0;
        $year_str=array();
        for ($k=0;$k<$totalSet;$k++) {
                if(($k)==$request['totalSalePeople']){
                    $j++;
                }
                array_push($year_str, $request['sale_people'][$k].''.$j);
        }
        //刪除舊資料
            $PriceNormal =HotelPriceNormal::where('hotel_id',substr($hotel_id, 1))->where('room_id',$request['room_list']);
            $PriceNormal->delete();
        for ($i=0;$i<$totalSet;$i++) {
            echo "組別：".floor($i/$request['totalSalePeople']);
            echo "優惠人次：".$request['sale_people'][$i]."／平日$". $request['weekday'][$i] ."<br/>";
            echo "優惠人次：".$request['sale_people'][$i]."／周五$". $request['friday'][$i] ."<br/>";
            echo "優惠人次：".$request['sale_people'][$i]."／周六$". $request['saturday'][$i] ."<br/>";
            echo "優惠人次：".$request['sale_people'][$i]."／周日$". $request['sunday'][$i] ."<br/>";
            echo "起始：".$request['price_time_month_start'][$i]."-". $request['price_time_day_end'][$i] ."~結束". $request['price_time_month_end'][$i] ."-". $request['price_time_day_end'][$i] ."<br/>";
            echo "年度區間：".$request['price_year'.($year_str[$i])]."<br/>";
            echo "---------------------------------------------<br/>";
            
            //新增房價新資料
            $PriceNormal =new HotelPriceNormal;
            $PriceNormal->hotel_id =substr($hotel_id, 1);
            $PriceNormal->room_id =$request['room_list'];
            $PriceNormal->merge =floor($i/$request['totalSalePeople']);
            $PriceNormal->people =$request['sale_people'][$i];
            $PriceNormal->weekday =$request['weekday'][$i];
            $PriceNormal->friday =$request['friday'][$i];
            $PriceNormal->saturday =$request['saturday'][$i];
            $PriceNormal->sunday =$request['sunday'][$i];
            $PriceNormal->is_year =$request['price_year'.($year_str[$i])];
            $PriceNormal->start =date("Y").'-'.str_pad($request['price_time_month_start'][$i],2,'0',STR_PAD_LEFT)."-". str_pad($request['price_time_day_start'][$i],2,'0',STR_PAD_LEFT);
            $PriceNormal->end =date("Y").'-'.str_pad($request['price_time_month_end'][$i],2,'0',STR_PAD_LEFT)."-". str_pad($request['price_time_day_end'][$i],2,'0',STR_PAD_LEFT);
            $PriceNormal->creator_id =session()->get('manager_id');
            $PriceNormal->creator_name =session()->get('manager_name');
            $PriceNormal->save();
        }

        return redirect()->back();
    }
}
