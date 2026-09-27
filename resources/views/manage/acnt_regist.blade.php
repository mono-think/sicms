@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<div class="w-75 p-3">
@if (url()->current() == route('acnt.create'))
    <h2>アカウント登録</h2>
@else
    <h2>アカウント編集</h2>
@endif
    <form id="acnt_regist_form" class="needs-validation" novalidate method="post" action="{{ route('acnt.confirm') }}">
        {{ csrf_field() }}
        <input type="hidden" id="check_no" value="{{route('acnt.checkno')}}">
        <input type="hidden" id="check_id" value="{{route('acnt.checkid')}}">
        <input type="hidden" id="js_message1" value="{{dispErrorMessage('m9')}}">
@include('manage.common.branch_regist_parts')
@include('manage.common.department_regist_parts')
@if (url()->current() == route('acnt.create'))
@include('manage.common.number_regist_parts', ['name' => 'input_no','text' => '番号', 'length' => 10 ,'required' => 'required'])
@else
        <input type="hidden" name="input_no" value="{{outputValue($form_data,'input_no')}}">
@endif
@include('manage.common.text_regist_parts', ['name' => 'input_sei','text' => '姓', 'length' => 10 ,'required' => 'required'])
@include('manage.common.text_regist_parts', ['name' => 'input_mei','text' => '名', 'length' => 10 ,'required' => 'required'])
@include('manage.common.text_regist_parts', ['name' => 'input_kana_sei','text' => '姓（かな）', 'length' => 50 ,'required' => 'required'])
@include('manage.common.text_regist_parts', ['name' => 'input_kana_mei','text' => '名（かな）', 'length' => 50 ,'required' => 'required'])
@include('manage.common.zip_regist_parts')
@include('manage.common.pref_regist_parts')
@include('manage.common.text_regist_parts', ['name' => 'input_city','text' => '住所 - 市区町村', 'length' => 20 ,'required' => ''])
@include('manage.common.text_regist_parts', ['name' => 'input_town','text' => '住所 - 町・丁目・番地', 'length' => 20 ,'required' => ''])
@include('manage.common.text_regist_parts', ['name' => 'input_address','text' => '住所 - その他', 'length' => 20 ,'required' => ''])
@include('manage.common.tel_regist_parts',['name' => 'input_tel','text' => '電話番号１', 'required' => ''])
@include('manage.common.tel_regist_parts',['name' => 'input_tel1','text' => '電話番号２', 'required' => ''])
@include('manage.common.email_regist_parts',['name' => 'input_email','text' => 'メールアドレス１', 'required' => 'required'])
@include('manage.common.email_regist_parts',['name' => 'input_email1','text' => 'メールアドレス２', 'required' => ''])
        <div class="input-group">
            <div class="input-group-prepend">
                <label class="input-group-text" for="input_posno">役職</label>
            </div>
            <input type="hidden" name="input_posname" id="input_posname" value="{{outputValue($form_data,'input_posname')}}">
            <select class="custom-select" name="input_posno" id="input_posno" aria-describedby="input_posno" required>
                <option value="">選択なし</option>
@foreach($m_position as $position)
                <option value="{{$position->position_no}}" @if ((string)outputValue($form_data,'input_posno') === (string)$position->position_no) selected @endif>{{$position->position_name}}</option>
@endforeach
            </select>
        </div>
@include('manage.common.text_regist_parts', ['name' => 'input_posother_name','text' => '役職 - 別名', 'length' => 32 ,'required' => ''])
        <div class="input-group">
            <div class="input-group-prepend">
                <label class="input-group-text" for="input_authno">権限</label>
            </div>
            <input type="hidden" name="input_authname" id="input_authname" value="{{outputValue($form_data,'input_authname')}}">
            <select class="custom-select" name="input_authno" id="input_authno" aria-describedby="input_authno" required>
                <option value="">選択なし</option>
@foreach(getConstsType('auth') as $auth_no=>$auth_name)
                <option value="{{$auth_no}}" @if ((string)outputValue($form_data,'input_authno') === (string)$auth_no) selected @endif>{{$auth_name}}</option>
@endforeach
            </select>
        </div>
@include('manage.common.text_regist_parts', ['name' => 'input_authother_name','text' => '権限 - 別名', 'length' => 32 ,'required' => ''])
@if (url()->current() == route('acnt.create'))
        <div class="input-group">
            <div class="input-group-prepend">
                <label class="input-group-text" for="input_id">ログインID</label>
            </div>
<!-- 【MySQL暫定対応】 -->
            <input type="text" class="form-control" name="input_id" id="input_id" placeholder="テキスト入力" maxlength="191" aria-describedby="input_id" value="{{outputValue($form_data,'input_id')}}" required>
            <div class="invalid-feedback">入力必須です。</div>
        </div>
        <div class="alert alert-danger id_js_message d-none"></div>
@else
        <div class="mx-0 my-1">【ログインID】{{outputValue($form_data,'input_id')}}</div>
        <input type="hidden" name="input_id" value="{{outputValue($form_data,'input_id')}}">
@endif
        <small class="m-0 text-muted">※入力欄右側にチェックを入れると、パスワードが表示されます。</small>
        <div class="input-group">
            <div class="input-group-prepend">
                <label class="input-group-text" for="input_pass">パスワード</label>
            </div>
            <input type="password" class="form-control" name="input_pass" id="input_pass" placeholder="パスワードを入力" maxlength="255" aria-describedby="input_pass" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <input type="checkbox" class="show_password">
                </div>
            </div>
            <div class="invalid-feedback">入力必須です。</div>
        </div>
        <small class="m-0 text-muted">※入力欄右側にチェックを入れると、パスワードが表示されます。</small>
        <div class="input-group">
            <div class="input-group-prepend">
                <label class="input-group-text" for="input_pass_confirmation">　（確認）</label>
            </div>
            <input type="password" class="form-control" name="input_pass_confirmation" id="input_pass_confirmation" placeholder="もう一度パスワードを入力" maxlength="255" aria-describedby="input_pass_confirmation" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <input type="checkbox" class="show_pass_confirmation">
                </div>
            </div>
            <div class="invalid-feedback">入力必須です。</div>
        </div>
        <small class="text-danger">※確認のため、（確認）欄にも同じパスワードを入力して下さい。<br />※尚、本画面以外では入力パスワードの表示はされません。必ず控えておいて下さい。</small>
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
@include('manage.acnt_regist_js')