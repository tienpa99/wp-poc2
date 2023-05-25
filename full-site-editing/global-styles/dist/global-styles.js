/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ 107:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(307);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(444);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__);


/**
 * WordPress dependencies
 */

const typography = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__.Path, {
  d: "M6.9 7L3 17.8h1.7l1-2.8h4.1l1 2.8h1.7L8.6 7H6.9zm-.7 6.6l1.5-4.3 1.5 4.3h-3zM21.6 17c-.1.1-.2.2-.3.2-.1.1-.2.1-.4.1s-.3-.1-.4-.2c-.1-.1-.1-.3-.1-.6V12c0-.5 0-1-.1-1.4-.1-.4-.3-.7-.5-1-.2-.2-.5-.4-.9-.5-.4 0-.8-.1-1.3-.1s-1 .1-1.4.2c-.4.1-.7.3-1 .4-.2.2-.4.3-.6.5-.1.2-.2.4-.2.7 0 .3.1.5.2.8.2.2.4.3.8.3.3 0 .6-.1.8-.3.2-.2.3-.4.3-.7 0-.3-.1-.5-.2-.7-.2-.2-.4-.3-.6-.4.2-.2.4-.3.7-.4.3-.1.6-.1.8-.1.3 0 .6 0 .8.1.2.1.4.3.5.5.1.2.2.5.2.9v1.1c0 .3-.1.5-.3.6-.2.2-.5.3-.9.4-.3.1-.7.3-1.1.4-.4.1-.8.3-1.1.5-.3.2-.6.4-.8.7-.2.3-.3.7-.3 1.2 0 .6.2 1.1.5 1.4.3.4.9.5 1.6.5.5 0 1-.1 1.4-.3.4-.2.8-.6 1.1-1.1 0 .4.1.7.3 1 .2.3.6.4 1.2.4.4 0 .7-.1.9-.2.2-.1.5-.3.7-.4h-.3zm-3-.9c-.2.4-.5.7-.8.8-.3.2-.6.2-.8.2-.4 0-.6-.1-.9-.3-.2-.2-.3-.6-.3-1.1 0-.5.1-.9.3-1.2s.5-.5.8-.7c.3-.2.7-.3 1-.5.3-.1.6-.3.7-.6v3.4z"
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (typography);
//# sourceMappingURL=typography.js.map

/***/ }),

/***/ 779:
/***/ ((module, exports) => {

var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
	Copyright (c) 2018 Jed Watson.
	Licensed under the MIT License (MIT), see
	http://jedwatson.github.io/classnames
*/
/* global define */

(function () {
	'use strict';

	var hasOwn = {}.hasOwnProperty;
	var nativeCodeString = '[native code]';

	function classNames() {
		var classes = [];

		for (var i = 0; i < arguments.length; i++) {
			var arg = arguments[i];
			if (!arg) continue;

			var argType = typeof arg;

			if (argType === 'string' || argType === 'number') {
				classes.push(arg);
			} else if (Array.isArray(arg)) {
				if (arg.length) {
					var inner = classNames.apply(null, arg);
					if (inner) {
						classes.push(inner);
					}
				}
			} else if (argType === 'object') {
				if (arg.toString !== Object.prototype.toString && !arg.toString.toString().includes('[native code]')) {
					classes.push(arg.toString());
					continue;
				}

				for (var key in arg) {
					if (hasOwn.call(arg, key) && arg[key]) {
						classes.push(key);
					}
				}
			}
		}

		return classes.join(' ');
	}

	if ( true && module.exports) {
		classNames.default = classNames;
		module.exports = classNames;
	} else if (true) {
		// register as 'classnames', consistent with npm package name
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function () {
			return classNames;
		}).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {}
}());


/***/ }),

/***/ 318:
/***/ (() => {

"use strict";
// extracted by mini-css-extract-plugin


/***/ }),

