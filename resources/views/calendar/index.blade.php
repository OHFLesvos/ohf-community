@extends('layouts.app')

@section('title', 'Calendar')

@section('content')
    <div id='calendar'></div>
@endsection

@section('script')
    $(document).ready(function() {
        $('#calendar').fullCalendar({
            height: "auto",
            //locale: 'de',
            //timeFormat: 'H:mm',
            minTime: '08:00',
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'agendaDay,agendaWeek,month,listWeek'
            },
            /*
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
            */
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
            //showNonCurrentDates: false,
            //defaultDate: '2018-02-12',
              navLinks: true, // can click day/week names to navigate views
              editable: true,
              eventLimit: true, // allow "more" link when too many events
              events: '{{ route('calendar.data') }}',
            });

    });
@endsection
