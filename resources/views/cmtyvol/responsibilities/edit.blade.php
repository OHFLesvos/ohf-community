@extends('layouts.app')

@section('title', __('responsibilities.edit_responsibility'))

@section('content')

    <div class="row">
        <div class="col-md">
            {!! Form::model($responsibility, ['route' => ['cmtyvol.responsibilities.update', $responsibility], 'method' => 'put']) !!}
                {{ Form::bsText('name', null, [ 'required' ], __('app.name')) }}
                {{ Form::bsNumber('capacity', null, [ 'min' => 0 ], __('app.capacity')) }}
                <p>{{ Form::bsCheckbox('available', 1, null, __('app.available')) }}</p>
                <p>{{ Form::bsSubmitButton(__('app.update')) }}</p>
            {!! Form::close() !!}
        </div>
        <div class="col-md">
            <div class="card mb-3">
                <div class="card-header">@lang('cmtyvol.active')</div>
                <div class="list-group list-group-flush">
                    @if($responsibility->communityVolunteers()->workStatus('active')->count() > 0)
                        @foreach($responsibility->communityVolunteers()->workStatus('active')->get() as $cmtyvol)
                            <a href="{{ route('cmtyvol.show', $cmtyvol) }}" class="list-group-item list-group-item-action" target="_blank">{{ $cmtyvol->fullName }}</a>
                        @endforeach
                    @else
                        <div class="list-group-item"><em>@lang('cmtyvol.no_active')</em></div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