/***/ 682:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "iU": () => (/* binding */ FONT_BASE),
/* harmony export */   "V6": () => (/* binding */ FONT_BASE_DEFAULT),
/* harmony export */   "GK": () => (/* binding */ FONT_HEADINGS),
/* harmony export */   "c$": () => (/* binding */ FONT_HEADINGS_DEFAULT),
/* harmony export */   "R$": () => (/* binding */ FONT_PAIRINGS),
/* harmony export */   "qD": () => (/* binding */ FONT_OPTIONS),
/* harmony export */   "px": () => (/* binding */ SITE_NAME)
/* harmony export */ });
const FONT_BASE = 'font_base';
const FONT_BASE_DEFAULT = 'font_base_default';
const FONT_HEADINGS = 'font_headings';
const FONT_HEADINGS_DEFAULT = 'font_headings_default';
const FONT_PAIRINGS = 'font_pairings';
const FONT_OPTIONS = 'font_options';
const SITE_NAME = 'blogname';

/***/ }),

/***/ 517:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(818);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(701);
/* harmony import */ var _wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(819);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_2__);



/**
 * DOM updater
 *
 * @param {string[]} options A list of option names to keep track of.
 * @param {Function} getOptionValue A function that given an option name as a string, returns the current option value.
 */

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((options, getOptionValue) => {
  _wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_1___default()(() => {
    // Book-keeping.
    const currentOptions = {};
    let previousOptions = {};
    const cssVariables = {};
    options.forEach(option => {
      cssVariables[option] = `--${option.replace('_', '-')}`;
    });
    let styleElement = null;
    (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.subscribe)(() => {
      /**
       * Do nothing until the editor is ready. This is required when
       * working in wpcom iframe environment to avoid running code before
       * everything has loaded, which can cause bugs like the following.
       *
       * @see https://github.com/Automattic/wp-calypso/pull/40690
       */
      const isEditorReady = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.select)('core/editor').__unstableIsEditorReady;

      if (isEditorReady && isEditorReady() === false) {
        return;
      } // Create style element if it has not been created yet. Must happen
      // after the editor is ready or the style element will be appended
      // before the styles it needs to affect.


      if (!styleElement) {
        styleElement = document.createElement('style');
        document.body.appendChild(styleElement);
      } // Maybe bail-out early.


      options.forEach(option => {
        currentOptions[option] = getOptionValue(option);
      });

      if ((0,lodash__WEBPACK_IMPORTED_MODULE_2__.isEmpty)(currentOptions) || (0,lodash__WEBPACK_IMPORTED_MODULE_2__.isEqual)(currentOptions, previousOptions)) {
        return;
      }

      previousOptions = { ...currentOptions
      }; // Update style node. We need this to be a stylesheet rather than inline styles
      // so the styles apply to all editor instances incl. previews.

      let declarationList = '';
      Object.keys(currentOptions).forEach(key => {
        declarationList += `${cssVariables[key]}:${currentOptions[key]};`;
      });
      styleElement.textContent = `.edit-post-visual-editor .editor-styles-wrapper{${declarationList}}`;
    });
  });
});

/***/ }),

/***/ 296:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(307);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(609);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(736);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_keycodes__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(630);
/* harmony import */ var _wordpress_keycodes__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_keycodes__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(779);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _no_support__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(409);



const __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__;



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_ref => {
  let {
    fontPairings,
    fontBase,
    fontHeadings,
    update
  } = _ref;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", null, __('Font Pairings', 'full-site-editing')), fontPairings && fontHeadings && fontBase ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "style-preview__font-options"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "style-preview__font-options-desktop"
  }, fontPairings.map(_ref2 => {
    let {
      label,
      headings,
      base
    } = _ref2;
    const isSelected = headings === fontHeadings && base === fontBase;
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
      className: classnames__WEBPACK_IMPORTED_MODULE_4___default()('style-preview__font-option', {
        'is-selected': isSelected
      }),
      onClick: () => update({
        headings,
        base
      }),
      onKeyDown: event => event.keyCode === _wordpress_keycodes__WEBPACK_IMPORTED_MODULE_3__.ENTER ? update({
        headings,
        base
      }) : null,
      key: label
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "style-preview__font-option-contents"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      style: {
        fontFamily: headings,
        fontWeight: 700
      }
    }, headings), "\xA0/\xA0", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      style: {
        fontFamily: base
      }
    }, base)));
  }))) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_no_support__WEBPACK_IMPORTED_MODULE_5__/* ["default"] */ .Z, {
    unsupportedFeature: __('font pairings', 'full-site-editing')
  }));
});

/***/ }),

/***/ 529:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(307);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(609);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(736);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _no_support__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(409);



