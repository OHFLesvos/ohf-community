@extends('layouts.app')

@section('title', __('app.settings'))

@section('content')

    {!! Form::open(['route' => [ $route ], 'method' => 'put']) !!}

        @if($fields->where('section', null)->count() > 0)
            <div class="mb-4">
                @foreach($fields->where('section', null) as $field_key => $field)
                    @include('settings_field')
                @endforeach
            </div>
        @endif
        @foreach($sections as $section_key => $section_label)
            @if($fields->where('section', $section_key)->count() > 0)
                <h2 class="display-4">{{ $section_label }}</h2>
                <div class="mb-4">
                    @foreach($fields->where('section', $section_key) as $field_key => $field)
                        @include('settings_field')
                    @endforeach
                </div>
            @endif
        @endforeach

        <p>
            {{ Form::bsSubmitButton(__('app.update')) }}
        </p>

    {!! Form::close() !!}

@endsection
