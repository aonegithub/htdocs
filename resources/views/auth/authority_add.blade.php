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
	        修改完成
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">OK！</button>
	      </div>
	    </div>
	  </div>
	</div>
@endif
<div style="text-align:right;">
	<a href="/auth/manager/authority_add" class="btn btn-secondary">新增帳號</a>
	<a href="/auth/manager/authority_list" class="btn btn-secondary">返回清單</a>
</div>
	<form method="POST" role="form" action="./authority_edit">
		{{ csrf_field() }}
		<!-- 會員資料 -->
		<div class="form-group">
		    <label for="inputID">登入帳號為</label>
		    <h3>{{ $Manager->id }}</h3>
	  	</div>
		<div class="form-group">
		    <label for="inputUserID">使用人</label>
		    <input type="text" class="form-control" id="inputUserID" name="inputUserID" placeholder="使用者姓名" value="{{ $Manager->name }}">
	  	</div>
	  	<div class="pwdChg" style="display:none;">
			<div class="form-group">
			    <label for="exampleInputPassword1">新密碼</label>
			    <input type="password" class="form-control" id="exampleInputPassword1" name="exampleInputPassword1" placeholder="輸入密碼">
			</div>
			<div class="form-group">
			    <label for="exampleInputPassword2">確認新密碼</label>
			    <input type="password" class="form-control" name="exampleInputPassword2" id="exampleInputPassword2" placeholder="請重複密碼">
			</div>
		</div>
		<label class="custom-control custom-checkbox" style="text-align: right;">
		    <input name="editPW" id="editPW" type="checkbox" class="custom-control-input" value="editPW" onchange="togglePwd()">
		    <span class="custom-control-indicator"></span>修改密碼
		</label>
	  	<div class="form-group">
		    <label for="inputDepartment">部門</label>
		    <input type="text" class="form-control" id="inputDepartment" name="inputDepartment" placeholder="所屬部門" value="{{ $Manager->department }}">
	  	</div>
	  	<label class="custom-control custom-checkbox" style="text-align: right;">
		    <input name="enableAccount" id="enableAccount" type="checkbox" class="custom-control-input" value="enableAccount">
		    <span class="custom-control-indicator"></span>啟動帳號
		</label>
		<!-- 權限表 -->
		<div class="row">
			@foreach($Auth_root as $key => $root)
				<div class="col-md-6">
					<label class="custom-control custom-checkbox">
					    <input name="auth_chk[]" id="auth_chk[]" type="checkbox" class="custom-control-input auth_chk_{{ $root->nokey }}" value="{{ $root->nokey }}">
					    <span class="custom-control-indicator"></span>{{ $root->auth_name }}
					</label>
					@foreach($Auth_sub as $sub)
						@if($sub->auth_parent == $root->nokey)
							<div style="padding-left: 80px;">
								<label class="custom-control custom-checkbox">
								    <input name="auth_chk[]" id="auth_chk[]" type="checkbox" class="custom-control-input auth_chk_{{ $sub->nokey }}" value="{{ $sub->nokey }}" >
								    <span class="custom-control-indicator"></span>{{ $sub->auth_name }}
								</label>
							</div>
						@endif
					@endforeach
				</div>
		@if(($key+1)%2 == 0)
		</div>
		<div class="row">
		@endif
			@endforeach
		</div>
		<button type="submit" class="btn btn-secondary btn-lg btn-block">修改確定</button>
	</form>
@endsection

<!-- js獨立區塊腳本 -->
@section('custom_script')
function togglePwd(){
	$(".pwdChg").slideToggle();
}
@endsection
<!-- jQuery ready 狀態內閉包內插 -->
@section('custom_ready_script')
	@foreach($Manager_auth as $auth_id)
		//修改完成跳出確認
		@if(!is_null(session()->get('controll_back_msg')))
			$('#okAlert').modal("toggle");
		@endif
		//自動勾選是否已經啟動帳號
		@if($Manager->enable)
			$("#enableAccount").attr("checked", "checked");
		@endif
		//顯示自動勾選已擁有的權限
		$(".auth_chk_{{ $auth_id }}").attr("checked", "checked");
	@endforeach
@endsection