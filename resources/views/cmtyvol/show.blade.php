@extends('layouts.app')

@section('title', __('Community Volunteer'))
@section('site-title', $cmtyvol->full_name . ' - ' . __('Community Volunteer'))

@section('content')
    <h1 class="display-4 mb-4">{{ $cmtyvol->full_name }}</h1>
    <div class="card-columns">
        @foreach($data as $section => $fields)
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between">
                    {{ $sections[$section] }}
                    @if($section == 'occupation')
                    <a href="{{ route('cmtyvol.responsibilities', $cmtyvol) }}">
                        @lang('Edit')
                    </a>
                    @endif
                </div>
                @if($section == 'occupation')
                    @if($cmtyvol->firstWorkStartDate == null)
                        <div class="card-body p-0">
                            <x-alert type="warning" class="m-0">
                                @lang('No starting date has been set.')
                            </x-alert>
                        </div>
                    @elseif($cmtyvol->firstWorkStartDate->gt(today()))
                        <div class="card-body p-0">
                            <x-alert type="warning" class="m-0">
                                @lang('This community volunteer will start on :date.', ['date' => $cmtyvol->firstWorkStartDate->toDateString() ])
                            </x-alert>
                        </div>
                    @else
                        @if($cmtyvol->lastWorkEndDate != null)
                            <div class="card-body p-0">
                                <x-alert type="info" class="m-0">
                                    @if($cmtyvol->lastWorkEndDate < today())
                                        @lang('This community volunteer left on :date.', ['date' => $cmtyvol->lastWorkEndDate->toDateString() ])
                                    @else
                                        @lang('This community volunteer will leave on :date.', ['date' => $cmtyvol->lastWorkEndDate->toDateString() ])
                                    @endif
                                </x-alert>
                            </div>
                        @endif
                    @endif
                @endif
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
                            <em>@lang('No data registered.')</em>
                        </li>
                    @endif
                </ul>
            </div>
        @endforeach
        @can('viewAny', App\Model\Comment::class)
            @can('update', $cmtyvol)
                <div class="column-break-avoid">
                    <h4>@lang('Comments')</h4>
                    <div id="cmtyvol-app">
                        <x-spinner />
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
@endpush
