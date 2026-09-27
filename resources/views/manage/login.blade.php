@extends('manage.common.login_base')
@section('sub_content')
<input type="hidden" id="login-api" value="{{route('login.auth')}}" />
<input type="hidden" id="main-page" value="{{route('company.index')}}" />
<input type="hidden" id="twostep-page" value="{{route('twoverify.index')}}" />
<form id="login-form" method="post">
    {{ csrf_field() }}
    <div class="input-group">
        <div class="input-group-prepend">
            <label class="input-group-text" for="acnt_id">ログインID</label>
        </div><!-- 【MySQL暫定対応】 -->
        <input type="search" class="form-control" name="acnt_id" id="acnt_id" placeholder="テキスト入力" maxlength="191" aria-describedby="acnt_id" value="{{old('acnt_id')}}">
    </div>
    <div class="input-group">
        <div class="input-group-prepend">
            <label class="input-group-text" for="password">パスワード</label>
        </div>
        <input type="password" class="form-control" name="password" id="password" placeholder="パスワード入力" maxlength="255" aria-describedby="password">
    </div>
    <div class="input-group">
        <div class="input-group-prepend">
            <label class="input-group-text" for="password_confirmation">　（確認）</label>
        </div>
        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="もう一度パスワード入力" maxlength="255" aria-describedby="password_confirmation">
    </div>
    <div class="input-group">
        <div class="input-group-prepend">
            <div class="input-group-text">
                <input type="checkbox" name="rememberme" value="true">
            </div>
        </div>
        <div class="input-group-append">
            <div class="input-group-text">
                <span><small>※ログイン状態を維持する場合は、こちらにチェックを入れて下さい。</small></span>
            </div>
        </div>
    </div>
    <p>※パスワードを忘れた方は<a href="{{ route('password.index') }}">こちら</a>をクリックして下さい。</p>
    <button type="button" class="btn btn-primary" id="login_btn">ログイン</button>
</form>
@endsection
@include('manage.common.footer')
@include('manage.login_js')