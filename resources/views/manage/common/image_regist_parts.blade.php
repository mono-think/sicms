<div class="input-group">
    <label class="input-group-text" for="input_image_no">画像</label>
    <div class="border-top border-bottom d-flex align-items-center px-2 w-75 bg-light" id="image_name_preview">{{outputValue($form_data,'input_image_name')}}</div>
    <button type="button" class="btn btn-secondary d-flex align-items-center" id="get_top_image_list">選択</button>
</div>
<div class="mx-0 my-1" id="image_preview"><img src="{{outputValue($form_data,'input_image_file')}}" width="400" /></div>
<input type="hidden" name="input_image_no" id="input_image_no" value="{{outputValue($form_data,'input_image_no')}}">
<input type="hidden" name="input_image_name" id="input_image_name" value="{{outputValue($form_data,'input_image_name')}}">
<input type="hidden" name="input_image_file" id="input_image_file" value="{{outputValue($form_data,'input_image_file')}}">