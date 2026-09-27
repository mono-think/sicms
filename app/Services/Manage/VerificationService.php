<?php
/**
 * 認証情報処理のServiceクラス
 * 検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package service
 */
namespace App\Services\Manage;

use App\Repositories\Manage\VerificationRepositoryInterface;
use App\Mail\Manage\TwoStepVerificationMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class VerificationService
{

    protected $verification_repository;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param VerificationRepositoryInterface $verification_repository
     *
     */
    public function __construct(VerificationRepositoryInterface $verification_repository)
    {
        $this->verification_repository = $verification_repository;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * 二段階認証情報取得
     * IPアドレスとid(email)と認証コードにより認証情報を取得
     *
     * @access public
     * @param string $email
     * @param int $coe
     * @return array 空配列 or 連想配列
     * @see VerificationService
     * @throws
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
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret = $this->verification_repository->getVerificationData($ipaddress, $email, $code);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 認証情報登録/更新
     *
     *
     * @access public
     * @param string $ipaddress
     * @param string $email
     * @return Boolean $ret;
     * @see VerificationService
     * @throws
     * @todo
     */
    public function upsertVerificationData($ipaddress, $email)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'ipaddress' => $ipaddress,
            'email' => $email
        ]);

        // 認証コードを発行（6桁の乱数）
        $verification_code = getNumRandom(6);

        // 認証コードをメール送信
        islog($this->class_name, __FUNCTION__, 'm59');
        $mail_data['two_step_verification'] = $verification_code;
        dslog($this->class_name, __FUNCTION__, 'm1', $mail_data);
        Mail::to($email)->send(new TwoStepVerificationMail($mail_data));

        // 登録があれば認証コードのみ更新、そうでなければ新規登録
        // 登録更新データを設定
        islog($this->class_name, __FUNCTION__, 'm14');
        islog($this->class_name, __FUNCTION__, 'm15');
        $upsert_data = [
            'email' => $email, // メールアドレス
            'ipaddress' => $ipaddress // 認証コード発行時のIPアドレス
        ];
        $update_data = [
            'code' => $verification_code, // 認証コード
            'updated_at' => Carbon::now() // 認証コード発行時の登録/更新日
        ];
        dslog($this->class_name, __FUNCTION__, 'm13', [
            'upsert_data' => $upsert_data,
            'update_data' => $update_data
        ]);

        islog($this->class_name, __FUNCTION__, 'm18');
        $ret = $this->verification_repository->upsertVerificationData($upsert_data, $update_data);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 認証情報削除
     * 不要となった認証情報を削除
     *
     * @access public
     * @param string $email
     * @return Boolean $ret;
     * @see VerificationService
     * @throws
     * @todo
     */
    public function deleteVerificationData($email)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'email' => $email
        ]);

        // 削除開始
        islog($this->class_name, __FUNCTION__, 'm20');
        $ret = $this->verification_repository->deleteVerificationData($email);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }
}
