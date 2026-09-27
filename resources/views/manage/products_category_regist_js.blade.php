@section('other_js')
<!-- validate -->
<script src="{{ asset('manage/js/validate-forms.min.js') }}"></script>
@if (request()->getHttpHost() === 'sicms.local')
<!-- js -->
<script src="{{ asset('manage/js/sicms_regist.js') }}"></script>
@else
<!-- js -->
<script src="{{ asset('manage/js/sicms_regist.min.js') }}"></script>
@endif
@endsection
