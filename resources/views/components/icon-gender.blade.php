@props([
    'gender',
    'withLabel' => false
])
@if($gender == 'm' || $gender == 'male')
    <x-icon icon="person" {{ $attributes }}/> @if($withLabel) {{ __('Male') }} @endif
@elseif($gender == 'f' || $gender == 'female')
    <x-icon icon="person-dress" {{ $attributes }}/> @if($withLabel) {{ __('Female') }} @endif
@endif
