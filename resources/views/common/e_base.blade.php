<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{getSiteTitle()}}</title>
<meta name="title" content="{{getSiteTitle()}}">
<meta name="robots" content="noindex">
<meta name="googlebot" content="noindex">
<!-- ico -->
<link rel="shortcut icon" href="{{asset('images/favicon.ico')}}" type="image/x-icon">
<link rel="icon" href="{{asset('images/favicon_32x32.png')}}" sizes="32x32">
<link rel="icon" href="{{asset('images/favicon_48x48.png')}}" sizes="48x48">
<link rel="icon" href="{{asset('images/favicon_96x96.png')}}" sizes="96x96">
<link rel="icon" href="{{asset('images/favicon_144x144.png')}}" sizes="144x144">
<!-- ico -->
<!-- Bootstrap CSS -->
<link href="{{ asset('css/bootstrap-4.4.1.min.css') }}" rel="stylesheet">
<!-- Bootstrap CSS -->
<!-- Common CSS -->
@if (request()->getHttpHost() === 'sicms.local')
<link href="{{ asset('css/revoltis-css.css') }}" rel="stylesheet">
@else
<link href="{{ asset('css/revoltis-css.min.css') }}" rel="stylesheet">
@endif
<!-- Common CSS -->
</head>
<body class="bg-white w-100">
@yield('header')
<main class="row m-0" data-barba="wrapper">
@yield('side')
@yield('content')
</main>
@yield('footer')
<a href="#" class="navi-top">先頭へ戻る</a>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="{{ asset('js/popper.min.js') }}"></script> 
<script src="{{ asset('js/bootstrap-4.4.1.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/@barba/core"></script>
<script src="{{ asset('js/validate-forms.min.js') }}"></script>
</body>
</html>
