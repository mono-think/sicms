@extends('manage.common.login_base')
@section('sub_content')
<h2>パスワードリセットメールを送信しました。</h2>
<p>しばらくしたらパスワードリセットのメールが届きますので、そのメールの指示に従って下さい。</p>
送信結果:{{$mail_status}}
<p>※メールが届かなければ、<a href="{{ route('password.index') }}">こちら</a>を押下してメール送信からやり直すか、管理者へお問い合わせ下さい。</p>
@endsection
@include('manage.common.footer')