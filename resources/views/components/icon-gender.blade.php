@props([
    'gender',
    'withLabel' => false
])
@if($gender == 'm' || $gender == 'male')
    <x-icon icon="male" {{ $attributes }}/> @if($withLabel) {{ __('Male') }} @endif
@elseif($gender == 'f' || $gender == 'female')
    <x-icon icon="female" {{ $attributes }}/> @if($withLabel) {{ __('Female') }} @endif
@endif
