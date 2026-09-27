@extends('common.e_base')
@include('common.header')
@include('common.side')
@section('content')
<article class="col-xl-11 px-2 horizontal-write-center" id="horizontal-write" data-barba="container" data-barba-namespace="401-error">
<header class="m-3">
    <h2>401 Unauthorized Error</h2>
    <p class="py-3">当該ページへのアクセスが許可されていません。</p>
</header>
<section class="m-3 px-3 py-0 contents-area">
    <div class="row m-0 py-2">認証が無効、もしくは障害が発生した可能性があります。<br />最初のページに戻ってやり直すか、お問い合わせページ等から管理者へお問い合わせ下さい。</div>
</section>
</article>
@endsection
@include('common.footer')