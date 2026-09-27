@section('acnt_modal')
<!-- アカウント一覧モーダル -->
<div class="modal fade" id="insert_acnt" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectAcnt">アカウントを選択して下さい。</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="w-100 p-3">
                    <h2>アカウント一覧</h2>
                    <input type="hidden" id="get_acnt" value="{{route('acnt.getlist')}}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="acnt_free_word">フリーワード</label>
                        </div>
                        <input type="search" class="form-control" name="acnt_free_word" id="acnt_free_word" placeholder="番号 / 名前 / 郵便番号 / 住所 / 電話番号 / 企業名 / 支店名 / 部署名 / 役職名" value="{{$free_word}}">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-primary" id="acnt_search">検索</button>
                        </div>
                    </div>
                </div>
                <div class="w-100 p-3">
                    <div class="list-group mb-2" id="acnt_list"></div>
                    <input type="hidden" id="select_acnt_counter" value="0" /><button type="button" class="btn btn-primary mt-1 mb-1 d-none" id="next_acnt" disabled>さらに表示(最大5件ずつ表示)</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="acnt_modal_close" data-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</div>
@endsection
