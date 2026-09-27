@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<div class="w-75 p-3">
    <h2>部署一覧</h2>
    <form id="search_form" class="needs-validation" novalidate method="get" action="{{ route('company_department.index') }}">
        {{ csrf_field() }}
        <div class="input-group">
            <div class="input-group-prepend">
                <label class="input-group-text" for="input_free_word">フリーワード</label>
            </div>
            <input type="search" class="form-control" name="input_free_word" id="input_free_word" placeholder="部署番号 / 部署名" value="{{$free_word}}">
            <div class="input-group-append">
                <button type="submit" class="btn btn-secondary">検索</button>
            </div>
        </div>
    </form>
</div>
<div class="w-75 p-3">
{{-- アドミニストレータ権限または支店管理者権限なら出力 --}}
@if ((checkAuthAcntAdmin($login_info['acnt_auth_no'])) || (checkAuthAcntBranch($login_info['acnt_auth_no'])))
    <div class="py-2">
        <button type="button" class="btn btn-sm btn-secondary" id="select_del" data-toggle="modal" data-target="#list_del" disabled>削除</button>
        <a type="button" class="btn btn-sm btn-dark" href="{{route('company_department.create')}}">新規作成</a>
    </div>
@endif
<!-- ページネーション -->
{{ $form_data->links() }}
    <form id="list_form" method="post" action="{{ route('company_department.destroy') }}">
        <input type="hidden" name="_method" value="delete">
        {{ csrf_field() }}
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th scope="col" class="text-center th_header">
{{-- アドミニストレータ権限または支店管理者権限なら出力 --}}
@if ((checkAuthAcntAdmin($login_info['acnt_auth_no'])) || (checkAuthAcntBranch($login_info['acnt_auth_no'])))
                    <input type="checkbox" id="select_all">
@endif
                    </th>
                    <th scope="col">部署名</th>
                </tr>
            </thead>
            <tbody>
@foreach ($form_data as $list_data) 
                <tr>
                    <th scope="row" class="text-center align-middle bg-light th_header">
{{-- アドミニストレータ権限または支店管理者権限かつ選択部署以外なら出力 --}}
@if (checkAuthAcntAdmin($login_info['acnt_auth_no']) || checkAuthAcntBranch($login_info['acnt_auth_no']))
@if ($login_info['company_department_no'] != $list_data->comp_department_no)
                    <input type="checkbox" name="select_no[]" value="{{$list_data->comp_department_no}}">
@endif
@endif
                    </th>
                    <td><p>{{$list_data->comp_department_name}}<br />{{dispPublic($list_data->comp_department_public_flg)}}</p>
                    <a type="button" class="btn btn-sm text-white bg-dark" href="{{route('company_department.show',$list_data->comp_department_no)}}">参照</a></td>
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
@include('manage.company_department_list_js')