/*!
  * Sicms JS v1.1.1
  * Copyright 2021-2021 Webじゃ研 Authors
  * Licensed under MIT
  */

/*!
  * 初期設定
  */
/* イベントリスナー開放（初期化） */
$(document).off('#get_acnt_list');
$(document).off('#next_acnt');
$(document).off('#acnt_search');
$(document).off('.select_acnt');

/* 読込完了 */
$(document).ready(function(){

    /* Key Event初期設定 */
    $(document).on('click','#get_acnt_list', function(){
        //最初のアカウント一覧を表示
        //モーダル初期化
        defaultAcntModalSetting();
        $('#acnt_free_word').val(''); //検索ワード欄を初期化

        //アカウント取得
        const send_url = $('#get_acnt').val() + '?bno=' + $('#input_cpb_no').val() + '&dno=' + $('#input_cpd_no').val() + '&start=0';
        getAcntList(send_url);
    });

    $(document).on('click','#next_acnt', function(){
        //次のアカウント一覧を表示
        //モーダル初期化
        $('#acnt_preview').empty(); //プレビューエリアを初期化

        //アカウント取得
        const send_url = $('#get_acnt').val() + '?input_free_word=' + encodeURIComponent($('#acnt_free_word').val()) + '&bno=' + $('#input_cpb_no').val() + '&dno=' + $('#input_cpd_no').val() + '&start=' + $('#select_acnt_counter').val();
        getAcntList(send_url);
    });

    $(document).on('click','#acnt_search', function(){
        //検索ワードによるアカウント一覧を表示
        //モーダル初期化
        defaultAcntModalSetting();

        //アカウント取得
        const send_url = $('#get_acnt').val() + '?input_free_word=' + encodeURIComponent($('#acnt_free_word').val()) + '&bno=' + $('#input_cpb_no').val() + '&dno=' + $('#input_cpd_no').val() + '&start=0';
        getAcntList(send_url);
    });

    $(document).on('click','.select_acnt', function(){
        //アカウント名称選択

        //アカウント番号取得
        const acnt_no = $(this).val();
        //支店名取得
        const acnt_name = $(this).text();

        // アカウント情報を渡す
        $('#input_acnt_no').val(acnt_no);
        $('#input_acnt_name').val(acnt_name);
        $('#acnt_preview').html(acnt_name);

        //モーダルを閉じる
        $('#insert_acnt').modal('hide');
    });
    /* Key Event初期設定 */

    /* 関数定義 */
    //モーダル初期化
    function defaultAcntModalSetting(){
        $('#acnt_preview').empty(); //プレビューエリアを初期化
        $('#select_acnt_counter').val(0); //開始位置カウンタ初期化
        $('#tbody_acnt_list').empty(); //一覧を初期化
    }
    // アカウント一覧取得
    function getAcntList(send_url){
        
        //送信リクエスト生成
        $.ajax({
            type: "get",
            url: send_url,
            dataType: 'json',
            beforeSend: function( xhr, settings ) { xhr.setRequestHeader('Authorization', 'Bearer ' + localStorage.getItem('token') ); }
        })
        //通信が成功したとき
        .done((res)=>{
            if (res === false) {
                alert($('#js_message').val());
                $('#insert_acnt').modal('hide');
            } else {
                // 取得アカウントリスト作成
                let data_count = 0;
                $('#acnt_list').empty();
                res.forEach(function(data){
                    const button_obj = $('<button type="button" class="list-group-item list-group-item-action select_acnt">');
                    button_obj.val(data.acnt_no);
                    button_obj.append(data.acnt_name);

                    // アカウントデータ挿入
                    $('#acnt_list').append(button_obj);
                    
                    // カウント
                    let start_cnt = Number($('#select_acnt_counter').val());
                    start_cnt = start_cnt + 1;
                    $('#select_acnt_counter').val(start_cnt);
                    
                    //表示件数
                    data_count = data_count + 1;
                });

                // 取得件数ゼロ
                if (data_count <= 0){
                     $('#acnt_list').append('<p>該当データは登録されていません。</p>');
                     $('#next_acnt').removeClass('d-block');
                     $('#next_acnt').addClass('d-none');
                } else {
                    // ボタン表示有無判定
                     $('#next_acnt').removeClass('d-none');
                     $('#next_acnt').addClass('d-block');
                    if (data_count >= 5){
                        $('#next_acnt').prop("disabled", false);
                    } else {
                        $('#next_acnt').prop("disabled", true);
                    }
                }

                // モーダル表示
                $('#insert_acnt').modal('show');
            }
        })
        //通信が失敗したとき
        .fail((error)=>{
            alert($('#js_message').val());
            console.log(error.statusText);
            $('#insert_acnt').modal('hide');
        })
    }
});
