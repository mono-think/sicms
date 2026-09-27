<?php
/**
 * [DB]comp_tRepositoryInterfaceクラス
 *
 * comp_tテーブルをメインとする検索、登録、更新、削除処理のインタフェースを定義
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories\Manage;

interface CompanyRepositoryInterface
{

    /**
     * 企業一覧情報ページネーション取得インタフェース
     *
     * @access public
     * @param string $free_word
     * @param int $company_type
     * @param int $limit
     */
    public function getCompanyPagenationList($free_word, $company_type, $limit = null);

    /**
     * 企業情報取得インタフェース
     *
     * @access public
     * @param string $company_no
     * @param int $delete_flg
     */
    public function getCompanyData($company_no, $delete_flg);

    /**
     * 企業テーブルへの追加インタフェース
     *
     * @access public
     * @param array $insert_data;
     */
    public function insertCompanyData($insert_data);

    /**
     * 企業テーブルへの更新インタフェース
     *
     * @access public
     * @param array $update_data
     * @param string $company_no
     */
    public function updateCompanyData($update_data, $company_no);

    /**
     * 企業テーブルへの削除インタフェース
     *
     * @access public
     * @param array $delete_data
     * @param string $acnt_no
     */
    public function deleteCompanyData($delete_data, $acnt_no);
}