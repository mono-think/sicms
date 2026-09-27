@extends('common.base')
@include('common.header')
@include('common.side')
@section('content')
<article class="col-xl-11 px-2 horizontal-write-center" id="horizontal-write" data-barba="container" data-barba-namespace="products">
<header class="my-3">
@include('common.navi')
    <h2><img src="{{asset('images/scroll_ico.svg')}}" alt="{{$detail_form_data->products_detail_title}}" />{{$detail_form_data->products_detail_title}}</h2>
    <input type="hidden" id="detaillist" value="{{route('front_products.getdetail',$detail_form_data->products_no)}}">
@if(!empty($detail_form_data->products_detail_no))
    <input type="hidden" id="reviewlist" value="{{route('front_products.getreview',$detail_form_data->products_detail_no)}}">
@else
    <input type="hidden" id="reviewlist" value="">
@endif
    <input type="hidden" id="pn" value="{{$detail_form_data->products_no}}">
    <input type="hidden" id="pdn" value="{{$detail_form_data->products_detail_no}}">
    <input type="hidden" id="cn" value="{{$company_no}}">
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
        <time itemprop="dateModified" datetime="{{convertDateType($detail_form_data->updated_at,'al')}}">{{convertDateType(data_get($detail_form_data,'updated_at',null),'j')}}</time>
    </p>
    <div class="row m-0 p-0">
        <div class="col-xl-7 h-100 m-0 py-3 px-5">
            <figure><picture>
            <?php $source_srcset = getImageSrcset($detail_form_data->image_folder, $detail_form_data->image_name, $detail_form_data->image_type, 1); ?>  
            <source type="image/{{$source_srcset['type']}}" srcset="{{$source_srcset['data']}}">
            <img class="w-100" loading="lazy" src="{{getImagePath($detail_form_data->image_folder, $detail_form_data->image_name, $detail_form_data->image_type)}}" alt="{{$detail_form_data->image_alt}}" srcset="{{getImageSrcset($detail_form_data->image_folder, $detail_form_data->image_name, $detail_form_data->image_type)}}">
            </picture></figure>
            <div class="py-3">
                <p><span class="mr-2 px-3 py-1 rounded bg-dark text-white">製品</span>{{$detail_form_data->products_name}}</p>
                <p><span class="mr-2 px-3 py-1 rounded bg-dark text-white">対象</span>{{getConstsType('customer', $detail_form_data->products_customer_type)}}</p>
                <p><span class="mr-2 px-3 py-1 rounded bg-dark text-white">種別</span>{{getConstsType('service', $detail_form_data->products_service_type)}}</p>
                <p><span class="mr-2 px-3 py-1 rounded bg-dark text-white">企業｜店</span><a href="{{route('company_shop.show',$detail_form_data->comp_no)}}">{{$detail_form_data->comp_name}}</a></p>
            </div>
            <div class="container-fluid p-0">
                <ul id="clothingnav1" class="nav nav-tabs" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" href="#detailPanel" id="detailTab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">仕様/詳細</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="#reviewPanel" role="tab" id="reviewTab" data-toggle="tab" aria-controls="hats">感想</a> </li>
                </ul>
                <!-- Content Panel -->
                <div id="products-content" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade show active" id="detailPanel" aria-labelledby="detailTab">
                        <div class="contents_text">{!! nl2br(convertHtmlText($detail_form_data->products_detail_text)) !!}</div>
@if (!empty($detail_form_data->search_tag))
                        <div class="contents_tag">
                            <ul>
<?php $search_tag_data = explode(',', $detail_form_data->search_tag); ?>
@foreach($search_tag_data as $tag_data)
                                <li><a type="button" href="{{route('front_products.index',$detail_form_data->products_category_no).'?tag='.trim($tag_data)}}">{{trim($tag_data)}}</a></li>
@endforeach
                            </ul>
                        </div>
                    @endif
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="reviewPanel" aria-labelledby="reviewTab">
                        <div class="pb-3 review_area"></div>
                        <input type="hidden" id="select_review_counter" value="0" /><button type="button" class="btn btn-danger" id="next_review" disabled>さらに表示(最大5件ずつ表示)</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-5 h-100 m-0 px-5 py-0">
            <div class="row mb-5 sub_menu d-none">
                <h3 class="col-xl-3 side-menu-titl">その他</h3>
                <div class="col-xl-9 list-group m-0 p-0" id="side-detail-list"></div>
            </div>
@include('common.allranking_parts')
        </div>
    </div>
<div class="pt-2 mx-3 ads-label">【公式支援】</div>
<div class="mx-3 mt-0 mb-3 ads-area"></div>
</section>
<div class="p-3 vertical-write-r">今を乱し、基本から見直す自動車情報屋</div>
<footer class="m-3">
    <p><img src="{{asset('images/samurai_ico.svg')}}" alt="Car Media REVOLT-IS" class="contents-writer-img" />作成/更新：{{getSiteOmitTitle()}}</p>
</footer>
@endsection
</article>
@include('common.footer')