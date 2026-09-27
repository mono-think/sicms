@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<form id="products_confirm_form" method="post" action="{{ route('products.store') }}">
    {{ csrf_field() }}
    <div class="w-75 p-3">
        <h2>製品登録確認</h2>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">対象種別</div>
            <div class="col-xl-10 p-2">{{getConstsType('customer', $form_data['input_ct'])}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">サービス種別</div>
            <div class="col-xl-10 p-2">{{getConstsType('service', $form_data['input_st'])}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">製品カテゴリ</div>
            <div class="col-xl-10 p-2">{{$form_data['input_ctg_name']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">番号</div>
            <div class="col-xl-10 p-2">{{$form_data['input_no']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">名称</div>
            <div class="col-xl-10 p-2">{{$form_data['input_name']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">支店</div>
            <div class="col-xl-10 p-2">{{$form_data['input_cpb_name']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">部署</div>
            <div class="col-xl-10 p-2">{{$form_data['input_cpd_name']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">担当</div>
            <div class="col-xl-10 p-2">{{$form_data['input_acnt_name']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">掲載開始日</div>
            <div class="col-xl-10 p-2">{{convertDateType($form_data['input_release_start'],'a')}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">掲載終了日</div>
            <div class="col-xl-10 p-2">{{convertDateType($form_data['input_release_end'],'a')}}</div>
        </div>
        <a type="button" class="btn btn-primary" href="{{route('products.create')}}">戻る</a>
        <button type="submit" class="btn btn-secondary">登録</button>
    </div>
</form>
@endsection
@include('manage.common.footer')
