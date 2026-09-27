@section('func_modal')
<!-- アラートモーダル -->
<div class="modal fade" id="list_del" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="selectDeleteItem">選択項目の削除確認</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>チェック項目を削除してよろしいですか？ </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
        <button type="button" class="btn btn-primary" id="del_next">次へ</button>
      </div>
    </div>
  </div>
</div>
@endsection
