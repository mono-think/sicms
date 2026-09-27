@section('header')
<div class="row p-1">
<div class="col-xl-9 px-4 pt-2 pb-0"><h1>SiCMS管理画面</h1></div>
<div class="col-xl-3 px-4 py-1">
  <div class="dropdown show acnt_profile"> <a class="btn btn-lg dropdown-toggle text-white p-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{$login_info['acnt_name']}}（{{$login_info['acnt_no']}}）</a>
    <div class="dropdown-menu"> <a class="dropdown-item p-0" href="{{route('logout.index')}}">ログアウト</a> <a class="dropdown-item p-0" href="{{route('acnt.show',$login_info['acnt_no'])}}">プロフィール</a></div>
  </div>
</div>
</div>
@endsection