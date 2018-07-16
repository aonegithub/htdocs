@extends('hotel_auth.main_layout')

<!-- 標題 -->
@section('title', $Title)
<!-- 飯店名稱 -->
@section('hotel_name', $Hotel->name)
@section('hotel_id', $Hotel->nokey)
@section('main_fun', 1)
@section('sub_fun', 'price_normal')

@section('content')
@if(!is_null(session()->get('controll_back_msg')))
<!-- 隱藏區塊 -->
@endif

<div class="row" style="width: 98%;margin-left: 1%;display:inline-block;">
	<form action="price_normal" method="POST">
		{{ csrf_field() }}
	<div style="float:left;width:580px;">
		選擇房型：
		<select name="room_list" id="room_list" style="width: 250px;" onchange="chgRoom()">
			@foreach($RoomSelect as $key => $room)
			<option value="{{$room->nokey}}" @if($Room_Key==$room->nokey)selected=''@endif>{{$room->name}}</option>
			@endforeach
		</select>
		<a href="javascript:void(0)" onclick="redirectDetail()" >房型詳細資料</a>　幣別NT$:(TWD)
	</div>
	<div style="float:right;width:192px;margin-bottom: 10px;">
		<a href="javascript:chgMod()" class="btn btn-primary btn-sm" style="@if($BrowseTag!=1)display:none;@endif">修改房價</a>
		<a href="javascript:clonePrice()" class="btn btn-primary btn-sm">新增區間房價+</a>
	</div>
	<div style="clear:both;">
		<table width="100%" id="price_table" border="0">
			<tr bgcolor="#FBEEC7">
				<td align="center">人數</td>
				<td align="center">週一~週四</td>
				<td align="center">週五</td>
				<td align="center">週六</td>
				<td align="center">週日</td>
				<td align="center">適用區間</td>
			</tr>
			@foreach($PriceNormal as $key => $normal)
			<tr class="@if(count($RoomSaleArray)>=($key+1))cloneTr @endif a{{floor($key/count($RoomSaleArray)+1)}} " @if($key>0 && ($key+1)%count($RoomSaleArray)==0)style="border-bottom: 5px solid #00366d;" @endif>
				<td width="10%" align="center">{{$normal->people}}<input name="sale_people[]" id="sale_people[]" type="text" value="{{$normal->people}}" style="display:none;"></td>
				<td width="15%" align="center">@if($BrowseTag!=1)<input style="width:100%;" name="weekday[]" id="weekday[]" class="weekday" type="text" value="{{$normal->weekday}}">@else{{$normal->weekday}}@endif</td>
				<td width="15%" align="center">@if($BrowseTag!=1)<input style="width:100%;" name="friday[]" id="friday[]" class="friday" type="text" value="{{$normal->friday}}">@else{{$normal->friday}}@endif</td>
				<td width="15%" align="center">@if($BrowseTag!=1)<input style="width:100%;" name="saturday[]" id="saturday[]" class="saturday" type="text" value="{{$normal->saturday}}">@else{{$normal->saturday}}@endif</td>
				<td width="15%" align="center">@if($BrowseTag!=1)<input style="width:100%;" name="sunday[]" id="sunday[]" class="sunday" type="text" value="{{$normal->sunday}}">@else{{$normal->sunday}}@endif</td>
				<td width="30%" align="center" rowspan="{{count($RoomSaleArray)}}" style="@if($key>0 && $key%count($RoomSaleArray)!=0)display:none;@endif @if($key%count($RoomSaleArray)==0) border-bottom: 5px solid #00366d; @endif">
					<input style="display:none;" type="radio" data-ser="{{$normal->people}}{{$normal->merge}}" id="price_year{{$normal->people}}{{$normal->merge}}" name="price_year{{$normal->people}}{{$normal->merge}}" value="0">
					<input style="display:none;" type="radio" value="1" checked="" data-ser="{{$normal->people}}{{$normal->merge}}" id="price_year{{$normal->people}}{{$normal->merge}}" name="price_year{{$normal->people}}{{$normal->merge}}">適用區間
					@if($BrowseTag!=1)
					<select data-ser="{{$normal->people}}{{$normal->merge}}" id="price_time_month_start[]" name="price_time_month_start[]" class="st{{floor($key/count($RoomSaleArray)+1)}}" onchange="chgDate({{floor($key/count($RoomSaleArray)+1)}},this,'st')" >
						@for($i=1;$i<=12;$i++)
						<option value="{{$i}}" @if(substr($normal->start,5,2)==str_pad($i,2,'0',STR_PAD_LEFT))selected=""@endif>{{str_pad($i,2,'0',STR_PAD_LEFT)}}</option>
						@endfor
					</select>
					@else{{str_pad(substr($normal->start,5,2),2,'0',STR_PAD_LEFT)}}@endif
					月
					@if($BrowseTag!=1)
					<select data-ser="{{$normal->people}}{{$normal->merge}}" id="price_time_day_start[]" name="price_time_day_start[]" class="sd{{floor($key/count($RoomSaleArray)+1)}}" onchange="chgDate({{floor($key/count($RoomSaleArray)+1)}},this,'sd')">
						@for($i=1;$i<=31;$i++)
						<option value="{{$i}}" @if(substr($normal->start,8,2)==str_pad($i,2,'0',STR_PAD_LEFT))selected=""@endif>{{str_pad($i,2,'0',STR_PAD_LEFT)}}</option>
						@endfor
					</select>
					@else{{str_pad(substr($normal->start,8,2),2,'0',STR_PAD_LEFT)}}@endif
					日
					至
					@if($BrowseTag!=1)
					<select data-ser="{{$normal->people}}{{$normal->merge}}" id="price_time_month_end[]" name="price_time_month_end[]" class="et{{floor($key/count($RoomSaleArray)+1)}}" onchange="chgDate({{floor($key/count($RoomSaleArray)+1)}},this,'et')">
						@for($i=1;$i<=12;$i++)
						<option value="{{$i}}" @if(substr($normal->end,5,2)==str_pad($i,2,'0',STR_PAD_LEFT))selected=""@endif>{{str_pad($i,2,'0',STR_PAD_LEFT)}}</option>
						@endfor
					</select>
					@else{{str_pad(substr($normal->end,5,2),2,'0',STR_PAD_LEFT)}}@endif
					月
					@if($BrowseTag!=1)
					<select id="price_time_day_start[]" name="price_time_day_end[]" class="ed{{floor($key/count($RoomSaleArray)+1)}}" onchange="chgDate({{floor($key/count($RoomSaleArray)+1)}},this,'ed')">
						@for($i=1;$i<=31;$i++)
						<option value="{{$i}}" @if(substr($normal->end,8,2)==str_pad($i,2,'0',STR_PAD_LEFT))selected=""@endif>{{str_pad($i,2,'0',STR_PAD_LEFT)}}</option>
						@endfor
					</select>
					@else{{str_pad(substr($normal->end,8,2),2,'0',STR_PAD_LEFT)}}@endif
					日
					<a href="javascript:delTime('a{{floor($key/count($RoomSaleArray)+1)}}')" class="delTime">刪除此區間</a>
				</td>

			</tr>
			@endforeach
		</table>
		<input type="text" value="{{$MergeLastNo+1}}" name="totalPriceSet" id="totalPriceSet" style="display:none;">
		<input type="text" value="{{count($RoomSaleArray)}}" name="totalSalePeople" id="totalSalePeople" style="display:none;">
		<div class="col-md-4 text-center" style="margin: auto;margin-top: 10px;">
			<button type="submit" class="btn btn-primary btn-sm" style="@if($BrowseTag ==1)display:none;@endif">儲存房價</button>
		</div>
	</div>
	</form>
