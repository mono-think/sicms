@extends('common.e_base')
@include('common.header')
@include('common.side')
@section('content')
<article class="col-xl-11 px-2 horizontal-write-center" id="horizontal-write" data-barba="container" data-barba-namespace="500-error">
<header class="m-3">
    <h2>500 internal server error</h2>
    <p class="py-3">障害が発生しました。</p>
</header>
<p class="p-3">アクセス負荷によるサーバーダウン、またはシステムに何らかの障害が発生した可能性があります。<br />お問い合わせページ等から管理者へお問い合わせ下さい。</p>
</article>
@endsection
@include('common.footer')