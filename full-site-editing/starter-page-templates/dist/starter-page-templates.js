/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

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

/***/ 334:
/***/ (() => {

"use strict";
// extracted by mini-css-extract-plugin


/***/ }),

/***/ 28:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "P": () => (/* binding */ PagePatternsPlugin)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(896);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(307);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _automattic_page_pattern_modal__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(18);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(818);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(694);
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(736);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(168);
/* harmony import */ var _wordpress_nux__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(462);
/* harmony import */ var _wordpress_nux__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_nux__WEBPACK_IMPORTED_MODULE_5__);







const __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__;


const INSERTING_HOOK_NAME = 'isInsertingPagePattern';
const INSERTING_HOOK_NAMESPACE = 'automattic/full-site-editing/inserting-pattern';
function PagePatternsPlugin(props) {
  const {
    setOpenState
  } = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.useDispatch)(_store__WEBPACK_IMPORTED_MODULE_4__/* .pageLayoutStore */ .I);
  const {
    setUsedPageOrPatternsModal
  } = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.useDispatch)('automattic/wpcom-welcome-guide');
  const {
    replaceInnerBlocks
  } = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.useDispatch)('core/block-editor');
  const {
    editPost
  } = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.useDispatch)('core/editor');
  const {
    toggleFeature
  } = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.useDispatch)('core/edit-post');
  const {
    disableTips
  } = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.useDispatch)('core/nux');
  const selectProps = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.useSelect)(select => {
    const {
      isOpen,
      isPatternPicker
    } = select(_store__WEBPACK_IMPORTED_MODULE_4__/* .pageLayoutStore */ .I);
    return {
      isOpen: isOpen(),
      isWelcomeGuideActive: select('core/edit-post').isFeatureActive('welcomeGuide'),
      // Gutenberg 7.2.0 or higher
      areTipsEnabled: select('core/nux') ? select('core/nux').areTipsEnabled() : false,
      // Gutenberg 7.1.0 or lower
      ...(isPatternPicker() && {
        title: __('Choose a Pattern', 'full-site-editing'),
        description: __('Pick a pre-defined layout or continue with a blank page', 'full-site-editing')
      })
    };
  }, []);
  const {
    getMeta,
    postContentBlock
  } = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.useSelect)(select => {
    const getMeta = () => select('core/editor').getEditedPostAttribute('meta');

    const currentBlocks = select('core/editor').getBlocks();
    return {
      getMeta,
      postContentBlock: currentBlocks.find(block => block.name === 'a8c/post-content')
    };
  }, []);
  const savePatternChoice = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useCallback)(name => {
    // Save selected pattern slug in meta.
    const currentMeta = getMeta();
    editPost({
      meta: { ...currentMeta,
        _starter_page_template: name
      }
    });
  }, [editPost, getMeta]);
  const insertPattern = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useCallback)((title, blocks) => {
    // Add filter to let the tracking library know we are inserting a template.
    (0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_2__.addFilter)(INSERTING_HOOK_NAME, INSERTING_HOOK_NAMESPACE, () => true); // Set post title.

    if (title) {
      editPost({
        title
      });
    } // Replace blocks.


    replaceInnerBlocks(postContentBlock ? postContentBlock.clientId : '', blocks, false); // Remove filter.

    (0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_2__.removeFilter)(INSERTING_HOOK_NAME, INSERTING_HOOK_NAMESPACE);
  }, [editPost, postContentBlock, replaceInnerBlocks]);
  const {
    isWelcomeGuideActive,
    areTipsEnabled
  } = selectProps;
  const hideWelcomeGuide = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useCallback)(() => {
    if (isWelcomeGuideActive) {
      // Gutenberg 7.2.0 or higher.
      toggleFeature('welcomeGuide');
    } else if (areTipsEnabled) {
      // Gutenberg 7.1.0 or lower.
      disableTips();
    }
  }, [areTipsEnabled, disableTips, isWelcomeGuideActive, toggleFeature]);
  const handleClose = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useCallback)(() => {
    setUsedPageOrPatternsModal();
    setOpenState('CLOSED');
  }, [setOpenState, setUsedPageOrPatternsModal]);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_automattic_page_pattern_modal__WEBPACK_IMPORTED_MODULE_6__/* ["default"] */ .Z, (0,_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_7__/* ["default"] */ .Z)({}, selectProps, {
    onClose: handleClose,
    savePatternChoice: savePatternChoice,
    insertPattern: insertPattern,
    hideWelcomeGuide: hideWelcomeGuide
  }, props));
}

