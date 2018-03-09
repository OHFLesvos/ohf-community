@extends('layouts.app')

@section('title', __('app.logviewer'))

@section('content')

    @if( ! $entries->isEmpty() )
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>@lang('app.date')</th>
                        <th>@lang('app.severity')</th>
                        <th>@lang('app.message')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($entries as $entry)
                        <tr>
                            <td style="white-space: nowrap">{{ $entry->date }}</td>
                            <td>{{ $entry->level }}</td>
                            <td>{!! nl2br(e($entry->context->message)) !!}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $entries->links() }}
    @else
        @component('components.alert.info')
            @lang('app.no_log_entries_found')
        @endcomponent
	@endif
	
@endsection
