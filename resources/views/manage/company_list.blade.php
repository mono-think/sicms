@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<div class="w-75 p-3">
    <h2>企業一覧</h2>
    <form id="search_form" class="needs-validation" novalidate method="get" action="{{ route('company.index') }}">
        {{ csrf_field() }}
        <div class="input-group">
            <div class="input-group-prepend">
                <label class="input-group-text" for="input_free_word">フリーワード</label>
            </div>
            <input type="search" class="form-control" name="input_free_word" id="input_free_word" placeholder="企業名 / 業態名 / 業種名" value="{{$free_word}}">
            <div class="input-group-append">
                <button type="submit" class="btn btn-secondary">検索</button>
            </div>
        </div>
    </form>
</div>
<div class="w-75 p-3">
    <div class="py-2">
        <button type="button" class="btn btn-sm btn-secondary" id="select_del" data-toggle="modal" data-target="#list_del" disabled>削除</button>
        <a type="button" class="btn btn-sm btn-dark" href="{{route('company.create')}}">新規作成</a>
    </div>
<!-- ページネーション -->
{{ $form_data->links() }}
    <form id="list_form" class="mb-2" method="post" action="{{ route('company.destroy') }}">
        <input type="hidden" name="_method" value="delete">
        {{ csrf_field() }}
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th scope="col" class="text-center th_header"><input type="checkbox" id="select_all"></th>
                    <th scope="col">企業名</th>
                </tr>
            </thead>
            <tbody>
@foreach ($form_data as $list_data) 
                <tr>
                    <th scope="row" class="text-center align-middle bg-light th_header">
@if ($login_info['company_no'] != $list_data->comp_no)
                    <input type="checkbox" name="select_no[]" value="{{$list_data->comp_no}}">
@endif
                    </th>
                    <td><p>{{$list_data->comp_name}}<br />【種別】{{getConstsType('company', $list_data->comp_type)}}【業態】{{$list_data->bformat_name}}【業種】{{$list_data->industry_name}}</p>
                    <a type="button" class="btn btn-sm btn-secondary" href="{{route('company.show',$list_data->comp_no)}}">参照</a></td>
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
@include('manage.company_list_js')
