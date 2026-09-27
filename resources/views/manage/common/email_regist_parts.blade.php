<div class="input-group">
    <div class="input-group-prepend">
        <label class="input-group-text" for="input_email">{{$text}}</label>
    </div><!-- 【MySQL暫定対応】 -->
    <input type="email" class="form-control" name="{{$name}}" id="{{$name}}" pattern="^[a-zA-Z0-9.!#$&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$" placeholder="{{$text}}" maxlength="191" aria-describedby="{{$name}}" value="{{outputValue($form_data,$name)}}" {{$required}}>
    <div class="invalid-feedback">メールアドレスは正しい書式で入力して下さい。</div>
</div>