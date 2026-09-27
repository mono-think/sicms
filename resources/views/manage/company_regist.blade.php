@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<div class="w-75 p-3">
@if (url()->current() == route('company.create'))
    <h2>企業登録</h2>
@else
    <h2>企業編集</h2>
@endif
    <form id="company_regist_form" class="needs-validation" novalidate method="post" action="{{ route('company.confirm') }}">
        {{ csrf_field() }}
        <input type="hidden" id="check_no" value="{{route('company.checkno')}}">
        <input type="hidden" id="js_message1" value="{{dispErrorMessage('m9')}}">
@if (checkAuthCompanyTypeDefault($login_info['company_type']))
        <small class="text-muted">※初期アカウントからでは、全て{{getConstsType('company',getAuthCompanyTypeAdmin())}}企業として登録、更新されます。</small>
        <input type="hidden" name="input_cptype" id="input_cptype" value="{{getAuthCompanyTypeAdmin()}}">
@else
        <input type="hidden" name="input_cptype" id="input_cptype" value="{{getAuthCompanyTypeCustomer()}}">
@endif
@if (url()->current() == route('company.create'))
@include('manage.common.number_regist_parts', ['name' => 'input_no','text' => '番号', 'length' => 10 ,'required' => 'required'])
@else
        <input type="hidden" name="input_no" value="{{outputValue($form_data,'input_no')}}">
@endif
<!-- 【MySQL暫定対応】 -->
@include('manage.common.text_regist_parts', ['name' => 'input_name','text' => '名称', 'length' => 191 ,'required' => 'required'])
        <div class="input-group">
            <div class="input-group-prepend">
                <label class="input-group-text" for="input_bfno">業態</label>
            </div>
            <input type="hidden" name="input_bfname" id="input_bfname" value="{{outputValue($form_data,'input_bfname')}}">
            <select class="custom-select" name="input_bfno" id="input_bfno" aria-describedby="input_bfno">
            <option value="">選択なし</option>
@foreach($m_bformat as $bf)
            <option value="{{$bf->bformat_no}}" @if (outputValue($form_data,'input_bfno') == $bf->bformat_no) selected @endif>{{$bf->bformat_name}}</option>
@endforeach
            </select>
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <label class="input-group-text" for="input_indno">業種</label>
            </div>
            <input type="hidden" name="input_indname" id="input_indname" value="{{outputValue($form_data,'input_indname')}}">
            <select class="custom-select" name="input_indno" id="input_indno" aria-describedby="input_indno">
            <option value="">選択なし</option>
@foreach($m_industry as $ind)
            <option value="{{$ind->industry_no}}" @if (outputValue($form_data,'input_indno') == $ind->industry_no) selected @endif>{{$ind->industry_name}}</option>
@endforeach
            </select>
        </div>
@if (url()->current() != route('company.create'))
@include('manage.common.detailtext_regist_parts', ['name' => 'input_text','text' => '詳細', 'length' => 30720 ,'required' => ''])
@include('manage.common.image_regist_parts')
@include('manage.common.text_regist_parts', ['name' => 'input_title','text' => 'ページタイトル', 'length' => 255 ,'required' => ''])
@endif
<!-- 【MySQL暫定対応】 -->
@include('manage.common.tag_regist_parts', ['name' => 'input_search_tag','text' => 'タグ', 'length' => 191 ,'required' => ''])
@include('manage.common.text_regist_parts', ['name' => 'input_url','text' => 'URL', 'length' => 255 ,'required' => ''])
       <div class="form-check p-3">
            <input type="checkbox" class="form-check-input" id="regist_check">
            <label class="form-check-label" for="regist_check">上記入力内容で了承</label>
        </div>
        <button type="submit" class="btn btn-primary" id="regist_button" disabled>入力確認</button>
    </form>
</div>
@endsection
@if (url()->current() != route('company.create'))
@include('manage.common.regist_modal')
@include('manage.common.image_modal')
@endif
@include('manage.common.footer')
@include('manage.company_regist_js')
