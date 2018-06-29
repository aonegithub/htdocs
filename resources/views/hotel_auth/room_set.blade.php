@extends('hotel_auth.main_layout')

<!-- 標題 -->
@section('title', $Title)
@section('sub_fun', 'room_set')
<!-- 飯店名稱 -->
@section('hotel_name', $Hotel->name)

@section('content')

    <div class="modal fade" id="okAlert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">套用下列範例名稱</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <ul>
                @foreach($RoomNames as $key => $name)
                  <li><a href="javascript:apply_name('{{$name->name}}')">{{$name->name}}</a></li>
                @endforeach
              </ul>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">OK！</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="people_sel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">選擇優惠人次</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <ul id="sale_people_ckb">
                
              </ul>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">OK！</button>
          </div>
        </div>
      </div>
    </div>


<form action="{{$RoomSet->nokey}}" method="POST">
	{{ csrf_field() }}
	<table width="98%" style="margin: auto;">
		<tr>
			<td width="60%">
          <div class="field_div"><span class="field_title">房型名稱：</span><input type="text" value="@if($RoomSet!=null){{$RoomSet->name}}@endif" style="width:350px;" id="name" name="name">
            <a href="javascript:toggle_name()">套用</a></div>   
          <div class="field_div"><div class="field_title" style="width: 80px;
    float: left;">床型選擇：</div><div id="beds_select_clone"><select name="beds[]" id="beds" style="width:350px;">
            @foreach($Beds as $key => $bed)
              <option value="{{$bed->nokey}}">{{$bed->name}}</option>
            @endforeach
            </select>
            數量：
              <select name="count[]" id="count" style="width:50px;">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
              </select>
          </div>
            
          </div>
          <div class="field_div" style="margin-left: 80px;">
                <ul id="bed_select" style="padding-left: 0px;">
                  
                </ul>
                <a href="javascript:add_bed()">增加床型</a>
          </div>
          <div class="field_div"><span class="field_title">標準住宿：</span><input type="text" style="width:100px;" onkeyup="chg_room_people(this)" id="min_people" name="min_people" value="@if($RoomSet!=null){{$RoomSet->min_people}}@endif">人<!--／最多<input type="text"  style="width:150px;" id="max_people" name="max_people" value="@if($RoomSet!=null){{$RoomSet->max_people}}@endif">人-->／
            <div class="checkbox checkbox-primary" style="padding-top:5px;display: inline-block;">
              <input type="checkbox" class="checkbox" value="1" id="sale" name="sale" style="display: none;" onchange="chg_sale(this)" @if($RoomSet->sale) checked @endif>
              <label for="sale">低於標準住宿人數<span id="room_people">{{$RoomSet->min_people}}</span>人.可按住宿人數遞減提供優惠價格<a id="people_sel_link" href="javascript:people_sel()" style="display:none;">按此勾選優惠人次</a></label>
            </div>
          </div>  
          <div class="field_div" id="sale_div" style="display:none">
            <span class="field_title">優惠人次：</span>
            <ul id="sale_people" style="list-style: none;position: relative;top: -24px;">
            </ul>
            <input type="text" name="sale_people_csv" id="sale_people_csv" style="display: none;">
          </div> 
          <div class="field_div">
            <span class="field_title">總房間數：</span><input type="text"  style="width:100px;" id=room_count" name="room_count" value="@if($RoomSet!=null){{$RoomSet->room_count}}@endif">
            <span class="field_title">開放間數：</span><input type="text"  style="width:100px;" id=room_open_count" name="room_open_count" value="@if($RoomSet!=null){{$RoomSet->room_open_count}}@endif">
            <span class="field_title">面積：</span><input type="text"  style="width:100px;" id=room_area" name="room_area" value="@if($RoomSet!=null){{$RoomSet->room_area}}@endif">坪
          </div>
          <div class="field_div"><span class="field_title">房間特色：</span><input type="text" style="width:90%;color: red;" id="room_feature" name="room_feature" value="@if($RoomSet!=null){{$RoomSet->room_feature}}@endif"></div>
          <!-- 勾選 -->
          @foreach($DeviceGroup as $key => $group)
            <div class="row service_item" style="margin:0px;margin-bottom: 20px;">
              <span class="field_title" style="width:90%;">{{$group->service_name}}</span>
              @foreach($DeviceItem as $j => $item)
              @if($item->parent == $group->nokey)
              <div class="col-md-3 service_item">
                <div class="input-group">
                  <div class="checkbox checkbox-primary" style="padding-top:5px;">
                    <input onchange="check_service(this)" type="checkbox" class="checkbox" value="{{$item->nokey}}" id="service{{$item->nokey}}" name="service[]" style="display: none;" @if(in_array($item->nokey,$RoomDevice)) checked="" @endif>
                    <label for="service{{$item->nokey}}">{{$item->service_name}}</label>
                  </div>
                </div>
              </div>
              @endif
              @endforeach
            </div>
          @endforeach
          <!-- 勾選 -->
      </td>
			<td width="40%" valign="top" style="background-color: green;">
        <div id="photo_big" name="photo_big"></div>
        <div id="photo_list" name="photo_list">
          <ul>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
          </ul>
        </div>
        <div id="photo_btn" name="photo_btn">
          <form id="photo_form" method="post" enctype="multipart/form-data" action="../room_set_upload/{{$RoomID}}">
            <input id="photo_browser" type="file" name="service_photo" onchange="autoUpload()" style="display: none;">
            <input type="button" value="上傳照片" style="margin-bottom: 10px;" onclick="openBrowser()">
          </form>
        </div>
      </td>
		</tr>
	</table>
	<button class="btn btn-lg btn-primary btn-block" type="submit" style="width: 95%;margin:auto">儲存設定</button>
