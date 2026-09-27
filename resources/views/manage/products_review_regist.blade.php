@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<div class="w-75 p-3">
@if (url()->current() == route('products_review.create'))
    <h2>製品詳細レビュー登録</h2>
@else
    <h2>製品詳細レビュー編集</h2>
@endif
    <form id="products_review_regist_form" class="needs-validation" novalidate method="post" action="{{ route('products_review.confirm') }}">
        {{ csrf_field() }}
        <input type="hidden" id="check_no" value="{{route('products_review.checkno')}}">
        <input type="hidden" id="js_message1" value="{{dispErrorMessage('m9')}}">
@if (url()->current() == route('products_review.create'))
@include('manage.common.number_regist_parts', ['name' => 'input_no','text' => '番号', 'length' => 10 ,'required' => 'required'])
@else
        <input type="hidden" name="input_no" value="{{outputValue($form_data,'input_no')}}">
@endif
<!-- 【MySQL暫定対応】 -->
@include('manage.common.text_regist_parts', ['name' => 'input_name','text' => '名称', 'length' => 191 ,'required' => 'required'])
@include('manage.common.detailtext_regist_parts', ['name' => 'input_text','text' => '詳細', 'length' => 30720 ,'required' => 'required'])
        <div class="form-check p-3">
            <input type="checkbox" class="form-check-input" id="regist_check">
            <label class="form-check-label" for="regist_check">上記入力内容で了承</label>
        </div>
        <button type="submit" class="btn btn-primary" id="regist_button" disabled>入力確認</button>
    </form>
</div>
@endsection
@include('manage.common.image_modal')
@include('manage.common.regist_modal')
@include('manage.common.footer')
@include('manage.products_review_regist_js')