/***/ }),

/***/ 168:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "I": () => (/* binding */ pageLayoutStore)
/* harmony export */ });
/* unused harmony export selectors */
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(818);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);


const reducer = function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'CLOSED';
  let {
    type,
    ...action
  } = arguments.length > 1 ? arguments[1] : undefined;
  return 'SET_IS_OPEN' === type ? action.openState : state;
};

const actions = {
  setOpenState: openState => ({
    type: 'SET_IS_OPEN',
    openState: openState || 'CLOSED'
  })
};
const selectors = {
  isOpen: state => 'CLOSED' !== state,
  isPatternPicker: state => 'OPEN_FOR_BLANK_CANVAS' === state
};
const pageLayoutStore = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.createReduxStore)('automattic/starter-page-layouts', {
  reducer,
  actions,
  selectors
});
(0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.register)(pageLayoutStore);

/***/ }),

/***/ 18:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(666);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(307);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(981);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(609);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(333);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(736);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(779);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(819);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _utils_contains_missing_block__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(531);
/* harmony import */ var _utils_group_utils__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(988);
/* harmony import */ var _utils_map_blocks_recursively__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(480);
/* harmony import */ var _utils_replace_placeholders__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(656);
/* harmony import */ var _utils_tracking__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(884);
/* harmony import */ var _pattern_selector_control__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(958);







const __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__;









