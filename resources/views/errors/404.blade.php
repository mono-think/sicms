@extends('common.e_base')
@include('common.header')
@include('common.side')
@section('content')
<article class="col-xl-11 px-2 horizontal-write-center" id="horizontal-write" data-barba="container" data-barba-namespace="404-error">
<header class="m-3">
    <h2>404 not found Error</h2>
    <p class="py-3">指定のページは存在しません。</p>
</header>
<p class="p-3">存在しないページを指定したか、削除された可能性があります。<br />最初のページまたは前のページへ戻って下さい。</p>
</article>
@endsection
@include('common.footer')