/*!
  * Sicms JS v1.1.1
  * Copyright 2021-2021 Webじゃ研 Authors
  * Licensed under MIT
  */

/*!
  * 初期設定
  */
/* イベントリスナー開放（初期化） */
$(document).off('#select_all');
$(document).off('input[name="select_no[]"]');
$(document).off('input[name="select_rno[]"]');
$(document).off('#del_next');

/* 読込完了 */
$(document).ready(function(){

    /* Key Event */
    if($('#select_all').length){
        $(document).on('click','#select_all', function(){
            //全選択チェック押下
            $("input[name='select_no[]']").prop('checked', this.checked);
            //存在していたらこちらも全選択
            $("input[name='select_rno[]']").prop('checked', this.checked);
    
            //削除ボタンの有効/無効
            if ($('input[name="select_no[]"]:checked').length > 0) {
                $('#select_del').prop("disabled", false);
            } else {
                $('#select_del').prop("disabled", true);
            }
        });
    }

    if($('input[name="select_no[]"]').length){
        $(document).on('click',"input[name='select_no[]']", function(){
            //一件でも選択されていたら削除ボタンを有効
            if ($('input[name="select_no[]"]:checked').length > 0) {
                $('#select_del').prop("disabled", false);
            } else {
                $('#select_del').prop("disabled", true);
            }
    
            //全てチェックが入ったら全選択チェックにチェック
            if ($('input[name="select_no[]"]:checked').length == $("table tbody").children().length - 1) {
                $('#select_all').prop('checked', true);
            } else {
                $('#select_all').prop('checked', false);
            }
        });
    }

    if($('input[name="select_rno[]"]').length){
        $(document).on('click',"input[name='select_rno[]']", function(){
            //一件でも選択されていたら削除ボタンを有効
            if ($('input[name="select_rno[]"]:checked').length > 0) {
                $('#select_del').prop("disabled", false);
            } else {
                $('#select_del').prop("disabled", true);
            }
        });
    }
    
    $(document).on('click','#del_next', function(){
        //削除（次へ）ボタン押下
        $('#list_form').submit();
    });
});
