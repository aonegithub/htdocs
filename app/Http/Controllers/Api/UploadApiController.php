<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use App\Awugo\Picture;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Image;
use View;
use DB;
use Validator;
use Debugbar;

class UploadApiController extends Controller
{
	private $photos_path;

    public function __construct()
    {
        $this->photos_path = public_path('/photos');
    }
	public function imageUploadView($country){
		return view('upload_temp');
	}
	//上傳圖片處理
    public function imageUpload(Request $request, $country){
    	// $request = request()->all();
    	ini_set('memory_limit', '256M');
    	//各尺寸縮圖資料夾
    	$s1_dir =60;
    	$s2_dir =250;
    	$s3_dir =800;
    	//
    	$photos = $request->file('file');

        if (!is_array($photos)) {
            $photos = [$photos];
        }

        if (!is_dir($this->photos_path)) {
            mkdir($this->photos_path, 0777);
        }

        for ($i = 0; $i < count($photos); $i++) {
            $photo = $photos[$i];
            $name = sha1(date('YmdHis') . str_random(30));
            $save_name = $name . '.' . $photo->getClientOriginalExtension();
            $resize_name = $name . str_random(2) . '.' . $photo->getClientOriginalExtension();
            //縮圖1  60
            Image::make($photo)
                ->resize($s1_dir, null, function ($constraints) {
                    $constraints->aspectRatio();
                })
                ->save($this->photos_path . '/'.$s1_dir.'/' . $save_name);
                // $photo->move($this->photos_path. '/'.$s1_dir.'/', $save_name);
            //縮圖2  250
            Image::make($photo)
                ->resize($s2_dir, null, function ($constraints) {
                    $constraints->aspectRatio();
                })
                ->save($this->photos_path . '/'.$s2_dir.'/' . $save_name);
                // $photo->move($this->photos_path. '/'.$s2_dir.'/', $save_name);
            //縮圖3  800
            Image::make($photo)
                ->resize($s3_dir, null, function ($constraints) {
                    $constraints->aspectRatio();
                })
                ->save($this->photos_path . '/'.$s3_dir.'/' . $save_name);
                // $photo->move($this->photos_path. '/'.$s3_dir.'/', $save_name);
            //
            $photo->move($this->photos_path, $save_name);

            // $upload = new Upload();
            // $upload->filename = $save_name;
            // $upload->resized_name = $resize_name;
            // $upload->original_name = basename($photo->getClientOriginalName());
            // $upload->save();
        }
        return response()->json([
            'message' => '上傳成功'
        ], 200);
    }
}
