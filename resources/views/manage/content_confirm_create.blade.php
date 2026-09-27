@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<form id="content_confirm_form" method="post" action="{{ route('content.store') }}">
    {{ csrf_field() }}
    <div class="w-75 p-3">
        <h2>コンテンツ登録確認</h2>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">種別</div>
            <div class="col-xl-10 p-2">{{getConstsType('content', $form_data['input_type'])}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">名称</div>
            <div class="col-xl-10 p-2">{{$form_data['input_name']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">画像</div>
            <div class="col-xl-10 p-2">{{outputValue($form_data,'input_image_name')}}</div>
        </div>
        <p id="image_preview"><img src="{{outputValue($form_data,'input_image_file')}}" width="400" /></p>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">紹介</div>
            <div class="col-xl-10 p-2">{{$form_data['input_description']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">詳細</div>
            <div class="col-xl-10 p-2">{!! insertPtag($form_data['input_text']) !!}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">タグ</div>
            <div class="col-xl-10 p-2">{{$form_data['input_search_tag']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">掲載開始日</div>
            <div class="col-xl-10 p-2">{{convertDateType($form_data['input_release_start'],'a')}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">掲載終了日</div>
            <div class="col-xl-10 p-2">{{convertDateType($form_data['input_release_end'],'a')}}</div>
        </div>
        <a type="button" class="btn btn-primary" href="{{route('content.create')}}">戻る</a>
        <button type="submit" class="btn btn-secondary">登録</button>
    </div>
</form>
@endsection
@include('manage.common.footer')