@extends('hotel_auth.main_layout')

<!-- 標題 -->
@section('title', $Title)
<!-- 飯店名稱 -->
@section('hotel_name', $Hotel->name)

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="input-group input-group">
            <div class="input-group-prepend">
                <span class="input-group-text input-group-custom">飯店名稱</span>
            </div>
            <input id="name" name="name" type="text" value="{{$Hotel->name}}" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        <div class="input-group input-group">
            <div class="input-group-prepend">
                <span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">相關證照</span>
            </div>
            <div class="checkbox checkbox-primary" style="padding-top:5px;">
                <input id="license_hotel" name="license_hotel" type="checkbox" value="1"@if($Hotel->license_hotel==1) checked="checked" @endif>
                <label for="license_hotel">合法旅館
                </label>
            </div>
            <div class="checkbox checkbox-primary" style="padding-top:5px;">
                <input id="license_homestay" name="license_homestay" type="checkbox" value="1"@if($Hotel->license_homestay==1) checked="checked" @endif>
                <label for="license_homestay">合法民宿
                </label>
            </div>
            <div class="checkbox checkbox-primary" style="padding-top:5px;">
                <input id="license_hospitable" name="license_hospitable" type="checkbox" value="1"@if($Hotel->license_hospitable==1) checked="checked" @endif>
                <label for="license_hospitable">好客民宿
                </label>
            </div>
        </div>
    </div>
</div>
<!-- ** -->
<div class="row" style="margin-top:10px;">
    <div class="col-md-6">
        <div class="input-group input-group">
          <div class="input-group-prepend">
            <span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">官方網站</span>
          </div>
          <input type="text" id="url" name="url" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="{{$Hotel->url}}" placeholder="輸入完整網址">
        </div>
    </div>
    <div class="input-group input-group col-md-6">
        <div class="input-group-prepend">
            <span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">飯店類型</span>
        </div>
        <select class="form-control col-md-5" id="type_scale" name="type_scale">
            <option value='國際觀光飯店'@if($Hotel->type_scale=='國際觀光飯店') selected="" @endif>國際觀光飯店</option>
            <option value='商務休閒飯店'@if($Hotel->type_scale=='商務休閒飯店') selected="" @endif>商務休閒飯店</option>
            <option value='汽車旅館'@if($Hotel->type_scale=='汽車旅館') selected="" @endif>汽車旅館</option>
            <option value='民宿'@if($Hotel->type_scale=='民宿') selected="" @endif>民宿</option>
            <option value='露營'@if($Hotel->type_scale=='露營') selected="" @endif>露營</option>
            <option value='國際觀光飯店／商務休閒飯店'@if($Hotel->type_scale=='國際觀光飯店／商務休閒飯店') selected="" @endif>國際觀光飯店／商務休閒飯店</option>
            <option value='商務休閒飯店／汽車旅館'@if($Hotel->type_scale=='商務休閒飯店／汽車旅館') selected="" @endif>商務休閒飯店／汽車旅館</option>
            <option value='民宿／露營'@if($Hotel->type_scale=='民宿／露營') selected="" @endif>民宿／露營</option>
        </select>
        <div class="input-group-prepend">
            <span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">飯店星級</span>
        </div>
        <select class="form-control col-md-2" id="type_level" name="type_level">
            <option value='0'@if($Hotel->type_level==0) selected="" @endif>☆</option>
            <option value='1'@if($Hotel->type_level==1) selected="" @endif>★</option>
            <option value='2'@if($Hotel->type_level==2) selected="" @endif>★★</option>
            <option value='3'@if($Hotel->type_level==3) selected="" @endif>★★★</option>
            <option value='4'@if($Hotel->type_level==4) selected="" @endif>★★★★</option>
            <option value='5'@if($Hotel->type_level==5) selected="" @endif>★★★★★</option>
        </select>
        <div class="input-group-prepend">
            <span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">房間總數</span>
          </div>
          <input id="type_room" name="type_room" type="text" class="form-control col-md-1" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="" value="{{$Hotel->type_room}}">
    </div>
