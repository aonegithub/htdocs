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

<div style="width: 98%;text-align: center;margin: auto;">
	<div id="photo_category" style="float:left;">
		<ul>
			<li><a href="photos">所有照片( {{$Photos->count()}} )</a></li>
			<li><a href="photos?cate=1">環境設施( {{$Category_Counts[0]}} )</a></li>
			<li><a href="photos?cate=2">餐飲( {{$Category_Counts[1]}} )</a></li>
			<li><a href="photos?cate=3">溫泉SPA( {{$Category_Counts[2]}} )</a></li>
			<li><a href="photos?cate=4">客房( {{$Category_Counts[3]}} )</a></li>
			<li><a href="photos?cate=-1">其他( {{$Category_Counts[4]}} )</a></li>
		</ul>
	</div>
	<div style="float:right; width: 140px;height: 50px;">
		<a data-fancybox data-type="iframe" data-src="photos_plan" href="javascript:;" class="btn btn-primary btn-sm" data-toggle="lightbox">批次上傳圖片</a>
	</div>
</div>
<div class="selItemFunRow" style="clear:both;text-align:right;margin: auto;margin-right: 35px;">
<!-- 		<a href="javascript:editPics()" class="btn btn-success btn-sm">修改照片資訊</a> -->
		<a href="javascript:delPics()" class="btn btn-danger btn-sm">刪除所勾選照片</a>
		將勾選的照片歸類到
		<select id="ver" name="ver">
	  	  	<option value='1'>環境設施</option>
		    <option value='2'>餐飲</option>
		    <option value='3'>溫泉SPA</option>
		    <option value='4'>客房</option>
		    <option value='-1'>其他</option>
	  	</select>
	  	<a href="javascript:void(0)" class="btn btn-primary btn-sm">確定</a>
	</div>
<div class="row" id="photo_gallery" name="photo_gallery" style="width: 98%;margin: auto;">
	<form action="">
	<ul>
		@foreach($Photos as $key => $photo)
		<li>
			<div style="width: 250px;height: 250px;">
				<div style="width:250px;height:150px;overflow: hidden;box-shadow:-5px -5px 10px #ebebeb;" id="pic_div{{$photo->nokey}}">
					<a data-fancybox data-type="iframe" data-src="photos_editplan?id={{$photo->nokey}}" href="javascript:;" data-toggle="lightbox">
					<img src="/photos/250/{{$photo->name}}.{{$photo->picture_type}}" alt="">
					</a>
				</div>
				<div style="height:100px;">
					<div style="float:left;padding-left: 10px;margin-top:5px;margin-bottom: 5px;">
						<input class="form-check-input" type="checkbox" value="{{$photo->nokey}}" name="sel_pic" id="sel_pic{{$photo->nokey}}" data-id="{{$photo->nokey}}" onchange="selPic({{$photo->nokey}})" style="position: relative;margin-left: 0;">
						<label class="form-check-label" for="sel_pic{{$photo->nokey}}" style="cursor: pointer;">
						    選擇圖片
						</label>
					</div>
					<div style="float:right;padding-right: 5px;margin-top:5px;margin-bottom: 5px;">
						<a href="javascript:delPic({{$photo->nokey}})">刪除</a>
					</div>
					<div class="input-group input-group-sm" style="margin-top: 5px;">
						<input id="pic_title{{$photo->nokey}}" name="pic_title{{$photo->nokey}}" type="text" class="form-control input-group-sm" placeholder="照片說明(用於行銷優化)" value="{{$photo->title}}" onblur="editPics()">
					</div>
					<div class="input-group input-group-sm" style="margin-top: 5px;">
					  <div class="input-group-prepend">
					    <span class="input-group-text" id="inputGroup-sizing-sm">排序值</span>
					  </div>
					  <input type="number" id="pic_sort{{$photo->nokey}}" name="pic_sort{{$photo->nokey}}" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="{{$photo->sort}}" onblur="editPics()" onkeyup="numcheck(this.id,this)">
					</div>
					<!-- ** -->
					<div class="input-group input-group-sm" style="margin-top: 5px;">
					  <div class="input-group-prepend">
					    <span class="input-group-text" id="inputGroup-sizing-sm">分類</span>
					  </div>
						  <span class="form-control" style="border: 0px;">
							<select class="form-control" id="ver" name="ver" style="width:105%;" onchange="changeCate({{$photo->nokey}},this)">
						  	  	<option value='1'@if($photo->category==1) selected='' @endif>環境設施</option>
							    <option value='2'@if($photo->category==2) selected='' @endif>餐飲</option>
							    <option value='3'@if($photo->category==3) selected='' @endif>溫泉SPA</option>
							    <option value='4'@if($photo->category==4) selected='' @endif>客房</option>
							    <option value='-1'@if($photo->category==-1) selected='' @endif>其他</option>
						  	</select>
						</span>
					</div>
				</div>
			</div>
		</li>
		@endforeach
	</ul>
	<div class="selItemFunRow" style="text-align: right;margin-right: 35px;margin-top: 30px;">
