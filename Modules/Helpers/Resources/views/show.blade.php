@extends('layouts.app')

@section('title', __('people::people.view_helper'))

@section('content')

    @if($helper->work_starting_date == null)
        @if($helper->work_rejection_date != null)
            @component('components.alert.warning')
                @lang('people::people.helper_application_rejection', ['date' => $helper->work_rejection_date->toDateString() ])
            @endcomponent
        @else
            @component('components.alert.info')
                @if($helper->work_application_date != null)
                    @lang('people::people.person_on_helper_waiting_list_date', ['date' => $helper->work_application_date->toDateString(), 'diff' => $helper->work_application_date->diffForHumans() ])
                @else
                    @lang('people::people.person_on_helper_waiting_list')
                @endif
            @endcomponent
        @endif
    @else
        @if($helper->work_leaving_date != null)
            @component('components.alert.warning')
                @if($helper->work_leaving_date < Carbon\Carbon::today())
                    @lang('people::people.helper_left', ['date' => $helper->work_leaving_date->toDateString() ])
                @else
                    @lang('people::people.helper_will_leave', ['date' => $helper->work_leaving_date->toDateString() ])
                @endif
            @endcomponent
        @elseif($helper->work_trial_period)
            @component('components.alert.warning')
                @lang('people::people.helper_on_trial_period', ['date' => $helper->work_starting_date->toDateString(), 'diff' => $helper->work_starting_date->diffForHumans() ])
            @endcomponent
        @endif
    @endif

    <div class="@isset($helper->person->portrait_picture)columns-3 @else columns-2 @endisset">
        @isset($helper->person->portrait_picture)
            <img src="{{ Storage::url($helper->person->portrait_picture) }}" class="img-fluid mb-4">
        @endisset
        @foreach($data as $section_label => $fields)
            @if(!empty($fields))
                <div class="card mb-4 column-break-avoid">
                    <div class="card-header">{{ $section_label }}</div>
                    <ul class="list-group list-group-flush">
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
                    </ul>
                </div>
            @endif
        @endforeach
    </div>

@endsection