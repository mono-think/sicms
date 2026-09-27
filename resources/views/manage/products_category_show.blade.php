@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<div class="w-75 p-3">
    <h2>製品カテゴリ参照</h2>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">番号</div>
        <div class="col-xl-10 p-2">{{$form_data['input_no']}}</div>
    </div>
    <div class="row border bg-light rounded p-1 mb-2">
        <div class="col-xl-2 p-2 border-right">名称</div>
        <div class="col-xl-10 p-2">{{$form_data['input_name']}}</div>
    </div>
    <a type="button" class="btn btn-primary" href="{{route('products_category.index')}}">戻る</a>
    <a type="button" class="btn btn-secondary" href="{{route('products_category.edit',$form_data['input_no'])}}">編集</a>
</div>
@endsection
@include('manage.common.footer')