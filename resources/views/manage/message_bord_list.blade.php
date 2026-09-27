@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<div class="w-75 p-3">
    <h2>メッセージボード一覧</h2>
    <form id="search_form" class="needs-validation" novalidate method="get" action="{{ route('message_bord.index') }}">
        {{ csrf_field() }}
        <div class="input-group">
            <div class="input-group-prepend">
                <label class="input-group-text" for="input_free_word">フリーワード</label>
            </div>
            <input type="search" class="form-control" name="input_free_word" id="input_free_word" placeholder="本文/名前" value="{{$free_word}}">
            <div class="input-group-append">
                <button type="submit" class="btn btn-secondary">検索</button>
            </div>
        </div>
    </form>
</div>
<div class="w-75 p-3">
{{-- 閲覧者以外出力 --}}
@if (!checkAuthAcntViewer($login_info['acnt_auth_no']))
    <div class="py-2">
        <button type="button" class="btn btn-sm btn-secondary" id="select_del" data-toggle="modal" data-target="#list_del" disabled>削除</button>
    </div>
@endif
<!-- ページネーション -->
{{ $form_data['m_data']->links() }}
    <form id="list_form" method="post" action="{{ route('message_bord.destroy') }}">
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
                    <th scope="col">メッセージ</th>
                </tr>
            </thead>
            <tbody>
@foreach ($form_data['m_data'] as $list_data) 
                <tr>
                    <th scope="row" class="text-center align-middle bg-light th_header">
{{-- 閲覧者以外出力 --}}
@if (!checkAuthAcntViewer($login_info['acnt_auth_no']))
                    <input type="checkbox" name="select_no[]" value="{{$list_data->message_bord_no}}">
@endif
                    </th>
                    <td>{{$list_data->message_bord_text}}【{{$list_data->message_bord_sender}}】{{$list_data->message_bord_ip}} / {{$list_data->message_bord_email}}<br />
@if (isset($form_data['rply_data'][$list_data->message_bord_no]))
@foreach ($form_data['rply_data'][$list_data->message_bord_no] as $list_sub_data) 
                    <div class="input-group">
{{-- 閲覧者以外出力 --}}
@if (!checkAuthAcntViewer($login_info['acnt_auth_no']))
                        <div class="input-group-text">
                            <input type="checkbox" name="select_rno[]" value="{{$list_data->message_bord_no}}:{{$list_sub_data['input_mr_no']}}">
                        </div>
@endif
                        <div class="border-top border-bottom d-flex align-items-center px-2 w-75">{{$list_sub_data['input_mr_text']}}【{{$list_sub_data['input_mr_cpname']}}&nbsp;{{$list_sub_data['input_mr_cpbname']}}】{{$list_sub_data['input_mr_date']}}</div>
{{-- 閲覧者以外出力 --}}
@if (!checkAuthAcntViewer($login_info['acnt_auth_no']))
                        <a type="button" class="btn btn-sm btn-dark d-flex align-items-center" href="{{route('message_bord.edit',$list_sub_data['input_mr_no'])}}">編集</a>
@endif
                    </div>
@endforeach
@endif
{{-- 閲覧者以外出力 --}}
@if (!checkAuthAcntViewer($login_info['acnt_auth_no']))
                    <a type="button" class="btn btn-sm btn-dark" href="{{route('message_bord.create',$list_data->message_bord_no)}}">返信</a>
@endif
                    </td>
                </tr>
@endforeach
            </tbody>
        </table>
    </form>
<!-- ページネーション -->
{{ $form_data['m_data']->links() }}
</div>
@endsection
@include('manage.common.list_modal')
@include('manage.common.footer')
@include('manage.message_bord_list_js')