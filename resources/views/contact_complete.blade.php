@extends('common.base')
@include('common.header')
@include('common.side')
@section('content')
<article class="col-xl-11 px-2 horizontal-write-center" id="horizontal-write" data-barba="container" data-barba-namespace="contact-complete">
<header class="my-3">
@include('common.navi')
    <h2><img src="{{asset('images/scroll_ico.svg')}}">お問い合わせ送信完了</h2>
</header>
<section class="my-3 px-3 pt-2 contents-area">
<div class="waku-ads">
<!-- 問い合わせフォーム広告 -->
<ins class="adsbygoogle waku-ads-data"
     data-ad-client="ca-pub-4788194548114778"
     data-ad-slot="3590149277"
     data-ad-format="horizontal"
     data-full-width-responsive="false"></ins>
</div>
<p class="py-3 m-0">返信があるまで、しばらくお待ち下さい。</p>
</section>
</article>
@endsection
@include('common.footer')