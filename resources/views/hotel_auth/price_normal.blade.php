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
			@foreach($RoomSaleArray as $key => $sale)
			<tr class="cloneTr">
				<td>{{$sale}}</td>
				<td><input name="weekday[]" id="weekday[]" type="text" value="1500"></td>
				<td><input name="friday[]" id="friday[]" type="text" value="1500"></td>
				<td><input name="saturday[]" id="saturday[]" type="text" value="1500"></td>
				<td><input name="sunday[]" id="sunday[]" type="text" value="1500"></td>
				<td>
					<input type="radio" data-ser="{{$sale}}0" id="price_year{{$sale}}0" name="price_year{{$sale}}0" checked="">適用全年度
					<input type="radio" data-ser="{{$sale}}0" id="price_year{{$sale}}0" name="price_year{{$sale}}0">適用區間
					<select data-ser="{{$sale}}0" id="price_time_month_start[]" name="price_time_month_start[]">
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
					</select>月
					<select data-ser="{{$sale}}0" id="price_time_day_start[]" name="price_time_day_start[]">
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
						<option value="13">13</option>
						<option value="14">14</option>
						<option value="15">15</option>
						<option value="16">16</option>
						<option value="17">17</option>
						<option value="18">18</option>
						<option value="19">19</option>
						<option value="20">20</option>
						<option value="21">21</option>
						<option value="22">22</option>
						<option value="23">23</option>
						<option value="24">24</option>
						<option value="25">25</option>
						<option value="26">26</option>
						<option value="27">27</option>
						<option value="28">28</option>
						<option value="29">29</option>
						<option value="30">30</option>
						<option value="31">31</option>
					</select>日
					至
					<select data-ser="{{$sale}}0" id="price_time_month_end[]" name="price_time_month_end[]">
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
					</select>月
					<select id="price_time_day_start[]" name="price_time_day_start[]">
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
						<option value="13">13</option>
						<option value="14">14</option>
						<option value="15">15</option>
						<option value="16">16</option>
						<option value="17">17</option>
						<option value="18">18</option>
						<option value="19">19</option>
						<option value="20">20</option>
						<option value="21">21</option>
						<option value="22">22</option>
						<option value="23">23</option>
						<option value="24">24</option>
						<option value="25">25</option>
						<option value="26">26</option>
						<option value="27">27</option>
						<option value="28">28</option>
						<option value="29">29</option>
						<option value="30">30</option>
						<option value="31">31</option>
					</select>日
				</td>
				<td>編輯</td>
				<td>刪除</td>
			</tr>
			@endforeach
		</table>
		<input type="text" value="0" name="totalPriceSet" id="totalPriceSet">
	</div>
</div>
<!-- main -->

@endsection

@section('instyle')


@endsection

<!-- js獨立區塊腳本 -->
@section('custom_script')


//複製區間房價表格(除單選紐，其餘改用陣列)
trNo1 =0;
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
	$("#totalPriceSet").val(trNo1);
}


@endsection

<!-- jQuery ready 狀態內閉包內插 -->
@section('custom_ready_script')



@endsection