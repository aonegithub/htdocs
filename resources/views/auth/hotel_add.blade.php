@extends('auth.main_layout')

<!-- 標題 -->
@section('title', $Title)

<!-- 導航按鈕按下狀態編號 -->
@section('nav_id', $Nav_ID)
<!-- 內容 -->
@section('content')
@if(!is_null(session()->get('controll_back_msg')))
	<div class="modal fade" id="okAlert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">新增完成</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        已新增一筆
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">OK！</button>
	      </div>
	    </div>
	  </div>
	</div>
@endif
<div style="text-align:right;">
	<a href="/{{$Country}}/auth/manager/hotel_list" class="btn btn-secondary">返回飯店清單</a>
</div>
<form method="POST" role="form" action="/{{$Country}}/auth/manager/hotel_add">
	{{ csrf_field() }}
	<div class="row">
		<div class="input-group input-group-sm col-md-6">
		  <div class="input-group-prepend">
		    <span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">飯店名稱</span>
		  </div>
		  <input id="name" name="name" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
		</div>
		<!-- ** -->
		<div class="input-group input-group-sm col-md-3">
		  <div class="input-group-prepend">
		    <span class="input-group-text" id="inputGroup-sizing-sm">版本</span>
		  </div>
		  <select class="form-control" id="ver" name="ver" style="max-width: 200px;">
			  <option value='CA'>CA</option>
			  <option value='AB'>AB</option>
			  <option value='D'>D</option>
		  </select>
		</div>
		<!-- ** -->
		<div class="input-group input-group-sm col-md-3">
		  <div class="input-group-prepend">
		    <span class="input-group-text" id="inputGroup-sizing-sm">狀態</span>
		  </div>
		  <div class="radio radio-inline">
		        <input type="radio" id="state0" value="0" name="state">
		        <label for="state0">上線</label>
		  </div>
		  <div class="radio radio-inline">
		        <input type="radio" id="state1" value="1" name="state" checked="checked">
		        <label for="state1">下線</label>
		  </div>
		  <div class="radio radio-inline">
		        <input type="radio" id="state2" value="2" name="state">
		        <label for="state2">關閉</label>
		  </div>
		</div>
	</div>
	<!-- ** -->
	<div class="row">
		<div class="input-group input-group-sm col-md-6">
		  <div class="input-group-prepend">
		    <span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">官方網站</span>
		  </div>
		  <input type="text" id="url" name="url" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
		</div>
		<!-- ** -->
		<div class="input-group input-group-sm col-md-3">
		  <div class="input-group-prepend">
		    <span class="input-group-text" id="inputGroup-sizing-sm">訂金</span>
		  </div>
		  <input type="text" id="deposit" name="deposit" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" style="max-width: 200px;" value="10">%
		</div>
		<!-- ** -->
		<div class="input-group input-group-sm col-md-3">
		  <div class="input-group-prepend">
		    <span class="input-group-text" id="inputGroup-sizing-sm">控管</span>
		  </div>
		  <div class="radio radio-inline">
		        <input type="radio" id="control1" value="0" name="control">
		        <label for="control1">立即訂房</label>
		  </div>
		  <div class="radio radio-inline">
		        <input type="radio" id="control2" value="1" name="control" checked="checked">
		        <label for="control2">客服訂房</label>
		  </div>
		</div>
	</div>
	<!-- ** -->
	<div class="row">
		<table width="100%">
		  <tr>
		    <th width="50%">
		    	<div class="input-group input-group-sm">
					  <div class="input-group-prepend">
					    <span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">飯店地址</span>
					  </div>
					  <select class="form-control" id="area_level1" name="area_level1" style="display:none">
						  <option value='1'>台灣</option>
					  </select>
					  <select class="form-control" id="area_level2" name="area_level2" onchange="chg_area(this,2)">
						  <option value='-1'>-</option>
						  @foreach($Areas_level2 as $key => $area2)
								<option value='{{$area2->nokey}}'>{{$area2->area_name}}</option>
						  @endforeach
					  </select>
					  <select class="form-control" id="area_level3" name="area_level3">
						  <option value='-1'>-</option>
					  </select><br/>
					  	<div class="input-group input-group-sm col-md-2"> 
						  <input id="zip_code" name="zip_code" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="郵遞區號">
						</div>
						<!-- ** -->
					  	<div class="input-group input-group-sm col-md-6">
						  <input id="address" name="address" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="請輸入地址">
						</div>
				</div>
		    </th>
		    <th rowspan="4" style="background-color: #c9fcb3;width: 5%;text-align: center;">手續費</th>
		    <td>
		    	<div class="row" style="padding-left: 15px;">
					<div class="input-group input-group-sm col-md-4">
					  <div class="input-group-prepend">
					    <span class="input-group-text" id="inputGroup-sizing-sm">C版</span>
					  </div>
					  <input id="fees_c" name="fees_c" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="10">%
					</div>
					<!-- ** -->
					<div class="input-group input-group-sm col-md-4" style="padding-left: 15px;">
					  <div class="input-group-prepend">
					    <span class="input-group-text" id="inputGroup-sizing-sm">紅利</span>
					  </div>
					  <input id="fees_c_bonus" name="fees_c_bonus" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="10">%
					</div>
				</div>
		    </td>
		  </tr>
		  <tr>
		    <td>
		    	<div class="row" style="margin-right: 0px;margin-left:0px;">
			    	<div class="input-group input-group-sm col-md-6">
					  <div class="input-group-prepend">
					    <span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">飯店電話</span>
					  </div>
					  <input id="tel1" name="tel1" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
					</div>
					<!-- ** -->
					<div class="input-group input-group-sm col-md-6">
					  <div class="input-group-prepend">
					    <span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">備用電話</span>
					  </div>
					  <input id="tel2" name="tel2" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
					</div>
				</div>
		    </td>
		    <td>
		    	<!-- ** -->
		    	<div class="row" style="padding-left: 15px;">
					<div class="input-group input-group-sm col-md-4">
					  <div class="input-group-prepend">
					    <span class="input-group-text" id="inputGroup-sizing-sm">AB版</span>
					  </div>
					  <input id="fees_ab" name="fees_ab" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="10">%
					</div>
					<!-- ** -->
					<div class="input-group input-group-sm col-md-4" style="padding-left: 15px;">
					  <div class="input-group-prepend">
					    <span class="input-group-text" id="inputGroup-sizing-sm">紅利</span>
					  </div>
					  <input id="fees_ab_bonus" name="fees_ab_bonus" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="10">%
					</div>
				</div>
				<!-- ** -->
		    </td>
		  </tr>
		  <tr>
		    <td rowspan="2">
		    	<div class="row" style="margin-right: 0px;margin-left:0px;">
			    	<div class="input-group input-group-sm col-md-6">
					  <div class="input-group-prepend">
					    <span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">飯店傳真</span>
					  </div>
					  <input id="fax1" name="fax1" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
					</div>
					<!-- ** -->
					<div class="input-group input-group-sm col-md-6">
					  <div class="input-group-prepend">
					    <span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">備用傳真</span>
					  </div>
					  <input id="fax2" name="fax2" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
					</div>
				</div>
		    </td>
		    <td>
		    	<!-- ** -->
		    	<div class="row" style="padding-left: 15px;">
					<div class="input-group input-group-sm col-md-4">
					  <div class="input-group-prepend">
					    <span class="input-group-text" id="inputGroup-sizing-sm">D版</span>
					  </div>
					  <input id="fees_d" name="fees_d" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="10">%
					</div>
					<!-- ** -->
					<div class="input-group input-group-sm col-md-4" style="padding-left: 15px;">
					  <div class="input-group-prepend">
					    <span class="input-group-text" id="inputGroup-sizing-sm">紅利</span>
					  </div>
					  <input id="fees_d_bonus" name="fees_d_bonus" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="10">%
					</div>
				</div>
				<!-- ** -->
		    </td>
		  </tr>
		  <tr>
		    <td>
		    	<!-- ** -->
		    	<div class="row" style="padding-left: 15px;">
		    		<div class="checkbox checkbox-primary">
	                    <input id="fees_sale_state" name="fees_sale_state" type="checkbox" value="1">
	                    <label for="fees_sale_state">
	                    </label>
	                </div>
					<div class="input-group input-group-sm col-md-4">
					  <div class="input-group-prepend">
					    <span class="input-group-text" id="inputGroup-sizing-sm">經銷紅利</span>
					  </div>
					  <input id="fees_sale_bonus" name="fees_sale_bonus" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="10">%
					</div>
					<!-- ** -->
					<div class="checkbox checkbox-primary">
	                    <input id="fees_roll_state" name="fees_roll_state" type="checkbox" value="1">
	                    <label for="fees_roll_state">
	                    </label>
	                </div>
					<div class="input-group input-group-sm col-md-4" style="padding-left: 15px;">
					  <div class="input-group-prepend">
					    <span class="input-group-text" id="inputGroup-sizing-sm">住宿紅利</span>
					  </div>
					  <input id="fees_roll_bonus" name="fees_roll_bonus" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="10">%
					</div>
				</div>
				<!-- ** -->
		    </td>
		  </tr>
		</table>
	</div>
	<!-- ** -->
	<div class="row">
		<div class="input-group input-group-sm col-md-3">
		  <div class="input-group-prepend">
		    <span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">主要信箱</span>
		  </div>
		  <input id="email1" name="email1" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
		</div>
		<!-- ** -->
		<div class="input-group input-group-sm col-md-3">
		  <div class="input-group-prepend">
		    <span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">備用信箱</span>
		  </div>
		  <input id="email2" name="email2" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
		</div>
		<!-- ** -->
		<div class="input-group input-group-sm col-md-6">
		  <div class="input-group-prepend">
		    <span class="input-group-text" id="inputGroup-sizing-sm">追蹤管理</span>
		  </div>
		  <div class="radio radio-inline align-middle">
		        <input type="radio" id="track0" value="0" name="track" checked="">
		        <label for="track0">不追蹤</label>
		  </div>
		  <div class="radio radio-inline align-middle">
		        <input type="radio" id="track1" value="1" name="track">
		        <label for="track1">追蹤</label>
		  </div>
		  <input id="track_comm" name="track_comm" type="text" class="form-control col-md-4" placeholder="追蹤事由" style="margin-left: 10px;">
		</div>
	</div>
	<!-- ** -->
	<div class="row">
		<div class="input-group input-group-sm col-md-3">
		  <div class="input-group-prepend">
		    <span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">通訊軟體</span>
		  </div>
		  <input id="app_line" name="app_line" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="Line">
		</div>
		<!-- ** -->
		<div class="input-group input-group-sm col-md-3">
		  <input id="app_wechat" name="app_wechat" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="WeChat">
		</div>
		<!-- ** -->
		<div class="input-group input-group-sm col-md-3">
		  <div class="input-group-prepend">
		    <span class="input-group-text" id="inputGroup-sizing-sm">結帳方式</span>
		  </div>
		  <div class="radio radio-inline align-middle">
		        <input type="radio" id="checkout0" value="0" name="checkout">
		        <label for="checkout0">日結</label>
		  </div>
		  <div class="radio radio-inline align-middle">
		        <input type="radio" id="checkout1" value="1" name="checkout" checked="">
		        <label for="checkout1">月結</label>
		  </div>
		</div>
		<div class="input-group input-group-sm col-md-2">
			  <div class="input-group-prepend">
			    <span class="input-group-text" id="inputGroup-sizing-sm">訂房起始日</span>
			  </div>
			  <input id="booking_day" name="booking_day" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="" value="1">日
		</div>
	</div>
	<!-- ** -->
	<div class="row">
		<div class="input-group input-group-sm col-md-6">
		  	<div class="input-group-prepend">
		    	<span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">相關證照</span>
		  	</div>
		  	<div class="checkbox checkbox-primary">
		        <input id="license_hotel" name="license_hotel" type="checkbox" value="1">
		        <label for="license_hotel">合法旅館
		        </label>
		  	</div>
		  	<div class="checkbox checkbox-primary">
		        <input id="license_homestay" name="license_homestay" type="checkbox" value="1">
		        <label for="license_homestay">合法民宿
		        </label>
		  	</div>
		  	<div class="checkbox checkbox-primary">
		        <input id="license_hospitable" name="license_hospitable" type="checkbox" value="1">
		        <label for="license_hospitable">好客民宿
		        </label>
		  	</div>
		</div>
		<!-- ** -->
		<div class="input-group input-group-sm col-md-3">
		  <div class="input-group-prepend">
		    <span class="input-group-text" id="inputGroup-sizing-sm">發票型態</span>
		  </div>
		  <div class="radio radio-inline align-middle">
		        <input type="radio" id="invoice_type0" value="0" name="invoice_type" checked="">
		        <label for="invoice_type0">甲</label>
		  </div>
		  <div class="radio radio-inline align-middle">
		        <input type="radio" id="invoice_type1" value="1" name="invoice_type">
		        <label for="invoice_type1">乙</label>
		  </div>
		  <div class="radio radio-inline align-middle">
		        <input type="radio" id="invoice_type2" value="2" name="invoice_type">
		        <label for="invoice_type2">丙</label>
		  </div>
		</div>
		<div class="input-group input-group-sm col-md-3">
			  <div class="input-group-prepend">
			    <span class="input-group-text" id="inputGroup-sizing-sm">配合度</span>
			  </div>
			  <div class="radio radio-inline align-middle">
			        <input type="radio" id="coordinate1" value="0" name="coordinate" checked="">
			        <label for="coordinate1">佳</label>
			  </div>
			  <div class="radio radio-inline align-middle">
			        <input type="radio" id="coordinate2" value="1" name="coordinate">
			        <label for="coordinate2">普通</label>
			  </div>
			  <div class="radio radio-inline align-middle">
			        <input type="radio" id="coordinate3" value="2" name="coordinate">
			        <label for="coordinate3">差</label>
			  </div>
		</div>
	</div>
	<!-- ** -->
	<div class="row">
		<div class="input-group input-group-sm col-md-6">
		  	<div class="input-group-prepend">
		    	<span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">飯店類型</span>
		  	</div>
		  	<select class="form-control" id="type_scale" name="type_scale">
			  	<option value='飯店'>飯店</option>
			  	<option value='民宿'>民宿</option>
		    </select>
		  	<div class="input-group-prepend">
		    	<span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">飯店星級</span>
		  	</div>
		  	<select class="form-control" id="type_level" name="type_level">
			  	<option value='0'>☆</option>
			  	<option value='1'>★</option>
			  	<option value='2'>★★</option>
			  	<option value='3'>★★★</option>
			  	<option value='4'>★★★★</option>
			  	<option value='5'>★★★★★</option>
		    </select>
		    <div class="input-group-prepend">
			    <span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">房間總數</span>
			  </div>
			  <input id="type_room" name="type_room" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="" value="1">
		</div>
		<!-- ** -->
		<div class="input-group input-group-sm col-md-6">
		  <div class="input-group-prepend">
		    <span class="input-group-text" id="inputGroup-sizing-sm">警察單位</span>
		  </div>
		  <div class="radio radio-inline align-middle">
		        <input type="radio" id="local_police0" value="0" name="local_police" checked="">
		        <label for="local_police0">不顯示</label>
		  </div>
		  <div class="radio radio-inline align-middle">
		        <input type="radio" id="local_police1" value="1" name="local_police">
		        <label for="local_police1">顯示</label>
		  </div>
		  <input id="local_police_comm" name="local_police_comm" type="text" class="form-control col-md-6" placeholder="當地警察單位與聯繫方式" style="margin-left: 10px;">
		</div>
	</div>
	<!-- ** -->
	<div class="row">
		<div class="input-group input-group-sm col-md-6">
		    <div class="input-group-prepend">
			    <span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">開立發票</span>
			</div>
			<div class="radio radio-inline align-middle">
			    <input type="radio" id="invoice0" value="0" name="invoice" checked="">
			    <label for="invoice0">可</label>
			</div>
			<div class="radio radio-inline align-middle">
			    <input type="radio" id="invoice1" value="1" name="invoice">
			    <label for="invoice1">僅開立收據</label>
			</div>
			<div class="radio radio-inline align-middle">
			    <input type="radio" id="invoice2" value="2" name="invoice">
			    <label for="invoice2">皆無</label>
			</div>
		</div>
		<!-- ** -->
		<div class="input-group input-group-sm col-md-6">
		    <div class="input-group-prepend">
			    <span class="input-group-text" id="inputGroup-sizing-sm">SEO標題</span>
			  </div>
			  <input id="seo_title" name="seo_title" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="" value="">
		</div>
	</div>
	<!-- ** -->
	<div class="row">
		<div class="input-group input-group-sm col-md-6">
		    <div class="input-group-prepend">
			    <span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">立案名稱</span>
			</div>
			<input id="reg_name" name="reg_name" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="" value="">
		</div>
		<!-- ** -->
		<div class="input-group input-group-sm col-md-6">
		    <div class="input-group-prepend">
			    <span class="input-group-text" id="inputGroup-sizing-sm">SEO描述</span>
			  </div>
			  <input id="seo_descript" name="seo_descript" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="" value="">
		</div>
	</div>
	<!-- ** -->
	<div class="row">
		<div class="input-group input-group-sm col-md-6">
		    <div class="input-group-prepend">
			    <span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">統一編號</span>
			</div>
			<input id="reg_no" name="reg_no" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="" value="">
		</div>
		<!-- ** -->
		<div class="input-group input-group-sm col-md-6">
		    <div class="input-group-prepend">
			    <span class="input-group-text" id="inputGroup-sizing-sm">SEO關鍵字</span>
			  </div>
			  <input id="seo_keyword" name="seo_keyword" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="" value="">
		</div>
	</div>
	<!-- ** -->
	<div class="row">
		<div class="input-group input-group-sm col-md-6">
		    <div class="input-group-prepend">
			    <span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">現場刷卡</span>
			</div>
			<div class="radio radio-inline align-middle">
			    <input type="radio" id="credit_card0" value="0" name="credit_card" checked="">
			    <label for="credit_card0">可(一般刷卡)</label>
			</div>
			<div class="radio radio-inline align-middle">
			    <input type="radio" id="credit_card1" value="1" name="credit_card">
			    <label for="credit_card1">可(支援國民旅遊卡)</label>
			</div>
			<div class="radio radio-inline align-middle">
			    <input type="radio" id="credit_card2" value="2" name="credit_card">
			    <label for="credit_card2">皆無</label>
			</div>
		</div>
		<!-- ** -->
		<div class="input-group input-group-sm col-md-6">
			<div class="input-group input-group-sm col-md-6">
		    <div class="input-group-prepend">
			    <span class="input-group-text" id="inputGroup-sizing-sm">前台電話</span>
			</div>
			<div class="radio radio-inline align-middle">
				<input type="radio" id="display_tel0" value="0" name="display_tel" checked="">
				<label for="display_tel0">不顯示</label>
			</div>
			<div class="radio radio-inline align-middle">
				<input type="radio" id="display_tel1" value="1" name="display_tel">
				<label for="display_tel1">顯示飯店電話</label>
			</div>
		</div>
		</div>
	</div>
	<!-- ** -->
	<div class="row">
		<div class="input-group input-group-sm col-md-6">
		    <div class="input-group-prepend">
			    <span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">帳戶資訊</span>
			</div>
			<input id="bank_name" name="bank_name" type="text" class="form-control col-md-2" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="銀行名稱" value="">
			<input id="bank_code" name="bank_code" type="text" class="form-control col-md-1" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="代碼" value="">
			<input id="bank_account" name="bank_account" type="text" class="form-control col-md-6" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="帳號" value="">
			<input id="bank_account_name" name="bank_account_name" type="text" class="form-control col-md-3" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="戶名" value="">
		</div>
	</div>
	<!-- ** -->
	<div class="row">
		<div class="input-group input-group-sm col-md-6">
			<div class="input-group-prepend">
			    <span class="input-group-text input-group-custom" id="inputGroup-sizing-sm">飯店優點</span>
			</div>
			<input id="point" name="point" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="" value="">
		</div>
	</div>
	<!-- ** -->
	<div class="row" style="margin-top:30px;">
		<table class="tg" style="width: 100%">
		  <tr>
		    <th style="background-color: #c9fcb3;height:45px;text-align: center;width:10%;">姓名</th>
		    <th style="background-color: #c9fcb3;height:45px;text-align: center;width:10%;">職稱</th>
		    <th style="background-color: #c9fcb3;height:45px;text-align: center;width:10%;">電話</th>
		    <th style="background-color: #c9fcb3;height:45px;text-align: center;width:15%;">手機</th>
		    <th style="background-color: #c9fcb3;height:45px;text-align: center;width:10%;">Line</th>
		    <th style="background-color: #c9fcb3;height:45px;text-align: center;width:10%;">微信</th>
		    <th style="background-color: #c9fcb3;height:45px;text-align: center;width:35%;">信箱</th>
		  </tr>
		  <tr>
		    <td style="height:45px;">
		    	<input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="請輸入姓名" value="">
		    </td>
		    <td style="height:45px;">
		    	<input type="text" class="form-control" id="contact_job" name="contact_job" placeholder="請輸入職稱" value="">
		    </td>
		    <td style="height:45px;">
		    	<input type="text" class="form-control" id="contact_tel" name="contact_tel" placeholder="請輸入電話" value="">
		    </td>
		    <td style="height:45px;">
		    	<input type="text" class="form-control" id="contact_mobile" name="contact_mobile" placeholder="請輸入手機" value="">
		    </td>
		    <td style="height:45px;">
		    	<input type="text" class="form-control" id="contact_line" name="contact_line" placeholder="請輸入LineID" value="">
		    </td>
		    <td style="height:45px;">
		    	<input type="text" class="form-control" id="contact_wechat" name="contact_wechat" placeholder="請輸入微信" value="">
		    </td>
		    <td style="height:45px;">
		    	<input type="text" class="form-control" id="contact_email" name="contact_email" placeholder="請輸入信箱" value="">
		    </td>
		  </tr>
		</table>
	</div>
	<!-- ** -->
	<div class="row">
		<!-- ** -->
		<div class="input-group input-group-sm col-md-6">
		    <div class="input-group-prepend">
			    <span class="input-group-text" id="inputGroup-sizing-sm">後台網址</span>
			</div>
			<input type="text" class="form-control" id="manage_url" name="manage_url" placeholder="" value="">
		</div>
		<!-- ** -->
		<div class="input-group input-group-sm col-md-6">
		    <div class="input-group-prepend">
			    <span class="input-group-text" id="inputGroup-sizing-sm">後台簡址</span>
			</div>
			<input type="text" class="form-control" id="manage_surl" name="manage_surl" placeholder="" value="">
		</div>
	</div>
	<!-- ** -->
	<div class="row">
		<!-- ** -->
		<div class="input-group input-group-sm col-md-6">
		    <div class="input-group-prepend">
			    <span class="input-group-text" id="inputGroup-sizing-sm">C版網址</span>
			</div>
			<input type="text" class="form-control" id="c_url" name="c_url" placeholder="" value="">
		</div>
		<!-- ** -->
		<div class="input-group input-group-sm col-md-6">
		    <div class="input-group-prepend">
			    <span class="input-group-text" id="inputGroup-sizing-sm">C版簡址</span>
			</div>
			<input type="text" class="form-control" id="c_surl" name="c_surl" placeholder="" value="">
		</div>
	</div>
	<!-- ** -->
	<div class="row">
		<!-- ** -->
		<div class="input-group input-group-sm col-md-6">
		    <div class="input-group-prepend">
			    <span class="input-group-text" id="inputGroup-sizing-sm">D版網址</span>
			</div>
			<div class="checkbox checkbox-primary">
	            <input id="d_enable" name="d_enable" type="checkbox" value="1">
	            <label for="d_enable">
	            	啟用
	            </label>
	        </div>
			<input type="text" class="form-control" id="d_url" name="d_url" placeholder="" value="">
		</div>
		<!-- ** -->
		<div class="input-group input-group-sm col-md-6">
		    <div class="input-group-prepend">
			    <span class="input-group-text" id="inputGroup-sizing-sm">D版簡址</span>
			</div>
			<input type="text" class="form-control" id="d_surl" name="d_surl" placeholder="" value="">
		</div>
	</div>
	<!-- ** -->
	<div class="row">
		<!-- ** -->
		<div class="input-group input-group-sm col-md-6">
		    <div class="input-group-prepend">
			    <span class="input-group-text" id="inputGroup-sizing-sm">AB版網址</span>
			</div>
			<input type="text" class="form-control" id="ab_url" name="ab_url" placeholder="" value="">
		</div>
	</div>
	<!-- ** -->
	<div class="row">
		<table width="100%">
		  <tr>
		    <th style="background-color: #c9fcb3;width: 85px;height: 100px;text-align: center;" rowspan="3">登錄者</th>
		    <td colspan="6">
		    	<div class="row col-md-12">
		    		<div class="input-group input-group-sm col-md-2">
					    <div class="input-group-prepend">
						    <span class="input-group-text" id="inputGroup-sizing-sm">姓名</span>
						</div>
						<input type="text" class="form-control" id="login_name" name="login_name" placeholder="" value="">
					</div>
					<!-- ** -->
					<div class="input-group input-group-sm col-md-2">
					    <div class="input-group-prepend">
						    <span class="input-group-text" id="inputGroup-sizing-sm">所屬公司</span>
						</div>
						<input type="text" class="form-control" id="login_com" name="login_com" placeholder="" value="">
					</div>
					<!-- ** -->
					<div class="input-group input-group-sm col-md-2">
					    <div class="input-group-prepend">
						    <span class="input-group-text" id="inputGroup-sizing-sm">部門或職稱</span>
						</div>
						<input type="text" class="form-control" id="login_job" name="login_job" placeholder="" value="">
					</div>
					<!-- ** -->
					<div class="input-group input-group-sm col-md-5">
					    <div class="input-group-prepend">
						    <span class="input-group-text" id="inputGroup-sizing-sm">公司地址</span>
						</div>
						<select class="form-control col-md-2" id="login_area_level1" name="login_area_level1" style="display:none">
						  	<option value='1'>台灣</option>
					  	</select>
					  	<select class="form-control col-md-2" id="login_area_level2" name="login_area_level2" onchange="login_chg_area(this,2)">
						  	<option value='-1'>-</option>
						  	@foreach($Areas_level2 as $key => $area2)
								<option value='{{$area2->nokey}}'>{{$area2->area_name}}</option>
						  	@endforeach
					  	</select>
					  	<select class="form-control col-md-2" id="login_area_level3" name="login_area_level3">
							<option value='-1'>-</option>
						</select>
						<input type="text" class="form-control col-md-8" id="login_addr" name="login_addr" placeholder="請輸入地址" value="">
					</div>
		    	</div>
		    </td>
		  </tr>
		  <tr>
		    <td colspan="6">
		    	<div class="row col-md-12">
		    		<div class="input-group input-group-sm col-md-2">
					    <div class="input-group-prepend">
						    <span class="input-group-text" id="inputGroup-sizing-sm">公司電話</span>
						</div>
						<input type="text" class="form-control" id="login_tel" name="login_tel" placeholder="" value="">
					</div>
					<!-- ** -->
					<div class="input-group input-group-sm col-md-2">
					    <div class="input-group-prepend">
						    <span class="input-group-text" id="inputGroup-sizing-sm">聯絡手機</span>
						</div>
						<input type="text" class="form-control" id="login_mobile" name="login_mobile" placeholder="" value="">
					</div>
					<!-- ** -->
					<div class="input-group input-group-sm col-md-2">
					    <div class="input-group-prepend">
						    <span class="input-group-text" id="inputGroup-sizing-sm">信箱</span>
						</div>
						<input type="text" class="form-control" id="login_email" name="login_email" placeholder="" value="">
					</div>
					<!-- ** -->
					<div class="input-group input-group-sm col-md-2">
					    <div class="input-group-prepend">
						    <span class="input-group-text" id="inputGroup-sizing-sm">帳號</span>
						</div>
						<input type="text" class="form-control" id="login_id" name="login_id" placeholder="" value="">
					</div>
					<!-- ** -->
					<div class="input-group input-group-sm col-md-2">
					    <div class="input-group-prepend">
						    <span class="input-group-text" id="inputGroup-sizing-sm">密碼</span>
						</div>
						<input type="text" class="form-control" id="login_passwd" name="login_passwd" placeholder="" value="">
					</div>
		    	</div>
		    </td>
		  </tr>
		  <tr>
		    <td colspan="6">
				<div class="row col-md-12">
		    		<div class="input-group input-group-sm col-md-6">
					    <div class="input-group-prepend">
						    <span class="input-group-text" id="inputGroup-sizing-sm">集團或連鎖</span>
						</div>
						<!-- ** -->
						<div class="radio radio-inline">
						    <input type="radio" id="login_is_group0" value="0" name="login_is_group" checked="">
						    <label for="login_is_group0">否</label>
						</div>
						<div class="radio radio-inline">
						    <input type="radio" id="login_is_group1" value="1" name="login_is_group">
						    <label for="login_is_group1">是</label>
						</div>
						<input type="text" class="form-control" id="login_group_name" name="login_group_name" placeholder="集團名稱" value="">與
						<input type="text" class="form-control" id="login_group_url" name="login_group_url" placeholder="" value="http://">
					</div>
					<!-- ** -->
					<div class="input-group input-group-sm col-md-6">
					    <div class="input-group-prepend">
						    <span class="input-group-text" id="inputGroup-sizing-sm">申請合作家數</span>
						</div>
						<div class="radio radio-inline">
						    <input type="radio" id="login_group_count0" value="0" name="login_group_count" checked="">
						    <label for="login_group_count0">一家</label>
						</div>
						<div class="radio radio-inline">
						    <input type="radio" id="login_group_count1" value="1" name="login_group_count">
						    <label for="login_group_count1">多家</label>
						</div>
					</div>
		    	</div>
		    </td>
		  </tr>
		</table>
	</div>
	<!-- ** -->
	<div class="row">
		<!-- ** -->
		<div class="input-group input-group-sm col-md-6">
		    <div class="input-group-prepend">
			    <span class="input-group-text" id="inputGroup-sizing-sm">合約到期日</span>
			</div>
			<input type="text" class="form-control" id="expire" name="expire" placeholder="" value="2020-09-19">
		</div>
		<!-- ** -->
		<div class="input-group input-group-sm col-md-6">
		    <div class="input-group-prepend">
			    <span class="input-group-text" id="inputGroup-sizing-sm">瀏覽人數</span>
			</div>
			<span class="form-control">888今日:88昨日:77:前日:88</span>
		</div>
	</div>
	<!-- ** -->
	<button type="submit" class="btn btn-secondary btn-lg btn-block" style="margin-top: 30px;">新增飯店</button>
