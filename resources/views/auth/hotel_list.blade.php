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
		  <option value='0'>上線</option>
		  <option value='1'>下線</option>
		  <option value='2'>關閉</option>
		</select>
	</div>
	<div class="col-md-1 search-padding">
		<select class="form-control" id="ver" name="ver">
		  <option value='-1'>版本</option>
		  <option value='A'>A</option>
		  <option value='B'>B</option>
		  <option value='C'>C</option>
		  <option value='D'>D</option>
		  <option value='G'>G</option>
		  <option value='A,CA'>A,CA</option>
		  <option value='B,C'>B,C</option>
		  <option value='BG,G'>BG,G</option>
		  <option value='D,C'>D,C</option>
		  <option value='DG,G'>DG,G</option>
		  <option value='DA,CA'>DA,CA</option>
		  <option value='A,CA,DA'>A,CA,DA</option>
		  <option value='B,C,D'>B,C,D</option>
		  <option value='BG,G,DG'>BG,G,DG</option>
		</select>
	</div>
	<div class="col-md-1 search-padding">
		<select class="form-control" id="country" name="country">
		  <option value='-1'>國家</option>
		  <option value='1'>台灣</option>
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
		  <option value='0'>立即訂房</option>
		  <option value='1'>客服訂房</option>
		</select>
	</div>
	<div class="col-md-1 search-padding">
		<select class="form-control" id="c_type" name="c_type">
		  <option value='-1'>合作種類</option>
		  <option value='合約'>合約</option>
		  <option value='住宿卷'>住宿卷</option>
		  <option value='約卷'>約卷</option>
		</select>
	</div>
	<div class="col-md-1 search-padding">
		<select class="form-control" id="room_count" name="room_count">
		  <option value='-1'>房間數量</option>
		  <option value='100'>100以上</option>
		  <option value='50-99'>50-99</option>
		  <option value='15-49'>15-49</option>
		  <option value='1-14'>1-14</option>
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
	  	@foreach($Hotels as $key => $hotel)
		  	@if($hotel->state==2)
				<tr style="color:#aeaeae!important">
	      	@elseif($hotel->state==1)
				<tr>
			@else
				<tr style="color:blue">
	      	@endif
		      <th scope="row">{{ sprintf("%05d",$hotel->nokey) }}</th>
		      <td>{{ $hotel->name }}</td>
		      <td>
		      	@if($hotel->state==0)
					上線
		      	@elseif($hotel->state==1)
					下線
				@else
					關閉
		      	@endif
		      </td>
		      <td>
		      	@if($hotel->invoice_type==0)
					甲
		      	@elseif($hotel->invoice_type==1)
					乙
				@else
					丙
		      	@endif
		      </td>
		      <td>{{ $hotel->version }}</td>
		      <td>--</td>
		      <td>
		      	@if($hotel->version=='CA')
					{{$hotel->fees_c}}
		      	@elseif($hotel->version=='AB')
					{{$hotel->fees_ab}}
				@else
					{{$hotel->fees_d}}
		      	@endif
		      </td>
		      <td>
		      	@if($hotel->version=='CA')
					{{$hotel->fees_c_bonus}}
		      	@elseif($hotel->version=='AB')
					{{$hotel->fees_ab_bonus}}
				@else
					{{$hotel->fees_d_bonus}}
		      	@endif
		      </td>
		      <td>--</td>
		      <td>{{$hotel->type_room}}</td>
		      <td>--</td>
		      <td>
		      	@if($hotel->control==0)
					立即訂房
				@else
					客服訂房
		      	@endif
		      </td>
		      <td>--</td>
		      <td>
		      	<a href="hotel_edit/{{ $hotel->nokey }}" class="btn btn-secondary">修改</a>
		      	<a href="javascript:disableHotel({{ $hotel->nokey }})" class="btn btn-secondary">關閉</a>
		      </td>
	    </tr>
	    @endforeach
	  </tbody>
	</table>
</div>
<div id="nav_pagerow">
{{ $Hotels->links('vendor.pagination.bootstrap-4') }}
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

//快捷關閉上線
	function disableHotel(key){
		<!-- $('#'+target).prop('disabled', true); -->
		if(confirm('確定要關閉？')){
			$.ajax({
		        headers: {
		            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
		        type: "POST",
		        url: 'hotel_disable/'+key,
		        data: {nokey:key},
		        success: function(data) {
		        	window.location.reload();
		        	<!-- $('#'+target).val(""); -->
		        	<!-- $('#'+target).val(data[0]['zip_code']); -->
		        	<!-- $('#'+target).prop('disabled', false); -->
		    	}
		    });
		}
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