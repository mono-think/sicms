/*!
  * Sicms JS v1.1.1
  * Copyright 2021-2021 Webじゃ研 Authors
  * Licensed under MIT
  */
/* --- Basic Seq & Event --- start --- */
$(window).on('load', function(){

    //ページビュー加算
	const title = document.getElementsByName('title').item(0).content;
    sendAjaxGetRequest('addpv', $('#addpv').val() + '?u=' + encodeURIComponent(location.href) + '&t=' + title);
    //バナーリスト取得
    sendAjaxGetRequest('ads', $('#getsupporter').val());
    
    //特定ページ表示処理
    const barba_name = $('#horizontal-write').attr('data-barba-namespace');
    if ((barba_name !== 'home') && (barba_name !== 'products-category') && (barba_name !== 'products') && (barba_name !== 'company-shop') && (barba_name !== 'contact')) {
        //全新着情報
        sendAjaxGetRequest('newupdate', $('#latest_all').val());
        //ページビューランキング取得
        sendAjaxGetRequest('getpv', $('#getpv').val());
    }

    //ポリシー同意イベント
    chkPolisy();

	if (barba_name !== 'home') {
        (adsbygoogle = window.adsbygoogle || []).push({});
	}
});
/* --- Basic Seq & Event --- end --- */
/* --- barba Seq & Event --- start --- */
barba.init({
    cacheIgnore: true,
    transitions:[{
        name:'default-transition',
        enter(data){
		    //共通部表示処理
		    //ポリシー同意イベント
		    chkPolisy();
	        //ヘッダ部書換
	        replaceHead(data);
        },
        after(){
 		    //ページビュー加算
        	const title = document.getElementsByName('title').item(0).content;
 		    sendAjaxGetRequest('addpv', $('#addpv').val() + '?u=' + encodeURIComponent(location.href) + '&t=' + title);
		    //Googleアナリティクスへの情報送信(実行後（コンテナ切替後？)イベント）
		    if (typeof gtag === 'function') {
		        gtag('config', window.GA_MEASUREMENT_ID,{
		            page_path: window.location.pathname
		        });
		    }
		    
		    //特定ページ表示処理
		    const barba_name = $('#horizontal-write').attr('data-barba-namespace');
		    if (barba_name !== 'home') {
		        (adsbygoogle = window.adsbygoogle || []).push({});
			}
        }
    }],
    views: [{
        namespace: 'home',
        beforeEnter() {
            //表示情報取得
            $('.vertical-write-r').css('opacity','0');
        },
        afterEnter() {
            //表示情報取得
            sendAjaxGetRequest('newupdate_index', $('#latest_all').val(),'top_latest_div'); //全新着情報
            sendAjaxGetRequest('newupdate_index', $('#latest_products').val(),'top_contents_aera1_div'); //製品新着情報
            sendAjaxGetRequest('newupdate_index', $('#latest_company').val(),'top_contents_aera2_div'); //企業新着情報
            sendAjaxGetRequest('newupdate_index', $('#latest_event').val(),'top_contents_aera3_div'); //イベント新着情報
            sendAjaxGetRequest('newupdate_index', $('#latest_content1').val(),'top_contents_aera4_div'); //特集新着情報
            sendAjaxGetRequest('newupdate_index', $('#latest_content2').val(),'top_contents_aera5_div'); //ニュース新着情報
            sendAjaxGetRequest('newupdate_index', $('#latest_content0').val(),'top_contents_aera6_div'); //編集後記新着情報
            $('.waku-label-start').animate({'opacity':'1'},2000);
            //レスポンシブ対応によるアニメーション
            $('.horizontal-write-center').animate({'max-width':'100%'},1000,function(){
                $('.vertical-write-r').animate({'opacity':'1'},2000);
                //アニメーション完了後にads
		        (adsbygoogle = window.adsbygoogle || []).push({});
            });
        }
    },
    {
        namespace: 'products',
        leave() {
            //離脱時にイベント開放
            $(document).off('#next_branch');
        },
        beforeEnter() {
            //表示情報取得
            let send_url = $('#detaillist').val() + '?c=' + $('#cn').val();
            sendAjaxGetRequest('productsdetail', send_url);
        
            if ($('#reviewlist').val() !== "") {
                //製品レビューリスト取得
                send_url = $('#reviewlist').val() + '?n=' + $('#pn').val() + '&c=' + $('#cn').val() + '&start=' + $('#select_review_counter').val();
                sendAjaxGetRequest('productsreview', send_url);
        
                //Key Event
                $(document).on('click','#next_branch', function(){
                    //次のレビュー一覧を表示
                    //支店取得
                    send_url = $('#reviewlist').val() + '?n=' + $('#pn').val() + '&c=' + $('#cn').val() + '&start=' + $('#select_review_counter').val();
                    sendAjaxGetRequest('productsreview', send_url);
                });
            }

            //ページビューランキング取得
            sendAjaxGetRequest('getpv', $('#getpv').val());
        }
    },
    {
        namespace: 'company-shop',
        beforeEnter() {
            //表示情報取得
            sendAjaxGetRequest('branch_list', $('#comp_branch').val()); //支店リスト取得
            sendAjaxGetRequest('branch_news', $('#branch_news').val()); //ニュースリスト取得
        }
    },
    {
        namespace: 'contact',
        beforeEnter() {
            //表示情報取得
	        sendAjaxGetRequest('captcha', $('#get_captcha').val()); //Captcha画像取得
        }
    }]
});

