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
	        新增完成，回到新增頁面繼續新增
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">OK！</button>
	      </div>
	    </div>
	  </div>
	</div>
@endif
<div style="text-align:right;">
	<a href="/{{$Country}}/auth/manager/authority_add" class="btn btn-secondary">新增帳號</a>
	<a href="/{{$Country}}/auth/manager/authority_list" class="btn btn-secondary">返回清單</a>
</div>
	<form method="POST" role="form" action="/{{$Country}}/auth/manager/authority_add">
		{{ csrf_field() }}
		<!-- 會員資料 -->
		<div class="form-group row">
		    <label for="inputID" class="col-sm-1 col-form-label">登入帳號</label>
		    <div class="col-sm-11">
		    	<input type="text" class="form-control" id="inputID" name="inputID" placeholder="登入帳號" value="">
		    </div>
	  	</div>
		<div class="form-group row">
		    <label for="inputUserID" class="col-sm-1 col-form-label">使用人</label>
		    <div class="col-sm-11">
		    	<input type="text" class="form-control" id="inputUserID" name="inputUserID" placeholder="使用者姓名" value="">
			</div>
	  	</div>
	  	<div class="pwdChg">
			<div class="form-group row">
			    <label for="exampleInputPassword1" class="col-sm-1 col-form-label">輸入密碼</label>
			    <div class="col-sm-11">
			    	<input type="password" class="form-control" id="exampleInputPassword1" name="exampleInputPassword1" placeholder="輸入密碼">
			    </div>
			</div>
			<div class="form-group row">
			    <label for="exampleInputPassword2" class="col-sm-1 col-form-label">確認密碼</label>
			    <div class="col-sm-11">
			    	<input type="password" class="form-control" name="exampleInputPassword2" id="exampleInputPassword2" placeholder="請重複密碼">
			    </div>
			</div>
		</div>
	  	<div class="form-group row">
		    <label for="inputDepartment" class="col-sm-1 col-form-label">部門</label>
		    <div class="col-sm-11">
		    	<input type="text" class="form-control" id="inputDepartment" name="inputDepartment" placeholder="所屬部門" value="">
		    </div>
	  	</div>
	  	<label class="custom-control custom-checkbox" style="text-align: right;">
		    <input name="enableAccount" id="enableAccount" type="checkbox" class="custom-control-input" value="enableAccount" checked="checked">
		    <span class="custom-control-indicator"></span>啟動帳號
		</label>
		<!-- 全選權限 -->
		<div style="">
			<label class="custom-control custom-checkbox">
			    <input name="auth_all" id="auth_all" type="checkbox" class="custom-control-input" value="" >
			    <span class="custom-control-indicator"></span>權限全選
			</label>
		</div>
		<!-- 權限表 -->
		<div class="row">
			@foreach($Auth_root as $key => $root)
				<div class="col-md-6">
					<label class="custom-control custom-checkbox">
					    <input data-all="auth" data-type="root" name="auth_chk[]" id="auth_chk[]" type="checkbox" class="custom-control-input auth_chk_{{ $root->nokey }}" value="{{ $root->nokey }}">
					    <span class="custom-control-indicator"></span>{{ $root->auth_name }}
					</label>
					@foreach($Auth_sub as $sub)
						@if($sub->auth_parent == $root->nokey)
							<div style="padding-left: 80px;">
								<label class="custom-control custom-checkbox">
								    <input data-all="auth" data-group="auth_{{ $root->nokey }}" name="auth_chk[]" id="auth_chk[]" type="checkbox" class="custom-control-input auth_chk_{{ $sub->nokey }}" value="{{ $sub->nokey }}" >
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
		<button type="submit" class="btn btn-secondary btn-lg btn-block">確定新增</button>
	</form>
@endsection

<!-- js獨立區塊腳本 -->
@section('custom_script')
@endsection
<!-- jQuery ready 狀態內閉包內插 -->
@section('custom_ready_script')
	//小群組全選
	$("input[data-type='root']").change(function(){
		//alert(0);
		root_id ="input[data-group='auth_"+ $(this).val() +"']";
		//alert(root_id);
		if($(this).prop("checked")){
			$(root_id).prop("checked", true);
		}else{
			$(root_id).prop("checked", false);
		}
	});
	//全選權限
	$("#auth_all").change(function(){
		if($(this).prop("checked")){
			$("input[data-all=auth]").prop("checked", true);
		}else{
			$("input[data-all=auth]").prop("checked", false);
		}
	});
	//新增完成跳出確認
	@if(!is_null(session()->get('controll_back_msg')))
		$('#okAlert').modal("toggle");
	@endif
@endsection