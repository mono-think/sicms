@section('side')
<div class="col-xl-1 text-black d-flex align-items-center vertical-write-l">
    <nav class="navbar navbar-expand-lg navbar-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#revoMenu" aria-controls="revoMenu" aria-expanded="false" aria-label="Toggle navigation">目次<br />
        <span class="navbar-toggler-icon"></span> </button>
        <div class="collapse navbar-collapse" id="revoMenu">
            <ul class="navbar-nav pt-2 px-0 pb-0">
                <li class="nav-item"> <a class="nav-link m-2" href="{{route('index')}}">最初</a> </li>
                <li class="nav-item"> <a class="nav-link m-2" href="{{route('front_products_category.index')}}">製品｜事業</a> </li>
                <li class="nav-item"> <a class="nav-link m-2" href="{{route('company_shop.index')}}">企業｜店</a> </li>
                <li class="nav-item"> <a class="nav-link m-2" href="{{route('front_event.index')}}">催し報告</a> </li>
                <li class="nav-item"> <a class="nav-link m-2" href="{{route('front_content.index',1)}}">特集</a> </li>
                <li class="nav-item"> <a class="nav-link m-2" href="{{route('front_content.index',2)}}">報道</a> </li>
                <li class="nav-item"> <a class="nav-link m-2" href="{{route('front_content.index',0)}}">編集後記</a> </li>
            </ul>
        </div>
    </nav>
</div>
@endsection
