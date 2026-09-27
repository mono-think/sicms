<?php
/**
 * 業態マスタデータ処理のServiceクラス
 * 検索、登録、更新、削除処理
 *
 * @access public
 * @bformator Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package service
 */
namespace App\Services\Manage;

use App\Repositories\Manage\BformatRepositoryInterface;
use Carbon\Carbon;

class BformatService
{

    protected $bformat_repository;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param BformatRepositoryInterface $bformat_repository
     *
     */
    public function __construct(BformatRepositoryInterface $bformat_repository)
    {
        $this->bformat_repository = $bformat_repository;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * 業態マスタ情報ページネーション一覧取得
     * 検索条件により全ての業態マスタ情報を取得
     *
     * @access public
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see BformatService
     * @throws
     * @todo
     */
    public function getBformatPagenationList($free_word)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $free_word = hscQuotes($free_word); // HTMLエンティティ変換
        $ret = $this->bformat_repository->getBfromatPagenationList($free_word);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 業態マスタ情報一覧取得
     * 全ての業態マスタ情報を取得
     *
     * @access public
     * @param string $free_word
     * @param int $start
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @see BformatService
     * @throws
     * @todo
     */
    public function getBformatList($free_word = null, $start = 0, $limit = null)
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
        $ret = $this->bformat_repository->getBfromatList($free_word, $start, $limit);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 業態マスタ情報取得
     * bformat_noとbformat_nameのみ抽出
     *
     * @access public
     * @param int $bformat_no
     * @return array
     * @see BformatService
     * @throws
     * @todo
     */
    public function getBformatData($bformat_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'bformat_no' => $bformat_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret = $this->bformat_repository->getBformatData($bformat_no);
        $form_data = [];
        if (! empty($ret)) {
            // 配列化
            $form_data = [
                'input_no' => $ret->bformat_no, // 業態番号
                'input_name' => $ret->bformat_name // 業態名
            ];
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $form_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $form_data;
    }

    /**
     * 業態マスタ情報登録
     * 入力した業態マスタ情報を登録
     *
     * @access public
     * @param array $form_data
     * @param string $acnt_no
     * @return Boolean
     * @see BformatService
     * @throws
     * @todo
     */
    public function insertBformatData($form_data, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'acnt_no' => $acnt_no
        ]);

        // 新規登録
        // 登録データを設定
        islog($this->class_name, __FUNCTION__, 'm14');
        $insert_data['bformat_name'] = $form_data['input_name']; // 業態名
        $insert_data['created_at'] = Carbon::now(); // 登録日
        $insert_data['updated_at'] = Carbon::now(); // 更新日
        $insert_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm13', $insert_data);

        islog($this->class_name, __FUNCTION__, 'm18');
        $ret = $this->bformat_repository->insertBformatData($insert_data);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 業態マスタ情報更新
     * 入力した業態マスタ情報を登録
     *
     * @access public
     * @param array $form_data
     * @param int $bformat_no
     * @param string $acnt_no
     * @return Boolean
     * @see BformatService
     * @throws
     * @todo
     */
    public function updateBformatData($form_data, $bformat_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'bformat_no' => $bformat_no,
            'acnt_no' => $acnt_no
        ]);

        // 既存更新
        // 更新データを設定
        islog($this->class_name, __FUNCTION__, 'm15');
        $update_data['bformat_name'] = $form_data['input_name']; // 権限名
        $update_data['updated_at'] = Carbon::now(); // 更新日
        $update_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm14', $update_data);

        islog($this->class_name, __FUNCTION__, 'm19');
        $ret = $this->bformat_repository->updateBformatData($update_data, $bformat_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 業態マスタ情報削除
     * 選択した企業情報を削除
     *
     * @access public
     * @param int $bformat_no
     * @return Boolean
     * @see BformatService
     * @throws
     * @todo
     */
    public function deleteBformatData($bformat_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'bformat_no' => $bformat_no
        ]);

        // 削除開始
        islog($this->class_name, __FUNCTION__, 'm20');
        $ret = $this->bformat_repository->deleteBformatData($bformat_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}
