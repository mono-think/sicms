<?php
return [
    /*
     * 一時保存フォルダ
     */
    'tmp_save_folder' => 'public/temp_images',
    /*
     * 一時保存公開フォルダ
     */
    'tmp_folder' => 'temp_images',
    /*
     * Captcha画像ファイル名
     */
    'captcha_file' => 'captcha.jpg',
    /*
     * アップロード画像保存フォルダ
     */
    'save_folder' => 'public/upload_images',
    /*
     * 画像なしファイルパス
     */
    'public_noimage_path' => 'images/common_images/no_image',
    'public_noimage_type' => 'jpg',
    /*
     * アップロード画像公開フォルダ
     */
    'public_image_folder' => 'upload_images',
    /*
     * 画像公開フォルダ
     */
    'image_folder' => 'images',
    /*
     * 画像タイプ（通常）
     */
    'image_type' => [
        '0'=>'', //最初は必ず空白のまま
        '1'=>'webp',
        //追加の画像タイプ（アイコン、svg以外）があればここ以降に追加していく
    ],
    /*
     * 最小画像幅(ピクセル)
     */
    'upload_min_width' => '200',
    /*
     * アップロードを許可する画像タイプ
     */
    'upload_auth_type' => [
        'image/jpeg',
        'image/png',
    ],
    /*
     * 各画面の画像指定
     */
    'disp_image' => [
        'full' => '750',
        'large' => '500',
        'middle' => '200',
        'small' => '130',
        'mini' => '100'
    ],
    /*
     * 画像変換パターン
     */
    'convert_pattern' => [
        '0' => [
            'width' => '750',
            'height' => null,
            'compression' => '80',
            'rename_flg' => true,
            'default_name' => null,
        ],
        '1' => [
            'width' => '500',
            'height' => null,
            'compression' => '80',
            'rename_flg' => true,
            'default_name' => null,
        ],
        '2' => [
            'width' => '200',
            'height' => null,
            'compression' => '80',
            'rename_flg' => true,
            'default_name' => null,
        ],
        '3' => [
            'width' => '130',
            'height' => null,
            'compression' => '80',
            'rename_flg' => true,
            'default_name' => null,
        ],
        '4' => [
            'width' => '100',
            'height' => null,
            'compression' => '80',
            'rename_flg' => true,
            'default_name' => null,
        ]
    ],
    /*
     * 画像カテゴリ
     */
    'image_category' => [
        '1' => '製品',
        '2' => '企業',
        '3' => 'イベント',
        '4' => 'コンテンツ',
    ],
    /*
     * 製品の画像カテゴリNo
     */
    'image_products_category' => '1',
    /*
     * 企業の画像カテゴリNo
     */
    'image_company_category' => '2',
    /*
     * イベントの画像カテゴリNo
     */
    'image_event_category' => '3',
    /*
     * コンテンツの画像カテゴリNo
     */
    'image_content_category' => '4',
];
