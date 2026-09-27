@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<form id="acnt_confirm_form" method="post" action="{{ route('acnt.store') }}">
    {{ csrf_field() }}
    <div class="w-75 p-3">
        <h2>アカウント登録確認</h2>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">支店名</div>
            <div class="col-xl-10 p-2">{{$form_data['input_cpb_name']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">部署名</div>
            <div class="col-xl-10 p-2">{{$form_data['input_cpd_name']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">番号</div>
            <div class="col-xl-10 p-2">{{$form_data['input_no']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">氏名</div>
            <div class="col-xl-10 p-2">{{$form_data['input_sei']}}{{$form_data['input_mei']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">氏名（かな）</div>
            <div class="col-xl-10 p-2">{{$form_data['input_kana_sei']}}{{$form_data['input_kana_mei']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">郵便番号</div>
            <div class="col-xl-10 p-2">{{$form_data['input_zip']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">都道府県名</div>
            <div class="col-xl-10 p-2">{{$form_data['input_pref']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">市区町村</div>
            <div class="col-xl-10 p-2">{{$form_data['input_city']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">町・丁目・番地</div>
            <div class="col-xl-10 p-2">{{$form_data['input_town']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">マンション・アパート</div>
            <div class="col-xl-10 p-2">{{$form_data['input_address']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">電話番号１</div>
            <div class="col-xl-10 p-2">{{$form_data['input_tel']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">電話番号２</div>
            <div class="col-xl-10 p-2">{{$form_data['input_tel1']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">メールアドレス１</div>
            <div class="col-xl-10 p-2">{{$form_data['input_email']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">メールアドレス２</div>
            <div class="col-xl-10 p-2">{{$form_data['input_email1']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">役職</div>
            <div class="col-xl-10 p-2">{{$form_data['input_posname']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">役職その他</div>
            <div class="col-xl-10 p-2">{{$form_data['input_posother_name']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">権限</div>
            <div class="col-xl-10 p-2">{{$form_data['input_authname']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">権限その他</div>
            <div class="col-xl-10 p-2">{{$form_data['input_authother_name']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">ログインID</div>
            <div class="col-xl-10 p-2">{{$form_data['input_id']}}</div>
        </div>
        <div class="row border bg-light rounded p-1 mb-2">
            <div class="col-xl-2 p-2 border-right">パスワード</div>
            <div class="col-xl-10 p-2 text-danger">※保護のため表示されません。</div>
        </div>
        <a type="button" class="btn btn-primary" href="{{route('acnt.create')}}">戻る</a>
        <button type="submit" class="btn btn-secondary">登録</button>
    </div>
</form>
@endsection
@include('manage.common.footer')