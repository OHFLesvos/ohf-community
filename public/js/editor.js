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

eval("$(document).ready(function () {\n  // Define function to open filemanager window\n  var lfm = function lfm(options, cb) {\n    var route_prefix = options && options.prefix ? options.prefix : '/laravel-filemanager';\n    window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');\n    window.SetUrl = cb;\n  }; // Define LFM summernote button\n\n\n  var LFMButton = function LFMButton(context) {\n    var ui = $.summernote.ui;\n    var button = ui.button({\n      contents: '<i class=\"note-icon-picture\"></i> ',\n      tooltip: 'Insert image with filemanager',\n      click: function click() {\n        lfm({\n          type: 'image',\n          prefix: '/laravel-filemanager'\n        }, function (lfmItems, path) {\n          if (Array.isArray(lfmItems)) {\n            lfmItems.forEach(function (lfmItem) {\n              context.invoke('insertImage', lfmItem.url);\n            });\n          } else {\n            context.invoke('insertImage', lfmItems);\n          }\n        });\n      }\n    });\n    return button.render();\n  };\n\n  $('#editor').summernote({\n    toolbar: [// [groupName, [list of button]]\n    // see https://summernote.org/deep-dive/#custom-toolbar-popover\n    ['style', ['bold', 'italic', 'underline', 'clear']], ['para', ['style', 'ul', 'ol'\n    /*'paragraph', */\n    ]], ['color', ['forecolor']], ['insert', ['link', 'lfm',\n    /*'picture', */\n    'video', 'table']], ['misc', ['undo', 'redo', 'fullscreen', 'codeview']]],\n    styleTags: ['p', 'h3', 'h4'],\n    buttons: {\n      lfm: LFMButton\n    },\n    dialogsInBody: true\n  });\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvZWRpdG9yLmpzP2E0ZDYiXSwibmFtZXMiOlsiJCIsImRvY3VtZW50IiwicmVhZHkiLCJsZm0iLCJvcHRpb25zIiwiY2IiLCJyb3V0ZV9wcmVmaXgiLCJwcmVmaXgiLCJ3aW5kb3ciLCJvcGVuIiwidHlwZSIsIlNldFVybCIsIkxGTUJ1dHRvbiIsImNvbnRleHQiLCJ1aSIsInN1bW1lcm5vdGUiLCJidXR0b24iLCJjb250ZW50cyIsInRvb2x0aXAiLCJjbGljayIsImxmbUl0ZW1zIiwicGF0aCIsIkFycmF5IiwiaXNBcnJheSIsImZvckVhY2giLCJsZm1JdGVtIiwiaW52b2tlIiwidXJsIiwicmVuZGVyIiwidG9vbGJhciIsInN0eWxlVGFncyIsImJ1dHRvbnMiLCJkaWFsb2dzSW5Cb2R5Il0sIm1hcHBpbmdzIjoiQUFBQUEsQ0FBQyxDQUFDQyxRQUFELENBQUQsQ0FBWUMsS0FBWixDQUFrQixZQUFXO0FBRXpCO0FBQ0EsTUFBSUMsR0FBRyxHQUFHLFNBQU5BLEdBQU0sQ0FBU0MsT0FBVCxFQUFrQkMsRUFBbEIsRUFBc0I7QUFDNUIsUUFBSUMsWUFBWSxHQUFJRixPQUFPLElBQUlBLE9BQU8sQ0FBQ0csTUFBcEIsR0FBOEJILE9BQU8sQ0FBQ0csTUFBdEMsR0FBK0Msc0JBQWxFO0FBQ0FDLFVBQU0sQ0FBQ0MsSUFBUCxDQUFZSCxZQUFZLEdBQUcsUUFBZixHQUEwQkYsT0FBTyxDQUFDTSxJQUFsQyxJQUEwQyxNQUF0RCxFQUE4RCxhQUE5RCxFQUE2RSxzQkFBN0U7QUFDQUYsVUFBTSxDQUFDRyxNQUFQLEdBQWdCTixFQUFoQjtBQUNILEdBSkQsQ0FIeUIsQ0FTekI7OztBQUNBLE1BQUlPLFNBQVMsR0FBRyxTQUFaQSxTQUFZLENBQVNDLE9BQVQsRUFBa0I7QUFDOUIsUUFBSUMsRUFBRSxHQUFHZCxDQUFDLENBQUNlLFVBQUYsQ0FBYUQsRUFBdEI7QUFDQSxRQUFJRSxNQUFNLEdBQUdGLEVBQUUsQ0FBQ0UsTUFBSCxDQUFVO0FBQ25CQyxjQUFRLEVBQUUsb0NBRFM7QUFFbkJDLGFBQU8sRUFBRSwrQkFGVTtBQUduQkMsV0FBSyxFQUFFLGlCQUFXO0FBQ2RoQixXQUFHLENBQUM7QUFBQ08sY0FBSSxFQUFFLE9BQVA7QUFBZ0JILGdCQUFNLEVBQUU7QUFBeEIsU0FBRCxFQUFrRCxVQUFTYSxRQUFULEVBQW1CQyxJQUFuQixFQUF5QjtBQUMxRSxjQUFJQyxLQUFLLENBQUNDLE9BQU4sQ0FBY0gsUUFBZCxDQUFKLEVBQTZCO0FBQ3pCQSxvQkFBUSxDQUFDSSxPQUFULENBQWlCLFVBQVVDLE9BQVYsRUFBbUI7QUFDaENaLHFCQUFPLENBQUNhLE1BQVIsQ0FBZSxhQUFmLEVBQThCRCxPQUFPLENBQUNFLEdBQXRDO0FBQ0gsYUFGRDtBQUdILFdBSkQsTUFJTztBQUNIZCxtQkFBTyxDQUFDYSxNQUFSLENBQWUsYUFBZixFQUE4Qk4sUUFBOUI7QUFDSDtBQUNKLFNBUkUsQ0FBSDtBQVNIO0FBYmtCLEtBQVYsQ0FBYjtBQWVBLFdBQU9KLE1BQU0sQ0FBQ1ksTUFBUCxFQUFQO0FBQ0gsR0FsQkQ7O0FBb0JBNUIsR0FBQyxDQUFDLFNBQUQsQ0FBRCxDQUFhZSxVQUFiLENBQXdCO0FBQ3BCYyxXQUFPLEVBQUUsQ0FDTDtBQUNBO0FBQ0EsS0FBQyxPQUFELEVBQVUsQ0FBQyxNQUFELEVBQVMsUUFBVCxFQUFtQixXQUFuQixFQUFnQyxPQUFoQyxDQUFWLENBSEssRUFJTCxDQUFDLE1BQUQsRUFBUyxDQUFDLE9BQUQsRUFBVSxJQUFWLEVBQWdCO0FBQU07QUFBdEIsS0FBVCxDQUpLLEVBS0wsQ0FBQyxPQUFELEVBQVUsQ0FBQyxXQUFELENBQVYsQ0FMSyxFQU1MLENBQUMsUUFBRCxFQUFXLENBQUMsTUFBRCxFQUFTLEtBQVQ7QUFBZ0I7QUFBZ0IsV0FBaEMsRUFBeUMsT0FBekMsQ0FBWCxDQU5LLEVBT0wsQ0FBQyxNQUFELEVBQVMsQ0FBQyxNQUFELEVBQVMsTUFBVCxFQUFpQixZQUFqQixFQUErQixVQUEvQixDQUFULENBUEssQ0FEVztBQVVwQkMsYUFBUyxFQUFFLENBQUMsR0FBRCxFQUFNLElBQU4sRUFBWSxJQUFaLENBVlM7QUFXcEJDLFdBQU8sRUFBRTtBQUNMNUIsU0FBRyxFQUFFUztBQURBLEtBWFc7QUFjcEJvQixpQkFBYSxFQUFFO0FBZEssR0FBeEI7QUFnQkgsQ0E5Q0QiLCJmaWxlIjoiLi9yZXNvdXJjZXMvanMvZWRpdG9yLmpzLmpzIiwic291cmNlc0NvbnRlbnQiOlsiJChkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24oKSB7XG5cbiAgICAvLyBEZWZpbmUgZnVuY3Rpb24gdG8gb3BlbiBmaWxlbWFuYWdlciB3aW5kb3dcbiAgICB2YXIgbGZtID0gZnVuY3Rpb24ob3B0aW9ucywgY2IpIHtcbiAgICAgICAgdmFyIHJvdXRlX3ByZWZpeCA9IChvcHRpb25zICYmIG9wdGlvbnMucHJlZml4KSA/IG9wdGlvbnMucHJlZml4IDogJy9sYXJhdmVsLWZpbGVtYW5hZ2VyJztcbiAgICAgICAgd2luZG93Lm9wZW4ocm91dGVfcHJlZml4ICsgJz90eXBlPScgKyBvcHRpb25zLnR5cGUgfHwgJ2ZpbGUnLCAnRmlsZU1hbmFnZXInLCAnd2lkdGg9OTAwLGhlaWdodD02MDAnKTtcbiAgICAgICAgd2luZG93LlNldFVybCA9IGNiO1xuICAgIH07XG5cbiAgICAvLyBEZWZpbmUgTEZNIHN1bW1lcm5vdGUgYnV0dG9uXG4gICAgdmFyIExGTUJ1dHRvbiA9IGZ1bmN0aW9uKGNvbnRleHQpIHtcbiAgICAgICAgdmFyIHVpID0gJC5zdW1tZXJub3RlLnVpO1xuICAgICAgICB2YXIgYnV0dG9uID0gdWkuYnV0dG9uKHtcbiAgICAgICAgICAgIGNvbnRlbnRzOiAnPGkgY2xhc3M9XCJub3RlLWljb24tcGljdHVyZVwiPjwvaT4gJyxcbiAgICAgICAgICAgIHRvb2x0aXA6ICdJbnNlcnQgaW1hZ2Ugd2l0aCBmaWxlbWFuYWdlcicsXG4gICAgICAgICAgICBjbGljazogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgbGZtKHt0eXBlOiAnaW1hZ2UnLCBwcmVmaXg6ICcvbGFyYXZlbC1maWxlbWFuYWdlcid9LCBmdW5jdGlvbihsZm1JdGVtcywgcGF0aCkge1xuICAgICAgICAgICAgICAgICAgICBpZiAoQXJyYXkuaXNBcnJheShsZm1JdGVtcykpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGxmbUl0ZW1zLmZvckVhY2goZnVuY3Rpb24gKGxmbUl0ZW0pIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb250ZXh0Lmludm9rZSgnaW5zZXJ0SW1hZ2UnLCBsZm1JdGVtLnVybCk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRleHQuaW52b2tlKCdpbnNlcnRJbWFnZScsIGxmbUl0ZW1zKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICAgICAgcmV0dXJuIGJ1dHRvbi5yZW5kZXIoKTtcbiAgICB9O1xuXG4gICAgJCgnI2VkaXRvcicpLnN1bW1lcm5vdGUoe1xuICAgICAgICB0b29sYmFyOiBbXG4gICAgICAgICAgICAvLyBbZ3JvdXBOYW1lLCBbbGlzdCBvZiBidXR0b25dXVxuICAgICAgICAgICAgLy8gc2VlIGh0dHBzOi8vc3VtbWVybm90ZS5vcmcvZGVlcC1kaXZlLyNjdXN0b20tdG9vbGJhci1wb3BvdmVyXG4gICAgICAgICAgICBbJ3N0eWxlJywgWydib2xkJywgJ2l0YWxpYycsICd1bmRlcmxpbmUnLCAnY2xlYXInXV0sXG4gICAgICAgICAgICBbJ3BhcmEnLCBbJ3N0eWxlJywgJ3VsJywgJ29sJywgLyoncGFyYWdyYXBoJywgKi9dXSxcbiAgICAgICAgICAgIFsnY29sb3InLCBbJ2ZvcmVjb2xvciddXSxcbiAgICAgICAgICAgIFsnaW5zZXJ0JywgWydsaW5rJywgJ2xmbScsIC8qJ3BpY3R1cmUnLCAqLyAndmlkZW8nLCAndGFibGUnXV0sXG4gICAgICAgICAgICBbJ21pc2MnLCBbJ3VuZG8nLCAncmVkbycsICdmdWxsc2NyZWVuJywgJ2NvZGV2aWV3J11dLFxuICAgICAgICBdLFxuICAgICAgICBzdHlsZVRhZ3M6IFsncCcsICdoMycsICdoNCddLFxuICAgICAgICBidXR0b25zOiB7XG4gICAgICAgICAgICBsZm06IExGTUJ1dHRvblxuICAgICAgICB9LFxuICAgICAgICBkaWFsb2dzSW5Cb2R5OiB0cnVlXG4gICAgfSk7XG59KTtcbiJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./resources/js/editor.js\n");

/***/ }),

/***/ 10:
/*!**************************************!*\
  !*** multi ./resources/js/editor.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/nicolas/devel/ohf-community/resources/js/editor.js */"./resources/js/editor.js");


/***/ })

/******/ });