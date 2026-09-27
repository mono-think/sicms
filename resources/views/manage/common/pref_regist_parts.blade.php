<div class="input-group">
    <div class="input-group-prepend">
        <label class="input-group-text" for="input_pref_cd">住所 - 都道府県</label>
    </div>
    <input type="hidden" name="input_pref" id="input_pref" value="{{outputValue($form_data,'input_pref')}}">
    <select class="custom-select" name="input_pref_cd" id="input_pref_cd" aria-describedby="input_pref_cd">
        <option value="">選択なし</option>
@foreach($m_pref as $pref)
        <option value="{{$pref->pref_no}}" @if ((string)outputValue($form_data,'input_pref_cd') === (string)$pref->pref_no) selected @endif>{{$pref->pref_name}}</option>
@endforeach
    </select>
</div>