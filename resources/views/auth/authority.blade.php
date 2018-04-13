@extends('auth.main_layout')

<!-- 標題 -->
@section('title', $Title)

<!-- 導航按鈕按下狀態編號 -->
@section('nav_id', $Nav_ID)
<!-- 內容 -->
@section('content')
{{ session()->get('controll_back_msg') }}
	<form method="POST" role="form" action="./authority">
		{{ csrf_field() }}
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
@endsection
<!-- jQuery ready 狀態內閉包內插 -->
@section('custom_ready_script')
	@foreach($Manager_auth as $auth_id)
		$(".auth_chk_{{ $auth_id }}").attr("checked", "checked");
	@endforeach
@endsection