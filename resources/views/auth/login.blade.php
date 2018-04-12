<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Awugo總管理後台_登入</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/signin.css">
        <style type="text/css">
          body{
            font-family: Microsoft JhengHei;
          }
        </style>
        
    </head>
 
    <body>
        <div class="container">
            <form class="form-signin" method="POST" role="form" action="./login">
                {{ csrf_field() }}
              <img src="../pic/auth_sign_logo.png" alt="">
              <h1 class="h3 mb-3 font-weight-normal">總管理後台</h1>
              <label for="inputID" class="sr-only">請輸入帳號</label>
              <input type="text" id="inputID" name="inputID" class="form-control" placeholder="輸入帳號" required autofocus>
              <label for="inputPassword" class="sr-only">請輸入密碼</label>
              <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="輸入密碼" required>
              <div class="checkbox mb-3 d-none">
                <label>
                  <input type="checkbox" value="remember-me"> Remember me
                </label>
              </div>
              <button class="btn btn-lg btn-primary btn-block" type="submit">登　入</button>
              <p class="mt-5 mb-3 text-center text-muted">&copy; 2017-2018 長龍科技股份有限公司</p>
            </form>
        </div>

    <!-- jQuery331 -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <!-- <script type="text/javascript">$(function(){alert(1);});</script> -->
    <!-- Bootstrap4.1 -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>

    </body>
</html>
