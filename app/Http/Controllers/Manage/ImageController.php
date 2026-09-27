<?php
/**
 * [管理]画像系画面のControllerクラス
 *
 * 画像系画面の表示、検索、登録、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package controller
 */
namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\ImageUpdateRequest;
use App\Http\Requests\Manage\CommonSearchRequest;
use App\Http\Requests\Manage\CommonDeleteNumRequest;
use App\Rules\ValueExists;
use App\Services\Manage\ImageService;
use App\Services\Manage\CompanyService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use App\Models\Company;

class ImageController extends BaseController
{

    protected $image_service;

    protected $company_service;

    protected $company_model;
    
    protected $image_session_name;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @param ImageService $image_service
     */
    public function __construct(CompanyService $company_service, ImageService $image_service, Company $company_model)
    {
        $this->company_service = $company_service;
        $this->image_service = $image_service;
        $this->company_model = $company_model;
        $this->class_name = get_class($this);
        $this->image_session_name = config('consts.session_info.image_auth');
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \App\Http\Requests\Manage\CommonSearchRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(CommonSearchRequest $request)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // ログイン情報取得
            islog($this->class_name, __FUNCTION__, 'm28');
            $login_info = getLoginInfo($request);
            dslog($this->class_name, __FUNCTION__, 'm11', $login_info);
            // 選択中の企業番号を取得
            $company_no = $login_info['company_no'];

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            $free_word = $request->input('input_free_word');
            dslog($this->class_name, __FUNCTION__, 'm3', $free_word);

            // 画像情報一覧を取得
            islog($this->class_name, __FUNCTION__, 'm10');
            $form_data = $this->image_service->getImagePagenationList($company_no, $free_word);

            $view_param = [
                'form_data' => $form_data,
                'free_word' => $free_word,
                'login_info' => $login_info
            ];

            // ビューへレンダリング
            dslog($this->class_name, __FUNCTION__, 'm4', $view_param);
            islog($this->class_name, __FUNCTION__, 'm24');
            return view('manage.image_list', $view_param);
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Confirm a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function confirm(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // ログイン情報取得
            islog($this->class_name, __FUNCTION__, 'm28');
            $login_info = getLoginInfo($request);
            dslog($this->class_name, __FUNCTION__, 'm11', $login_info);
            // パラメータ取得
            $company_no = $login_info['company_no']; // 選択時の企業番号を取得
            $acnt_no = $login_info['acnt_no'];

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm22');
            $form_image_data = $request->file('input_image_file'); // 画像データ
            islog($this->class_name, __FUNCTION__, 'm21');
            $image_category = $request->input('ictg');
            dslog($this->class_name, __FUNCTION__, 'm3', [
                'form_image_data' => $form_image_data,
                'image_category' => $image_category
            ]);

            // アップロード画像のバリデーション開始
            islog($this->class_name, __FUNCTION__, 'm60');
            $ret_image_data = $this->image_service->validateImageData($form_image_data);
            if (empty($ret_image_data)) {
                // 画像アップロードに失敗
                // 呼び出し元へ返却
                eslog($this->class_name, __FUNCTION__, 'm12');
                islog($this->class_name, __FUNCTION__, 'm3');
                return json_encode(false);
            }
            dslog($this->class_name, __FUNCTION__, 'm3', $ret_image_data);
            // アップロード画像のバリデーション終了

            // 画像アップロード（アップロードフォルダへ移動
            islog($this->class_name, __FUNCTION__, 'm42');
            $upload_file_info = $this->image_service->uploadImageFile($ret_image_data);
            $ret = false;
            if (! empty($upload_file_info)) {
                // 画像変換
                islog($this->class_name, __FUNCTION__, 'm43');
                $ret = $this->image_service->convertImageSeq($upload_file_info);
                if (empty($ret)) {
                    // 画像変換エラー
                    eslog($this->class_name, __FUNCTION__, 'm13', $upload_file_info);
                }

                // 画像情報登録
                islog($this->class_name, __FUNCTION__, 'm18');
                $ret = $this->image_service->insertImageData($upload_file_info, $image_category, $company_no, $acnt_no);
            }

            // 呼び出し元へ返却
            if (count($form_image_data) != count($ret_image_data)) {
                // 一件でもアップロードエラー発生ならfalse
                $ret = false;
            }
            dslog($this->class_name, __FUNCTION__, 'm2', $ret);
            islog($this->class_name, __FUNCTION__, 'm3');
            return json_encode($ret);
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            islog($this->class_name, __FUNCTION__, 'm3');
            return json_encode(false);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Manage\ImageUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ImageUpdateRequest $request, $id)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // ログイン情報取得
            islog($this->class_name, __FUNCTION__, 'm28');
            $login_info = getLoginInfo($request);
            dslog($this->class_name, __FUNCTION__, 'm11', $login_info);
            // パラメータ取得
            $acnt_no = $login_info['acnt_no'];

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm22');
            $form_data = $request->all();
            dslog($this->class_name, __FUNCTION__, 'm1', $id);
            dslog($this->class_name, __FUNCTION__, 'm1', $form_data);

            // 画像情報更新
            islog($this->class_name, __FUNCTION__, 'm18');
            $ret = $this->image_service->updateImageData($form_data, $id, $acnt_no);

            // 呼び出し元へ返却
            dslog($this->class_name, __FUNCTION__, 'm2', $ret);
            islog($this->class_name, __FUNCTION__, 'm3');
            return json_encode($ret);
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Http\Requests\Manage\CommonDeleteNumRequest $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(CommonDeleteNumRequest $request)
    {
        try {
            islog($this->class_name, __FUNCTION__, 'm1');

            // ログイン情報取得
            islog($this->class_name, __FUNCTION__, 'm28');
            $login_info = getLoginInfo($request);
            dslog($this->class_name, __FUNCTION__, 'm11', $login_info);
            // パラメータ取得
            $company_no = null;
            if (checkAuthCompanyTypeCustomer($login_info['company_type'])) {
                $company_no = $login_info['company_no'];
            }

            // フォーム値を取得
            islog($this->class_name, __FUNCTION__, 'm21');
            $select_no = $request->input('select_no');
            $select_image_path = $request->input('select_image_path');
            dslog($this->class_name, __FUNCTION__, 'm3', [
                'select_no' => $select_no,
                'select_image_path' => $select_image_path
            ]);

            // 画像情報削除
            islog($this->class_name, __FUNCTION__, 'm20');
            $ret = $this->image_service->deleteImageData($company_no, $select_no, $select_image_path);
            if (empty($ret)) {
                // 削除エラー
                // 一覧画面へ戻る
                eslog($this->class_name, __FUNCTION__, 'm9');
                islog($this->class_name, __FUNCTION__, 'm29');
                return redirect()->route('image.index')->with('disp_error_message', dispErrorMessage('m3'));
            }

            // 一覧画面へ戻る
            islog($this->class_name, __FUNCTION__, 'm32');
            islog($this->class_name, __FUNCTION__, 'm29');
            return redirect()->route('image.index')->with('disp_message', dispInfoMessage('m3'));
        } catch (Exception $e) {
            // 例外処理
            eslog($this->class_name, __FUNCTION__, 'm5', $e->getMessage());
            abort(500, dispExceptionMessage(500));
        }
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     */
    public function delete(Request $request)
    {
        //
    }
}
