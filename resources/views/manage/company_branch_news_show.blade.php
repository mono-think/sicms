@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<div class="w-75 p-3">
    <h2>支店ニュース参照</h2>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">名称</div>
        <div class="col-xl-10 p-2">{{$form_data['input_name']}}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">詳細</div>
        <div class="col-xl-10 p-2">{!! insertPtag($form_data['input_text']) !!}</div>
    </div>
    <a type="button" class="btn btn-primary" href="{{route('company_branch_news.index')}}">戻る</a>
{{-- アドミニストレータ権限または支店管理者権限なら出力 --}}
@if ((checkAuthAcntAdmin($login_info['acnt_auth_no'])) || (checkAuthAcntBranch($login_info['acnt_auth_no'])))
    <a type="button" class="btn btn-secondary" href="{{route('company_branch_news.edit',$form_data['input_no'])}}">編集</a>
@endif
</div>
@endsection
@include('manage.common.footer')