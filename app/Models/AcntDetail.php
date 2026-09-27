<?php
/**
 * [DB]acnt_detail_tテーブルのModelクラス
 *
 * テーブル定義
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package model
 */
namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcntDetail extends Model
{
//    use HasFactory;
    
    /**
     * テーブル名
     *
     * @var string
     * @example デフォルトから変更する時に定義
     *
     */
    protected $table = 'acnt_detail_t';
    
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
        'acnt_detail_no',
        'acnt_no',
        'acnt_detail_zip_code',
        'acnt_detail_pref_code',
        'acnt_detail_city',
        'acnt_detail_town',
        'acnt_detail_address',
        'created_at',
        'updated_at',
        'last_acnt_no',
        'delete_flg',
    ];
    
    /**
     * 値を登録しないカラム
     *
     * @var array
     */
    protected $guarded = [
        'acnt_detail_no'
    ];
}
