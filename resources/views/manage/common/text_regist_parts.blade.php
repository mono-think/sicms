<div class="input-group">
    <div class="input-group-prepend">
        <label class="input-group-text" for="{{$name}}">{{$text}}</label>
    </div>
    <input type="text" class="form-control" name="{{$name}}" id="{{$name}}" placeholder="テキスト入力" maxlength="{{$length}}" aria-describedby="{{$name}}" value="{{outputValue($form_data,$name)}}" {{$required}}>
    <div class="invalid-feedback">入力必須です。</div>
</div>