@extends('common.base')
@include('common.header')
@include('common.side')
@section('content')
<article class="col-xl-11 px-2 horizontal-write-center" id="horizontal-write" data-barba="container" data-barba-namespace="company-shop-list">
<header class="my-3">
@include('common.navi')
    <h2><img src="{{asset('images/shiro_ico.svg')}}" alt="企業｜店一覧" />企業｜店一覧</h2>
@if($list_form_data->count() <= 0)
    <p class="py-3">登録データはまだありません。</p>
@else
    <p class="py-3">一覧から項目を選択して下さい。</p>
@endif
</header>
@if($list_form_data->count() > 0)
<section class="my-3 px-3 py-0 contents-area">
<div class="waku-ads">
<!-- リスト広告 -->
<ins class="adsbygoogle waku-ads-data"
     data-ad-client="ca-pub-4788194548114778"
     data-ad-slot="2604370668"
     data-ad-format="horizontal"
     data-full-width-responsive="false"></ins>
</div>
    <div class="row m-0 py-2">
        <div class="col-xl-7 h-100 list-group">
@foreach ($list_form_data as $list_data) 
            <a class="list-group-item list-group-item-action border-0 w-100" href="{{route('company_shop.show',$list_data->comp_no)}}">
                <div class="row m-0 p-0">
                    <div class="col-xl-6">
                        <figure><picture>
                        <?php $source_srcset = getImageSrcset($list_data->image_folder, $list_data->image_name, $list_data->image_type, 1); ?>
                        <source type="image/{{$source_srcset['type']}}" srcset="{{$source_srcset['data']}}">
                        <img class="w-100" loading="lazy" src="{{getImagePath($list_data->image_folder, $list_data->image_name, $list_data->image_type)}}" alt="{{$list_data->image_alt}}" srcset="{{getImageSrcset($list_data->image_folder, $list_data->image_name, $list_data->image_type)}}"></picture></figure>
                    </div>
                    <div class="col-xl-6 py-1">
                        <article>
                            <h3>{{$list_data->comp_name}}</h3>
                            <p><span class="mr-2 px-3 py-1 rounded bg-dark text-white">業態</span>{{$list_data->bformat_name}}</p>
                            <p><span class="mr-2 px-3 py-1 rounded bg-dark text-white">業種</span>{{$list_data->industry_name}}</p>
                        </article>
                    </div>
                </div>
            </a> 
@endforeach
        </div>
        <div class="col-xl-5 h-100 m-0 px-5 pt-3 pb-1">
        <div class="list-group">
@include('common.newupdate_parts')
@include('common.allranking_parts')
        </div>
        </div>
    </div>
<div class="pt-2 mx-3 ads-label">【公式支援】</div>
<div class="mx-3 mb-3 ads-area"></div>
</section>
<!-- ページネーション -->
{{ $list_form_data->links() }}
@endif
<div class="p-3 vertical-write-r">今を乱し、基本から見直す自動車情報屋</div>
</article>
@endsection
@include('common.footer')