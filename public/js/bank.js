/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;
/******/
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
/******/ 	// identity function for calling harmony imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };
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
/******/ 	return __webpack_require__(__webpack_require__.s = 239);
/******/ })
/************************************************************************/
/******/ ({

/***/ 12:
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

/***/ 142:
/***/ (function(module, exports, __webpack_require__) {

pagination = __webpack_require__(12);

var delayTimer;
var lastFilterValue = "";

$(function () {

	filterField.on('change paste propertychange input', function (evt) {
		//console.log( 'EVENT '  + evt.type );

		if (evt.type == 'input' || evt.type == 'propertychange') {
			clearTimeout(delayTimer);
			delayTimer = setTimeout(function () {
				//console.log('DELAY');
				applyFilter(filterField.val());
			}, 250);
		} else {
			applyFilter(filterField.val());
		}
	});

	filterField.on('keydown', function (evt) {
		var isEscape = false;
		var isEnter = false;
		if ("key" in evt) {
			isEscape = evt.key == "Escape" || evt.key == "Esc";
			isEnter = evt.key == "Enter";
		} else {
			isEscape = evt.keyCode == 27;
			isEnter = evt.keyCode == 13;
		}
		if (isEscape) {
			//console.log( 'ESCAPE '  + evt.type );
			resetFilter();
		} else if (isEnter) {
			applyFilter(filterField.val());
			filterField.blur();
		}
	});

	filterReset.on('click', function () {
		resetFilter();
	});

	addFilterElem.on('click', function () {
		var filterVal = $(this).attr('data-filter');
		var prevVal = filterField.val();
		if (!prevVal.trim().split(" ").includes(filterVal.trim())) {
			filterField.focus().val(filterVal + prevVal).change();
		} else {
			filterField.focus().val(prevVal);
		}
	});

	filterField.select().change();

	showStats();
});

function showStats() {
	$.get('bank/todayStats', function (data) {
		if (data.numberOfPersonsServed) {
			$('#stats').html('Today, we served <strong>' + data.numberOfPersonsServed + '</strong> persons, handing out <strong> ' + data.transactionValue + '</strong> drachmas.');
		} else {
			$('#stats').html('We did not yet serve any persons today.');
		}
		$('#stats').fadeIn('fast');
	});
}

function resetFilter() {
	// console.log( 'RESET filter' );
	filterField.val('').change().focus();
	resetAlert();
	resetStatus();
	$.post(resetFilterUrl, {
		"_token": csrfToken
	});
}

function applyFilter(value) {
	if (lastFilterValue == value) {
		return;
	}
	$('#stats').hide();
	//console.log('APPLY "' + value + '"');

	if (value != '') {
		filterReset.addClass('bg-primary text-light');
	} else {
		filterReset.removeClass('bg-primary text-light');
	}

	var searchValue = value.trim();
	if (searchValue != '') {
		filterTable(searchValue, 1);
	} else {
		table.hide();
		showStats();
		resetAlert();
		resetStatus();
	}
	lastFilterValue = value;
}

function showStatus(msg) {
	statusContainer.html(msg);
}

function resetStatus() {
	statusContainer.html('');
	paginator.empty();
	paginationInfo.empty();
}

function showAlert(msg, type) {
	alertContainer.html(msg);
	alertContainer.addClass('alert alert-' + type);
	var icon = type == 'danger' ? 'warning' : 'info-circle';
	alertContainer.prepend($('<i>').addClass('fa fa-' + icon).append('&nbsp;'));
	alertContainer.show();
}

function resetAlert() {
	alertContainer.hide();
	alertContainer.removeClass();
}

function loadPage(page) {
	filterTable(filterField.val().trim(), page);
}

function filterTable(filter, page) {
	paginator.empty();
	paginationInfo.empty();
	showStatus('Searching...'); //  for \'' + filter + '\'
	$.post(filterUrl, {
		"_token": csrfToken,
		"filter": filter,
		"page": page
	}, function (data) {
		var tbody = table.children('tbody');
		if (data.results.length > 0) {
			tbody.empty();
			$.each(data.results, function (k, v) {
				tbody.append(writeRow(v));
			});
			table.show();
			resetAlert();
			resetStatus();
			//showStatus(data.results.length < data.total ? 'Showing <strong>' + data.results.length + '</strong> of <strong>' + data.total + '</strong> persons, refine your search.' : 'Found <strong>' + data.results.length + '</strong> persons.');
			pagination.updatePagination(paginator, data, loadPage);
			paginationInfo.html(data.from + ' - ' + data.to + ' of ' + data.total);
		} else {
			table.hide();
			showStats();
			resetStatus();
			var msg = $('<span>').text('No results. ').append($('<a>').attr('href', createNewRecordUrl + (data.register ? '?' + data.register : '')).append('Register new person'));
			showAlert(msg, 'info');
		}
	}).fail(function (jqXHR, textStatus, error) {
		table.hide();
		showStats();
		resetStatus();
		showAlert(textStatus + ": " + error, 'danger');
		console.log("Error: " + textStatus + " " + jqXHR.responseText);
	});
}

function writeRow(person) {
	var today = $('<td>');
	if (person.today > 0) {
		today.text(person.today).append(' &nbsp; ').append($('<a>').attr('href', 'javascript:;').append($('<i>').addClass('fa fa-pencil')).on('click', function () {
			var transactionInput = $('<input>').attr('type', 'number').attr('min', 0).attr('max', transactionMaxAmount).attr('value', person.today).addClass('form-control form-control-sm').on('focus', function () {
				$(this).select();
			}).on('keypress', function (e) {
				if (e.keyCode == 13) {
					// Enter
					storeTransaction(person.id, $(this).val() - person.today);
				}
			});
			today.empty();
			today.append(transactionInput);
			transactionInput.focus();
		}));
	} else {
		today.append($('<a>').attr('href', '#').addClass('btn btn-secondary btn-sm').text('2').on('click', function (e) {
			$(this).parent().html('<i class="fa fa-spinner fa-spin">');
			storeTransaction(person.id, 2);
			e.preventDefault();
		}));
		today.append(' &nbsp; ');
		today.append($('<a>').attr('href', '#').addClass('btn btn-secondary btn-sm').text('1').on('click', function (e) {
			$(this).parent().html('<i class="fa fa-spinner fa-spin">');
			storeTransaction(person.id, 1);
			e.preventDefault();
		}));
	}
	return $('<tr>').attr('id', 'person-' + person.id).addClass(person.today > 0 ? 'table-success' : null).append($('<td>').append($('<a>').attr('href', 'people/' + person.id).text(person.family_name))).append($('<td>').append($('<a>').attr('href', 'people/' + person.id).text(person.name))).append($('<td>').text(person.case_no)).append($('<td>').text(person.medical_no)).append($('<td>').text(person.registration_no)).append($('<td>').text(person.section_card_no)).append($('<td>').text(person.temp_no)).append($('<td>').text(person.nationality)).append($('<td>').text(person.remarks))
	// Boutique coupon
	.append($('<td>').html(function () {
		if (person.boutique_coupon) {
			return person.boutique_coupon;
		}
		return $('<a>').attr('href', 'javascript:;').text('Give coupon').on('click', function () {
			if (confirm('Give coupon to ' + person.family_name + ' ' + person.name + '?')) {
				$.post(giveBouqiqueCouponUrl, {
					"_token": csrfToken,
					"person_id": person.id
				}, function (data) {
					updatePerson(person.id);
					filterField.select();
				}).fail(function (jqXHR, textStatus) {
					alert(extStatus);
				});
			}
		});
	}))
	// Diapers coupon
	.append($('<td>').html(function () {
		if (person.diapers_coupon) {
			return person.diapers_coupon;
		}
		return $('<a>').attr('href', 'javascript:;').text('Give coupon').on('click', function () {
			if (confirm('Give coupon to ' + person.family_name + ' ' + person.name + '?')) {
				$.post(giveDiapersCouponUrl, {
					"_token": csrfToken,
					"person_id": person.id
				}, function (data) {
					updatePerson(person.id);
					filterField.select();
				}).fail(function (jqXHR, textStatus) {
					alert(extStatus);
				});
			}
		});
	}))
	// .append(
	// 	$('<td>')
	// 		.html(person.yesterday > 0 ? '<strong>' + person.yesterday + '</strong>' : 0)
	// )
	.append(today);
}

function storeTransaction(personId, value) {
	$.post(storeTransactionUrl, {
		"_token": csrfToken,
		"person_id": personId,
		"value": value
	}, function (data) {
		//alert(1);
		$('tr#person-' + personId).replaceWith(writeRow(data));
		filterField.select();
	}).fail(function (jqXHR, textStatus) {
		alert(textStatus + ': ' + jqXHR.responseJSON);
	});
}

function updatePerson(personId) {
	$.get('bank/person/' + personId, function (data) {
		$('tr#person-' + personId).replaceWith(writeRow(data));
	}).fail(function (jqXHR, textStatus) {
		alert(textStatus);
	});
}

/***/ }),

/***/ 239:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(142);


/***/ })

/******/ });