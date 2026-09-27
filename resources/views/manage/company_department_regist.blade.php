@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<div class="w-75 p-3">
@if (url()->current() == route('company_department.create'))
    <h2>企業部署登録</h2>
@else
    <h2>企業部署編集</h2>
@endif
    <form id="company_department_regist_form" class="needs-validation" novalidate method="post" action="{{ route('company_department.confirm') }}">
        {{ csrf_field() }}
        <input type="hidden" id="check_no" value="{{route('company_department.checkno')}}">
        <input type="hidden" id="js_message1" value="{{dispErrorMessage('m9')}}">
@if (url()->current() == route('company_department.create'))
@include('manage.common.number_regist_parts', ['name' => 'input_no','text' => '番号', 'length' => 10 ,'required' => 'required'])
@else
        <input type="hidden" name="input_no" value="{{outputValue($form_data,'input_no')}}">
@endif
<!-- 【MySQL暫定対応】 -->
@include('manage.common.text_regist_parts', ['name' => 'input_name','text' => '名称', 'length' => 191 ,'required' => 'required'])
@include('manage.common.tel_regist_parts',['name' => 'input_tel','text' => '電話番号', 'required' => 'required'])
@include('manage.common.tel_regist_parts',['name' => 'input_fax','text' => 'FAX番号', 'required' => ''])
@include('manage.common.email_regist_parts',['name' => 'input_email','text' => 'メールアドレス', 'required' => ''])
@include('manage.common.order_regist_parts', ['name' => 'input_order','text' => '並び順', 'length' => 3 ,'required' => 'required'])
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <input type="checkbox" name="input_public" id="input_public" value="1" @if (outputValue($form_data,'input_public') === 1) checked @endif>
                </div>
            </div>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span>公開フラグ <small>※チェックの入った部署は一般に公開されます。公開対象ならチェックを入れて下さい。</small></span>
                </div>
            </div>
        </div>
        <hr />
        <div id="department_sns_list">
            <div class="input-group">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="input_sns">SNS</label>
                </div>
                <div class="input-group-append">
                    <button type="button" class="btn btn-info" id="department_sns_item_add">項目追加</button>
                </div>
            </div>
            <span><small>※最大5件まで登録できます。</small><br /><small>※有効なURLを入力して下さい。</small></span>
@if (outputValue($form_data,'input_snsno'))
@foreach (outputValue($form_data,'input_snsno') as $select_idx=>$select_sns_no_data) 
            <div class="w-50 department_sns_area">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <select class="custom-select input_snsno" name="input_snsno[]">
                            <option value="" @if (strlen($select_sns_no_data) == 0) selected @endif>選択なし</option>
@foreach(getConstsType('sns') as $sns_no=>$sns_name)
                            <option value="{{$sns_no}}" @if ((!is_null($select_sns_no_data)) && ((string)$select_sns_no_data === (string)$sns_no)) selected @endif>{{$sns_name}}</option>
@endforeach
                        </select>
                    </div>
                    <input type="text" class="form-control input_sns" name="input_sns[]" placeholder="snsのurl入力" value="{{outputValue($form_data,'input_sns',$select_idx)}}">
@if ($select_idx > 0)
                    <div class="input-group-append">
                        <button type="button" class="btn btn-info department_sns_item_del">削除</button>
                    </div>
@endif
                </div>
            </div>
@endforeach
@else
            <div class="w-50 department_sns_area">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <select class="custom-select input_snsno" name="input_snsno[]">
                            <option value="" selected>選択なし</option>
@foreach(getConstsType('sns') as $sns_no=>$sns_name)
                            <option value="{{$sns_no}}">{{$sns_name}}</option>
@endforeach
                        </select>
                    </div>
                    <input type="text" class="form-control input_sns" name="input_sns[]" placeholder="snsのurl入力">
              </div>
          </div>
@endif
        </div>
        <hr />
        <div class="form-check p-3">
            <input type="checkbox" class="form-check-input" id="regist_check">
            <label class="form-check-label" for="regist_check">上記入力内容で了承</label>
        </div>
        <button type="submit" class="btn btn-primary" id="regist_button" disabled>入力確認</button>
    </form>
</div>
@endsection
@include('manage.common.footer')
@include('manage.company_department_regist_js')