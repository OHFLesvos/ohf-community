@props([
    'gender',
    'withLabel' => false
])
@if($gender == 'm' || $gender == 'male')
    <x-icon icon="male" {{ $attributes }}/> @if($withLabel) @lang('app.male') @endif
@elseif($gender == 'f' || $gender == 'female')
    <x-icon icon="female" {{ $attributes }}/> @if($withLabel) @lang('app.female') @endif
@endif
