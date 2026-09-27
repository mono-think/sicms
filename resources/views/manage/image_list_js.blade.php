@section('other_js')
@if (request()->getHttpHost() === 'sicms.local')
<!-- js -->
<script src="{{ asset('manage/js/sicms_image.js') }}"></script>
<script src="{{ asset('manage/js/sicms_image_list.js') }}"></script>
<script src="{{ asset('manage/js/sicms_list.js') }}"></script>
@else
<!-- js -->
<script src="{{ asset('manage/js/sicms_image.min.js') }}"></script>
<script src="{{ asset('manage/js/sicms_image_list.min.js') }}"></script>
<script src="{{ asset('manage/js/sicms_list.min.js') }}"></script>
@endif
@endsection
