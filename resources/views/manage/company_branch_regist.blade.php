@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<div class="w-75 p-3">
@if (url()->current() == route('company_branch.create'))
    <h2>支店登録</h2>
@else
    <h2>支店編集</h2>
@endif
    <form id="company_branch_regist_form" class="needs-validation" novalidate method="post" action="{{ route('company_branch.confirm') }}">
        {{ csrf_field() }}
        <input type="hidden" id="check_no" value="{{route('company_branch.checkno')}}">
        <input type="hidden" id="js_message1" value="{{dispErrorMessage('m9')}}">
@if (url()->current() == route('company_branch.create'))
@include('manage.common.number_regist_parts', ['name' => 'input_no','text' => '番号', 'length' => 10 ,'required' => 'required'])
@else
        <input type="hidden" name="input_no" value="{{outputValue($form_data,'input_no')}}">
@endif
<!-- 【MySQL暫定対応】 -->
@include('manage.common.text_regist_parts', ['name' => 'input_name','text' => '名称', 'length' => 191 ,'required' => 'required'])
@include('manage.common.zip_regist_parts')
@include('manage.common.pref_regist_parts')
@include('manage.common.text_regist_parts', ['name' => 'input_city','text' => '住所 - 市区町村', 'length' => 20 ,'required' => ''])
@include('manage.common.text_regist_parts', ['name' => 'input_town','text' => '住所 - 町・丁目・番地', 'length' => 20 ,'required' => ''])
@include('manage.common.text_regist_parts', ['name' => 'input_address','text' => '住所 - その他', 'length' => 20 ,'required' => ''])
@include('manage.common.order_regist_parts', ['name' => 'input_order','text' => '並び順', 'length' => 3 ,'required' => 'required'])
        <div class="input-group">
            <div class="input-group-prepend">
                <label class="input-group-text" for="input_suppoter">協賛</label>
            </div>
            <select class="custom-select" name="input_suppoter" id="input_suppoter" aria-describedby="input_suppoter">
@foreach(getConstsType('branch_support') as $support_no=>$support_name)
                <option value="{{$support_no}}" @if (outputValue($form_data,'input_suppoter') == $support_no) selected @endif>{{$support_name}}</option>
@endforeach
            </select>
        </div>
        <div class="form-check p-3">
            <input type="checkbox" class="form-check-input" id="regist_check">
            <label class="form-check-label" for="regist_check">上記入力内容で了承</label>
        </div>
      <button type="submit" class="btn btn-primary" id="regist_button" disabled>入力確認</button>
    </form>
</div>
@endsection
@include('manage.common.footer')
@include('manage.company_branch_regist_js')