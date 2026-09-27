@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<div class="w-75 p-3">
    <h2>画像一覧</h2>
    <input type="hidden" id="js_image_message1" value="{{dispErrorMessage('m2')}}">
    <input type="hidden" id="js_image_message2" value="{{dispInfoMessage('m2')}}">
    <input type="hidden" id="js_image_message3" value="{{dispErrorMessage('m12')}}">
    <input type="hidden" id="js_image_message4" value="{{dispInfoMessage('m6')}}">
    <input type="hidden" name="act" id="act" value="{{$login_info['acnt_no']}}">
    <form id="search_form" class="needs-validation" novalidate method="get" action="{{ route('image.index') }}">
        {{ csrf_field() }}
        <div class="input-group">
            <div class="input-group-prepend">
                <label class="input-group-text" for="input_free_word">フリーワード</label>
            </div>
            <input type="search" class="form-control" name="input_free_word" id="input_free_word" placeholder="画像名/代替テキスト/説明" value="{{$free_word}}">
            <div class="input-group-append">
                <button type="submit" class="btn btn-secondary">検索</button>
            </div>
        </div>
    </form>
</div>
{{-- 閲覧者以外出力 --}}
@if (!checkAuthAcntViewer($login_info['acnt_auth_no']))
<div class="w-75 p-3">
    <form id="image_regist_form" enctype='multipart/form-data' method="post" action="{{ route('image.store') }}">
        {{ csrf_field() }}
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">画像ファイル</span>
            </div>
            <div class="custom-file">
                <input type="file" multiple class="custom-file-input" name="input_image_file[]" id="input_image_file">
                <label class="custom-file-label" for="input_image_file">押下してファイル選択（複数可）</label>
            </div>
            <div class="input-group-append">
                <button type="button" class="btn btn-primary" id="image_upload_button" disabled>アップロード開始</button>
            </div>
        </div>
    </form>
    <progress id="input_image_file" value="0" max="100"></progress>
    <div class="preview_area"></div>
</div>
@endif
<div class="w-75 p-3">
{{-- 閲覧者以外出力 --}}
@if (!checkAuthAcntViewer($login_info['acnt_auth_no']))
    <div class="py-2">
        <button type="button" class="btn btn-sm btn-secondary" id="select_del" data-toggle="modal" data-target="#list_del" disabled>削除</button>
    </div>
@endif
<!-- ページネーション -->
{{ $form_data->links() }}
    <form id="list_form" method="post" action="{{ route('image.destroy') }}">
        <input type="hidden" name="_method" value="delete">
        {{ csrf_field() }}
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th scope="col" class="text-center th_header">
{{-- 閲覧者以外出力 --}}
@if (!checkAuthAcntViewer($login_info['acnt_auth_no']))
                    <input type="checkbox" id="select_all">
@endif
                    </th>
                    <th scope="col" colspan="2">画像情報</th>
                </tr>
            </thead>
            <tbody>
@foreach ($form_data as $list_data) 
                <tr>
                    <th scope="row" class="text-center align-middle bg-light th_header">
{{-- 閲覧者以外出力 --}}
@if (!checkAuthAcntViewer($login_info['acnt_auth_no']))
                    <input type="checkbox" name="select_no[]" value="{{$list_data->image_no}}"><input type="hidden" name="select_image_path[]" value="{{$list_data->image_no}}=>{{$list_data->image_folder}}">
@endif
                    </th>
                    <td><img src="{{getImagePath($list_data->image_folder, $list_data->image_name, $list_data->image_type)}}" alt="{{$list_data->image_alt}}" width="400" /><br />
【名称】{{$list_data->image_name}}<br />【画像パス】{{$list_data->image_folder}}<br />【画像タイプ】{{$list_data->image_type}}</td>
                    <td class="w-50"><div class="alert alert-danger update-danger d-none" role="alert"></div>
                        <div class="alert alert-success update-success d-none" role="alert"></div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="ictg">カテゴリ</label>
                            </div>
                            <select class="custom-select ictg"
{{-- 閲覧者のみ出力 --}}
@if (checkAuthAcntViewer($login_info['acnt_auth_no']))
 style="pointer-events: none;"
@endif
                             >
                                <option value="">選択なし</option>
@foreach(getConstsType('image_category') as $category_no=>$category_name)
                                <option value="{{$category_no}}" @if (!strcmp($list_data->image_category, $category_no)) selected @endif>{{$category_name}}</option>
@endforeach
                            </select>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">代替テキスト</span>
                            </div>
                            <textarea cols="3" rows="5" class="form-control input_image_alt" placeholder="代替テキスト"
{{-- 閲覧者のみ出力 --}}
@if (checkAuthAcntViewer($login_info['acnt_auth_no']))
 style="pointer-events: none;"
@endif
                            >{{$list_data->image_alt}}</textarea>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">キャプション</span>
                            </div>
                            <textarea cols="3" rows="5" class="form-control input_image_text" placeholder="キャプション"
{{-- 閲覧者のみ出力 --}}
@if (checkAuthAcntViewer($login_info['acnt_auth_no']))
 style="pointer-events: none;"
@endif
                            >{{$list_data->image_text}}</textarea>
                        </div>
                        <input type="hidden" class="send_image_update" value="{{route('image.update', $list_data->image_no)}}">
{{-- 閲覧者以外出力 --}}
@if (!checkAuthAcntViewer($login_info['acnt_auth_no']))
                        <button type="button" class="btn btn-primary image_update">更新</button>
@endif
                    </td>
                </tr>
@endforeach
            </tbody>
        </table>
    </form>
<!-- ページネーション -->
{{ $form_data->links() }}
</div>
@endsection
@include('manage.common.list_modal')
@include('manage.common.footer')
@include('manage.image_list_js')     
