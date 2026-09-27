@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<div class="w-75 p-3">
@if (url()->current() == route('content.create'))
    <h2>コンテンツ登録</h2>
@else
    <h2>コンテンツ編集</h2>
@endif
    <form id="content_regist_form" class="needs-validation" novalidate method="post" action="{{ route('content.confirm') }}">
        {{ csrf_field() }}
        <input type="hidden" name="input_no" id="input_no" value="{{outputValue($form_data,'input_no')}}">
        <div class="input-group">
            <div class="input-group-prepend">
                <label class="input-group-text" for="input_type">種別</label>
            </div>
            <select class="custom-select" name="input_type" id="input_type" aria-describedby="input_type" required>
                <option value="">選択なし</option>
@foreach(getConstsType('content') as $content_no=>$content_name)
                <option value="{{$content_no}}" @if (!strcmp(outputValue($form_data,'input_type'), $content_no)) selected @endif>{{$content_name}}</option>
@endforeach
            </select>
            <div class="invalid-feedback">選択されていません。</div>
        </div>
<!-- 【MySQL暫定対応】 -->
@include('manage.common.text_regist_parts', ['name' => 'input_name','text' => '名称', 'length' => 191 ,'required' => 'required'])
@include('manage.common.image_regist_parts')
<!-- 【MySQL暫定対応】 -->
@include('manage.common.description_regist_parts', ['name' => 'input_description','text' => '紹介', 'length' => 191 ,'required' => 'required'])
@include('manage.common.detailtext_regist_parts', ['name' => 'input_text','text' => '詳細', 'length' => 30720 ,'required' => 'required'])
<!-- 【MySQL暫定対応】 -->
@include('manage.common.tag_regist_parts', ['name' => 'input_search_tag','text' => 'タグ', 'length' => 191 ,'required' => ''])
@include('manage.common.release_regist_parts')
        <div class="form-check p-3">
            <input type="checkbox" class="form-check-input" id="regist_check">
            <label class="form-check-label" for="regist_check">上記入力内容で了承</label>
        </div>
        <button type="submit" class="btn btn-primary" id="regist_button" disabled>入力確認</button>
    </form>
</div>
@endsection
@include('manage.common.regist_modal')
@include('manage.common.image_modal')
@include('manage.common.footer')
@include('manage.content_regist_js')