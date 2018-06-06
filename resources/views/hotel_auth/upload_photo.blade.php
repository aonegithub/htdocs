@extends('hotel_auth.main_layout')

<!-- 標題 -->
@section('title', $Title)
@section('sub_fun', 'photos')
<!-- 飯店名稱 -->
@section('hotel_name', $Hotel->name)

@section('content')
@if(!is_null(session()->get('controll_back_msg')))
    <div class="modal fade" id="okAlert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">編輯完成</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            已編輯成功
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">OK！</button>
          </div>
        </div>
      </div>
    </div>
@endif

<div style="width: 98%;text-align: right;margin: auto;"><a data-fancybox data-type="iframe" data-src="photos_plan" href="javascript:;" class="btn btn-primary btn-sm" data-toggle="lightbox">批次上傳圖片</a></div>
<div class="row" id="photo_gallery" name="photo_gallery" style="width: 98%;margin: auto;">
	<form action="">
	<ul>
		@foreach($Photos as $key => $photo)
		<li>
			<div style="width: 250px;height: 250px;">
				<div style="width:250px;height:150px;overflow: hidden;box-shadow:-5px -5px 10px #ebebeb;">
					<a data-fancybox data-type="image" data-src="/photos/800/{{$photo->name}}.{{$photo->picture_type}}" href="javascript:;" data-toggle="lightbox">
					<img src="/photos/250/{{$photo->name}}.{{$photo->picture_type}}" alt="">
					</a>
				</div>
				<div style="height:100px;">
					<div style="padding-left: 10px;">
						<input class="form-check-input" type="checkbox" value="" name="sel_pic" id="sel_pic{{$photo->nokey}}" style="position: relative;margin-left: 0;">
						<label class="form-check-label" for="sel_pic{{$photo->nokey}}">
						    選擇圖片
						</label>
					</div>
					<div class="input-group input-group-sm" style="margin-top: 5px;">
						<input id="pic_title" name="pic_title" type="text" class="form-control input-group-sm" placeholder="照片說明(用於行銷優化)" value="">
					</div>
					<div class="input-group input-group-sm" style="margin-top: 5px;">
					  <div class="input-group-prepend">
					    <span class="input-group-text" id="inputGroup-sizing-sm">排序值</span>
					  </div>
					  <input type="text" id="pic_sort" name="pic_sort" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="">
					</div>
				</div>
			</div>
		</li>
		@endforeach
	</ul>
	</form>
</div>
<!-- main -->

@endsection

@section('instyle')
#photo_gallery > form > ul{
	padding:0;
	margin:0;
}
#photo_gallery > form > ul > li{
	display:inline-block;
	margin:19px;
}
@endsection

<!-- js獨立區塊腳本 -->
@section('custom_script')

@endsection

<!-- jQuery ready 狀態內閉包內插 -->
@section('custom_ready_script')
//啟動lightbox效果
$("[data-fancybox]").fancybox({
	'width': 800,
    'height': 400,
    'transitionIn': 'elastic', // this option is for v1.3.4
    'transitionOut': 'elastic', // this option is for v1.3.4
    // if using v2.x AND set class fancybox.iframe, you may not need this
    'type': 'iframe',
    // if you want your iframe always will be 600x250 regardless the viewport size
    'fitToView' : false  // use autoScale for v1.3.4
});
//停用完成跳出確認
    @if(!is_null(session()->get('controll_back_msg')))
        $('#okAlert').modal("toggle");
    @endif
@endsection