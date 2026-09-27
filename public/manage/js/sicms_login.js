/*!
  * Sicms JS v1.1.1
  * Copyright 2021-2021 Webじゃ研 Authors
  * Licensed under MIT
  */

/*!
  * 初期設定
  */
/* イベントリスナー開放（初期化） */
$(document).off('#login_btn');

/* 読込完了 */
$(document).ready(function(){

    /* Key Event */
    $(document).on('click','#login_btn', function(){
       //ログインAPI実行
       startLoginSeq();
    });

    /* 関数定義 */
    // ログイン処理
    function startLoginSeq(){
        //通信パラメータ設定
        const ajax_headers = {
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        };
        const ajax_send = {
            type: "post",
            url: $('#login-api').val(),
            data: { "acnt_id" : $('input[name="acnt_id"]').val(), "password" : $('input[name="password"]').val(), "password_confirmation" : $('input[name="password_confirmation"]').val(), "rememberme" : $('input[name="rememberme"]').val()},
            dataType: 'json'
        }

        //通信
        sendLoginAjax(ajax_headers, ajax_send, 'login');
    }

    //ログイン結果
    function resultLogin(res){
        console.log(res);
        console.log(res.result);
        if(res.result === false){
            //error
            $('.alert-danger').html(res['data']);
            $('.alert-danger').removeClass('d-none');
            $('.alert-danger').addClass('d-block');
        } else {
            if (res.data === 'twoverify'){
                //二段階認証画面へ遷移
                window.location.href = $('#twostep-page').val();
            } else {
                //メイン画面へ遷移
                localStorage.setItem('token', res.data); //トークン保存
                window.location.href = $('#main-page').val();
            }
        }
    }

    // 非同期通信
    function sendLoginAjax(headers, send, mode){
        //初期化
        $('.alert-danger').removeClass('d-block');
        $('.alert-danger').addClass('d-none');

        //送信リクエスト生成
        $.ajaxSetup(headers);
        $.ajax(send)
        //通信が成功したとき
        .done((res)=>{
            switch (mode) {
                // ログイン
                case 'login':
                    resultLogin(res);
                    break;
            }
        })
        //通信が失敗したとき
        .fail((error)=>{
            alert($('#js_message').val());
            console.log(error.statusText)
        })
    }
});
