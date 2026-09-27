@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<div class="w-75 p-3">
@if (url()->current() == route('event.create'))
    <h2>イベント登録</h2>
@else
    <h2>イベント編集</h2>
@endif
    <form id="event_regist_form" class="needs-validation" novalidate method="post" action="{{ route('event.confirm') }}">
        {{ csrf_field() }}
        <input type="hidden" name="input_no" id="input_no" value="{{outputValue($form_data,'input_no')}}">
<!-- 【MySQL暫定対応】 -->
@include('manage.common.text_regist_parts', ['name' => 'input_name','text' => '名称', 'length' => 191 ,'required' => 'required'])
@include('manage.common.image_regist_parts')
<!-- 【MySQL暫定対応】 -->
@include('manage.common.description_regist_parts', ['name' => 'input_description','text' => '紹介', 'length' => 191 ,'required' => 'required'])
        <div class="input-group">
            <div class="input-group-prepend">
                <label class="input-group-text" for="input_date">開催日</label>
            </div>
            <input type="date" class="form-control" name="input_date" id="input_date" placeholder="開催日" maxlength="10" aria-describedby="input_date" value="{{outputValue($form_data,'input_date')}}" required>
            <div class="invalid-feedback">入力必須です。</div>
        </div>
@include('manage.common.text_regist_parts', ['name' => 'input_stage','text' => '開催場所', 'length' => 64 ,'required' => 'required'])
        <div class="input-group">
            <div class="input-group-prepend">
                <label class="input-group-text" for="input_weather">お天気</label>
            </div>
            <select class="custom-select" name="input_weather" id="input_weather" aria-describedby="input_weather" required>
                <option value="">選択なし</option>
@foreach(getConstsType('weather') as $weather_no=>$weather_name)
                <option value="{{$weather_no}}" @if (!strcmp(outputValue($form_data,'input_weather'),$weather_no)) selected @endif>{{$weather_name}}</option>
@endforeach
            </select>
            <div class="invalid-feedback">選択されていません。</div>
        </div>
@include('manage.common.text_regist_parts', ['name' => 'input_organizer','text' => '主催者', 'length' => 64 ,'required' => 'required'])
@include('manage.common.detailtext_regist_parts', ['name' => 'input_text','text' => '詳細', 'length' => 15360 ,'required' => 'required'])
<!-- 【MySQL暫定対応】 -->
@include('manage.common.tag_regist_parts', ['name' => 'input_search_tag','text' => 'タグ', 'length' => 191 ,'required' => ''])
@include('manage.common.release_regist_parts')
        <div class="form-check p-3">
            <input type="checkbox" class="form-check-input" id="regist_check">
            <label class="form-check-label" for="regist_check">上記入力内容で了承</label>
        </div>
        <button type="submit" class="btn btn-primary" id="regist_button" disabled>入力確認</button>
    </form>
</div>
@endsection
@include('manage.common.regist_modal')
@include('manage.common.image_modal')
@include('manage.common.footer')
@include('manage.event_regist_js')