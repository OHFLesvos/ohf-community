@extends('layouts.app')

@section('title', __('helpers::responsibilities.edit_responsibility'))

@section('content')

    <div class="row">
        <div class="col-md">
            {!! Form::model($responsibility, ['route' => ['people.helpers.responsibilities.update', $responsibility], 'method' => 'put']) !!}
                {{ Form::bsText('name', null, [ 'required' ], __('app.name')) }}
                {{ Form::bsNumber('capacity', null, [ 'min' => 0 ], __('app.capacity')) }}
                <p>{{ Form::bsCheckbox('available', 1, null, __('app.available')) }}</p>
                <p>{{ Form::bsSubmitButton(__('app.update')) }}</p>
            {!! Form::close() !!}
        </div>
        <div class="col-md">
            <div class="card mb-3">
                <div class="card-header">@lang('helpers::helpers.active_helpers')</div>
                <div class="list-group list-group-flush">
                    @if($responsibility->helpers()->active()->count() > 0)
                        @foreach($responsibility->helpers()->active()->get() as $helper)
                            <a href="{{ route('people.helpers.show', $helper) }}" class="list-group-item list-group-item-action" target="_blank">{{ $helper->person->fullName }}</a>
                        @endforeach
                    @else
                        <div class="list-group-item"><em>@lang('helpers::helpers.no_active_helpers')</em></div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
