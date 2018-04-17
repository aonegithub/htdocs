<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Awugo總管理後台 - @yield('title')</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/checkbox.css">
		<style type="text/css">
			body{
				font-family: Microsoft JhengHei;
			}
			.container_width{
				width:90%;
			}
			.btn-no-border{
				border-color:#FFF;
			}
			.nav{
    			margin: auto;
			}
			#nav_logout{
				position: relative;
  top: 50%;
  float: right;
  transform: translateY(30%);
			}
			#nav_item{
				background: #FFF;
			}
			#top{
				width:100%;
				height:65px;
				background: #C2E1EC;
			}
			#top_container{
				margin: auto;
			}
			#top_container_title{
				width: 300px;
			    font-size: 20pt;
			    box-sizing: content-box;
			    	-webkit-box-sizing: content-box;
			    	-moz-box-sizing: content-box;
			    padding: 10px;
			}
			@yield('instyle')
		</style>
        
    </head>
<body style="margin-top:150px">
	<header>
		<nav class="navbar navbar-default fixed-top" style="padding:0;box-shadow: 0 6px 6px -2px #cacaca;background-color: white;">
			<div id="top">
				<div id="top_container" class="container_width">
					<div id="top_container_logo" style="float:left;"><img src="/pic/auth_layout_logo.png" alt=""></div>
					<div id="top_container_title" style="float:left;">訂房總管理系統</div>
					<div id="nav_logout" class="align-middle">
						<span class="align-middle" role="button" aria-pressed="true" id="top-nav-36" href="#"> {{ session()->get('manager_name') }} 您好！</span>
						<a id="logout" class="btn btn-outline-secondary btn-no-border btn-smalign-middle" role="button" aria-pressed="true" id="top-nav-36">登出</a>
					</div>
				</div>
			</div>
			<div id="nav_item" class="nav container_width" style="margin-bottom: 5px;">
			  	<a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-1" href="#">飯店管理</a>
			  	<a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-2" href="#">費用管理</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-3" href="#">訂房成功</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-4" href="#">訂房查詢</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-5" href="#">訂單留言</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-6" href="#">會員管理</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-7" href="#">飯店留言</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-8" href="#">通知飯店</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-9" href="#">聯絡我們</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-10" href="./main">最新資料</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-11" href="#">公司發票</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-12" href="#">飯店發票</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-13" href="#">紅利點數</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-14" href="#">景點設定</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-15" href="#">電子報　</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-16" href="#">熱門地點</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-17" href="#">設施服務</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-18" href="#">客房設施</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-19" href="#">傳真紀錄</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-20" href="#">住宿評鑑</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-21" href="#">住宿用卷</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-22" href="#">追蹤管理</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-23" href="#">飯店加盟</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-24" href="#">電話訂單</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-25" href="#">比價列表</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-26" href="#">待辦事項</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-27" href="#">合約管理</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-28" href="#">系統設定</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-29" href="/auth/manager/authority_list">權限管理</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-30" href="#">廣告刊登</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-31" href="#">團體預約</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-32" href="#">流量分析</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-33" href="#">買貴回報</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-34" href="#">網站編輯</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-35" href="#">網站後台</a>
			    <a class="btn btn-outline-secondary btn-no-border btn-sm" role="button" aria-pressed="true" id="top-nav-36" href="#">網站前台</a>
			</div>
		</nav>
	</header>
	<div class="container">
		<!-- 錯誤訊息 -->
		@include('error_msg')

		@yield('content')
	</div>
		<footer>
			<p class="mt-5 mb-3 text-center text-muted" style="clear: both;">© 2017-2018 長龍科技股份有限公司</p>
		</footer>
	<!-- Modal -->
	<div class="modal fade" id="logoutAlert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">警告！</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        此動作即將登出，資料儲存好了嗎？
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">按錯</button>
	        <button type="button" class="btn btn-secondary" onclick="window.location.href='/auth/logout'">確定登出</button>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- jQuery331 -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript">
    	@yield('custom_script')
    	$(function(){
    		@yield('custom_ready_script')
    		//判斷頁面導航紐按下樣式
    		$("#top-nav-@yield('nav_id')").addClass("active");
    		// 浮動確認視窗
    		$('#logout').click(function () {
  				$('#logoutAlert').modal("toggle");
			});
    	})
    </script>
    <!-- <script type="text/javascript">$(function(){alert(1);});</script> -->
    <!-- Bootstrap4.1 -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</body>
</html>