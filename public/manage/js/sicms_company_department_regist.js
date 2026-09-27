/*!
  * Sicms JS v1.1.1
  * Copyright 2021-2021 Webじゃ研 Authors
  * Licensed under MIT
  */

/*!
  * 初期設定
  */
/* イベントリスナー開放（初期化） */
$(document).off('#department_sns_item_add');
$(document).off('.department_sns_item_del');
$(document).off('.input_snsno');

/* 読込完了 */
$(document).ready(function(){

    /* Key Event */
    $(document).on('click','#department_sns_item_add', function(){
        //SNSの要素追加ボタン押下
        
        //要素数カウント
        department_sns_count = $("#department_sns_count").text();
        department_sns_count = Number(department_sns_count) + 1;
        $("#department_sns_count").text(department_sns_count);
        
        //SNSエリアをコピーして追加
        $('#department_sns_list').append($("#department_sns_list .department_sns_area:last").clone());
        //コピー元の選択、入力値をリセット
        $("#department_sns_list .department_sns_area:last").find('select').val('');
        $("#department_sns_list .department_sns_area:last").find('input').val('');
        //1件目の追加なら削除ボタンを追加
        if ($("#department_sns_list .department_sns_area").length == 2) {
            $("#department_sns_list .department_sns_area:last .input-group").append('<div class="input-group-append"><button type="button" class="btn btn-info department_sns_item_del">削除</button></div>');
        }

        //5件以上の追加は禁止
        if ($("#department_sns_list .department_sns_area").length == 5) {
             $(this).prop("disabled", true);
        }
    });

    $(document).on('click','.department_sns_item_del', function(){
        //SNSの要素削除ボタン押下
        $(this).parent().parent().parent().remove();

        //5件未満ならSNS追加ボタンを使用可能にする
        if ($("#department_sns_list .department_sns_area").length < 5){
            $('#department_sns_item_add').prop("disabled", false);
        }
    });

    $(document).on('click','.input_snsno', function(){
        if ($(this).val() === ''){
            $(this).parent().parent().find('.input_sns').attr('value','');
            $(this).parent().parent().find('.input_sns').val('');
        }
    });
});