</form>

@endsection
<!-- style內置區塊 -->
@section('instyle')
.input-group-text{
	background-color:#c9fcb3;
}
.input-group-custom{
	background-color:#fff3c6;
}
/** 分頁樣式 */
#nav_pagerow{
	float: right;
	left: -50%;
	position: relative;
}
#nav_pagerow > ul{
	float:left;
	left: 50%;
	position: relative;
}
.input-group{
	padding-left: 5px;
    padding-right: 5px;
}
.row{
	margin-top: 10px;
}
@endsection
<!-- js獨立區塊腳本 -->
@section('custom_script')
//現存級別
var level_global=1;
	
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
	        url: 'area_get',
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
			for(i=0; i< data.length; i++){
				$("#area_level"+(level+1)).append($('<option>', {
				    value: data[i]['nokey'],
				    text: data[i]['area_name']
				}));
			}
			$("#area_level"+(level+1)).prop('disabled', false);
			//alert(data['1']['area_name']);
			//$("#area_level"+level+" option[value!='-1']").remove();
		}
	}
	//登錄者用
	// 切換選項時，level為該選項之級別值
	function login_chg_area(sel_obj, level){
		$("#contact_area_level"+(level+1)).prop('disabled', true);
		$("#contact_area_level"+(level+1)+" option").remove();
		sel_val =$(sel_obj).val();

		if(sel_val == '-1'){
			sel_val =$("#contact_area_level"+(level-1)).val()
		}
		$.ajax({
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
	        type: "POST",
	        url: 'area_get',
	        data: {level:sel_val},
	        success: function(data) {
	        	//填入下一級選項
	        	login_fill_area(data,level);
	    	}
	    });
	}
	
	//填入下級選項
	function login_fill_area(data, level){
		if(level <=4){
			$("#contact_area_level"+(level+1)+" option[value!='-1']").remove();
			for(i=0; i< data.length; i++){
				$("#contact_area_level"+(level+1)).append($('<option>', {
				    value: data[i]['nokey'],
				    text: data[i]['area_name']
				}));
			}
			$("#contact_area_level"+(level+1)).prop('disabled', false);
			//alert(data['1']['area_name']);
			//$("#area_level"+level+" option[value!='-1']").remove();
		}
	}
$(window).resize(function(){
	$("body").css("margin-top",$("nav").height()+20);
});
@endsection
<!-- jQuery ready 狀態內閉包內插 -->
@section('custom_ready_script')
	$("body").css("margin-top",$("nav").height()+20);
	//停用完成跳出確認
	@if(!is_null(session()->get('controll_back_msg')))
		$('#okAlert').modal("toggle");
	@endif
@endsection