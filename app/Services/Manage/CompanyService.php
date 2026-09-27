<?php
/**
 * 企業情報処理のServiceクラス
 * 検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package service
 */
namespace App\Services\Manage;

use App\Repositories\Manage\CompanyRepositoryInterface;
use Carbon\Carbon;

class CompanyService
{

    protected $company_repository;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param CompanyRepositoryInterface $company_repository
     *
     */
    public function __construct(CompanyRepositoryInterface $company_repository)
    {
        $this->company_repository = $company_repository;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * 企業情報ページネーション一覧取得
     * 検索条件により全ての企業情報を取得
     *
     * @access public
     * @param string $free_word
     * @param int $company_type
     * @return \Illuminate\Database\Eloquent\Collection
     * @see CompanyService
     * @throws
     * @todo
     */
    public function getCompanyPagenationList($free_word, $company_type)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word,
            'company_type' => $company_type
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $free_word = hscQuotes($free_word); //HTMLエンティティ変換
        $ret = $this->company_repository->getCompanyPagenationList($free_word, $company_type);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 企業情報取得
     * 企業番号により企業情報を取得
     *
     * @access public
     * @param string $company_no
     * @param int $delete_flg
     * @return array
     * @see CompanyService
     * @throws
     * @todo
     */
    public function getCompanyData($company_no, $delete_flg = 0)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'delete_flg' => $delete_flg
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret = $this->company_repository->getCompanyData($company_no, $delete_flg);
        $form_data = [];
        if (! empty($ret)) {
            // 配列化
            $form_data['input_no'] = $ret->comp_no; // 企業番号
            $form_data['input_name'] = $ret->comp_name; // 企業名
            $form_data['input_indno'] = $ret->industry_no; // 業種No
            $form_data['input_indname'] = $ret->industry_name; // 業種名
            $form_data['input_bfno'] = $ret->bformat_no; // 業態No
            $form_data['input_bfname'] = $ret->bformat_name; // 業態名
            $form_data['input_text'] = convertHtmlText($ret->comp_business_text); // 事業内容
            $form_data['input_image_no'] = (int)$ret->comp_banner_image_no; // 企業画像番号
            $form_data['input_image_category'] = $ret->image_category; // 製品画像カテゴリ
            $form_data['input_image_name'] = $ret->image_name; // 製品画像名称
            $form_data['input_image_folder'] = $ret->image_folder; // 製品画像フォルダ
            $form_data['input_image_type'] = $ret->image_type; // 製品画像種別
            $form_data['input_image_alt'] = $ret->image_alt; // 製品画像代替テキスト
            $form_data['input_image_text'] = $ret->image_text; // 製品画像キャプション
            $form_data['input_image_file'] = getImagePath($ret->image_folder, $ret->image_name, $ret->image_type); // imgタグのsrcパス
            $form_data['input_title'] = $ret->page_title; // ページタイトル
            $form_data['input_search_tag'] = $ret->search_tag; // タグ
            $form_data['input_url'] = $ret->comp_url; // 企業URL
            $form_data['input_cptype'] = $ret->comp_type; // 企業種別
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $form_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $form_data;
    }

    /**
     * 企業情報登録
     * 入力した企業情報を登録
     *
     * @access public
     * @param array $form_data
     * @param string $acnt_no
     * @return Boolean
     * @see CompanyService
     * @throws
     * @todo
     */
    public function insertCompanyData($form_data, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'acnt_no' => $acnt_no
        ]);

        // 新規登録
        // 登録データを設定
        islog($this->class_name, __FUNCTION__, 'm14');
        $insert_data['comp_no'] = $form_data['input_no']; // 企業番号
        $insert_data['comp_name'] = $form_data['input_name']; // 企業名
        $insert_data['industry_no'] = (int)$form_data['input_indno']; // 業種No
        $insert_data['bformat_no'] = (int)$form_data['input_bfno']; // 業態No
        $insert_data['search_tag'] = $form_data['input_search_tag']; // タグ
        $insert_data['comp_url'] = $form_data['input_url']; // 企業URL
        $insert_data['comp_type'] = $form_data['input_cptype']; // 企業種別
        $insert_data['created_at'] = Carbon::now(); // 登録日
        $insert_data['updated_at'] = Carbon::now(); // 更新日
        $insert_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm13', $insert_data);

        islog($this->class_name, __FUNCTION__, 'm18');
        $ret = $this->company_repository->insertCompanyData($insert_data);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 企業情報更新
     * 入力した企業情報を登録
     *
     * @access public
     * @param array $form_data
     * @param string $company_no
     * @param string $acnt_no
     * @return Boolean
     * @see CompanyService
     * @throws
     * @todo
     */
    public function updateCompanyData($form_data, $company_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'company_no' => $company_no,
            'acnt_no' => $acnt_no
        ]);

        // 既存更新
        // 更新データを設定
        islog($this->class_name, __FUNCTION__, 'm15');
        $update_data['comp_name'] = $form_data['input_name']; // 企業名
        $update_data['industry_no'] = (int)$form_data['input_indno']; // 業種No
        $update_data['bformat_no'] = (int)$form_data['input_bfno']; // 業態No
        $update_data['comp_business_text'] = convertHtmlText($form_data['input_text']); // 事業内容
        $update_data['comp_banner_image_no'] = (int)$form_data['input_image_no']; // 企業画像番号
        $update_data['page_title'] = $form_data['input_title']; // ページタイトル
        $update_data['search_tag'] = $form_data['input_search_tag']; // タグ
        $update_data['comp_url'] = $form_data['input_url']; // 企業URL
        $update_data['comp_type'] = $form_data['input_cptype']; // 企業種別
        $update_data['updated_at'] = Carbon::now(); // 更新日
        $update_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm14', $update_data);

        islog($this->class_name, __FUNCTION__, 'm19');
        $ret = $this->company_repository->updateCompanyData($update_data, $company_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 企業情報削除
     * 選択した企業情報を削除
     *
     * @access public
     * @param array $company_no
     * @param string $acnt_no
     * @return Boolean
     * @see CompanyService
     * @throws
     * @todo
     */
    public function deleteCompanyData($company_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'acnt_no' => $acnt_no
        ]);

        // 削除開始
        islog($this->class_name, __FUNCTION__, 'm20');
        $ret = $this->company_repository->deleteCompanyData($company_no, $acnt_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * ログイン情報設定
     * 選択した企業情報をログイン情報として設定
     *
     * @access public
     * @param array $login_info
     * @param array $form_data
     * @return array
     * @see CompanyService
     * @throws
     * @todo
     */
    public function setCompanyLoginInfo($login_info, $form_data)
    {
        $login_info['company_no'] = $form_data['input_no'];
        $login_info['company_name'] = $form_data['input_name'];
        $login_info['company_branch_no'] = '';
        $login_info['company_branch_name'] = '';
        $login_info['company_department_no'] = '';
        $login_info['company_department_name'] = '';
        $login_info['products_no'] = '';
        $login_info['products_name'] = '';
        $login_info['products_detail_no'] = '';
        $login_info['products_detail_name'] = '';

        return $login_info;
    }
}
