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
				  <select class="form-control" id="level1" name="level1">
					  <option value='1'>台灣</option>
				  </select>
				  <select class="form-control" id="level2" name="level2">
					  <option value='2'>台南市</option>
				  </select>
				  <select class="form-control" id="level3" name="level3">
					  <option value='4'>新化區</option>
				  </select><br/>
				  	<div class="input-group input-group-sm col-md-2"> 
					  <input id="zip_code" name="zip_code" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="郵遞區號">
					</div>
					<!-- ** -->
				  	<div class="input-group input-group-sm col-md-4">
					  <div class="input-group-prepend">
					    <span class="input-group-text" id="inputGroup-sizing-sm">地址</span>
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
				  <input id="fees_c" name="fees_c" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">%
				</div>
				<!-- ** -->
				<div class="input-group input-group-sm col-md-4" style="padding-left: 15px;">
				  <div class="input-group-prepend">
				    <span class="input-group-text" id="inputGroup-sizing-sm">紅利</span>
				  </div>
				  <input id="fees_c_bonus" name="fees_c_bonus" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">%
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
				  <input id="fees_ab" name="fees_ab" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">%
				</div>
				<!-- ** -->
				<div class="input-group input-group-sm col-md-4" style="padding-left: 15px;">
				  <div class="input-group-prepend">
				    <span class="input-group-text" id="inputGroup-sizing-sm">紅利</span>
				  </div>
				  <input id="fees_ab_bonus" name="fees_ab_bonus" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">%
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
				  <input id="fees_d" name="fees_d" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">%
				</div>
				<!-- ** -->
				<div class="input-group input-group-sm col-md-4" style="padding-left: 15px;">
				  <div class="input-group-prepend">
				    <span class="input-group-text" id="inputGroup-sizing-sm">紅利</span>
				  </div>
				  <input id="fees_d_bonus" name="fees_d_bonus" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">%
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
				  <input id="fees_ab" name="fees_ab" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">%
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
				  <input id="fees_ab_bonus" name="fees_ab_bonus" type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm">%
				</div>
			</div>
			<!-- ** -->
	    </td>
	  </tr>
	</table>
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
	// 刪除地區
	function delArea(nokey){
		if(confirm('確定要刪除')){
			//開啟讀取模式
			$("#loading").slideDown();
			//
			$.ajax({
		        headers: {
		            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
		        type: "POST",
		        url: 'area_del',
		        data: {req_nokey:nokey},
		        success: function(data) {
		        	$(".edit_field"+nokey).val("");
		        	refresh_area(level_global);
		    	},
		    	error: function(xhr, ajaxOptions, thrownError) {
					alert(xhr.responseText);
					$("#loading").slideUp();
				}
		    });
		}
		//alert(level_global+','+nokey+','+$(".edit_field"+nokey).val());
	}
	// 編輯地區
	function editArea(nokey){
		//開啟讀取模式
		$("#loading").slideDown();
		//
		$.ajax({
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
	        type: "POST",
	        url: 'area_edit',
	        data: {req_nokey:nokey,req_name:$(".edit_field"+nokey).val()},
	        success: function(data) {
	        	$(".edit_field"+nokey).val("");
	        	$("#loading").slideUp();
	        	refresh_area(level_global);
	    	},
	    	error: function(xhr, ajaxOptions, thrownError) {
				alert(xhr.responseText);
				$("#loading").slideUp();
			}
	    });
		//alert(level_global+','+nokey+','+$(".edit_field"+nokey).val());
	}
	//項目風琴開合效果
	function editAreaToggle(nokey){
		$('.edit_area').slideUp();
		$('.edit_area_row'+nokey).slideDown();
	}
	// 新增地區打開選項
	function openAddArea(){
		$("#addAreaPanel").slideToggle();
	}
	// 新增地區
	function addArea(){
		level1 =$("#area_level1").val();
		level2 =$("#area_level2").val();
		level3 =$("#area_level3").val();
		level4 =$("#area_level4").val();
		area_name =$("#area_name").val();

		//欲新增層級判斷 and 判斷父層
		level =1;
		if(level1 <0){
			level =1;
		}else if(level2 <0){
			level =2;
		}else if(level3 <0){
			level =3
		}else{
			level =4;
		}
		$.ajax({
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
	        type: "POST",
	        url: 'area_add',
	        data: {level_no:level,parent_no:$("#area_level"+(level-1)).val(),area_string:area_name},
	        success: function(data) {
	        	refresh_area(level);
	        	$("#area_name").val("");
	    	},
	    	error: function(xhr, ajaxOptions, thrownError) {
				alert(xhr.responseText);
			}
	    });
	}
	//更新地區下拉式選單
	function refresh_area(level){
		$("#loading").slideDown();
		parent_val =$("#area_level"+(level-1)).val();
		$.ajax({
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
	        type: "POST",
	        url: 'area_get',
	        data: {level:parent_val},
	        success: function(data) {
	        	//填入下一級選項，參數2為上一層級別
	        	fill_area(data,(level-1));
	        	//填入清單
	        	fill_list(data);
	        	//更新級別
	        	refresh_level();
	        	$("#loading").slideUp();
	    	}
	    });
	}
	// 切換選項時，level為該選項之級別值
	function chg_area(sel_obj, level){
		//清除新增地區填寫內容在隱藏
		$("#area_name").val("");
		$("#addAreaPanel").hide();
		//開啟讀取模式
		$("#loading").slideDown();
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
		        //填入清單
	        	fill_list(data);
	        	//更新級別
	        	refresh_level();
	    	}
	    });
	}
	//更新填入清單
	function fill_list(data){
		$(".list_tr").empty();
		for(i=0; i< data.length; i++){
			$("<tr><th scope=\"row\"><div class=\"row area_text_span"+ data[i]['nokey'] +"\" style=\"cursor: pointer;height: 38px;padding: 10px;\" onclick=\"editAreaToggle("+ data[i]['nokey'] +")\">"+ data[i]['area_name'] +"</div><div class=\"row edit_area edit_area_row"+ data[i]['nokey'] +"\" style=\"display:none\"><div class=\"col-md-10\"><input type=\"text\" class=\"form-control edit_field"+ data[i]['nokey'] +"\" data-nokey=\""+ data[i]['nokey'] +"\" value=\""+ data[i]['area_name'] +"\"></div><div class=\"col-md-2\"><a href=\"javascript:editArea("+ data[i]['nokey'] +");\" class=\"btn btn-secondary\">更名</a></div></div></th><td><a href=\"javascript:delArea("+ data[i]['nokey'] +")\" class=\"btn btn-secondary\">刪除</a></td></tr>").appendTo(".list_tr");
		}
	}
	//更新級別
	function refresh_level(){
		for(i=1; i<=4; i++){
			if($("#area_level"+i).val() !='-1'){
				//要修改的地區級別，通常為當局級別的下層
				level_global =(i+1);
			}
		}
	}
	//填入下級選項
	function fill_area(data, level){
		level +=1;
		if(level <=4){
			$("#area_level"+level+" option[value!='-1']").remove();
			for(i=0; i< data.length; i++){
				$("#area_level"+level).append($('<option>', {
				    value: data[i]['nokey'],
				    text: data[i]['area_name']
				}));

			}
			//alert(data['1']['area_name']);
			//$("#area_level"+level+" option[value!='-1']").remove();
		}
		$("#loading").slideUp();
	}
$(window).resize(function(){
	$("body").css("margin-top",$("nav").height()+20);
});
@endsection
<!-- jQuery ready 狀態內閉包內插 -->
@section('custom_ready_script')
	$("body").css("margin-top",$("nav").height()+20);
	//觸發縣市選單
	$('#area_level2').val(-1).change();
	//停用完成跳出確認
	@if(!is_null(session()->get('controll_back_msg')))
		$('#okAlert').modal("toggle");
	@endif
@endsection