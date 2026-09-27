<?php
return [

    /*
     * 許可するHTMLタグ
     */
    'auth_tag' => 'div[class],a[href|target],p[style],br,img[class|loading|srcset|alt|src],figure,figcaption,picture,source[type|srcset],iframe[loading|title|src|frameborder|allowfullscreen]', //タグが増えるなら最後尾へ追加していく
    /*
     * 許可するCSSタグ
     */
//    'auth_css' => 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align', //タグが増えるなら最後尾へ追加していく
    'auth_css' => '', //タグが増えるなら最後尾へ追加していく
];
