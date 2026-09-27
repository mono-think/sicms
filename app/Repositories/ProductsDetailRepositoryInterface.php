<?php
namespace App\Repositories;

interface ProductsDetailRepositoryInterface
{
    /**
     * 製品詳細情報も含めた最新の製品一覧取得インタフェース
     *
     * @access public
     * @param string $company_no
     * @param string $products_no
     */
    public function getProductsDetailList($company_no, $products_no);
}