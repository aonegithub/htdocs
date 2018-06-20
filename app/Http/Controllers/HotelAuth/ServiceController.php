<?php

namespace App\Http\Controllers\HotelAuth;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use App\Awugo\Auth\HotelManagers;
use App\Awugo\Auth\Hotel;
use App\Awugo\HotelAuth\HotelService;
use App\Awugo\HotelAuth\HotelServicePhotos;
use App\Awugo\Auth\Service;
use Request;
use Image;
use View;
use DB;
use Validator;
use Debugbar;
use Carbon;
use File;

class ServiceController extends Controller
{
	// 服務設施介面
    public function main($country, $hotel_id){

        // 取出飯店檔案
        $Hotel =Hotel::find(substr($hotel_id, 1));
        //取出服務設施群組
        $ServiceGroups =Service::where('parent',-1)->OrderBy('sort','desc')->get();
        //取出服務設施子項目
        $ServiceItems =Service::where('parent','!=',-1)->OrderBy('sort','desc')->get();
        //取出該飯店服務設施已勾選
        $HotelServiceID =HotelService::where('hotel_list_id',substr($hotel_id, 1))->get(['service_list_id'])->toArray();
        $HotelServiceArray =array();
        foreach($HotelServiceID as $key => $id){
            array_push($HotelServiceArray, $id['service_list_id']);
        }
        // print_r();
        // exit;
        $binding =[
            'Title' => '設施與服務',
            'Nav_ID' => 10,  //功能按鈕編號  
            'Hotel_ID' => $hotel_id,
            'Hotel' =>$Hotel,
            'HotelServiceID' =>$HotelServiceArray,
            'ServiceGroups' =>$ServiceGroups,
            'ServiceItems' =>$ServiceItems,
            'Country' => $country,
        ];
    	return view('hotel_auth.service',$binding);
    }

    // 服務設施修改處理
    public function mainPost($country, $hotel_id){
        //取得勾選值
        $request =request()->all();
        $chk_service =(!empty($request['service']))?$request['service']:'';
        // 取出飯店檔案
        $Hotel =Hotel::find(substr($hotel_id, 1));
        //取出服務設施群組
        $ServiceGroups =Service::where('parent',-1)->OrderBy('sort','desc')->get();
        //取出服務設施子項目
        $ServiceItems =Service::where('parent','!=',-1)->OrderBy('sort','desc')->get();
        //取出該飯店服務設施已勾選，先刪除舊的
        $HotelService =HotelService::where('hotel_list_id',substr($hotel_id, 1));
        $HotelService->delete();
        //刪除照片紀錄(無刪除檔)  2018-06-20 #不能刪除，如有重複的並已上傳的照片記錄，會導致誤刪
        // $HotelServicePhoto =HotelServicePhotos::whereIN('hotel_service_id', $chk_service)->where('hotel_list_id', substr($hotel_id, 1));
        // $HotelServicePhoto->delete();
        
        //寫入新勾選值
        foreach ($chk_service as $key => $service) {
            $HotelService = new HotelService;
            $HotelService->hotel_list_id =substr($hotel_id, 1);
            $HotelService->service_list_id =$service;
            $HotelService->creator_id =session()->get('hotel_manager_id');
            $HotelService->creator_name =session()->get('hotel_manager_name');
            $HotelService->save();
        }

        // return 1;
        return redirect()->to('./service');
    }
    
}
