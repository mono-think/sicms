@extends('manage.common.base')
@include('manage.common.header')
@section('content')
<div class="w-75 p-3">
    <h2>役職マスタ一覧</h2>
    <form id="search_form" class="needs-validation" novalidate method="get" action="{{ route('position.index') }}">
        {{ csrf_field() }}
        <div class="input-group">
            <div class="input-group-prepend">
                <label class="input-group-text" for="input_free_word">フリーワード</label>
            </div>
            <input type="search" class="form-control" name="input_free_word" id="input_free_word" placeholder="役職名" value="{{$free_word}}">
            <div class="input-group-append">
                <button type="submit" class="btn btn-secondary">検索</button>
            </div>
        </div>
    </form>
</div>
<div class="w-75 p-3">
    <div class="py-2">
        <button type="button" class="btn btn-sm btn-secondary" id="select_del" data-toggle="modal" data-target="#list_del" disabled>削除</button>
        <a type="button" class="btn btn-sm btn-dark" href="{{route('position.create')}}">新規作成</a>
    </div>
<!-- ページネーション -->
{{ $form_data->links() }}
    <form id="list_form" class="mb-2" method="post" action="{{ route('position.destroy') }}">
        <input type="hidden" name="_method" value="delete">
        {{ csrf_field() }}
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th scope="col" class="text-center th_header"><input type="checkbox" id="select_all"></th>
                    <th scope="col">役職</th>
                </tr>
            </thead>
            <tbody>
@foreach ($form_data as $list_data) 
                <tr>
                    <th scope="row" class="text-center align-middle bg-light th_header"><input type="checkbox" name="select_no[]" value="{{$list_data->position_no}}"></th>
                    <td><p>{{$list_data->position_name}}</p><a type="button" class="btn btn-sm btn-secondary" href="{{route('position.show',$list_data->position_no)}}">参照</a></td>
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
@include('manage.position_list_js')
