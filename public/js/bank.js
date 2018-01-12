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
/******/ 	return __webpack_require__(__webpack_require__.s = 238);
/******/ })
/************************************************************************/
/******/ ({

/***/ 238:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(239);


/***/ }),

/***/ 239:
/***/ (function(module, exports) {

/*
 * Instascan QR code camera 
 */
/*
const Instascan = require('instascan');
function scanQR(callback) {
	let scanner = new Instascan.Scanner({ 
		video: document.getElementById('preview'),
		mirror: false,
		continuous: true,
	});
	Instascan.Camera.getCameras().then(function (cameras) {
	  if (cameras.length > 0) {
		scanner.addListener('scan', function (content) {
			scanner.stop();
			$('#videoPreviewModal').modal('hide');
			callback(content);
		});
		scanner.start(cameras[0]).then(function(){
			$('#videoPreviewModal').modal('show');
			$('#videoPreviewModal').on('hide.bs.modal', function (e) {
				scanner.stop();
			})
		});
	  } else {
		alert('No cameras found.');
	  }
	}).catch(function (e) {
	  console.error(e);
	});
}
*/

var delayTimer;
var lastFilterValue = "";

$(function () {

	// // Do scan QR code card and search for the number
	// $('#scan-id-button').on('click', function(){
	// 	scanQR(function(content){
	// 		filterField.val(content).change();
	// 	});
	// });

	// Drachma
	$('.give-cash').on('click', function () {
		var person = $(this).attr('data-person');
		var value = $(this).attr('data-value');
		var resultElem = $(this).parent();
		storeTransaction(person, value, resultElem);
	});

	// Boutique
	$('.give-boutique-coupon').on('click', function () {
		var person = $(this).attr('data-person');
		var resultElem = $(this).parent();
		var name = resultElem.parents('.card').find('.card-header strong').text();
		if (confirm('Give coupon to ' + name + '?')) {
			resultElem.html('<i class="fa fa-spinner fa-spin">');
			$.post(giveBouqiqueCouponUrl, {
				"_token": csrfToken,
				"person_id": person
			}, function (data) {
				resultElem.html(data.countdown);
			}).fail(function (jqXHR, textStatus) {
				alert(textStatus);
			});
		}
	});

	// Diapers
	$('.give-diapers-coupon').on('click', function () {
		var person = $(this).attr('data-person');
		var resultElem = $(this).parent();
		var name = resultElem.parents('.card').find('.card-header strong').text();
		if (confirm('Give coupon to ' + name + '?')) {
			resultElem.html('<i class="fa fa-spinner fa-spin">');
			$.post(giveDiapersCouponUrl, {
				"_token": csrfToken,
				"person_id": person
			}, function (data) {
				resultElem.html(data.countdown);
			}).fail(function (jqXHR, textStatus) {
				alert(textStatus);
			});
		}
	});
});

function storeTransaction(personId, value, resultElem) {
	resultElem.html('<i class="fa fa-spinner fa-spin">');
	$.post(storeTransactionUrl, {
		"_token": csrfToken,
		"person_id": personId,
		"value": value
	}, function (data) {
		resultElem.html(data.today + ' drachma ').append($('<small>').addClass('text-muted').text('on ' + data.date));
	}).fail(function (jqXHR, textStatus) {
		alert(textStatus + ': ' + jqXHR.responseJSON);
	});
}

function writeRow(person) {
	var today = $('<td>');

	// Gender icon
	var genderIcon;
	if (person.gender == 'f') {
		genderIcon = createIcon('female');
	} else if (person.gender == 'm') {
		genderIcon = createIcon('male');
	} else {
		genderIcon = $('<a>').attr('href', '#').append(createIcon('question-circle-o')).on('click', function () {
			$(this).parent().empty().addClass('text-nowrap').append(createChooseGenderIcon(person, 'male', 'm')).append('&nbsp; ').append(createChooseGenderIcon(person, 'female', 'f'));
		});
	}

	// Card
	var card;
	if (person.card_no) {
		var refresh = $('<i>').addClass('fa').addClass('fa-refresh');
		var msg = 'Really revoke the card ' + person.card_no.substr(0, 7) + ' and issue a new one?';
		card = $('<span>').text(person.card_no.substr(0, 7) + ' ').append(createCardLink(person.id, refresh, msg));
	} else {
		card = createCardLink(person.id, 'Give card');
	}
}

function createCardLink(person_id, caption, confirmMessage) {
	return $('<a>').attr('href', 'javascript:;').html(caption).on('click', function () {
		if (!confirmMessage || confirm(confirmMessage)) {
			scanQR(function (content) {
				$.post(registerCardUrl, {
					"_token": csrfToken,
					"person_id": person_id,
					"card_no": content
				}, function (data) {
					$('tr#person-' + person_id).replaceWith(writeRow(data));
					filterField.select();
				}).fail(function (jqXHR, textStatus) {
					var msg = jqXHR.responseJSON.message ? jqXHR.responseJSON.message : jqXHR.responseText;
					alert('Error: ' + msg);
				});
			});
		}
	});
}

function createIcon(icon) {
	return $('<i>').addClass('fa').addClass('fa-' + icon);
}

function createChooseGenderIcon(person, icon, value) {
	return $('<a>').attr('href', '#').append(createIcon(icon)).on('click', function () {
		$.post(updateGenderUrl, {
			"_token": csrfToken,
			"person_id": person.id,
			'gender': value
		}, function (data) {
			$('tr#person-' + person.id).replaceWith(writeRow(data));
			filterField.select();
		}).fail(function (jqXHR, textStatus) {
			alert(textStatus);
		});
	});
}

/***/ })

/******/ });