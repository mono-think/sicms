@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<form id="company_branch_news_confirm_form" method="post" action="{{ route('company_branch_news.store') }}">
    {{ csrf_field() }}
    <div class="w-75 p-3">
        <h2>支店ニュース登録確認</h2>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">名称</div>
            <div class="col-xl-10 p-2">{{$form_data['input_name']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">詳細</div>
            <div class="col-xl-10 p-2">{!! insertPtag($form_data['input_text']) !!}</div>
        </div>
        <a type="button" class="btn btn-primary" href="{{route('company_branch_news.create')}}">戻る</a>
        <button type="submit" class="btn btn-secondary">登録</button>
    </div>
</form>
@endsection
@include('manage.common.footer')
