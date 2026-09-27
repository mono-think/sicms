@extends('manage.common.login_base')
@section('sub_content')
<h2>パスワード変更画面</h2>
<form action="{{ route('password.update') }}" class="needs-validation" novalidate method="post">
    <input type="hidden" name="_method" value="put">
    {{ csrf_field() }}
    <input type="hidden" name="token" value="{{$form_data['token']}}">
    <input type="hidden" name="email" value="{{$form_data['email']}}">
    <div class="input-group">
        <div class="input-group-prepend">
            <label class="input-group-text" for="password">新しいパスワード</label>
        </div>
        <input type="password" class="form-control" name="password" id="password" placeholder="パスワード入力" maxlength="255" aria-describedby="password" required>
        <div class="invalid-feedback">入力必須です。</div>
    </div>
    <div class="input-group">
        <div class="input-group-prepend">
            <label class="input-group-text" for="password_confirmation">　　　　（確認）</label>
        </div>
        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="もう一度パスワード入力" maxlength="255" aria-describedby="password_confirmation" required>
        <div class="invalid-feedback">入力必須です。</div>
    </div>
    <button type="submit" class="btn btn-primary">送信</button>
</form>
@endsection
@include('manage.common.footer')