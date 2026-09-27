@extends('manage.common.login_base')
@section('sub_content')
<h2>パスワードをリセットします。</h2>
<p>アカウント登録した際のメールアドレスを入力して、送信ボタンを押下して下さい。</p>
<form action="{{ route('password.sendmail') }}" class="needs-validation" novalidate method="post">
    {{ csrf_field() }}
    <div class="input-group">
        <div class="input-group-prepend">
            <label class="input-group-text" for="email">メールアドレス</label>
        </div><!-- 【MySQL暫定対応】 -->
        <input type="text" class="form-control" name="email" id="email" placeholder="登録メールアドレス" maxlength="191" aria-describedby="email" required>
        <div class="invalid-feedback">入力必須です。</div>
    </div>
    <button type="submit" class="btn btn-primary">送信</button>
</form>
@endsection
@include('manage.common.footer')