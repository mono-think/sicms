@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<div class="w-75 p-3">
    <h2>企業参照</h2>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">番号</div>
        <div class="col-xl-10 p-2">{{$form_data['input_no']}}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">名称</div>
        <div class="col-xl-10 p-2">{{$form_data['input_name']}}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">業態</div>
        <div class="col-xl-10 p-2">{{$form_data['input_bfname']}}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">業種</div>
        <div class="col-xl-10 p-2">{{$form_data['input_indname']}}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">詳細</div>
        <div class="col-xl-10 p-2">{!! insertPtag($form_data['input_text']) !!}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">画像</div>
        <div class="col-xl-10 p-2">{{outputValue($form_data,'input_image_name')}}</div>
    </div>
    <p id="image_preview"><img src="{{outputValue($form_data,'input_image_file')}}" width="400" /></p>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">ページタイトル</div>
        <div class="col-xl-10 p-2">{{$form_data['input_title']}}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">タグ</div>
        <div class="col-xl-10 p-2">{{$form_data['input_search_tag']}}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">URL</div>
        <div class="col-xl-10 p-2">{{$form_data['input_url']}}</div>
    </div>
{{-- アドミニストレータ権限のみ出力 --}}
@if (checkAuthAcntAdmin($login_info['acnt_auth_no']))
    <a type="button" class="btn btn-primary" href="{{route('company.index')}}">戻る</a>
    <a type="button" class="btn btn-secondary" href="{{route('company.edit',$form_data['input_no'])}}">編集</a>
@endif
</div>
@endsection
@include('manage.common.footer')