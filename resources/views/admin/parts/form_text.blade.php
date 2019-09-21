@if(isset($data->$key))
    <?php $val = $data->$key; ?>
@else
    <?php $val = old($key); ?>
@endif
<div class="form-group @if(!empty($errors->first($key))) has-error @endif">
    <label class="control-label col-md-4" for="first-name">{{ $form['label'] }} @if(isset($form['required']) && $form['required']) * @endif</label>
    <div class="col-md-6">
        <input type="text" id="{{ $key }}" name="{{ $key }}" class="form-control" value="{{ $val }}">
        @if(empty($isSearch))
        @if(!empty($form['memo'])) <span class="help-block">{{ $form['memo'] }} </span> @endif
        <span class="help-block">{{$errors->first($key)}}</span>
        @endif
    </div>
</div>
