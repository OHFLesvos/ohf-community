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
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
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
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 10);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/editor.js":
/*!********************************!*\
  !*** ./resources/js/editor.js ***!
  \********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("$(document).ready(function () {\n  // Define function to open filemanager window\n  var lfm = function lfm(options, cb) {\n    var route_prefix = options && options.prefix ? options.prefix : '/laravel-filemanager';\n    window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');\n    window.SetUrl = cb;\n  }; // Define LFM summernote button\n\n\n  var LFMButton = function LFMButton(context) {\n    var ui = $.summernote.ui;\n    var button = ui.button({\n      contents: '<i class=\"note-icon-picture\"></i> ',\n      tooltip: 'Insert image with filemanager',\n      click: function click() {\n        lfm({\n          type: 'image',\n          prefix: '/laravel-filemanager'\n        }, function (lfmItems, path) {\n          if (Array.isArray(lfmItems)) {\n            lfmItems.forEach(function (lfmItem) {\n              context.invoke('insertImage', lfmItem.url);\n            });\n          } else {\n            context.invoke('insertImage', lfmItems);\n          }\n        });\n      }\n    });\n    return button.render();\n  };\n\n  $('#editor').summernote({\n    toolbar: [// [groupName, [list of button]]\n    // see https://summernote.org/deep-dive/#custom-toolbar-popover\n    ['style', ['bold', 'italic', 'underline', 'clear']], ['para', ['style', 'ul', 'ol'\n    /*'paragraph', */\n    ]], ['color', ['forecolor']], ['insert', ['link', 'lfm',\n    /*'picture', */\n    'video', 'table']], ['misc', ['undo', 'redo', 'fullscreen', 'codeview']]],\n    styleTags: ['p', 'h3', 'h4'],\n    buttons: {\n      lfm: LFMButton\n    },\n    dialogsInBody: true\n  });\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvZWRpdG9yLmpzP2E0ZDYiXSwibmFtZXMiOlsiJCIsImRvY3VtZW50IiwicmVhZHkiLCJsZm0iLCJvcHRpb25zIiwiY2IiLCJyb3V0ZV9wcmVmaXgiLCJwcmVmaXgiLCJ3aW5kb3ciLCJvcGVuIiwidHlwZSIsIlNldFVybCIsIkxGTUJ1dHRvbiIsImNvbnRleHQiLCJ1aSIsInN1bW1lcm5vdGUiLCJidXR0b24iLCJjb250ZW50cyIsInRvb2x0aXAiLCJjbGljayIsImxmbUl0ZW1zIiwicGF0aCIsIkFycmF5IiwiaXNBcnJheSIsImZvckVhY2giLCJsZm1JdGVtIiwiaW52b2tlIiwidXJsIiwicmVuZGVyIiwidG9vbGJhciIsInN0eWxlVGFncyIsImJ1dHRvbnMiLCJkaWFsb2dzSW5Cb2R5Il0sIm1hcHBpbmdzIjoiQUFBQUEsQ0FBQyxDQUFDQyxRQUFELENBQUQsQ0FBWUMsS0FBWixDQUFrQixZQUFXO0FBRXpCO0FBQ0EsTUFBSUMsR0FBRyxHQUFHLFNBQU5BLEdBQU0sQ0FBU0MsT0FBVCxFQUFrQkMsRUFBbEIsRUFBc0I7QUFDNUIsUUFBSUMsWUFBWSxHQUFJRixPQUFPLElBQUlBLE9BQU8sQ0FBQ0csTUFBcEIsR0FBOEJILE9BQU8sQ0FBQ0csTUFBdEMsR0FBK0Msc0JBQWxFO0FBQ0FDLFVBQU0sQ0FBQ0MsSUFBUCxDQUFZSCxZQUFZLEdBQUcsUUFBZixHQUEwQkYsT0FBTyxDQUFDTSxJQUFsQyxJQUEwQyxNQUF0RCxFQUE4RCxhQUE5RCxFQUE2RSxzQkFBN0U7QUFDQUYsVUFBTSxDQUFDRyxNQUFQLEdBQWdCTixFQUFoQjtBQUNILEdBSkQsQ0FIeUIsQ0FTekI7OztBQUNBLE1BQUlPLFNBQVMsR0FBRyxTQUFaQSxTQUFZLENBQVNDLE9BQVQsRUFBa0I7QUFDOUIsUUFBSUMsRUFBRSxHQUFHZCxDQUFDLENBQUNlLFVBQUYsQ0FBYUQsRUFBdEI7QUFDQSxRQUFJRSxNQUFNLEdBQUdGLEVBQUUsQ0FBQ0UsTUFBSCxDQUFVO0FBQ25CQyxjQUFRLEVBQUUsb0NBRFM7QUFFbkJDLGFBQU8sRUFBRSwrQkFGVTtBQUduQkMsV0FBSyxFQUFFLGlCQUFXO0FBQ2RoQixXQUFHLENBQUM7QUFBQ08sY0FBSSxFQUFFLE9BQVA7QUFBZ0JILGdCQUFNLEVBQUU7QUFBeEIsU0FBRCxFQUFrRCxVQUFTYSxRQUFULEVBQW1CQyxJQUFuQixFQUF5QjtBQUMxRSxjQUFJQyxLQUFLLENBQUNDLE9BQU4sQ0FBY0gsUUFBZCxDQUFKLEVBQTZCO0FBQ3pCQSxvQkFBUSxDQUFDSSxPQUFULENBQWlCLFVBQVVDLE9BQVYsRUFBbUI7QUFDaENaLHFCQUFPLENBQUNhLE1BQVIsQ0FBZSxhQUFmLEVBQThCRCxPQUFPLENBQUNFLEdBQXRDO0FBQ0gsYUFGRDtBQUdILFdBSkQsTUFJTztBQUNIZCxtQkFBTyxDQUFDYSxNQUFSLENBQWUsYUFBZixFQUE4Qk4sUUFBOUI7QUFDSDtBQUNKLFNBUkUsQ0FBSDtBQVNIO0FBYmtCLEtBQVYsQ0FBYjtBQWVBLFdBQU9KLE1BQU0sQ0FBQ1ksTUFBUCxFQUFQO0FBQ0gsR0FsQkQ7O0FBb0JBNUIsR0FBQyxDQUFDLFNBQUQsQ0FBRCxDQUFhZSxVQUFiLENBQXdCO0FBQ3BCYyxXQUFPLEVBQUUsQ0FDTDtBQUNBO0FBQ0EsS0FBQyxPQUFELEVBQVUsQ0FBQyxNQUFELEVBQVMsUUFBVCxFQUFtQixXQUFuQixFQUFnQyxPQUFoQyxDQUFWLENBSEssRUFJTCxDQUFDLE1BQUQsRUFBUyxDQUFDLE9BQUQsRUFBVSxJQUFWLEVBQWdCO0FBQU07QUFBdEIsS0FBVCxDQUpLLEVBS0wsQ0FBQyxPQUFELEVBQVUsQ0FBQyxXQUFELENBQVYsQ0FMSyxFQU1MLENBQUMsUUFBRCxFQUFXLENBQUMsTUFBRCxFQUFTLEtBQVQ7QUFBZ0I7QUFBZ0IsV0FBaEMsRUFBeUMsT0FBekMsQ0FBWCxDQU5LLEVBT0wsQ0FBQyxNQUFELEVBQVMsQ0FBQyxNQUFELEVBQVMsTUFBVCxFQUFpQixZQUFqQixFQUErQixVQUEvQixDQUFULENBUEssQ0FEVztBQVVwQkMsYUFBUyxFQUFFLENBQUMsR0FBRCxFQUFNLElBQU4sRUFBWSxJQUFaLENBVlM7QUFXcEJDLFdBQU8sRUFBRTtBQUNMNUIsU0FBRyxFQUFFUztBQURBLEtBWFc7QUFjcEJvQixpQkFBYSxFQUFFO0FBZEssR0FBeEI7QUFnQkgsQ0E5Q0QiLCJmaWxlIjoiLi9yZXNvdXJjZXMvanMvZWRpdG9yLmpzLmpzIiwic291cmNlc0NvbnRlbnQiOlsiJChkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24oKSB7XHJcblxyXG4gICAgLy8gRGVmaW5lIGZ1bmN0aW9uIHRvIG9wZW4gZmlsZW1hbmFnZXIgd2luZG93XHJcbiAgICB2YXIgbGZtID0gZnVuY3Rpb24ob3B0aW9ucywgY2IpIHtcclxuICAgICAgICB2YXIgcm91dGVfcHJlZml4ID0gKG9wdGlvbnMgJiYgb3B0aW9ucy5wcmVmaXgpID8gb3B0aW9ucy5wcmVmaXggOiAnL2xhcmF2ZWwtZmlsZW1hbmFnZXInO1xyXG4gICAgICAgIHdpbmRvdy5vcGVuKHJvdXRlX3ByZWZpeCArICc/dHlwZT0nICsgb3B0aW9ucy50eXBlIHx8ICdmaWxlJywgJ0ZpbGVNYW5hZ2VyJywgJ3dpZHRoPTkwMCxoZWlnaHQ9NjAwJyk7XHJcbiAgICAgICAgd2luZG93LlNldFVybCA9IGNiO1xyXG4gICAgfTtcclxuXHJcbiAgICAvLyBEZWZpbmUgTEZNIHN1bW1lcm5vdGUgYnV0dG9uXHJcbiAgICB2YXIgTEZNQnV0dG9uID0gZnVuY3Rpb24oY29udGV4dCkge1xyXG4gICAgICAgIHZhciB1aSA9ICQuc3VtbWVybm90ZS51aTtcclxuICAgICAgICB2YXIgYnV0dG9uID0gdWkuYnV0dG9uKHtcclxuICAgICAgICAgICAgY29udGVudHM6ICc8aSBjbGFzcz1cIm5vdGUtaWNvbi1waWN0dXJlXCI+PC9pPiAnLFxyXG4gICAgICAgICAgICB0b29sdGlwOiAnSW5zZXJ0IGltYWdlIHdpdGggZmlsZW1hbmFnZXInLFxyXG4gICAgICAgICAgICBjbGljazogZnVuY3Rpb24oKSB7XHJcbiAgICAgICAgICAgICAgICBsZm0oe3R5cGU6ICdpbWFnZScsIHByZWZpeDogJy9sYXJhdmVsLWZpbGVtYW5hZ2VyJ30sIGZ1bmN0aW9uKGxmbUl0ZW1zLCBwYXRoKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKEFycmF5LmlzQXJyYXkobGZtSXRlbXMpKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGxmbUl0ZW1zLmZvckVhY2goZnVuY3Rpb24gKGxmbUl0ZW0pIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRleHQuaW52b2tlKCdpbnNlcnRJbWFnZScsIGxmbUl0ZW0udXJsKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgY29udGV4dC5pbnZva2UoJ2luc2VydEltYWdlJywgbGZtSXRlbXMpO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfSk7XHJcbiAgICAgICAgcmV0dXJuIGJ1dHRvbi5yZW5kZXIoKTtcclxuICAgIH07XHJcblxyXG4gICAgJCgnI2VkaXRvcicpLnN1bW1lcm5vdGUoe1xyXG4gICAgICAgIHRvb2xiYXI6IFtcclxuICAgICAgICAgICAgLy8gW2dyb3VwTmFtZSwgW2xpc3Qgb2YgYnV0dG9uXV1cclxuICAgICAgICAgICAgLy8gc2VlIGh0dHBzOi8vc3VtbWVybm90ZS5vcmcvZGVlcC1kaXZlLyNjdXN0b20tdG9vbGJhci1wb3BvdmVyXHJcbiAgICAgICAgICAgIFsnc3R5bGUnLCBbJ2JvbGQnLCAnaXRhbGljJywgJ3VuZGVybGluZScsICdjbGVhciddXSxcclxuICAgICAgICAgICAgWydwYXJhJywgWydzdHlsZScsICd1bCcsICdvbCcsIC8qJ3BhcmFncmFwaCcsICovXV0sXHJcbiAgICAgICAgICAgIFsnY29sb3InLCBbJ2ZvcmVjb2xvciddXSxcclxuICAgICAgICAgICAgWydpbnNlcnQnLCBbJ2xpbmsnLCAnbGZtJywgLyoncGljdHVyZScsICovICd2aWRlbycsICd0YWJsZSddXSxcclxuICAgICAgICAgICAgWydtaXNjJywgWyd1bmRvJywgJ3JlZG8nLCAnZnVsbHNjcmVlbicsICdjb2RldmlldyddXSxcclxuICAgICAgICBdLFxyXG4gICAgICAgIHN0eWxlVGFnczogWydwJywgJ2gzJywgJ2g0J10sXHJcbiAgICAgICAgYnV0dG9uczoge1xyXG4gICAgICAgICAgICBsZm06IExGTUJ1dHRvblxyXG4gICAgICAgIH0sXHJcbiAgICAgICAgZGlhbG9nc0luQm9keTogdHJ1ZVxyXG4gICAgfSk7XHJcbn0pO1xyXG4iXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./resources/js/editor.js\n");

/***/ }),

/***/ 10:
/*!**************************************!*\
  !*** multi ./resources/js/editor.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\nicolas\Development\Web\ohf-community\resources\js\editor.js */"./resources/js/editor.js");


/***/ })

/******/ });