//ヘッダ部書換
const replaceHead = function(data){
    const head = document.head;
    const newPageRawHead = data.next.html.match(/<head[^>]*>([\s\S.]*)<\/head>/i)[0];
    const newPageHead = document.createElement('head');
    newPageHead.innerHTML = newPageRawHead;

    const removeHeadTags = [ 
        "meta[name='title']"
        ,"meta[name='description']"
        ,"meta[name='robots']"
        ,"meta[name='keywords']"
        ,"meta[property^='og']"
        ,"meta[name^='twitter']"
        ,"meta[itemprop]"
        ,"link[itemprop]"
        ,"link[rel='canonical']"
        ,"link[rel='image_src']"
    ].join(',');

    const headTags = head.querySelectorAll(removeHeadTags)
    for(let i = 0; i < headTags.length; i++ ){
        head.removeChild(headTags[i]);
    }

    const newHeadTags = newPageHead.querySelectorAll(removeHeadTags)
    for(let i = 0; i < newHeadTags.length; i++ ){
        head.appendChild(newHeadTags[i]);
    }
}

//次ページへの遷移直前
barba.hooks.beforeEnter((data) => {
    //共通部表示処理
    //メニューバーがオープンしていたら閉じる
    if ($('#revoMenu').hasClass('show')){
        $('.navbar-toggler').click();
    }
    
    //スクロール位置リセット
    window.scrollTo(0,0);
    //バナーリスト取得
    sendAjaxGetRequest('ads', $('#getsupporter').val());
    //特定ページ表示処理
    if ((data.next.namespace !== 'home') && (data.next.namespace !== 'products-category') && (data.next.namespace !== 'products') && (data.next.namespace !== 'company-shop')) {
        //全新着情報
        sendAjaxGetRequest('newupdate', $('#latest_all').val());
        //ページビューランキング取得
        sendAjaxGetRequest('getpv', $('#getpv').val());
    }
})
/* --- barba Seq & Event --- end --- */

/* --- common js --- start --- */
// SNSアイコン表示
function getSNSmark(type){
    switch(type){
        case 0:
            return '[T]';
        case 1:
            return '[F]';
        case 2:
            return '[I]';
        case 3:
            return '[Y]';
        case 4:
            return '[L]';
        default:
            return "";
    }
}
// ポリシー同意イベント
function chkPolisy(){
    const c_data = document.cookie;
    const c_arr = c_data.split(';');

    let chk_val = '';
    for(let c_val of c_arr){
        let cval = c_val.split('=');
        if( cval[0].indexOf('revoltis-policy') !== -1 ){ //キーが一致
            chk_val = decodeURIComponent(cval[1]);
            break;
        }
    }

    if( chk_val.indexOf('policy-ok') === -1 ){ //値が一致
        $('.alert-infomation').removeClass('d-none');
        $('.alert-infomation').addClass('d-block');

        //ポリシー同意ボタンイベント
        $(document).on('click','.info-accept', function(){
            document.cookie = 'revoltis-policy=' + encodeURIComponent('policy-ok') + '; max-age=31536000';
            const down_param = window.innerHeight + (window.innerHeight / 2);
            $('.alert-infomation').animate({'top':down_param},1000,function(){
                $('.alert-infomation').removeClass('d-block');
                $('.alert-infomation').addClass('d-none');
            });
        });
    }else{
        $(document).off('.info-accept');
    }
}

