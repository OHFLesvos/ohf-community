@extends('layouts.app')

@section('title', __('app.logviewer'))

@section('content')

    {{ Form::open(['route' => 'logviewer.index', 'method' => 'get']) }}
        <div class="card mb-4">
            <div class="card-header">@lang('app.filter')</div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm">{{ Form::bsCheckboxInlineList('level[]', collect($levels)->mapWithKeys(function($e){return [$e => __('app.'.$e)];}), $activeLevels) }}</div>
                </div>
                {{ Form::bsSubmitButton(__('app.apply_filter'), 'filter') }}
                <a href="{{ route('logviewer.index') }}" class="btn btn-secondary">@icon(undo) @lang('app.reset_filter')</a>
            </div>
        </div>
    {{ Form::close() }}

    @if( ! $entries->isEmpty() )
        <div class="table-responsive">
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th>@lang('app.date')</th>
                        <th>@lang('app.severity')</th>
                        <th>@lang('app.message')</th>
                        <th colspan="2">@lang('app.parameters')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($entries as $entry)
                        @php
                            switch($entry->level) {
                                case 'emergency':
                                case 'alert':
                                case 'critical':
                                case 'error':
                                    $class = 'text-danger';
                                    break;
                                case 'warning':
                                    $class = 'text-warning';
                                    break;
                                case 'notice':
                                case 'info':
                                    $class = 'text-info';
                                    break;
                                case 'debug':
                                default:
                                    $class = '';
                            }
                            if (preg_match('/^(.+) ({.+})$/', $entry->context->message, $m)) {
                                $message = $m[1];
                                $params = collect(json_decode($m[2]))->toArray();
                            } else {
                                $message = $entry->context->message;
                                $params = [];
                            }
                        @endphp
                        <tr>
                            <td style="white-space: nowrap">{{ $entry->date }}</td>
                            <td class="{{ $class }}">@lang('app.'.$entry->level)</td>
                            <td>{!! nl2br(e($message)) !!}</td>
                            <td class="p-0">
                                <table class="table m-0">
                                    @foreach($params as $k => $v)
                                        <tr><td>{{ $k }}</td><td>{{ $v }}</td></tr>
                                    @endforeach
                                </table>
                            </td>
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
