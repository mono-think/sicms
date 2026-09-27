<input type="hidden" id="crn" value="{{Route::currentRouteName()}}" />
<div id="accordion2" role="tablist">
  <div class="card p-0">
    <div class="card-header" role="tab" id="headingOne2">
      <h5 class="mb-0"> <a data-toggle="collapse" href="#collapseOne2" role="button" aria-expanded="true" aria-controls="collapseOne2">基本情報</a> </h5>
    </div>
    <div id="collapseOne2" class="collapse" role="tabpanel" aria-labelledby="headingOne2" data-parent="#accordion2">
      <div class="card-body"><a href="{{ route('company.index') }}">企業</a></div>
@if (isset($login_info['company_no']) && (!empty($login_info['company_no'])) && ($login_info['company_no'] !== 'master'))
      <div class="card-body"><a href="{{ route('acnt.index') }}">アカウント</a></div>
@endif
    </div>
  </div>
</div>
<div id="accordion3" role="tablist">
  <div class="card p-0">
    <div class="card-header" role="tab" id="headingOne3">
      <h5 class="mb-0"> <a data-toggle="collapse" href="#collapseOne3" role="button" aria-expanded="true" aria-controls="collapseOne3">マスタ</a> </h5>
    </div>
    <div id="collapseOne3" class="collapse" role="tabpanel" aria-labelledby="headingOne3" data-parent="#accordion3">
      <div class="card-body"><a href="{{ route('industry.index') }}">業種</a></div>
      <div class="card-body"><a href="{{ route('bformat.index') }}">業態</a></div>
      <div class="card-body"><a href="{{ route('position.index') }}">役職</a></div>
      <div class="card-body"><a href="{{ route('pref.index') }}">都道府県</a></div>
      <div class="card-body"><a href="{{ route('products_category.index') }}">製品カテゴリ</a></div>
    </div>
  </div>
</div>