@extends('common.base')
@include('common.header')
@include('common.side')
@section('content')
<article class="col-xl-11 px-2 horizontal-write-center" id="horizontal-write" data-barba="container" data-barba-namespace="company-shop-news-list">
<header class="my-3">
@include('common.navi')
    <h2><img src="{{asset('images/shiro_ico.svg')}}">報道発表一覧</h2>
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
    <div class="h-100 list-group">
@foreach ($list_form_data as $list_data) 
        <a href="{{route('company_shop_news.show',$list_data->comp_no)}}?b={{$list_data->comp_branch_no}}&bn={{$list_data->comp_branch_news_no}}" class="list-group-item list-group-item-action border-0 mb-2">{{$list_data->comp_branch_news_title}}[{{convertDateType($list_data->created_at,'j')}}]</a>
@endforeach
    </div>
</section>
<!-- ページネーション -->
{{ $list_form_data->links() }}
@endif
<div class="p-3 vertical-write-r">今を乱し、基本から見直す自動車情報屋</div>
</article>
@endsection
@include('common.footer')