</div>
<!-- main -->

@endsection

@section('instyle')
table {  
  border: 1px solid grey;  
  border-collapse: collapse;  
}  
tr, td {  
  border: 1px solid grey;  
}
td > input{
	border: 1px solid #cecece;
}

@endsection

<!-- js獨立區塊腳本 -->
@section('custom_script')

//
function chgDate(no,obj, typeName){
	$('.'+typeName+no).val($(obj).val());
}

//導向客房詳細
function redirectDetail(){
	window.location.href='room_set/'+$("#room_list :selected").val();
}
//切換編輯模式
function chgMod(){
	window.location.href='price_normal?r={{$RoomID}}&b=0';
}
//切換客房
function chgRoom(){
	window.location.href='price_normal?r='+$("#room_list :selected").val()+'&b={{$BrowseTag}}';
}

//刪除區間
function delTime(time){
	$("."+ time).remove();
	$("#totalPriceSet").val((trNo1+1)-1);
	trNo1--;
}

//複製區間房價表格(除單選紐，其餘改用陣列)
trNo1 ={{$MergeLastNo}};
st=sd=et=ed=0
function clonePrice(){
	trNo1++;
	tr_clone =$(".cloneTr").clone().removeClass("cloneTr").removeClass("a1").addClass("a"+(trNo1+1));
	//房價空白
	tr_clone.children().find('.weekday').attr('value',"");
	tr_clone.children().find('.friday').attr('value',"");
	tr_clone.children().find('.saturday').attr('value',"");
	tr_clone.children().find('.sunday').attr('value',"");
	//
	if(et <12 && ed<=31){
		if(st==0){
			st =parseInt(tr_clone.children().find('.et1').val());
			sd =parseInt(tr_clone.children().find('.sd1').val());
			et =parseInt(tr_clone.children().find('.et1').val());
			ed =parseInt(tr_clone.children().find('.ed1').val());
		}
		tr_clone.children().find('.st1').val(et+1);
		tr_clone.children().find('.sd1').val(1);
		tr_clone.children().find('.et1').val(et+1);
		tr_clone.children().find('.ed1').val(31);
		et++;
		st++;
	}
	//
	tr_clone.children().find('.delTime').attr('href',"javascript:delTime('a"+ (trNo1+1) +"')").show();
	tr_clone.children().find("select[class^='st']").removeClass().addClass('st'+(trNo1+1));
	tr_clone.children().find("select[class^='st']").attr('onchange',"chgDate("+(trNo1+1)+",this,'st')");
	tr_clone.children().find("select[class^='sd']").removeClass().addClass('sd'+(trNo1+1));
	tr_clone.children().find("select[class^='sd']").attr('onchange',"chgDate("+(trNo1+1)+",this,'sd')");
	tr_clone.children().find("select[class^='et']").removeClass().addClass('et'+(trNo1+1));
	tr_clone.children().find("select[class^='et']").attr('onchange',"chgDate("+(trNo1+1)+",this,'et')");
	tr_clone.children().find("select[class^='ed']").removeClass().addClass('ed'+(trNo1+1));
	tr_clone.children().find("select[class^='ed']").attr('onchange',"chgDate("+(trNo1+1)+",this,'ed')");
	//
	@foreach($RoomSaleArray as $key => $sale)
		tr_clone.children().find("input[name^='price_year{{$sale}}']").attr('id','price_year'+($("input[name^='price_year{{$sale}}']").data('ser')+trNo1)).attr('name','price_year'+($("input[name^='price_year{{$sale}}']").data('ser')+trNo1));
		//
	@endforeach
	//
	$("#price_table").append(tr_clone);
	$("#totalPriceSet").val((trNo1+1));
}


@endsection

<!-- jQuery ready 狀態內閉包內插 -->
@section('custom_ready_script')

$(".delTime").eq(0).hide();


@endsection