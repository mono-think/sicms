@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<div class="w-75 p-3">
@if (url()->current() == route('position.create'))
    <h2>役職マスタ登録</h2>
@else
    <h2>役職マスタ編集</h2>
@endif
    <form id="position_regist_form" class="needs-validation" novalidate method="post" action="{{ route('position.confirm') }}">
    {{ csrf_field() }}
        <input type="hidden" name="input_no" id="input_no" value="{{outputValue($form_data,'input_no')}}">
@include('manage.common.text_regist_parts', ['name' => 'input_name','text' => '名称', 'length' => 32 ,'required' => 'required'])
        <div class="form-check p-3">
            <input type="checkbox" class="form-check-input" id="regist_check">
            <label class="form-check-label" for="regist_check">上記入力内容で了承</label>
        </div>
        <button type="submit" class="btn btn-primary" id="regist_button" disabled>入力確認</button>
    </form>
</div>
@endsection
@include('manage.common.footer')
@include('manage.position_regist_js')
