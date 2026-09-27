<?php
/**
 * [DB]verification_tテーブルのRepositoryクラス
 *
 * verification_tテーブルをメインとするイベント情報の検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package repository
 */
namespace App\Repositories\Manage;

use App\Models\Verification;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Carbon\Carbon;

class VerificationRepository implements VerificationRepositoryInterface
{

    protected $verification_obj;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param Verification $verification
     */
    public function __construct(Verification $verification)
    {
        $this->verification_obj = $verification;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * [検索]認証情報取得
     *
     * 認証メールアドレスから認証情報を取得
     *
     * @access public
     * @param string $ipaddress
     * @param string $email
     * @param int $code
     * @return object Illuminate\Database\Eloquent\Collectionオブジェクト
     * @see VerificationRepository
     * @throws QueryException
     * @todo
     */
    public function getVerificationData($ipaddress, $email, $code)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'ipaddress' => $ipaddress,
            'email' => $email,
            'code' => $code
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm2');
        $cb = new Carbon(); // 現在日時
        $now_datetime = $cb->format('Y-m-d H:i:s');
        $ret = $this->verification_obj->whereRaw('date_add(updated_at,interval 1 day) >= "' . $now_datetime . '"')
            ->where('ipaddress', $ipaddress)
            ->where('email', $email)
            ->where('code', $code)
            ->first();

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [登録]認証情報登録/更新
     *
     * 認証情報を追加または更新
     *
     * @access public
     * @param array $upsert_data
     * @param array $update_data
     * @return Boolean $ret
     * @see VerificationRepository
     * @throws QueryException
     * @todo
     */
    public function upsertVerificationData($upsert_data, $update_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'upsert_data' => $upsert_data
        ]);

        // メールアドレスに対応する認証情報があればコード更新
        // メールアドレスに対応する認証情報がなければコード登録
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm5');
        $ret = $this->verification_obj->updateOrInsert($upsert_data, $update_data);
        if (empty($ret)) {
            // 登録または更新失敗
            eslog($this->class_name, __FUNCTION__, 'm1');
            islog($this->class_name, __FUNCTION__, 'm8');
            DB::rollBack();
        } else {
            // 登録または更新完了
            islog($this->class_name, __FUNCTION__, 'm9');
            DB::commit();
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * [削除]認証情報削除
     *
     * 有効な認証情報を削除
     *
     * @access public
     * @param string $delete_data
     * @return Boolean $ret
     * @see VerificationRepository
     * @throws QueryException
     * @todo
     */
    public function deleteVerificationData($delete_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'delete_data' => $delete_data
        ]);

        // イベント削除
        islog($this->class_name, __FUNCTION__, 'm4');
        DB::beginTransaction();
        islog($this->class_name, __FUNCTION__, 'm7');
        $ret = $this->verification_obj->where('email', $delete_data)->delete();
        if (empty($ret)) {
            // 削除失敗
            eslog($this->class_name, __FUNCTION__, 'm3');
            islog($this->class_name, __FUNCTION__, 'm8');
            DB::rollBack();
        } else {
            // 削除完了
            islog($this->class_name, __FUNCTION__, 'm9');
            DB::commit();
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}