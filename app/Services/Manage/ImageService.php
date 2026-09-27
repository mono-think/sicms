<?php
/**
 * 画像情報処理のServiceクラス
 * 検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package service
 */
namespace App\Services\Manage;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Repositories\Manage\ImageRepositoryInterface;
use Carbon\Carbon;
use Image;

class ImageService
{

    protected $image_repository;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param ImageRepositoryInterface $image_repository
     *
     */
    public function __construct(ImageRepositoryInterface $image_repository)
    {
        $this->image_repository = $image_repository;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * 画像情報ページネーション一覧取得
     * 検索条件により全ての画像情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ImageService
     * @throws
     * @todo
     */
    public function getImagePagenationList($company_no, $free_word)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $free_word = hscQuotes($free_word); // HTMLエンティティ変換
        $ret = $this->image_repository->getImagePagenationList($company_no, $free_word);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 画像情報一覧取得
     * 検索条件により全ての画像情報を取得
     *
     * @access public
     * @param string $company_no
     * @param int $image_category
     * @param string $free_word
     * @param int $start
     * @return \Illuminate\Database\Eloquent\Collection
     * @see ImageService
     * @throws
     * @todo
     */
    public function getImageList($company_no, $image_category, $free_word, $start, $limit)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'image_category' => $image_category,
            'free_word' => $free_word,
            'start' => $start,
            'limit' => $limit
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $free_word = hscQuotes($free_word); // HTMLエンティティ変換
        $ret_collection = $this->image_repository->getImageList($company_no, $image_category, $free_word, $start, $limit);
        dslog($this->class_name, __FUNCTION__, 'm2', $ret_collection);

        // 各コレクションオブジェクトへいくつか要素を追加
        $convert_collection = new Collection();
        foreach ($ret_collection as $obj) {
            $obj->image_category_name = getConstsType('image_category', $obj->image_category); // カテゴリ
            $convert_collection->push($obj);
        }
        $ret = $convert_collection->toJson(); // Json化
        
        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 画像情報登録
     * 入力した画像情報を登録
     *
     * @access public
     * @param array $upload_info
     * @param string $company_no
     * @param string $acnt_no
     * @return Boolean
     * @see ImageService
     * @throws
     * @todo
     */
    public function insertImageData($upload_info, $image_category, $company_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'upload_info' => $upload_info,
            'image_category' => $image_category,
            'company_no' => $company_no,
            'acnt_no' => $acnt_no
        ]);

        // 登録開始
        islog($this->class_name, __FUNCTION__, 'm14');
        $ret = [];
        foreach ($upload_info as $upload_data) {

            // 登録データを設定
            $insert_data = null;
            $insert_data['comp_no'] = $company_no; // 企業管理番号
            $insert_data['image_category'] = $image_category; // カテゴリ番号
            $insert_data['image_name'] = $upload_data['image_file']; // 画像ファイル名
            $insert_data['image_folder'] = $upload_data['image_path']; // 画像フォルダー
            $insert_data['image_type'] = $upload_data['image_type']; // 画像タイプ
            $insert_data['created_at'] = Carbon::now(); // 登録日
            $insert_data['updated_at'] = Carbon::now(); // 更新日
            $insert_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
            dslog($this->class_name, __FUNCTION__, 'm13', $insert_data);

            islog($this->class_name, __FUNCTION__, 'm18');
            $ret[] = $this->image_repository->insertImageData($insert_data);
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 画像情報更新
     * 入力した画像情報を更新
     *
     * @access public
     * @param array $form_data
     * @param int $image_no
     * @param string $acnt_no
     * @return Boolean
     * @see ImageService
     * @throws
     * @todo 【MySQL暫定対応】MySQLバージョンを5.7以上にあげたら、DB対応後に191を255へ変更する
     */
    public function updateImageData($form_data, $image_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'image_no' => $image_no,
            'acnt_no' => $acnt_no
        ]);

        // 更新パラメータのバリデーション
        $chk_param = null;
        // カテゴリ
        if (! empty(getConstsType('image_category', $form_data['ctg_no']))) {
            $chk_param[] = true;
        } else if (empty($form_data['ctg_no'])) {
            $chk_param[] = true;
        }
        // 引用テキスト
        if (! empty($form_data['alt']) && is_string($form_data['alt'])) {
            if (mb_strlen($form_data['alt'], 'UTF-8') <= 191) {
                $chk_param[] = true;
            }
        } else if (empty($form_data['alt'])) {
            $chk_param[] = true;
        }
        // キャプション
        if (! empty($form_data['text']) && is_string($form_data['text'])) {
            if (mb_strlen($form_data['text'], 'UTF-8') <= 191) {
                $chk_param[] = true;
            }
        } else if (empty($form_data['text'])) {
            $chk_param[] = true;
        }

