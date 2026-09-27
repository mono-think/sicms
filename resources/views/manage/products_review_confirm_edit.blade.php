@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<form id="products_review_confirm_form" method="post" action="{{ route('products_review.update',$form_data['input_no']) }}">
    <input type="hidden" name="_method" value="put">
    {{ csrf_field() }}
    <div class="w-75 p-3">
        <h2>製品詳細レビュー編集確認</h2>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">名称</div>
            <div class="col-xl-10 p-2">{{$form_data['input_name']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">詳細</div>
            <div class="col-xl-10 p-2">{!! insertPtag($form_data['input_text']) !!}</div>
        </div>
        <a type="button" class="btn btn-primary" href="{{route('products_review.edit',$form_data['input_no'])}}">戻る</a>
        <button type="submit" class="btn btn-secondary">登録</button>
    </div>
</form>
@endsection
@include('manage.common.footer')