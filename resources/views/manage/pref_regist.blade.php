@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<div class="w-75 p-3">
@if (url()->current() == route('pref.create'))
    <h2>都道府県マスタ登録</h2>
@else
    <h2>都道府県マスタ編集</h2>
@endif
    <form id="pref_regist_form" class="needs-validation" novalidate method="post" action="{{ route('pref.confirm') }}">
    {{ csrf_field() }}
        <input type="hidden" name="input_no" id="input_no" value="{{outputValue($form_data,'input_no')}}">
@include('manage.common.text_regist_parts', ['name' => 'input_name','text' => '名称', 'length' => 5 ,'required' => 'required'])
        <div class="form-check p-3">
            <input type="checkbox" class="form-check-input" id="regist_check">
            <label class="form-check-label" for="regist_check">上記入力内容で了承</label>
        </div>
        <button type="submit" class="btn btn-primary" id="regist_button" disabled>入力確認</button>
    </form>
</div>
@endsection
@include('manage.common.footer')
@include('manage.pref_regist_js')
