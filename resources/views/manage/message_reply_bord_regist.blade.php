@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<div class="w-75 p-3">
    <h2>メッセージボード返信登録/編集</h2>
    <form id="message_reply_bord_regist_form" class="needs-validation" novalidate method="post" action="{{ route('message_bord.confirm') }}">
        {{ csrf_field() }}
        <input type="hidden" name="input_no" id="input_no" value="{{$input_no}}">
@include('manage.common.text_regist_parts', ['name' => 'input_mr_text','text' => '返信内容', 'length' => 255 ,'required' => 'required'])
        <div class="form-check p-3">
            <input type="checkbox" class="form-check-input" id="regist_check">
            <label class="form-check-label" for="regist_check">上記入力内容で了承</label>
        </div>
        <button type="submit" class="btn btn-primary" id="regist_button" disabled>入力確認</button>
    </form>
</div>
@endsection
@include('manage.common.footer')
@include('manage.message_reply_bord_regist_js')