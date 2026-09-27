<?php
/**
 * [DB]pref_mテーブルのModelクラス
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
use Illuminate\Database\Eloquent\Model;

class Pref extends Model
{
    use HasFactory;

    /**
     * テーブル名
     *
     * @var string
     * @example デフォルトから変更する時に定義
     *         
     */
    protected $table = 'pref_m';

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
     * 値を登録するカラム
     *
     * @var array
     */
    protected $fillable = [
        'pref_no',
        'pref_name',
        'created_at',
        'updated_at',
        'last_acnt_no'
    ];

    /**
     * 値を登録しないカラム
     *
     * @var array
     */
    protected $guarded = [
        'pref_no'
    ];
}
