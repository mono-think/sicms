<?php
/**
 * 企業支店ニュース情報処理のServiceクラス
 * 検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package service
 */
namespace App\Services\Manage;

use App\Repositories\Manage\CompanyBranchNewsRepositoryInterface;
use Carbon\Carbon;

class CompanyBranchNewsService
{

    protected $company_branch_news_repository;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param CompanyBranchNewsRepositoryInterface $company_repository
     *
     */
    public function __construct(CompanyBranchNewsRepositoryInterface $company_branch_news_repository)
    {
        $this->company_branch_news_repository = $company_branch_news_repository;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * 企業支店ニュース情報一覧ページネーション取得
     * 検索条件により全ての企業情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see CompanyBranchNewsService
     * @throws
     * @todo
     */
    public function getCompanyBranchNewsPagenationList($company_no, $company_branch_no, $free_word)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $free_word = hscQuotes($free_word); //HTMLエンティティ変換
        $ret = $this->company_branch_news_repository->getCompanyBranchNewsPagenationList($company_no, $company_branch_no, $free_word);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 企業支店ニュース情報取得
     * 企業支店管理番号により企業支店ニュース情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $company_branch_no
     * @param int $company_branch_news_no
     * @return array
     * @see CompanyBranchNewsService
     * @throws
     * @todo
     */
    public function getCompanyBranchNewsData($company_no, $company_branch_no, $company_branch_news_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'company_branch_news_no' => $company_branch_news_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret = $this->company_branch_news_repository->getCompanyBranchNewsData($company_no, $company_branch_no, $company_branch_news_no);
        $form_data = [];
        if (! empty($ret)) {
            // 配列化
            $form_data['input_no'] = $ret->comp_branch_news_no; // 企業支店ニュース番号
            $form_data['input_cpb_no'] = $ret->comp_branch_no; // 企業支店番号
            $form_data['input_name'] = $ret->comp_branch_news_title; // ニュースタイトル
            $form_data['input_text'] = convertHtmlText($ret->comp_branch_news_text); // ニューステキスト
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $form_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $form_data;
    }

    /**
     * 企業支店ニュース情報登録
     * 入力した企業支店ニュース情報を登録
     *
     * @access public
     * @param array $form_data
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $acnt_no
     * @return Boolean
     * @see CompanyBranchNewsService
     * @throws
     * @todo
     */
    public function insertCompanyBranchNewsData($form_data, $company_no, $company_branch_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'acnt_no' => $acnt_no
        ]);

        // 新規登録
        // 登録データを設定
        islog($this->class_name, __FUNCTION__, 'm14');
        $insert_data['comp_branch_no'] = $company_branch_no; // 企業支店管理番号
        $insert_data['comp_no'] = $company_no; // 企業管理番号
        $insert_data['comp_branch_news_title'] = $form_data['input_name']; // ニュースタイトル
        $insert_data['comp_branch_news_text'] = convertHtmlText($form_data['input_text']); // ニューステキスト
        $insert_data['created_at'] = Carbon::now(); // 登録日
        $insert_data['updated_at'] = Carbon::now(); // 更新日
        $insert_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm13', $insert_data);

        islog($this->class_name, __FUNCTION__, 'm18');
        $ret = $this->company_branch_news_repository->insertCompanyBranchNewsData($insert_data);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 企業支店ニュース情報更新
     * 入力した企業支店ニュース情報を登録
     *
     * @access public
     * @param array $form_data
     * @param int $input_no
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $acnt_no
     * @return Boolean
     * @see CompanyBranchNewsService
     * @throws
     * @todo
     */
    public function updateCompanyBranchNewsData($form_data, $input_no, $company_no, $company_branch_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'input_no' => $input_no,
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'acnt_no' => $acnt_no
        ]);

        // 既存更新
        // 更新データを設定
        islog($this->class_name, __FUNCTION__, 'm15');
        $update_data['comp_branch_news_title'] = $form_data['input_name']; // ニュースタイトル
        $update_data['comp_branch_news_text'] = convertHtmlText($form_data['input_text']); // ニューステキスト
        $update_data['updated_at'] = Carbon::now(); // 更新日
        $update_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm14', $update_data);

        islog($this->class_name, __FUNCTION__, 'm19');
        $ret = $this->company_branch_news_repository->updateCompanyBranchNewsData($update_data, $company_no, $company_branch_no, $input_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 企業支店ニュース情報削除
     * 選択した企業情報を削除
     *
     * @access public
     * @param string $company_no
     * @param string $company_branch_no
     * @param array $company_branch_news_no
     * @return Boolean
     * @see CompanyBranchNewsService
     * @throws
     * @todo
     */
    public function deleteCompanyBranchNewsData($company_no, $company_branch_no, $company_branch_news_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'company_branch_news_no' => $company_branch_news_no
        ]);

        // 削除開始
        islog($this->class_name, __FUNCTION__, 'm20');
        $ret = $this->company_branch_news_repository->deleteCompanyBranchNewsData($company_branch_news_no, $company_no, $company_branch_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}
