@extends('layouts.app')

@section('title', __('cmtyvol.community_volunteer'))
@section('site-title', $cmtyvol->full_name . ' - ' . __('cmtyvol.community_volunteer'))

@section('content')
    <h1 class="display-4 mb-4">{{ $cmtyvol->full_name }}</h1>
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
    <div class="card-columns">
        @foreach($data as $section => $fields)
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    {{ $sections[$section] }}
                </div>
                <ul class="list-group list-group-flush">
                    @if(! empty($fields))
                        @foreach($fields as $field)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-lg-5">
                                        @isset($field['icon'])
                                            <x-icon :icon="$field['icon']" :style="$field['icon_style']"/>
                                        @endisset
                                        <strong>{{ $field['label'] }}</strong>
                                    </div>
                                    <div class="col-lg">
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
        @can('viewAny', App\Model\Comment::class)
            @can('update', $cmtyvol)
                <div class="column-break-avoid">
                    <h4>@lang('app.comments')</h4>
                    <div id="cmtyvol-app">
                        <cmtyvol-comments id="{{ $cmtyvol->id }}">
                            @lang('app.loading')
                        </cmtyvol-comments>
                    </div>
                </div>
            @endcan
        @endcan
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
