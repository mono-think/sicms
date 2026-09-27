<?php
// Top
Breadcrumbs::for('top', function ($trail, $param = null) {
    $trail->push('最初', route('index'));
});

// 最初 > 製品｜事業 > カテゴリ > 製品名 > 製品詳細と製品レビュー
Breadcrumbs::for('products', function ($trail, $param) {
    $trail->parent('top');
    $trail->push('製品｜事業', route('front_products_category.index'));
    if (isset($param['category'])) {
        $trail->push($param['category_name'], route('front_products.index', $param['category']));
        if (isset($param['product']) && isset($param['detail'])) {
            $trail->push($param['detail_name'], route('front_products.show', $param['category']) . '?n=' . $param['product'] . '&d=' . $param['detail']);
        } else if (isset($param['product'])) {
            $trail->push($param['products_name'], route('front_products.show', $param['category']) . '&n=' . $param['product'] . '&d=');
        }
    }
});

// 最初 > 企業｜店
Breadcrumbs::for('company_shop', function ($trail, $param) {
    $trail->parent('top');
    $trail->push('企業｜店', route('company_shop.index'));
    if (isset($param['company_no'])) {
        $trail->push($param['company_name'], route('company_shop.show', $param['company_no']));
    }
});

// 最初 > 企業｜店 > 報道一覧 > 報道
Breadcrumbs::for('company_shop_news', function ($trail, $param) {
    $trail->parent('top');
    $trail->push('企業｜店', route('company_shop.index'));
    if (isset($param['company_no'])) {
        $trail->push($param['company_name'], route('company_shop.show', $param['company_no']));
        $trail->push('報道発表', route('company_shop_news.index', $param['company_no']));
        if (isset($param['company_branch_no']) && isset($param['company_branch_news_no'])) {
            $trail->push($param['company_branch_news_title'], route('company_shop_news.show', $param['company_no']) . '?b=' . $param['company_branch_no'] . '&bn=' . $param['company_branch_news_no']);
        }
    }
});

// 最初 > 企業｜店 > 製品｜事業
Breadcrumbs::for('company_shop_products', function ($trail, $param) {
    $trail->parent('top');
    $trail->push('企業｜店', route('company_shop.index'));
    if (isset($param['company_no'])) {
        $trail->push($param['company_name'], route('company_shop.show', $param['company_no']));
        $trail->push('製品｜事業', route('front_products_category.index') . '?c=' . $param['company_no']);
        if (isset($param['category'])) {
            $trail->push($param['category_name'], route('front_products.index', $param['category']) . '?c=' . $param['company_no']);
            if (isset($param['product']) && isset($param['detail'])) {
                $trail->push($param['detail_name'], route('front_products.show', $param['category']) . '?n=' . $param['product'] . '&d=' . $param['detail'] . '&c=' . $param['company_no']);
            } else if (isset($param['product'])) {
                $trail->push($param['products_name'], route('front_products.show', $param['category']) . '?n=' . $param['product'] . '&d=&c=' . $param['company_no']);
            }
        }
    }
});

// 最初 > 催し報告
Breadcrumbs::for('event', function ($trail, $param) {
    $trail->parent('top');
    $trail->push('催し報告', route('front_event.index'));
    if (isset($param['event'])) {
        $trail->push($param['event_name'], route('front_event.show', $param['event']));
    }
});

// 最初 > コンテンツ
Breadcrumbs::for('content', function ($trail, $param) {
    $trail->parent('top');
    $trail->push($param['contents_category_name'], route('front_content.index', $param['contents_type']));
    if (isset($param['contents'])) {
        $trail->push($param['contents_title'], route('front_content.show', $param['contents_type']) . '?c=' . $param['contents']);
    }
});

// 最初 > 問い合わせ
Breadcrumbs::for('contact', function ($trail, $param) {
    $trail->parent('top');
    $trail->push('問い合わせ', route('front_contact'));
});
