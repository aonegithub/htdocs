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
	        <h5 class="modal-title" id="exampleModalLabel">確定</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        已刪除此區
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">OK！</button>
	      </div>
	    </div>
	  </div>
	</div>
@endif

<div class="row">
	<div class="input-group input-group-sm col-md-6">
	  <div class="input-group-prepend">
	    <span class="input-group-text" id="inputGroup-sizing-sm">飯店名稱</span>
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
	        <input type="radio" id="state1" value="1" name="state">
	        <label for="state1">下線</label>
	  </div>
	  <div class="radio radio-inline">
	        <input type="radio" id="state2" value="2" name="state" checked="checked">
	        <label for="state2">關閉</label>
	  </div>
	</div>
</div>
<!-- ** -->
<div class="row">
	<div class="input-group input-group-sm col-md-6">
	  <div class="input-group-prepend">
	    <span class="input-group-text" id="inputGroup-sizing-sm">官方網站</span>
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
				    <span class="input-group-text" id="inputGroup-sizing-sm">飯店地址</span>
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
					  <div class="input-group-prepend">
					    <span class="input-group-text" id="inputGroup-sizing-sm">飯店地址</span>
					  </div>
					  <input id="address" name="address" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="請輸入地址">
					</div>
			</div>
	    </th>
	    <th rowspan="4" style="background-color: #E9ECEF;width: 5%;text-align: center;">手續費</th>
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
				    <span class="input-group-text" id="inputGroup-sizing-sm">飯店電話</span>
				  </div>
				  <input id="tel1" name="tel1" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
				</div>
				<!-- ** -->
				<div class="input-group input-group-sm col-md-6">
				  <div class="input-group-prepend">
				    <span class="input-group-text" id="inputGroup-sizing-sm">備用電話</span>
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
				    <span class="input-group-text" id="inputGroup-sizing-sm">飯店傳真</span>
				  </div>
				  <input id="fax1" name="fax1" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
				</div>
				<!-- ** -->
				<div class="input-group input-group-sm col-md-6">
				  <div class="input-group-prepend">
				    <span class="input-group-text" id="inputGroup-sizing-sm">備用傳真</span>
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
				  <input id="fees_ab" name="fees_ab" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="10">%
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
				  <input id="fees_ab_bonus" name="fees_ab_bonus" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="10">%
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
	    <span class="input-group-text" id="inputGroup-sizing-sm">主要信箱</span>
	  </div>
	  <input id="email1" name="email1" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
	</div>
	<!-- ** -->
	<div class="input-group input-group-sm col-md-3">
	  <div class="input-group-prepend">
	    <span class="input-group-text" id="inputGroup-sizing-sm">備用信箱</span>
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
	    <span class="input-group-text" id="inputGroup-sizing-sm">通訊軟體</span>
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
	    	<span class="input-group-text" id="inputGroup-sizing-sm">相關證照</span>
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
	        <input type="radio" id="invoice_type0" value="0" name="invoice_type">
	        <label for="invoice_type0">甲</label>
	  </div>
	  <div class="radio radio-inline align-middle">
	        <input type="radio" id="invoice_type1" value="1" name="invoice_type" checked="">
	        <label for="invoice_type1">乙</label>
	  </div>
	  <div class="radio radio-inline align-middle">
	        <input type="radio" id="invoice_type2" value="2" name="invoice_type" checked="">
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

@endsection
<!-- style內置區塊 -->
@section('instyle')
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