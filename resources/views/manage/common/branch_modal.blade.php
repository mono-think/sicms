@section('branch_modal')
<!-- 支店一覧モーダル -->
<div class="modal fade" id="insert_branch" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectBranch">支店を選択して下さい。</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="w-100 p-3">
                    <h2>支店一覧</h2>
                    <input type="hidden" id="get_branch" value="{{route('company_branch.getlist')}}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="branch_free_word">フリーワード</label>
                        </div>
                        <input type="search" class="form-control" name="branch_free_word" id="branch_free_word" placeholder="支店番号 / 支店名 / 住所" value="{{$free_word}}">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-primary" id="branch_search">検索</button>
                        </div>
                    </div>
                </div>
                <div class="w-100 p-3">
                    <div class="list-group mb-2" id="branch_list"></div>
                    <input type="hidden" id="select_branch_counter" value="0" /><button type="button" class="btn btn-primary mt-1 mb-1 d-none" id="next_branch" disabled>さらに表示(最大5件ずつ表示)</button>
                </div>
            </div>
            <div class="modal-footer">
	            <button type="button" class="btn btn-secondary" id="branch_modal_close" data-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</div>
@endsection
