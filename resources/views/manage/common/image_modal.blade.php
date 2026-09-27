@section('image_modal')
<!-- 画像一覧モーダル -->
<div class="modal fade" id="insert_image" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectImageList">画像一覧【{{getConstsType('image_category',$image_category)}}向け】</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="w-100 p-3">
                    <input type="hidden" id="get_image" value="{{route('image_select.getlist', $image_category)}}">
                    <input type="hidden" id="im" value="">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="image_free_word">フリーワード</label>
                        </div>
                        <input type="search" class="form-control" name="image_free_word" id="image_free_word" placeholder="画像名/代替テキスト/説明" value="{{$free_word}}">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-secondary" id="image_search">検索</button>
                        </div>
                    </div>
                </div>
                <div class="w-100 p-3">
                    <input type="hidden" id="image_upload" value="{{ route('image.store') }}">
                    <form id="image_regist_form">
                        {{ csrf_field() }}
                        <input type="hidden" id="ictg" name="ictg" value="{{$image_category}}">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">画像ファイル</span>
                            </div>
                            <div class="custom-file">
                                <input type="file" multiple class="custom-file-input" name="input_image_file[]" id="input_image_file" aria-describedby="input_image_file_select">
                                <label class="custom-file-label" for="input_image_file">押下してファイル選択（複数可）</label>
                            </div>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary" id="image_upload_button" disabled>アップロード開始</button>
                            </div>
                        </div>
                    </form>
                    <progress id="input_image_file" value="0" max="100"></progress>
                    <div class="preview_area"></div>
                </div>
                <div class="alert alert-danger upload-danger d-none" role="alert"></div>
                <div class="w-100 p-3">
                    <input type="hidden" id="image_base" value="{{asset(getImagePublicFolder())}}">
@foreach(getConstsType('image_type') as $type)
                    <input type="hidden" name="image_type[]" value="{{$type}}">
@endforeach
@foreach(getConstsType('image_disp') as $disp)
                    <input type="hidden" class="image_disp" name="image_disp[]" value="{{$disp}}">
@endforeach
                    <input type="hidden" id="js_image_message" value="{{dispErrorMessage('m12')}}">
                    <input type="hidden" id="js_image_message1" value="{{dispErrorMessage('m2')}}">
                    <input type="hidden" id="js_image_message2" value="{{dispInfoMessage('m2')}}">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" colspan="2"><small>※以下の中から挿入する画像をクリックして下さい。</small></th>
                            </tr>
                        </thead>
                        <tbody id="tbody_image_list"></tbody>
                    </table>
                    <input type="hidden" id="select_image_counter" value="0" /><button type="button" class="btn btn-primary mt-1 mb-1 d-none" id="next_image" disabled>さらに表示(最大5件ずつ表示)</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="image_modal_close" data-dismiss="modal">閉じる</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
