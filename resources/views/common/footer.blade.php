@section('footer')
<footer class="mt-3 w-100">
    <div class="row w-100 m-0">
        <nav class="col-xl-3">
            <div class="list-group footer_menu">
                <a href="{{route('front_products_category.index')}}" class="list-group-item list-group-item-action border-0">製品｜事業</a>
                <a href="{{route('company_shop.index')}}" class="list-group-item list-group-item-action border-0">企業｜店</a>
                <a href="{{route('front_event.index')}}" class="list-group-item list-group-item-action border-0">催し報告</a>
                <a href="{{route('front_content.index',2)}}" class="list-group-item list-group-item-action border-0">報道</a>
                <a href="{{route('front_content.index',1)}}" class="list-group-item list-group-item-action border-0">特集</a>
                <a href="{{route('front_content.index',0)}}" class="list-group-item list-group-item-action border-0">編集後記</a>
            </div>
        </nav>
        <nav class="col-xl-3">
            <div class="list-group footer_menu">
                <a href="{{route('front_contact')}}" class="list-group-item list-group-item-action border-0">お問い合わせ</a>
                <a href="{{route('front_content.show',3)}}" class="list-group-item list-group-item-action border-0">私達について</a>
                <a href="{{route('front_content.show',4)}}" class="list-group-item list-group-item-action border-0">利用規約</a>
                <a href="{{route('front_content.show',5)}}" class="list-group-item list-group-item-action border-0">企業、店主様へ</a>
            </div>
        </nav>
        <nav class="col-xl-3">
            <div class="list-group footer_menu">
                <a href="{{getTwitterUrl()}}" target="_break" class="list-group-item list-group-item-action border-0">twitter</a>
                <a href="{{getFacebookUrl()}}" target="_break" class="list-group-item list-group-item-action border-0">facebook</a>
                <a href="{{getInstagramUrl()}}" target="_break" class="list-group-item list-group-item-action border-0">instagram</a>
                <a href="{{getYoutubeUrl()}}" target="_break" class="list-group-item list-group-item-action border-0">youtube</a>
            </div>
        </nav>
        <div class="col-xl-3 text-right">{{getSiteFooterTitle()}}</div>
    </div>
</footer>
@endsection