</div>
<!-- ** -->
<div class="row" style="margin-top:10px;">
    <div class="col-md-6">
        <div class="input-group input-group">
          <div class="input-group-prepend">
            <span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">飯店地址</span>
          </div>
          <select class="form-control" id="area_level1" name="area_level1" style="display:none">
              <option value='1'>台灣</option>
          </select>
          <select class="form-control" id="area_level2" name="area_level2" onchange="chg_area(this,2)">
              <option value='-1'>縣市</option>
              @foreach($Areas_level2 as $key => $area2)
                    <option value='{{$area2->nokey}}'@if($Hotel->area_level2==$area2->nokey) selected="" @endif>{{$area2->area_name}}</option>
              @endforeach
          </select>
          <select class="form-control" id="area_level3" name="area_level3" onchange="chg_zip_code(this,'zip_code')">
              <option value='-1'>區域</option>
              @foreach($Addr_level3 as $key => $addr3)
                <option value='{{$addr3->nokey}}'@if($Hotel->area_level3==$addr3->nokey) selected="" @endif>{{$addr3->area_name}}</option>
              @endforeach
          </select><br/>
            <div class="input-group input-group-sm col-md-2"> 
              <input id="zip_code" name="zip_code" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="郵遞區號" value="{{$Hotel->zip_code}}">
            </div>
            <!-- ** -->
            <div class="input-group input-group-sm col-md-6">
              <input id="address" name="address" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="請輸入地址" value="{{$Hotel->address}}">
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="input-group input-group">
            <div class="input-group-prepend">
                <span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">開立發票</span>
            </div>
            <div class="radio radio-inline align-middle" style="padding-top: 5px">
                <input type="radio" id="invoice0" value="0" name="invoice"@if($Hotel->invoice==0) checked="checked" @endif>
                <label for="invoice0">可</label>
            </div>
            <div class="radio radio-inline align-middle" style="padding-top: 5px">
                <input type="radio" id="invoice1" value="1" name="invoice"@if($Hotel->invoice==1) checked="checked" @endif>
                <label for="invoice1">僅開立收據</label>
            </div>
            <div class="radio radio-inline align-middle" style="padding-top: 5px">
                <input type="radio" id="invoice2" value="2" name="invoice"@if($Hotel->invoice==2) checked="checked" @endif>
                <label for="invoice2">皆無</label>
            </div>
        </div>
    </div>
</div>
@endsection


<!-- js獨立區塊腳本 -->
@section('custom_script')
//切換三級選單取得郵遞區號
function chg_zip_code(obj,target){
    $('#'+target).prop('disabled', true);
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: '../area_get_zipcode',
        data: {nokey:$(obj).val()},
        success: function(data) {
            $('#'+target).val("");
            $('#'+target).val(data[0]['zip_code']);
            $('#'+target).prop('disabled', false);
        }
    });
}
// 切換選項時，level為該選項之級別值
function chg_area(sel_obj, level){
    $("#area_level"+(level+1)).prop('disabled', true);
    $("#area_level"+(level+1)+" option").remove();
    sel_val =$(sel_obj).val();

    if(sel_val == '-1'){
        sel_val =$("#area_level"+(level-1)).val()
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: '../manager/area_get',
        data: {level:sel_val},
        success: function(data) {
            //填入下一級選項
            fill_area(data,level);
        }
    });
}

//填入下級選項
function fill_area(data, level){
    if(level <=4){
        $("#area_level"+(level+1)+" option[value!='-1']").remove();
        $("#area_level"+(level+1)).append($('<option>', {
            value: -1,
            text: '區域'
        }));
        if($("#area_level"+level).val() !='-1'){
            for(i=0; i< data.length; i++){
                $("#area_level"+(level+1)).append($('<option>', {
                    value: data[i]['nokey'],
                    text: data[i]['area_name']
                }));
            }
        }
        $("#area_level"+(level+1)).prop('disabled', false);
        //alert(data['1']['area_name']);
        //$("#area_level"+level+" option[value!='-1']").remove();
    }
}
@endsection