// 日付時間フォーマット変換
function convertDateFormat(obj, mode){
    const date_obj = new Date(obj);
    const year = date_obj.getFullYear();
    let month = date_obj.getMonth() + 1;
    if (String(month).length === 1) {
        month = '0' + month;
    }
    let tdate = date_obj.getDate();
    if (String(tdate).length === 1) {
        tdate = '0' + tdate;
    }
    let hour = date_obj.getHours();
    if (String(hour).length === 1) {
        hour = '0' + hour;
    }
    let min = date_obj.getMinutes();
    if (String(min).length === 1) {
        min = '0' + min;
    }
    let sec = date_obj.getSeconds();

    switch(mode){
        case 'j':
            return year + '年' + month + '月' + tdate + '日';
        case 'al':
            return year + '/' + month + '/' + tdate + ' ' + hour + ':' + min + ':' + sec;
        default:
            return year + '/' + month + '/' + tdate;
    }
}

// 非同期通信（get）
function sendAjaxGetRequest(res_mode, send_url ,opt){
    //送信リクエスト生成
    $.ajax({
        type: "get",
        url: send_url,
        dataType: 'json'
    })
    //通信が成功したとき
    .done((res)=>{
        if (res.length > 0){
            switch (res_mode) {
                case 'ads':
                    // サポーター一覧取得
                    getSupporterList(res);
                    break;
                case  'addpv':
                    // PV加算
                    addPageView(res);
                    break;
                case 'getpv':
                    // PV取得
                    getPageView(res);
                    break;
                case 'newupdate_index':
                    // 新着更新情報（index）
                    getNewUpdateIndex(res, opt);
                    break;
                case 'newupdate':
                    // 新着更新情報
                    getNewUpdateList(res);
                    break;
                case 'branch_list':
                    //支店
                    branchHTML(res);
                    break;
                case 'branch_news':
                    //支店ニュース
                    branchNewsHTML(res);
                    break;
                case 'productsdetail':
                    //製品詳細
                    productsDetailHTML(res);
                    break;
                case 'productsreview':
                    //製品レビュー
                    productsReviewHTML(res);
                    break;
                case 'captcha':
                    //captcha画像
                    getCaptcha(res);
                    break;
            }
        }
    })
    //通信が失敗したとき
    .fail((error)=>{
        alert($('#js_message').val());
        console.log(error.statusText)
        return true;
    })
}

// 企業サポーターリスト取得
function getSupporterList(res){
    $('.ads-area').empty();
    res.forEach( function(item) {
        //タグ生成
        //テキストブロック作成
        const a_element = $('<a class="p-0"></a>');
        a_element.attr('href', item.url);
        a_element.append(createJpegImage(item, '100%'));  //Jpg画像生成後に設定
        $('.ads-area').append(a_element);
    });
}

// ページビュー加算
function addPageView(res){
    if (!res) {
        console.log('Error Add Page View');
    }
}

