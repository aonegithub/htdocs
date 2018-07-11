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
		<select name="room_list" id="room_list" style="width: 250px;">
			@foreach($RoomList as $key => $room)
			<option value="{{$room->nokey}}">{{$room->name}}</option>
			@endforeach
		</select>
		<a href="room_set">房型詳細資料</a>　幣別NT$:(TWD)
	</div>
	<div style="float:right;width:120px;">
		<a href="javascript:clonePrice()" class="btn btn-primary btn-sm">新增區間房價+</a>
	</div>
	<div style="clear:both;">
		<table width="100%" id="price_table">
			<tr>
				<td>人數</td>
				<td>周一~周四</td>
				<td>周五</td>
				<td>週六</td>
				<td>週日</td>
				<td>適用區間</td>
				<td></td>
				<td></td>
			</tr>
			@foreach($PriceNormal as $key => $normal)
			<tr @if(count($RoomSaleArray)>=($key+1))class="cloneTr"@endif>
				<td>{{$normal->people}}<input name="sale_people[]" id="sale_people[]" type="text" value="{{$normal->people}}" style="display:none;"></td>
				<td><input name="weekday[]" id="weekday[]" type="text" value="{{$normal->weekday}}"></td>
				<td><input name="friday[]" id="friday[]" type="text" value="{{$normal->friday}}"></td>
				<td><input name="saturday[]" id="saturday[]" type="text" value="{{$normal->saturday}}"></td>
				<td><input name="sunday[]" id="sunday[]" type="text" value="{{$normal->sunday}}"></td>
				<td>
					<input type="radio" data-ser="{{$normal->people}}{{$normal->merge}}" id="price_year{{$normal->people}}{{$normal->merge}}" name="price_year{{$normal->people}}{{$normal->merge}}" value="0" @if($normal->is_year==0)checked=""@endif>適用全年度
					<input type="radio" value="1" @if($normal->is_year==1)checked=""@endif data-ser="{{$normal->people}}{{$normal->merge}}" id="price_year{{$normal->people}}{{$normal->merge}}" name="price_year{{$normal->people}}{{$normal->merge}}">適用區間
					<select data-ser="{{$normal->people}}{{$normal->merge}}" id="price_time_month_start[]" name="price_time_month_start[]">
						@for($i=1;$i<=12;$i++)
						<option value="{{$i}}" @if(substr($normal->start,5,2)==str_pad($i,2,'0',STR_PAD_LEFT))selected=""@endif>{{str_pad($i,2,'0',STR_PAD_LEFT)}}</option>
						@endfor
					</select>月
					<select data-ser="{{$normal->people}}{{$normal->merge}}" id="price_time_day_start[]" name="price_time_day_start[]">
						@for($i=1;$i<=31;$i++)
						<option value="{{$i}}" @if(substr($normal->start,8,2)==str_pad($i,2,'0',STR_PAD_LEFT))selected=""@endif>{{str_pad($i,2,'0',STR_PAD_LEFT)}}</option>
						@endfor
					</select>日
					至
					<select data-ser="{{$normal->people}}{{$normal->merge}}" id="price_time_month_end[]" name="price_time_month_end[]">
						@for($i=1;$i<=12;$i++)
						<option value="{{$i}}" @if(substr($normal->end,5,2)==str_pad($i,2,'0',STR_PAD_LEFT))selected=""@endif>{{str_pad($i,2,'0',STR_PAD_LEFT)}}</option>
						@endfor
					</select>月
					<select id="price_time_day_start[]" name="price_time_day_end[]">
						@for($i=1;$i<=31;$i++)
						<option value="{{$i}}" @if(substr($normal->end,8,2)==str_pad($i,2,'0',STR_PAD_LEFT))selected=""@endif>{{str_pad($i,2,'0',STR_PAD_LEFT)}}</option>
						@endfor
					</select>日
				</td>
				<td></td>
				<td></td>
			</tr>
			@endforeach
		</table>
		<input type="text" value="{{$MergeLastNo+1}}" name="totalPriceSet" id="totalPriceSet">
		<input type="text" value="{{count($RoomSaleArray)}}" name="totalSalePeople" id="totalSalePeople">
		<button type="submit">儲存</button>
	</div>
	</form>
</div>
<!-- main -->

@endsection

@section('instyle')


@endsection

<!-- js獨立區塊腳本 -->
@section('custom_script')


//複製區間房價表格(除單選紐，其餘改用陣列)
trNo1 ={{$MergeLastNo}};
function clonePrice(){
	trNo1++;
	tr_clone =$(".cloneTr").clone().removeClass("cloneTr");
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



@endsection