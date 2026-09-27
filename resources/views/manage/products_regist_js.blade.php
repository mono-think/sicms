@section('other_js')
<!-- validate -->
<script src="{{ asset('manage/js/validate-forms.min.js') }}"></script>
@if (request()->getHttpHost() === 'sicms.local')
<!-- js -->
<script src="{{ asset('manage/js/sicms_company_branch_select.js') }}"></script>
<script src="{{ asset('manage/js/sicms_company_department_select.js') }}"></script>
<script src="{{ asset('manage/js/sicms_acnt_select.js') }}"></script>
<script src="{{ asset('manage/js/sicms_regist.js') }}"></script>
<script src="{{ asset('manage/js/sicms_products_regist.js') }}"></script>
@else
<!-- js -->
<script src="{{ asset('manage/js/sicms_company_branch_select.min.js') }}"></script>
<script src="{{ asset('manage/js/sicms_company_department_select.min.js') }}"></script>
<script src="{{ asset('manage/js/sicms_acnt_select.min.js') }}"></script>
<script src="{{ asset('manage/js/sicms_regist.min.js') }}"></script>
<script src="{{ asset('manage/js/sicms_products_regist.min.js') }}"></script>
@endif
@endsection
