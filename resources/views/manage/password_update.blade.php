@extends('manage.common.login_base')
@section('sub_content')
<h2>パスワードが正常に更新されました。</h2>
更新結果:{{$update_status}}
<p><a href="{{ route('login') }}">こちら</a>を押下してログインし直して下さい。</p>
@endsection
@include('manage.common.footer')