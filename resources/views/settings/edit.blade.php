@extends('layouts.app')

@section('title', __('app.settings'))

@section('content')

    {!! Form::open(['route' => [ 'settings.update' ], 'method' => 'put', 'files' => true]) !!}

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            @foreach($sections as $section_key => $section_label)
                <li class="nav-item" role="presentation">
                    <a
                        class="nav-link"
                        id="{{ $section_key }}-tab"
                        data-toggle="tab"
                        href="#{{ $section_key }}"
                        role="tab"
                        aria-controls="{{ $section_key }}"
                        aria-selected="true"
                    >
                        {{ $section_label }}
                    </a>
                </li>
            @endforeach
        </ul>
        <div class="tab-content" id="myTabContent">
            @foreach($sections as $section_key => $section_label)
                <div
                    class="tab-pane fade show p-2"
                    id="{{ $section_key }}"
                    role="tabpanel"
                    aria-labelledby="{{ $section_key }}-tab"
                >
                    @foreach($fields->where('section', $section_key) as $field_key => $field)
                        @include('settings.field')
                    @endforeach
                </div>
            @endforeach
        </div>
        <hr>
        <p>
            {{ Form::bsSubmitButton(__('app.update')) }}
            <button class="btn btn-secondary" type="submit" name="reset" onclick="return confirm('@lang('app.confirm_reset_to_default_settings')')">
                <x-icon icon="undo"/> @lang('app.reset_to_default_settings')
            </button>
        </p>

    {!! Form::close() !!}

@endsection

@section('script')
$(function(){
    const value = sessionStorage.getItem('settings.tab')
    if (value !== undefined && value != null && value.length > 0) {
        $('#' + value).tab('show')
    } else {
        $('#{{ array_keys($sections)[0] }}-tab').tab('show')
    }
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        sessionStorage.setItem('settings.tab', e.target.id)
    })
})
@endsection
