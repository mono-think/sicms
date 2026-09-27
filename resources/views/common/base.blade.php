<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{getSiteTitle($form_data)}}</title>
<meta name="title" content="{{getSiteTitle($form_data)}}">
<meta name="description" content="{{getSiteDescription($form_data)}}">
<meta name="robots" content="{{getSiteRobots(Route::currentRouteName())}}">
<meta name="keywords" content="{{getSiteWord($form_data)}}">
<link rel="canonical" href="{{getSiteCanonical($form_data)}}">
<link rel="image_src" href="{{getHeaderImagePath($form_data)}}">
<!-- Twitter & facebook -->
<meta property="fb:app_id" content="{{getFbAppId()}}">
<meta property="og:site_name" content="{{getSiteName()}}">
<meta property="og:title" content="{{getSiteTitle($form_data)}}">
<meta property="og:description" content="{{getSiteDescription($form_data)}}">
<meta property="og:url" content="{{getSiteCanonical($form_data)}}">
<meta property="og:type" content="{{getOgpType()}}">
<meta property="og:image" content="{{getHeaderImagePath($form_data)}}">
<meta property="og:image:width" content="{{getHeaderImageWidth($form_data)}}">
<meta property="og:image:height" content="{{getHeaderImageHeight($form_data)}}">
<meta name="twitter:card" content="{{getSiteTwitterCard()}}">
<meta name="twitter:site" content="{{getSiteName()}}">
<meta name="twitter:title" content="{{getSiteTitle($form_data)}}">
<meta name="twitter:description" content="{{getSiteDescription($form_data)}}">
<meta name="twitter:url" content="{{getSiteCanonical($form_data)}}">
<meta name="twitter:image" content="{{getHeaderImagePath($form_data)}}">
<!-- Twitter & facebook -->
<!-- 検索サムネイル表示 -->
<meta name="thumbnail" content="{{getHeaderImagePath($form_data)}}" />
<PageMap>
    <DataObject type="thumbnail">
      <Attribute name="src" value="{{getHeaderImagePath($form_data)}}"/>
      <Attribute name="width" value="{{getHeaderImageWidth($form_data)}}"/>
      <Attribute name="height" value="{{getHeaderImageHeight($form_data)}}"/>
    </DataObject>
</PageMap>
<!-- 検索サムネイル表示 -->
<!-- schema.org -->
<div itemscopeitemtype="https://schema.org/WebSite">
    <link itemprop="url" href="{{getSiteCanonical($form_data)}}">
    <meta itemprop="name" content="{{getSiteTitle($form_data)}}">
    <meta itemprop="description" content="{{getSiteDescription($form_data)}}">
    <span itemprop="author" itemscope itemtype="http://schema.org/Organization">
        <link itemprop="name" content="{{getSiteName()}}">
    </span>
    <link itemprop="thumbnailUrl" href="{{getHeaderImagePath($form_data)}}">
    <span itemprop="thumbnail" itemscope itemtype="http://schema.org/ImageObject">
        <link itemprop="url" href="{{getHeaderImagePath($form_data)}}">
        <meta itemprop="width" content="{{getHeaderImageWidth($form_data)}}">
        <meta itemprop="height" content="{{getHeaderImageHeight($form_data)}}">
    </span>
    <meta itemprop="genre" content="Autos&amp;Vehicles">
</div>
<!-- schema.org -->
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
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-68311701-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  window.GA_MEASUREMENT_ID = 'UA-68311701-1'
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-68311701-1');
</script>
</head>
<body class="bg-white w-100">
<input type="hidden" id="getsupporter" value="{{route('common.getsupporter')}}">
<input type="hidden" id="addpv" value="{{route('common.addpv')}}">
<input type="hidden" id="getpv" value="{{route('common.getpv')}}">
<input type="hidden" id="latest_all" value="{{route('index.getupdate')}}" />
<input type="hidden" id="js_message" value="{{dispErrorMessage('m13')}}">
@yield('header')
<main class="row m-0" data-barba="wrapper">
@yield('side')
@yield('content')
</main>
@yield('footer')
@include('common.alert_modal')
<a href="#" class="navi-top">先頭へ戻る</a>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="{{ asset('js/popper.min.js') }}"></script> 
<script src="{{ asset('js/bootstrap-4.4.1.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/@barba/core"></script>
<script src="{{ asset('js/validate-forms.min.js') }}"></script>
@if (request()->getHttpHost() === 'sicms.local')
<script src="{{ asset('js/revoltis-common.js') }}"></script>
@else
<script src="{{ asset('js/revoltis-common.min.js?v=1') }}"></script>
@endif
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
</body>
</html>
