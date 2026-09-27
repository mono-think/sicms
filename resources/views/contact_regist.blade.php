@extends('common.base')
@include('common.header')
@include('common.side')
@section('content')
<article class="col-xl-11 px-2 horizontal-write-center" id="horizontal-write" data-barba="container" data-barba-namespace="contact">
<input type="hidden" id="get_captcha" value="{{route('common.getcaptcha')}}" />
<header class="my-3">
@include('common.navi')
    <h2><img src="{{asset('images/scroll_ico.svg')}}">お問い合わせ</h2>
    <p class="py-3 m-0">この度はご訪問頂きありがとうございます。<br />当WEBサイトへご意見やご質問、その他お問い合わせ等御座いましたら、以下注意事項をお読みの上で本お問い合わせフォーム、または各公式SNSのダイレクトメッセージ機能をご利用下さい。</p>
    <p class="pt-3 m-0">※氏名欄には必ず実名で入力して下さい。また法人様でしたら、氏名の前か後ろに貴社名も入力して下さい。ニックネームやハンドルネームの方は未読のまま破棄致します。</p>
    <p class="py-0 m-0">※迷惑メールやいたずらメールの類は固くお断りします。もし悪意あるメールの場合は、事前告知なく然るべく対応を取らせて頂きます。</p>
    <p class="py-0 m-0">※個人情報に触れるお問い合わせについてはお答え致しかねます。予めご了承下さい。</p>
    <p class="py-0 m-0">※営業メールについては原則、直接お付き合いのあるご法人様もしくは、以前にお名刺交換頂いた方のみ閲覧しております。その他については迷惑メール扱いとして、未読のまま破棄、受信拒否致します。</p>
    <p class="pb-3 m-0">※当方は情報配信を行うメディアサイトであり、通販サイトではありません。掲載製品や事業への商談、価格や在庫といったお問い合わせは、それぞれ担当する法人企業様、ショップ様へ直接お願い致します。</p>
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
    <form id="front_contact_regist_form" class="needs-validation" novalidate method="post" action="{{ route('front_contact.confirm') }}">
        {{ csrf_field() }}
        <div class="contents_area_detail">
            <span class="notes">※全て入力必須です。</span>
            <div class="form-group">
                <label class="col-xl-12 control-label" for="contact_name">氏名</label>
                <input class="form-control" type="text" name="contact_name" id="contact_name" placeholder="実名で入力" maxlength="64" aria-describedby="contact_name" value="{{outputValue($form_data,'contact_name')}}" required>
                <div class="invalid-feedback">入力されていません。</div>
@error('contact_name')
                <div class="alert alert-danger">{{ $message }}</div>
@enderror
            </div>
            <div class="form-group">
                <label class="col-xl-12 control-label" for="contact_mail">メールアドレス</label>
<!-- 【MySQL暫定対応】 -->
                <input class="form-control" type="text" name="contact_mail" id="contact_mail" pattern="^[a-zA-Z0-9.!#$&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$" placeholder="メールアドレスを入力" maxlength="191" aria-describedby="contact_mail" value="{{outputValue($form_data,'contact_mail')}}" required>
                <div class="invalid-feedback">入力されていません。正しい書式で入力して下さい。</div>
@error('contact_mail')
                <div class="alert alert-danger">{{ $message }}</div>
@enderror
            </div>
            <div class="form-group">
                <label class="col-xl-12 control-label" for="contact_mail_confirmation">確認用メールアドレス</label>
<!-- 【MySQL暫定対応】 -->
                <input class="form-control" type="text" name="contact_mail_confirmation" id="contact_mail_confirmation" pattern="^[a-zA-Z0-9.!#$&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$" placeholder="確認に同じメールアドレスを入力" maxlength="191" aria-describedby="contact_mail_confirmation" value="{{outputValue($form_data,'contact_mail_confirmation')}}" required>
                <div class="invalid-feedback">入力されていません。正しい書式で入力して下さい。</div>
@error('contact_mail_confirmation')
                <div class="alert alert-danger">{{ $message }}</div>
@enderror
            </div>
            <div class="form-group">
                <label class="col-xl-12 control-label" for="contact_text">本文</label>
                <textarea cols="5" rows="10" class="form-control" name="contact_text" id="contact_text" placeholder="お問い合わせ内容を入力。ご法人様は貴社名、部署名も本項に入力下さい。" required>{{outputValue($form_data,'contact_text')}}</textarea>
                <div class="invalid-feedback">入力されていません。</div>
@error('contact_text')
                <div class="alert alert-danger">{{ $message }}</div>
@enderror
            </div>
            <div class="form-group">
                <label class="col-xl-12 control-label" for="captcha_auth">Captcha認証</label>
                <p class="captcha_area px-0 pl-3"></p>
                <input class="form-control" type="text" name="captcha_auth" id="captcha_auth" placeholder="上部画像に表示の英数字を入力して下さい。" maxlength="4" aria-describedby="captcha_auth" value="" required>
                <div class="invalid-feedback">入力されていません。</div>
@error('captcha_auth')
                <div class="alert alert-danger">{{ $message }}</div>
@enderror
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-danger rounded" id="contact_regist_button">入力確認</button>
                </div>
            </div>
        </div>
    </form>
</section>
</article>
@endsection
@include('common.footer')