<?php
namespace App\Repositories\Manage;

interface VerificationRepositoryInterface
{

    /**
     * 認証情報取得インタフェース
     *
     * @access public
     * @param string $ipaddress
     * @param string $email
     * @param int $code
     */
    public function getVerificationData($ipaddress, $email, $code);

    /**
     * 認証情報登録/更新インタフェース
     *
     * @access public
     * @param array $upsert_data
     * @param array $update_data
     */
    public function upsertVerificationData($upsert_data, $update_data);

    /**
     * 認証情報削除インタフェース
     *
     * @access public
     * @param array $delete_data
     */
    public function deleteVerificationData($delete_data);
}