// ページビュー取得
function getPageView(res){
    res.forEach( function(item) {
        //aタグ生成
        const a_element = $('<a class="list-group-item list-group-item-action border-0 w-100"></a>');
        a_element.attr('href', item.pageview_url);

        //div（画像側）タグ生成
//        const div_image_element = $('<div class="col-xl-6"></div>');
//        div_image_element.append(createImageBlock(item));  //画像ブロック生成後に設定

        //div（情報側）タグ生成
        const h4_element = $('<h4></h4>');
        h4_element.append(item.title);
        const article_element = $('<article></article>');
        article_element.append(h4_element);
        const div_info_element = $('<div class="col-xl-12"></div>');
//        const div_info_element = $('<div class="col-xl-6"></div>');
        div_info_element.append(article_element);

        //divタグ生成
        const div_area_element = $('<div class="row m-0 p-0"></div>');
//        div_area_element.append(div_image_element);
        div_area_element.append(div_info_element);

        a_element.append(div_area_element);
        $('#side-ranking-list').append(a_element);
    });
}

// 新着情報リスト取得
function getNewUpdateList(res){
    res.forEach( function(item) {
        //aタグ生成
        const a_element = $('<a class="list-group-item list-group-item-action border-0 w-100"></a>');
        a_element.attr('href', item.url);

        //div（画像側）タグ生成
        const div_image_element = $('<div class="col-xl-6"></div>');
        div_image_element.append(createImageBlock(item));  //画像ブロック生成後に設定

        //div（情報側）タグ生成
        const h4_element = $('<h4></h4>');
        h4_element.append(item.title);
        const p_element = $('<p></p>');
        p_element.append(item.title_text);
        const article_element = $('<article></article>');
        article_element.append(h4_element);
        article_element.append(p_element);
        const div_info_element = $('<div class="col-xl-6"></div>');
        div_info_element.append(article_element);

        //divタグ生成
        const div_area_element = $('<div class="row m-0 p-0"></div>');
        div_area_element.append(div_image_element);
        div_area_element.append(div_info_element);

        a_element.append(div_area_element);
        $('#side-newupdate-list').append(a_element);
    });
}

// 画像ブロック生成
function createImageBlock(item){

    //imgタグ生成
    const picture_tag = $('<picture></picture>');
    const img_tag = $('<img loading="lazy" class="w-100"></img>');
    img_tag.attr('src', item.img_file);
    img_tag.attr('alt', item.image_alt);
    img_tag.attr('srcset', item.img_srcset_full + ', ' + item.img_srcset_large + ', ' + item.img_srcset_middle + ', ' + item.img_srcset_small + ', ' + item.img_srcset_mini);
    picture_tag.append(img_tag);

    //sourceタグ生成
    const source_tag = $('<source></source>');
    source_tag.attr('type', 'image/' + item.source_srcset_type);
    source_tag.attr('srcset', item.source_srcset_full + ', ' + item.source_srcset_large + ', ' + item.source_srcset_middle + ', ' + item.source_srcset_small + ', ' + item.source_srcset_mini);
    picture_tag.append(source_tag);

    //画像ブロック構築
    const figure_tag = $("<figure></figure>");
    figure_tag.append(picture_tag);

    return figure_tag;
}

// Jpg画像生成
function createJpegImage(item, height){

    //imgタグ生成
    const img_tag = $('<img loading="lazy"></img>');
    img_tag.attr('src', item.img_file);
    img_tag.attr('alt', item.image_alt)
    img_tag.attr('height',height);

    return img_tag;
}

// Captcha画像設定
function getCaptcha(res){
    const img_element = $('<img loading="lazy"></img>');
    img_element.attr('src', res);
    img_element.attr('alt', 'Captcha画像');
    $('.captcha_area').append(img_element);
}

