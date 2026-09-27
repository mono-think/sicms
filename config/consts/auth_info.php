<?php
/*
 * アクセス権限の判定配列
 * [判定パラメータ]
 * ①企業種別・・・0,初期アカウント 1,運営 2,お客様
 * ②権限種別・・・0,閲覧のみ 1,アドミニストレータ 2,支店管理者 3,部署管理者
 *
 * [配列構成]
 * ページ単位で許可するパターンを設定する
 * login_auth => [
 * 'カレントページ名' => [
 * '企業種別' => [
 * '権限種別',
 * ]
 * ],
 *
 */
return [
    'login_auth' => [
        // 企業
        'company.index' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'company.create' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'company.confirm' => [
            0 => [
                1
            ],
            1 => [
                1
            ],
            2 => [
                1
            ]
        ],
        'company.store' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'company.show' => [
            0 => [
                1
            ],
            1 => [
                0,
                1,
                2,
                3
            ],
            2 => [
                0,
                1,
                2,
                3
            ]
        ],
        'company.edit' => [
            0 => [
                1
            ],
            1 => [
                1
            ],
            2 => [
                1
            ]
        ],
        'company.update' => [
            0 => [
                1
            ],
            1 => [
                1
            ],
            2 => [
                1
            ]
        ],
        'company.destroy' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        // 企業支店
        'company_branch.index' => [
            1 => [
                0,
                1,
                2,
                3
            ],
            2 => [
                0,
                1,
                2,
                3
            ]
        ],
        'company_branch.create' => [
            1 => [
                1
            ],
            2 => [
                1
            ]
        ],
        'company_branch.getlist' => [
            0 => [
                1
            ],
            1 => [
                1,
                2,
                3
            ],
            2 => [
                1,
                2,
                3
            ]
        ],
        'company_branch.confirm' => [
            1 => [
                1,
                2
            ],
            2 => [
                1,
                2
            ]
        ],
        'company_branch.store' => [
            1 => [
                1
            ],
            2 => [
                1
            ]
        ],
        'company_branch.show' => [
            1 => [
                0,
                1,
                2,
                3
            ],
            2 => [
                0,
                1,
                2,
                3
            ]
        ],
        'company_branch.edit' => [
            1 => [
                1,
                2
            ],
            2 => [
                1,
                2
            ]
        ],
        'company_branch.update' => [
            1 => [
                1,
                2
            ],
            2 => [
                1,
                2
            ]
        ],
        'company_branch.destroy' => [
            1 => [
                1
            ],
            2 => [
                1
            ]
        ],
        // 企業ニュース
        'company_branch_news.index' => [
            1 => [
                0,
                1,
                2,
                3
            ],
            2 => [
                0,
                1,
                2,
                3
            ]
        ],
        'company_branch_news.create' => [
            1 => [
                1,
                2
            ],
            2 => [
                1,
                2
            ]
        ],
        'company_branch_news.confirm' => [
            1 => [
                1,
                2
            ],
            2 => [
                1,
                2
            ]
        ],
        'company_branch_news.store' => [
            1 => [
                1,
                2
            ],
            2 => [
                1,
                2
            ]
        ],
        'company_branch_news.show' => [
            1 => [
                0,
                1,
                2,
                3
            ],
            2 => [
                0,
                1,
                2,
                3
            ]
        ],
        'company_branch_news.edit' => [
            1 => [
                1,
                2
            ],
            2 => [
                1,
                2
            ]
        ],
        'company_branch_news.update' => [
            1 => [
                1,
                2
            ],
            2 => [
                1,
                2
            ]
        ],
        'company_branch_news.destroy' => [
            1 => [
                1,
                2
            ],
            2 => [
                1,
                2
            ]
        ],
        // 企業部署
        'company_department.index' => [
            1 => [
                0,
                1,
                2,
                3
            ],
            2 => [
                0,
                1,
                2,
                3
            ]
        ],
        'company_department.create' => [
            1 => [
                1,
                2
            ],
            2 => [
                1,
                2
            ]
        ],
        'company_department.getlist' => [
            0 => [
                1
            ],
            1 => [
                1,
                2,
                3
            ],
            2 => [
                1,
                2,
                3
            ]
        ],
        'company_department.confirm' => [
            1 => [
                1,
                2,
                3
            ],
            2 => [
                1,
                2,
                3
            ]
        ],
        'company_department.store' => [
            1 => [
                1,
                2
            ],
            2 => [
                1,
                2
            ]
        ],
        'company_department.show' => [
            1 => [
                0,
                1,
                2,
                3
            ],
            2 => [
                0,
                1,
                2,
                3
            ]
        ],
        'company_department.edit' => [
            1 => [
                1,
                2,
                3
            ],
            2 => [
                1,
                2,
                3
            ]
        ],
        'company_department.update' => [
            1 => [
                1,
                2,
                3
            ],
            2 => [
                1,
                2,
                3
            ]
        ],
        'company_department.destroy' => [
            1 => [
                1,
                2
            ],
            2 => [
                1,
                2
            ]
        ],
        // アカウント
        'acnt.index' => [
            0 => [
                1
            ],
            1 => [
                0,
                1,
                2,
                3
            ],
            2 => [
                0,
                1,
                2,
                3
            ]
        ],
        'acnt.create' => [
            0 => [
                1
            ],
            1 => [
                1
            ],
            2 => [
                1
            ]
        ],
        'acnt.getlist' => [
            0 => [
                1
            ],
            1 => [
                1,
                2,
                3
            ],
            2 => [
                1,
                2,
                3
            ]
        ],
        'acnt.confirm' => [
            0 => [
                1
            ],
            1 => [
                1
            ],
            2 => [
                1
            ]
        ],
        'acnt.store' => [
            0 => [
                1
            ],
            1 => [
                1
            ],
            2 => [
                1
            ]
        ],
        'acnt.show' => [
            0 => [
                1
            ],
            1 => [
                0,
                1,
                2,
                3
            ],
            2 => [
                0,
                1,
                2,
                3
            ]
        ],
        'acnt.edit' => [
            0 => [
                1
            ],
            1 => [
                1
            ],
            2 => [
                1
            ]
        ],
        'acnt.update' => [
            0 => [
                1
            ],
            1 => [
                1
            ],
            2 => [
                1
            ]
        ],
        'acnt.destroy' => [
            0 => [
                1
            ],
            1 => [
                1
            ],
            2 => [
                1
            ]
        ],
        // 画像
        'image.index' => [
            0 => [
                1
            ],
            1 => [
                0,
                1,
                2,
                3
            ],
            2 => [
                0,
                1,
                2,
                3
            ]
        ],
        'image.store' => [
            0 => [
                1
            ],
            1 => [
                1,
                2,
                3
            ],
            2 => [
                1,
                2,
                3
            ]
        ],
        'image.update' => [
            0 => [
                1
            ],
            1 => [
                1,
                2,
                3
            ],
            2 => [
                1,
                2,
                3
            ]
        ],
        'image.destroy' => [
            0 => [
                1
            ],
            1 => [
                1,
                2,
                3
            ],
            2 => [
                1,
                2,
                3
            ]
        ],
        'image_select.getlist' => [
            0 => [
                1
            ],
            1 => [
                1,
                2,
                3
            ],
            2 => [
                1,
                2,
                3
            ]
        ],
        // メッセージボード管理
        'message_bord.index' => [
            1 => [
                0,
                1,
                2,
                3
            ],
            2 => [
                0,
                1,
                2,
                3
            ]
        ],
        'message_bord.create' => [
            1 => [
                1,
                2,
                3
            ],
            2 => [
                1,
                2,
                3
            ]
        ],
        'message_bord.confirm' => [
            1 => [
                1,
                2,
                3
            ],
            2 => [
                1,
                2,
                3
            ]
        ],
        'message_bord.store' => [
            1 => [
                1,
                2,
                3
            ],
            2 => [
                1,
                2,
                3
            ]
        ],
        'message_bord.edit' => [
            1 => [
                1,
                2,
                3
            ],
            2 => [
                1,
                2,
                3
            ]
        ],
        'message_bord.update' => [
            1 => [
                1,
                2,
                3
            ],
            2 => [
                1,
                2,
                3
            ]
        ],
        'message_bord.destroy' => [
            1 => [
                1
            ],
            2 => [
                1
            ]
        ],
        // マスタ（業種）
        'industry.index' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'industry.create' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'industry.show' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'industry.confirm' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'industry.store' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'industry.edit' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'industry.update' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'industry.destroy' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        // マスタ（業態）
        'bformat.index' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'bformat.create' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'bformat.show' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'bformat.confirm' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'bformat.store' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'bformat.edit' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'bformat.update' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'bformat.destroy' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        // マスタ（役職）
        'position.index' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'position.create' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'position.show' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'position.confirm' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'position.store' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'position.edit' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'position.update' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'position.destroy' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        // マスタ（都道府県）
        'pref.index' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'pref.create' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'pref.show' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'pref.confirm' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'pref.store' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'pref.edit' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'pref.update' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'pref.destroy' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        // 製品カテゴリ（マスタ）
        'products_category.index' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'products_category.create' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'products_category.show' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'products_category.confirm' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'products_category.store' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'products_category.edit' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'products_category.update' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        'products_category.destroy' => [
            0 => [
                1
            ],
            1 => [
                1
            ]
        ],
        // イベント
        'event.index' => [
            1 => [
                0,
                1,
                2,
                3
            ]
        ],
        'event.create' => [
            1 => [
                1,
                2,
                3
            ]
        ],
        'event.confirm' => [
            1 => [
                1,
                2,
                3
            ]
        ],
        'event.store' => [
            1 => [
                1,
                2,
                3
            ]
        ],
        'event.show' => [
            1 => [
                0,
                1,
                2,
                3
            ]
        ],
        'event.edit' => [
            1 => [
                1,
                2,
                3
            ]
        ],
        'event.update' => [
            1 => [
                1,
                2,
                3
            ]
        ],
        'event.destroy' => [
            1 => [
                1,
                2,
                3
            ]
        ],
        // コンテンツ
        'content.index' => [
            1 => [
                0,
                1,
                2,
                3
            ]
        ],
        'content.create' => [
            1 => [
                1,
                2,
                3
            ]
        ],
        'content.confirm' => [
            1 => [
                1,
                2,
                3
            ]
        ],
        'content.store' => [
            1 => [
                1,
                2,
                3
            ]
        ],
        'content.show' => [
            1 => [
                0,
                1,
                2,
                3
            ]
        ],
        'content.edit' => [
            1 => [
                1,
                2,
                3
            ]
        ],
        'content.update' => [
            1 => [
                1,
                2,
                3
            ]
        ],
        'content.destroy' => [
            1 => [
                1,
                2,
                3
            ]
        ],
        // 製品
        'products.index' => [
            1 => [
                0,
                1,
                2,
                3
            ],
            2 => [
                0,
                1,
                2,
                3
            ]
        ],
        'products.create' => [
            1 => [
                1
            ],
            2 => [
                1
            ]
        ],
        'products.confirm' => [
            1 => [
                1
            ],
            2 => [
                1
            ]
        ],
        'products.store' => [
            1 => [
                1
            ],
            2 => [
                1
            ]
        ],
        'products.show' => [
            1 => [
                0,
                1,
                2,
                3
            ],
            2 => [
                0,
                1,
                2,
                3
            ]
        ],
        'products.edit' => [
            1 => [
                1
            ],
            2 => [
                1
            ]
        ],
        'products.update' => [
            1 => [
                1
            ],
            2 => [
                1
            ]
        ],
        'products.destroy' => [
            1 => [
                1
            ],
            2 => [
                1
            ]
        ],
        // 製品詳細
        'products_detail.index' => [
            1 => [
                0,
                1,
                2,
                3
            ],
            2 => [
                0,
                1,
                2,
                3
            ]
        ],
        'products_detail.create' => [
            1 => [
                1,
                2
            ],
            2 => [
                1,
                2
            ]
        ],
        'products_detail.confirm' => [
            1 => [
                1,
                2
            ],
            2 => [
                1,
                2
            ]
        ],
        'products_detail.store' => [
            1 => [
                1,
                2
            ],
            2 => [
                1,
                2
            ]
        ],
        'products_detail.show' => [
            1 => [
                0,
                1,
                2,
                3
            ],
            2 => [
                0,
                1,
                2,
                3
            ]
        ],
        'products_detail.edit' => [
            1 => [
                1,
                2
            ],
            2 => [
                1,
                2
            ]
        ],
        'products_detail.update' => [
            1 => [
                1,
                2
            ],
            2 => [
                1,
                2
            ]
        ],
        'products_detail.destroy' => [
            1 => [
                1,
                2
            ],
            2 => [
                1,
                2
            ]
        ],
        // 製品レビュー
        'products_review.index' => [
            1 => [
                0,
                1,
                2,
                3
            ],
            2 => [
                0,
                1,
                2,
                3
            ]
        ],
        'products_review.create' => [
            1 => [
                1,
                2,
                3
            ],
            2 => [
                1,
                2,
                3
            ]
        ],
        'products_review.confirm' => [
            1 => [
                1,
                2,
                3
            ],
            2 => [
                1,
                2,
                3
            ]
        ],
        'products_review.store' => [
            1 => [
                1,
                2,
                3
            ],
            2 => [
                1,
                2,
                3
            ]
        ],
        'products_review.show' => [
            1 => [
                0,
                1,
                2,
                3
            ],
            2 => [
                0,
                1,
                2,
                3
            ]
        ],
        'products_review.edit' => [
            1 => [
                1,
                2,
                3
            ],
            2 => [
                1,
                2,
                3
            ]
        ],
        'products_review.update' => [
            1 => [
                1,
                2,
                3
            ],
            2 => [
                1,
                2,
                3
            ]
        ],
        'products_review.destroy' => [
            1 => [
                1,
                2,
                3
            ],
            2 => [
                1,
                2,
                3
            ]
        ]
    ],
    /*
     * 企業種別
     */
    'company_type' => [
        0 => '初期アカウント',
        1 => '運営者',
        2 => 'お客様'
    ],
    /*
     * 企業種別コード
     */
    'company_type_default' => 0,
    'company_type_admin' => 1,
    'company_type_customer' => 2,
    /*
     * 権限カテゴリ
     */
    'auth_category' => [
        0 => '閲覧のみ',
        1 => 'アドミニストレーター',
        2 => '支店管理者',
        3 => '部署管理者'
    ],
    /*
     * 閲覧権限
     */
    'auth_category_view' => 0,
    'auth_category_admin' => 1,
    'auth_category_branch' => 2,
    'auth_category_department' => 3
];
