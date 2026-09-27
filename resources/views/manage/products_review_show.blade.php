@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<div class="w-75 p-3">
    <h2>製品詳細レビュー参照</h2>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">番号</div>
        <div class="col-xl-10 p-2">{{$form_data['input_no']}}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">名称</div>
        <div class="col-xl-10 p-2">{{$form_data['input_name']}}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">詳細</div>
        <div class="col-xl-10 p-2">{!! insertPtag($form_data['input_text']) !!}</div>
    </div>
    <a type="button" class="btn btn-primary" href="{{route('products_review.index')}}">戻る</a>
{{-- 閲覧者以外出力 --}}
@if (!checkAuthAcntViewer($login_info['acnt_auth_no']))
    <a type="button" class="btn btn-secondary" href="{{route('products_review.edit',$form_data['input_no'])}}">編集</a>
@endif
</div>
@endsection
@include('manage.common.footer')