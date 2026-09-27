<div class="input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">{{$text}}</span>
    </div>
    <textarea cols="5" rows="10" class="form-control insert_text" name="{{$name}}" id="{{$name}}" placeholder="テキスト入力" maxlength="{{$length}}" {{$required}}>{{outputValue($form_data, $name)}}</textarea>
    <div class="input-group-append">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#insert_atag">リンク挿入</button>
        <button type="button" class="btn btn-secondary" id="get_image_list" data-toggle="modal" data-target="#insert_image">画像挿入</button>
    </div>
    <div class="invalid-feedback">入力必須です。</div>
</div>