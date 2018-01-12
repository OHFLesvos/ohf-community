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
/******/ 	return __webpack_require__(__webpack_require__.s = 747);
/******/ })
/************************************************************************/
/******/ ({

/***/ 107:
/***/ (function(module, exports) {

module.exports = {
    updatePagination: updatePagination
};

function updatePagination(container, result, callback) {
    container.empty();

    // First page
    if (result.current_page > 1) {
        container.append(createPaginationItem('&laquo;', 1, null, callback));
    } else {
        container.append(createPaginationItem('&laquo;', null, 'disabled', callback));
    }

    // Previous page
    if (result.current_page > 1) {
        container.append(createPaginationItem('&lsaquo;', result.current_page - 1, null, callback));
    } else {
        container.append(createPaginationItem('&lsaquo;', null, 'disabled', callback));
    }

    // Pages before
    for (i = 2 + Math.max(2 - (result.last_page - result.current_page), 0); i >= 1; i--) {
        if (result.current_page > i) {
            container.append(createPaginationItem(result.current_page - i, result.current_page - i, null, callback));
        }
    }

    // Current page
    container.append(createPaginationItem(result.current_page, null, 'active', callback));

    // Pages after
    for (i = 1; i <= 2 + Math.max(0, 3 - result.current_page); i++) {
        if (result.current_page + i - 1 < result.last_page) {
            container.append(createPaginationItem(result.current_page + i, result.current_page + i, null, callback));
        }
    }

    // Next page
    if (result.current_page < result.last_page) {
        container.append(createPaginationItem('&rsaquo;', result.current_page + 1, null, callback));
    } else {
        container.append(createPaginationItem('&rsaquo;', null, 'disabled', callback));
    }

    // Last page
    if (result.current_page < result.last_page) {
        container.append(createPaginationItem('&raquo;', result.last_page, null, callback));
    } else {
        container.append(createPaginationItem('&raquo;', null, 'disabled', callback));
    }
}

function createPaginationItem(content, pageTarget, elemClass, callback) {
    var elem = $('<li>').addClass('page-item');
    if (pageTarget != null) {
        elem.append($('<a>').addClass('page-link').attr('href', 'javascript:;').html(content).on('click', function () {
            callback(pageTarget);
        }));
    } else {
        elem.append($('<span>').addClass('page-link').html(content));
    }
    if (elemClass != null) {
        elem.addClass(elemClass);
    }
    return elem;
}

/***/ }),

/***/ 747:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(748);


/***/ }),

/***/ 748:
/***/ (function(module, exports, __webpack_require__) {

pagination = __webpack_require__(107);

var delayTimer;

$(function () {
    $('#filter input').on('change keyup', function (e) {
        var keyCode = e.keyCode;
        if (keyCode == 0 || keyCode == 8 || keyCode == 13 || keyCode == 27 || keyCode == 46 || keyCode >= 48 && keyCode <= 90 || keyCode >= 96 && keyCode <= 111) {
            var elem = $(this);

            $('#filter-status').html('');
            var tbody = $('#results-table tbody');
            tbody.empty();
            tbody.append($('<tr>').append($('<td>').text('Searching...').attr('colspan', 13)));

            clearTimeout(delayTimer);
            delayTimer = setTimeout(function () {
                if (keyCode == 27) {
                    // ESC
                    elem.val('').focus();
                }
                if (keyCode == 13) {
                    // Enter
                    elem.blur();
                }
                filterTable(1);
            }, 300);
        }
    });

    $('#reset-filter').on('click', function () {
        $('#filter input[name="family_name"]').val('');
        $('#filter input[name="name"]').val('');
        $('#filter input[name="police_no"]').val('');
        $('#filter input[name="case_no"]').val('');
        $('#filter input[name="medical_no"]').val('');
        $('#filter input[name="registration_no"]').val('');
        $('#filter input[name="section_card_no"]').val('');
        $('#filter input[name="temp_no"]').val('');
        $('#filter input[name="nationality"]').val('');
        $('#filter input[name="languages"]').val('');
        $('#filter input[name="skills"]').val('');
        $('#filter input[name="remarks"]').val('');
        filterTable(1);
    });

    filterTable(1);
});

function filterTable(page) {
    $('#filter-status').html('');
    var tbody = $('#results-table tbody');
    tbody.empty();
    tbody.append($('<tr>').append($('<td>').text('Searching...').attr('colspan', 13)));

    var paginator = $('#paginator');
    paginator.empty();

    var paginationInfo = $('#paginator-info');
    paginationInfo.empty();
    $.post(filterUrl, {
        "_token": csrfToken,
        "family_name": $('#filter input[name="family_name"]').val(),
        "name": $('#filter input[name="name"]').val(),
        "police_no": $('#filter input[name="police_no"]').val(),
        "case_no": $('#filter input[name="case_no"]').val(),
        "medical_no": $('#filter input[name="medical_no"]').val(),
        "registration_no": $('#filter input[name="registration_no"]').val(),
        "section_card_no": $('#filter input[name="section_card_no"]').val(),
        "temp_no": $('#filter input[name="temp_no"]').val(),
        "nationality": $('#filter input[name="nationality"]').val(),
        "languages": $('#filter input[name="languages"]').val(),
        "skills": $('#filter input[name="skills"]').val(),
        "remarks": $('#filter input[name="remarks"]').val(),
        "page": page
    }, function (result) {
        tbody.empty();
        if (result.data.length > 0) {
            $.each(result.data, function (k, v) {
                tbody.append(writeRow(v));
            });
            pagination.updatePagination(paginator, result, filterTable);
            paginationInfo.html(result.from + ' - ' + result.to + ' of ' + result.total);
        } else {
            tbody.append($('<tr>').addClass('warning').append($('<td>').text('No results').attr('colspan', 13)));
        }
    }).fail(function (jqXHR, textStatus) {
        tbody.empty();
        tbody.append($('<tr>').addClass('danger').append($('<td>').text(textStatus).attr('colspan', 13)));
    });
}

function writeRow(person) {
    var icon = '';
    if (person.gender == 'f') {
        icon = 'female';
    }
    if (person.gender == 'm') {
        icon = 'male';
    }
    return $('<tr>').attr('id', 'person-' + person.id).append($('<td>').html(icon != '' ? '<i class="fa fa-' + icon + '"></i>' : '')).append($('<td>').append($('<a>').attr('href', 'people/' + person.id).text(person.family_name))).append($('<td>').append($('<a>').attr('href', 'people/' + person.id).text(person.name))).append($('<td>').text(person.police_no)).append($('<td>').text(person.case_no)).append($('<td>').text(person.medical_no)).append($('<td>').text(person.registration_no)).append($('<td>').text(person.section_card_no)).append($('<td>').text(person.temp_no)).append($('<td>').text(person.nationality)).append($('<td>').text(person.languages)).append($('<td>').text(person.skills)).append($('<td>').text(person.remarks));
}

/***/ })

/******/ });