const __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__;

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_ref => {
  let {
    fontBase,
    fontBaseDefault,
    fontHeadings,
    fontHeadingsDefault,
    fontBaseOptions,
    fontHeadingsOptions,
    updateBaseFont,
    updateHeadingsFont
  } = _ref;

  if (!fontBaseOptions || !fontHeadingsOptions) {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_no_support__WEBPACK_IMPORTED_MODULE_3__/* ["default"] */ .Z, {
      unsupportedFeature: __('custom font selection', 'full-site-editing')
    });
  }

  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.SelectControl, {
    label: __('Heading Font', 'full-site-editing'),
    value: fontHeadings,
    options: fontHeadingsOptions,
    onChange: newValue => updateHeadingsFont(newValue),
    style: {
      fontFamily: fontHeadings !== 'unset' ? fontHeadings : fontHeadingsDefault
    }
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.SelectControl, {
    label: __('Base Font', 'full-site-editing'),
    value: fontBase,
    options: fontBaseOptions,
    onChange: newValue => updateBaseFont(newValue),
    style: {
      fontFamily: fontBase !== 'unset' ? fontBase : fontBaseDefault
    }
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("hr", null));
});

/***/ }),

/***/ 464:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(307);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(609);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(818);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(67);
/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(736);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_icons__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(107);
/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(483);
/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_url__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(682);
/* harmony import */ var _font_pairings_panel__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(296);
/* harmony import */ var _font_selection_panel__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(529);






const __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__;





const ANY_PROPERTY = 'ANY_PROPERTY';

const isFor = filterProperty => option => option.prop === ANY_PROPERTY || option.prop === filterProperty;

const toOption = font => {
  if (typeof font === 'object') {
    const {
      label,
      value,
      prop = ANY_PROPERTY
    } = font;
    return {
      label,
      value,
      prop
    };
  }

  return {
    label: font,
    value: font,
    prop: ANY_PROPERTY
  };
};

const isNotNull = option => option.value !== null && option.label !== null;

const toOptions = (options, filterProperty) => !options ? [] : options.map(toOption).filter(isNotNull).filter(isFor(filterProperty));

const PanelActionButtons = _ref => {
  let {
    hasLocalChanges,
    resetAction,
    publishAction,
    className = null
  } = _ref;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: className
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
    disabled: !hasLocalChanges,
    isDefault: true,
    onClick: resetAction
  }, __('Reset', 'full-site-editing')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
    className: "global-styles-sidebar__publish-button",
    disabled: !hasLocalChanges,
    isPrimary: true,
    onClick: publishAction
  }, __('Publish', 'full-site-editing')));
};

