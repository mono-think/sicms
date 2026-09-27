/*!
  * Sicms JS v1.1.1
  * Copyright 2021-2021 Webじゃ研 Authors
  * Licensed under MIT
  */

/*!
  * 初期設定
  */
$(document).ready(function(){

    /* Key Event初期設定 */
    if($('#get_image_list').length){
        $(document).on('click','#get_image_list', function(){
            //最初の画像一覧を表示
            //モーダル初期化
            defaultImageModalSetting();
            $('#image_free_word').val(''); //検索ワード欄を初期化
            $('#im').val('t'); //画像ブロック挿入

            //画像取得
            const send_url = $('#get_image').val() + '?start=0';
            getImageList(send_url);
        });
    }

    if($('#get_top_image_list').length){
        $(document).on('click','#get_top_image_list', function(){
            //最初の画像一覧を表示
            //モーダル初期化
            defaultImageModalSetting();
            $('#image_free_word').val(''); //検索ワード欄を初期化
            $('#im').val('i'); //画像ファイル挿入

            //画像取得
            const send_url = $('#get_image').val() + '?start=0';
            getImageList(send_url);
        });
    }

    $(document).on('click','#next_image', function(){
        //次の画像一覧を表示
        //モーダル初期化
        $('input[name="input_image_file[]"]').val(''); //ファイル選択を初期化
        $('.preview_area').empty(); //プレビューエリアを初期化

        //画像取得
        const send_url = $('#get_image').val() + '?input_free_word=' + encodeURIComponent($('#image_free_word').val()) + '&start=' + $('#select_image_counter').val();
        getImageList(send_url);
    });

    $(document).on('click','#image_search', function(){
        //検索ワードによる画像一覧を表示
        //モーダル初期化
        defaultImageModalSetting();
        //画像取得
        const send_url = $('#get_image').val() + '?input_free_word=' + encodeURIComponent($('#image_free_word').val()) + '&start=0';
        getImageList(send_url);
    });

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
        UploadImage('modal');
    });

    $(document).on('click','.select_image', function(){
        /* 後付けKey Event設定 */
        //モーダルから画像情報取得

        //画像ベースフォルダ取得
        const image_base = $('#image_base').val();
        // 画像タイプ取得
        const imagetype_list = $('input[name="image_type[]"]').map(function() {
            return $(this).val();
        }).get();
        // 画像表示種別の取得
        const imagedisp_list = $('input[name="image_disp[]"]').map(function() {
            return $(this).val();
        }).get();
        //画像番号取得
        const image_no = $(this).parents().children('.image_no').val();
        //画像名取得
        const image_name = $(this).parents().children('.image_name').val();
        //画像フォルダ取得
        const image_folder = $(this).parents().children('.image_folder').val();
        //画像種別取得
        const image_type = $(this).parents().children('.image_type').val();
        //画像代替テキスト取得
        const image_alt = $(this).parents().children('.image_alt').val();
        //画像キャプション取得
        const image_text = $(this).parents().children('.image_text').val();
        //画像ファイル名取得
        const image_file = $(this).attr("src");

        //挿入パターン判定
        if ($('#im').val() === 't') {
            //画像ブロック構築
            figure_tag = create_image_figure(image_base, image_name, image_folder, image_type, image_alt, image_text, imagetype_list, imagedisp_list);

            //タグのみ挿入
            const element_textarea = document.getElementsByClassName("insert_text");

            //選択文字列抽出
            const start = element_textarea[0].selectionStart; //選択開始位置
            const value = element_textarea[0].value; //文字列取得

            //タグ挿入位置
            const str_bfore = value.slice(0, start); //選択位置までのテキスト抽出
            const str_after = value.slice(start, value.length); //選択位置からののテキスト抽出

            // 子ウィンドから親ウィンドウへ値を渡す
            element_textarea[0].value = str_bfore + figure_tag.prop('outerHTML') + str_after;
        } else if ($('#im').val() === 'i') {
            //画像番号、画像ファイルを設定
            $("#image_name_preview").html(image_name);
            $("#image_preview").children('img').attr('src', image_file);;
            $("#input_image_no").val(image_no);
            $("#input_image_name").val(image_name);
            $("#input_image_file").val(image_file);
        }

        //モーダルを閉じる
        $('#insert_image').modal('hide');
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
    /* Key Event初期設定 */
});

// 画像一覧取得
function getImageList(send_url){

    //通信パラメータ設定
    const ajax_headers = {}
    const ajax_send = {
        type: "get",
        url: send_url,
        dataType: 'json',
        beforeSend: function( xhr, settings ) { xhr.setRequestHeader('Authorization', 'Bearer ' + localStorage.getItem('token') ); }
    }

    //通信
    sendImageAjax(ajax_headers, ajax_send, 'getImageList', '');
}

//画像リスト取得結果
function resultGetImageList(res){
    if (res === false) {
        alert($('#js_message').val());
        $('#insert_image').modal('hide');
    } else {
        // 取得画像リスト作成
        let data_count = 0;
        res.forEach(function(data){
            if (data_count < 5){
                // 画像データ挿入
                const tr_obj = create_imagelist_block(data);
                $('#tbody_image_list').append(tr_obj);

                // カウント
                let start_cnt = Number($('#select_image_counter').val());
                start_cnt = start_cnt + 1;
                $('#select_image_counter').val(start_cnt);
            }

            //表示件数
            data_count = data_count + 1;
        });

        // 取得件数ゼロ
        if (data_count <= 0){
            $('#tbody_image_list').append('<tr><td>該当データは登録されていません。</td></tr>');
            $('#next_image').removeClass('d-block');
            $('#next_image').addClass('d-none');
        } else {
            // ボタン表示有無判定
            $('#next_image').removeClass('d-none');
            $('#next_image').addClass('d-block');
            if (data_count > 5){
                $('#next_image').prop("disabled", false);
            } else {
                $('#next_image').prop("disabled", true);
            }
        }

        // モーダル表示
        $('#insert_image').modal('show');
    }
}

