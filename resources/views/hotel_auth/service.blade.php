@extends('hotel_auth.main_layout')

<!-- 標題 -->
@section('title', $Title)
@section('sub_fun', 'service')
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

<form action="service" method="POST">
	{{ csrf_field() }}
	<div style="width: 98%;margin: auto;">
		<table width="100%">
			@foreach($ServiceGroups as $i => $group)
			<tr>
				<td>
					<div class="row service_group" style="display: block;margin:0px">{{$group->service_name}}</div>
					<div class="row service_item" style="margin:0px;margin-bottom: 20px;">
						@foreach($ServiceItems as $j => $item)
							@if($item->parent == $group->nokey)
							<div class="col-md-2">
								<input type="checkbox" class="checkbox" value="{{$item->nokey}}" data-id="{{$item->nokey}}" id="service{{$item->nokey}}" name="service[]" @if(in_array($item->nokey,$HotelServiceID)) checked="" @endif>
								{{$item->service_name}}
							</div>
							@endif
						@endforeach
					</div>
				</td>
			</tr>
			@endforeach
		</table>
	</div>

		<button class="btn btn-lg btn-primary btn-block" type="submit">儲存資料</button>
</form>
<!-- main -->

@endsection

@section('instyle')

#photo_gallery > form > ul{
	padding:0;
	margin:0;
	min-width: 1460px;
}
#photo_gallery > form > ul > li{
	display:inline-block;
	margin:19px;
	margin-bottom: -10px;
	max-width: 250px;
    max-height: 250px;
}
#photo_category > ul{
	padding:0;
	margin:0;
}
#photo_category > ul > li{
	display:inline-block;
	margin:19px;
	margin-top: 5px;
}
.sel_pic{
	border: 3px solid #2E75B6;
}
.selItemFunRow{
	display:none;
}

.service_group{
	font-weight:bold;
	padding:10px;
}
.service_item{
	
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
	}else{
		editPics();
	}
}
//群組修改照片
function groupChg(selID){
	toCate =$('#'+selID).val();
	var count = $('input:checkbox:checked[name="sel_pic"]').length;
	if(count >0){
		alert('開始移動照片，請稍後');
	}
	$('input:checkbox:checked[name="sel_pic"]').each(function(i) { 
		key =$(this).data('id');
		$.ajax({
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
	        type: "POST",
	        url: 'photos_cate',
    		data: {nokey:key,cate:toCate},
	        success: function(data) {
				//結束提示
			    if (i+1 === count) {
			    	alert('全數移動完成');
			        window.location.reload();
			    }
	    	}
	    });
	});
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
//修正選擇圖片Label
function fixLabel(id){
	if($('#sel_pic'+id).prop("checked")){
		$('#sel_pic'+id).prop("checked",false);
	}else{
		$('#sel_pic'+id).prop("checked",true);
	}
	selPic(id,$('#sel_pic'+id).prop("checked"));
}
//選取圖片外框
function selPic(id,is_check){
	$('#pic_div'+id).removeClass('sel_pic');
	//is_chk =$('#sel_pic'+id).prop("checked");
	//alert(is_chk);
	if(is_check){
		//$('#sel_pic'+id).prop('checked',true);
		$('#pic_div'+id).addClass('sel_pic');
		$('#pic_chk_yes'+id).show();
		$('#pic_chk_no'+id).hide();
	}else{
		//$('#sel_pic'+id).prop('checked',false);
		$('#pic_chk_no'+id).show();
		$('#pic_chk_yes'+id).hide();
	}
	//selItemFunRow
	var count = $('input:checkbox:checked[name="sel_pic"]').length;
	flag_top =$("#photo_gallery > form > ul > li:first-of-type").offset().top
	if(count >0){
		$(".pic_flag").css('top',(flag_top+30));
		$('.selItemFunRow').show();
	}else{
		$(".pic_flag").css('top',(flag_top-35));
		$('.selItemFunRow').hide();
	}
}

@endsection

<!-- jQuery ready 狀態內閉包內插 -->
@section('custom_ready_script')
flag_left =$("#photo_gallery > form > ul > li:first-of-type").offset().left+80;
flag_top =$("#photo_gallery > form > ul > li:first-of-type").offset().top-5;
$(".pic_flag").css('left',flag_left).css('top',flag_top);

//啟動lightbox效果
$(".fancybox").fancybox({
	'width': 850,
    'height': 250,
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