function maybeOpenSidebar() {
  const openSidebar = (0,_wordpress_url__WEBPACK_IMPORTED_MODULE_5__.getQueryArg)(window.location.href, 'openSidebar');

  if ('global-styles' === openSidebar) {
    (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.dispatch)('core/edit-post').openGeneralSidebar('jetpack-global-styles/global-styles');
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_ref2 => {
  let {
    fontHeadings,
    fontHeadingsDefault,
    fontBase,
    fontBaseDefault,
    fontPairings,
    fontOptions,
    siteName,
    publishOptions,
    updateOptions,
    hasLocalChanges,
    resetLocalChanges
  } = _ref2;
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    maybeOpenSidebar();
  }, []);

  const publish = () => publishOptions({
    [_constants__WEBPACK_IMPORTED_MODULE_6__/* .FONT_BASE */ .iU]: fontBase,
    [_constants__WEBPACK_IMPORTED_MODULE_6__/* .FONT_HEADINGS */ .GK]: fontHeadings
  });

  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_3__.PluginSidebarMoreMenuItem, {
    icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_9__/* ["default"] */ .Z,
    target: "global-styles"
  }, __('Global Styles', 'full-site-editing')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_3__.PluginSidebar, {
    icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_9__/* ["default"] */ .Z,
    name: "global-styles",
    title: __('Global Styles', 'full-site-editing'),
    className: "global-styles-sidebar"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.PanelBody, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null,
  /* translators: %s: Name of site. */
  (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.sprintf)(__('You are customizing %s.', 'full-site-editing'), siteName)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, __('Any change you make here will apply to the entire website.', 'full-site-editing')), hasLocalChanges ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("em", null, __('You have unsaved changes.', 'full-site-editing'))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelActionButtons, {
    hasLocalChanges: hasLocalChanges,
    publishAction: publish,
    resetAction: resetLocalChanges
  })) : null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.PanelBody, {
    title: __('Font Selection', 'full-site-editing')
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_font_selection_panel__WEBPACK_IMPORTED_MODULE_8__/* ["default"] */ .Z, {
    fontBase: fontBase,
    fontBaseDefault: fontBaseDefault,
    fontHeadings: fontHeadings,
    fontHeadingsDefault: fontHeadingsDefault,
    fontBaseOptions: toOptions(fontOptions, _constants__WEBPACK_IMPORTED_MODULE_6__/* .FONT_BASE */ .iU),
    fontHeadingsOptions: toOptions(fontOptions, _constants__WEBPACK_IMPORTED_MODULE_6__/* .FONT_HEADINGS */ .GK),
    updateBaseFont: value => updateOptions({
      [_constants__WEBPACK_IMPORTED_MODULE_6__/* .FONT_BASE */ .iU]: value
    }),
    updateHeadingsFont: value => updateOptions({
      [_constants__WEBPACK_IMPORTED_MODULE_6__/* .FONT_HEADINGS */ .GK]: value
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_font_pairings_panel__WEBPACK_IMPORTED_MODULE_7__/* ["default"] */ .Z, {
    fontHeadings: fontHeadings,
    fontBase: fontBase,
    fontPairings: fontPairings,
    update: _ref3 => {
      let {
        headings,
        base
      } = _ref3;
      return updateOptions({
        [_constants__WEBPACK_IMPORTED_MODULE_6__/* .FONT_HEADINGS */ .GK]: headings,
        [_constants__WEBPACK_IMPORTED_MODULE_6__/* .FONT_BASE */ .iU]: base
      });
    }
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.PanelBody, null, hasLocalChanges ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("em", null, __('You have unsaved changes.', 'full-site-editing'))) : null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelActionButtons, {
    hasLocalChanges: hasLocalChanges,
    publishAction: publish,
    resetAction: resetLocalChanges,
    className: "global-styles-sidebar__panel-action-buttons"
  }))));
});

/***/ }),

/***/ 409:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(307);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(736);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);


const __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_ref => {
  let {
    unsupportedFeature
  } = _ref;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.sprintf)(
  /* translators: %s: feature name (i.e. font pairings, etc) */
  __("Your active theme doesn't support %s.", 'full-site-editing'), unsupportedFeature));
});

/***/ }),

/***/ 942:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "h": () => (/* binding */ store)
/* harmony export */ });
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(989);
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(818);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);

 // Global data passed from PHP.

const {
  STORE_NAME,
  REST_PATH
} = JETPACK_GLOBAL_STYLES_EDITOR_CONSTANTS; // eslint-disable-line no-undef

let cache = {};
let alreadyFetchedOptions = false;
const actions = {
  *publishOptions(options) {
    yield {
      type: 'IO_PUBLISH_OPTIONS',
      options
    };
    return {
      type: 'PUBLISH_OPTIONS',
      options
    };
  },

  updateOptions(options) {
    return {
      type: 'UPDATE_OPTIONS',
      options
    };
  },

  fetchOptions() {
    return {
      type: 'IO_FETCH_OPTIONS'
    };
  },

  resetLocalChanges() {
    return {
      type: 'RESET_OPTIONS',
      options: cache
    };
  }

};
const store = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.createReduxStore)(STORE_NAME, {
  reducer(state, action) {
    switch (action.type) {
      case 'UPDATE_OPTIONS':
      case 'RESET_OPTIONS':
      case 'PUBLISH_OPTIONS':
        return { ...state,
          ...action.options
        };
    }

    return state;
  },

  actions,
  selectors: {
    getOption(state, key) {
      return state ? state[key] : undefined;
    },

    hasLocalChanges(state) {
      return !!state && Object.keys(cache).some(key => cache[key] !== state[key]);
    }

  },
  resolvers: {
    // eslint-disable-next-line no-unused-vars
    *getOption(key) {
      if (alreadyFetchedOptions) {
        return; // do nothing
      }

      let options;

      try {
        alreadyFetchedOptions = true;
        options = yield actions.fetchOptions();
      } catch (error) {
        options = {};
      }

      cache = options;
      return {
        type: 'UPDATE_OPTIONS',
        options
      };
    }

  },
  controls: {
    IO_FETCH_OPTIONS() {
      return _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_0___default()({
        path: REST_PATH
      });
    },

    IO_PUBLISH_OPTIONS(_ref) {
      let {
        options
      } = _ref;
      cache = options; // optimistically update the cache

      return _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_0___default()({
        path: REST_PATH,
        method: 'POST',
        data: { ...options
        }
      });
    }

  }
});
(0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.register)(store);

