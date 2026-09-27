/*!
  * Sicms JS v1.1.1
  * Copyright 2021-2021 Webじゃ研 Authors
  * Licensed under MIT
  */

/*!
  * 初期設定
  */
/* イベントリスナー開放（初期化） */
$(document).off('#input_posno');
$(document).off('#input_authno');
$(document).off('.show_password');
$(document).off('.show_pass_confirmation');

/* 読込完了 */
$(document).ready(function(){

    /* Key Event */
    $(document).on('change','#input_posno', function(){
        //役職選択後、役職名を設定
        if ($(this).val() == ''){
            $('#input_posname').val('');
        } else {
            $('#input_posname').val($('#input_posno option:selected').text());
        }
    });

    $(document).on('change','#input_authno', function(){
        //権限選択後、権限名を設定
        if ($(this).val() == ''){
            $('#input_authname').val('');
        } else {
            $('#input_authname').val($('#input_authno option:selected').text());
        }
    });

    $(document).on('click','.show_password', function(){
        //パスワード表示
        if ($('#input_pass').attr('type') === "password") {
            $('#input_pass').attr('type','text');
        } else {
            $('#input_pass').attr('type','password');
        }
    });

    $(document).on('click','.show_pass_confirmation', function(){
        //パスワード（確認）表示
        if ($('#input_pass_confirmation').attr('type') === "password") {
            $('#input_pass_confirmation').attr('type','text');
        } else {
            $('#input_pass_confirmation').attr('type','password');
        }
    });

    //初期設定
    $('#input_posname').val($('#input_posno option:selected').text());
    $('#input_authname').val($('#input_authno option:selected').text());
});