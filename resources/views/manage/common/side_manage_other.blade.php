<input type="hidden" id="crn" value="{{Route::currentRouteName()}}" />
<div id="accordion1" role="tablist">
  <div class="card p-0">
    <div class="card-header" role="tab" id="headingOne1">
      <h5 class="mb-0"> <a data-toggle="collapse" href="#collapseOne1" role="button" aria-expanded="true" aria-controls="collapseOne1">コンテンツ</a> </h5>
    </div>
    <div id="collapseOne1" class="collapse" role="tabpanel" aria-labelledby="headingOne1" data-parent="#accordion1">
      <div class="card-body"><a href="{{ route('products.index') }}">製品</a></div>
@if (isset($login_info['products_no']) && !empty($login_info['products_no']))
      <div class="card-body"><a href="{{ route('products_detail.index') }}">製品詳細</a></div>
@endif
@if (isset($login_info['products_detail_no']) && !empty($login_info['products_detail_no']))
      <div class="card-body"><a href="{{ route('products_review.index') }}">製品レビュー</a></div>
@endif
      <div class="card-body"><a href="{{ route('message_bord.index') }}">メッセージボード一覧</a></div>
      <div class="card-body"><a href="{{ route('event.index') }}">イベント</a></div>
      <div class="card-body"><a href="{{ route('content.index') }}">コンテンツ</a></div>
    </div>
  </div>
</div>
<div id="accordion2" role="tablist">
  <div class="card p-0">
    <div class="card-header" role="tab" id="headingOne2">
      <h5 class="mb-0"> <a data-toggle="collapse" href="#collapseOne2" role="button" aria-expanded="true" aria-controls="collapseOne2">基本情報</a> </h5>
    </div>
    <div id="collapseOne2" class="collapse" role="tabpanel" aria-labelledby="headingOne2" data-parent="#accordion2">
      <div class="card-body"><a href="{{ route('company.show', $login_info['company_no']) }}">企業</a></div>
      <div class="card-body"><a href="{{ route('company_branch.index') }}">支店</a></div>
@if (isset($login_info['company_branch_no']) && !empty($login_info['company_branch_no']))
      <div class="card-body"><a href="{{ route('company_branch_news.index') }}">ニュース</a></div>
@endif
@if (isset($login_info['company_branch_no']) && !empty($login_info['company_branch_no']))
      <div class="card-body"><a href="{{ route('company_department.index') }}">部署</a></div>
@endif
      <div class="card-body"><a href="{{ route('acnt.index') }}">アカウント</a></div>
      <div class="card-body"><a href="{{ route('image.index') }}">画像</a></div>
    </div>
  </div>
</div>