/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ 731:
/***/ (() => {

// extracted by mini-css-extract-plugin


/***/ }),

/***/ 6:
/***/ ((__unused_webpack_module, __unused_webpack___webpack_exports__, __webpack_require__) => {

/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(694);
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _style_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(731);
var _window;




function overrideCoreDocumentationLinksToWpcom(translation, text) {
  switch (text) {
    case 'https://wordpress.org/support/article/excerpt/':
    case 'https://wordpress.org/support/article/settings-sidebar/#excerpt':
      return 'https://wordpress.com/support/excerpts/';

    case 'https://wordpress.org/support/article/writing-posts/#post-field-descriptions':
    case 'https://wordpress.org/support/article/settings-sidebar/#permalink':
      return 'https://wordpress.com/support/permalinks-and-slugs/';

    case 'https://wordpress.org/support/article/wordpress-editor/':
      return 'https://wordpress.com/support/wordpress-editor/';

    case 'https://wordpress.org/support/article/site-editor/':
      return 'https://wordpress.com/support/site-editor/';

    case 'https://wordpress.org/support/article/block-based-widgets-editor/':
      return 'https://wordpress.com/support/widgets/';

    case 'https://wordpress.org/plugins/classic-widgets/':
      return 'https://wordpress.com/plugins/classic-widgets';

    case 'https://wordpress.org/support/article/styles-overview/':
      return 'https://wordpress.com/support/using-styles/';
  }

  return translation;
}

function hideSimpleSiteTranslations(translation, text) {
  switch (text) {
    case 'https://wordpress.org/plugins/classic-widgets/':
      return '';

    case 'Want to stick with the old widgets?':
      return '';

    case 'Get the Classic Widgets plugin.':
      return '';
  }

  return translation;
}

(0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__.addFilter)('i18n.gettext_default', 'full-site-editing/override-core-docs-to-wpcom', overrideCoreDocumentationLinksToWpcom, 9);

if (((_window = window) === null || _window === void 0 ? void 0 : _window._currentSiteType) === 'simple') {
  (0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__.addFilter)('i18n.gettext_default', 'full-site-editing/override-core-docs-to-wpcom', hideSimpleSiteTranslations, 10);
}

/***/ }),

/***/ 694:
/***/ ((module) => {

module.exports = window["wp"]["hooks"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _src__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(6);

})();

window.EditingToolkit = __webpack_exports__;
/******/ })()
;