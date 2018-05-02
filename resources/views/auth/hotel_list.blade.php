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
<!-- 下拉式選單組合 -->
<div class="row search_row">
	<div class="col-md-1 search-padding">
		<select class="form-control" id="state" name="state">
		  <option value='-1'>狀態</option>
		</select>
	</div>
	<div class="col-md-1 search-padding">
		<select class="form-control" id="ver" name="ver">
		  <option value='-1'>版本</option>
		</select>
	</div>
	<div class="col-md-1 search-padding">
		<select class="form-control" id="country" name="country">
		  <option value='-1'>國家</option>
		</select>
	</div>
	<div class="col-md-1 search-padding">
		<select class="form-control" id="area1" name="area1">
		  <option value='-1'>地區/縣市</option>
		</select>
	</div>
	<div class="col-md-1 search-padding">
		<select class="form-control" id="area2" name="area2">
		  <option value='-1'>鄉鎮/區</option>
		</select>
	</div>
	<div class="col-md-1 search-padding">
		<select class="form-control" id="ctrl" name="ctrl">
		  <option value='-1'>控管</option>
		</select>
	</div>
	<div class="col-md-1 search-padding">
		<select class="form-control" id="c_type" name="c_type">
		  <option value='-1'>合作種類</option>
		</select>
	</div>
	<div class="col-md-1 search-padding">
		<select class="form-control" id="room_count" name="room_count">
		  <option value='-1'>房間數量</option>
		</select>
	</div>
	<div class="col-md-2 search-padding">
		<input type="text" class="form-control" id="search" name="search" placeholder="關鍵字搜尋" value="">
	</div>
	<div class="col-md-1 search-padding">
		<span class="btn btn-secondary" style="width:100%;">搜尋</span>
	</div>
	<div class="col-md-1 search-padding">
		<a class="btn btn-secondary" style="width:100%;" href="hotel_add">新增</a>
	</div>
</div>
<!-- 清單內容 -->
<div class="row">
	<table class="table table-hover" style="margin-top:10px;">
	  <thead class="thead-light">
	    <tr>
	      <th scope="col">編號</th>
	      <th scope="col">飯店名稱</th>
	      <th scope="col">狀態</th>
	      <th scope="col">發票</th>
	      <th scope="col">版本</th>
	      <th scope="col">比價表</th>
	      <th scope="col">服務費</th>
	      <th scope="col">紅利</th>
	      <th scope="col">開房年月</th>
	      <th scope="col">房間數</th>
	      <th scope="col">合作種類</th>
	      <th scope="col">控管</th>
	      <th scope="col">權限</th>
	      <th scope="col">操作</th>
	    </tr>
	  </thead>
	  <tbody class="list_tr">
		<tr>
		      <th scope="row">1234</th>
		      <td>高雄民宿-不老溫泉長青溫泉渡假山莊</td>
		      <td>正常</td>
		      <td>甲</td>
		      <td>CA</td>
		      <td>16-09</td>
		      <td>10/15</td>
		      <td>10%</td>
		      <td>16-12</td>
		      <td>150</td>
		      <td>住宿卷</td>
		      <td>立即訂房</td>
		      <td>權限</td>
		      <td>
		      	<span class="btn btn-secondary">修改</span>
		      	<span class="btn btn-secondary">刪除</span>
		      </td>
	    </tr>
	  </tbody>
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
	.search-padding{
		padding-left: 5px;
		padding-right: 5px;
	}
	.search-padding select{
		
	}
@endsection
<!-- js獨立區塊腳本 -->
@section('custom_script')
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