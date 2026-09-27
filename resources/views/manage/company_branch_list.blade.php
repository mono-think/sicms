@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<div class="w-75 p-3">
    <h2>支店一覧</h2>
    <form id="search_form" class="needs-validation" novalidate method="get" action="{{ route('company_branch.index') }}">
        {{ csrf_field() }}
        <div class="input-group">
            <div class="input-group-prepend">
                <label class="input-group-text" for="input_free_word">フリーワード</label>
            </div>
            <input type="search" class="form-control" name="input_free_word" id="input_free_word" placeholder="支店番号 / 支店名 / 住所" value="{{$free_word}}">
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
        <a type="button" class="btn btn-sm btn-dark" href="{{route('company_branch.create')}}">新規作成</a>
    </div>
@endif
<!-- ページネーション -->
{{ $form_data->links() }}
    <form id="list_form" method="post" action="{{ route('company_branch.destroy') }}">
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
                    <th scope="col">支店名</th>
                </tr>
            </thead>
            <tbody>
@foreach ($form_data as $list_data) 
                <tr>
                    <th scope="row" class="text-center align-middle bg-light th_header">
{{-- アドミニストレータ権限かつ選択中支店以外を出力 --}}
@if (checkAuthAcntAdmin($login_info['acnt_auth_no']) && ($login_info['company_branch_no'] != $list_data->comp_branch_no))
                    <input type="checkbox" name="select_no[]" value="{{$list_data->comp_branch_no}}">
@endif
                    </th>
                    <td><p>{{$list_data->comp_branch_name}}<br />【住所】{{$list_data->cb_address}}</p>
                    <a type="button" class="btn btn-sm btn-secondary" href="{{route('company_branch.show',$list_data->comp_branch_no)}}">参照</a></td>
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
@include('manage.company_branch_list_js')