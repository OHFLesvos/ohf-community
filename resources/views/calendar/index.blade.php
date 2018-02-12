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

    var lastResourceGroup = null;

    $(document).ready(function() {

        var calendar = $('#calendar');
        var modal = $('#eventModal');
        var resourceModal = $('#resourceModal');

        // Elements
        var titleElem = $('#event_editor_title');
        var descriptionElem = $('#event_editor_description');
        var dateStartElem = $('#event_editor_date_start');
        var dateEndElem = $('#event_editor_date_end');
        var resourceIdInputElem = $('#event_editor_resource_id');
        var deleteButton = $('#event_editor_delete');
        var submitButton = modal.find('button[type="submit"]');
        var creditsElement =  $('#event_editor_credits');

        var resourceTitleElem = $('#resource_editor_title');
        var resourceGroupElem = $('#resource_editor_group');
        var resourceColorElem = $('#resource_editor_color');
        var resourceDeleteButton = $('#resource_editor_delete');
        var resourceSubmitButton = resourceModal.find('button[type="submit"]');

        // Initialite the calendar
        calendar.fullCalendar({
            height: "auto",
            //locale: 'de',
            //timeFormat: 'H:mm',
            minTime: '08:00', // TODO  scrollTime: '08:00',
            header: {
                left: manageResourcesAllowed ? 'prev,next today promptResource' : 'prev,next today',
                center: 'title',
                right: 'agendaDay,agendaWeek,month,listWeek,timelineDay'
            },
            customButtons: {
                promptResource: {
                    text: '+ Resource',
                    click: createResource,
                }
            },
            resourceLabelText: 'Resources',
            resourceRender: function(resource, cellEls) {
                if (manageResourcesAllowed) {
                    cellEls.on('click', function() {
                        editResource(resource);
                    });
                }
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
                },
                timelineDay: {
                    buttonText: 'Timeline'
                }
            },
            defaultView: sessionStorage.getItem('calendar-view-name') ? sessionStorage.getItem('calendar-view-name') : 'agendaWeek',
            viewRender: function(view, element){
                sessionStorage.setItem('calendar-view-name', view.name)
            },
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
            schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
            resources: listResourcesUrl,
            resourceOrder: 'title',
            //eventOverlap: false, // TODO
            resourceGroupField: 'group',
        });

        /**
        * Create a new resource using modal dialog
        */
        function createResource() {
            // Prepare modal dialog
            resourceModal.find('.modal-title').text('Create Resource');

            resourceTitleElem.val('');
            resourceGroupElem.val(lastResourceGroup ? lastResourceGroup : '');
            resourceColorElem.val(getRandomColor());

            resourceDeleteButton.hide();
            resourceSubmitButton.show();

            // Action on submit: Store resource
            resourceModal.find('form').off().on('submit', function(){
                $.ajax(storeResourceUrl, {
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        title: resourceTitleElem.val(),
                        group: resourceGroupElem.val(),
                        color: resourceColorElem.val(),
                    }
                })
                .done(function(resourceData) {
                    lastResourceGroup = resourceGroupElem.val();
                    resourceModal.modal('hide');
                    calendar.fullCalendar('addResource', resourceData, true);
                })
                .fail(function(jqXHR, textStatus) {
                    alert('Error: ' + (jqXHR.responseJSON.message ? jqXHR.responseJSON.message : jqXHR.responseText));
                });
            });

            // Show modal
            resourceModal.modal('show');

            // Focus title input
            resourceTitleElem.focus();
        }

        /**
        * Edit a resource using modal dialog
        */
        function editResource(resource) {
            // Prepare modal dialog
            resourceModal.find('.modal-title').text('Create Resource');

            resourceTitleElem.val(resource.title);
            resourceGroupElem.val(resource.group);
            resourceColorElem.val(resource.eventColor);

            resourceDeleteButton.show();
            resourceSubmitButton.show();

            // Action on submit: Update resource
            resourceModal.find('form').off().on('submit', function(){
                $.ajax(resource.url, {
                    method: 'PUT',
                    data: {
                        _token: csrfToken,
                        title: resourceTitleElem.val(),
                        group: resourceGroupElem.val(),
                        color: resourceColorElem.val(),
                    }
                })
                .done(function() {
                    resourceModal.modal('hide');
                    calendar.fullCalendar('refetchResources');
                })
                .fail(function(jqXHR, textStatus) {
                    alert('Error: ' + (jqXHR.responseJSON.message ? jqXHR.responseJSON.message : jqXHR.responseText));
                });
            });

            // Action on delete button click: Delete event
            resourceDeleteButton.off().on('click', function(){
                if (confirm('Are you sure you want to delete thre resource \'' + resource.title + '\'?')) {
                    $.ajax(resource.url, {
                        method: 'DELETE',
                        data: {
                            _token: csrfToken,
                        },
                    })
                    .done(function() {
                        resourceModal.modal('hide');
                        calendar.fullCalendar('removeResource', resource);
                    })
                    .fail(function(jqXHR, textStatus) {
                        alert('Error: ' + (jqXHR.responseJSON.message ? jqXHR.responseJSON.message : jqXHR.responseText));
                    });
                }
            });

            // Show modal
            resourceModal.modal('show');

            // Focus title input
            resourceTitleElem.focus();
        }

        /**
        * Create a new event using modal dialog
        */
        function createEvent(start, end, jsEvent, view, resource) {
            var resources = calendar.fullCalendar('getResources');
            if (resources.length == 0) {
                alert('Please add a resource first before creating an event!');
                calendar.fullCalendar('unselect');
                return;
            }

            // Prepare modal dialog
            modal.find('.modal-title').text('Create Event');
            dateStartElem.text(getDateStartLabel(start));
            dateEndElem.text(getDateEndLabel(start, end));
            titleElem.val('').prop("readonly", false);
            descriptionElem.val('').prop("readonly", false);
            updateResourceIdSelect(resource ? resource.id : resources[0].id, false);
            deleteButton.hide();
            submitButton.show();
            creditsElement.hide();

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
                        resourceId: resourceIdInputElem.val(),
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
            updateResourceIdSelect(calEvent.resourceId, true);

            deleteButton.hide();
            submitButton.hide();
            if (calEvent.user.id != currentUserId ) {
                creditsElement.find('[rel="author"]').text(calEvent.user.name);
                creditsElement.show();
            } else {
                creditsElement.hide();
            }

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
            updateResourceIdSelect(calEvent.resourceId, false);
            deleteButton.show();
            submitButton.show();
            if (calEvent.user.id != currentUserId ) {
                creditsElement.find('[rel="author"]').text(calEvent.user.name);
                creditsElement.show();
            } else {
                creditsElement.hide();
            }

            // Action on form submit: Update event
            modal.find('form').off().on('submit', function(){
                $.ajax(calEvent.url, {
                    method: 'PUT',
                    data: {
                        _token: csrfToken,
                        title: titleElem.val(),
                        description: descriptionElem.val(),
                        resourceId: resourceIdInputElem.val(),
                    }
                })
                .done(function() {
                    modal.modal('hide');
                    calEvent.title = titleElem.val();
                    calEvent.description = descriptionElem.val();
                    calEvent.resourceId = resourceIdInputElem.val();
                    calendar.fullCalendar('updateEvent', calEvent);
                })
                .fail(function(jqXHR, textStatus) {
                    alert('Error: ' + (jqXHR.responseJSON.message ? jqXHR.responseJSON.message : jqXHR.responseText));
                });
            });

            // Action on delete button click: Delete event
            deleteButton.off().on('click', function(){
                if (confirm('Really delete event \'' + calEvent.title + '\'?')) {
                    $.ajax(calEvent.url, {
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
                    resourceId: calEvent.resourceId,
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

        function updateResourceIdSelect(resourceId, disabled) {
            var resources = calendar.fullCalendar('getResources');
            resourceIdInputElem.empty();
            var resourceColors = {};
            $.each(resources, function (i, item) {
                resourceIdInputElem.append($('<option>', { 
                    value: item.id,
                    text : item.title 
                }));
                resourceColors[item.id] = item.eventColor;
            });
            console.log(resourceColors);
            resourceIdInputElem.val(resourceId).change().prop("disabled", disabled);            

            // Change color of the label next to the resource select
            var setresourceIdInputElemColor = function() {
                var color = resourceColors[resourceIdInputElem.val()];
                resourceIdInputElem.siblings().find('label').css('color', color ? color : 'inherit');
            }
            resourceIdInputElem.off('change').on('change', setresourceIdInputElemColor)
            setresourceIdInputElemColor();
        }

    });

    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
          color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
      }
      
@endsection
