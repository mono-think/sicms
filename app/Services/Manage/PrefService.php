<?php
/**
 * 都道府県マスタデータ処理のServiceクラス
 * 検索、登録、更新、削除処理
 *
 * @access public
 * @prefor Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package service
 */
namespace App\Services\Manage;

use App\Repositories\Manage\PrefRepositoryInterface;
use Carbon\Carbon;

class PrefService
{

    protected $pref_repository;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param PrefRepositoryInterface $pref_repository
     *
     */
    public function __construct(PrefRepositoryInterface $pref_repository)
    {
        $this->pref_repository = $pref_repository;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * 都道府県マスタ情報ページネーション一覧取得
     * 検索条件により全ての都道府県マスタ情報を取得
     *
     * @access public
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see PrefService
     * @throws
     * @todo
     */
    public function getPrefPagenationList($free_word)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $free_word = hscQuotes($free_word); // HTMLエンティティ変換
        $ret = $this->pref_repository->getPrefPagenationList($free_word);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 都道府県マスタ情報一覧取得
     * 全ての都道府県マスタ情報を取得
     *
     * @access public
     * @param string $free_word
     * @param int $start
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws
     * @todo
     */
    public function getPrefList($free_word = null, $start = 0, $limit = null)
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
        $ret = $this->pref_repository->getPrefList($free_word, $start, $limit);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 都道府県マスタ情報取得
     * pref_noとpref_nameのみ抽出
     *
     * @access public
     * @param int $pref_no
     * @return array
     * @see PrefService
     * @throws
     * @todo
     */
    public function getPrefData($pref_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'pref_no' => $pref_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret = $this->pref_repository->getPrefData($pref_no);
        $form_data = [];
        if (! empty($ret)) {
            // 配列化
            $form_data = [
                'input_no' => $ret->pref_no, // 都道府県番号
                'input_name' => $ret->pref_name // 都道府県名
            ];
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $form_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $form_data;
    }

    /**
     * 都道府県マスタ情報登録
     * 入力した都道府県マスタ情報を登録
     *
     * @access public
     * @param array $form_data
     * @param string $acnt_no
     * @return Boolean
     * @see PrefService
     * @throws
     * @todo
     */
    public function insertPrefData($form_data, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'acnt_no' => $acnt_no
        ]);

        // 新規登録
        // 登録データを設定
        islog($this->class_name, __FUNCTION__, 'm14');
        $insert_data['pref_name'] = $form_data['input_name']; // 都道府県名
        $insert_data['created_at'] = Carbon::now(); // 登録日
        $insert_data['updated_at'] = Carbon::now(); // 更新日
        $insert_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm13', $insert_data);

        islog($this->class_name, __FUNCTION__, 'm18');
        $ret = $this->pref_repository->insertPrefData($insert_data);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 都道府県マスタ情報更新
     * 入力した都道府県マスタ情報を更新
     *
     * @access public
     * @param array $form_data
     * @param int $pref_no
     * @param string $acnt_no
     * @return Boolean
     * @see PrefService
     * @throws
     * @todo
     */
    public function updatePrefData($form_data, $pref_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'pref_no' => $pref_no,
            'acnt_no' => $acnt_no
        ]);

        // 既存更新
        // 更新データを設定
        islog($this->class_name, __FUNCTION__, 'm15');
        $update_data['pref_name'] = $form_data['input_name']; // 都道府県名
        $update_data['updated_at'] = Carbon::now(); // 更新日
        $update_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm14', $update_data);

        islog($this->class_name, __FUNCTION__, 'm19');
        $ret = $this->pref_repository->updatePrefData($update_data, $pref_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 都道府県マスタ情報削除
     * 選択した企業情報を削除
     *
     * @access public
     * @param int $pref_no
     * @return Boolean
     * @see PrefService
     * @throws
     * @todo
     */
    public function deletePrefData($pref_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'pref_no' => $pref_no
        ]);

        // 削除開始
        islog($this->class_name, __FUNCTION__, 'm20');
        $ret = $this->pref_repository->deletePrefData($pref_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}
