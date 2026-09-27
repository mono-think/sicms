/*!
  * Sicms JS v1.1.1
  * Copyright 2021-2021 Webじゃ研 Authors
  * Licensed under MIT
  */

/*!
  * 初期設定
  */
/* イベントリスナー開放（初期化） */
$(document).off('#input_indno');
$(document).off('#input_bfno');

/* 読込完了 */
$(document).ready(function(){

    /* Key Event */
    $(document).on('change','#input_indno', function(){
        //業種選択後、業種名を設定
        if ($(this).val() == ''){
            $('#input_indname').val('');
        } else {
            $('#input_indname').val($('#input_indno option:selected').text());
        }
    });

    $(document).on('change','#input_bfno', function(){
        //業態選択後、業態名を設定
        if ($(this).val() == ''){
            $('#input_bfname').val('');
        } else {
            $('#input_bfname').val($('#input_bfno option:selected').text());
        }
    });
});
