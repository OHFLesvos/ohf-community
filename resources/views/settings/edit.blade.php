@extends('layouts.app')

@section('title', __('app.settings'))

@section('content')

    {!! Form::open(['route' => [ 'settings.update' ], 'method' => 'put']) !!}

        @foreach($sections as $section_key => $section_label)
            @if($fields->where('section', $section_key)->count() > 0)
                <h2 class="display-4">{{ $section_label }}</h2>
                <div class="mb-4">
                    @foreach($fields->where('section', $section_key) as $field_key => $field)
                        @include('settings.field')
                    @endforeach
                </div>
                <hr>
            @endif
        @endforeach

        <p>
            {{ Form::bsSubmitButton(__('app.update')) }}
        </p>

    {!! Form::close() !!}

@endsection
