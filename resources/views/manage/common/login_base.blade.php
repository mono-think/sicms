<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SiCMS管理ログイン</title>
<!-- Bootstrap -->
<link href="{{ asset('manage/css/bootstrap-4.4.1.css') }}" rel="stylesheet">
<link href="{{ asset('manage/css/sicms_common.css') }}" rel="stylesheet">
</head>
<body class="bg-white">
<!-- body code goes here -->
<header class="bg-dark text-white w-100 p-2">
  <h1 class="m-0 p-2">SiCMS管理ログイン</h1>
</header>
<input type="hidden" id="js_message" value="{{dispErrorMessage('m13')}}">
<div class="container my-5 p-5">
<!-- Display Event Message Info -->
@if(session()->has('disp_error_message'))
<div class="alert alert-danger w-75" role="alert">
    {{session('disp_error_message')}}
</div>
@endif
@if(session()->has('disp_message'))
<div class="alert alert-success w-75" role="alert">
    {{session('disp_message')}}
</div>
@endif
@yield('sub_content')
</div>
<footer class="w-100">
@yield('footer')
</footer>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="{{ asset('manage/js/jquery-3.4.1.min.js') }}"></script> 

<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="{{ asset('manage/js/popper.min.js') }}"></script> 
<script src="{{ asset('manage/js/bootstrap-4.4.1.js') }}"></script> 
@yield('other_js')
</body>
</html>