class PagePatternModal extends _wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Component {
  constructor(props) {
    super(props);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7__/* ["default"] */ .Z)(this, "getFormattedPatternsByPatternSlugs", (0,lodash__WEBPACK_IMPORTED_MODULE_6__.memoize)(patterns => {
      const blocksByPatternSlugs = patterns.reduce((prev, _ref) => {
        let {
          name,
          description = '',
          html,
          pattern_meta
        } = _ref;
        // The default value is from https://github.com/Automattic/wp-calypso/blob/d22976d8250fb4479d5677f5434742878fbd0ef3/apps/editing-toolkit/editing-toolkit-plugin/block-patterns/class-block-patterns-from-api.php#L115
        const viewportWidth = pattern_meta !== null && pattern_meta !== void 0 && pattern_meta.viewport_width ? Number(pattern_meta.viewport_width) : 1280;
        prev[name] = {
          name,
          // Keep showing the description as before instead of the title
          title: description,
          blocks: html ? (0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__.parse)((0,_utils_replace_placeholders__WEBPACK_IMPORTED_MODULE_8__/* ["default"] */ .Z)(html, this.props.siteInformation)) : [],
          viewportWidth: Math.max(viewportWidth, 320)
        };
        return prev;
      }, {}); // Remove patterns that include a missing block

      return this.filterPatternsWithMissingBlocks(blocksByPatternSlugs);
    }));

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7__/* ["default"] */ .Z)(this, "getBlocksForSelection", selectedPattern => {
      const blocks = this.getBlocksByPatternSlug(selectedPattern); // Modify the existing blocks returning new block object references.

      return (0,_utils_map_blocks_recursively__WEBPACK_IMPORTED_MODULE_9__/* ["default"] */ .Z)(blocks, function modifyBlocksForSelection(block) {
        // Ensure that core/button doesn't link to external pattern site
        if ('core/button' === block.name && undefined !== block.attributes.url) {
          block.attributes.url = '#';
        }

        return block;
      });
    });

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7__/* ["default"] */ .Z)(this, "setPattern", name => {
      // Track selection and mark post as using a pattern in its postmeta.
      (0,_utils_tracking__WEBPACK_IMPORTED_MODULE_10__/* .trackSelection */ .E2)(name);
      this.props.savePatternChoice(name); // Check to see if this is a blank pattern selection
      // and reset the pattern if so.

      if ('blank' === name) {
        this.props.insertPattern('', []);
        this.props.onClose();
        return;
      }

      const pattern = this.props.patterns.find(t => t.name === name);
      const isHomepagePattern = ((pattern === null || pattern === void 0 ? void 0 : pattern.categories) || {}).hasOwnProperty('home'); // Load content.

      const blocks = this.getBlocksForSelection(name); // Only overwrite the page title if the pattern is not one of the Homepage Layouts

      const title = isHomepagePattern ? null : (pattern === null || pattern === void 0 ? void 0 : pattern.title) || ''; // Skip inserting if this is not a blank pattern
      // and there's nothing to insert.

      if (!blocks || !blocks.length) {
        this.props.onClose();
        return;
      }

      this.props.insertPattern(title, blocks);
      this.props.onClose();
    });

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7__/* ["default"] */ .Z)(this, "handleCategorySelection", selectedCategory => {
      this.setState({
        selectedCategory
      });
    });

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7__/* ["default"] */ .Z)(this, "closeModal", event => {
      // As of Gutenberg 13.1, the editor will auto-focus on the title block
      // automatically. See: https://github.com/WordPress/gutenberg/pull/40195.
      // This ends up triggering a `blur` event on the Modal that causes it
      // to close just after the editor loads. To circumvent this, we check if
      // the event is a `blur`, and if that's the case, we return before the
      // function is able to close the modal. Originally, you can't click outside
      // the modal to close it, meaning it doesn't respond to the `blur` event
      // anyways, so it's safe to use this simpler approach instead of trying to
      // check what element triggered the `blur` (which doesn't work when the
      // theme is block-based, as the title block DOM element is not directly
      // accessible as it's inside the `editor-canvas` iframe).
      if (event.type === 'blur') {
        event.stopPropagation();
        return;
      }

      (0,_utils_tracking__WEBPACK_IMPORTED_MODULE_10__/* .trackDismiss */ .GI)();
      this.props.onClose();
    });

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7__/* ["default"] */ .Z)(this, "getPatternGroups", () => {
      if (!this.props.patterns.length) {
        return null;
      }

      const patternGroups = {};

      for (const pattern of this.props.patterns) {
        for (const key in pattern.categories) {
          if (!(key in patternGroups)) {
            patternGroups[key] = pattern.categories[key];
          }
        }
      }

      const preferredGroupOrder = ['featured', 'about', 'blog', 'home', 'gallery', 'services', 'contact'];
      return (0,_utils_group_utils__WEBPACK_IMPORTED_MODULE_11__/* .sortGroupNames */ .x)(preferredGroupOrder, patternGroups);
    });

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7__/* ["default"] */ .Z)(this, "getPatternsForGroup", groupName => {
      if (!this.props.patterns.length) {
        return null;
      }

      if ('blank' === groupName) {
        return [{
          name: 'blank',
          title: 'Blank',
          html: '',
          ID: null
        }];
      }

      const patterns = [];

      for (const pattern of this.props.patterns) {
        for (const key in pattern.categories) {
          if (key === groupName) {
            patterns.push(pattern);
          }
        }
      }

      return patterns;
    });

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7__/* ["default"] */ .Z)(this, "getPatternCategories", () => {
      const groups = this.getPatternGroups();

      if (!groups) {
        return null;
      }

      const categories = [];

      for (const key in groups) {
        categories.push({
          slug: key,
          name: groups[key].title
        });
      }

      return categories;
    });

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7__/* ["default"] */ .Z)(this, "renderPatternGroup", () => {
      const {
        selectedCategory
      } = this.state;

      if (!selectedCategory) {
        return null;
      }

      const patterns = this.getPatternsForGroup(selectedCategory);

      if (!(patterns !== null && patterns !== void 0 && patterns.length)) {
        return null;
      }

      return this.renderPatternsList(patterns);
    });

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7__/* ["default"] */ .Z)(this, "renderPatternsList", patternsList => {
      if (!patternsList.length) {
        return null;
      } // The raw `patterns` prop is not filtered to remove patterns that
      // contain missing Blocks. Therefore we compare with the keys of the
      // filtered patterns from `getBlocksByPatternSlugs()` and filter this
      // list to match. This ensures that the list of pattern thumbnails is
      // filtered so that it does not include patterns that have missing Blocks.


      const formattedPatternsByPatternSlug = this.getFormattedPatternsByPatternSlugs(this.props.patterns);
      const patternsWithoutMissingBlocks = Object.keys(formattedPatternsByPatternSlug);

      const filterOutPatternsWithMissingBlocks = (patternsToFilter, filterIn) => {
        return patternsToFilter.filter(pattern => filterIn.includes(pattern.name));
      };

      const filteredPatternsList = filterOutPatternsWithMissingBlocks(patternsList, patternsWithoutMissingBlocks);

      if (!filteredPatternsList.length) {
        return null;
      }

      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_pattern_selector_control__WEBPACK_IMPORTED_MODULE_12__/* ["default"] */ .Z, {
        label: __('Layout', "full-site-editing"),
        patterns: filteredPatternsList.map(pattern => formattedPatternsByPatternSlug[pattern.name]),
        onPatternSelect: this.setPattern
      });
    });

    this.state = {
      selectedCategory: this.getDefaultSelectedCategory()
    };
  } // Parse patterns blocks and memoize them.


  filterPatternsWithMissingBlocks(patterns) {
    return Object.entries(patterns).reduce((acc, _ref2) => {
      let [name, pattern] = _ref2;
      // Does the pattern contain any missing blocks?
      const patternHasMissingBlocks = (0,_utils_contains_missing_block__WEBPACK_IMPORTED_MODULE_13__/* ["default"] */ .Z)(pattern.blocks); // Only retain the pattern in the collection if:
      // 1. It does not contain any missing blocks
      // 2. There are no blocks at all (likely the "blank" pattern placeholder)

      if (!patternHasMissingBlocks || !pattern.blocks.length) {
        acc[name] = pattern;
      }

      return acc;
    }, {});
  }

  componentDidMount() {
    if (this.props.isOpen) {
      this.trackCurrentView();
    }
  }

  componentDidUpdate(prevProps) {
    // Only track when the modal is first displayed
    // and if it didn't already happen during componentDidMount.
    if (!prevProps.isOpen && this.props.isOpen) {
      this.trackCurrentView();
    } // Disable welcome guide right away as it collides with the modal window.


    if (this.props.isWelcomeGuideActive || this.props.areTipsEnabled) {
      this.props.hideWelcomeGuide();
    }
  }

  trackCurrentView() {
    (0,_utils_tracking__WEBPACK_IMPORTED_MODULE_10__/* .trackView */ .Fk)('add-page');
  }

  getDefaultSelectedCategory() {
    const categories = this.getPatternCategories();

    if (!(categories !== null && categories !== void 0 && categories.length)) {
      return null;
    }

    return categories[0].slug;
  }

  getBlocksByPatternSlug(name) {
    var _this$getFormattedPat, _this$getFormattedPat2;

    return ((_this$getFormattedPat = this.getFormattedPatternsByPatternSlugs(this.props.patterns)) === null || _this$getFormattedPat === void 0 ? void 0 : (_this$getFormattedPat2 = _this$getFormattedPat[name]) === null || _this$getFormattedPat2 === void 0 ? void 0 : _this$getFormattedPat2.blocks) ?? [];
  }

  render() {
    var _this$getPatternCateg, _this$getPatternCateg2;

    const {
      selectedCategory
    } = this.state;
    const {
      isOpen,
      instanceId
    } = this.props;

    if (!isOpen) {
      return null;
    }

    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Modal, {
      title: "" // We're providing the title with the `aria.labelledby` prop
      ,
      className: "page-pattern-modal" // @ts-expect-error `onRequestClose`'s type is () => void but ideally but
      // in reality, it might receive an event object that might be one of multiple
      // types (see the `CloseModalEvent` type above for more info). We ignore the
      // error for now until the type is updated in DefinitelyTyped.
      // DT PR: https://github.com/DefinitelyTyped/DefinitelyTyped/pull/60127.
      ,
      onRequestClose: this.closeModal,
      aria: {
        labelledby: `page-pattern-modal__heading-${instanceId}`,
        describedby: `page-pattern-modal__description-${instanceId}`
      }
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "page-pattern-modal__inner"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "page-pattern-modal__sidebar"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h1", {
      id: `page-pattern-modal__heading-${instanceId}`,
      className: classnames__WEBPACK_IMPORTED_MODULE_5___default()('page-pattern-modal__heading', {
        'page-pattern-modal__heading--default': !this.props.title
      })
    }, this.props.title || __('Add a page', "full-site-editing")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
      id: `page-pattern-modal__description-${instanceId}`,
      className: "page-pattern-modal__description"
    }, this.props.description || __('Pick a pre-defined layout or start with a blank page.', "full-site-editing")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "page-pattern-modal__button-container"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
      isSecondary: true,
      onClick: () => this.setPattern('blank'),
      className: "page-pattern-modal__blank-button"
    }, __('Blank page', "full-site-editing")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("select", {
      className: "page-pattern-modal__mobile-category-dropdown",
      value: selectedCategory ?? undefined,
      onChange: e => this.handleCategorySelection(e.currentTarget.value)
    }, (_this$getPatternCateg = this.getPatternCategories()) === null || _this$getPatternCateg === void 0 ? void 0 : _this$getPatternCateg.map(_ref3 => {
      let {
        slug,
        name
      } = _ref3;
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("option", {
        key: slug,
        value: slug
      }, name);
    }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.VisuallyHidden, {
      as: "h2",
      id: `page-pattern-modal__list-heading-${instanceId}`
    }, __('Page categories', "full-site-editing")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.NavigableMenu, {
      className: "page-pattern-modal__category-list",
      orientation: "vertical",
      "aria-labelledby": `page-pattern-modal__list-heading-${instanceId}`,
      onNavigate: (_index, child) => this.handleCategorySelection(child.dataset.slug ?? null)
    }, (_this$getPatternCateg2 = this.getPatternCategories()) === null || _this$getPatternCateg2 === void 0 ? void 0 : _this$getPatternCateg2.map(_ref4 => {
      let {
        slug,
        name
      } = _ref4;
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.MenuItem, {
        key: slug,
        isTertiary: true,
        "aria-selected": slug === selectedCategory,
        "data-slug": slug,
        onClick: () => this.handleCategorySelection(slug),
        className: "page-pattern-modal__category-button",
        tabIndex: slug === selectedCategory ? undefined : -1
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
        className: "page-pattern-modal__category-item-selection-wrapper"
      }, name));
    }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "page-pattern-modal__pattern-list-container"
    }, this.renderPatternGroup())));
  }

}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__.withInstanceId)(PagePatternModal));

