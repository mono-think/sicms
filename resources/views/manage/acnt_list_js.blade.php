@section('other_js')
<!-- js -->
@if (request()->getHttpHost() === 'sicms.local')
<script src="{{ asset('manage/js/sicms_list.js') }}"></script>
@else
<script src="{{ asset('manage/js/sicms_list.min.js') }}"></script>
@endif
@endsection
