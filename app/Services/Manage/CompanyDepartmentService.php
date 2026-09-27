<?php
/**
 * 企業部署情報処理のServiceクラス
 * 検索、登録、更新、削除処理
 *
 * @access public
 * @author Seiji Ishikawa（メールアドレス）
 * @copyright Webじゃ軒 All Rights Reserved
 * @package service
 */
namespace App\Services\Manage;

use App\Repositories\Manage\CompanyDepartmentRepositoryInterface;
use Carbon\Carbon;

class CompanyDepartmentService
{

    protected $company_department_repository;

    protected $class_name;

    /**
     * コンストラクタ
     *
     * @access public
     * @param CompanyDepartmentRepositoryInterface $company_department_repository
     *
     */
    public function __construct(CompanyDepartmentRepositoryInterface $company_department_repository)
    {
        $this->company_department_repository = $company_department_repository;
        $this->class_name = get_class($this);
        islog($this->class_name, __FUNCTION__);
    }

    /**
     * 企業部署情報ページネーション一覧取得
     * 検索条件により全ての企業部署情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $free_word
     * @return \Illuminate\Database\Eloquent\Collection
     * @see CompanyDepartmentService
     * @throws
     * @todo
     */
    public function getCompanyDepartmentPagenationList($company_no, $company_branch_no, $free_word)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'free_word' => $free_word
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $free_word = hscQuotes($free_word); //HTMLエンティティ変換
        $ret = $this->company_department_repository->getCompanyDepartmentPagenationList($company_no, $company_branch_no, $free_word);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 企業部署情報取得
     * 企業部署番号により企業部署情報を取得
     *
     * @access public
     * @param string $company_no
     * @param string $company_branch_no
     * @param int $company_department_no
     * @param int $delete_flg
     * @return array
     * @see CompanyDepartmentService
     * @throws
     * @todo
     */
    public function getCompanyDepartmentData($company_no, $company_branch_no, $company_department_no, $delete_flg = 0)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'company_department_no' => $company_department_no,
            'delete_flg' => $delete_flg
        ]);

        // データ取得
        islog($this->class_name, __FUNCTION__, 'm10');
        $ret = $this->company_department_repository->getCompanyDepartmentData($company_no, $company_branch_no, $company_department_no, $delete_flg);
        $form_data = [];
        if (! empty($ret)) {
            // 配列化
            $form_data['input_no'] = $ret->comp_department_no; // 企業部署番号
            $form_data['input_cpb_no'] = $ret->comp_branch_no; // 企業支店管理番号
            $form_data['input_name'] = $ret->comp_department_name; // 部署名
            $form_data['input_order'] = $ret->comp_department_order; // 部署順
            $form_data['input_tel'] = $ret->comp_department_tel; // 電話番号
            $form_data['input_fax'] = $ret->comp_department_fax; // FAX番号
            $form_data['input_email'] = $ret->comp_department_mail; // Eメール
            $form_data['input_public'] = $ret->comp_department_public_flg; // 公開フラグ
            $form_data['input_snsno'] = []; // SNS種別
            $form_data['input_sns'] = []; // SNS URL
            if (! empty($ret->sns_url1)) {
                $form_data['input_snsno'][] = $ret->sns_no1; // SNS種別1
                $form_data['input_sns'][] = $ret->sns_url1; // SNS URL1
            }
            if (! empty($ret->sns_url2)) {
                $form_data['input_snsno'][] = $ret->sns_no2; // SNS種別2
                $form_data['input_sns'][] = $ret->sns_url2; // SNS URL2
            }
            if (! empty($ret->sns_url3)) {
                $form_data['input_snsno'][] = $ret->sns_no3; // SNS種別3
                $form_data['input_sns'][] = $ret->sns_url3; // SNS URL3
            }
            if (! empty($ret->sns_url4)) {
                $form_data['input_snsno'][] = $ret->sns_no4; // SNS種別4
                $form_data['input_sns'][] = $ret->sns_url4; // SNS URL4
            }
            if (! empty($ret->sns_url5)) {
                $form_data['input_snsno'][] = $ret->sns_no5; // SNS種別5
                $form_data['input_sns'][] = $ret->sns_url5; // SNS URL5
            }
        }

        dslog($this->class_name, __FUNCTION__, 'm2', $form_data);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $form_data;
    }

    /**
     * 企業部署情報登録
     * 入力した企業部署情報を登録
     *
     * @access public
     * @param array $form_data
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $acnt_no
     * @return Boolean
     * @see CompanyDepartmentService
     * @throws
     * @todo
     */
    public function insertCompanyDepartmentData($form_data, $company_no, $company_branch_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'acnt_no' => $acnt_no
        ]);

        // 新規登録
        // 部署登録データを設定
        islog($this->class_name, __FUNCTION__, 'm14');
        $insert_data['comp_department_no'] = $form_data['input_no']; // 企業部署番号
        $insert_data['comp_no'] = $company_no; // 企業番号
        $insert_data['comp_branch_no'] = $company_branch_no; // 企業支店番号
        $insert_data['comp_department_name'] = $form_data['input_name']; // 部署名
        $insert_data['comp_department_order'] = (int)$form_data['input_order']; // 部署順
        $insert_data['comp_department_tel'] = $form_data['input_tel']; // 電話番号
        $insert_data['comp_department_fax'] = $form_data['input_fax']; // FAX番号
        $insert_data['comp_department_mail'] = $form_data['input_email']; // Eメール
        $insert_data['comp_department_public_flg'] = (int)isset($form_data['input_public']) ? 1 : 0; // 公開フラグ
        $insert_data['created_at'] = Carbon::now(); // 登録日
        $insert_data['updated_at'] = Carbon::now(); // 更新日
        $insert_data['last_acnt_no'] = $acnt_no; // 最終更新者NO

        // 有効なSNSだけ設定
        foreach ($form_data['input_snsno'] as $sns_idx => $sns_type) {
            $db_idx = $sns_idx + 1;
            if (isset($form_data['input_sns'][$sns_idx]) && ! empty($form_data['input_sns'][$sns_idx])) {
                $sns_mode_label = 'sns_no' . $db_idx;
                $sns_data_label = 'sns_url' . $db_idx;

                $insert_data[$sns_mode_label] = $sns_type; // SNS種別
                $insert_data[$sns_data_label] = $form_data['input_sns'][$sns_idx]; // SNS URL
                $db_idx ++;
            }
        }
        dslog($this->class_name, __FUNCTION__, 'm13', $insert_data);

        islog($this->class_name, __FUNCTION__, 'm18');
        $ret = $this->company_department_repository->insertCompanyDepartmentData($insert_data);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 企業部署情報更新
     * 入力した企業部署情報を更新
     *
     * @access public
     * @param array $form_data
     * @param string $input_no
     * @param string $company_no
     * @param string $company_branch_no
     * @param string $acnt_no
     * @return Boolean
     * @see CompanyDepartmentService
     * @throws
     * @todo
     */
    public function updateCompanyDepartmentData($form_data, $input_no, $company_no, $company_branch_no, $acnt_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'form_data' => $form_data,
            'input_no' => $input_no,
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'acnt_no' => $acnt_no
        ]);

        // 既存更新
        // 更新データを設定
        islog($this->class_name, __FUNCTION__, 'm15');
        $update_data['comp_department_name'] = $form_data['input_name']; // 部署名
        $update_data['comp_department_order'] = (int)$form_data['input_order']; // 部署順
        $update_data['comp_department_tel'] = $form_data['input_tel']; // 電話番号
        $update_data['comp_department_fax'] = $form_data['input_fax']; // FAX番号
        $update_data['comp_department_mail'] = $form_data['input_email']; // Eメール
        $update_data['comp_department_public_flg'] = (int)isset($form_data['input_public']) ? 1 : 0; // 公開フラグ
        $update_data['updated_at'] = Carbon::now(); // 更新日
        $update_data['last_acnt_no'] = $acnt_no; // 最終更新者NO

        // 有効なSNSだけ設定
        $max_sns = count(getConstsType('sns'));
        for ($idx = 1; $idx <= $max_sns; $idx ++) {
            $sns_mode_label = 'sns_no' . $idx;
            $sns_data_label = 'sns_url' . $idx;
            $form_idx = $idx - 1;
            if (isset($form_data['input_sns'][$form_idx]) && ! empty($form_data['input_sns'][$form_idx])) {
                //SNS種別判定
                $update_data[$sns_mode_label] = $form_data['input_snsno'][$form_idx]; // SNS種別
                $update_data[$sns_data_label] = $form_data['input_sns'][$form_idx]; // SNS URL
            } else {
                $update_data[$sns_mode_label] = null;
                $update_data[$sns_data_label] = null; // SNS URL
            }
        }
        dslog($this->class_name, __FUNCTION__, 'm14', $update_data);

        islog($this->class_name, __FUNCTION__, 'm19');
        $ret = $this->company_department_repository->updateCompanyDepartmentData($update_data, $company_no, $company_branch_no, $input_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * 企業部署情報削除
     * 選択した企業部署情報を削除
     *
     * @access public
     * @param array $login_data
     * @param string $company_department_no
     * @return Boolean
     * @see CompanyDepartmentService
     * @throws
     * @todo
     */
    public function deleteCompanyDepartmentData($company_no, $company_branch_no, $acnt_no, $company_department_no)
    {
        islog($this->class_name, __FUNCTION__, 'm1');
        dslog($this->class_name, __FUNCTION__, 'm1', [
            'company_no' => $company_no,
            'company_branch_no' => $company_branch_no,
            'acnt_no' => $acnt_no,
            'company_department_no' => $company_department_no
        ]);

        // 削除開始
        islog($this->class_name, __FUNCTION__, 'm20');
        $ret = $this->company_department_repository->deleteCompanyDepartmentData($company_department_no, $company_no, $company_branch_no, $acnt_no);

        dslog($this->class_name, __FUNCTION__, 'm2', $ret);
        islog($this->class_name, __FUNCTION__, 'm3');
        return $ret;
    }

    /**
     * ログイン情報設定
     * 選択した企業部署情報をログイン情報として設定
     *
     * @access public
     * @param array $login_info
     * @param array $form_data
     * @return array
     * @see CompanyDepartmentService
     * @throws
     * @todo
     */
    public function setCompanyDepartmentLoginInfo($login_info, $form_data)
    {
        $login_info['company_department_no'] = $form_data['input_no'];
        $login_info['company_department_name'] = $form_data['input_name'];

        return $login_info;
    }
}
