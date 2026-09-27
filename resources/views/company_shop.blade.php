@extends('common.base')
@include('common.header')
@include('common.side')
@section('content')
<article class="col-xl-11 px-2 horizontal-write-center" id="horizontal-write" data-barba="container" data-barba-namespace="company-shop">
<header class="my-3">
@include('common.navi')
    <h2><img src="{{asset('images/shiro_ico.svg')}}" alt="{{$detail_form_data->comp_name}}" />{{$detail_form_data->comp_name}}</h2>
    <input type="hidden" id="comp_branch" value="{{route('company_shop.branch',$detail_form_data->comp_no)}}">
    <input type="hidden" id="branch_news" value="{{route('company_shop.news',$detail_form_data->comp_no)}}">
    <input type="hidden" id="news_detail" value="{{route('company_shop_news.show',$detail_form_data->comp_no)}}">
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
    <div class="row m-0 p-0">
        <div class="col-xl-6 h-100 m-0 p-3">
            <figure><picture>
            <?php $source_srcset = getImageSrcset($detail_form_data->image_folder, $detail_form_data->image_name, $detail_form_data->image_type, 1); ?>  
            <source type="image/{{$source_srcset['type']}}" srcset="{{$source_srcset['data']}}">
            <img class="w-100" loading="lazy" src="{{getImagePath($detail_form_data->image_folder, $detail_form_data->image_name, $detail_form_data->image_type)}}" alt="{{$detail_form_data->image_alt}}" srcset="{{getImageSrcset($detail_form_data->image_folder, $detail_form_data->image_name, $detail_form_data->image_type)}}">
            </picture></figure>
            <p><span class="mr-2 px-3 py-1 rounded bg-dark text-white">業態</span>{{$detail_form_data->bformat_name}}</p>
            <p><span class="mr-2 px-3 py-1 rounded bg-dark text-white">業種</span>{{$detail_form_data->industry_name}}</p>
            <p><span class="mr-2 px-3 py-1 rounded bg-dark text-white">URL</span><a href="{{$detail_form_data->comp_url}}" target="_blank">{{$detail_form_data->comp_url}}</a></p>
            <p><span class="mr-2 px-3 py-1 rounded bg-dark text-white">製品｜事業</span><a href="{{route('front_products_category.index').'?c='.$detail_form_data->comp_no}}">取り扱い一覧はこちら</a></p>
            <div class="pb-3">
                <div class="contents_text">{!! nl2br(convertHtmlText($detail_form_data->comp_business_text)) !!}</div>
@if (!empty($detail_form_data->search_tag))
                <div class="contents_tag">
                <ul>
<?php $search_tag_data = explode(',', $detail_form_data->search_tag); ?>
@foreach($search_tag_data as $tag_data)
                    <li><a type="button" href="{{route('company_shop.index').'?tag='.trim($tag_data)}}">{{trim($tag_data)}}</a></li>
@endforeach
                </ul>
            </div>
@endif
            </div>
        </div>
        <div class="col-xl-6 h-100 m-0 p-3">
        <div class="row mb-5 sub_menu d-none">
            <h3 class="col-xl-3 side-menu-titl">報道発表</h3>
            <div class="col-xl-9">
                <div class="company_news_area"></div>
                <small><b><a href="{{route('company_shop_news.index',$detail_form_data->comp_no)}}">全て見る</a></b></small>
            </div>
        </div>
        <div class="row mb-5">
            <h3 class="col-xl-3 side-menu-titl">拠点</h3>
            <div class="col-xl-9">
                <div class="pb-3 company_branch_area"></div>
            </div>
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