/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 750);
/******/ })
/************************************************************************/
/******/ ({

/***/ 750:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(751);


/***/ }),

/***/ 751:
/***/ (function(module, exports) {

//
// Calendar
//

// import 'moment';
// import $ from 'jquery';
// import 'fullcalendar';
// import 'fullcalendar-scheduler';

var lastResourceGroup = null;

$(document).ready(function () {

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
    var creditsElement = $('#event_editor_credits');

    var resourceTitleElem = $('#resource_editor_title');
    var resourceGroupElem = $('#resource_editor_group');
    var resourceColorElem = $('#resource_editor_color');
    var resourceDeleteButton = $('#resource_editor_delete');
    var resourceSubmitButton = resourceModal.find('button[type="submit"]');

    // Initialite the calendar
    calendar.fullCalendar({
        themeSystem: 'bootstrap4',
        height: "auto",
        locale: locale,
        slotLabelFormat: 'H:mm',
        minTime: '08:00', // TODO  scrollTime: '08:00',
        header: {
            left: manageResourcesAllowed ? 'prev,next today promptResource' : 'prev,next today',
            center: 'title',
            right: 'agendaDay,agendaWeek,month,listWeek,timelineDay'
        },
        customButtons: {
            promptResource: {
                text: '+ Resource',
                click: createResource
            }
        },
        resourceLabelText: 'Resources',
        resourceRender: function resourceRender(resource, cellEls) {
            if (manageResourcesAllowed) {
                cellEls.on('click', function () {
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
        defaultView: localStorage.getItem('calendar-view-name') ? localStorage.getItem('calendar-view-name') : 'agendaWeek',
        viewRender: function viewRender(view, element) {
            localStorage.setItem('calendar-view-name', view.name);
        },
        firstDay: 1,
        weekends: true,
        weekNumbers: true,
        weekNumbersWithinDays: true,
        businessHours: {
            dow: [1, 2, 3, 4, 5, 6],
            start: '10:00',
            end: '19:00'
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
        resourceGroupField: 'group'
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
        resourceModal.find('form').off().on('submit', function () {
            $.ajax(storeResourceUrl, {
                method: 'POST',
                data: {
                    _token: csrfToken,
                    title: resourceTitleElem.val(),
                    group: resourceGroupElem.val(),
                    color: resourceColorElem.val()
                }
            }).done(function (resourceData) {
                lastResourceGroup = resourceGroupElem.val();
                resourceModal.modal('hide');
                calendar.fullCalendar('addResource', resourceData, true);
            }).fail(function (jqXHR, textStatus) {
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
        resourceModal.find('form').off().on('submit', function () {
            $.ajax(resource.url, {
                method: 'PUT',
                data: {
                    _token: csrfToken,
                    title: resourceTitleElem.val(),
                    group: resourceGroupElem.val(),
                    color: resourceColorElem.val()
                }
            }).done(function () {
                resourceModal.modal('hide');
                calendar.fullCalendar('refetchResources');
            }).fail(function (jqXHR, textStatus) {
                alert('Error: ' + (jqXHR.responseJSON.message ? jqXHR.responseJSON.message : jqXHR.responseText));
            });
        });

        // Action on delete button click: Delete event
        resourceDeleteButton.off().on('click', function () {
            if (confirm('Are you sure you want to delete thre resource \'' + resource.title + '\'?')) {
                $.ajax(resource.url, {
                    method: 'DELETE',
                    data: {
                        _token: csrfToken
                    }
                }).done(function () {
                    resourceModal.modal('hide');
                    calendar.fullCalendar('removeResource', resource);
                }).fail(function (jqXHR, textStatus) {
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
        modal.find('form').off().on('submit', function () {
            $.ajax(storeEventUrl, {
                method: 'POST',
                data: {
                    _token: csrfToken,
                    title: titleElem.val(),
                    description: descriptionElem.val(),
                    resourceId: resourceIdInputElem.val(),
                    start: start.format(),
                    end: end ? end.format() : null
                }
            }).done(function (eventData) {
                modal.modal('hide');
                calendar.fullCalendar('renderEvent', eventData, true);
            }).fail(function (jqXHR, textStatus) {
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
        if (calEvent.user.id != currentUserId) {
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
        if (calEvent.user.id != currentUserId) {
            creditsElement.find('[rel="author"]').text(calEvent.user.name);
            creditsElement.show();
        } else {
            creditsElement.hide();
        }

        // Action on form submit: Update event
        modal.find('form').off().on('submit', function () {
            $.ajax(calEvent.url, {
                method: 'PUT',
                data: {
                    _token: csrfToken,
                    title: titleElem.val(),
                    description: descriptionElem.val(),
                    resourceId: resourceIdInputElem.val()
                }
            }).done(function () {
                modal.modal('hide');
                calEvent.title = titleElem.val();
                calEvent.description = descriptionElem.val();
                calEvent.resourceId = resourceIdInputElem.val();
                calendar.fullCalendar('updateEvent', calEvent);
            }).fail(function (jqXHR, textStatus) {
                alert('Error: ' + (jqXHR.responseJSON.message ? jqXHR.responseJSON.message : jqXHR.responseText));
            });
        });

        // Action on delete button click: Delete event
        deleteButton.off().on('click', function () {
            if (confirm('Really delete event \'' + calEvent.title + '\'?')) {
                $.ajax(calEvent.url, {
                    method: 'DELETE',
                    data: {
                        _token: csrfToken
                    }
                }).done(function () {
                    modal.modal('hide');
                    calendar.fullCalendar('removeEvents', calEvent.id);
                }).fail(function (jqXHR, textStatus) {
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
            data: {
                _token: csrfToken,
                start: calEvent.start.format(),
                end: calEvent.end ? calEvent.end.format() : null,
                resourceId: calEvent.resourceId
            }
        }).fail(function (jqXHR, textStatus) {
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
                text: item.title
            }));
            resourceColors[item.id] = item.eventColor;
        });
        console.log(resourceColors);
        resourceIdInputElem.val(resourceId).change().prop("disabled", disabled);

        // Change color of the label next to the resource select
        var setresourceIdInputElemColor = function setresourceIdInputElemColor() {
            var color = resourceColors[resourceIdInputElem.val()];
            resourceIdInputElem.siblings().find('label').css('color', color ? color : 'inherit');
        };
        resourceIdInputElem.off('change').on('change', setresourceIdInputElemColor);
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

/***/ })

/******/ });