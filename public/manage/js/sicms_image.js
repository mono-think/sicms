/*!
  * Sicms JS v1.1.1
  * Copyright 2021-2021 Webじゃ研 Authors
  * Licensed under MIT
  */

/* イベントリスナー開放（初期化） */
$(document).off('#next_image');
$(document).off('#image_search');
$(document).off('input[name="input_image_file[]"]');
$(document).off('#image_upload_button');
$(document).off('.select_image');
$(document).off('.image_update');

/* 関数定義 */
// 画像アップロード
function UploadImage(ret_param) {

    //画像アップロード
    const send_url = $('#image_upload').val();
    $('progress').attr({
        value: 0,
        max: 100,
    });
    
    //通信パラメータ設定
    const ajax_headers = {}
    const ajax_send = {
        type: 'post',
        url: send_url,
        data: new FormData($('#image_regist_form')[0]),
        cache: false,
        contentType: false,
        processData: false,
        xhr: function () {
            const xhr_obj = $.ajaxSettings.xhr();
            if (xhr_obj.upload) {
                xhr_obj.upload.addEventListener('progress', function (e) {
                    if (e.lengthComputable) {
                        $('progress').attr({
                            value: e.loaded,
                            max: e.total,
                        });
                        
                    }
                }, false);
            }
            return xhr_obj;
        }
    }

    //通信
    sendImageAjax(ajax_headers, ajax_send, 'uploadImage', ret_param);
}

//モーダル初期化
function defaultImageModalSetting(){
    $('input[name="input_image_file[]"]').val(''); //ファイル選択を初期化
    $('.preview_area').empty(); //プレビューエリアを初期化
    $('#select_image_counter').val(0); //開始位置カウンタ初期化
    $('#tbody_image_list').empty(); //一覧を初期化
}

// 非同期通信
function sendImageAjax(headers, send, mode, opt){
    //送信リクエスト生成
    $.ajaxSetup(headers);
    $.ajax(send)
    //通信が成功したとき
    .done((res)=>{
        switch (mode) {
            // 画像アップロード
            case 'uploadImage':
                resultUploadImageInfo(res, opt);
                break;
            // 画像情報の更新
            case 'updateImage':
                resultUpdateImageInfo(res, opt);
                break;
           // 画像リストの取得
            case 'getImageList':
                resultGetImageList(res);
                break;
        }
    })
    //通信が失敗したとき
    .fail((error)=>{
        alert($('#js_message').val());
        console.log(error.statusText);
        $('#insert_image').modal('hide');
    })
}

//画像アップロード結果
function resultUploadImageInfo(res, param){
    if (param === 'regist') {
        if (res !== 'false'){
            alert($('#js_image_message4').val());
        } else {
            alert($('#js_image_message3').val());
        }
        location.reload();
    } else if (param === 'modal') {
        if (res !== 'false'){
            //モーダル初期化
            defaultImageModalSetting();
            $('#image_free_word').val(''); //検索ワード欄初期化

            //画像取得
            const send_url = $('#get_image').val() + '?start=0';
            getImageList(send_url);
            $('input[name="input_image_file[]"]').val('');
        } else {
            //モーダル初期化
            $('input[name="input_image_file[]"]').val(''); //ファイル選択を初期化
            $('.preview_area').empty(); //プレビューエリアを初期化
            $('#select_image_counter').val(0); //開始位置カウンタ初期化
            $('#image_free_word').val(''); //検索ワード欄初期化
            $('.upload-danger').removeClass('d-none');
            $('.upload-danger').addClass('d-block');
            $('.upload-danger').html($('#js_image_message').val());
        }
    }
}

//画像情報更新結果
function resultUpdateImageInfo(res, obj){
    if (res === false) {
        obj.find('.update-success').removeClass('d-block');
        obj.find('.update-success').addClass('d-none');
        obj.parent().find('.update-success').html('');
        obj.find('.update-danger').removeClass('d-none');
        obj.find('.update-danger').addClass('d-block');
        obj.find('.update-danger').html($('#js_image_message1').val());
    } else {
        obj.find('.update-danger').removeClass('d-block');
        obj.find('.update-danger').addClass('d-none');
        obj.find('.update-danger').html('');
        obj.find('.update-success').removeClass('d-none');
        obj.find('.update-success').addClass('d-block');
        obj.parent().find('.update-success').html($('#js_image_message2').val());
    }
}
