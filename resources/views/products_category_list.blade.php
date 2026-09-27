@extends('common.base')
@include('common.header')
@include('common.side')
@section('content')
<article class="col-xl-11 px-2 horizontal-write-center" id="horizontal-write" data-barba="container" data-barba-namespace="products-category">
<header class="my-3">
@include('common.navi')
    <h2><img src="{{asset('images/scroll_ico.svg')}}">製品｜事業部門一覧</h2>
@if($list_form_data->count() <= 0)
    <p class="py-3">登録データはまだありません。</p>
@else
    <p class="py-3">一覧から項目を選択して下さい。</p>
@endif
</header>
@if($list_form_data->count() > 0)
<section class="my-3 px-3 pt-2 category-list">
<div class="waku-ads">
<!-- リスト広告 -->
<ins class="adsbygoogle waku-ads-data"
     data-ad-client="ca-pub-4788194548114778"
     data-ad-slot="2604370668"
     data-ad-format="horizontal"
     data-full-width-responsive="false"></ins>
</div>
@foreach ($list_form_data as $list_data) 
    <div class="h-100 list-group">
@if (!empty($company_no))
        <a href="{{route('front_products.index',$list_data->products_category_no)}}?c={{$company_no}}" class="list-group-item list-group-item-action border-0 mb-2">{{$list_data->products_category_name}}</a>
@else
        <a href="{{route('front_products.index',$list_data->products_category_no)}}" class="list-group-item list-group-item-action border-0 mb-2">{{$list_data->products_category_name}}</a>
@endif
    </div>
@endforeach
<div class="pt-2 mx-3 ads-label">【公式支援】</div>
<div class="mx-3 mt-0 mb-3 ads-area"></div>
</section>
<!-- ページネーション -->
{{ $list_form_data->links() }}
@endif
<div class="p-3 vertical-write-r">今を乱し、基本から見直す自動車情報屋</div>
</article>
@endsection
@include('common.footer')