        // 3件のパラメータバリデーションが通過したら更新する
        $ret = false;
        if (! is_null($chk_param) && (count($chk_param) === 3)) {
            // 既存更新
            // 更新データを設定
            islog($this->class_name, __FUNCTION__, 'm15');
            $update_data['image_category'] = $form_data['ctg_no']; // カテゴリ番号
            $update_data['image_alt'] = strip_tags($form_data['alt']); // 引用テキスト
            $update_data['image_text'] = strip_tags($form_data['text']); // キャプション
            $update_data['updated_at'] = Carbon::now(); // 更新日
            $update_data['last_acnt_no'] = $acnt_no; // 最終更新者NO
            dslog($this->class_name, __FUNCTION__, 'm14', $update_data);

            islog($this->class_name, __FUNCTION__, 'm19');
            $ret = $this->image_repository->updateImageData($update_data, $image_no);
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 画像情報削除
     * 選択した画像情報を削除
     *
     * @access public
     * @param string $company_no
     * @param int $image_no
     * @param string $image_path
     * @return Boolean
     * @see ImageService
     * @throws
     * @todo
     */
    public function deleteImageData($company_no, $image_no, $image_path)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'image_no' => $image_no,
            'image_path' => $image_path
        ]);

        // 削除開始
        islog($this->class_name, __FUNCTION__, 'm20');
        $ret = $this->image_repository->deleteImageData($image_no, $company_no);
        dslog($this->class_name, __FUNCTION__, 'm20', $ret);

        // 正常終了したらファイル削除
        if (! empty($ret)) {
            islog($this->class_name, __FUNCTION__, 'm54');
            foreach ($image_path as $path_info) {
                list ($no, $path) = explode("=>", $path_info);
                if (in_array($no, $image_no)){
                    $result = Storage::deleteDirectory(getUploadImagePath($path));
                    dslog($this->class_name, __FUNCTION__, 'm21', [
                        'path' => $path,
                        'result' => $result
                    ]);
                }
            }
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * アップロード画像のバリデーション
     * アップロード画像からエラーファイルを削除
     *
     * @access public
     * @param array $form_image_data
     * @return mixed
     * @see ImageService
     * @throws
     * @todo
     */
    public function validateImageData($form_image_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_image_data' => $form_image_data
        ]);

        $err_idx = [];
        foreach ($form_image_data as $idx => $data) {
            // バリデーション（存在チェック、最小画像幅とmimeタイプチェック）
            if (! chkUploadImage($data)) {
                $err_idx[] = $idx;
            }
        }

        // エラーファイルだけアンセット（アップロード対象から除外)
        if (! empty($err_idx)) {
            foreach ($err_idx as $no) {
                if (isset($form_image_data[$no])) {
                    eslog($this->class_name, __FUNCTION__, 'm23', $form_image_data[$no]->getClientOriginalName());
                    unset($form_image_data[$no]);
                }
            }
        }

        // アンセット結果が空
        if (empty($form_image_data)) {
            // 画像アップロードに失敗
            dslog($this->class_name, __FUNCTION__, 'm2', false);
            islog($this->class_name, __FUNCTION__, 'm3');
            return false;
        } else {
            // 連番振り直し
            $form_image_data = array_values($form_image_data);
            dslog($this->class_name, __FUNCTION__, 'm2', $form_image_data);
            islog($this->class_name, __FUNCTION__, 'm3');
            return $form_image_data;
        }
    }

    /**
     * 画像ファイルのアップロード処理
     * 一時アップロードファイルを正規フォルダへ移動、リネーム処理
     *
     * @access public
     * @param array $form_image_data
     * @return array;
     * @see ImageService
     * @throws
     * @todo
     */
    public function uploadImageFile($form_image_data)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_image_data' => $form_image_data
        ]);

        // 画像アップロード（正規フォルダへ保存）
        $image_info = [];
        foreach ($form_image_data as $idx => $image_data) {

            // アップロードフォルダを設定
            islog($this->class_name, __FUNCTION__, 'm46');
            $now = Carbon::now();
            $upfolder = $now->format('Ymd') . '/' . Str::random(10);
            $upload_path = getImageBaseFolder() . '/' . $upfolder;
            dslog($this->class_name, __FUNCTION__, 'm15', $upload_path);

            // アップロードファイルを保存
            islog($this->class_name, __FUNCTION__, 'm47');
            $file = $image_data->getClientOriginalName();
            $type = $image_data->extension();
            $ret = $image_data->storeAs($upload_path, $file);
            if (! empty($ret)) {
                // アップロード正常終了
                $image_info[$idx] = [
                    'image_path' => $upfolder,
                    'image_file' => substr($file, 0, strripos($file, '.')), // 拡張子なしを取得
                    'image_type' => $type
                ];
            } else {
                // アップロード異常終了
                $image_info[$idx] = [];
            }
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $image_info);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $image_info;
    }

    /**
     * 画像変換処理
     * 指定パラメータに従い画像変換する指示を与える
     *
     * @access public
     * @param array $image_info
     * @return Boolean
     * @see ImageService
     * @throws
     * @todo
     */
    public function convertImageSeq($image_info)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'image_info' => $image_info
        ]);

        // 変換パラメータ取得
        $upload_current_pattern = getImageConvertPattern();
        $upload_image_pattern = getConstsType('image_type');
        dslog($this->class_name, __FUNCTION__, 'm16', $upload_current_pattern);
        dslog($this->class_name, __FUNCTION__, 'm16', $upload_image_pattern);

        // 画像変換開始
        islog($this->class_name, __FUNCTION__, 'm43');
        $err_count = 0;
        foreach ($upload_image_pattern as $image_type) {
            foreach ($upload_current_pattern as $pattern) {
                $convert_info = [
                    'width_pixel' => $pattern['width'],
                    'height_pixel' => $pattern['height'],
                    'image_type' => $image_type,
                    'image_compression' => $pattern['compression'],
                    'rename_flg' => $pattern['rename_flg'],
                    'default_name' => $pattern['default_name']
                ];
                dslog($this->class_name, __FUNCTION__, 'm17', $convert_info);

                // 画像変換
                islog($this->class_name, __FUNCTION__, 'm48');
                $ret = $this->convertImage($image_info, $convert_info);
                if (empty($ret)) {
                    // 変換失敗
                    eslog($this->class_name, __FUNCTION__, 'm13');
                    $err_count ++;
                }
            }
        }

        dslog($this->class_name, __FUNCTION__, 'm18', $err_count);
        $ret = false;
        if (empty($err_count)) {
            $ret = true;
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 画像変換
     * 指定パラメータに従い画像変換を行う
     *
     * @access public
     * @param array $image_info
     * @param array $convert_info
     * @return Boolean
     * @see ImageService
     * @throws
     * @todo
     */
    public function convertImage($image_info, $convert_info)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'image_info' => $image_info,
            'convert_info' => $convert_info
        ]);

        $convert_file = [];
        foreach ($image_info as $image_data) {

            // パス作成
            $upload_image_file = getUploadImageFullPath($image_data['image_path'], $image_data['image_file'], $image_data['image_type']);
            $convert_image_file = getUploadImageFullPath($image_data['image_path'], $image_data['image_file']);

            // アップロード画像の有無判定（インスタンス生成結果）
            islog($this->class_name, __FUNCTION__, 'm49');
            $img = Image::make($upload_image_file);
            if (empty($img)) {
                // アップロード画像が存在しない
                eslog($this->class_name, __FUNCTION__, 'm20', $upload_image_file);
                islog($this->class_name, __FUNCTION__, 'm3');
                return false;
            } else {
                dslog($this->class_name, __FUNCTION__, 'm15', $upload_image_file);

                // 画像タイプ設定
                $image_type = empty($convert_info['image_type']) ? $image_data['image_type'] : $convert_info['image_type'];
                // 指定パラメータの画像サイズに高さ指定の有無
                if (empty($convert_info['height_pixel'])) {
                    islog($this->class_name, __FUNCTION__, 'm50');
                    // 高さ指定無しのため、指定の幅、高さは自動調整でリサイズ
                    $ret = $img->resize($convert_info['width_pixel'], null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize(); // ファイルサイズの肥大化は無視
                    })
                        ->encode($image_type);
                } else {
                    islog($this->class_name, __FUNCTION__, 'm51');
                    // 画像サイズは指定の幅、高さでリサイズ
                    $ret = $img->resize($convert_info['width_pixel'], $convert_info['height_pixel'])->encode($image_type);
                }

                if (empty($ret)) {
                    // 変換失敗
                    eslog($this->class_name, __FUNCTION__, 'm13');
                    islog($this->class_name, __FUNCTION__, 'm3');
                    return false;
                }

                // 変換後の画像サイズを付与したファイル名で保存する
                islog($this->class_name, __FUNCTION__, 'm52');
                $convert_file_name = convertImageFilename($image_data['image_file'], $convert_info['width_pixel'], $convert_info['height_pixel'], $image_type, $convert_info['rename_flg'], $convert_info['default_name']);
                $convert_image_file = getUploadImagePath($image_data['image_path'], $convert_file_name);
                Storage::put($convert_image_file, $ret);
                // $ret->save($convert_image_file, $convert_info['image_compression']);
                $convert_file[] = $convert_image_file;
            }
        }
        islog($this->class_name, __FUNCTION__, 'm53', $convert_file);
        islog($this->class_name, __FUNCTION__, 'm3');
        return true;
    }
}
