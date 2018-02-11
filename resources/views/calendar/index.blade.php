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
    <div id='calendar'></div>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@icon(times-circle) Cancel</button>
                        <button type="submit" class="btn btn-primary">@icon(check) Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    var listEventsUrl = '{{ route('calendar.listEvents') }}';
    var storeEventUrl = '{{ route('calendar.storeEvent') }}';
    var defaltEventType = {{ $defaultType ?? 0 }};
    var typeColors = @json($typeColors);

    $(document).ready(function() {

        var calendar = $('#calendar');
        var modal = $('#eventModal');

        // Elements
        var titleElem = $('#event_editor_title');
        var descriptionElem = $('#event_editor_description');
        var dateStartElem = $('#event_editor_date_start');
        var dateEndElem = $('#event_editor_date_end');
        var typeElem = $('#event_editor_type');

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
            selectable: true,
            selectHelper: false,
            select: createEvent,
            unselectAuto: false,
            eventClick: editEvent,
        });

        /**
        * Creates a new event using modal dialog
        **/
        function createEvent(start, end) {
            // Prepare dialog
            modal.find('.modal-title').text('Create Event');
            dateStartElem.text(getDateStartLabel(start));
            dateEndElem.text(getDateEndLabel(start, end));
            titleElem.val('');
            descriptionElem.val('');
            typeElem.val(defaltEventType).change();

            // Action on cancel
            modal.on('hide.bs.modal', function (e) {
                calendar.fullCalendar('unselect');
            });

            // Action on submit
            modal.find('form').off().on('submit', function(){
                $.post(storeEventUrl, {
                    _token: '{{ csrf_token() }}',
                    title: titleElem.val(),
                    description: descriptionElem.val(),
                    type: typeElem.val(),
                    start: start.format(),
                    end: end ? end.format() : null,
                }, function(eventData) {
                    modal.modal('hide');
                    calendar.fullCalendar('renderEvent', eventData, true);
                })
                .fail(function(jqXHR, textStatus) {
                    alert('Error: ' + (jqXHR.responseJSON.message ? jqXHR.responseJSON.message : jqXHR.responseText));
                });
            });

            // Show modal
            modal.modal('show');
            titleElem.focus();
        }

        function editEvent(calEvent, jsEvent, view) {
            // Prepare dialog
            modal.find('.modal-title').text('Edit Event');
            dateStartElem.text(getDateStartLabel(calEvent.start));
            dateEndElem.text(getDateEndLabel(calEvent.start, calEvent.end));
            titleElem.val(calEvent.title);
            descriptionElem.val(calEvent.description);
            typeElem.val(calEvent.type).change();

            // Action on submit
            modal.find('form').off().on('submit', function(){
                $.post(calEvent.updateUrl, {
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT',
                    title: titleElem.val(),
                    description: descriptionElem.val(),
                    type: typeElem.val(),
                }, function() {
                    modal.modal('hide');
                    calEvent.title = titleElem.val();
                    calEvent.description = descriptionElem.val();
                    calEvent.type = typeElem.val();
                    calEvent.color = typeColors[calEvent.type];
                    calendar.fullCalendar('updateEvent', calEvent, true);
                })
                .fail(function(jqXHR, textStatus) {
                    alert('Error: ' + (jqXHR.responseJSON.message ? jqXHR.responseJSON.message : jqXHR.responseText));
                });
            });

            // Show modal
            modal.modal('show');
            titleElem.select();
            return false;
        }

        /**
        * Updates an event after dragging / resizing
        */
        function updateEventDate(calEvent, delta, revertFunc) {
            $.post(calEvent.updateDateUrl, {
                _token: '{{ csrf_token() }}',
                _method: 'PUT',
                start: calEvent.start.format(),
                end: calEvent.end ? calEvent.end.format() : null,
            })
            .fail(function(jqXHR, textStatus) {
                alert('Error: ' + (jqXHR.responseJSON.message ? jqXHR.responseJSON.message : jqXHR.responseText));
                revertFunc();
            });
        }

        function getDateStartLabel(start) {
            var isMidNight = start.format('HH:mm:ss') == '00:00:00';
            return start.format(isMidNight ? 'LL' : 'LLL'); // LL: Date, LLL: Date with time
        }

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

        typeElem.on('change', setTypeElemColor)
        function setTypeElemColor() {
            var color = typeColors[typeElem.val()];
            typeElem.siblings().find('label').css('color', color ? color : 'inherit');
        }
        setTypeElemColor();

    });
@endsection
