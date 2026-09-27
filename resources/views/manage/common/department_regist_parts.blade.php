<div class="input-group">
    <label class="input-group-text" for="input_cpd_no">部署</label>
    <div class="border-top border-bottom d-flex align-items-center px-2 w-75 bg-light" id="department_preview">{{outputValue($form_data,'input_cpd_name')}}</div>
    <button type="button" class="btn btn-secondary d-flex align-items-center" id="get_department_list">選択</button>
</div>
<input type="hidden" name="input_cpd_no" id="input_cpd_no" value="{{outputValue($form_data,'input_cpd_no')}}">
<input type="hidden" name="input_cpd_name" id="input_cpd_name" value="{{outputValue($form_data,'input_cpd_name')}}">