<div class="input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">{{$text}}</span>
    </div>
    <textarea cols="5" rows="10" class="form-control" name="{{$name}}" id="input_description" placeholder="テキスト入力" maxlength="{{$length}}" {{$required}}>{{outputValue($form_data,$name)}}</textarea>
    <div class="invalid-feedback">入力必須です。</div>
</div>