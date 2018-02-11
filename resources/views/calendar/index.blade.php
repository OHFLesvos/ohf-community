@extends('layouts.app')

@php
    $defaultType = $types->filter(function($e){
            return $e->default;
        })
        ->pluck('id')
        ->first();
    $typeColors = $types->mapWithKeys(function($e){
            return [$e->id => $e->color];
        })
        ->toArray();
@endphp

@section('title', 'Calendar')

@section('head-meta')
    <link href="{{ asset('css/fullcalendar.min.css') }}?v={{ $app_version }}" rel="stylesheet" type="text/css">
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
                        {{ Form::bsText('title', null, [ 'placeholder' => 'Title', 'id' => 'event_editor_title', 'tab' ], '') }}
                        @php
                            $typesArray = $types->mapWithKeys(function($e){
                                return [$e->id => $e->name];
                            })
                            ->toArray();
                        @endphp
                        <div class="input-group mb-3">
                            {{ Form::select('type', $typesArray, $defaultType ?? 0, [ 'class' => 'custom-select', 'id' => 'event_editor_type' ]) }}
                            <div class="input-group-append">
                                <label class="input-group-text" for="event_editor_type">@icon(circle)</label>
                            </div>
                        </div>
                        {{ Form::bsTextarea('description', null, [ 'placeholder' => 'Description', 'id' => 'event_editor_description', 'rows' => 3 ], '') }}
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
@endsection

