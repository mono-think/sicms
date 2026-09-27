<?php
/**
 * [DB]comp_branch_tテーブルのRepositoryInterfaceクラス
 *
 * comp_branch_tテーブルをメインとする検索、登録、更新、削除処理のインタフェースを定義
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories;

interface CompanyBranchFrontRepositoryInterface
{

    /**
     * 企業支店テーブルの一覧情報取得インタフェース（条件指定）
     *
     * @access public
     * @param string $company_no
     * @param int $start
     * @param int $limit
     */
    public function getCompanyBranchList($company_no, $start = 0, $limit = null);

    /**
     * サポーター支店を持つ企業の一覧情報取得インタフェース
     *
     * @access public
     * @param int $suppoter_flg
     */
    public function getCompanySupporterBranchList($suppoter_flg);
}