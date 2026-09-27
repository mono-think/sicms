<div class="input-group">
    <label class="input-group-text" for="input_cpb_no">支店</label>
    <div class="border-top border-bottom d-flex align-items-center px-2 w-75 bg-light" id="branch_preview">{{outputValue($form_data,'input_cpb_name')}}</div>
    <button type="button" class="btn btn-secondary d-flex align-items-center" id="get_branch_list">選択</button>
</div>
<input type="hidden" name="input_cpb_no" id="input_cpb_no" value="{{outputValue($form_data,'input_cpb_no')}}">
<input type="hidden" name="input_cpb_name" id="input_cpb_name" value="{{outputValue($form_data,'input_cpb_name')}}">
