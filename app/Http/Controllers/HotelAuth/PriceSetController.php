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
    // 
    public function price($country, $hotel_id){
        
        // 
        $Hotel =Hotel::find(substr($hotel_id, 1));

        $binding =[
            'Title' => '全部房價',
            'Nav_ID' => 10,  //  
            'Hotel_ID' => $hotel_id,
            'Hotel' =>$Hotel,
            'Country' => $country,
        ];
        return view('hotel_auth.price',$binding);
    }

    // 
    public function price_normal($country, $hotel_id){
        $room_id =RQ::input('r');
        $browseTag =(RQ::input('b')!=1)?0:1;
        // 
        $RoomList =null;
        if($room_id !=null){
            $RoomList =HotelRoomSet::where('hotel_id', substr($hotel_id, 1))->where('nokey', $room_id)->get();
        }else{
            $RoomList =HotelRoomSet::where('hotel_id', substr($hotel_id, 1))->get();
        }
        //
        $RoomSelect =HotelRoomSet::where('hotel_id', substr($hotel_id, 1))->get();
        // 
        $Hotel =Hotel::find(substr($hotel_id, 1));
        // 
        $RoomSale =substr($RoomList[0]->sale_people, 0, -1);

        $RoomSaleArray =explode(',', $RoomSale);
        // dd($RoomSaleArray);
        // exit;
        // 
        $room_key=$RoomList[0]->nokey;
        if($room_id !=null){
            $room_key=$room_id;
        }
        $PriceNormal =HotelPriceNormal::where('hotel_id',substr($hotel_id, 1))->where('room_id',$room_key)->OrderBy('merge','asc')->OrderBy('people','desc')->get();
        // 
        $MergeLastNo =0;
            $MergeNo =HotelPriceNormal::where('hotel_id',substr($hotel_id, 1))->where('room_id',$room_key)->OrderBy('merge','desc')->select('merge')->first();
        if(isset($MergeNo)){
            $MergeLastNo =$MergeNo->merge;
        }

        // dd($RoomArray);
        // dd($MergeNo->merge);
        // exit;
        $binding =[
            'Title' => '全部房價',
            'Nav_ID' => 10,   
            'Hotel_ID' => $hotel_id,
            'Hotel' =>$Hotel,
            'Room_Key' =>$room_key,
            'RoomList' =>$RoomList,
            'RoomSelect' =>$RoomSelect,
            'RoomSaleArray' =>$RoomSaleArray,
            'PriceNormal' =>$PriceNormal,
            'MergeLastNo' =>$MergeLastNo,
            'BrowseTag' =>$browseTag,
            'RoomID' =>$room_id,
            'Country' => $country,
        ];
        return view('hotel_auth.price_normal',$binding);
    }

    // 一般房價(接收資料)
    public function price_normal_post($country, $hotel_id){
        //寫入登入資訊
        $request =request()->all();
        $room_id =RQ::input('r');
        //
        $totalSet =$request['totalPriceSet']*$request['totalSalePeople'];
        $j=0;
        $year_str=array();
        // dd(count($request['sale_people']));
        // exit;
        for ($k=0;$k<count($request['sale_people']);$k++) {
                if(($k)==$request['totalSalePeople']){
                    $j++;
                }
                array_push($year_str, $request['sale_people'][$k].''.$j);
        }
        //刪除舊資料
            $PriceNormal =HotelPriceNormal::where('hotel_id',substr($hotel_id, 1))->where('room_id',$request['room_list']);
            $PriceNormal->delete();
        for ($i=0;$i<count($request['sale_people']);$i++) {

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
            $PriceNormal->is_year =1;
            $PriceNormal->start =date("Y").'-'.str_pad($request['price_time_month_start'][$i],2,'0',STR_PAD_LEFT)."-". str_pad($request['price_time_day_start'][$i],2,'0',STR_PAD_LEFT);
            $PriceNormal->end =date("Y").'-'.str_pad($request['price_time_month_end'][$i],2,'0',STR_PAD_LEFT)."-". str_pad($request['price_time_day_end'][$i],2,'0',STR_PAD_LEFT);
            $PriceNormal->creator_id =session()->get('manager_id');
            $PriceNormal->creator_name =session()->get('manager_name');
            $PriceNormal->save();
        }

        return redirect()->to("/tw/auth/h".substr($hotel_id, 1)."/price_normal?r=".$room_id."&b=1");
    }
}
