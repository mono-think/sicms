@extends('common.base')
@include('common.header')
@include('common.side')
@section('content')
<article class="col-xl-11 px-2 horizontal-write-center" id="horizontal-write" data-barba="container" data-barba-namespace="company-shop-news">
<header class="my-3">
@include('common.navi')
    <h2><img src="{{asset('images/shiro_ico.svg')}}" alt="{{$detail_form_data->comp_branch_news_title}}" />{{$detail_form_data->comp_branch_news_title}}【{{$detail_form_data->comp_branch_name}}】</h2>
</header>
<section class="my-3 px-3 py-0 contents-area">
<div class="waku-ads">
<!-- 詳細広告 -->
<ins class="adsbygoogle waku-ads-data"
     data-ad-client="ca-pub-4788194548114778"
     data-ad-slot="7765838632"
     data-ad-format="horizontal"
     data-full-width-responsive="false"></ins>
</div>
    <p class="mt-2 mb-0 mx-5">
        <span class="mr-2 px-3 py-1 rounded bg-dark text-white">公開日</span>
        <time itemprop="datePublished" datetime="{{convertDateType($detail_form_data->created_at,'al')}}">{{convertDateType($detail_form_data->created_at,'j')}}</time>
        <br />
        <span class="mr-2 px-3 py-1 rounded bg-dark text-white">更新日</span>
        <time itemprop="dateModified" datetime="{{convertDateType($detail_form_data->updated_at,'al')}}">{{convertDateType(data_get($form_data,'updated_at',null),'j')}}</time>
    </p>
    <div class="row m-0 p-0">
        <div class="col-xl-7 h-100 m-0 py-3 px-5">
            <figure><picture>
            <?php $source_srcset = getImageSrcset($detail_form_data->image_folder, $detail_form_data->image_name, $detail_form_data->image_type, 1); ?>  
            <source type="image/{{$source_srcset['type']}}" srcset="{{$source_srcset['data']}}">
            <img class="w-100" loading="lazy" src="{{getImagePath($detail_form_data->image_folder, $detail_form_data->image_name, $detail_form_data->image_type)}}" alt="{{$detail_form_data->image_alt}}" srcset="{{getImageSrcset($detail_form_data->image_folder, $detail_form_data->image_name, $detail_form_data->image_type)}}">
            </picture></figure>
            <div class="py-3 contents_text">{!! nl2br(convertHtmlText($detail_form_data->comp_branch_news_text)) !!}</div>
        </div>
        <div class="col-xl-5 h-100 m-0 px-5 py-0">
@include('common.newupdate_parts')
@include('common.allranking_parts')
        </div>
    </div>
</section>
<div class="p-3 vertical-write-r">今を乱し、基本から見直す自動車情報屋</div>
<footer class="m-3">
    <p><img src="{{asset('images/samurai_ico.svg')}}" alt="Car Media REVOLT-IS" class="contents-writer-img" />作成/更新：{{getSiteOmitTitle()}}</p>
</footer>
</article>
@endsection
@include('common.footer')