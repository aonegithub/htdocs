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
	<div class="col-md-2">
		<select class="form-control" id="area_level1" name="area_level1">
			@foreach($Countries as $key => $country)
				<option value='{{$country->nokey}}'>{{$country->area_name}}</option>
			@endforeach
		</select>
	</div>
	<div class="col-md-2">
		<select class="form-control" onchange="chg_area(this,2)" id="area_level2" name="area_level2">
		    <option value='-1'>-</option>
		  @foreach($Areas_level2 as $key => $area2)
				<option value='{{$area2->nokey}}'>{{$area2->area_name}}</option>
			@endforeach
		</select>
	</div>
	<div class="col-md-2">
		<select class="form-control" onchange="chg_area(this,3)" id="area_level3" name="area_level3">
		  <option value='-1'>-</option>
		</select>
	</div>
	<div class="col-md-2">
		<select class="form-control" onchange="chg_area(this,4)" id="area_level4" name="area_level4">
		  <option value='-1'>-</option>
		</select>
	</div>
	<div class="col-md-4">
		<div style="text-align:right;" >
			@if(in_array('40',$Auths))
			<a href="javascript:openAddArea()" class="btn btn-secondary">新增地區</a>
			@endif
			<a href="/{{@Country}}/auth/manager/area_list" class="btn btn-secondary">地區清單</a>
		</div>
	</div>
</div>
<!-- 隱藏讀取圖 -->
<div id="loading" class="row text-center" style="display:none">
	<img src="/pic/loading.gif" class="text-right center">
</div>
<!-- 隱藏新增地區欄 -->
<div id="addAreaPanel" class="row" style="margin:20px;display: none;">
	<div class="col-md-1 text-center">
		<span class="align-middle">地區名稱</span>
	</div>
	<div class="col-md-5">
		<input type="text" class="form-control" id="area_name" name="area_name" placeholder="請輸入地區名稱" value="">
	</div>
	<div class="col-md-2">
		<span class="btn btn-secondary" onclick="addArea()">新增</span>
	</div>
</div>

<table class="table table-hover" style="margin-top:10px;">
  <thead class="thead-light">
    <tr>
      <th scope="col">地區名稱</th>
      <th scope="col">飯店數量</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
  	@foreach($Countries as $key => $area)
    <tr>
      <th style="cursor: pointer;" scope="row" onclick="editArea({{ $area->nokey }})">{{ $area->area_name }}</th>
      <td>0</td>
      <td><a href="./area_del/" class="btn btn-secondary">刪除</a></td>
    </tr>
    @endforeach
  </tbody>
</table>

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
@endsection
<!-- js獨立區塊腳本 -->
@section('custom_script')
	// 編輯地區
	function editArea(nokey){
		alert(nokey);
	}
	// 新增地區打開選項
	function openAddArea(){
		$("#addAreaPanel").slideToggle();
	}
	// 新增地區
	function addArea(){
		alert('add_area');
	}
	// 切換選項時，level為該選項之級別值
	function chg_area(sel_obj, level){
		$("#loading").slideDown();
		sel_val =$(sel_obj).val();
		if(sel_val != '-1'){
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
		}else{
			for(i=level; i<=4; i++){
				$("#area_level"+(i+1)+" option[value!='-1']").remove();
			}
			$("#loading").slideUp();
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
			$("#loading").slideUp();
			//alert(data['1']['area_name']);
			//$("#area_level"+level+" option[value!='-1']").remove();
		}
	}
@endsection
<!-- jQuery ready 狀態內閉包內插 -->
@section('custom_ready_script')
	//停用完成跳出確認
	@if(!is_null(session()->get('controll_back_msg')))
		$('#okAlert').modal("toggle");
	@endif
@endsection