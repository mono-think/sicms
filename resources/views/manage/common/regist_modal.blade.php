@section('func_modal')
<!-- リンクタグモーダル -->
<div class="modal fade" id="insert_atag" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="insertLinkTag">埋め込むリンクタグ情報を入力して下さい。</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="w-100 p-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="atag_url">URL</label>
                </div>
                <input type="text" class="form-control" id="atag_url" placeholder="url" maxlength="255" aria-describedby="atag_url">
            </div>
            <div class="input-group">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="atag_target">ターゲット</label>
                </div>
                <select class="custom-select" id="atag_target" aria-describedby="atag_target">
                    <option value="">選択なし</option>
                    <option value="_self">_self</option>
                    <option value="_blank">_blank</option>
                    <option value="_parent">_parent</option>
                    <option value="_top">_top</option>
                </select>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" id="atag_comp">完了</button>
      </div>
    </div>
  </div>
</div>
@endsection
