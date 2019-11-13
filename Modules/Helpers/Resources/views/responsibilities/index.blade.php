@extends('layouts.app')

@section('title', __('helpers::responsibilities.responsibilities'))

@section('content')

    @if(count($responsibilities) > 0)
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>@lang('app.name')</th>
                        <th class="text-right fit">@lang('app.capacity')</th>
                        <th class="text-center fit">@lang('app.available')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($responsibilities as $responsibility)
                        @php
                            $numHelpers = $responsibility->helpers()->count();
                            $warning = ($responsibility->capacity != null && $responsibility->capacity < $numHelpers) || (!$responsibility->available && $numHelpers > 0);
                        @endphp
                        <tr class="@if($warning) table-warning @endif">
                            <td>
                                <a href="{{ route('people.helpers.responsibilities.edit', $responsibility) }}">{{ $responsibility->name }}</a>
                            </td>
                            <td class="text-right fit">{{ $numHelpers }} / {{ $responsibility->capacity ?? '∞'}}</td>
                            <td class="text-center fit">@if($responsibility->available) @icon(check) @else @icon(times) @endif</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        @component('components.alert.info')
            @lang('helpers::responsibilities.no_responsibilities_defined')
        @endcomponent
	@endif
	
@endsection
