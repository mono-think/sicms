@extends('common.base')
@include('common.header')
@include('common.side')
@section('content')
<article class="col-xl-11 px-2 horizontal-write-center" id="horizontal-write" data-barba="container" data-barba-namespace="contact-confirm">
<header class="my-3">
@include('common.navi')
    <h2><img src="{{asset('images/scroll_ico.svg')}}">お問い合わせ確認</h2>
    <p class="py-3 m-0">入力内容をご確認頂き、よろしければ送信ボタンをクリックして下さい。<br />訂正する場合は戻るボタンをクリックして下さい。</p>
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
    <form method="post" action="{{ route('front_contact.store') }}">
        {{ csrf_field() }}
        <div class="contents_area_detail">
            <div class="form-group row">
            <div class="col-xl-12">
                <label class="control-label">【氏名】</label>
                <div class="input_text">{{$form_data['contact_name']}}</div>
            </div>
            </div>
            <div class="form-group row">
            <div class="col-xl-12">
                <label class="control-label">【メールアドレス】</label>
                <div class="input_text">{{$form_data['contact_mail']}}</div>
            </div>
            </div>
            <div class="form-group row">
            <div class="col-xl-12">
                <label class="control-label">【本文】</label>
                <div class="input_text">{{$form_data['contact_text']}}</div>
            </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-offset-2 col-xl-12">
                <a type="button" class="btn btn-danger rounded" href="{{route('front_contact')}}">戻る</a>
                <button type="submit" class="btn btn-danger rounded">送信</button>
                </div>
            </div>
        </div>
    </form>
</section>
</article>
@endsection
@include('common.footer')