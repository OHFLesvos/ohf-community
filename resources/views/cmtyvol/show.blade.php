@extends('layouts.app')

@section('title', __('cmtyvol.view'))

@section('content')

    @if($cmtyvol->work_starting_date == null)
        @component('components.alert.warning')
            @lang('people.no_started_date_set')
        @endcomponent
    @elseif($cmtyvol->work_starting_date->gt(today()))
        @component('components.alert.warning')
            @lang('cmtyvol.has_not_started_yet', ['date' => $cmtyvol->work_starting_date->toDateString() ])
        @endcomponent
    @else
        @if($cmtyvol->work_leaving_date != null)
            @component('components.alert.warning')
                @if($cmtyvol->work_leaving_date < Carbon\Carbon::today())
                    @lang('cmtyvol.left_on_date', ['date' => $cmtyvol->work_leaving_date->toDateString() ])
                @else
                    @lang('cmtyvol.will_leave_on_date', ['date' => $cmtyvol->work_leaving_date->toDateString() ])
                @endif
            @endcomponent
        @endif
    @endif

    <div class="columns-3">
        @foreach($data as $section => $fields)
            <div class="card mb-4 column-break-avoid">
                <div class="card-header">
                    {{ $sections[$section] }}
                    <a href="{{ route('cmtyvol.edit', [$cmtyvol, 'section' => $section]) }}" class="float-right">@icon(edit)</a>
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

    <hr>
    <h4>@lang('app.comments')</h4>
    <div id="cmtyvol-app">
        <cmtyvol-comments id="{{ $cmtyvol->id }}">
            @lang('app.loading')
        </cmtyvol-comments>
    </div>

@endsection

@section('script')
    $(function () {
        // Make popovers work
        $('[data-toggle="popover"]').popover();
    });
@endsection

@section('footer')
    <script src="{{ asset('js/cmtyvol.js') }}?v={{ $app_version }}"></script>
@endsection
