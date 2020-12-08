@extends('layouts.app')

@section('title', __('cmtyvol.view'))

@section('content')
    @if($cmtyvol->work_starting_date == null)
        <x-alert type="warning">
            @lang('people.no_started_date_set')
        </x-alert>
    @elseif($cmtyvol->work_starting_date->gt(today()))
        <x-alert type="warning">
            @lang('cmtyvol.has_not_started_yet', ['date' => $cmtyvol->work_starting_date->toDateString() ])
        </x-alert>
    @else
        @if($cmtyvol->work_leaving_date != null)
            <x-alert type="info">
                @if($cmtyvol->work_leaving_date < Carbon\Carbon::today())
                    @lang('cmtyvol.left_on_date', ['date' => $cmtyvol->work_leaving_date->toDateString() ])
                @else
                    @lang('cmtyvol.will_leave_on_date', ['date' => $cmtyvol->work_leaving_date->toDateString() ])
                @endif
            </x-alert>
        @endif
    @endif
    <div class="columns-3">
        @foreach($data as $section => $fields)
            <div class="card shadow-sm mb-4 column-break-avoid">
                <div class="card-header">
                    {{ $sections[$section] }}
                    <a href="{{ route('cmtyvol.edit', [$cmtyvol, 'section' => $section]) }}" class="float-right"><x-icon icon="edit"/></a>
                </div>
                <ul class="list-group list-group-flush">
                    @if(! empty($fields))
                        @foreach($fields as $field)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-sm-5">
                                        @isset($field['icon'])
                                            <x-icon :icon="$field['icon']" :style="$field['icon_style']"/>
                                        @endisset
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

@push('footer')
    <script>
        $(function () {
            // Make popovers work
            $('[data-toggle="popover"]').popover();
        });
    </script>
    <script src="{{ mix('js/cmtyvol.js') }}"></script>
@endpush
