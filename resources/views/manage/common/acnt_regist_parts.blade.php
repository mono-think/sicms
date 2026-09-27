<div class="input-group">
    <label class="input-group-text" for="input_ano">担当</label>
    <div class="border-top border-bottom d-flex align-items-center px-2 w-75 bg-light" id="acnt_preview">{{outputValue($form_data,'input_acnt_name')}}</div>
    <button type="button" class="btn btn-secondary d-flex align-items-center" id="get_acnt_list">選択</button>
</div>
<input type="hidden" name="input_acnt_no" id="input_acnt_no" value="{{outputValue($form_data,'input_acnt_no')}}">
<input type="hidden" name="input_acnt_name" id="input_acnt_name" value="{{outputValue($form_data,'input_acnt_name')}}">