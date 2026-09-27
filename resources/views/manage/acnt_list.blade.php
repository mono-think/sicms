@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<div class="w-75 p-3">
    <h2>アカウント一覧</h2>
    <form id="search_form" class="needs-validation" novalidate method="get" action="{{ route('acnt.index') }}">
        {{ csrf_field() }}
        <div class="input-group">
            <div class="input-group-prepend">
                <label class="input-group-text" for="input_free_word">フリーワード</label>
            </div>
            <input type="search" class="form-control" name="input_free_word" id="input_free_word" placeholder="番号 / 名前 / 郵便番号 / 住所 / 電話番号 / 企業名 / 支店名 / 部署名 / 役職名" value="{{$free_word}}">
            <div class="input-group-append">
                <button type="submit" class="btn btn-secondary">検索</button>
            </div>
        </div>
    </form>
</div>
<div class="w-75 p-3">
{{-- アドミニストレータ権限のみ出力 --}}
@if (checkAuthAcntAdmin($login_info['acnt_auth_no']))
    <div class="py-2">
        <button type="button" class="btn btn-sm btn-secondary" id="select_del" data-toggle="modal" data-target="#list_del" disabled>削除</button>
        <a type="button" class="btn btn-sm btn-dark" href="{{route('acnt.create')}}">新規作成</a>
    </div>
@endif
<!-- ページネーション -->
{{ $form_data->links() }}
    <form id="list_form" method="post" action="{{ route('acnt.destroy') }}">
        <input type="hidden" name="_method" value="delete">
        {{ csrf_field() }}
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th scope="col" class="text-center th_header">
{{-- アドミニストレータ権限のみ出力 --}}
@if (checkAuthAcntAdmin($login_info['acnt_auth_no']))
                    <input type="checkbox" id="select_all">
@endif
                    </th>
                    <th scope="col">アカウント</th>
                </tr>
            </thead>
            <tbody>
@foreach ($form_data as $list_data) 
                <tr>
                    <th scope="row" class="text-center align-middle bg-light th_header">
{{-- アドミニストレータ権限かつログインアカウント以外を出力 --}}
@if (checkAuthAcntAdmin($login_info['acnt_auth_no']) && ($login_info['acnt_no'] != $list_data->acnt_no))
                    <input type="checkbox" name="select_no[]" value="{{$list_data->acnt_no}}">
@endif
                    </th>
                    <td><p>{{$list_data->acnt_name}}（{{$list_data->acnt_kananame}}）<br />
                    【住所】{{$list_data->acnt_detail_zip_code}}{{$list_data->acnt_address}}<br />
                    【支店】{{$list_data->comp_branch_name}}【部署】{{$list_data->comp_department_name}}【役職】{{$list_data->position_name}}【権限】{{getConstsType('auth', $list_data->acnt_auth_no)}}</p>
                    <a type="button" class="btn btn-sm btn-secondary" href="{{route('acnt.show',$list_data->acnt_no)}}">参照</a></td>
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
@include('manage.acnt_list_js')