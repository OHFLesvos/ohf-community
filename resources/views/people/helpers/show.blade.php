@extends('layouts.app')

@section('title', __('people.view_helper'))

@section('content')

    @if($helper->starting_date == null)
        @if($helper->rejection_date != null)
            @component('components.alert.warning')
                @lang('people.helper_application_rejection', ['date' => $helper->rejection_date->toDateString() ])
            @endcomponent
        @else
            @component('components.alert.info')
                @if($helper->application_date != null)
                    @lang('people.person_on_helper_waiting_list_date', ['date' => $helper->application_date->toDateString() ])
                @else
                    @lang('people.person_on_helper_waiting_list')
                @endif
            @endcomponent
        @endif
    @else
        @if($helper->leaving_date != null)
            @component('components.alert.warning')
                @lang('people.helper_left', ['date' => $helper->leaving_date->toDateString() ])
            @endcomponent
        @elseif($helper->trial_period)
            @component('components.alert.warning')
                @lang('people.helper_on_trial_period', ['date' => $helper->starting_date->toDateString() ])
            @endcomponent
        @endif
    @endif

    <div class="row">
        @foreach($sections as $section_key => $section_label)
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">{{ $section_label }}</div>
                    <ul class="list-group list-group-flush">
                        @foreach(collect($fields)->where('section', $section_key) as $field)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-sm-4">
                                        @isset($field['icon'])@icon({{$field['icon']}}) @endisset
                                        <strong>{{ $field['label'] }}</strong>
                                    </div>
                                    <div class="col-sm">
                                        @isset($field['value_html'])
                                            {{ $field['prefix'] ?? '' }}{!! $field['value_html']($helper) !!}
                                        @else
                                            @if(gettype($field['value']) == 'string')
                                                {{ $field['prefix'] ?? '' }}{{ $helper->{$field['value']} }}
                                            @else
                                                {{ $field['prefix'] ?? '' }}{{ $field['value']($helper) }}
                                            @endif
                                        @endisset
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>

@endsection