</form>
<!-- main -->

@endsection

@section('instyle')
#photo_list >ul{
  padding:0px;
}
#photo_list > ul >li{
  list-style-type: none;
  display: inline-block;
  width:85px;
  height:85px;
  overflow:hidden;
  background-color:blue;
  margin: 2px;
}
#photo_big{
  width:650px;
  height:650px;
  background-color:red;
}
#sale_people{
  margin-left: 40px;
}
#sale_people >li{
  list-style-type: none;
  display: inline-block;
}
#bed_select > li{
  list-style-type: none;
  margin-bottom: 5px;
}

.field_div{
  margin-bottom:10px;
}
.field_title{
  color:green;
  font-weight:bold;
}
.service_group{
	font-weight:bold;
	padding:10px;
}
.service_item{
	
}
.service_select {
	color:#E5670D;
}
@endsection

<!-- js獨立區塊腳本 -->
@section('custom_script')
//$('.checkbox :checked').parent().addClass("service_select");

//開啟上傳視窗
function openBrowser(){
  $('#photo_browser').click();
}
//自動提交上傳
function autoUpload(){
  $('#photo_form').submit();
}

//新增選擇床型
function add_bed(){
  $("#bed_select").append("<li>"+$("#beds_select_clone").html()+"<img src='/pic/del.png' width='16' style='cursor:pointer;' onclick='del_bed(this)'></li>");
}

//刪除已選擇床型
function del_bed(obj){
  $(obj).parent().remove();
  /*del_val =$(obj).parent().find('input').val()+',';
  replace_text =$("#bed_csv").val().replace(del_val, '');
  $("#bed_csv").val(replace_text);*/
}

//變化已填入人數
function chg_room_people(obj){
  $("#room_people").empty().text($(obj).val());
}

//勾選紐切換
function chg_sale(obj){
  if($(obj).prop('checked')){
    $("#people_sel_link").show();
    $("#sale_div").show();
  }else{
    $("#people_sel_link").hide();
    $("#sale_div").hide();
  }
}

