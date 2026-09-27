@extends('common.base')
@include('common.header')
@include('common.side')
@section('content')
<article class="col-xl-11 px-2 horizontal-write-center" id="horizontal-write" data-barba="container" data-barba-namespace="event">
<header class="my-3">
@include('common.navi')
    <h2><img src="{{asset('images/uchiwa_ico.svg')}}" alt="{{$detail_form_data->event_name}}" />{{$detail_form_data->event_name}}</h2>
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
            <div class="py-3">
                <p><span class="mr-2 px-3 py-1 rounded bg-dark text-white">開催日</span>{{convertDateType($detail_form_data->event_start_date,'j')}}</p>
                <p><span class="mr-2 px-3 py-1 rounded bg-dark text-white">開催場所</span>{{$detail_form_data->event_stage}}</p>
                <p><span class="mr-2 px-3 py-1 rounded bg-dark text-white">当日の天気</span>{{getConstsType('weather', $detail_form_data->event_weather_type)}}</p>
                <p><span class="mr-2 px-3 py-1 rounded bg-dark text-white">主催者</span>{{$detail_form_data->event_organizer}}</p>
            </div>
            <div class="contents_text">{!! nl2br(convertHtmlText($detail_form_data->event_text)) !!}</div>
            @if (!empty($detail_form_data->search_tag))
            <div class="contents_tag">
              <ul>
                <?php $search_tag_data = explode(',', $detail_form_data->search_tag); ?>
                @foreach($search_tag_data as $tag_data)
                    <li><a type="button" href="{{route('front_event.index').'?tag='.trim($tag_data)}}">{{trim($tag_data)}}</a></li>
                @endforeach
              </ul>
            </div>
            @endif
        </div>
        <div class="col-xl-5 h-100 m-0 px-5 py-0">
@include('common.newupdate_parts')
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
</article>
@endsection
@include('common.footer')