<!-- 		<a href="javascript:editPics()" class="btn btn-success btn-sm">修改照片資訊</a> -->
		<a href="javascript:delPics()" class="btn btn-danger btn-sm">刪除所勾選照片</a>
		將勾選的照片歸類到
		<select id="ver" name="ver">
	  	  	<option value='1'>環境設施</option>
		    <option value='2'>餐飲</option>
		    <option value='3'>溫泉SPA</option>
		    <option value='4'>客房</option>
		    <option value='-1'>其他</option>
	  	</select>
	  	<a href="javascript:void(0)" class="btn btn-primary btn-sm">確定</a>
	</div>
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
#photo_category > ul{
	padding:0;
	margin:0;
}
#photo_category > ul > li{
	display:inline-block;
	margin:19px;
}
.sel_pic{
	border: 2px solid red;
}
.selItemFunRow{
	display:none;
}
@endsection

<!-- js獨立區塊腳本 -->
@section('custom_script')
//驗證數字，專屬特地給IE享用
function numcheck(id,time){
	var re = /^[0-9]+$/;
	if (!re.test(time.value)){
		alert("只能輸入數字");
	  	document.getElementById(id).value="0";
	}
}
//修改照片分類
function changeCate(key,obj){
	$.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: 'photos_cate',
        data: {nokey:key,cate:$(obj).val()},
        success: function(data) {
			window.location.reload();
    	}
    });
}
//修改照片資訊
function editPics(){
	var count = $('input:checkbox[name="sel_pic"]').length;

	$('input:checkbox[name="sel_pic"]').each(function(i) { 
		pTitle =$('#pic_title'+this.value).val();
		pSort =$('#pic_sort'+this.value).val();
		$.ajax({
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
	        type: "POST",
	        url: 'photos_edit',
	        data: {nokey:this.value,title:pTitle,sort:pSort},
	        success: function(data) {

	    	}
	    });
	});
}
//刪除勾選圖片
function delPics(){
	if(confirm('確定要刪除所勾選照片？')){
		var count = $('input:checkbox:checked[name="sel_pic"]').length;
		if(count >0){
			alert('開始刪除，請稍後');
		}
		$('input:checkbox:checked[name="sel_pic"]').each(function(i) { 
			$.ajax({
		        headers: {
		            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
		        type: "POST",
		        url: 'photos_del',
		        data: {nokey:this.value},
		        success: function(data) {
					//結束提示
				    if (i+1 === count) {
				    	alert('全數刪除完成');
				        window.location.reload();
				    }
		    	}
		    });
		});
	}
}
//刪除圖片
function delPic(key){
	if(confirm('確定要刪除此照片？')){
		$.ajax({
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
	        type: "POST",
	        url: 'photos_del',
	        data: {nokey:key},
	        success: function(data) {
	        	window.location.reload();
	    	}
	    });
	}
}
//選取圖片外框
function selPic(id){
	$('#pic_div'+id).removeClass('sel_pic');
	if($('#sel_pic'+id).prop("checked")){
		$('#pic_div'+id).addClass('sel_pic');
	}
	//selItemFunRow
	var count = $('input:checkbox:checked[name="sel_pic"]').length;
	if(count >0){
		$('.selItemFunRow').show();
	}else{
		$('.selItemFunRow').hide();
	}
}

@endsection

<!-- jQuery ready 狀態內閉包內插 -->
@section('custom_ready_script')
//啟動lightbox效果
$("[data-fancybox]").fancybox({
	'width': 800,
    'height': 700,
    'transitionIn': 'elastic', // this option is for v1.3.4
    'transitionOut': 'elastic', // this option is for v1.3.4
    // if using v2.x AND set class fancybox.iframe, you may not need this
    'type': 'iframe',
    // if you want your iframe always will be 600x250 regardless the viewport size
    'fitToView' : false,  // use autoScale for v1.3.4
    afterClose  : function() { 
    	//關閉後自動重整
        window.location.reload();
    }
});
//停用完成跳出確認
    @if(!is_null(session()->get('controll_back_msg')))
        $('#okAlert').modal("toggle");
    @endif
@endsection