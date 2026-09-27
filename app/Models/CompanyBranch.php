<?php
/**
 * [DB]comp_branch_tテーブルのModelクラス
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

class CompanyBranch extends Model
{
    use HasFactory;

    /**
     * テーブル名
     *
     * @var string
     * @example デフォルトから変更する時に定義
     *         
     */
    protected $table = 'comp_branch_t';

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
        'comp_branch_mgr_no',
        'comp_branch_no',
        'comp_no',
        'comp_branch_name',
        'comp_branch_zip_code',
        'comp_branch_pref_code',
        'comp_branch_city',
        'comp_branch_town',
        'comp_branch_address',
        'comp_branch_order',
        'suppoter_flg',
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
        'comp_branch_no'
    ];
}
