@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<form id="company_branch_confirm_form" method="post" action="{{ route('company_branch.store') }}">
    {{ csrf_field() }}
    <div class="w-75 p-3">
        <h2>支店登録確認</h2>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">番号</div>
            <div class="col-xl-10 p-2">{{$form_data['input_no']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">名称</div>
            <div class="col-xl-10 p-2">{{$form_data['input_name']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">郵便番号</div>
            <div class="col-xl-10 p-2">{{$form_data['input_zip']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">住所 - 都道府県</div>
            <div class="col-xl-10 p-2">{{$form_data['input_pref']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">住所 - 市区町村</div>
            <div class="col-xl-10 p-2">{{$form_data['input_city']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">住所 - 町・丁目・番地</div>
            <div class="col-xl-10 p-2">{{$form_data['input_town']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">住所 - その他</div>
            <div class="col-xl-10 p-2">{{$form_data['input_address']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">並び順</div>
            <div class="col-xl-10 p-2">{{$form_data['input_order']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">協賛</div>
            <div class="col-xl-10 p-2">{{getConstsType('branch_support',$form_data['input_suppoter'])}}</div>
        </div>
        <a type="button" class="btn btn-primary" href="{{route('company_branch.create')}}">戻る</a>
        <button type="submit" class="btn btn-secondary">登録</button>
    </div>
</form>
@endsection
@include('manage.common.footer')
