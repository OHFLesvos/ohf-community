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
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 250);
/******/ })
/************************************************************************/
/******/ ({

/***/ 250:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(251);


/***/ }),

/***/ 251:
/***/ (function(module, exports, __webpack_require__) {

var ISBN = __webpack_require__(252);

function toggleSubmit() {
    if ($('#book_id').val()) {
        $('#lend-existing-book-button').attr('disabled', false);
    } else {
        $('#lend-existing-book-button').attr('disabled', true);
    }
}

$(function () {
    $('#lendBookModal').on('shown.bs.modal', function (e) {
        $('input[name="book_id"]').val('');
        $('input[name="book_id_search"]').val('').focus();
    });

    $('#registerBookModal').on('shown.bs.modal', function (e) {
        $('input[name="isbn"]').focus();
        var book_search = $('input[name="book_id_search"]').val().toUpperCase().replace(/[^+0-9X]/gi, '');
        if (ISBN.Validate(book_search)) {
            $('input[name="isbn"]').val(book_search).trigger('propertychange');
        }
    });

    $('input[name="isbn"]').on('input propertychange', function () {
        $(this).removeClass('is-valid').removeClass('is-invalid');
        var isbn = $(this).val().toUpperCase().replace(/[^+0-9X]/gi, '');
        if (/^(97(8|9))?\d{9}(\d|X)$/.test(isbn) && ISBN.Validate(isbn)) {
            $(this).addClass('is-valid');
            $('input[name="title"]').val('');
            $('input[name="author"]').val('');
            $('input[name="language"]').val('');
            $('input[name="title"]').attr('placeholder', 'Searching for title...');
            $('input[name="author"]').attr('placeholder', 'Searching for author...');
            $('input[name="language"]').attr('placeholder', 'Searching for language...');
            $.get("/library/books/findIsbn/" + isbn, function (data) {
                $('input[name="title"]').val(data.title);
                $('input[name="author"]').val(data.author);
                $('input[name="language"]').val(data.language);
            }).fail(function () {
                $('input[name="title"]').attr('placeholder', 'Title');
                $('input[name="author"]').attr('placeholder', 'Author');
                $('input[name="language"]').attr('placeholder', 'Language');
            });
        } else if ($(this).val().length > 0) {
            $(this).addClass('is-invalid');
        }
    });
});

/***/ }),

/***/ 252:
/***/ (function(module, exports, __webpack_require__) {

/* 
 * @author: Tomasz Sochacki
 * ISBN-13 and ISBN-10 validator.
 */

const regexp = __webpack_require__( 253 );
const checksum = __webpack_require__( 254 );

class ISBN {
    static Validate( isbn ) {
        //Method always retruns boolean value!
        
        //Remove optional prefix:
        isbn = isbn.replace( regexp.PREFIX, '' );
        
        if( !regexp.ISBN.test( isbn ) ) {
            return false;
        }
        
        return checksum( isbn ); //true or false
    }
}

module.exports = ISBN;

/***/ }),

/***/ 253:
/***/ (function(module, exports) {

/* 
 * @author: Tomasz Sochacki
 * Regular Expression for validate ISBN-10 and ISB-13
 */

/*
 * Regexp for remove prefix in ISBN number.
 * Example prefixes which will be removed:
 * ISBN number
 * ISBN: number
 * ISBN-10 number
 * ISBN-13 number
 * ISBN-10: number
 * ISBN-13: number
 * 
 * Regexp description:
 * /^ISBN       on start 'ISBN' or 'isbn'
 * (?:-1[03])?  optional prefix -10 or -13
 * :?           optional colon ":"
 * \x20+        minimum one space
 * /i           case insensitive
 */
const PREFIX = /^ISBN(?:-1[03])?:?\x20+/i;

/*
 * Regexp for validate ISBN (only nubers or char "X").
 * Example for ISBN-10: "048665088X", "0306406152".
 * Example for ISBN-13: "9788371815102".
 * 
 * Regexp description:
 * /^          start of string
 * (?:
 *    \d{9}    9 digits
 *    [\dXx]   and of end one digit or char "X"/"x"
 *    |\d{13}  or 13 digits (ISBN-13)
 * )$/         and of string
 */
const ISBN = /^(?:\d{9}[\dXx]|\d{13})$/;

module.exports = {
    PREFIX,
    ISBN
};

/***/ }),

/***/ 254:
/***/ (function(module, exports) {

/* 
 * @author: Tomasz Sochacki
 * Checksum for validate ISBN-10 and ISBN-13.
 */

const checksum = ( isbn ) => {
    //isbn have to be number or string (composed only of digits or char "X"):
    isbn = isbn.toString();

    //Remove last digit (control digit):
    let number = isbn.slice( 0,-1 );
	
    //Convert number to array (with only digits):
    number = number.split( '' ).map( Number );
    
    //Save last digit (control digit):
    const last = isbn.slice( -1 );
    const lastDigit = ( last !== 'X' ) ? parseInt( last, 10 ) : 'X';

    //Algorithm for checksum calculation (digit * position):
    number = number.map( ( digit, index ) => {
        return digit * ( index + 1 );
    } );
    
    //Calculate checksum from array:
    const sum = number.reduce( ( a, b ) => a + b, 0 );

    //Validate control digit:
    const controlDigit = sum % 11;
    return lastDigit === ( controlDigit !== 10 ? controlDigit : 'X' ); 
};

module.exports = checksum;


/***/ })

/******/ });