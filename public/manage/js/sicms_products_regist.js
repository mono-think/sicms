/*!
  * Sicms JS v1.1.1
  * Copyright 2021-2021 Webじゃ研 Authors
  * Licensed under MIT
  */

/*!
  * 初期設定
  */
/* イベントリスナー開放（初期化） */
$(document).off('#input_ctg_no');

/* 読込完了 */
$(document).ready(function(){

    /* Key Event */
    $(document).on('change','#input_ctg_no', function(){
        //製品種別選択押下後、製品種別名を設定
        if ($(this).val() == ''){
            $('#input_ctg_name').val('');
        } else {
            $('#input_ctg_name').val($('#input_ctg_no option:selected').text());
        }
    });
});
