@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<div class="w-75 p-3">
    <h2>支店参照</h2>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">番号</div>
        <div class="col-xl-10 p-2">{{$form_data['input_no']}}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">名称</div>
        <div class="col-xl-10 p-2">{{$form_data['input_name']}}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">郵便番号</div>
        <div class="col-xl-10 p-2">{{$form_data['input_zip']}}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">住所 - 都道府県</div>
        <div class="col-xl-10 p-2">{{$form_data['input_pref']}}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">住所 - 市区町村</div>
        <div class="col-xl-10 p-2">{{$form_data['input_city']}}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">住所 - 町・丁目・番地</div>
        <div class="col-xl-10 p-2">{{$form_data['input_town']}}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">住所 - その他</div>
        <div class="col-xl-10 p-2">{{$form_data['input_address']}}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">並び順</div>
        <div class="col-xl-10 p-2">{{$form_data['input_order']}}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">協賛</div>
        <div class="col-xl-10 p-2">{{getConstsType('branch_support',$form_data['input_suppoter'])}}</div>
    </div>
    <a type="button" class="btn btn-primary" href="{{route('company_branch.index')}}">戻る</a>
{{-- アドミニストレータ権限または支店管理者権限なら出力 --}}
@if (checkAuthAcntAdmin($login_info['acnt_auth_no']) || (checkAuthAcntBranch($login_info['acnt_auth_no']) && ($login_info['company_branch_no'] == $form_data['input_no'])))
    <a type="button" class="btn btn-secondary" href="{{route('company_branch.edit',$form_data['input_no'])}}">編集</a>
@endif
</div>
@endsection
@include('manage.common.footer')