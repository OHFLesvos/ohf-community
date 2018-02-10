@extends('layouts.app')

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
                        <p>Date/Time: <span id="event_editor_date_start"></span> - <span id="event_editor_date_end"></span></p>
                        {{ Form::bsText('title', null, [ 'placeholder' => 'Title', 'id' => 'event_editor_title' ], '') }}
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
    $(document).ready(function() {

        var calendar = $('#calendar');
        var modal = $('#eventModal');

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
            events: '{{ route('calendar.listEvents') }}',
            editable: false,
            eventDrop: updateEvent,
            eventResize: updateEvent,
            selectable: true,
            selectHelper: true,
            select: storeEvent,
            unselectAuto: false,         
        });

        function storeEvent(start, end) {
            var titleElem = $('#event_editor_title');
            var descriptionElem = $('#event_editor_description');
            var dateStartElem = $('#event_editor_date_start');
            var dateEndElem = $('#event_editor_date_end');
            modal.find('.modal-title').text('Create Event');
            dateStartElem.text(start.format());
            dateEndElem.text(end.format());
            titleElem.val('');
            descriptionElem.val('');
            modal.on('hide.bs.modal', function (e) {
                calendar.fullCalendar('unselect');
            });
            modal.find('form').off().on('submit', function(){
                $.post('{{ route('calendar.storeEvent') }}', {
                    _token: '{{ csrf_token() }}',
                    title: titleElem.val(),
                    description: descriptionElem.val(),
                    start: start.format(),
                    end: end ? end.format() : null,
                }, function(eventData) {
                    modal.modal('hide');
                    calendar.fullCalendar('renderEvent', eventData, true);
                })
                .fail(function(jqXHR, textStatus) {
                    alert(textStatus + ': ' + jqXHR.responseJSON);
                    calendar.fullCalendar('unselect');
                });
            });
            modal.modal('show');
            titleElem.focus();
        }

        function updateEvent(event, delta, revertFunc) {
            $.post(event.updateUrl, {
                _token: '{{ csrf_token() }}',
                _method: 'PUT',
                start: event.start.format(),
                end: event.end ? event.end.format() : null,
            })
            .fail(function(jqXHR, textStatus) {
                alert(textStatus + ': ' + jqXHR.responseJSON);
                revertFunc();
            });
        }
    });
@endsection
