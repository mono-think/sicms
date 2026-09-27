<?php
/**
 * 役職マスタデータ処理のServiceクラス
 * 検索、登録、更新、削除処理
 *
 * @access public
 * @positionor Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package service
 */
namespace App\Services\Manage;

use App\Repositories\Manage\PositionRepositoryInterface;
use Carbon\Carbon;

class PositionService
{

    protected $position_repository;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param PositionRepositoryInterface $position_repository
     *
     */
    public function __construct(PositionRepositoryInterface $position_repository)
    {
        $this->position_repository = $position_repository;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * 役職マスタ情報ページネーション一覧取得
     * 検索条件により全ての役職マスタ情報を取得
     *
     * @access public
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see PositionService
     * @throws
     * @todo
     */
    public function getPositionPagenationList($free_word)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $free_word = hscQuotes($free_word); // HTMLエンティティ変換
        $ret = $this->position_repository->getPositionPagenationList($free_word);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 役職マスタ情報一覧取得
     * 全ての役職マスタ情報を取得
     *
     * @access public
     * @param string $free_word
     * @param int $start
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws
     * @todo
     */
    public function getPositionList($free_word = null, $start = 0, $limit = null)
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
        $ret = $this->position_repository->getPositionList($free_word, $start, $limit);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 役職マスタ情報取得
     * position_noとposition_nameのみ抽出
     *
     * @access public
     * @param int $position_no
     * @return array
     * @see PositionService
     * @throws
     * @todo
     */
    public function getPositionData($position_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'position_no' => $position_no
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret = $this->position_repository->getPositionData($position_no);
        $form_data = [];
        if (! empty($ret)) {
            // 配列化
            $form_data = [
                'input_no' => $ret->position_no, // 役職番号
                'input_name' => $ret->position_name // 役職名
            ];
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $form_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $form_data;
    }

    /**
     * 役職マスタ情報登録
     * 入力した役職マスタ情報を登録
     *
     * @access public
     * @param array $form_data
     * @param string $acnt_no
     * @return Boolean
     * @see PositionService
     * @throws
     * @todo
     */
    public function insertPositionData($form_data, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'acnt_no' => $acnt_no
        ]);

        // 新規登録
        // 登録データを設定
        islog($this->class_name, __FUNCTION__, 'm14');
        $insert_data['position_name'] = $form_data['input_name']; // 役職名
        $insert_data['created_at'] = Carbon::now(); // 登録日
        $insert_data['updated_at'] = Carbon::now(); // 更新日
        $insert_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm13', $insert_data);

        islog($this->class_name, __FUNCTION__, 'm18');
        $ret = $this->position_repository->insertPositionData($insert_data);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 役職マスタ情報更新
     * 入力した役職マスタ情報を更新
     *
     * @access public
     * @param array $form_data
     * @param int $position_no
     * @param string $acnt_no
     * @return Boolean
     * @see PositionService
     * @throws
     * @todo
     */
    public function updatePositionData($form_data, $position_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'position_no' => $position_no,
            'acnt_no' => $acnt_no
        ]);

        // 既存更新
        // 更新データを設定
        islog($this->class_name, __FUNCTION__, 'm15');
        $update_data['position_name'] = $form_data['input_name']; // 役職名
        $update_data['updated_at'] = Carbon::now(); // 更新日
        $update_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
        dslog($this->class_name, __FUNCTION__, 'm14', $update_data);

        islog($this->class_name, __FUNCTION__, 'm19');
        $ret = $this->position_repository->updatePositionData($update_data, $position_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 役職マスタ情報削除
     * 選択した企業情報を削除
     *
     * @access public
     * @param int $position_no
     * @return Boolean
     * @see PositionService
     * @throws
     * @todo
     */
    public function deletePositionData($position_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'position_no' => $position_no
        ]);

        // 削除開始
        islog($this->class_name, __FUNCTION__, 'm20');
        $ret = $this->position_repository->deletePositionData($position_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}
