<?php
/**
 * [DB]comp_tテーブルのRepositoryInterfaceクラス
 *
 * comp_tテーブルをメインとする検索、登録、更新、削除処理のインタフェースを定義
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories\Manage;

interface AcntRepositoryInterface
{
    /**
     * アカウントテーブルの一覧情報ページネーション取得インタフェース
     *
     * @access public
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $company_department_no
     * @param string $free_word
     * @param int $limit
     */
    public function getAcntPagenationList($company_no, $company_branch_no, $company_department_no, $free_word, $limit = null);

    
    /**
     * アカウントテーブルの一覧情報取得インタフェース
     *
     * @access public
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $company_department_no
     * @param string $free_word
     * @param int $start
     * @param int $limit
     */
    public function getAcntList($company_no, $company_branch_no, $company_department_no, $free_word, $start = 0, $limit = null);
    
    /**
     * アカウントテーブルの情報取得インタフェース
     *
     * @access public
     * @param string $company_no
     * @param string $acnt_no
     * @param string $acnt_id
     * @param int $delete_flg
     */
    public function getAcntData($company_no, $acnt_no, $acnt_id, $delete_flg);

    /**
     * アカウントテーブルへの追加インタフェース
     *
     * @access public
     * @param array $insert_data
     * @param array $insert_detail_data
     */
    public function insertAcntData($insert_data, $insert_detail_data);

    /**
     * アカウントテーブルへの更新インタフェース
     *
     * @access public
     * @param array $update_data
     * @param array $update_detail_data
     * @param string $company_no
     * @param string $acnt_no
     * @param string $acnt_id
     */
    public function updateAcntData($update_data, $update_detail_data, $company_no, $acnt_no, $acnt_id);

    /**
     * アカウントテーブルへの削除インタフェース
     *
     * @access public
     * @param array $delete_data
     * @param string $company_no
     * @param string $acnt_no
     */
    public function deleteAcntData($delete_data, $company_no, $acnt_no);
}