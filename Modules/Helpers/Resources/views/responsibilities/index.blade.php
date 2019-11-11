@extends('layouts.app')

@section('title', __('helpers::responsibilities.responsibilities'))

@section('content')

    @if(count($responsibilities) > 0)
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>@lang('app.name')</th>
                        <th>@lang('app.capacity')</th>
                        <th>@lang('app.available')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($responsibilities as $responsibility)
                        <tr>
                            <td>
                                <a href="{{ route('people.helpers.responsibilities.edit', $responsibility) }}">{{ $responsibility->name }}</a>
                            </td>
                            <td>{{ $responsibility->capacity }}</td>
                            <td>@if($responsibility->available) @icon(check) @else @icon(times) @endif</td>
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
