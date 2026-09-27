@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<form id="company_department_confirm_form" method="post" action="{{ route('company_department.update', $form_data['input_no']) }}">
    <input type="hidden" name="_method" value="put">
    {{ csrf_field() }}
    <div class="w-75 p-3">
        <h2>部署編集確認</h2>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">番号</div>
            <div class="col-xl-10 p-2">{{$form_data['input_no']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">名称</div>
            <div class="col-xl-10 p-2">{{$form_data['input_name']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">電話番号</div>
            <div class="col-xl-10 p-2">{{$form_data['input_tel']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">FAX番号</div>
            <div class="col-xl-10 p-2">{{$form_data['input_fax']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">メールアドレス</div>
            <div class="col-xl-10 p-2">{{$form_data['input_email']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">並び順</div>
            <div class="col-xl-10 p-2">{{$form_data['input_order']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">公開フラグ</div>
            <div class="col-xl-10 p-2">{{dispPublic(isset($form_data['input_public'])?$form_data['input_public']:0)}}</div>
        </div>
@if (isset($form_data['input_snsno']))
@foreach ($form_data['input_snsno'] as $select_idx=>$select_sns_no_data) 
@if (isset($form_data['input_sns'][$select_idx]) && !empty($form_data['input_sns'][$select_idx]))
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">{{getConstsType('sns', $select_sns_no_data)}}</div>
            <div class="col-xl-10 p-2">{{$form_data['input_sns'][$select_idx]}}</div>
        </div>
@endif
@endforeach
@endif
        <a type="button" class="btn btn-primary" href="{{route('company_department.edit', $form_data['input_no'])}}">戻る</a>
        <button type="submit" class="btn btn-secondary">登録</button>
    </div>
</form>
@endsection
@include('manage.common.footer')