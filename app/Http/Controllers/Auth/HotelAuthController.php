<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Awugo\Auth\Authority;
use App\Awugo\Auth\Managers;
use App\Awugo\Auth\Hotel;
use App\Awugo\Auth\HotelAuthority;
use App\Awugo\Auth\HotelManagers;
use Carbon\Carbon;
// use Illuminate\Http\Request;
use Image;
use View;
use DB;
use Validator;

class HotelAuthController extends Controller
{
    /**
     * 飯店權限清單.
     */
    public function main(Request $request,$country,$hotel_key)
    {
        $manager_list =HotelManagers::where('hotel_list_id',$hotel_key)->get();
        //
        return 1;
    }
}