/***/ }),

/***/ 958:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* unused harmony export PatternSelectorControl */
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(307);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(175);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(609);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(333);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__);

// @ts-expect-error Missing definition
// eslint-disable-next-line wpcalypso/no-unsafe-wp-apis





const noop = () => undefined;

const PatternSelectorControl = _ref => {
  let {
    instanceId,
    label,
    patterns = [],
    onPatternSelect = noop
  } = _ref;
  const shownPatterns = (0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__.useAsyncList)(patterns);

  if (!Array.isArray(patterns) || !patterns.length) {
    return null;
  }

  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.BaseControl, {
    id: `pattern-selector-control__${instanceId}`,
    label: label,
    className: "pattern-selector-control"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.__experimentalBlockPatternsList, {
    blockPatterns: patterns,
    shownPatterns: shownPatterns,
    onClickPattern: pattern => onPatternSelect(pattern.name)
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.memo)((0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__.withInstanceId)(PatternSelectorControl)));

/***/ }),

/***/ 531:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
// Once parsed, missing Blocks have a name prop of `core/missing`.
// see: https://github.com/WordPress/gutenberg/tree/742dbf2ef0e37481a3c14c29f3688aa0cd3cf887/packages/block-library/src/missing
const MISSING_BLOCK_NAME = 'core/missing';
/**
 * Determines whether the provided collection of Blocks contains any "missing"
 * blocks as determined by the presence of the `core/missing` block type.
 *
 * @param blocks the collection of block objects to check for "missing" block .
 * @returns whether the collection blocks contains any missing blocks.
 */