/* --- common js --- end --- */
/* --- index js --- start --- */
// 新着情報リスト取得（index用）
function getNewUpdateIndex(res, tag_name){
    res.forEach( function(item) {
        //divタグ生成
        const div_element = $('<div class="p-2 wc_area"></div>');

        //aタグ生成
        const a_element = $('<a class="waku waku-content"></a>');
        a_element.css('background-image','url(' + item.img_file + ')');
        a_element.attr('href', item.url);

        //spanタグ生成
        const span_element = $('<span class="mt-2 p-1 waku-content-txt"></span>');
        if (item.title_text === '') {
            span_element.append(item.title);
        } else {
            span_element.append(item.title_text);
        }

        //生成タグ挿入
        a_element.append(span_element);
        div_element.append(a_element);
        const insert_element_name = '#' + tag_name;
        $(insert_element_name).append(div_element);
        //追加要素を透過から表示へ
        div_element.animate({opacity:1},1000);
    });
}
/* --- index js --- end --- */
/* --- company-shop js --- start --- */
// 支店HTML生成
function branchHTML(res) {
    //挿入タグ生成
    res.forEach( function(item) {
        //支店設定
        const div_row_element = $('<div class="row mt-3 mb-5"></div>');
        const h4_col3_element = $('<h4 class="col-xl-3"></h4>');
        const div_col9_element = $('<div class="col-xl-9 text-left"></div>');

        let address_txt = '';
        if (item.comp_branch_zip_code.length > 0) {
            address_txt = address_txt + '〒' + item.comp_branch_zip_code + '&nbsp;';
        }
        if (item.cb_address.length > 0){
            address_txt = address_txt  + item.cb_address;
        }
        const p_address_element = $('<p></p>');
        p_address_element.append(address_txt);
        div_col9_element.append(p_address_element);

        //部署設定
        const ditem = JSON.parse(item.cd_data);
        if (ditem !== null){
            const ul_element = $('<ul class="list-group"></ul>');
            ditem.forEach( function(sub_item) {
                const li_element = $('<li class="list-group-item"></li>');
                
                //部署名
                li_element.append('［' + sub_item.comp_department_name + '］');
                li_element.append('<br />');
                //電話番号
                const a_tel_element = $('<a></a>');
                a_tel_element.attr('href','tel:' + sub_item.comp_department_tel);
                a_tel_element.append('Tel:' + sub_item.comp_department_tel);
                li_element.append(a_tel_element);

                //Fax番号
                if (sub_item.comp_department_fax !== ''){
                    li_element.append('<br />');
                    const a_fax_element = $('<a></a>');
                    a_fax_element.attr('href','tel:' + sub_item.comp_department_fax);
                    a_fax_element.append(' Fax:' + sub_item.comp_department_fax);
                    li_element.append(a_fax_element);
                }
                
                //SNS
                if (sub_item.sns_no1 !== ''){
                    const a_sns_element = $('<a></a>');
                    a_sns_element.attr('href', sub_item.sns_url1);
                    a_sns_element.append(getSNSmark(sub_item.sns_no1));
                    li_element.append(a_sns_element);
                }
                if (sub_item.sns_no2 !== ''){
                    const a_sns_element = $('<a></a>');
                    a_sns_element.attr('href', sub_item.sns_url2);
                    a_sns_element.append(getSNSmark(sub_item.sns_no2));
                    li_element.append(a_sns_element);
                }
                if (sub_item.sns_no3 !== ''){
                    const a_sns_element = $('<a></a>');
                    a_sns_element.attr('href', sub_item.sns_url3);
                    a_sns_element.append(getSNSmark(sub_item.sns_no3));
                    li_element.append(a_sns_element);
                }
                if (sub_item.sns_no4 !== ''){
                    const a_sns_element = $('<a></a>');
                    a_sns_element.attr('href', sub_item.sns_url4);
                    a_sns_element.append(getSNSmark(sub_item.sns_no4));
                    li_element.append(a_sns_element);
                }
                if (sub_item.sns_no5 !== ''){
                    const a_sns_element = $('<a></a>');
                    a_sns_element.attr('href', sub_item.sns_url5);
                    a_sns_element.append(getSNSmark(sub_item.sns_no5));
                    li_element.append(a_sns_element);
                }

                ul_element.append(li_element);
            });
            div_col9_element.append(ul_element);
        };

        h4_col3_element.append(item.comp_branch_name);
        div_row_element.append(h4_col3_element);
        div_row_element.append(div_col9_element);
        $('.company_branch_area').append(div_row_element);
        $('.company_branch_area').append('<hr />');
    });
}

