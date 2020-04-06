@extends('layouts.app')

@section('title', __('app.settings'))

@section('content')

    {!! Form::open(['route' => [ 'settings.update' ], 'method' => 'put', 'files' => true]) !!}

        @foreach($sections as $section_key => $section_label)
            @if($fields->where('section', $section_key)->count() > 0)
                <div class="row">
                    <div class="col-sm-6 col-lg-4">
                        <h2 class="display-4">{{ $section_label }}</h2>
                    </div>
                    <div class="col-sm-6  col-lg-8">
                        @foreach($fields->where('section', $section_key) as $field_key => $field)
                            @include('settings.field')
                        @endforeach
                    </div>
                </div>
                <hr>
            @endif
        @endforeach

        <p>
            {{ Form::bsSubmitButton(__('app.update')) }}
            <button class="btn btn-secondary" type="submit" name="reset">
                @icon(undo) @lang('app.reset_to_default_settings')
            </button>
        </p>

    {!! Form::close() !!}

@endsection