function containsMissingBlock(blocks) {
  return !!blocks.find(block => {
    // If we found a missing block the bale out immediately
    if (block.name === MISSING_BLOCK_NAME) {
      return true;
    } // If there are innerblocks then recurse down into them...


    if (block.innerBlocks && block.innerBlocks.length) {
      return containsMissingBlock(block.innerBlocks);
    }

    return false;
  });
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (containsMissingBlock);

/***/ }),

/***/ 988:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "x": () => (/* binding */ sortGroupNames)
/* harmony export */ });
/**
 * Sorts the keys on the group object to have a preferred order.
 * If some groups exist without a preferred order, they will be included last
 *
 * @param preferredGroupOrder the order of group slugs that we want
 * @param groupsObject an object with all group information, with group names as keys
 */
function sortGroupNames(preferredGroupOrder, groupsObject) {
  const groups = Object.keys(groupsObject);
  const orderedGroups = preferredGroupOrder.filter(x => groups.includes(x));
  const remainingGroups = groups.filter(x => !preferredGroupOrder.includes(x));
  const allGroups = orderedGroups.concat(remainingGroups.sort());
  return allGroups.reduce((result, groupName) => {
    result[groupName] = groupsObject[groupName];
    return result;
  }, {});
}

/***/ }),

/***/ 480:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(981);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);


