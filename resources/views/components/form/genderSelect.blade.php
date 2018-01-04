<p>Gender</p>
<div class="form-check form-check-inline">
    <label class="form-check-label">
        {{ Form::radio($name, 'm', null, [ 'class' => 'form-check-input' ]) }} @icon(male) Male
    </label>
</div>
<div class="form-check form-check-inline">
    <label class="form-check-label">
        {{ Form::radio($name, 'f', null, [ 'class' => 'form-check-input' ]) }} @icon(female) Female
    </label>
</div>
@if ($errors->has($name))
    <div><small class="text-danger">{{ $errors->first($name) }}</small></div>
@endif