// 画像リスト用ブロック生成 
function create_imagelist_block(data) {

    const tr_obj = $('<tr>');
    const td_obj1 = $('<td class="text-center w-50">');
    const td_obj1_img = $('<img class="select_image" width="400" />');
    td_obj1_img.attr('src',data.img_file);
    const td_obj1_input1 = $('<input type="hidden" class="image_no" />');
    td_obj1_input1.val(data.image_no)
    const td_obj1_input2 = $('<input type="hidden" class="image_name" />');
    td_obj1_input2.val(data.image_name)
    const td_obj1_input3 = $('<input type="hidden" class="image_type" />');
    td_obj1_input3.val(data.image_type)
    const td_obj1_input4 = $('<input type="hidden" class="image_folder" />');
    td_obj1_input4.val(data.image_folder)
    const td_obj1_input5 = $('<input type="hidden" class="image_alt" />');
    td_obj1_input5.val(data.image_alt)
    const td_obj1_input6 = $('<input type="hidden" class="image_text" />');
    td_obj1_input6.val(data.image_text)
    td_obj1.append(td_obj1_img);
    td_obj1.append(td_obj1_input1);
    td_obj1.append(td_obj1_input2);
    td_obj1.append(td_obj1_input3);
    td_obj1.append(td_obj1_input4);
    td_obj1.append(td_obj1_input5);
    td_obj1.append(td_obj1_input6);
    td_obj1.append('<p class="mx-5 my-1 p-0 text-left">【画像名】' + data.image_name + '<br />【画像パス】' + data.image_folder + '<br />【画像タイプ】' + data.image_type + '</p>');

    const td_obj2 = $('<td>');
    const div_alert1 = $('<div class="alert alert-danger update-danger d-none" role="alert">');
    const div_alert2 = $('<div class="alert alert-success update-success d-none" role="alert">');

    const p_data1 = $('<p>');
    p_data1.html('カテゴリ：' + data.image_category_name);
    const p_data1_input = $('<input type="hidden" class="ictg" />');
    p_data1_input.val(data.image_category);
    p_data1.append(p_data1_input);

    const div_data2 = $('<div class="input-group">');
    const div_data2_div = $('<div class="input-group-prepend">');
    const div_data2_div_span = $('<span class="input-group-text">');
    div_data2_div_span.html('代替テキスト');
    const div_data2_textarea = $('<textarea cols="3" rows="5" class="form-control input_image_alt">');
    div_data2_textarea.val(data.image_alt);
    div_data2_div.append(div_data2_div_span);
    div_data2.append(div_data2_div);
    div_data2.append(div_data2_textarea);

    const div_data3 = $('<div class="input-group">');
    const div_data3_div = $('<div class="input-group-prepend">');
    const div_data3_div_span = $('<span class="input-group-text">');
    div_data3_div_span.html('キャプション');
    const div_data3_textarea = $('<textarea cols="3" rows="5" class="form-control input_image_text">');
    div_data3_textarea.val(data.image_text);
    div_data3_div.append(div_data3_div_span);
    div_data3.append(div_data3_div);
    div_data3.append(div_data3_textarea);

    const td_input = $('<input type="hidden" class="send_image_update" />');
    td_input.val(data.url);
    const td_button = $('<button type="button" class="btn btn-primary image_update">');
    td_button.html('更新');

    // TDタグ挿入
    td_obj2.append(div_alert1);
    td_obj2.append(div_alert2);
    td_obj2.append(p_data1);
    td_obj2.append(div_data2);
    td_obj2.append(div_data3);
    td_obj2.append(td_input);
    td_obj2.append(td_button);

    // TRタグ挿入
    tr_obj.append(td_obj1);
    tr_obj.append(td_obj2);

    return tr_obj;
}

// 画像(figureブロック生成) 
function create_image_figure(image_base, image_name, image_folder, image_type, image_alt, image_text, imagetype_list, imagedisp_list) {

    //挿入画像タグ生成
    const figure_tag = $('<figure></figure>');
    const picture_tag = $('<picture></picture>');
    const figcaption_tag = $('<figcaption></figcaption>');

    //パス生成
    const img_path = image_base + '/' +  image_folder + '/' + image_name;

    //複数画像パス
    imagetype_list.forEach(function(type, o_idx){
        let srimg_child = [];
        if (type.length > 0){
            w_image_type = type;
        } else {
            w_image_type = image_type;
        }

        imagedisp_list.forEach(function(wnum, i_idx){
            if (srimg_child[i_idx] !== ""){
                srimg_child[i_idx] = srimg_child[i_idx] + ', ';
            }
            srimg_child[i_idx] = img_path + '_' + wnum + '.' + w_image_type + ' ' + wnum + 'w';
        });
        if (o_idx <= 0){ //最初はimgタグ用のみ
            //imgタグ生成
            const img_tag = $('<img loading="lazy" class="select_image"></img>');
            img_tag.attr('src',img_path + '.' + w_image_type);
            img_tag.attr('alt',image_alt);
            img_tag.attr('srcset',srimg_child.toString());
            picture_tag.append(img_tag);
        } else {
            //sourceタグ生成
            const source_tag = $('<source></source>');
            source_tag.attr('type','image/' + w_image_type);
            source_tag.attr('srcset',srimg_child.toString());
            picture_tag.prepend(source_tag);
        }
    });

    //キャプション設定
    figcaption_tag.text(image_text);

    //画像ブロック構築
    figure_tag.append(picture_tag);
    figure_tag.append(figcaption_tag);
    
    return figure_tag;
}