@section('script')
    var listEventsUrl = '{{ route('calendar.events.index') }}';
    var storeEventUrl = '{{ route('calendar.events.store') }}';
    var defaltEventType = {{ $defaultType ?? 0 }};
    var typeColors = @json($typeColors);
    var csrfToken = '{{ csrf_token() }}';
    var createEventAllowed = true; // TODO

    $(document).ready(function() {

        var calendar = $('#calendar');
        var modal = $('#eventModal');

        // Elements
        var titleElem = $('#event_editor_title');
        var descriptionElem = $('#event_editor_description');
        var dateStartElem = $('#event_editor_date_start');
        var dateEndElem = $('#event_editor_date_end');
        var typeElem = $('#event_editor_type');
        var deleteButton = $('#event_editor_delete');
        var submitButton = modal.find('button[type="submit"]');

        // Initialite the calendar
        calendar.fullCalendar({
            height: "auto",
            //locale: 'de',
            //timeFormat: 'H:mm',
            minTime: '08:00', // TODO  scrollTime: '08:00',
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'agendaDay,agendaWeek,month,listWeek'
            },
            views: {
                agendaDay: {
                    buttonText: 'Day'
                },
                agendaWeek: {
                    buttonText: 'Week'
                },
                month: {
                    buttonText: 'Month'
                },
                listWeek: {
                    buttonText: 'List'
                }
            },
            defaultView: 'agendaWeek',
            firstDay: 1,
            weekends: true,
            weekNumbers: true,
            weekNumbersWithinDays: true,
            businessHours: {
                dow: [ 1, 2, 3, 4, 5, 6 ],
                start: '10:00',
                end: '19:00',
            },      
            navLinks: true,
            eventLimit: true,
            events: listEventsUrl,
            editable: false,
            eventDrop: updateEventDate,
            eventResize: updateEventDate,
            selectable: createEventAllowed,
            selectHelper: false,
            select: createEvent,
            unselectAuto: false,
            eventClick: editEvent,
        });

        /**
        * Create a new event using modal dialog
        */
        function createEvent(start, end) {
            // Prepare modal dialog
            modal.find('.modal-title').text('Create Event');
            dateStartElem.text(getDateStartLabel(start));
            dateEndElem.text(getDateEndLabel(start, end));
            titleElem.val('').prop("readonly", false);
            descriptionElem.val('').prop("readonly", false);
            typeElem.val(defaltEventType).change().prop("disabled", false);
            deleteButton.hide();
            submitButton.show();

            // Action on cancel: Hide modal dialog
            modal.on('hide.bs.modal', function (e) {
                calendar.fullCalendar('unselect');
            });

            // Action on submit: Store event
            modal.find('form').off().on('submit', function(){
                $.ajax(storeEventUrl, {
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        title: titleElem.val(),
                        description: descriptionElem.val(),
                        type: typeElem.val(),
                        start: start.format(),
                        end: end ? end.format() : null,
                    }
                })
                .done(function(eventData) {
                    modal.modal('hide');
                    calendar.fullCalendar('renderEvent', eventData, true);
                })
                .fail(function(jqXHR, textStatus) {
                    alert('Error: ' + (jqXHR.responseJSON.message ? jqXHR.responseJSON.message : jqXHR.responseText));
                });
            });

            // Show modal
            modal.modal('show');

            // Focus title input
            titleElem.focus();
        }
        /**
        * Edit a new event using modal dialog
        */
        function showEvent(calEvent) {
            // Prepare dialog
            modal.find('.modal-title').text('View Event');
            dateStartElem.text(getDateStartLabel(calEvent.start));
            dateEndElem.text(getDateEndLabel(calEvent.start, calEvent.end));
            titleElem.val(calEvent.title).prop("readonly", true);
            descriptionElem.val(calEvent.description).prop("readonly", true);
            typeElem.val(calEvent.type).change().prop("disabled", true);
            deleteButton.hide();
            submitButton.hide();

            // Show modal
            modal.modal('show');

            // Return false so default behaviour (navigate to event URL) is supressed
            return false;
        }

        /**
        * Edit a new event using modal dialog
        */
        function editEvent(calEvent, jsEvent, view) {
            if (!calEvent.editable) {
                return showEvent(calEvent);
            }

            // Prepare dialog
            modal.find('.modal-title').text('Edit Event');
            dateStartElem.text(getDateStartLabel(calEvent.start));
            dateEndElem.text(getDateEndLabel(calEvent.start, calEvent.end));
            titleElem.val(calEvent.title).prop("readonly", false);
            descriptionElem.val(calEvent.description).prop("readonly", false);
            typeElem.val(calEvent.type).change().prop("disabled", false);
            deleteButton.show();
            submitButton.show();

            // Action on form submit: Update event
            modal.find('form').off().on('submit', function(){
                $.ajax(calEvent.updateUrl, {
                    method: 'PUT',
                    data: {
                        _token: csrfToken,
                        title: titleElem.val(),
                        description: descriptionElem.val(),
                        type: typeElem.val(),
                    }
                })
                .done(function() {
                    modal.modal('hide');
                    calEvent.title = titleElem.val();
                    calEvent.description = descriptionElem.val();
                    calEvent.type = typeElem.val();
                    calEvent.color = typeColors[calEvent.type];
                    calendar.fullCalendar('updateEvent', calEvent);
                })
                .fail(function(jqXHR, textStatus) {
                    alert('Error: ' + (jqXHR.responseJSON.message ? jqXHR.responseJSON.message : jqXHR.responseText));
                });
            });

            // Action on delete button click: Delete event
            deleteButton.off().on('click', function(){
                if (confirm('Really delete \'' + calEvent.title + '\'?')) {
                    $.ajax(calEvent.deleteUrl, {
                        method: 'DELETE',
                        data: {
                            _token: csrfToken,
                        },
                    })
                    .done(function() {
                        modal.modal('hide');
                        calendar.fullCalendar('removeEvents', calEvent.id);
                    })
                    .fail(function(jqXHR, textStatus) {
                        alert('Error: ' + (jqXHR.responseJSON.message ? jqXHR.responseJSON.message : jqXHR.responseText));
                    });
                }
            });

            // Show modal
            modal.modal('show');

            // Select title
            titleElem.select();

            // Return false so default behaviour (navigate to event URL) is supressed
            return false;
        }

        /**
        * Updates an event after dragging / resizing
        */
        function updateEventDate(calEvent, delta, revertFunc) {
            $.ajax(calEvent.updateDateUrl, {
                method: 'PUT',
                data : {
                    _token: csrfToken,
                    start: calEvent.start.format(),
                    end: calEvent.end ? calEvent.end.format() : null,
                }
            })
            .fail(function(jqXHR, textStatus) {
                alert('Error: ' + (jqXHR.responseJSON.message ? jqXHR.responseJSON.message : jqXHR.responseText));
                revertFunc();
            });
        }

        /**
        * Creates a label text for the start date
        */
        function getDateStartLabel(start) {
            var isMidNight = start.format('HH:mm:ss') == '00:00:00';
            return start.format(isMidNight ? 'LL' : 'LLL'); // LL: Date, LLL: Date with time
        }

        /**
        * Creates a label text for the end date
        */
        function getDateEndLabel(start, end) {
            var prefix = ' - ';
            if (start.isSame(end, 'day')) {
                return prefix + end.format('LT'); // LT: Time
            }
            var startIsMidNight = start.format('HH:mm:ss') == '00:00:00';
            var endIsMidNight = end.format('HH:mm:ss') == '00:00:00';
            if (startIsMidNight && endIsMidNight) {
                var newEnd = end.clone().subtract(1, 'day');
                return start.isSame(newEnd, 'day') ? '' : prefix + newEnd.format('LL');
            }
            return prefix + end.format('LLL');
        }

        // Change color of the label next to the type select
        typeElem.on('change', setTypeElemColor)
        function setTypeElemColor() {
            var color = typeColors[typeElem.val()];
            typeElem.siblings().find('label').css('color', color ? color : 'inherit');
        }
        setTypeElemColor();

    });
@endsection
