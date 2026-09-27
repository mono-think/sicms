/*!
  * Sicms JS v1.1.1
  * Copyright 2021-2021 Webじゃ研 Authors
  * Licensed under MIT
  */

/* 初期設定 */
$(document).ready(function(){

    //初期設定
    /* アコーディオンメニューのオープン指定 */
    switch($('#crn').val()){
        case 'products.index':
        case 'products.create':
        case 'products.show':
        case 'products_detail.index':
        case 'products_detail.create':
        case 'products_detail.show':
        case 'message_bord.index':
        case 'message_bord.create':
        case 'message_bord.edit':
        case 'content.index':
        case 'content.create':
        case 'content.show':
        case 'event.index':
        case 'event.create':
        case 'event.show':
            $('#collapseOne1').addClass("show");
            $('#collapseOne2').removeClass("show");
            $('#collapseOne3').removeClass("show");
            break;
        case 'acnt.index':
        case 'acnt.create':
        case 'acnt.show':
        case 'company.index':
        case 'company.create':
        case 'company.show':
        case 'company_branch.index':
        case 'company_branch.create':
        case 'company_branch.show':
        case 'company_branch_news.index':
        case 'company_branch_news.create':
        case 'company_branch_news.show':
        case 'company_department.index':
        case 'company_department.create':
        case 'company_department.show':
        case 'image.index':
        case 'image.create':
        case 'image.show':
            $('#collapseOne1').removeClass("show");
            $('#collapseOne2').addClass("show");
            $('#collapseOne3').removeClass("show");
            break;
        case 'industry.index':
        case 'industry.create':
        case 'industry.show':
        case 'bformat.index':
        case 'bformat.create':
        case 'bformat.show':
        case 'position.index':
        case 'position.create':
        case 'position.show':
        case 'products_category.index':
        case 'products_category.create':
        case 'products_category.show':
        case 'pref.index':
        case 'pref.create':
        case 'pref.show':
            $('#collapseOne1').removeClass("show");
            $('#collapseOne2').removeClass("show");
            $('#collapseOne3').addClass("show");
            break;
        default:
            break;
    }
    
    /* Titleタグテキスト指定 */
    const title_text = $('h2').text();
    $('title').text('SiCMS管理画面 - ' + title_text);
});
