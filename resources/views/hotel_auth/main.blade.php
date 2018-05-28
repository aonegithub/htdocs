<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" content="IE=9">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>飯店後台管理_儀錶板</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/signin.css">
        <style type="text/css">
          body{
            font-family: Microsoft JhengHei;
          }
        </style>
        <!--[if lt IE 9]>
        <script src="http://apps.bdimg.com/libs/html5shiv/3.7/html5shiv.min.js"></script>
        <script src="http://apps.bdimg.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
 
    <body>
        <div class="container" style="max-width:100%;">
            儀錶板主頁
              <!-- 錯誤訊息 -->
              @include('error_msg')
              <p class="mt-5 mb-3 text-center text-muted">&copy; 2017-2018 長龍科技股份有限公司</p>
        </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    </body>
</html>
