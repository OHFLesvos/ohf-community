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
/******/ 	return __webpack_require__(__webpack_require__.s = 5);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/editor.js":
/*!********************************!*\
  !*** ./resources/js/editor.js ***!
  \********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("$(document).ready(function () {\n  // Define function to open filemanager window\n  var lfm = function lfm(options, cb) {\n    var route_prefix = options && options.prefix ? options.prefix : '/laravel-filemanager';\n    window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');\n    window.SetUrl = cb;\n  }; // Define LFM summernote button\n\n\n  var LFMButton = function LFMButton(context) {\n    var ui = $.summernote.ui;\n    var button = ui.button({\n      contents: '<i class=\"note-icon-picture\"></i> ',\n      tooltip: 'Insert image with filemanager',\n      click: function click() {\n        lfm({\n          type: 'image',\n          prefix: '/laravel-filemanager'\n        }, function (lfmItems, path) {\n          if (Array.isArray(lfmItems)) {\n            lfmItems.forEach(function (lfmItem) {\n              context.invoke('insertImage', lfmItem.url);\n            });\n          } else {\n            context.invoke('insertImage', lfmItems);\n          }\n        });\n      }\n    });\n    return button.render();\n  };\n\n  $('#editor').summernote({\n    toolbar: [// [groupName, [list of button]]\n    // see https://summernote.org/deep-dive/#custom-toolbar-popover\n    ['style', ['bold', 'italic', 'underline', 'clear']], ['para', ['style', 'ul', 'ol']], ['color', ['forecolor']], ['insert', ['link', 'lfm',\n    /*'picture', */\n    'video', 'table']], ['misc', ['undo', 'redo', 'fullscreen', 'codeview']]],\n    styleTags: ['p', 'h3', 'h4'],\n    buttons: {\n      lfm: LFMButton\n    },\n    dialogsInBody: true\n  });\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvZWRpdG9yLmpzP2E0ZDYiXSwibmFtZXMiOlsiJCIsImRvY3VtZW50IiwicmVhZHkiLCJsZm0iLCJvcHRpb25zIiwiY2IiLCJyb3V0ZV9wcmVmaXgiLCJwcmVmaXgiLCJ3aW5kb3ciLCJvcGVuIiwidHlwZSIsIlNldFVybCIsIkxGTUJ1dHRvbiIsImNvbnRleHQiLCJ1aSIsInN1bW1lcm5vdGUiLCJidXR0b24iLCJjb250ZW50cyIsInRvb2x0aXAiLCJjbGljayIsImxmbUl0ZW1zIiwicGF0aCIsIkFycmF5IiwiaXNBcnJheSIsImZvckVhY2giLCJsZm1JdGVtIiwiaW52b2tlIiwidXJsIiwicmVuZGVyIiwidG9vbGJhciIsInN0eWxlVGFncyIsImJ1dHRvbnMiLCJkaWFsb2dzSW5Cb2R5Il0sIm1hcHBpbmdzIjoiQUFBQUEsQ0FBQyxDQUFDQyxRQUFELENBQUQsQ0FBWUMsS0FBWixDQUFrQixZQUFXO0FBRXpCO0FBQ0EsTUFBSUMsR0FBRyxHQUFHLFNBQU5BLEdBQU0sQ0FBU0MsT0FBVCxFQUFrQkMsRUFBbEIsRUFBc0I7QUFDNUIsUUFBSUMsWUFBWSxHQUFJRixPQUFPLElBQUlBLE9BQU8sQ0FBQ0csTUFBcEIsR0FBOEJILE9BQU8sQ0FBQ0csTUFBdEMsR0FBK0Msc0JBQWxFO0FBQ0FDLFVBQU0sQ0FBQ0MsSUFBUCxDQUFZSCxZQUFZLEdBQUcsUUFBZixHQUEwQkYsT0FBTyxDQUFDTSxJQUFsQyxJQUEwQyxNQUF0RCxFQUE4RCxhQUE5RCxFQUE2RSxzQkFBN0U7QUFDQUYsVUFBTSxDQUFDRyxNQUFQLEdBQWdCTixFQUFoQjtBQUNILEdBSkQsQ0FIeUIsQ0FTekI7OztBQUNBLE1BQUlPLFNBQVMsR0FBRyxTQUFaQSxTQUFZLENBQVNDLE9BQVQsRUFBa0I7QUFDOUIsUUFBSUMsRUFBRSxHQUFHZCxDQUFDLENBQUNlLFVBQUYsQ0FBYUQsRUFBdEI7QUFDQSxRQUFJRSxNQUFNLEdBQUdGLEVBQUUsQ0FBQ0UsTUFBSCxDQUFVO0FBQ25CQyxjQUFRLEVBQUUsb0NBRFM7QUFFbkJDLGFBQU8sRUFBRSwrQkFGVTtBQUduQkMsV0FBSyxFQUFFLGlCQUFXO0FBQ2RoQixXQUFHLENBQUM7QUFBQ08sY0FBSSxFQUFFLE9BQVA7QUFBZ0JILGdCQUFNLEVBQUU7QUFBeEIsU0FBRCxFQUFrRCxVQUFTYSxRQUFULEVBQW1CQyxJQUFuQixFQUF5QjtBQUMxRSxjQUFJQyxLQUFLLENBQUNDLE9BQU4sQ0FBY0gsUUFBZCxDQUFKLEVBQTZCO0FBQ3pCQSxvQkFBUSxDQUFDSSxPQUFULENBQWlCLFVBQVVDLE9BQVYsRUFBbUI7QUFDaENaLHFCQUFPLENBQUNhLE1BQVIsQ0FBZSxhQUFmLEVBQThCRCxPQUFPLENBQUNFLEdBQXRDO0FBQ0gsYUFGRDtBQUdILFdBSkQsTUFJTztBQUNIZCxtQkFBTyxDQUFDYSxNQUFSLENBQWUsYUFBZixFQUE4Qk4sUUFBOUI7QUFDSDtBQUNKLFNBUkUsQ0FBSDtBQVNIO0FBYmtCLEtBQVYsQ0FBYjtBQWVBLFdBQU9KLE1BQU0sQ0FBQ1ksTUFBUCxFQUFQO0FBQ0gsR0FsQkQ7O0FBb0JBNUIsR0FBQyxDQUFDLFNBQUQsQ0FBRCxDQUFhZSxVQUFiLENBQXdCO0FBQ3BCYyxXQUFPLEVBQUUsQ0FDTDtBQUNBO0FBQ0EsS0FBQyxPQUFELEVBQVUsQ0FBQyxNQUFELEVBQVMsUUFBVCxFQUFtQixXQUFuQixFQUFnQyxPQUFoQyxDQUFWLENBSEssRUFJTCxDQUFDLE1BQUQsRUFBUyxDQUFDLE9BQUQsRUFBVSxJQUFWLEVBQWdCLElBQWhCLENBQVQsQ0FKSyxFQUtMLENBQUMsT0FBRCxFQUFVLENBQUMsV0FBRCxDQUFWLENBTEssRUFNTCxDQUFDLFFBQUQsRUFBVyxDQUFDLE1BQUQsRUFBUyxLQUFUO0FBQWdCO0FBQWdCLFdBQWhDLEVBQXlDLE9BQXpDLENBQVgsQ0FOSyxFQU9MLENBQUMsTUFBRCxFQUFTLENBQUMsTUFBRCxFQUFTLE1BQVQsRUFBaUIsWUFBakIsRUFBK0IsVUFBL0IsQ0FBVCxDQVBLLENBRFc7QUFVcEJDLGFBQVMsRUFBRSxDQUFDLEdBQUQsRUFBTSxJQUFOLEVBQVksSUFBWixDQVZTO0FBV3BCQyxXQUFPLEVBQUU7QUFDTDVCLFNBQUcsRUFBRVM7QUFEQSxLQVhXO0FBY3BCb0IsaUJBQWEsRUFBRTtBQWRLLEdBQXhCO0FBZ0JILENBOUNEIiwiZmlsZSI6Ii4vcmVzb3VyY2VzL2pzL2VkaXRvci5qcy5qcyIsInNvdXJjZXNDb250ZW50IjpbIiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCkge1xyXG5cclxuICAgIC8vIERlZmluZSBmdW5jdGlvbiB0byBvcGVuIGZpbGVtYW5hZ2VyIHdpbmRvd1xyXG4gICAgdmFyIGxmbSA9IGZ1bmN0aW9uKG9wdGlvbnMsIGNiKSB7XHJcbiAgICAgICAgdmFyIHJvdXRlX3ByZWZpeCA9IChvcHRpb25zICYmIG9wdGlvbnMucHJlZml4KSA/IG9wdGlvbnMucHJlZml4IDogJy9sYXJhdmVsLWZpbGVtYW5hZ2VyJztcclxuICAgICAgICB3aW5kb3cub3Blbihyb3V0ZV9wcmVmaXggKyAnP3R5cGU9JyArIG9wdGlvbnMudHlwZSB8fCAnZmlsZScsICdGaWxlTWFuYWdlcicsICd3aWR0aD05MDAsaGVpZ2h0PTYwMCcpO1xyXG4gICAgICAgIHdpbmRvdy5TZXRVcmwgPSBjYjtcclxuICAgIH07XHJcblxyXG4gICAgLy8gRGVmaW5lIExGTSBzdW1tZXJub3RlIGJ1dHRvblxyXG4gICAgdmFyIExGTUJ1dHRvbiA9IGZ1bmN0aW9uKGNvbnRleHQpIHtcclxuICAgICAgICB2YXIgdWkgPSAkLnN1bW1lcm5vdGUudWk7XHJcbiAgICAgICAgdmFyIGJ1dHRvbiA9IHVpLmJ1dHRvbih7XHJcbiAgICAgICAgICAgIGNvbnRlbnRzOiAnPGkgY2xhc3M9XCJub3RlLWljb24tcGljdHVyZVwiPjwvaT4gJyxcclxuICAgICAgICAgICAgdG9vbHRpcDogJ0luc2VydCBpbWFnZSB3aXRoIGZpbGVtYW5hZ2VyJyxcclxuICAgICAgICAgICAgY2xpY2s6IGZ1bmN0aW9uKCkge1xyXG4gICAgICAgICAgICAgICAgbGZtKHt0eXBlOiAnaW1hZ2UnLCBwcmVmaXg6ICcvbGFyYXZlbC1maWxlbWFuYWdlcid9LCBmdW5jdGlvbihsZm1JdGVtcywgcGF0aCkge1xyXG4gICAgICAgICAgICAgICAgICAgIGlmIChBcnJheS5pc0FycmF5KGxmbUl0ZW1zKSkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBsZm1JdGVtcy5mb3JFYWNoKGZ1bmN0aW9uIChsZm1JdGVtKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb250ZXh0Lmludm9rZSgnaW5zZXJ0SW1hZ2UnLCBsZm1JdGVtLnVybCk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRleHQuaW52b2tlKCdpbnNlcnRJbWFnZScsIGxmbUl0ZW1zKTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0pO1xyXG4gICAgICAgIHJldHVybiBidXR0b24ucmVuZGVyKCk7XHJcbiAgICB9O1xyXG5cclxuICAgICQoJyNlZGl0b3InKS5zdW1tZXJub3RlKHtcclxuICAgICAgICB0b29sYmFyOiBbXHJcbiAgICAgICAgICAgIC8vIFtncm91cE5hbWUsIFtsaXN0IG9mIGJ1dHRvbl1dXHJcbiAgICAgICAgICAgIC8vIHNlZSBodHRwczovL3N1bW1lcm5vdGUub3JnL2RlZXAtZGl2ZS8jY3VzdG9tLXRvb2xiYXItcG9wb3ZlclxyXG4gICAgICAgICAgICBbJ3N0eWxlJywgWydib2xkJywgJ2l0YWxpYycsICd1bmRlcmxpbmUnLCAnY2xlYXInXV0sXHJcbiAgICAgICAgICAgIFsncGFyYScsIFsnc3R5bGUnLCAndWwnLCAnb2wnLCAvKidwYXJhZ3JhcGgnLCAqL11dLFxyXG4gICAgICAgICAgICBbJ2NvbG9yJywgWydmb3JlY29sb3InXV0sXHJcbiAgICAgICAgICAgIFsnaW5zZXJ0JywgWydsaW5rJywgJ2xmbScsIC8qJ3BpY3R1cmUnLCAqLyAndmlkZW8nLCAndGFibGUnXV0sXHJcbiAgICAgICAgICAgIFsnbWlzYycsIFsndW5kbycsICdyZWRvJywgJ2Z1bGxzY3JlZW4nLCAnY29kZXZpZXcnXV0sXHJcbiAgICAgICAgXSxcclxuICAgICAgICBzdHlsZVRhZ3M6IFsncCcsICdoMycsICdoNCddLFxyXG4gICAgICAgIGJ1dHRvbnM6IHtcclxuICAgICAgICAgICAgbGZtOiBMRk1CdXR0b25cclxuICAgICAgICB9LFxyXG4gICAgICAgIGRpYWxvZ3NJbkJvZHk6IHRydWVcclxuICAgIH0pO1xyXG59KTtcclxuIl0sInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./resources/js/editor.js\n");

/***/ }),

/***/ 5:
/*!**************************************!*\
  !*** multi ./resources/js/editor.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\devel\web\ohf-community\resources\js\editor.js */"./resources/js/editor.js");


/***/ })

/******/ });