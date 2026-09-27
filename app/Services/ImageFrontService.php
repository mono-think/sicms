<?php
/**
 * 画像情報処理のServiceクラス
 * 画像変換、生成処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package service
 */
namespace App\Services;

use Illuminate\Support\Str;
use Image;

class ImageFrontService
{

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     *        
     */
    public function __construct()
    {
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * Captcha画像生成
     * ランダムな数値を合成した長方形画像を生成
     *
     * @access public
     * @param int $limit
     * @return int
     * @see ImageFrontService
     * @throws
     * @todo
     */
    public function createCaptchaImage($limit)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'limit' => $limit
        ]);

        // ベースの長方形生成
        $img = Image::canvas(55, 30, '#FFF9E6');
        // 合成する数値を乱数で出力
        $convert_num = Str::random($limit);
        // 指定パラメータで数値を合成
        $img->text($convert_num, 10, 10, function ($font) {
            $font->file('5');
            $font->size(100);
            $font->color('#000');
            $font->align('left');
            $font->valign('top');
        });

        // 保存フルパスへ生成画像保存
        $temp_path = getCaptchaSavePath();
        $img->save($temp_path);
        
        islog($this->class_name, __FUNCTION__, 'm53', $convert_num);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $convert_num;
    }
}