/**
 * Recursively maps over a collection of blocks calling the modifier function on
 * each to modify it and returning a collection of new block references.
 *
 * @param blocks an array of block objects
 * @param modifier a callback function used to modify the blocks
 */
function mapBlocksRecursively(blocks, modifier) {
  return blocks.map(block => {
    // `blocks` is an object. Therefore any changes made here will
    // be reflected across all references to the blocks object. To ensure we
    // only modify the blocks when needed, we return a new object reference
    // for any blocks we modify. This allows us to modify blocks for
    // particular contexts. For example we may wish to show blocks
    // differently in the preview than we do when they are inserted into the
    // editor itself.
    block = modifier((0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.cloneBlock)(block)); // Recurse into nested Blocks

    if (block.innerBlocks && block.innerBlocks.length) {
      block.innerBlocks = mapBlocksRecursively(block.innerBlocks, modifier);
    }

    return block;
  });
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (mapBlocksRecursively);

/***/ }),

/***/ 656:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(736);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);

const _x = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__._x;
const PLACEHOLDER_DEFAULTS = {
  Address: _x('123 Main St', 'default address', "full-site-editing"),
  Phone: _x('555-555-5555', 'default phone number', "full-site-editing"),
  CompanyName: _x('Your Company Name', 'default company name', "full-site-editing"),
  Vertical: _x('Business', 'default vertical name', "full-site-editing")
};
const KEY_MAP = {
  CompanyName: 'title',
  Address: 'address',
  Phone: 'phone',
  Vertical: 'vertical'
};

const replacePlaceholders = function (pageContent) {
  let siteInformation = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

  if (!pageContent) {
    return '';
  }

  return pageContent.replace(/{{(\w+)}}/g, (_match, placeholder) => {
    const defaultValue = isObjKey(placeholder, PLACEHOLDER_DEFAULTS) ? PLACEHOLDER_DEFAULTS[placeholder] : '';
    const key = isObjKey(placeholder, KEY_MAP) ? KEY_MAP[placeholder] : '';
    return siteInformation[key] || defaultValue || placeholder;
  });
}; // eslint-disable-next-line @typescript-eslint/no-explicit-any


function isObjKey(key, obj) {
  return key in obj;
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (replacePlaceholders);

/***/ }),

/***/ 884:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (/* binding */ initializeWithIdentity),
/* harmony export */   "Fk": () => (/* binding */ trackView),
/* harmony export */   "GI": () => (/* binding */ trackDismiss),
/* harmony export */   "E2": () => (/* binding */ trackSelection)
/* harmony export */ });
// Ensure Tracks Library
window._tkq = window._tkq || [];
let tracksIdentity = null;
/**
 * Populate `identity` on WPCOM and ATOMIC to enable tracking.
 * Always disabled for regular self-hosted installations.
 *
 * @param identity Info about identity.
 */

const initializeWithIdentity = identity => {
  tracksIdentity = identity;

  window._tkq.push(['identifyUser', identity.userid, identity.username]);
};
/**
 * Track a view of the layout selector.
 *
 * @param source Source triggering the view.
 */

const trackView = source => {
  if (!tracksIdentity) {
    return;
  }

  window._tkq.push(['recordEvent', 'a8c_full_site_editing_template_selector_view', {
    blog_id: tracksIdentity.blogid,
    source
  }]);
};
/**
 * Track closing of the layout selector.
 */

const trackDismiss = () => {
  if (!tracksIdentity) {
    return;
  }

  window._tkq.push(['recordEvent', 'a8c_full_site_editing_template_selector_dismiss', {
    blog_id: tracksIdentity.blogid
  }]);
};
/**
 * Track layout selection.
 *
 * @param pattern Pattern slug.
 */

