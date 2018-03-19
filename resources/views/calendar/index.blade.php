@extends('layouts.app')

@section('title', 'Calendar')

@section('head-meta')
    <link href="{{ asset('css/fullcalendar.min.css') }}?v={{ $app_version }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/scheduler.min.css') }}?v={{ $app_version }}" rel="stylesheet" type="text/css">
@endsection

@section('content')
    <div id="calendar" class="mb-4"></div>
@endsection

@section('content-footer')
    <div class="modal" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="javascript:;" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eventModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pb-0">
                        <p><span id="event_editor_date_start"></span><span id="event_editor_date_end"></span></p>
                        {{ Form::bsText('title', null, [ 'placeholder' => 'Title', 'id' => 'event_editor_title' ], '') }}
                        <div class="input-group mb-3">
                            {{ Form::select('resourceId', [], 0, [ 'class' => 'custom-select', 'id' => 'event_editor_resource_id' ]) }}
                            <div class="input-group-append">
                                <label class="input-group-text" for="event_editor_resource_id">@icon(circle)</label>
                            </div>
                        </div>
                        {{ Form::bsTextarea('description', null, [ 'placeholder' => 'Description', 'id' => 'event_editor_description', 'rows' => 3 ], '') }}
                        <p id="event_editor_credits"><small class="text-muted">Event created by <span rel="author"></span></small></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" tabindex="-1" class="btn btn-outline-danger mr-auto" id="event_editor_delete">@icon(trash) Delete</button>
                        <button type="button" tabindex="-1" class="btn btn-secondary" data-dismiss="modal">@icon(times-circle) Cancel</button>
                        <button type="submit" class="btn btn-primary">@icon(check) Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="resourceModal" tabindex="-1" role="dialog" aria-labelledby="resourceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="javascript:;" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="resourceModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pb-0">
                        {{ Form::bsText('title', null, [ 'placeholder' => 'Title', 'id' => 'resource_editor_title' ], '') }}
                        {{ Form::bsText('group', null, [ 'placeholder' => 'Group (optional)', 'id' => 'resource_editor_group' ], '') }}
                        <p>{{ Form::color('color', null, [ 'placeholder' => 'Color', 'id' => 'resource_editor_color', 'type' => 'color' ]) }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" tabindex="-1" class="btn btn-outline-danger mr-auto" id="resource_editor_delete">@icon(trash) Delete</button>
                        <button type="button" tabindex="-1" class="btn btn-secondary" data-dismiss="modal">@icon(times-circle) Cancel</button>
                        <button type="submit" class="btn btn-primary">@icon(check) Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    var listEventsUrl = '{{ route('calendar.events.index') }}';
    var storeEventUrl = '{{ route('calendar.events.store') }}';
    var listResourcesUrl = '{{ route('calendar.resources.index') }}';
    var storeResourceUrl = '{{ route('calendar.resources.store') }}';
    var csrfToken = '{{ csrf_token() }}';
    var createEventAllowed = @can('create', App\CalendarEvent::class) true @else false @endcan;
    var manageResourcesAllowed = @can('create', App\CalendarResource::class) true @else false @endcan;
    var currentUserId = {{ Auth::id() }};
    var locale = '{{ App::getLocale() }}';
@endsection

@section('footer')
    <script src='{{ asset('js/moment-with-locales.min.js') }}'></script>
    <script src='{{ asset('js/fullcalendar.min.js') }}'></script>
    <script src='{{ asset('js/scheduler.min.js') }}'></script>
    <script src="{{ asset('js/calendar.js') }}?v={{ $app_version }}"></script>
@endsection
