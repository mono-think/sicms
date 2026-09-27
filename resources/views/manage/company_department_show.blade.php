@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<div class="w-75 p-3">
    <h2>部署確認</h2>
    <input type="hidden" name="bno" id="input_cpb_no" value="{{$form_data['input_cpb_no']}}">
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">番号</div>
        <div class="col-xl-10 p-2">{{$form_data['input_no']}}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">名称</div>
        <div class="col-xl-10 p-2">{{$form_data['input_name']}}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">電話番号</div>
        <div class="col-xl-10 p-2">{{$form_data['input_tel']}}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">FAX番号</div>
        <div class="col-xl-10 p-2">{{$form_data['input_fax']}}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">メールアドレス</div>
        <div class="col-xl-10 p-2">{{$form_data['input_email']}}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">並び順</div>
        <div class="col-xl-10 p-2">{{$form_data['input_order']}}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">公開フラグ</div>
        <div class="col-xl-10 p-2">{{dispPublic(isset($form_data['input_public'])?$form_data['input_public']:0)}}</div>
    </div>
@foreach ($form_data['input_snsno'] as $select_idx=>$select_sns_no_data) 
@if (!is_null($form_data['input_sns'][$select_idx]))
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">{{getConstsType('sns', $select_sns_no_data)}}</div>
        <div class="col-xl-10 p-2">{{$form_data['input_sns'][$select_idx]}}</div>
    </div>
@endif
@endforeach
    <a type="button" class="btn btn-primary" href="{{route('company_department.index')}}">戻る</a>
{{-- アドミニストレータまたは支店管理者または部署管理者で所属部署なら出力 --}}
@if (checkAuthAcntAdmin($login_info['acnt_auth_no']) || checkAuthAcntBranch($login_info['acnt_auth_no']) || (checkAuthAcntDepartment($login_info['acnt_auth_no']) && ($login_info['company_department_no'] == $form_data['input_no'])))
    <a type="button" class="btn btn-secondary" href="{{route('company_department.edit',$form_data['input_no'])}}">編集</a>
@endif
</div>
@endsection
@include('manage.common.footer')