/***/ }),

/***/ 819:
/***/ ((module) => {

"use strict";
module.exports = window["lodash"];

/***/ }),

/***/ 989:
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["apiFetch"];

/***/ }),

/***/ 609:
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["components"];

/***/ }),

/***/ 333:
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["compose"];

/***/ }),

/***/ 818:
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["data"];

/***/ }),

/***/ 701:
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["domReady"];

/***/ }),

/***/ 67:
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["editPost"];

/***/ }),

/***/ 307:
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["element"];

/***/ }),

/***/ 736:
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["i18n"];

/***/ }),

/***/ 630:
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["keycodes"];

/***/ }),

/***/ 817:
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["plugins"];

/***/ }),

/***/ 444:
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["primitives"];

/***/ }),

/***/ 483:
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["url"];

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
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(333);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(818);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(817);
/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_plugins__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _src_constants__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(682);
/* harmony import */ var _src_dom_updater__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(517);
/* harmony import */ var _src_global_styles_sidebar__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(464);
/* harmony import */ var _src_store__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(942);
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(318);






 // Tell Webpack to compile this into CSS

 // Global data passed from PHP.

const {
  PLUGIN_NAME
} = JETPACK_GLOBAL_STYLES_EDITOR_CONSTANTS; // eslint-disable-line no-undef

(0,_src_dom_updater__WEBPACK_IMPORTED_MODULE_4__/* ["default"] */ .Z)([_src_constants__WEBPACK_IMPORTED_MODULE_3__/* .FONT_BASE */ .iU, _src_constants__WEBPACK_IMPORTED_MODULE_3__/* .FONT_HEADINGS */ .GK], (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.select)(_src_store__WEBPACK_IMPORTED_MODULE_6__/* .store */ .h).getOption);
(0,_wordpress_plugins__WEBPACK_IMPORTED_MODULE_2__.registerPlugin)(PLUGIN_NAME, {
  render: (0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_0__.compose)((0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.withSelect)(select => {
    const {
      getOption,
      hasLocalChanges
    } = select(_src_store__WEBPACK_IMPORTED_MODULE_6__/* .store */ .h);
    return {
      siteName: getOption(_src_constants__WEBPACK_IMPORTED_MODULE_3__/* .SITE_NAME */ .px),
      fontHeadings: getOption(_src_constants__WEBPACK_IMPORTED_MODULE_3__/* .FONT_HEADINGS */ .GK),
      fontHeadingsDefault: getOption(_src_constants__WEBPACK_IMPORTED_MODULE_3__/* .FONT_HEADINGS_DEFAULT */ .c$),
      fontBase: getOption(_src_constants__WEBPACK_IMPORTED_MODULE_3__/* .FONT_BASE */ .iU),
      fontBaseDefault: getOption(_src_constants__WEBPACK_IMPORTED_MODULE_3__/* .FONT_BASE_DEFAULT */ .V6),
      fontPairings: getOption(_src_constants__WEBPACK_IMPORTED_MODULE_3__/* .FONT_PAIRINGS */ .R$),
      fontOptions: getOption(_src_constants__WEBPACK_IMPORTED_MODULE_3__/* .FONT_OPTIONS */ .qD),
      hasLocalChanges: hasLocalChanges()
    };
  }), (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.withDispatch)(dispatch => ({
    updateOptions: dispatch(_src_store__WEBPACK_IMPORTED_MODULE_6__/* .store */ .h).updateOptions,
    publishOptions: dispatch(_src_store__WEBPACK_IMPORTED_MODULE_6__/* .store */ .h).publishOptions,
    resetLocalChanges: dispatch(_src_store__WEBPACK_IMPORTED_MODULE_6__/* .store */ .h).resetLocalChanges
  })))(_src_global_styles_sidebar__WEBPACK_IMPORTED_MODULE_5__/* ["default"] */ .Z)
});
})();

window.EditingToolkit = __webpack_exports__;
/******/ })()
;