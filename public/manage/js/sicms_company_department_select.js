/*!
  * Sicms JS v1.1.1
  * Copyright 2021-2021 Webじゃ研 Authors
  * Licensed under MIT
  */

/*!
  * 初期設定
  */
/* イベントリスナー開放（初期化） */
$(document).off('#get_department_list');
$(document).off('#next_department');
$(document).off('#department_search');
$(document).off('.select_department');

/* 読込完了 */
$(document).ready(function(){

    /* Key Event初期設定 */
    $(document).on('click','#get_department_list', function(){
        //最初の部署一覧を表示
        //モーダル初期化
        defaultDepartmentModalSetting();
        $('#department_free_word').val(''); //検索ワード欄を初期化

        //部署取得
        const send_url = $('#get_department').val() + '?n=' + $('#input_cpb_no').val() + '&start=0';
        getDepartmentList(send_url);
    });

    $(document).on('click','#next_department', function(){
        //次の部署一覧を表示
        //モーダル初期化
        $('#department_preview').empty(); //プレビューエリアを初期化

        //部署取得
        const send_url = $('#get_department').val() + '?n=' + $('#input_cpb_no').val() + '&input_free_word=' + encodeURIComponent($('#department_free_word').val()) + '&start=' + $('#select_department_counter').val();
        getDepartmentList(send_url);
    });

    $(document).on('click','#department_search', function(){
        //検索ワードによる部署一覧を表示
        //モーダル初期化
        defaultDepartmentModalSetting();

        //部署取得
        const send_url = $('#get_department').val() + '?n=' + $('#input_cpb_no').val() + '&input_free_word=' + encodeURIComponent($('#department_free_word').val()) + '&start=0';
        getDepartmentList(send_url);
    });

    $(document).on('click','.select_department', function(){
        //部署名称選択

        //部署番号取得
        const department_no = $(this).val();
        //部署名取得
        const department_name = $(this).text();

        // 部署情報を渡す
        $('#input_cpd_no').val(department_no);
        $('#input_cpd_name').val(department_name);
        $('#department_preview').html(department_name);
        if (("#input_acnt_no") !== null){
            $('#input_acnt_no').val('');
            $('#input_acnt_name').val('');
            $('#acnt_preview').html('');
        }

        //モーダルを閉じる
        $('#insert_department').modal('hide');
    });
    /* Key Event初期設定 */

    /* 関数定義 */
    //モーダル初期化
    function defaultDepartmentModalSetting(){
        $('#department_preview').empty(); //プレビューエリアを初期化
        $('#select_department_counter').val(0); //開始位置カウンタ初期化
        $('#tbody_department_list').empty(); //一覧を初期化
    }
    // 部署一覧取得
    function getDepartmentList(send_url){
        
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
                $('#insert_department').modal('hide');
            } else {
                // 取得部署リスト作成
                let data_count = 0;
                $('#department_list').empty();
                res.forEach(function(data){

                    const button_obj = $('<button type="button" class="list-group-item list-group-item-action select_department">');
                    button_obj.val(data.comp_department_no);
                    button_obj.append(data.comp_department_name);

                    // 部署データ挿入
                    $('#department_list').append(button_obj);
                    
                    // カウント
                    let start_cnt = Number($('#select_department_counter').val());
                    start_cnt = start_cnt + 1;
                    $('#select_department_counter').val(start_cnt);
                    
                    //表示件数
                    data_count = data_count + 1;
                });

                // 取得件数ゼロ
                if (data_count <= 0){
                     $('#department_list').append('<p>該当データは登録されていません。</p>');
                     $('#next_department').removeClass('d-block');
                     $('#next_department').addClass('d-none');
                } else {
                    // ボタン表示有無判定
                    $('#next_department').removeClass('d-none');
                    $('#next_department').addClass('d-block');
                    if (data_count >= 5){
                        $('#next_department').prop("disabled", false);
                    } else {
                        $('#next_department').prop("disabled", true);
                    }
                }
                // モーダル表示
                $('#insert_department').modal('show');
            }
        })
        //通信が失敗したとき
        .fail((error)=>{
            //警告
            alert($('#js_message').val());
            console.log(error.statusText)
            $('#insert_department').modal('hide');
        })
    }
});