@section('other_js')
@if (request()->getHttpHost() === 'sicms.local')
<!-- js -->
<script src="{{ asset('manage/js/sicms_login.js') }}"></script>
@else
<!-- js -->
<script src="{{ asset('manage/js/sicms_login.min.js') }}"></script>
@endif
@endsection
