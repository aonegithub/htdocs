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
	        已停用此帳號
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">OK！</button>
	      </div>
	    </div>
	  </div>
	</div>
@endif
@if(in_array('34',$Auths))
<div style="text-align:right;">
	<a href="/auth/manager/area_add" class="btn btn-secondary">新增地區</a>
</div>
@endif

<table class="table table-hover" style="margin-top:10px;">
  <thead class="thead-light">
    <tr>
      <th scope="col">國家</th>
      <th scope="col">縣市</th>
      <th scope="col">地區</th>
      <th scope="col">刪除</th>
    </tr>
  </thead>
  <tbody>
  	@foreach($Area_parent as $key => $parent)
    <tr style="cursor: pointer;">
      <th scope="row" onclick="window.location.href='./area_edit/'">{{ $parent->area_name }}</th>
      <td onclick="window.location.href='./area_edit/'">台南市</td>
      <td onclick="window.location.href='./area_edit/'">永康區</td>
      <td onclick="window.location.href='./area_del/'">刪除</td>
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
@endsection
<!-- jQuery ready 狀態內閉包內插 -->
@section('custom_ready_script')
	//停用完成跳出確認
	@if(!is_null(session()->get('controll_back_msg')))
		$('#okAlert').modal("toggle");
	@endif
@endsection