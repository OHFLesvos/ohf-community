@props([
    'gender',
    'withLabel' => false
])
@if($gender == 'm' || $gender == 'male')
    <x-icon icon="male" {{ $attributes }}/> @if($withLabel) @lang('Male') @endif
@elseif($gender == 'f' || $gender == 'female')
    <x-icon icon="female" {{ $attributes }}/> @if($withLabel) @lang('Female') @endif
@endif
