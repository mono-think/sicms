<div class="input-group">
    <div class="input-group-prepend">
        <label class="input-group-text" for="input_zip">郵便番号</label>
    </div>
    <input type="text" class="form-control" name="input_zip" id="input_zip" placeholder="ハイフン込みで入力" aria-describedby="input_zip" value="{{outputValue($form_data,'input_zip')}}">
    <div class="invalid-feedback">ハイフン込みの正しい書式で入力して下さい。</div>
</div>