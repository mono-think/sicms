/*!
  * Sicms JS v1.1.1
  * Copyright 2021-2021 Webじゃ研 Authors
  * Licensed under MIT
  */

/*!
  * 初期設定
  */
/* イベントリスナー開放（初期化） */
$(document).off('#regist_check');
$(document).off('#zipcode_search');
$(document).off('#input_pref_cd');
$(document).off('#input_no');
$(document).off('#input_id');
$(document).off('#regist_button');
$(document).off('#atag_comp');

/* 読込完了 */
$(document).ready(function(){
    /* Key Event */
    $(document).on('change','#regist_check', function(){
        //入力確認チェック
        if ($("#regist_check").prop("checked") == true) {
            //入力確認ボタンを使用可
            $('#regist_button').prop("disabled", false);
        } else {
            //入力確認ボタンを使用不可
            $('#regist_button').prop("disabled", true);
        }
    });

    $(document).on('click','#regist_button', function(){
        //入力確認ボタン押下後、入力内容確認チェックを外す
        $('#regist_check').prop('checked', false);
    });

    if($('#zipcode_search').length){
        $(document).on('click','#zipcode_search', function(){
            //郵便番号取得
            //既存の無料公開APIがSSL化していないため、後日独自APIを開発する。
        });
    }

    if($('#input_pref_cd').length){
        $(document).on('change','#input_pref_cd', function(){
            //都道府県選択後、都道府県名を設定
            if ($(this).val() == ''){
                $('#input_pref').val('');
            } else {
                $('#input_pref').val($('#input_pref_cd option:selected').text());
            }
        });
    }

    if($('#input_no').length){
        $(document).on('focusout','#input_no', function(){
            //番号の重複確認
            const input_no = $('#input_no').val();
            if (input_no.length <= 0){
                //入力されていなければ何もしない
                return false;
            }
            const send_url = $('#check_no').val() + '?n=' + input_no;
            chkDuplicateNo(send_url);
        });
    }

    if($('#input_id').length){
        $(document).on('focusout','#input_id', function(){
            //IDの重複確認
            const input_id = $('#input_id').val();
            if (input_id.length <= 0){
                //入力されていなければ何もしない
                return false;
            }
            const send_url = $('#check_id').val() + '?i=' + input_id;
            chkDuplicateId(send_url);
        });
    }

    if($('#atag_comp').length){
        $(document).on('click','#atag_comp', function(){
            //選択文字列にaタグ挿入
            
            //選択文字列抽出
            const element = document.getElementById('input_text');
            const start = element.selectionStart; //選択開始位置
            const end = element.selectionEnd; //選択終了位置
            const value = element.value; //文字列取得
            const str = value.slice(start, end); //選択範囲を抽出
            
            //選択範囲に抽出文字列があれば挿入
            if (str.length > 0){
                const url = $('#atag_url').val(); //URL
                const target = $('#atag_target').val(); //target

                //aタグ生成
                const new_str = '<a href="' + url + '" target="' + target + '">' + str + '</a>';

                //タグ挿入位置
                const str_bfore = value.slice(0, start); //選択範囲直前までのテキスト抽出
                const str_after = value.slice(end, value.length); //選択範囲直後からののテキスト抽出

                //タグ挿入後の文字列を再設定
                $('#input_text').val(str_bfore + new_str + str_after);

                //モーダル初期化
                $('#atag_url').val(''); //URL
                $('#atag_target').val(''); //target
            }
        });
    }

    /* 関数定義 */
    // 指定番号の重複確認
    function chkDuplicateNo(send_url){
        //Jsエラーメッセージエリアを一旦非表示
        $('.no_js_message').removeClass('d-block');
        $('.no_js_message').addClass('d-none');
        
        //送信リクエスト生成
        $.ajax({
            type: "get",
            url: send_url,
            dataType: 'json',
            beforeSend: function( xhr, settings ) { xhr.setRequestHeader('Authorization', 'Bearer ' + localStorage.getItem('token') ); }
        })
        //通信が成功したとき
        .done((res)=>{
            if (res){
                //重複有り
                $('.no_js_message').removeClass('d-none');
                $('.no_js_message').addClass('d-block');
                $('.no_js_message').text($('#js_message1').val());
                $('#input_no').focus();
                $('#input_no').select();
            }
        })
        //通信が失敗したとき
        .fail((error)=>{
            alert($('#js_message').val());
            console.log(error.statusText)
            return false;
        })
    }
    
    // 指定IDの重複確認
    function chkDuplicateId(send_url){
        //Jsエラーメッセージエリアを一旦非表示
        $('.id_js_message').removeClass('d-block');
        $('.id_js_message').addClass('d-none');
        
        //送信リクエスト生成
        $.ajax({
            type: "get",
            url: send_url,
            dataType: 'json',
            beforeSend: function( xhr, settings ) { xhr.setRequestHeader('Authorization', 'Bearer ' + localStorage.getItem('token') ); }
        })
        //通信が成功したとき
        .done((res)=>{
            if (res){
                //重複有り
                $('.id_js_message').removeClass('d-none');
                $('.id_js_message').addClass('d-block');
                $('.id_js_message').text($('#js_message1').val());
                $('#input_id').focus();
                $('#input_id').select();
            }
        })
        //通信が失敗したとき
        .fail((error)=>{
            alert($('#js_message').val());
            console.log(error.statusText)
            return false;
        })
    }
});
