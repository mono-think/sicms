<?php
/**
 * [DB]acnt_tテーブルのModelクラス
 *
 * テーブル定義
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package model
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Notifications\Notifiable;

class Acnt extends Authenticatable
{
//    use HasFactory;
    use HasApiTokens, HasFactory, Notifiable;
    
    /**
     * テーブル名
     *
     * @var string
     * @example デフォルトから変更する時に定義
     *
     */
    protected $table = 'acnt_t';
    
    /**
     * 主キー
     *
     * @var string
     * @example デフォルトから変更する時に定義
     */
    // protected $primaryKey = '';
    
    /**
     * インクリメント
     *
     * @var bool
     * @example true...有効, false...無効
     */
    // public $incrementing = true;
    
    /**
     * CREATED_AT 登録日
     * UPDATED_AT 更新日
     *
     * @var string
     * @example デフォルトから変更する時に定義
     *
     */
    // const CREATED_AT = '';
    
    // const UPDATED_AT = '';
    
    /**
     * タイムスタンプの自動付与
     *
     * @var bool
     * @example true...有効, false...無効
     */
    // public $timestamps = true;
    
    /**
     * タイムスタンプのフォーマット
     *
     * @var string
     * @example デフォルトから変更する時に定義
     */
    // protected $dateFormat = '';
    
    /**
     * データベース名
     *
     * @var string
     * @example デフォルトから変更する時に定義
     *
     */
    // protected $connection = '';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'comp_no',
        'comp_branch_no',
        'comp_department_no',
        'acnt_no',
        'acnt_sei',
        'acnt_mei',
        'acnt_kana_sei',
        'acnt_kana_mei',
        'acnt_id',
        'password',
        'acnt_tel1',
        'acnt_tel2',
        'email',
        'acnt_email2',
        'acnt_position_no',
        'acnt_position_other_name',
        'acnt_auth_no',
        'acnt_auth_other_name',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at',
        'last_acnt_no',
        'delete_flg'
    ];
    
    /**
     * 値を登録しないカラム
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];
    
    /**
     * パスワードリセット通知の送信をオーバーライド
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
