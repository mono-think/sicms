@extends('manage.common.login_base')
@section('sub_content')
<div class="container">
    <form action="{{route('twoverify.verify')}}" class="needs-validation" novalidate method="post">
        {{ csrf_field() }}
        <div class="input-group">
            <div class="input-group-prepend">
                <label class="input-group-text" for="verify_id">認証コード</label>
            </div>
            <input type="text" class="form-control" name="verify_id" id="verify_id" placeholder="認証コード" maxlength="6" aria-describedby="verify_id" required>
            <div class="invalid-feedback">入力されていません。</div>
        </div>
@error('verify_id')
        <div class="alert alert-danger">{{ $message }}</div>
@enderror
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <input type="checkbox" name="omit" id="omit" value="true">
                </div>
            </div>
            <div class="input-group-append">
                <div class="input-group-text">
                    <small>※この端末から次回以降、二段階認証を省略するならチェックを入れて下さい。</small>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">認証開始</button>
    </form>
    <p>認証コードを再発行したい方は、再発行ボタンを押下して下さい。</p>
    <a type="button" class="btn btn-sm text-white bg-dark" href="{{route('twoverify.send')}}">再発行</a>
</div>
@endsection
@include('manage.common.footer')