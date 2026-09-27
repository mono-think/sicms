/*!
  * Sicms JS v1.1.1
  * Copyright 2021-2021 Webじゃ研 Authors
  * Licensed under MIT
  */

/*!
  * 初期設定
  */
/* イベントリスナー開放（初期化） */
$(document).off('#get_branch_list');
$(document).off('#next_branch');
$(document).off('#branch_search');
$(document).off('.select_branch');

/* 読込完了 */
$(document).ready(function(){

    /* Key Event初期設定 */
    $(document).on('click','#get_branch_list', function(){
        //最初の支店一覧を表示
        //モーダル初期化
        defaultBranchModalSetting();
        $('#branch_free_word').val(''); //検索ワード欄を初期化

        //支店取得
        const send_url = $('#get_branch').val() + '?start=0';
        getBranchList(send_url);
    });

    $(document).on('click','#next_branch', function(){
        //次の支店一覧を表示
        //モーダル初期化
        $('#branch_preview').empty(); //プレビューエリアを初期化

        //支店取得
        const send_url = $('#get_branch').val() + '?input_free_word=' + encodeURIComponent($('#branch_free_word').val()) + '&start=' + $('#select_branch_counter').val();
        getBranchList(send_url);
    });

    $(document).on('click','#branch_search', function(){
        //検索ワードによる支店一覧を表示
        //モーダル初期化
        defaultBranchModalSetting();

        //支店取得
        const send_url = $('#get_branch').val() + '?input_free_word=' + encodeURIComponent($('#branch_free_word').val()) + '&start=0';
        getBranchList(send_url);
    });

    $(document).on('click','.select_branch', function(){
        //支店名称選択

        //支店番号取得
        const branch_no = $(this).val();
        //支店名取得
        const branch_name = $(this).text();

        // 支店情報を渡す
        $('#input_cpb_no').val(branch_no);
        $('#input_cpb_name').val(branch_name);
        $('#branch_preview').html(branch_name);

        // その他を初期化
        $('#input_cpd_no').val('');
        $('#input_cpd_name').val('');
        $('#department_preview').html('');
        if (("#input_acnt_no") !== null){
            $('#input_acnt_no').val('');
            $('#input_acnt_name').val('');
            $('#acnt_preview').html('');
        }

        //モーダルを閉じる
        $('#insert_branch').modal('hide');
    });
    /* Key Event初期設定 */

    /* 関数定義 */
    //モーダル初期化
    function defaultBranchModalSetting(){
        $('#branch_preview').empty(); //プレビューエリアを初期化
        $('#select_branch_counter').val(0); //開始位置カウンタ初期化
        $('#tbody_branch_list').empty(); //一覧を初期化
    }

    // 支店一覧取得
    function getBranchList(send_url){
        
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
                $('#insert_branch').modal('hide');
            } else {
                // 取得支店リスト作成
                let data_count = 0;
                $('#branch_list').empty();
                res.forEach(function(data){

                    const button_obj = $('<button type="button" class="list-group-item list-group-item-action select_branch">');
                    button_obj.val(data.comp_branch_no);
                    button_obj.append(data.comp_branch_name);

                    // 支店データ挿入
                    $('#branch_list').append(button_obj);
                    
                    // カウント
                    let start_cnt = Number($('#select_branch_counter').val());
                    start_cnt = start_cnt + 1;
                    $('#select_branch_counter').val(start_cnt);
                    
                    //表示件数
                    data_count = data_count + 1;
                });

                // 取得件数ゼロ
                if (data_count <= 0){
                     $('#branch_list').append('<p>該当データは登録されていません。</p>');
                     $('#next_branch').removeClass('d-block');
                     $('#next_branch').addClass('d-none');
                } else {
                    // ボタン表示有無判定
                    $('#next_branch').removeClass('d-none');
                    $('#next_branch').addClass('d-block');
                    if (data_count >= 5){
                        $('#next_branch').prop("disabled", false);
                    } else {
                        $('#next_branch').prop("disabled", true);
                    }
                }

                // モーダル表示
                $('#insert_branch').modal('show');
            }
        })
        //通信が失敗したとき
        .fail((error)=>{
            //警告
            alert($('#js_message').val());
            console.log(error.statusText)
            $('#insert_branch').modal('hide');
        })
    }
});
