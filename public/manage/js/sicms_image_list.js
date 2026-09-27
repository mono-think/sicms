/*!
  * Sicms JS v1.1.1
  * Copyright 2021-2021 Webじゃ研 Authors
  * Licensed under MIT
  */

/*!
  * 初期設定
  */
$(document).ready(function(){

    /* Key Event */
    $(document).on('change','input[name="input_image_file[]"]', function(e){

        //画像ファイルプレビュー
        $('.preview_area').html('');

        //アップロードボタンを使用可
        $('#image_upload_button').prop("disabled", false); //通常用

        //プレビュー領域に画像設定
        const file_length = e.target.files.length;
        for (i = 0;i < file_length;i++){
            const reader = new FileReader();
            reader.onload = function (elm) {
               const img_tag = $('<img width="200"></img>');
               img_tag.attr('src',elm.target.result);
               $('.preview_area').append(img_tag);
            }
            reader.readAsDataURL(e.target.files[i]);
        }
    });

    $(document).on('click','#image_upload_button', function(){
        //画像アップロード
        UploadImage('regist');
    });

    $(document).on('click','.image_update', function(){
        //画像情報更新

        //通信パラメータ設定
        const ajax_headers = {
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        };
        const ajax_send = {
            type: "put",
            url: $(this).parent().find('.send_image_update').val(),
            data: { "ctg_no" : $(this).parent().find('.ictg').val(), "alt" : $(this).parent().find('.input_image_alt').val(), "text" : $(this).parent().find('.input_image_text').val()},
            dataType: 'json'
        }

        //通信
        sendImageAjax(ajax_headers, ajax_send, 'updateImage', $(this).parent());
    });
});
