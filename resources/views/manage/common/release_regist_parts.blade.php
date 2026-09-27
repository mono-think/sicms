<div class="input-group">
    <div class="input-group-prepend">
        <label class="input-group-text" for="input_release_start">掲載開始日</label>
    </div>
    <input type="date" class="form-control" name="input_release_start" id="input_release_start" placeholder="掲載開始日" maxlength="10" aria-describedby="input_release_start" value="{{outputValue($form_data,'input_release_start')}}" required>
    <div class="invalid-feedback">入力必須です。</div>
</div>
<div class="input-group">
    <div class="input-group-prepend">
        <label class="input-group-text" for="input_release_end">掲載終了日</label>
    </div>
    <input type="date" class="form-control" name="input_release_end" id="input_release_end" placeholder="掲載終了日" maxlength="10" aria-describedby="input_release_end" value="{{outputValue($form_data,'input_release_end')}}" required>
    <div class="invalid-feedback">入力必須です。</div>
</div>