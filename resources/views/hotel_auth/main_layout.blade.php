<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('hotel_name') 管理後台 - @yield('title')</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="/css/checkbox.css">
		<style type="text/css">
			html{

			}
			body{
				font-family: Microsoft JhengHei;
			}
			header{
				
			}
			.container{
				max-width: 1440px;color:#000;
				min-width: 98%;
				padding: 0px;
				margin:auto;
			}
			.container_padding{
				padding-left: 15px;
				padding-right: 15px;
			}
			.center {
			    margin: auto;
			}
			.input-group-text{
				color:#000;
			}
			.table td, .table th {
				padding:.2rem;
			}
			#top_nav ul > li{
				display:inline;
				margin:0px;
				margin-left: 5px;
				margin-right: 5px;
			}
			#top_nav ul{
				margin-bottom:0rem;
				margin-top:20px;
				padding-left: 0px;
			}
			#top_nav ul > li > a{
				color:#000;
			}
			#sys_btn{
				width:100%;
				height:70px;
			}
			#sys_btn ul{
				margin-bottom:5px;
				margin-top:20px;
				display: inline-block;
				width:100%;
			}
			#sys_btn ul > li{
				display:inline-block;
				width:12.2%;
				color:#000;
				border: 0px;
			}
			#sys_btn ul > li > a{
				color: #000;
			}
			#subsys_btn{
				width:100%;
				height:70px;
			}
			#subsys_btn ul{
				margin-bottom:5px;
				margin-top:20px;
				display: inline-block;
				width:100%;
			}
			#subsys_btn ul > li{
				display:inline-block;
				width:12.2%;
				color:#000;
				border: 0px;
			}
			#subsys_btn ul > li > a{
				color: #000;
			}
			.btn-info{
				background: #c1e1ee;
			}
			.btn-info:hover{
				background-color: #8cd8f8;
			}
			#subsys_btn ul > .btn-warning{
				background-color: #f8e2a2;
			}
			@yield('instyle')
		</style>
        
    </head>
<body style="color: #000;">
	<header>
		<div class="container" style="background: #C1E1EE;max-width: 100%;padding-left: 15px;padding-right: 15px;height: 70px;">
			<div style="float: left;width:125px;height:62px;margin-right: 10px;"><img src="/pic/auth_layout_logo.png" alt=""></div>
			<div style="float: left;margin-top:15px;font-size: 20px;font-weight: bold;">@yield('hotel_name')管理系統</div>
			<div id="top_nav" style="width:500px;float: right;">
				<ul>
					<li>選單管理</li>
					<li>權限設定</li>
					<li>合約書</li>
					<li>awugo<->飯店留言</li>
					<li>員工X</li>
					<li><a href="logout" onclick="return confirm('確定要登出?')">登出</a></li>
				</ul>
			</div>
		</div>
		<div id="sys_btn">
			<ul class="container_padding">
				<li class="btn btn-warning"><a href="#">飯店資料</a></li>
				<li class="btn btn-info"><a href="#">房價表</a></li>
				<li class="btn btn-info"><a href="#">最新資料</a></li>
				<li class="btn btn-info"><a href="#">訂房紀錄</a></li>
				<li class="btn btn-info"><a href="#">費用管理</a></li>
				<li class="btn btn-info"><a href="#">訂單留言</a></li>
				<li class="btn btn-info"><a href="#">客戶評鑑</a></li>
				<li class="btn btn-info"><a href="#">訪客留言</a></li>
			</ul>
		</div>
		<div id="subsys_btn" style="margin-bottom: 5px;margin-top: -4px;">
			<ul class="container_padding" style="margin-bottom: 0px;margin-top: 0px;">
				<li class="btn btn-warning"><a href="#">基本資料</a></li>
				<li class="btn"><a href="#">照片上傳</a></li>
				<li class="btn"><a href="#">設施與服務</a></li>
				<li class="btn"><a href="#">客房設定</a></li>
			</ul>
		</div>
	</header>
	<div class="container">
		<!-- 錯誤訊息 -->
		@include('error_msg')

		@yield('content')
	</div>
		<footer>
			<p class="mt-5 mb-3 text-center text-muted" style="clear: both;">© 2017-2018 長龍科技股份有限公司　v0.2.1</p>
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
	        <button type="button" class="btn btn-secondary" onclick="window.location.href='/{{$Country}}/auth/logout'">確定登出</button>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- jQuery331 -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>

    <script type="text/javascript">
    	@yield('custom_script')
    	$(function(){
    		@yield('custom_ready_script')
			
    	});
    </script>
</body>
</html>