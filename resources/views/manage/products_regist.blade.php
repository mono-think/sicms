@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<div class="w-75 p-3">
@if (url()->current() == route('products.create'))
    <h2>製品登録</h2>
@else
    <h2>製品編集</h2>
@endif
    <form id="products_regist_form" class="needs-validation" novalidate method="post" action="{{ route('products.confirm') }}">
        {{ csrf_field() }}
        <input type="hidden" id="check_no" value="{{route('products.checkno')}}">
        <input type="hidden" id="js_message1" value="{{dispErrorMessage('m9')}}">
        <div class="input-group">
            <div class="input-group-prepend">
                <label class="input-group-text" for="input_ct">対象種別</label>
            </div>
            <select class="custom-select" name="input_ct" id="input_ct" aria-describedby="input_ct" required>
                <option value="">選択なし</option>
@foreach(getConstsType('customer') as $customer_no=>$customer_name)
                <option value="{{$customer_no}}" @if ((string)outputValue($form_data,'input_ct') === (string)$customer_no) selected @endif>{{$customer_name}}</option>
@endforeach
            </select>
            <div class="invalid-feedback">選択されていません。</div>
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <label class="input-group-text" for="input_st">サービス種別</label>
            </div>
            <select class="custom-select" name="input_st" id="input_st" aria-describedby="input_st" required>
                <option value="">選択なし</option>
@foreach(getConstsType('service') as $service_no=>$service_name)
                <option value="{{$service_no}}" @if ((string)outputValue($form_data,'input_st') === (string)$service_no) selected @endif>{{$service_name}}</option>
@endforeach
            </select>
            <div class="invalid-feedback">選択されていません。</div>
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <label class="input-group-text" for="input_ctg_no">カテゴリ</label>
            </div>
            <input type="hidden" name="input_ctg_name" id="input_ctg_name" value="{{outputValue($form_data,'input_ctg_name')}}">
            <select class="custom-select" name="input_ctg_no" id="input_ctg_no" aria-describedby="input_ctg_no" required>
                <option value="">選択なし</option>
@foreach($products_category_list as $category_data)
                <option value="{{$category_data->products_category_no}}" @if (outputValue($form_data,'input_ctg_no') === $category_data->products_category_no) selected @endif>{{$category_data->products_category_name}}</option>
@endforeach
            </select>
            <div class="invalid-feedback">選択されていません。</div>
        </div>
@if (url()->current() == route('products.create'))
@include('manage.common.number_regist_parts', ['name' => 'input_no','text' => '番号', 'length' => 10 ,'required' => 'required'])
@else
        <input type="hidden" name="input_no" value="{{outputValue($form_data,'input_no')}}">
@endif
@include('manage.common.text_regist_parts', ['name' => 'input_name','text' => '名称', 'length' => 64 ,'required' => 'required'])
@include('manage.common.branch_regist_parts')
@include('manage.common.department_regist_parts')
@include('manage.common.acnt_regist_parts')
@include('manage.common.release_regist_parts')
        <div class="form-check p-3">
            <input type="checkbox" class="form-check-input" id="regist_check">
            <label class="form-check-label" for="regist_check">上記入力内容で了承</label>
        </div>
        <button type="submit" class="btn btn-primary" id="regist_button" disabled>入力確認</button>
    </form>
</div>
@endsection
@include('manage.common.branch_modal')
@include('manage.common.department_modal')
@include('manage.common.acnt_modal')
@include('manage.common.footer')
@include('manage.products_regist_js')
