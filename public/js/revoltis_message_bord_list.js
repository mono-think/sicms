/*!
  * Sicms JS v1.1.1
  * Copyright 2021-2021 Webじゃ研 Authors
  * Licensed under MIT
  */

/*!
  * 初期設定
  */
/* イベントリスナー開放（初期化） */
$(document).off('.mb_end');
$(document).off('.mb_del');

/* 読込完了 */
$(document).ready(function(){

    $(document).on('click','.mb_end', function(){
        //終了更新
        $('#mb_update_list_form').attr('action', this.value);
        $('#mb_update_list_form').children('input[name="_method"]').val('put');
        $('.modal-title').html('【終了確認】認証コード入力');
    });

    $(document).on('click','.mb_del', function(){
        //削除更新
        $('#mb_update_list_form').attr('action', this.value);
        $('#mb_update_list_form').children('input[name="_method"]').val('delete');
        $('.modal-title').html('【削除確認】認証コード入力');
    });
});
