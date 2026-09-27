@extends('common.base')
@include('common.header')
@include('common.side')
@section('content')
<article class="col-xl-11 px-2 horizontal-write-center hwc-index" id="horizontal-write" data-barba="container" data-barba-namespace="home">
<input type="hidden" id="latest_products" value="{{route('index.getupdate_products')}}" />
<input type="hidden" id="latest_company" value="{{route('index.getupdate_company')}}" />
<input type="hidden" id="latest_event" value="{{route('index.getupdate_event')}}" />
<input type="hidden" id="latest_content1" value="{{route('index.getupdate_content',1)}}" />
<input type="hidden" id="latest_content2" value="{{route('index.getupdate_content',2)}}" />
<input type="hidden" id="latest_content0" value="{{route('index.getupdate_content',0)}}" />
<section class="waku-ads">
<!-- indexページ広告 -->
<ins class="adsbygoogle waku-ads-data"
     data-ad-client="ca-pub-4788194548114778"
     data-ad-slot="5326206192"
     data-ad-format="horizontal"
     data-full-width-responsive="false"></ins>
</section>
<section class="d-flex flex-align flex-wrap ml-0 mr-2 mb-3" id="top_latest_div">
    <div class="p-2">
        <h2 class="bg-white waku waku-label-start">新着<br />情報</h2>
    </div>
</section>
<div class="ads-label">【公式支援】</div>
<div class="ml-0 mr-2 my-0 ads-area"></div>
<section class="d-flex flex-align flex-wrap ml-0 mr-2 my-3" id="top_contents_aera1_div">
    <div class="p-2">
        <h2 class="bg-white waku waku-label-start"><a href="{{route('front_products_category.index')}}">製品<br />事業</a></h2>
    </div>
</section>
<section class="d-flex flex-align flex-wrap ml-0 mr-2 my-3" id="top_contents_aera2_div">
    <div class="p-2">
        <h2 class="bg-white waku waku-label-start"><a href="{{route('company_shop.index')}}">企業<br />店</a></h2>
    </div>
</section>
<div class="ads-label">【公式支援】</div>
<div class="ml-0 mr-2 my-0 ads-area"></div>
<section class="d-flex flex-align flex-wrap ml-0 mr-2 my-3" id="top_contents_aera3_div">
    <div class="p-2">
        <h2 class="bg-white waku waku-label-start"><a href="{{route('front_event.index')}}">催し<br />報告</a></h2>
    </div>
</section>
<section class="d-flex flex-align flex-wrap ml-0 mr-2 my-3" id="top_contents_aera4_div">
    <div class="p-2">
        <h2 class="bg-white waku waku-label-start"><a href="{{route('front_content.index',1)}}">特集</a></h2>
    </div>
</section>
<div class="ads-label">【公式支援】</div>
<div class="ml-0 mr-2 my-0 ads-area"></div>
<section class="d-flex flex-align flex-wrap ml-0 mr-2 my-3" id="top_contents_aera5_div">
    <div class="p-2">
        <h2 class="bg-white waku waku-label-start"><a href="{{route('front_content.index',2)}}">報道</a></h2>
    </div>
</section>
<section class="d-flex flex-align flex-wrap ml-0 mr-2 my-3" id="top_contents_aera6_div">
    <div class="p-2">
        <h2 class="bg-white waku waku-label-start"><a href="{{route('front_content.index',0)}}">編集<br />後記</a></h2>
    </div>
</section>
<div class="p-3 vertical-write-r">今を乱し、基本から見直す自動車情報屋</div>
</article>
@endsection
@include('common.footer')