<?php
/**
 * [DB]image_tテーブルのModelクラス
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

class Image extends Model
{
    use HasFactory;
    
    /**
     * テーブル名
     *
     * @var string
     * @example デフォルトから変更する時に定義
     *         
     */
    protected $table = 'image_t';

    /**
     * 主キー
     *
     * @var string
     * @example デフォルトから変更する時に定義
     */
//    protected $primaryKey = '';

    /**
     * インクリメント
     *
     * @var bool
     * @example true...有効, false...無効
     */
//    protected $incrementing = true;

    /**
     * CREATED_AT 登録日
     * UPDATED_AT 更新日
     *
     * @var string
     * @example デフォルトから変更する時に定義
     *         
     */
//    const CREATED_AT = '';

//    const UPDATED_AT = '';

    /**
     * タイムスタンプの自動付与
     *
     * @var bool
     * @example true...有効, false...無効
     */
//    protected $timestamps = true;

    /**
     * タイムスタンプのフォーマット
     *
     * @var string
     * @example デフォルトから変更する時に定義
     */
//    protected $dateFormat = '';

    /**
     * データベース名
     *
     * @var string
     * @example デフォルトから変更する時に定義
     *         
     */
//    protected $connection = '';

    /**
     * 値を登録するカラム
     *
     * @var array
     */
    protected $fillable = [
        'image_no',
        'comp_no',
        'image_category',
        'image_name',
        'image_folder',
        'image_type',
        'image_text',
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
        'image_no'
    ];
}
