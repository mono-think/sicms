<div class="input-group">
    <div class="input-group-prepend">
        <label class="input-group-text" for="{{$name}}">{{$text}}</label>
    </div>
    <input type="tel" class="form-control" name="{{$name}}" id="{{$name}}" pattern="\d{2,4}-\d{2,4}-\d{3,4}" placeholder="ハイフン付きで入力" maxlength="15" aria-describedby="{{$name}}" value="{{outputValue($form_data,$name)}}" {{$required}}>
    <div class="invalid-feedback">{{$text}}はハイフン込みの正しい書式で入力して下さい。</div>
</div>