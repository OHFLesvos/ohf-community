@extends('layouts.app')

@section('title', __('cmtyvol.edit'))

@section('content')

    {!! Form::model($cmtyvol, ['route' => ['cmtyvol.update', $cmtyvol], 'method' => 'put', 'files' => true]) !!}

        <div class="columns-3 mb-4">
            @foreach($data as $section_label => $fields)
                @if(! empty($fields))
                    <div class="card mb-4 column-break-avoid">
                        <div class="card-header">{{ $section_label }}</div>
                        <div class="card-body">
                            @include('cmtyvol.form')
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <p>
            {{ Form::bsSubmitButton(__('app.update')) }}
        </p>

    {!! Form::close() !!}

@endsection

@section('script')
    $(function () {
        // Make popovers work
        $('[data-toggle="popover"]').popover();
    });
@endsection
