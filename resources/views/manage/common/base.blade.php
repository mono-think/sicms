<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<meta name="googlebot" content="noindex">
<title>SiCMS管理</title>
<!-- Bootstrap -->
<link href="{{ asset('manage/css/bootstrap-4.4.1.min.css') }}" rel="stylesheet">
@if (request()->getHttpHost() === 'sicms.local')
<link href="{{ asset('manage/css/sicms_common.css') }}" rel="stylesheet">
@else
<link href="{{ asset('manage/css/sicms_common.min.css') }}" rel="stylesheet">
@endif
</head>
<body class="bg-white text-wrap text-break">
<!-- body code goes here -->
<header class="bg-dark text-white w-100">
@yield('header')
</header>
<input type="hidden" id="js_message" value="{{dispErrorMessage('m13')}}">
<div class="container-fluid base_body">
  <div class="row">
    <div class="col-xl-2 main_menu_area m-0 p-0">
<!-- Enter Side Info -->
<div class="card mx-0 my-2">
  <div class="card-header"><strong>選択企業</strong></div>
  <div class="card-body">
    <h5 class="card-title">{{$login_info['company_name']}}</h5>
@if (isset($login_info['company_branch_name']) && !empty($login_info['company_branch_name']))
    <p class="card-text m-0">【支店】{{$login_info['company_branch_name']}}</p>
@endif
@if (isset($login_info['company_department_name']) && !empty($login_info['company_department_name']))
    <p class="card-text m-0">【部署】{{$login_info['company_department_name']}}</p>
@endif
@if (isset($login_info['products_name']) && !empty($login_info['products_name']))
    <p class="card-text m-0">【製品】{{$login_info['products_name']}}</p>
@endif
@if (isset($login_info['products_detail_name']) && !empty($login_info['products_detail_name']))
    <p class="card-text m-0">【詳細】{{$login_info['products_detail_name']}}</p>
@endif
  </div>
</div>
<!-- Enter Side Info -->
@if (checkAuthCompanyTypeAdmin($login_info['company_type']))
    @if (checkAuthAcntAdmin($login_info['acnt_auth_no']))
        @include('manage.common.side_manage_admin')
    @else
        @include('manage.common.side_manage_other')
    @endif
@elseif (checkAuthCompanyTypeCustomer($login_info['company_type']))
        @include('manage.common.side_customer')
@else
        @include('manage.common.side_default')
@endif
    </div>
    <div class="col-xl-10">
<!-- Display Event Message Info -->
@if ($errors->any())
    <div class="alert alert-danger w-75" role="alert">
<?php $error_array = array_unique($errors->all());?>
@foreach ($error_array as $error)
        <p class="my-0">{{ $error }}</p>
@endforeach
    </div>
@endif
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
<!-- Enter Main Contents -->
@yield('content')
@yield('func_modal')
@yield('branch_modal')
@yield('department_modal')
@yield('acnt_modal')
@yield('image_modal')
    </div>
  </div>
</div>
<footer class="w-100">
@yield('footer')
</footer>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="{{ asset('manage/js/jquery-3.4.1.min.js') }}"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="{{ asset('manage/js/popper.min.js') }}"></script> 
<script src="{{ asset('manage/js/bootstrap-4.4.1.min.js') }}"></script> 
@if (request()->getHttpHost() === 'sicms.local')
<script src="{{ asset('manage/js/sicms_common.js') }}"></script> 
@else
<script src="{{ asset('manage/js/sicms_common.min.js') }}"></script> 
@endif
@yield('other_js')
</body>
</html>