//勾選優惠人次視窗
function people_sel(){
  $('#people_sel').modal("toggle");
  opt_count =parseInt($("#min_people").val())-1;
  $("#sale_people_ckb").empty();
  for(;opt_count>0;opt_count--){
    $("#sale_people_ckb").append("<li><input class='ckb' onchange=\"chg_sale_people_ul(this)\" type=\"checkbox\" value=\""+ opt_count +"\">"+ opt_count +"人</li>");
  }  
  //還原勾選優惠人數
  @if($RoomSet->sale)
    restore_sale();
  @endif
}

//反應勾選人次sale_people_csv
function chg_sale_people_ul(obj){
  $("#sale_people").empty();
  $("#sale_people_csv").val('');
  sel_str='';
  $("#sale_people_ckb > li :checked").each(function(){
    $("#sale_people").append("<li>"+ $(this).val() +"人｜</li>");
    sel_str +=$(this).val()+',';
  });
  $("#sale_people_csv").val(sel_str);
}

//套用名稱(執行後關閉視窗)
function apply_name(name){
  $('#name').empty().val(name);
  $("#okAlert .close").click();
}

//打開切換名稱視窗
function toggle_name(){
  $('#okAlert').modal("toggle");
}

//判斷是否勾選，勾選變藍字
function check_service(obj){
	if($(obj).prop("checked")){
		$(obj).parent().addClass("service_select").find("span").show();
	}else{
		$(obj).parent().removeClass("service_select").find("span").hide();
	}
}

//還原勾選優惠人數
function restore_sale(){
  sale_csv ="{{substr($RoomSet->sale_people,0,-1)}}";
  sale_array =sale_csv.split(',');
  for(i=0; i<sale_array.length;i++){
  $("#sale_people_ckb > li > input").each(function(){
      if($(this).val() ==sale_array[i]){
        $(this).prop("checked",true);
        $(this).trigger("change");
      }
    });
  }
}

@endsection

<!-- jQuery ready 狀態內閉包內插 -->
@section('custom_ready_script')
//還原勾選優惠人數
@if($RoomSet->sale)
  sale_csv ="{{substr($RoomSet->sale_people,0,-1)}}";
  sale_array =sale_csv.split(',');
  $("#people_sel_link").show();
  $("#sale_div").show();
  for(i=0; i<sale_array.length;i++){
    $("#sale_people").append("<li>"+ sale_array[i] +"人｜</li>");
  }
  $("#sale_people_csv").val(sale_csv+',');
@endif
//還原選擇床型
@foreach($Beds_Type as $key => $bed)
  @if($key !=0)
$("#bed_select").append("<li>"+$("#beds_select_clone").html()+"<img src='/pic/del.png' width='16' style='cursor:pointer;' onclick='del_bed(this)'></li>");
  @endif
@endforeach
@foreach($Beds_Type as $key => $bed)
//還原設定值
$("#bed_select li").eq({{$key-1}}).find('select option[value={{$bed->bed_id}}]').attr('selected','selected');
$("#bed_select li").eq({{$key-1}}).find('#count option[value={{$bed->count}}]').attr('selected','selected');
@endforeach
//還原第一組床型
$("#beds_select_clone").find('select option[value={{$Beds_Type[0]->bed_id}}]').attr('selected','selected');
$("#beds_select_clone").find('#count option[value={{$Beds_Type[0]->count}}]').attr('selected','selected');


//將已勾選的設施服務加上藍字
$(".service_item > .input-group > .checkbox > input:checkbox:checked").parent().addClass("service_select").find("span").show();


//啟動lightbox效果
$(".fancybox").fancybox({
	'width': 850,
    'height': 250,
    'transitionIn': 'elastic', // this option is for v1.3.4
    'transitionOut': 'elastic', // this option is for v1.3.4
    // if using v2.x AND set class fancybox.iframe, you may not need this
    'type': 'iframe',
    // if you want your iframe always will be 600x250 regardless the viewport size
    'fitToView' : false,  // use autoScale for v1.3.4
    afterClose  : function() { 
    	//關閉後自動重整
        //window.location.reload();
    }
});
//停用完成跳出確認
    @if(!is_null(session()->get('controll_back_msg')))
        $('#okAlert').modal("toggle");
    @endif
@endsection