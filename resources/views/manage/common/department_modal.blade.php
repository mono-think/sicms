@section('department_modal')
<!-- 部署一覧モーダル -->
<div class="modal fade" id="insert_department" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectDepartment">部署を選択して下さい。</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="w-100 p-3">
                    <h2>部署一覧</h2>
                    <input type="hidden" id="get_department" value="{{route('company_department.getlist')}}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="department_free_word">フリーワード</label>
                        </div>
                        <input type="search" class="form-control" name="department_free_word" id="department_free_word" placeholder="部署番号 / 部署名" value="{{$free_word}}">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-primary" id="department_search">検索</button>
                        </div>
                    </div>
                </div>
                <div class="w-100 p-3">
                    <div class="list-group mb-2" id="department_list"></div>
                    <input type="hidden" id="select_department_counter" value="0" /><button type="button" class="btn btn-primary mt-1 mb-1 d-none" id="next_department" disabled>さらに表示(最大5件ずつ表示)</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="department_modal_close" data-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</div>
@endsection