// 支店ニュースHTML生成
function branchNewsHTML(res) {
    //挿入タグ生成
    const div_element = $('<div class="pb-3 list-group"></div>');
    let new_count = 0;
    res.forEach( function(item) {
        const a_element = $('<a class="list-group-item list-group-item-action border-0"></a>');
        a_element.attr('href',$('#news_detail').val() + '?b=' + item.comp_branch_no + '&bn=' + item.comp_branch_news_no);
        a_element.append(item.comp_branch_news_title + '[' + convertDateFormat(item.created_at,'j') + ']');
        div_element.append(a_element);
        new_count = new_count + 1;
    });

    //1件以上あれば全て見るをアクティブ
    if (new_count > 0){
        $('.sub_menu').removeClass('d-none');
    }

    $('.company_news_area').append(div_element);
}
/* --- company-shop js --- end --- */
/* --- products js --- start --- */
// 製品詳細HTMLメニュー生成
function productsDetailHTML(res){
    let new_count = 0;
    res.forEach( function(item) {
        if (item.products_detail_no == $('#pdn').val()){
            //現在開いている詳細と同一ならスキップ
            return;
        }

        //現在のURL取得
        const now_url = location.href;
        const url_fullpath_array = now_url.split("?");

        //aタグ生成
        const a_element = $('<a class="list-group-item list-group-item-action border-0 w-100"></a>');
        let aurl = url_fullpath_array[0] + '?n=' + $('#pn').val() + '&d=' + item.products_detail_no;
        if ($('#cn').val() !== '') {
            aurl = aurl + '&c=' + item.comp_no;
        }
        a_element.attr('href', aurl);

        //div（画像側）タグ生成
        const div_image_element = $('<div class="col-xl-6"></div>');
        div_image_element.append(createImageBlock(item));  //画像ブロック生成後に設定

        //div（情報側）タグ生成
        const h4_element = $('<h4></h4>');
        h4_element.append(item.products_detail_title);
        const p_element = $('<p></p>');
        p_element.append(item.products_detail_description);
        const article_element = $('<article></article>');
        article_element.append(h4_element);
        article_element.append(p_element);
        const div_info_element = $('<div class="col-xl-6"></div>');
        div_info_element.append(article_element);

        //divタグ生成
        const div_area_element = $('<div class="row m-0 p-0"></div>');
        div_area_element.append(div_image_element);
        div_area_element.append(div_info_element);

        a_element.append(div_area_element);
        $('#side-detail-list').prepend(a_element);
        new_count = new_count + 1;
    });

    //1件以上あれば全て見るをアクティブ
    if (new_count > 0){
        $('.sub_menu').removeClass('d-none');
    }
}

// 製品レビューHTML生成
function productsReviewHTML(res){
    let data_count = 0;
    res.forEach( function(item) {

        //h3タグ生成
        const h3_tag = $("<h3></h3>");
        h3_tag.html(item.products_review_title);

        //smallタグ生成
        const time1_tag = $('<time itemprop="datePublished"></time>');
        time1_tag.attr('datetime', convertDateFormat(item.created_at, 'al'));
        time1_tag.append(convertDateFormat(item.created_at, 'j'));

        const time2_tag = $('<time itemprop="dateModified"></time>');
        time2_tag.attr('datetime', convertDateFormat(item.updated_at, 'al'));
        time2_tag.append(convertDateFormat(item.updated_at, 'j'));

        const small_tag = $('<small class="text-muted"></small>');
        small_tag.append("公開日：");
        small_tag.append(time1_tag);
        small_tag.append(" / 更新日：");
        small_tag.append(time2_tag);

        //divタグ生成
        const div_tag = $('<div class="contents_text"></div>');
        div_tag.append(item.products_review_text);

        //タグ挿入
        $(".review_area").append(h3_tag);
        $(".review_area").append(small_tag);
        $(".review_area").append(div_tag);
        $(".review_area").append('<hr />');
        
        // カウント
        let start_cnt = Number($('#select_review_counter').val());
        start_cnt = start_cnt + 1;
        $('#select_review_counter').val(start_cnt);

        //表示件数
        data_count = data_count + 1;
    });
                    
    // ボタン表示有無判定
    if (data_count >= 5){
        $('#next_review').prop("disabled", false);
    } else {
        $('#next_review').prop("disabled", true);
    }
}
/* --- products js --- end --- */

