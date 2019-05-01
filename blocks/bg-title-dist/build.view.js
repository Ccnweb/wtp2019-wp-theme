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
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./assets/src/index.view.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/flash-words/flash-words.view.js":
/*!****************************************************!*\
  !*** ./assets/src/flash-words/flash-words.view.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("console.log('flash-words: This runs only in the view.');\ndocument.addEventListener('DOMContentLoaded', flash_words_init, false);\n\nfunction flash_words_init() {\n  if (typeof jQuery == 'undefined') {\n    var headTag = document.getElementsByTagName(\"head\")[0];\n    var jqTag = document.createElement('script');\n    jqTag.type = 'text/javascript';\n    jqTag.src = 'https://code.jquery.com/jquery-3.4.0.slim.min.js';\n    jqTag.onload = load_flash_words;\n    headTag.appendChild(jqTag);\n  } else {\n    load_flash_words();\n  }\n}\n\nfunction load_flash_words() {\n  jQuery('[data-flash-words]').each(function () {\n    var _this = this;\n\n    var flash_words_delay = parseInt(jQuery(this).attr('data-speed')); // ms\n\n    var flash_words = jQuery(this).attr('data-flash-words').split(/\\s*\\,\\s*/);\n    var i = 0;\n    setInterval(function (_) {\n      $(_this).text(flash_words[i]);\n      i = (i + 1) % flash_words.length;\n    }, flash_words_delay);\n  });\n}\n\n//# sourceURL=webpack:///./assets/src/flash-words/flash-words.view.js?");

/***/ }),

/***/ "./assets/src/index.view.js":
/*!**********************************!*\
  !*** ./assets/src/index.view.js ***!
  \**********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _flash_words_flash_words_view_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./flash-words/flash-words.view.js */ \"./assets/src/flash-words/flash-words.view.js\");\n/* harmony import */ var _flash_words_flash_words_view_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_flash_words_flash_words_view_js__WEBPACK_IMPORTED_MODULE_0__);\n\n\n//# sourceURL=webpack:///./assets/src/index.view.js?");

/***/ })

/******/ });