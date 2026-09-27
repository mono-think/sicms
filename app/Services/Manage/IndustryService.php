<?php
/**
 * 業種マスタデータ処理のServiceクラス
 * 検索、登録、更新、削除処理
 *
 * @access public
 * @industryor Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package service
 */
namespace App\Services\Manage;

use App\Repositories\Manage\IndustryRepositoryInterface;
use Carbon\Carbon;

class IndustryService
{

    protected $industry_repository;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param IndustryRepositoryInterface $industry_repository
     *
     */
    public function __construct(IndustryRepositoryInterface $industry_repository)
    {
        $this->industry_repository = $industry_repository;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * 業種マスタ情報ページネーション一覧取得
     * 検索条件により全ての業種マスタ情報を取得
     *
     * @access public
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see IndustryService
     * @throws
     * @todo
     */
    public function getIndustryPagenationList($free_word)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $free_word = hscQuotes($free_word); // HTMLエンティティ変換
        $ret = $this->industry_repository->getIndustryPagenationList($free_word);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 業種マスタ情報一覧取得
     * 全ての業種マスタ情報を取得
     *
     * @access public
     * @param string $free_word
     * @param int $start
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see IndustryService
     * @throws
     * @todo
     */
    public function getIndustryList($free_word = null, $start = 0, $limit = null)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word,
            'start' => $start,
            'limit' => $limit
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $free_word = hscQuotes($free_word); // HTMLエンティティ変換
        $ret = $this->industry_repository->getIndustryList($free_word, $start, $limit);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 業種マスタ情報取得
     * industry_noとindustry_nameのみ抽出
     *
     * @access public
     * @param int $industry_no
     * @return array
     * @see IndustryService
     * @throws
     * @todo
     */
    public function getIndustryData($industry_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'industry_no' => $industry_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret = $this->industry_repository->getIndustryData($industry_no);
        $form_data = [];
        if (! empty($ret)) {
            // 配列化
            $form_data = [
                'input_no' => $ret->industry_no, // 業種番号
                'input_name' => $ret->industry_name // 業種名
            ];
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $form_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $form_data;
    }

    /**
     * 業種マスタ情報登録
     * 入力した業種マスタ情報を登録
     *
     * @access public
     * @param array $form_data
     * @param string $acnt_no
     * @return Boolean
     * @see IndustryService
     * @throws
     * @todo
     */
    public function insertIndustryData($form_data, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'acnt_no' => $acnt_no
        ]);

        // 新規登録
        // 登録データを設定
        islog($this->class_name, __FUNCTION__, 'm14');
        $insert_data['industry_name'] = $form_data['input_name']; // 業種名
        $insert_data['created_at'] = Carbon::now(); // 登録日
        $insert_data['updated_at'] = Carbon::now(); // 更新日
        $insert_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm13', $insert_data);

        islog($this->class_name, __FUNCTION__, 'm18');
        $ret = $this->industry_repository->insertIndustryData($insert_data);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 業種マスタ情報更新
     * 入力した業種マスタ情報を更新
     *
     * @access public
     * @param array $form_data
     * @param int $input_no
     * @param string $acnt_no
     * @return Boolean
     * @see IndustryService
     * @throws
     * @todo
     */
    public function updateIndustryData($form_data, $industry_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'industry_no' => $industry_no,
            'acnt_no' => $acnt_no
        ]);

        // 既存更新
        // 更新データを設定
        islog($this->class_name, __FUNCTION__, 'm15');
        $update_data['industry_name'] = $form_data['input_name']; // 権限名
        $update_data['updated_at'] = Carbon::now(); // 更新日
        $update_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm14', $update_data);

        islog($this->class_name, __FUNCTION__, 'm19');
        $ret = $this->industry_repository->updateIndustryData($update_data, $industry_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 業種マスタ情報削除
     * 選択した企業情報を削除
     *
     * @access public
     * @param array $industry_no
     * @return Boolean
     * @see IndustryService
     * @throws
     * @todo
     */
    public function deleteIndustryData($industry_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'industry_no' => $industry_no
        ]);

        // 削除開始
        islog($this->class_name, __FUNCTION__, 'm20');
        $ret = $this->industry_repository->deleteIndustryData($industry_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}