const trackSelection = pattern => {
  if (!tracksIdentity) {
    return;
  }

  window._tkq.push(['recordEvent', 'a8c_full_site_editing_template_selector_template_selected', {
    blog_id: tracksIdentity.blogid,
    pattern
  }]);
};

/***/ }),

/***/ 819:
/***/ ((module) => {

"use strict";
module.exports = window["lodash"];

/***/ }),

/***/ 175:
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ 981:
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["blocks"];

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

/***/ 307:
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["element"];

/***/ }),

/***/ 694:
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["hooks"];

/***/ }),

/***/ 736:
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["i18n"];

/***/ }),

/***/ 462:
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["nux"];

/***/ }),

/***/ 817:
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["plugins"];

/***/ }),

/***/ 666:
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (/* binding */ _defineProperty)
/* harmony export */ });
/* harmony import */ var _toPropertyKey_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(795);

function _defineProperty(obj, key, value) {
  key = (0,_toPropertyKey_js__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z)(key);
  if (key in obj) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
  } else {
    obj[key] = value;
  }
  return obj;
}

/***/ }),

/***/ 896:
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (/* binding */ _extends)
/* harmony export */ });
function _extends() {
  _extends = Object.assign ? Object.assign.bind() : function (target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = arguments[i];
      for (var key in source) {
        if (Object.prototype.hasOwnProperty.call(source, key)) {
          target[key] = source[key];
        }
      }
    }
    return target;
  };
  return _extends.apply(this, arguments);
}

/***/ }),

/***/ 745:
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (/* binding */ _toPrimitive)
/* harmony export */ });
/* harmony import */ var _typeof_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(522);

function _toPrimitive(input, hint) {
  if ((0,_typeof_js__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z)(input) !== "object" || input === null) return input;
  var prim = input[Symbol.toPrimitive];
  if (prim !== undefined) {
    var res = prim.call(input, hint || "default");
    if ((0,_typeof_js__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z)(res) !== "object") return res;
    throw new TypeError("@@toPrimitive must return a primitive value.");
  }
  return (hint === "string" ? String : Number)(input);
}

/***/ }),

/***/ 795:
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (/* binding */ _toPropertyKey)
/* harmony export */ });
/* harmony import */ var _typeof_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(522);
/* harmony import */ var _toPrimitive_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(745);


function _toPropertyKey(arg) {
  var key = (0,_toPrimitive_js__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z)(arg, "string");
  return (0,_typeof_js__WEBPACK_IMPORTED_MODULE_1__/* ["default"] */ .Z)(key) === "symbol" ? key : String(key);
}

/***/ }),

/***/ 522:
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (/* binding */ _typeof)
/* harmony export */ });
function _typeof(obj) {
  "@babel/helpers - typeof";

  return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) {
    return typeof obj;
  } : function (obj) {
    return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
  }, _typeof(obj);
}

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
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(307);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _automattic_page_pattern_modal__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(884);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(818);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(817);
/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_plugins__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _page_patterns_plugin__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(28);
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(168);
/* harmony import */ var _index_scss__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(334);







// Load config passed from backend.
const {
  templates: patterns = [],
  tracksUserData,
  screenAction
} = window.starterPageTemplatesConfig ?? {};

if (tracksUserData) {
  (0,_automattic_page_pattern_modal__WEBPACK_IMPORTED_MODULE_6__/* .initializeWithIdentity */ .Z)(tracksUserData);
} // Open plugin only if we are creating new page.


if (screenAction === 'add') {
  (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_1__.dispatch)(_store__WEBPACK_IMPORTED_MODULE_4__/* .pageLayoutStore */ .I).setOpenState('OPEN_FROM_ADD_PAGE');
} // Always register ability to open from document sidebar.


(0,_wordpress_plugins__WEBPACK_IMPORTED_MODULE_2__.registerPlugin)('page-patterns', {
  render: () => {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_page_patterns_plugin__WEBPACK_IMPORTED_MODULE_3__/* .PagePatternsPlugin */ .P, {
      patterns: patterns
    });
  },
  // `registerPlugin()` types assume `icon` is mandatory however it isn't
  // actually required.
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  icon: undefined
});
})();

window.EditingToolkit = __webpack_exports__;
/******/ })()
;