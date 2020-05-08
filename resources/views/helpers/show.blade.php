@extends('layouts.app')

@section('title', __('people.view_helper'))

@section('content')

    @if($helper->work_starting_date == null)
        @component('components.alert.warning')
            @lang('people.no_started_date_set')
        @endcomponent
    @elseif($helper->work_starting_date->gt(today()))
        @component('components.alert.warning')
            @lang('people.helper_has_not_started_yet', ['date' => $helper->work_starting_date->toDateString() ])
        @endcomponent
    @else
        @if($helper->work_leaving_date != null)
            @component('components.alert.warning')
                @if($helper->work_leaving_date < Carbon\Carbon::today())
                    @lang('people.helper_left', ['date' => $helper->work_leaving_date->toDateString() ])
                @else
                    @lang('people.helper_will_leave', ['date' => $helper->work_leaving_date->toDateString() ])
                @endif
            @endcomponent
        @endif
    @endif

    <div class="columns-3">
        @foreach($data as $section => $fields)
            <div class="card mb-4 column-break-avoid">
                <div class="card-header">
                    {{ $sections[$section] }}
                    <a href="{{ route('people.helpers.edit', [$helper, 'section' => $section]) }}" class="float-right">@icon(edit)</a>
                </div>
                <ul class="list-group list-group-flush">
                    @if(! empty($fields))
                        @foreach($fields as $field)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-sm-5">
                                        @isset($field['icon'])@icon({{$field['icon']}}) @endisset
                                        <strong>{{ $field['label'] }}</strong>
                                    </div>
                                    <div class="col-sm">
                                        {!! $field['value'] !!}
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    @else
                        <li class="list-group-item">
                            <em>@lang('app.no_data_registered')</em>
                        </li>
                    @endif
                </ul>
            </div>
        @endforeach
    </div>

@endsection
