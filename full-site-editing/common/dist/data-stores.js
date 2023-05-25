/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ 7266:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (/* binding */ compile)
/* harmony export */ });
/* harmony import */ var _tannin_postfix__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(5854);
/* harmony import */ var _tannin_evaluate__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(730);



/**
 * Given a C expression, returns a function which can be called to evaluate its
 * result.
 *
 * @example
 *
 * ```js
 * import compile from '@tannin/compile';
 *
 * const evaluate = compile( 'n > 1' );
 *
 * evaluate( { n: 2 } );
 * // ⇒ true
 * ```
 *
 * @param {string} expression C expression.
 *
 * @return {(variables?:{[variable:string]:*})=>*} Compiled evaluator.
 */
function compile( expression ) {
	var terms = (0,_tannin_postfix__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z)( expression );

	return function( variables ) {
		return (0,_tannin_evaluate__WEBPACK_IMPORTED_MODULE_1__/* ["default"] */ .Z)( terms, variables );
	};
}


/***/ }),

/***/ 730:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (/* binding */ evaluate)
/* harmony export */ });
/**
 * Operator callback functions.
 *
 * @type {Object}
 */
var OPERATORS = {
	'!': function( a ) {
		return ! a;
	},
	'*': function( a, b ) {
		return a * b;
	},
	'/': function( a, b ) {
		return a / b;
	},
	'%': function( a, b ) {
		return a % b;
	},
	'+': function( a, b ) {
		return a + b;
	},
	'-': function( a, b ) {
		return a - b;
	},
	'<': function( a, b ) {
		return a < b;
	},
	'<=': function( a, b ) {
		return a <= b;
	},
	'>': function( a, b ) {
		return a > b;
	},
	'>=': function( a, b ) {
		return a >= b;
	},
	'==': function( a, b ) {
		return a === b;
	},
	'!=': function( a, b ) {
		return a !== b;
	},
	'&&': function( a, b ) {
		return a && b;
	},
	'||': function( a, b ) {
		return a || b;
	},
	'?:': function( a, b, c ) {
		if ( a ) {
			throw b;
		}

		return c;
	},
};

/**
 * Given an array of postfix terms and operand variables, returns the result of
 * the postfix evaluation.
 *
 * @example
 *
 * ```js
 * import evaluate from '@tannin/evaluate';
 *
 * // 3 + 4 * 5 / 6 ⇒ '3 4 5 * 6 / +'
 * const terms = [ '3', '4', '5', '*', '6', '/', '+' ];
 *
 * evaluate( terms, {} );
 * // ⇒ 6.333333333333334
 * ```
 *
 * @param {string[]} postfix   Postfix terms.
 * @param {Object}   variables Operand variables.
 *
 * @return {*} Result of evaluation.
 */
function evaluate( postfix, variables ) {
	var stack = [],
		i, j, args, getOperatorResult, term, value;

	for ( i = 0; i < postfix.length; i++ ) {
		term = postfix[ i ];

		getOperatorResult = OPERATORS[ term ];
		if ( getOperatorResult ) {
			// Pop from stack by number of function arguments.
			j = getOperatorResult.length;
			args = Array( j );
			while ( j-- ) {
				args[ j ] = stack.pop();
			}

			try {
				value = getOperatorResult.apply( null, args );
			} catch ( earlyReturn ) {
				return earlyReturn;
			}
		} else if ( variables.hasOwnProperty( term ) ) {
			value = variables[ term ];
		} else {
			value = +term;
		}

		stack.push( value );
	}

	return stack[ 0 ];
}


/***/ }),

/***/ 1184:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (/* binding */ pluralForms)
/* harmony export */ });
/* harmony import */ var _tannin_compile__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(7266);


/**
 * Given a C expression, returns a function which, when called with a value,
 * evaluates the result with the value assumed to be the "n" variable of the
 * expression. The result will be coerced to its numeric equivalent.
 *
 * @param {string} expression C expression.
 *
 * @return {Function} Evaluator function.
 */
function pluralForms( expression ) {
	var evaluate = (0,_tannin_compile__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z)( expression );

	return function( n ) {
		return +evaluate( { n: n } );
	};
}


/***/ }),

/***/ 5854:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (/* binding */ postfix)
/* harmony export */ });
var PRECEDENCE, OPENERS, TERMINATORS, PATTERN;

/**
 * Operator precedence mapping.
 *
 * @type {Object}
 */
PRECEDENCE = {
	'(': 9,
	'!': 8,
	'*': 7,
	'/': 7,
	'%': 7,
	'+': 6,
	'-': 6,
	'<': 5,
	'<=': 5,
	'>': 5,
	'>=': 5,
	'==': 4,
	'!=': 4,
	'&&': 3,
	'||': 2,
	'?': 1,
	'?:': 1,
};

/**
 * Characters which signal pair opening, to be terminated by terminators.
 *
 * @type {string[]}
 */
OPENERS = [ '(', '?' ];

/**
 * Characters which signal pair termination, the value an array with the
 * opener as its first member. The second member is an optional operator
 * replacement to push to the stack.
 *
 * @type {string[]}
 */
TERMINATORS = {
	')': [ '(' ],
	':': [ '?', '?:' ],
};

/**
 * Pattern matching operators and openers.
 *
 * @type {RegExp}
 */
PATTERN = /<=|>=|==|!=|&&|\|\||\?:|\(|!|\*|\/|%|\+|-|<|>|\?|\)|:/;

/**
 * Given a C expression, returns the equivalent postfix (Reverse Polish)
 * notation terms as an array.
 *
 * If a postfix string is desired, simply `.join( ' ' )` the result.
 *
 * @example
 *
 * ```js
 * import postfix from '@tannin/postfix';
 *
 * postfix( 'n > 1' );
 * // ⇒ [ 'n', '1', '>' ]
 * ```
 *
 * @param {string} expression C expression.
 *
 * @return {string[]} Postfix terms.
 */
function postfix( expression ) {
	var terms = [],
		stack = [],
		match, operator, term, element;

	while ( ( match = expression.match( PATTERN ) ) ) {
		operator = match[ 0 ];

		// Term is the string preceding the operator match. It may contain
		// whitespace, and may be empty (if operator is at beginning).
		term = expression.substr( 0, match.index ).trim();
		if ( term ) {
			terms.push( term );
		}

		while ( ( element = stack.pop() ) ) {
			if ( TERMINATORS[ operator ] ) {
				if ( TERMINATORS[ operator ][ 0 ] === element ) {
					// Substitution works here under assumption that because
					// the assigned operator will no longer be a terminator, it
					// will be pushed to the stack during the condition below.
					operator = TERMINATORS[ operator ][ 1 ] || operator;
					break;
				}
			} else if ( OPENERS.indexOf( element ) >= 0 || PRECEDENCE[ element ] < PRECEDENCE[ operator ] ) {
				// Push to stack if either an opener or when pop reveals an
				// element of lower precedence.
				stack.push( element );
				break;
			}

			// For each popped from stack, push to terms.
			terms.push( element );
		}

		if ( ! TERMINATORS[ operator ] ) {
			stack.push( operator );
		}

		// Slice matched fragment from expression to continue match.
		expression = expression.substr( match.index + operator.length );
	}

	// Push remainder of operand, if exists, to terms.
	expression = expression.trim();
	if ( expression ) {
		terms.push( expression );
	}

	// Pop remaining items from stack into terms.
	return terms.concat( stack.reverse() );
}


/***/ }),

/***/ 6668:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (/* binding */ sprintf)
/* harmony export */ });
/**
 * Regular expression matching format placeholder syntax.
 *
 * The pattern for matching named arguments is a naive and incomplete matcher
 * against valid JavaScript identifier names.
 *
 * via Mathias Bynens:
 *
 * >An identifier must start with $, _, or any character in the Unicode
 * >categories “Uppercase letter (Lu)”, “Lowercase letter (Ll)”, “Titlecase
 * >letter (Lt)”, “Modifier letter (Lm)”, “Other letter (Lo)”, or “Letter
 * >number (Nl)”.
 * >
 * >The rest of the string can contain the same characters, plus any U+200C zero
 * >width non-joiner characters, U+200D zero width joiner characters, and
 * >characters in the Unicode categories “Non-spacing mark (Mn)”, “Spacing
 * >combining mark (Mc)”, “Decimal digit number (Nd)”, or “Connector
 * >punctuation (Pc)”.
 *
 * If browser support is constrained to those supporting ES2015, this could be
 * made more accurate using the `u` flag:
 *
 * ```
 * /^[$_\p{L}\p{Nl}][$_\p{L}\p{Nl}\u200C\u200D\p{Mn}\p{Mc}\p{Nd}\p{Pc}]*$/u;
 * ```
 *
 * @see http://www.pixelbeat.org/programming/gcc/format_specs.html
 * @see https://mathiasbynens.be/notes/javascript-identifiers#valid-identifier-names
 *
 * @type {RegExp}
 */
var PATTERN = /%(((\d+)\$)|(\(([$_a-zA-Z][$_a-zA-Z0-9]*)\)))?[ +0#-]*\d*(\.(\d+|\*))?(ll|[lhqL])?([cduxXefgsp%])/g;
//               ▲         ▲                    ▲       ▲  ▲            ▲           ▲ type
//               │         │                    │       │  │            └ Length (unsupported)
//               │         │                    │       │  └ Precision / max width
//               │         │                    │       └ Min width (unsupported)
//               │         │                    └ Flags (unsupported)
//               └ Index   └ Name (for named arguments)

/**
 * Given a format string, returns string with arguments interpolatation.
 * Arguments can either be provided directly via function arguments spread, or
 * with an array as the second argument.
 *
 * @see https://en.wikipedia.org/wiki/Printf_format_string
 *
 * @example
 *
 * ```js
 * import sprintf from '@tannin/sprintf';
 *
 * sprintf( 'Hello %s!', 'world' );
 * // ⇒ 'Hello world!'
 * ```
 *
 * @param {string} string printf format string
 * @param {Array}  [args] String arguments.
 *
 * @return {string} Formatted string.
 */
function sprintf( string, args ) {
	var i;

	if ( ! Array.isArray( args ) ) {
		// Construct a copy of arguments from index one, used for replace
		// function placeholder substitution.
		args = new Array( arguments.length - 1 );
		for ( i = 1; i < arguments.length; i++ ) {
			args[ i - 1 ] = arguments[ i ];
		}
	}

	i = 1;

	return string.replace( PATTERN, function() {
		var index, name, precision, type, value;

		index = arguments[ 3 ];
		name = arguments[ 5 ];
		precision = arguments[ 7 ];
		type = arguments[ 9 ];

		// There's no placeholder substitution in the explicit "%", meaning it
		// is not necessary to increment argument index.
		if ( type === '%' ) {
			return '%';
		}

		// Asterisk precision determined by peeking / shifting next argument.
		if ( precision === '*' ) {
			precision = args[ i - 1 ];
			i++;
		}

		if ( name !== undefined ) {
			// If it's a named argument, use name.
			if ( args[ 0 ] && typeof args[ 0 ] === 'object' &&
					args[ 0 ].hasOwnProperty( name ) ) {
				value = args[ 0 ][ name ];
			}
		} else {
			// If not a positional argument, use counter value.
			if ( index === undefined ) {
				index = i;
			}

			i++;

			// Positional argument.
			value = args[ index - 1 ];
		}

		// Parse as type.
		if ( type === 'f' ) {
			value = parseFloat( value ) || 0;
		} else if ( type === 'd' ) {
			value = parseInt( value ) || 0;
		}

		// Apply precision.
		if ( precision !== undefined ) {
			if ( type === 'f' ) {
				value = value.toFixed( precision );
			} else if ( type === 's' ) {
				value = value.substr( 0, precision );
			}
		}

		// To avoid "undefined" concatenation, return empty string if no
		// placeholder substitution can be performed.
		return value !== undefined && value !== null ? value : '';
	} );
}


/***/ }),

/***/ 6744:
/***/ ((module) => {

module.exports = {
  "100": "Continue",
  "101": "Switching Protocols",
  "102": "Processing",
  "200": "OK",
  "201": "Created",
  "202": "Accepted",
  "203": "Non-Authoritative Information",
  "204": "No Content",
  "205": "Reset Content",
  "206": "Partial Content",
  "207": "Multi-Status",
  "208": "Already Reported",
  "226": "IM Used",
  "300": "Multiple Choices",
  "301": "Moved Permanently",
  "302": "Found",
  "303": "See Other",
  "304": "Not Modified",
  "305": "Use Proxy",
  "307": "Temporary Redirect",
  "308": "Permanent Redirect",
  "400": "Bad Request",
  "401": "Unauthorized",
  "402": "Payment Required",
  "403": "Forbidden",
  "404": "Not Found",
  "405": "Method Not Allowed",
  "406": "Not Acceptable",
  "407": "Proxy Authentication Required",
  "408": "Request Timeout",
  "409": "Conflict",
  "410": "Gone",
  "411": "Length Required",
  "412": "Precondition Failed",
  "413": "Payload Too Large",
  "414": "URI Too Long",
  "415": "Unsupported Media Type",
  "416": "Range Not Satisfiable",
  "417": "Expectation Failed",
  "418": "I'm a teapot",
  "421": "Misdirected Request",
  "422": "Unprocessable Entity",
  "423": "Locked",
  "424": "Failed Dependency",
  "425": "Unordered Collection",
  "426": "Upgrade Required",
  "428": "Precondition Required",
  "429": "Too Many Requests",
  "431": "Request Header Fields Too Large",
  "500": "Internal Server Error",
  "501": "Not Implemented",
  "502": "Bad Gateway",
  "503": "Service Unavailable",
  "504": "Gateway Timeout",
  "505": "HTTP Version Not Supported",
  "506": "Variant Also Negotiates",
  "507": "Insufficient Storage",
  "508": "Loop Detected",
  "509": "Bandwidth Limit Exceeded",
  "510": "Not Extended",
  "511": "Network Authentication Required"
}


/***/ }),

/***/ 2680:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var GetIntrinsic = __webpack_require__(7286);

var callBind = __webpack_require__(9429);

var $indexOf = callBind(GetIntrinsic('String.prototype.indexOf'));

module.exports = function callBoundIntrinsic(name, allowMissing) {
	var intrinsic = GetIntrinsic(name, !!allowMissing);
	if (typeof intrinsic === 'function' && $indexOf(name, '.prototype.') > -1) {
		return callBind(intrinsic);
	}
	return intrinsic;
};


/***/ }),

/***/ 9429:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var bind = __webpack_require__(4090);
var GetIntrinsic = __webpack_require__(7286);

var $apply = GetIntrinsic('%Function.prototype.apply%');
var $call = GetIntrinsic('%Function.prototype.call%');
var $reflectApply = GetIntrinsic('%Reflect.apply%', true) || bind.call($call, $apply);

var $gOPD = GetIntrinsic('%Object.getOwnPropertyDescriptor%', true);
var $defineProperty = GetIntrinsic('%Object.defineProperty%', true);
var $max = GetIntrinsic('%Math.max%');

if ($defineProperty) {
	try {
		$defineProperty({}, 'a', { value: 1 });
	} catch (e) {
		// IE 8 has a broken defineProperty
		$defineProperty = null;
	}
}

module.exports = function callBind(originalFunction) {
	var func = $reflectApply(bind, $call, arguments);
	if ($gOPD && $defineProperty) {
		var desc = $gOPD(func, 'length');
		if (desc.configurable) {
			// original length, plus the receiver, minus any additional arguments (after the receiver)
			$defineProperty(
				func,
				'length',
				{ value: 1 + $max(0, originalFunction.length - (arguments.length - 1)) }
			);
		}
	}
	return func;
};

var applyBind = function applyBind() {
	return $reflectApply(bind, $apply, arguments);
};

if ($defineProperty) {
	$defineProperty(module.exports, 'apply', { value: applyBind });
} else {
	module.exports.apply = applyBind;
}


/***/ }),

/***/ 3421:
/***/ ((__unused_webpack_module, exports) => {

"use strict";
/*!
 * cookie
 * Copyright(c) 2012-2014 Roman Shtylman
 * Copyright(c) 2015 Douglas Christopher Wilson
 * MIT Licensed
 */



/**
 * Module exports.
 * @public
 */

exports.parse = parse;
exports.serialize = serialize;

/**
 * Module variables.
 * @private
 */

var decode = decodeURIComponent;
var encode = encodeURIComponent;
var pairSplitRegExp = /; */;

/**
 * RegExp to match field-content in RFC 7230 sec 3.2
 *
 * field-content = field-vchar [ 1*( SP / HTAB ) field-vchar ]
 * field-vchar   = VCHAR / obs-text
 * obs-text      = %x80-FF
 */

var fieldContentRegExp = /^[\u0009\u0020-\u007e\u0080-\u00ff]+$/;

/**
 * Parse a cookie header.
 *
 * Parse the given cookie header string into an object
 * The object has the various cookies as keys(names) => values
 *
 * @param {string} str
 * @param {object} [options]
 * @return {object}
 * @public
 */

function parse(str, options) {
  if (typeof str !== 'string') {
    throw new TypeError('argument str must be a string');
  }

  var obj = {}
  var opt = options || {};
  var pairs = str.split(pairSplitRegExp);
  var dec = opt.decode || decode;

  for (var i = 0; i < pairs.length; i++) {
    var pair = pairs[i];
    var eq_idx = pair.indexOf('=');

    // skip things that don't look like key=value
    if (eq_idx < 0) {
      continue;
    }

    var key = pair.substr(0, eq_idx).trim()
    var val = pair.substr(++eq_idx, pair.length).trim();

    // quoted values
    if ('"' == val[0]) {
      val = val.slice(1, -1);
    }

    // only assign once
    if (undefined == obj[key]) {
      obj[key] = tryDecode(val, dec);
    }
  }

  return obj;
}

/**
 * Serialize data into a cookie header.
 *
 * Serialize the a name value pair into a cookie string suitable for
 * http headers. An optional options object specified cookie parameters.
 *
 * serialize('foo', 'bar', { httpOnly: true })
 *   => "foo=bar; httpOnly"
 *
 * @param {string} name
 * @param {string} val
 * @param {object} [options]
 * @return {string}
 * @public
 */

function serialize(name, val, options) {
  var opt = options || {};
  var enc = opt.encode || encode;

  if (typeof enc !== 'function') {
    throw new TypeError('option encode is invalid');
  }

  if (!fieldContentRegExp.test(name)) {
    throw new TypeError('argument name is invalid');
  }

  var value = enc(val);

  if (value && !fieldContentRegExp.test(value)) {
    throw new TypeError('argument val is invalid');
  }

  var str = name + '=' + value;

  if (null != opt.maxAge) {
    var maxAge = opt.maxAge - 0;

    if (isNaN(maxAge) || !isFinite(maxAge)) {
      throw new TypeError('option maxAge is invalid')
    }

    str += '; Max-Age=' + Math.floor(maxAge);
  }

  if (opt.domain) {
    if (!fieldContentRegExp.test(opt.domain)) {
      throw new TypeError('option domain is invalid');
    }

    str += '; Domain=' + opt.domain;
  }

  if (opt.path) {
    if (!fieldContentRegExp.test(opt.path)) {
      throw new TypeError('option path is invalid');
    }

    str += '; Path=' + opt.path;
  }

  if (opt.expires) {
    if (typeof opt.expires.toUTCString !== 'function') {
      throw new TypeError('option expires is invalid');
    }

    str += '; Expires=' + opt.expires.toUTCString();
  }

  if (opt.httpOnly) {
    str += '; HttpOnly';
  }

  if (opt.secure) {
    str += '; Secure';
  }

  if (opt.sameSite) {
    var sameSite = typeof opt.sameSite === 'string'
      ? opt.sameSite.toLowerCase() : opt.sameSite;

    switch (sameSite) {
      case true:
        str += '; SameSite=Strict';
        break;
      case 'lax':
        str += '; SameSite=Lax';
        break;
      case 'strict':
        str += '; SameSite=Strict';
        break;
      case 'none':
        str += '; SameSite=None';
        break;
      default:
        throw new TypeError('option sameSite is invalid');
    }
  }

  return str;
}

/**
 * Try decoding a string using a decoding function.
 *
 * @param {string} str
 * @param {function} decode
 * @private
 */

function tryDecode(str, decode) {
  try {
    return decode(str);
  } catch (e) {
    return str;
  }
}


/***/ }),

/***/ 5881:
/***/ ((module) => {

/**
 * Helpers.
 */

var s = 1000;
var m = s * 60;
var h = m * 60;
var d = h * 24;
var w = d * 7;
var y = d * 365.25;

/**
 * Parse or format the given `val`.
 *
 * Options:
 *
 *  - `long` verbose formatting [false]
 *
 * @param {String|Number} val
 * @param {Object} [options]
 * @throws {Error} throw an error if val is not a non-empty string or a number
 * @return {String|Number}
 * @api public
 */

module.exports = function(val, options) {
  options = options || {};
  var type = typeof val;
  if (type === 'string' && val.length > 0) {
    return parse(val);
  } else if (type === 'number' && isFinite(val)) {
    return options.long ? fmtLong(val) : fmtShort(val);
  }
  throw new Error(
    'val is not a non-empty string or a valid number. val=' +
      JSON.stringify(val)
  );
};

/**
 * Parse the given `str` and return milliseconds.
 *
 * @param {String} str
 * @return {Number}
 * @api private
 */

function parse(str) {
  str = String(str);
  if (str.length > 100) {
    return;
  }
  var match = /^(-?(?:\d+)?\.?\d+) *(milliseconds?|msecs?|ms|seconds?|secs?|s|minutes?|mins?|m|hours?|hrs?|h|days?|d|weeks?|w|years?|yrs?|y)?$/i.exec(
    str
  );
  if (!match) {
    return;
  }
  var n = parseFloat(match[1]);
  var type = (match[2] || 'ms').toLowerCase();
  switch (type) {
    case 'years':
    case 'year':
    case 'yrs':
    case 'yr':
    case 'y':
      return n * y;
    case 'weeks':
    case 'week':
    case 'w':
      return n * w;
    case 'days':
    case 'day':
    case 'd':
      return n * d;
    case 'hours':
    case 'hour':
    case 'hrs':
    case 'hr':
    case 'h':
      return n * h;
    case 'minutes':
    case 'minute':
    case 'mins':
    case 'min':
    case 'm':
      return n * m;
    case 'seconds':
    case 'second':
    case 'secs':
    case 'sec':
    case 's':
      return n * s;
    case 'milliseconds':
    case 'millisecond':
    case 'msecs':
    case 'msec':
    case 'ms':
      return n;
    default:
      return undefined;
  }
}

/**
 * Short format for `ms`.
 *
 * @param {Number} ms
 * @return {String}
 * @api private
 */

function fmtShort(ms) {
  var msAbs = Math.abs(ms);
  if (msAbs >= d) {
    return Math.round(ms / d) + 'd';
  }
  if (msAbs >= h) {
    return Math.round(ms / h) + 'h';
  }
  if (msAbs >= m) {
    return Math.round(ms / m) + 'm';
  }
  if (msAbs >= s) {
    return Math.round(ms / s) + 's';
  }
  return ms + 'ms';
}

/**
 * Long format for `ms`.
 *
 * @param {Number} ms
 * @return {String}
 * @api private
 */

function fmtLong(ms) {
  var msAbs = Math.abs(ms);
  if (msAbs >= d) {
    return plural(ms, msAbs, d, 'day');
  }
  if (msAbs >= h) {
    return plural(ms, msAbs, h, 'hour');
  }
  if (msAbs >= m) {
    return plural(ms, msAbs, m, 'minute');
  }
  if (msAbs >= s) {
    return plural(ms, msAbs, s, 'second');
  }
  return ms + ' ms';
}

/**
 * Pluralization helper.
 */

function plural(ms, msAbs, n, name) {
  var isPlural = msAbs >= n * 1.5;
  return Math.round(ms / n) + ' ' + name + (isPlural ? 's' : '');
}


/***/ }),

/***/ 2699:
/***/ ((module) => {

"use strict";
// Copyright Joyent, Inc. and other Node contributors.
//
// Permission is hereby granted, free of charge, to any person obtaining a
// copy of this software and associated documentation files (the
// "Software"), to deal in the Software without restriction, including
// without limitation the rights to use, copy, modify, merge, publish,
// distribute, sublicense, and/or sell copies of the Software, and to permit
// persons to whom the Software is furnished to do so, subject to the
// following conditions:
//
// The above copyright notice and this permission notice shall be included
// in all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
// OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
// MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN
// NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
// DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
// OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE
// USE OR OTHER DEALINGS IN THE SOFTWARE.



var R = typeof Reflect === 'object' ? Reflect : null
var ReflectApply = R && typeof R.apply === 'function'
  ? R.apply
  : function ReflectApply(target, receiver, args) {
    return Function.prototype.apply.call(target, receiver, args);
  }

var ReflectOwnKeys
if (R && typeof R.ownKeys === 'function') {
  ReflectOwnKeys = R.ownKeys
} else if (Object.getOwnPropertySymbols) {
  ReflectOwnKeys = function ReflectOwnKeys(target) {
    return Object.getOwnPropertyNames(target)
      .concat(Object.getOwnPropertySymbols(target));
  };
} else {
  ReflectOwnKeys = function ReflectOwnKeys(target) {
    return Object.getOwnPropertyNames(target);
  };
}

function ProcessEmitWarning(warning) {
  if (console && console.warn) console.warn(warning);
}

var NumberIsNaN = Number.isNaN || function NumberIsNaN(value) {
  return value !== value;
}

function EventEmitter() {
  EventEmitter.init.call(this);
}
module.exports = EventEmitter;
module.exports.once = once;

// Backwards-compat with node 0.10.x
EventEmitter.EventEmitter = EventEmitter;

EventEmitter.prototype._events = undefined;
EventEmitter.prototype._eventsCount = 0;
EventEmitter.prototype._maxListeners = undefined;

// By default EventEmitters will print a warning if more than 10 listeners are
// added to it. This is a useful default which helps finding memory leaks.
var defaultMaxListeners = 10;

function checkListener(listener) {
  if (typeof listener !== 'function') {
    throw new TypeError('The "listener" argument must be of type Function. Received type ' + typeof listener);
  }
}

Object.defineProperty(EventEmitter, 'defaultMaxListeners', {
  enumerable: true,
  get: function() {
    return defaultMaxListeners;
  },
  set: function(arg) {
    if (typeof arg !== 'number' || arg < 0 || NumberIsNaN(arg)) {
      throw new RangeError('The value of "defaultMaxListeners" is out of range. It must be a non-negative number. Received ' + arg + '.');
    }
    defaultMaxListeners = arg;
  }
});

EventEmitter.init = function() {

  if (this._events === undefined ||
      this._events === Object.getPrototypeOf(this)._events) {
    this._events = Object.create(null);
    this._eventsCount = 0;
  }

  this._maxListeners = this._maxListeners || undefined;
};

// Obviously not all Emitters should be limited to 10. This function allows
// that to be increased. Set to zero for unlimited.
EventEmitter.prototype.setMaxListeners = function setMaxListeners(n) {
  if (typeof n !== 'number' || n < 0 || NumberIsNaN(n)) {
    throw new RangeError('The value of "n" is out of range. It must be a non-negative number. Received ' + n + '.');
  }
  this._maxListeners = n;
  return this;
};

function _getMaxListeners(that) {
  if (that._maxListeners === undefined)
    return EventEmitter.defaultMaxListeners;
  return that._maxListeners;
}

EventEmitter.prototype.getMaxListeners = function getMaxListeners() {
  return _getMaxListeners(this);
};

EventEmitter.prototype.emit = function emit(type) {
  var args = [];
  for (var i = 1; i < arguments.length; i++) args.push(arguments[i]);
  var doError = (type === 'error');

  var events = this._events;
  if (events !== undefined)
    doError = (doError && events.error === undefined);
  else if (!doError)
    return false;

  // If there is no 'error' event listener then throw.
  if (doError) {
    var er;
    if (args.length > 0)
      er = args[0];
    if (er instanceof Error) {
      // Note: The comments on the `throw` lines are intentional, they show
      // up in Node's output if this results in an unhandled exception.
      throw er; // Unhandled 'error' event
    }
    // At least give some kind of context to the user
    var err = new Error('Unhandled error.' + (er ? ' (' + er.message + ')' : ''));
    err.context = er;
    throw err; // Unhandled 'error' event
  }

  var handler = events[type];

  if (handler === undefined)
    return false;

  if (typeof handler === 'function') {
    ReflectApply(handler, this, args);
  } else {
    var len = handler.length;
    var listeners = arrayClone(handler, len);
    for (var i = 0; i < len; ++i)
      ReflectApply(listeners[i], this, args);
  }

  return true;
};

function _addListener(target, type, listener, prepend) {
  var m;
  var events;
  var existing;

  checkListener(listener);

  events = target._events;
  if (events === undefined) {
    events = target._events = Object.create(null);
    target._eventsCount = 0;
  } else {
    // To avoid recursion in the case that type === "newListener"! Before
    // adding it to the listeners, first emit "newListener".
    if (events.newListener !== undefined) {
      target.emit('newListener', type,
                  listener.listener ? listener.listener : listener);

      // Re-assign `events` because a newListener handler could have caused the
      // this._events to be assigned to a new object
      events = target._events;
    }
    existing = events[type];
  }

  if (existing === undefined) {
    // Optimize the case of one listener. Don't need the extra array object.
    existing = events[type] = listener;
    ++target._eventsCount;
  } else {
    if (typeof existing === 'function') {
      // Adding the second element, need to change to array.
      existing = events[type] =
        prepend ? [listener, existing] : [existing, listener];
      // If we've already got an array, just append.
    } else if (prepend) {
      existing.unshift(listener);
    } else {
      existing.push(listener);
    }

    // Check for listener leak
    m = _getMaxListeners(target);
    if (m > 0 && existing.length > m && !existing.warned) {
      existing.warned = true;
      // No error code for this since it is a Warning
      // eslint-disable-next-line no-restricted-syntax
      var w = new Error('Possible EventEmitter memory leak detected. ' +
                          existing.length + ' ' + String(type) + ' listeners ' +
                          'added. Use emitter.setMaxListeners() to ' +
                          'increase limit');
      w.name = 'MaxListenersExceededWarning';
      w.emitter = target;
      w.type = type;
      w.count = existing.length;
      ProcessEmitWarning(w);
    }
  }

  return target;
}

EventEmitter.prototype.addListener = function addListener(type, listener) {
  return _addListener(this, type, listener, false);
};

EventEmitter.prototype.on = EventEmitter.prototype.addListener;

EventEmitter.prototype.prependListener =
    function prependListener(type, listener) {
      return _addListener(this, type, listener, true);
    };

function onceWrapper() {
  if (!this.fired) {
    this.target.removeListener(this.type, this.wrapFn);
    this.fired = true;
    if (arguments.length === 0)
      return this.listener.call(this.target);
    return this.listener.apply(this.target, arguments);
  }
}

function _onceWrap(target, type, listener) {
  var state = { fired: false, wrapFn: undefined, target: target, type: type, listener: listener };
  var wrapped = onceWrapper.bind(state);
  wrapped.listener = listener;
  state.wrapFn = wrapped;
  return wrapped;
}

EventEmitter.prototype.once = function once(type, listener) {
  checkListener(listener);
  this.on(type, _onceWrap(this, type, listener));
  return this;
};

EventEmitter.prototype.prependOnceListener =
    function prependOnceListener(type, listener) {
      checkListener(listener);
      this.prependListener(type, _onceWrap(this, type, listener));
      return this;
    };

// Emits a 'removeListener' event if and only if the listener was removed.
EventEmitter.prototype.removeListener =
    function removeListener(type, listener) {
      var list, events, position, i, originalListener;

      checkListener(listener);

      events = this._events;
      if (events === undefined)
        return this;

      list = events[type];
      if (list === undefined)
        return this;

      if (list === listener || list.listener === listener) {
        if (--this._eventsCount === 0)
          this._events = Object.create(null);
        else {
          delete events[type];
          if (events.removeListener)
            this.emit('removeListener', type, list.listener || listener);
        }
      } else if (typeof list !== 'function') {
        position = -1;

        for (i = list.length - 1; i >= 0; i--) {
          if (list[i] === listener || list[i].listener === listener) {
            originalListener = list[i].listener;
            position = i;
            break;
          }
        }

        if (position < 0)
          return this;

        if (position === 0)
          list.shift();
        else {
          spliceOne(list, position);
        }

        if (list.length === 1)
          events[type] = list[0];

        if (events.removeListener !== undefined)
          this.emit('removeListener', type, originalListener || listener);
      }

      return this;
    };

EventEmitter.prototype.off = EventEmitter.prototype.removeListener;

EventEmitter.prototype.removeAllListeners =
    function removeAllListeners(type) {
      var listeners, events, i;

      events = this._events;
      if (events === undefined)
        return this;

      // not listening for removeListener, no need to emit
      if (events.removeListener === undefined) {
        if (arguments.length === 0) {
          this._events = Object.create(null);
          this._eventsCount = 0;
        } else if (events[type] !== undefined) {
          if (--this._eventsCount === 0)
            this._events = Object.create(null);
          else
            delete events[type];
        }
        return this;
      }

      // emit removeListener for all listeners on all events
      if (arguments.length === 0) {
        var keys = Object.keys(events);
        var key;
        for (i = 0; i < keys.length; ++i) {
          key = keys[i];
          if (key === 'removeListener') continue;
          this.removeAllListeners(key);
        }
        this.removeAllListeners('removeListener');
        this._events = Object.create(null);
        this._eventsCount = 0;
        return this;
      }

      listeners = events[type];

      if (typeof listeners === 'function') {
        this.removeListener(type, listeners);
      } else if (listeners !== undefined) {
        // LIFO order
        for (i = listeners.length - 1; i >= 0; i--) {
          this.removeListener(type, listeners[i]);
        }
      }

      return this;
    };

function _listeners(target, type, unwrap) {
  var events = target._events;

  if (events === undefined)
    return [];

  var evlistener = events[type];
  if (evlistener === undefined)
    return [];

  if (typeof evlistener === 'function')
    return unwrap ? [evlistener.listener || evlistener] : [evlistener];

  return unwrap ?
    unwrapListeners(evlistener) : arrayClone(evlistener, evlistener.length);
}

EventEmitter.prototype.listeners = function listeners(type) {
  return _listeners(this, type, true);
};

EventEmitter.prototype.rawListeners = function rawListeners(type) {
  return _listeners(this, type, false);
};

EventEmitter.listenerCount = function(emitter, type) {
  if (typeof emitter.listenerCount === 'function') {
    return emitter.listenerCount(type);
  } else {
    return listenerCount.call(emitter, type);
  }
};

EventEmitter.prototype.listenerCount = listenerCount;
function listenerCount(type) {
  var events = this._events;

  if (events !== undefined) {
    var evlistener = events[type];

    if (typeof evlistener === 'function') {
      return 1;
    } else if (evlistener !== undefined) {
      return evlistener.length;
    }
  }

  return 0;
}

EventEmitter.prototype.eventNames = function eventNames() {
  return this._eventsCount > 0 ? ReflectOwnKeys(this._events) : [];
};

function arrayClone(arr, n) {
  var copy = new Array(n);
  for (var i = 0; i < n; ++i)
    copy[i] = arr[i];
  return copy;
}

function spliceOne(list, index) {
  for (; index + 1 < list.length; index++)
    list[index] = list[index + 1];
  list.pop();
}

function unwrapListeners(arr) {
  var ret = new Array(arr.length);
  for (var i = 0; i < ret.length; ++i) {
    ret[i] = arr[i].listener || arr[i];
  }
  return ret;
}

function once(emitter, name) {
  return new Promise(function (resolve, reject) {
    function errorListener(err) {
      emitter.removeListener(name, resolver);
      reject(err);
    }

    function resolver() {
      if (typeof emitter.removeListener === 'function') {
        emitter.removeListener('error', errorListener);
      }
      resolve([].slice.call(arguments));
    };

    eventTargetAgnosticAddListener(emitter, name, resolver, { once: true });
    if (name !== 'error') {
      addErrorHandlerIfEventEmitter(emitter, errorListener, { once: true });
    }
  });
}

function addErrorHandlerIfEventEmitter(emitter, handler, flags) {
  if (typeof emitter.on === 'function') {
    eventTargetAgnosticAddListener(emitter, 'error', handler, flags);
  }
}

function eventTargetAgnosticAddListener(emitter, name, listener, flags) {
  if (typeof emitter.on === 'function') {
    if (flags.once) {
      emitter.once(name, listener);
    } else {
      emitter.on(name, listener);
    }
  } else if (typeof emitter.addEventListener === 'function') {
    // EventTarget does not have `error` event semantics like Node
    // EventEmitters, we do not listen for `error` events here.
    emitter.addEventListener(name, function wrapListener(arg) {
      // IE does not have builtin `{ once: true }` support so we
      // have to do it manually.
      if (flags.once) {
        emitter.removeEventListener(name, wrapListener);
      }
      listener(arg);
    });
  } else {
    throw new TypeError('The "emitter" argument must be of type EventEmitter. Received type ' + typeof emitter);
  }
}


/***/ }),

/***/ 5695:
/***/ ((module) => {

"use strict";


module.exports = function (data, opts) {
    if (!opts) opts = {};
    if (typeof opts === 'function') opts = { cmp: opts };
    var cycles = (typeof opts.cycles === 'boolean') ? opts.cycles : false;

    var cmp = opts.cmp && (function (f) {
        return function (node) {
            return function (a, b) {
                var aobj = { key: a, value: node[a] };
                var bobj = { key: b, value: node[b] };
                return f(aobj, bobj);
            };
        };
    })(opts.cmp);

    var seen = [];
    return (function stringify (node) {
        if (node && node.toJSON && typeof node.toJSON === 'function') {
            node = node.toJSON();
        }

        if (node === undefined) return;
        if (typeof node == 'number') return isFinite(node) ? '' + node : 'null';
        if (typeof node !== 'object') return JSON.stringify(node);

        var i, out;
        if (Array.isArray(node)) {
            out = '[';
            for (i = 0; i < node.length; i++) {
                if (i) out += ',';
                out += stringify(node[i]) || 'null';
            }
            return out + ']';
        }

        if (node === null) return 'null';

        if (seen.indexOf(node) !== -1) {
            if (cycles) return JSON.stringify('__cycle__');
            throw new TypeError('Converting circular structure to JSON');
        }

        var seenIndex = seen.push(node) - 1;
        var keys = Object.keys(node).sort(cmp && cmp(node));
        out = '';
        for (i = 0; i < keys.length; i++) {
            var key = keys[i];
            var value = stringify(node[key]);

            if (!value) continue;
            if (out) out += ',';
            out += JSON.stringify(key) + ':' + value;
        }
        seen.splice(seenIndex, 1);
        return '{' + out + '}';
    })(data);
};


/***/ }),

/***/ 7795:
/***/ ((module) => {

"use strict";


/* eslint no-invalid-this: 1 */

var ERROR_MESSAGE = 'Function.prototype.bind called on incompatible ';
var slice = Array.prototype.slice;
var toStr = Object.prototype.toString;
var funcType = '[object Function]';

module.exports = function bind(that) {
    var target = this;
    if (typeof target !== 'function' || toStr.call(target) !== funcType) {
        throw new TypeError(ERROR_MESSAGE + target);
    }
    var args = slice.call(arguments, 1);

    var bound;
    var binder = function () {
        if (this instanceof bound) {
            var result = target.apply(
                this,
                args.concat(slice.call(arguments))
            );
            if (Object(result) === result) {
                return result;
            }
            return this;
        } else {
            return target.apply(
                that,
                args.concat(slice.call(arguments))
            );
        }
    };

    var boundLength = Math.max(0, target.length - args.length);
    var boundArgs = [];
    for (var i = 0; i < boundLength; i++) {
        boundArgs.push('$' + i);
    }

    bound = Function('binder', 'return function (' + boundArgs.join(',') + '){ return binder.apply(this,arguments); }')(binder);

    if (target.prototype) {
        var Empty = function Empty() {};
        Empty.prototype = target.prototype;
        bound.prototype = new Empty();
        Empty.prototype = null;
    }

    return bound;
};


/***/ }),

/***/ 4090:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var implementation = __webpack_require__(7795);

module.exports = Function.prototype.bind || implementation;


/***/ }),

/***/ 7286:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var undefined;

var $SyntaxError = SyntaxError;
var $Function = Function;
var $TypeError = TypeError;

// eslint-disable-next-line consistent-return
var getEvalledConstructor = function (expressionSyntax) {
	try {
		return $Function('"use strict"; return (' + expressionSyntax + ').constructor;')();
	} catch (e) {}
};

var $gOPD = Object.getOwnPropertyDescriptor;
if ($gOPD) {
	try {
		$gOPD({}, '');
	} catch (e) {
		$gOPD = null; // this is IE 8, which has a broken gOPD
	}
}

var throwTypeError = function () {
	throw new $TypeError();
};
var ThrowTypeError = $gOPD
	? (function () {
		try {
			// eslint-disable-next-line no-unused-expressions, no-caller, no-restricted-properties
			arguments.callee; // IE 8 does not throw here
			return throwTypeError;
		} catch (calleeThrows) {
			try {
				// IE 8 throws on Object.getOwnPropertyDescriptor(arguments, '')
				return $gOPD(arguments, 'callee').get;
			} catch (gOPDthrows) {
				return throwTypeError;
			}
		}
	}())
	: throwTypeError;

var hasSymbols = __webpack_require__(2636)();

var getProto = Object.getPrototypeOf || function (x) { return x.__proto__; }; // eslint-disable-line no-proto

var needsEval = {};

var TypedArray = typeof Uint8Array === 'undefined' ? undefined : getProto(Uint8Array);

var INTRINSICS = {
	'%AggregateError%': typeof AggregateError === 'undefined' ? undefined : AggregateError,
	'%Array%': Array,
	'%ArrayBuffer%': typeof ArrayBuffer === 'undefined' ? undefined : ArrayBuffer,
	'%ArrayIteratorPrototype%': hasSymbols ? getProto([][Symbol.iterator]()) : undefined,
	'%AsyncFromSyncIteratorPrototype%': undefined,
	'%AsyncFunction%': needsEval,
	'%AsyncGenerator%': needsEval,
	'%AsyncGeneratorFunction%': needsEval,
	'%AsyncIteratorPrototype%': needsEval,
	'%Atomics%': typeof Atomics === 'undefined' ? undefined : Atomics,
	'%BigInt%': typeof BigInt === 'undefined' ? undefined : BigInt,
	'%BigInt64Array%': typeof BigInt64Array === 'undefined' ? undefined : BigInt64Array,
	'%BigUint64Array%': typeof BigUint64Array === 'undefined' ? undefined : BigUint64Array,
	'%Boolean%': Boolean,
	'%DataView%': typeof DataView === 'undefined' ? undefined : DataView,
	'%Date%': Date,
	'%decodeURI%': decodeURI,
	'%decodeURIComponent%': decodeURIComponent,
	'%encodeURI%': encodeURI,
	'%encodeURIComponent%': encodeURIComponent,
	'%Error%': Error,
	'%eval%': eval, // eslint-disable-line no-eval
	'%EvalError%': EvalError,
	'%Float32Array%': typeof Float32Array === 'undefined' ? undefined : Float32Array,
	'%Float64Array%': typeof Float64Array === 'undefined' ? undefined : Float64Array,
	'%FinalizationRegistry%': typeof FinalizationRegistry === 'undefined' ? undefined : FinalizationRegistry,
	'%Function%': $Function,
	'%GeneratorFunction%': needsEval,
	'%Int8Array%': typeof Int8Array === 'undefined' ? undefined : Int8Array,
	'%Int16Array%': typeof Int16Array === 'undefined' ? undefined : Int16Array,
	'%Int32Array%': typeof Int32Array === 'undefined' ? undefined : Int32Array,
	'%isFinite%': isFinite,
	'%isNaN%': isNaN,
	'%IteratorPrototype%': hasSymbols ? getProto(getProto([][Symbol.iterator]())) : undefined,
	'%JSON%': typeof JSON === 'object' ? JSON : undefined,
	'%Map%': typeof Map === 'undefined' ? undefined : Map,
	'%MapIteratorPrototype%': typeof Map === 'undefined' || !hasSymbols ? undefined : getProto(new Map()[Symbol.iterator]()),
	'%Math%': Math,
	'%Number%': Number,
	'%Object%': Object,
	'%parseFloat%': parseFloat,
	'%parseInt%': parseInt,
	'%Promise%': typeof Promise === 'undefined' ? undefined : Promise,
	'%Proxy%': typeof Proxy === 'undefined' ? undefined : Proxy,
	'%RangeError%': RangeError,
	'%ReferenceError%': ReferenceError,
	'%Reflect%': typeof Reflect === 'undefined' ? undefined : Reflect,
	'%RegExp%': RegExp,
	'%Set%': typeof Set === 'undefined' ? undefined : Set,
	'%SetIteratorPrototype%': typeof Set === 'undefined' || !hasSymbols ? undefined : getProto(new Set()[Symbol.iterator]()),
	'%SharedArrayBuffer%': typeof SharedArrayBuffer === 'undefined' ? undefined : SharedArrayBuffer,
	'%String%': String,
	'%StringIteratorPrototype%': hasSymbols ? getProto(''[Symbol.iterator]()) : undefined,
	'%Symbol%': hasSymbols ? Symbol : undefined,
	'%SyntaxError%': $SyntaxError,
	'%ThrowTypeError%': ThrowTypeError,
	'%TypedArray%': TypedArray,
	'%TypeError%': $TypeError,
	'%Uint8Array%': typeof Uint8Array === 'undefined' ? undefined : Uint8Array,
	'%Uint8ClampedArray%': typeof Uint8ClampedArray === 'undefined' ? undefined : Uint8ClampedArray,
	'%Uint16Array%': typeof Uint16Array === 'undefined' ? undefined : Uint16Array,
	'%Uint32Array%': typeof Uint32Array === 'undefined' ? undefined : Uint32Array,
	'%URIError%': URIError,
	'%WeakMap%': typeof WeakMap === 'undefined' ? undefined : WeakMap,
	'%WeakRef%': typeof WeakRef === 'undefined' ? undefined : WeakRef,
	'%WeakSet%': typeof WeakSet === 'undefined' ? undefined : WeakSet
};

try {
	null.error; // eslint-disable-line no-unused-expressions
} catch (e) {
	// https://github.com/tc39/proposal-shadowrealm/pull/384#issuecomment-1364264229
	var errorProto = getProto(getProto(e));
	INTRINSICS['%Error.prototype%'] = errorProto;
}

var doEval = function doEval(name) {
	var value;
	if (name === '%AsyncFunction%') {
		value = getEvalledConstructor('async function () {}');
	} else if (name === '%GeneratorFunction%') {
		value = getEvalledConstructor('function* () {}');
	} else if (name === '%AsyncGeneratorFunction%') {
		value = getEvalledConstructor('async function* () {}');
	} else if (name === '%AsyncGenerator%') {
		var fn = doEval('%AsyncGeneratorFunction%');
		if (fn) {
			value = fn.prototype;
		}
	} else if (name === '%AsyncIteratorPrototype%') {
		var gen = doEval('%AsyncGenerator%');
		if (gen) {
			value = getProto(gen.prototype);
		}
	}

	INTRINSICS[name] = value;

	return value;
};

var LEGACY_ALIASES = {
	'%ArrayBufferPrototype%': ['ArrayBuffer', 'prototype'],
	'%ArrayPrototype%': ['Array', 'prototype'],
	'%ArrayProto_entries%': ['Array', 'prototype', 'entries'],
	'%ArrayProto_forEach%': ['Array', 'prototype', 'forEach'],
	'%ArrayProto_keys%': ['Array', 'prototype', 'keys'],
	'%ArrayProto_values%': ['Array', 'prototype', 'values'],
	'%AsyncFunctionPrototype%': ['AsyncFunction', 'prototype'],
	'%AsyncGenerator%': ['AsyncGeneratorFunction', 'prototype'],
	'%AsyncGeneratorPrototype%': ['AsyncGeneratorFunction', 'prototype', 'prototype'],
	'%BooleanPrototype%': ['Boolean', 'prototype'],
	'%DataViewPrototype%': ['DataView', 'prototype'],
	'%DatePrototype%': ['Date', 'prototype'],
	'%ErrorPrototype%': ['Error', 'prototype'],
	'%EvalErrorPrototype%': ['EvalError', 'prototype'],
	'%Float32ArrayPrototype%': ['Float32Array', 'prototype'],
	'%Float64ArrayPrototype%': ['Float64Array', 'prototype'],
	'%FunctionPrototype%': ['Function', 'prototype'],
	'%Generator%': ['GeneratorFunction', 'prototype'],
	'%GeneratorPrototype%': ['GeneratorFunction', 'prototype', 'prototype'],
	'%Int8ArrayPrototype%': ['Int8Array', 'prototype'],
	'%Int16ArrayPrototype%': ['Int16Array', 'prototype'],
	'%Int32ArrayPrototype%': ['Int32Array', 'prototype'],
	'%JSONParse%': ['JSON', 'parse'],
	'%JSONStringify%': ['JSON', 'stringify'],
	'%MapPrototype%': ['Map', 'prototype'],
	'%NumberPrototype%': ['Number', 'prototype'],
	'%ObjectPrototype%': ['Object', 'prototype'],
	'%ObjProto_toString%': ['Object', 'prototype', 'toString'],
	'%ObjProto_valueOf%': ['Object', 'prototype', 'valueOf'],
	'%PromisePrototype%': ['Promise', 'prototype'],
	'%PromiseProto_then%': ['Promise', 'prototype', 'then'],
	'%Promise_all%': ['Promise', 'all'],
	'%Promise_reject%': ['Promise', 'reject'],
	'%Promise_resolve%': ['Promise', 'resolve'],
	'%RangeErrorPrototype%': ['RangeError', 'prototype'],
	'%ReferenceErrorPrototype%': ['ReferenceError', 'prototype'],
	'%RegExpPrototype%': ['RegExp', 'prototype'],
	'%SetPrototype%': ['Set', 'prototype'],
	'%SharedArrayBufferPrototype%': ['SharedArrayBuffer', 'prototype'],
	'%StringPrototype%': ['String', 'prototype'],
	'%SymbolPrototype%': ['Symbol', 'prototype'],
	'%SyntaxErrorPrototype%': ['SyntaxError', 'prototype'],
	'%TypedArrayPrototype%': ['TypedArray', 'prototype'],
	'%TypeErrorPrototype%': ['TypeError', 'prototype'],
	'%Uint8ArrayPrototype%': ['Uint8Array', 'prototype'],
	'%Uint8ClampedArrayPrototype%': ['Uint8ClampedArray', 'prototype'],
	'%Uint16ArrayPrototype%': ['Uint16Array', 'prototype'],
	'%Uint32ArrayPrototype%': ['Uint32Array', 'prototype'],
	'%URIErrorPrototype%': ['URIError', 'prototype'],
	'%WeakMapPrototype%': ['WeakMap', 'prototype'],
	'%WeakSetPrototype%': ['WeakSet', 'prototype']
};

var bind = __webpack_require__(4090);
var hasOwn = __webpack_require__(3198);
var $concat = bind.call(Function.call, Array.prototype.concat);
var $spliceApply = bind.call(Function.apply, Array.prototype.splice);
var $replace = bind.call(Function.call, String.prototype.replace);
var $strSlice = bind.call(Function.call, String.prototype.slice);
var $exec = bind.call(Function.call, RegExp.prototype.exec);

/* adapted from https://github.com/lodash/lodash/blob/4.17.15/dist/lodash.js#L6735-L6744 */
var rePropName = /[^%.[\]]+|\[(?:(-?\d+(?:\.\d+)?)|(["'])((?:(?!\2)[^\\]|\\.)*?)\2)\]|(?=(?:\.|\[\])(?:\.|\[\]|%$))/g;
var reEscapeChar = /\\(\\)?/g; /** Used to match backslashes in property paths. */
var stringToPath = function stringToPath(string) {
	var first = $strSlice(string, 0, 1);
	var last = $strSlice(string, -1);
	if (first === '%' && last !== '%') {
		throw new $SyntaxError('invalid intrinsic syntax, expected closing `%`');
	} else if (last === '%' && first !== '%') {
		throw new $SyntaxError('invalid intrinsic syntax, expected opening `%`');
	}
	var result = [];
	$replace(string, rePropName, function (match, number, quote, subString) {
		result[result.length] = quote ? $replace(subString, reEscapeChar, '$1') : number || match;
	});
	return result;
};
/* end adaptation */

var getBaseIntrinsic = function getBaseIntrinsic(name, allowMissing) {
	var intrinsicName = name;
	var alias;
	if (hasOwn(LEGACY_ALIASES, intrinsicName)) {
		alias = LEGACY_ALIASES[intrinsicName];
		intrinsicName = '%' + alias[0] + '%';
	}

	if (hasOwn(INTRINSICS, intrinsicName)) {
		var value = INTRINSICS[intrinsicName];
		if (value === needsEval) {
			value = doEval(intrinsicName);
		}
		if (typeof value === 'undefined' && !allowMissing) {
			throw new $TypeError('intrinsic ' + name + ' exists, but is not available. Please file an issue!');
		}

		return {
			alias: alias,
			name: intrinsicName,
			value: value
		};
	}

	throw new $SyntaxError('intrinsic ' + name + ' does not exist!');
};

module.exports = function GetIntrinsic(name, allowMissing) {
	if (typeof name !== 'string' || name.length === 0) {
		throw new $TypeError('intrinsic name must be a non-empty string');
	}
	if (arguments.length > 1 && typeof allowMissing !== 'boolean') {
		throw new $TypeError('"allowMissing" argument must be a boolean');
	}

	if ($exec(/^%?[^%]*%?$/, name) === null) {
		throw new $SyntaxError('`%` may not be present anywhere but at the beginning and end of the intrinsic name');
	}
	var parts = stringToPath(name);
	var intrinsicBaseName = parts.length > 0 ? parts[0] : '';

	var intrinsic = getBaseIntrinsic('%' + intrinsicBaseName + '%', allowMissing);
	var intrinsicRealName = intrinsic.name;
	var value = intrinsic.value;
	var skipFurtherCaching = false;

	var alias = intrinsic.alias;
	if (alias) {
		intrinsicBaseName = alias[0];
		$spliceApply(parts, $concat([0, 1], alias));
	}

	for (var i = 1, isOwn = true; i < parts.length; i += 1) {
		var part = parts[i];
		var first = $strSlice(part, 0, 1);
		var last = $strSlice(part, -1);
		if (
			(
				(first === '"' || first === "'" || first === '`')
				|| (last === '"' || last === "'" || last === '`')
			)
			&& first !== last
		) {
			throw new $SyntaxError('property names with quotes must have matching quotes');
		}
		if (part === 'constructor' || !isOwn) {
			skipFurtherCaching = true;
		}

		intrinsicBaseName += '.' + part;
		intrinsicRealName = '%' + intrinsicBaseName + '%';

		if (hasOwn(INTRINSICS, intrinsicRealName)) {
			value = INTRINSICS[intrinsicRealName];
		} else if (value != null) {
			if (!(part in value)) {
				if (!allowMissing) {
					throw new $TypeError('base intrinsic for ' + name + ' exists, but the property is not available.');
				}
				return void undefined;
			}
			if ($gOPD && (i + 1) >= parts.length) {
				var desc = $gOPD(value, part);
				isOwn = !!desc;

				// By convention, when a data property is converted to an accessor
				// property to emulate a data property that does not suffer from
				// the override mistake, that accessor's getter is marked with
				// an `originalValue` property. Here, when we detect this, we
				// uphold the illusion by pretending to see that original data
				// property, i.e., returning the value rather than the getter
				// itself.
				if (isOwn && 'get' in desc && !('originalValue' in desc.get)) {
					value = desc.get;
				} else {
					value = value[part];
				}
			} else {
				isOwn = hasOwn(value, part);
				value = value[part];
			}

			if (isOwn && !skipFurtherCaching) {
				INTRINSICS[intrinsicRealName] = value;
			}
		}
	}
	return value;
};


/***/ }),

/***/ 2636:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var origSymbol = typeof Symbol !== 'undefined' && Symbol;
var hasSymbolSham = __webpack_require__(6679);

module.exports = function hasNativeSymbols() {
	if (typeof origSymbol !== 'function') { return false; }
	if (typeof Symbol !== 'function') { return false; }
	if (typeof origSymbol('foo') !== 'symbol') { return false; }
	if (typeof Symbol('bar') !== 'symbol') { return false; }

	return hasSymbolSham();
};


/***/ }),

/***/ 6679:
/***/ ((module) => {

"use strict";


/* eslint complexity: [2, 18], max-statements: [2, 33] */
module.exports = function hasSymbols() {
	if (typeof Symbol !== 'function' || typeof Object.getOwnPropertySymbols !== 'function') { return false; }
	if (typeof Symbol.iterator === 'symbol') { return true; }

	var obj = {};
	var sym = Symbol('test');
	var symObj = Object(sym);
	if (typeof sym === 'string') { return false; }

	if (Object.prototype.toString.call(sym) !== '[object Symbol]') { return false; }
	if (Object.prototype.toString.call(symObj) !== '[object Symbol]') { return false; }

	// temp disabled per https://github.com/ljharb/object.assign/issues/17
	// if (sym instanceof Symbol) { return false; }
	// temp disabled per https://github.com/WebReflection/get-own-property-symbols/issues/4
	// if (!(symObj instanceof Symbol)) { return false; }

	// if (typeof Symbol.prototype.toString !== 'function') { return false; }
	// if (String(sym) !== Symbol.prototype.toString.call(sym)) { return false; }

	var symVal = 42;
	obj[sym] = symVal;
	for (sym in obj) { return false; } // eslint-disable-line no-restricted-syntax, no-unreachable-loop
	if (typeof Object.keys === 'function' && Object.keys(obj).length !== 0) { return false; }

	if (typeof Object.getOwnPropertyNames === 'function' && Object.getOwnPropertyNames(obj).length !== 0) { return false; }

	var syms = Object.getOwnPropertySymbols(obj);
	if (syms.length !== 1 || syms[0] !== sym) { return false; }

	if (!Object.prototype.propertyIsEnumerable.call(obj, sym)) { return false; }

	if (typeof Object.getOwnPropertyDescriptor === 'function') {
		var descriptor = Object.getOwnPropertyDescriptor(obj, sym);
		if (descriptor.value !== symVal || descriptor.enumerable !== true) { return false; }
	}

	return true;
};


/***/ }),

/***/ 3198:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var bind = __webpack_require__(4090);

module.exports = bind.call(Function.call, Object.prototype.hasOwnProperty);


/***/ }),

/***/ 4495:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var utils = __webpack_require__(212);
var assert = __webpack_require__(9561);

function BlockHash() {
  this.pending = null;
  this.pendingTotal = 0;
  this.blockSize = this.constructor.blockSize;
  this.outSize = this.constructor.outSize;
  this.hmacStrength = this.constructor.hmacStrength;
  this.padLength = this.constructor.padLength / 8;
  this.endian = 'big';

  this._delta8 = this.blockSize / 8;
  this._delta32 = this.blockSize / 32;
}
exports.BlockHash = BlockHash;

BlockHash.prototype.update = function update(msg, enc) {
  // Convert message to array, pad it, and join into 32bit blocks
  msg = utils.toArray(msg, enc);
  if (!this.pending)
    this.pending = msg;
  else
    this.pending = this.pending.concat(msg);
  this.pendingTotal += msg.length;

  // Enough data, try updating
  if (this.pending.length >= this._delta8) {
    msg = this.pending;

    // Process pending data in blocks
    var r = msg.length % this._delta8;
    this.pending = msg.slice(msg.length - r, msg.length);
    if (this.pending.length === 0)
      this.pending = null;

    msg = utils.join32(msg, 0, msg.length - r, this.endian);
    for (var i = 0; i < msg.length; i += this._delta32)
      this._update(msg, i, i + this._delta32);
  }

  return this;
};

BlockHash.prototype.digest = function digest(enc) {
  this.update(this._pad());
  assert(this.pending === null);

  return this._digest(enc);
};

BlockHash.prototype._pad = function pad() {
  var len = this.pendingTotal;
  var bytes = this._delta8;
  var k = bytes - ((len + this.padLength) % bytes);
  var res = new Array(k + this.padLength);
  res[0] = 0x80;
  for (var i = 1; i < k; i++)
    res[i] = 0;

  // Append length
  len <<= 3;
  if (this.endian === 'big') {
    for (var t = 8; t < this.padLength; t++)
      res[i++] = 0;

    res[i++] = 0;
    res[i++] = 0;
    res[i++] = 0;
    res[i++] = 0;
    res[i++] = (len >>> 24) & 0xff;
    res[i++] = (len >>> 16) & 0xff;
    res[i++] = (len >>> 8) & 0xff;
    res[i++] = len & 0xff;
  } else {
    res[i++] = len & 0xff;
    res[i++] = (len >>> 8) & 0xff;
    res[i++] = (len >>> 16) & 0xff;
    res[i++] = (len >>> 24) & 0xff;
    res[i++] = 0;
    res[i++] = 0;
    res[i++] = 0;
    res[i++] = 0;

    for (t = 8; t < this.padLength; t++)
      res[i++] = 0;
  }

  return res;
};


/***/ }),

/***/ 5079:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var utils = __webpack_require__(212);
var common = __webpack_require__(4495);
var shaCommon = __webpack_require__(713);

var rotl32 = utils.rotl32;
var sum32 = utils.sum32;
var sum32_5 = utils.sum32_5;
var ft_1 = shaCommon.ft_1;
var BlockHash = common.BlockHash;

var sha1_K = [
  0x5A827999, 0x6ED9EBA1,
  0x8F1BBCDC, 0xCA62C1D6
];

function SHA1() {
  if (!(this instanceof SHA1))
    return new SHA1();

  BlockHash.call(this);
  this.h = [
    0x67452301, 0xefcdab89, 0x98badcfe,
    0x10325476, 0xc3d2e1f0 ];
  this.W = new Array(80);
}

utils.inherits(SHA1, BlockHash);
module.exports = SHA1;

SHA1.blockSize = 512;
SHA1.outSize = 160;
SHA1.hmacStrength = 80;
SHA1.padLength = 64;

SHA1.prototype._update = function _update(msg, start) {
  var W = this.W;

  for (var i = 0; i < 16; i++)
    W[i] = msg[start + i];

  for(; i < W.length; i++)
    W[i] = rotl32(W[i - 3] ^ W[i - 8] ^ W[i - 14] ^ W[i - 16], 1);

  var a = this.h[0];
  var b = this.h[1];
  var c = this.h[2];
  var d = this.h[3];
  var e = this.h[4];

  for (i = 0; i < W.length; i++) {
    var s = ~~(i / 20);
    var t = sum32_5(rotl32(a, 5), ft_1(s, b, c, d), e, W[i], sha1_K[s]);
    e = d;
    d = c;
    c = rotl32(b, 30);
    b = a;
    a = t;
  }

  this.h[0] = sum32(this.h[0], a);
  this.h[1] = sum32(this.h[1], b);
  this.h[2] = sum32(this.h[2], c);
  this.h[3] = sum32(this.h[3], d);
  this.h[4] = sum32(this.h[4], e);
};

SHA1.prototype._digest = function digest(enc) {
  if (enc === 'hex')
    return utils.toHex32(this.h, 'big');
  else
    return utils.split32(this.h, 'big');
};


/***/ }),

/***/ 713:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var utils = __webpack_require__(212);
var rotr32 = utils.rotr32;

function ft_1(s, x, y, z) {
  if (s === 0)
    return ch32(x, y, z);
  if (s === 1 || s === 3)
    return p32(x, y, z);
  if (s === 2)
    return maj32(x, y, z);
}
exports.ft_1 = ft_1;

function ch32(x, y, z) {
  return (x & y) ^ ((~x) & z);
}
exports.ch32 = ch32;

function maj32(x, y, z) {
  return (x & y) ^ (x & z) ^ (y & z);
}
exports.maj32 = maj32;

function p32(x, y, z) {
  return x ^ y ^ z;
}
exports.p32 = p32;

function s0_256(x) {
  return rotr32(x, 2) ^ rotr32(x, 13) ^ rotr32(x, 22);
}
exports.s0_256 = s0_256;

function s1_256(x) {
  return rotr32(x, 6) ^ rotr32(x, 11) ^ rotr32(x, 25);
}
exports.s1_256 = s1_256;

function g0_256(x) {
  return rotr32(x, 7) ^ rotr32(x, 18) ^ (x >>> 3);
}
exports.g0_256 = g0_256;

function g1_256(x) {
  return rotr32(x, 17) ^ rotr32(x, 19) ^ (x >>> 10);
}
exports.g1_256 = g1_256;


/***/ }),

/***/ 212:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var assert = __webpack_require__(9561);
var inherits = __webpack_require__(1285);

exports.inherits = inherits;

function isSurrogatePair(msg, i) {
  if ((msg.charCodeAt(i) & 0xFC00) !== 0xD800) {
    return false;
  }
  if (i < 0 || i + 1 >= msg.length) {
    return false;
  }
  return (msg.charCodeAt(i + 1) & 0xFC00) === 0xDC00;
}

function toArray(msg, enc) {
  if (Array.isArray(msg))
    return msg.slice();
  if (!msg)
    return [];
  var res = [];
  if (typeof msg === 'string') {
    if (!enc) {
      // Inspired by stringToUtf8ByteArray() in closure-library by Google
      // https://github.com/google/closure-library/blob/8598d87242af59aac233270742c8984e2b2bdbe0/closure/goog/crypt/crypt.js#L117-L143
      // Apache License 2.0
      // https://github.com/google/closure-library/blob/master/LICENSE
      var p = 0;
      for (var i = 0; i < msg.length; i++) {
        var c = msg.charCodeAt(i);
        if (c < 128) {
          res[p++] = c;
        } else if (c < 2048) {
          res[p++] = (c >> 6) | 192;
          res[p++] = (c & 63) | 128;
        } else if (isSurrogatePair(msg, i)) {
          c = 0x10000 + ((c & 0x03FF) << 10) + (msg.charCodeAt(++i) & 0x03FF);
          res[p++] = (c >> 18) | 240;
          res[p++] = ((c >> 12) & 63) | 128;
          res[p++] = ((c >> 6) & 63) | 128;
          res[p++] = (c & 63) | 128;
        } else {
          res[p++] = (c >> 12) | 224;
          res[p++] = ((c >> 6) & 63) | 128;
          res[p++] = (c & 63) | 128;
        }
      }
    } else if (enc === 'hex') {
      msg = msg.replace(/[^a-z0-9]+/ig, '');
      if (msg.length % 2 !== 0)
        msg = '0' + msg;
      for (i = 0; i < msg.length; i += 2)
        res.push(parseInt(msg[i] + msg[i + 1], 16));
    }
  } else {
    for (i = 0; i < msg.length; i++)
      res[i] = msg[i] | 0;
  }
  return res;
}
exports.toArray = toArray;

function toHex(msg) {
  var res = '';
  for (var i = 0; i < msg.length; i++)
    res += zero2(msg[i].toString(16));
  return res;
}
exports.toHex = toHex;

function htonl(w) {
  var res = (w >>> 24) |
            ((w >>> 8) & 0xff00) |
            ((w << 8) & 0xff0000) |
            ((w & 0xff) << 24);
  return res >>> 0;
}
exports.htonl = htonl;

function toHex32(msg, endian) {
  var res = '';
  for (var i = 0; i < msg.length; i++) {
    var w = msg[i];
    if (endian === 'little')
      w = htonl(w);
    res += zero8(w.toString(16));
  }
  return res;
}
exports.toHex32 = toHex32;

function zero2(word) {
  if (word.length === 1)
    return '0' + word;
  else
    return word;
}
exports.zero2 = zero2;

function zero8(word) {
  if (word.length === 7)
    return '0' + word;
  else if (word.length === 6)
    return '00' + word;
  else if (word.length === 5)
    return '000' + word;
  else if (word.length === 4)
    return '0000' + word;
  else if (word.length === 3)
    return '00000' + word;
  else if (word.length === 2)
    return '000000' + word;
  else if (word.length === 1)
    return '0000000' + word;
  else
    return word;
}
exports.zero8 = zero8;

function join32(msg, start, end, endian) {
  var len = end - start;
  assert(len % 4 === 0);
  var res = new Array(len / 4);
  for (var i = 0, k = start; i < res.length; i++, k += 4) {
    var w;
    if (endian === 'big')
      w = (msg[k] << 24) | (msg[k + 1] << 16) | (msg[k + 2] << 8) | msg[k + 3];
    else
      w = (msg[k + 3] << 24) | (msg[k + 2] << 16) | (msg[k + 1] << 8) | msg[k];
    res[i] = w >>> 0;
  }
  return res;
}
exports.join32 = join32;

function split32(msg, endian) {
  var res = new Array(msg.length * 4);
  for (var i = 0, k = 0; i < msg.length; i++, k += 4) {
    var m = msg[i];
    if (endian === 'big') {
      res[k] = m >>> 24;
      res[k + 1] = (m >>> 16) & 0xff;
      res[k + 2] = (m >>> 8) & 0xff;
      res[k + 3] = m & 0xff;
    } else {
      res[k + 3] = m >>> 24;
      res[k + 2] = (m >>> 16) & 0xff;
      res[k + 1] = (m >>> 8) & 0xff;
      res[k] = m & 0xff;
    }
  }
  return res;
}
exports.split32 = split32;

function rotr32(w, b) {
  return (w >>> b) | (w << (32 - b));
}
exports.rotr32 = rotr32;

function rotl32(w, b) {
  return (w << b) | (w >>> (32 - b));
}
exports.rotl32 = rotl32;

function sum32(a, b) {
  return (a + b) >>> 0;
}
exports.sum32 = sum32;

function sum32_3(a, b, c) {
  return (a + b + c) >>> 0;
}
exports.sum32_3 = sum32_3;

function sum32_4(a, b, c, d) {
  return (a + b + c + d) >>> 0;
}
exports.sum32_4 = sum32_4;

function sum32_5(a, b, c, d, e) {
  return (a + b + c + d + e) >>> 0;
}
exports.sum32_5 = sum32_5;

function sum64(buf, pos, ah, al) {
  var bh = buf[pos];
  var bl = buf[pos + 1];

  var lo = (al + bl) >>> 0;
  var hi = (lo < al ? 1 : 0) + ah + bh;
  buf[pos] = hi >>> 0;
  buf[pos + 1] = lo;
}
exports.sum64 = sum64;

function sum64_hi(ah, al, bh, bl) {
  var lo = (al + bl) >>> 0;
  var hi = (lo < al ? 1 : 0) + ah + bh;
  return hi >>> 0;
}
exports.sum64_hi = sum64_hi;

function sum64_lo(ah, al, bh, bl) {
  var lo = al + bl;
  return lo >>> 0;
}
exports.sum64_lo = sum64_lo;

function sum64_4_hi(ah, al, bh, bl, ch, cl, dh, dl) {
  var carry = 0;
  var lo = al;
  lo = (lo + bl) >>> 0;
  carry += lo < al ? 1 : 0;
  lo = (lo + cl) >>> 0;
  carry += lo < cl ? 1 : 0;
  lo = (lo + dl) >>> 0;
  carry += lo < dl ? 1 : 0;

  var hi = ah + bh + ch + dh + carry;
  return hi >>> 0;
}
exports.sum64_4_hi = sum64_4_hi;

function sum64_4_lo(ah, al, bh, bl, ch, cl, dh, dl) {
  var lo = al + bl + cl + dl;
  return lo >>> 0;
}
exports.sum64_4_lo = sum64_4_lo;

function sum64_5_hi(ah, al, bh, bl, ch, cl, dh, dl, eh, el) {
  var carry = 0;
  var lo = al;
  lo = (lo + bl) >>> 0;
  carry += lo < al ? 1 : 0;
  lo = (lo + cl) >>> 0;
  carry += lo < cl ? 1 : 0;
  lo = (lo + dl) >>> 0;
  carry += lo < dl ? 1 : 0;
  lo = (lo + el) >>> 0;
  carry += lo < el ? 1 : 0;

  var hi = ah + bh + ch + dh + eh + carry;
  return hi >>> 0;
}
exports.sum64_5_hi = sum64_5_hi;

function sum64_5_lo(ah, al, bh, bl, ch, cl, dh, dl, eh, el) {
  var lo = al + bl + cl + dl + el;

  return lo >>> 0;
}
exports.sum64_5_lo = sum64_5_lo;

function rotr64_hi(ah, al, num) {
  var r = (al << (32 - num)) | (ah >>> num);
  return r >>> 0;
}
exports.rotr64_hi = rotr64_hi;

function rotr64_lo(ah, al, num) {
  var r = (ah << (32 - num)) | (al >>> num);
  return r >>> 0;
}
exports.rotr64_lo = rotr64_lo;

function shr64_hi(ah, al, num) {
  return ah >>> num;
}
exports.shr64_hi = shr64_hi;

function shr64_lo(ah, al, num) {
  var r = (ah << (32 - num)) | (al >>> num);
  return r >>> 0;
}
exports.shr64_lo = shr64_lo;


/***/ }),

/***/ 1285:
/***/ ((module) => {

if (typeof Object.create === 'function') {
  // implementation from standard node.js 'util' module
  module.exports = function inherits(ctor, superCtor) {
    if (superCtor) {
      ctor.super_ = superCtor
      ctor.prototype = Object.create(superCtor.prototype, {
        constructor: {
          value: ctor,
          enumerable: false,
          writable: true,
          configurable: true
        }
      })
    }
  };
} else {
  // old school shim for old browsers
  module.exports = function inherits(ctor, superCtor) {
    if (superCtor) {
      ctor.super_ = superCtor
      var TempCtor = function () {}
      TempCtor.prototype = superCtor.prototype
      ctor.prototype = new TempCtor()
      ctor.prototype.constructor = ctor
    }
  }
}


/***/ }),

/***/ 7839:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var events = __webpack_require__(2699)
var inherits = __webpack_require__(1285)

module.exports = LRU

function LRU (opts) {
  if (!(this instanceof LRU)) return new LRU(opts)
  if (typeof opts === 'number') opts = {max: opts}
  if (!opts) opts = {}
  events.EventEmitter.call(this)
  this.cache = {}
  this.head = this.tail = null
  this.length = 0
  this.max = opts.max || 1000
  this.maxAge = opts.maxAge || 0
}

inherits(LRU, events.EventEmitter)

Object.defineProperty(LRU.prototype, 'keys', {
  get: function () { return Object.keys(this.cache) }
})

LRU.prototype.clear = function () {
  this.cache = {}
  this.head = this.tail = null
  this.length = 0
}

LRU.prototype.remove = function (key) {
  if (typeof key !== 'string') key = '' + key
  if (!this.cache.hasOwnProperty(key)) return

  var element = this.cache[key]
  delete this.cache[key]
  this._unlink(key, element.prev, element.next)
  return element.value
}

LRU.prototype._unlink = function (key, prev, next) {
  this.length--

  if (this.length === 0) {
    this.head = this.tail = null
  } else {
    if (this.head === key) {
      this.head = prev
      this.cache[this.head].next = null
    } else if (this.tail === key) {
      this.tail = next
      this.cache[this.tail].prev = null
    } else {
      this.cache[prev].next = next
      this.cache[next].prev = prev
    }
  }
}

LRU.prototype.peek = function (key) {
  if (!this.cache.hasOwnProperty(key)) return

  var element = this.cache[key]

  if (!this._checkAge(key, element)) return
  return element.value
}

LRU.prototype.set = function (key, value) {
  if (typeof key !== 'string') key = '' + key

  var element

  if (this.cache.hasOwnProperty(key)) {
    element = this.cache[key]
    element.value = value
    if (this.maxAge) element.modified = Date.now()

    // If it's already the head, there's nothing more to do:
    if (key === this.head) return value
    this._unlink(key, element.prev, element.next)
  } else {
    element = {value: value, modified: 0, next: null, prev: null}
    if (this.maxAge) element.modified = Date.now()
    this.cache[key] = element

    // Eviction is only possible if the key didn't already exist:
    if (this.length === this.max) this.evict()
  }

  this.length++
  element.next = null
  element.prev = this.head

  if (this.head) this.cache[this.head].next = key
  this.head = key

  if (!this.tail) this.tail = key
  return value
}

LRU.prototype._checkAge = function (key, element) {
  if (this.maxAge && (Date.now() - element.modified) > this.maxAge) {
    this.remove(key)
    this.emit('evict', {key: key, value: element.value})
    return false
  }
  return true
}

LRU.prototype.get = function (key) {
  if (typeof key !== 'string') key = '' + key
  if (!this.cache.hasOwnProperty(key)) return

  var element = this.cache[key]

  if (!this._checkAge(key, element)) return

  if (this.head !== key) {
    if (key === this.tail) {
      this.tail = element.next
      this.cache[this.tail].prev = null
    } else {
      // Set prev.next -> element.next:
      this.cache[element.prev].next = element.next
    }

    // Set element.next.prev -> element.prev:
    this.cache[element.next].prev = element.prev

    // Element is the new head
    this.cache[this.head].next = key
    element.prev = this.head
    element.next = null
    this.head = key
  }

  return element.value
}

LRU.prototype.evict = function () {
  if (!this.tail) return
  var key = this.tail
  var value = this.remove(this.tail)
  this.emit('evict', {key: key, value: value})
}


/***/ }),

/***/ 9561:
/***/ ((module) => {

module.exports = assert;

function assert(val, msg) {
  if (!val)
    throw new Error(msg || 'Assertion failed');
}

assert.equal = function assertEqual(l, r, msg) {
  if (l != r)
    throw new Error(msg || ('Assertion failed: ' + l + ' != ' + r));
};


/***/ }),

/***/ 9500:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var hasMap = typeof Map === 'function' && Map.prototype;
var mapSizeDescriptor = Object.getOwnPropertyDescriptor && hasMap ? Object.getOwnPropertyDescriptor(Map.prototype, 'size') : null;
var mapSize = hasMap && mapSizeDescriptor && typeof mapSizeDescriptor.get === 'function' ? mapSizeDescriptor.get : null;
var mapForEach = hasMap && Map.prototype.forEach;
var hasSet = typeof Set === 'function' && Set.prototype;
var setSizeDescriptor = Object.getOwnPropertyDescriptor && hasSet ? Object.getOwnPropertyDescriptor(Set.prototype, 'size') : null;
var setSize = hasSet && setSizeDescriptor && typeof setSizeDescriptor.get === 'function' ? setSizeDescriptor.get : null;
var setForEach = hasSet && Set.prototype.forEach;
var hasWeakMap = typeof WeakMap === 'function' && WeakMap.prototype;
var weakMapHas = hasWeakMap ? WeakMap.prototype.has : null;
var hasWeakSet = typeof WeakSet === 'function' && WeakSet.prototype;
var weakSetHas = hasWeakSet ? WeakSet.prototype.has : null;
var hasWeakRef = typeof WeakRef === 'function' && WeakRef.prototype;
var weakRefDeref = hasWeakRef ? WeakRef.prototype.deref : null;
var booleanValueOf = Boolean.prototype.valueOf;
var objectToString = Object.prototype.toString;
var functionToString = Function.prototype.toString;
var $match = String.prototype.match;
var $slice = String.prototype.slice;
var $replace = String.prototype.replace;
var $toUpperCase = String.prototype.toUpperCase;
var $toLowerCase = String.prototype.toLowerCase;
var $test = RegExp.prototype.test;
var $concat = Array.prototype.concat;
var $join = Array.prototype.join;
var $arrSlice = Array.prototype.slice;
var $floor = Math.floor;
var bigIntValueOf = typeof BigInt === 'function' ? BigInt.prototype.valueOf : null;
var gOPS = Object.getOwnPropertySymbols;
var symToString = typeof Symbol === 'function' && typeof Symbol.iterator === 'symbol' ? Symbol.prototype.toString : null;
var hasShammedSymbols = typeof Symbol === 'function' && typeof Symbol.iterator === 'object';
// ie, `has-tostringtag/shams
var toStringTag = typeof Symbol === 'function' && Symbol.toStringTag && (typeof Symbol.toStringTag === hasShammedSymbols ? 'object' : 'symbol')
    ? Symbol.toStringTag
    : null;
var isEnumerable = Object.prototype.propertyIsEnumerable;

var gPO = (typeof Reflect === 'function' ? Reflect.getPrototypeOf : Object.getPrototypeOf) || (
    [].__proto__ === Array.prototype // eslint-disable-line no-proto
        ? function (O) {
            return O.__proto__; // eslint-disable-line no-proto
        }
        : null
);

function addNumericSeparator(num, str) {
    if (
        num === Infinity
        || num === -Infinity
        || num !== num
        || (num && num > -1000 && num < 1000)
        || $test.call(/e/, str)
    ) {
        return str;
    }
    var sepRegex = /[0-9](?=(?:[0-9]{3})+(?![0-9]))/g;
    if (typeof num === 'number') {
        var int = num < 0 ? -$floor(-num) : $floor(num); // trunc(num)
        if (int !== num) {
            var intStr = String(int);
            var dec = $slice.call(str, intStr.length + 1);
            return $replace.call(intStr, sepRegex, '$&_') + '.' + $replace.call($replace.call(dec, /([0-9]{3})/g, '$&_'), /_$/, '');
        }
    }
    return $replace.call(str, sepRegex, '$&_');
}

var utilInspect = __webpack_require__(3260);
var inspectCustom = utilInspect.custom;
var inspectSymbol = isSymbol(inspectCustom) ? inspectCustom : null;

module.exports = function inspect_(obj, options, depth, seen) {
    var opts = options || {};

    if (has(opts, 'quoteStyle') && (opts.quoteStyle !== 'single' && opts.quoteStyle !== 'double')) {
        throw new TypeError('option "quoteStyle" must be "single" or "double"');
    }
    if (
        has(opts, 'maxStringLength') && (typeof opts.maxStringLength === 'number'
            ? opts.maxStringLength < 0 && opts.maxStringLength !== Infinity
            : opts.maxStringLength !== null
        )
    ) {
        throw new TypeError('option "maxStringLength", if provided, must be a positive integer, Infinity, or `null`');
    }
    var customInspect = has(opts, 'customInspect') ? opts.customInspect : true;
    if (typeof customInspect !== 'boolean' && customInspect !== 'symbol') {
        throw new TypeError('option "customInspect", if provided, must be `true`, `false`, or `\'symbol\'`');
    }

    if (
        has(opts, 'indent')
        && opts.indent !== null
        && opts.indent !== '\t'
        && !(parseInt(opts.indent, 10) === opts.indent && opts.indent > 0)
    ) {
        throw new TypeError('option "indent" must be "\\t", an integer > 0, or `null`');
    }
    if (has(opts, 'numericSeparator') && typeof opts.numericSeparator !== 'boolean') {
        throw new TypeError('option "numericSeparator", if provided, must be `true` or `false`');
    }
    var numericSeparator = opts.numericSeparator;

    if (typeof obj === 'undefined') {
        return 'undefined';
    }
    if (obj === null) {
        return 'null';
    }
    if (typeof obj === 'boolean') {
        return obj ? 'true' : 'false';
    }

    if (typeof obj === 'string') {
        return inspectString(obj, opts);
    }
    if (typeof obj === 'number') {
        if (obj === 0) {
            return Infinity / obj > 0 ? '0' : '-0';
        }
        var str = String(obj);
        return numericSeparator ? addNumericSeparator(obj, str) : str;
    }
    if (typeof obj === 'bigint') {
        var bigIntStr = String(obj) + 'n';
        return numericSeparator ? addNumericSeparator(obj, bigIntStr) : bigIntStr;
    }

    var maxDepth = typeof opts.depth === 'undefined' ? 5 : opts.depth;
    if (typeof depth === 'undefined') { depth = 0; }
    if (depth >= maxDepth && maxDepth > 0 && typeof obj === 'object') {
        return isArray(obj) ? '[Array]' : '[Object]';
    }

    var indent = getIndent(opts, depth);

    if (typeof seen === 'undefined') {
        seen = [];
    } else if (indexOf(seen, obj) >= 0) {
        return '[Circular]';
    }

    function inspect(value, from, noIndent) {
        if (from) {
            seen = $arrSlice.call(seen);
            seen.push(from);
        }
        if (noIndent) {
            var newOpts = {
                depth: opts.depth
            };
            if (has(opts, 'quoteStyle')) {
                newOpts.quoteStyle = opts.quoteStyle;
            }
            return inspect_(value, newOpts, depth + 1, seen);
        }
        return inspect_(value, opts, depth + 1, seen);
    }

    if (typeof obj === 'function' && !isRegExp(obj)) { // in older engines, regexes are callable
        var name = nameOf(obj);
        var keys = arrObjKeys(obj, inspect);
        return '[Function' + (name ? ': ' + name : ' (anonymous)') + ']' + (keys.length > 0 ? ' { ' + $join.call(keys, ', ') + ' }' : '');
    }
    if (isSymbol(obj)) {
        var symString = hasShammedSymbols ? $replace.call(String(obj), /^(Symbol\(.*\))_[^)]*$/, '$1') : symToString.call(obj);
        return typeof obj === 'object' && !hasShammedSymbols ? markBoxed(symString) : symString;
    }
    if (isElement(obj)) {
        var s = '<' + $toLowerCase.call(String(obj.nodeName));
        var attrs = obj.attributes || [];
        for (var i = 0; i < attrs.length; i++) {
            s += ' ' + attrs[i].name + '=' + wrapQuotes(quote(attrs[i].value), 'double', opts);
        }
        s += '>';
        if (obj.childNodes && obj.childNodes.length) { s += '...'; }
        s += '</' + $toLowerCase.call(String(obj.nodeName)) + '>';
        return s;
    }
    if (isArray(obj)) {
        if (obj.length === 0) { return '[]'; }
        var xs = arrObjKeys(obj, inspect);
        if (indent && !singleLineValues(xs)) {
            return '[' + indentedJoin(xs, indent) + ']';
        }
        return '[ ' + $join.call(xs, ', ') + ' ]';
    }
    if (isError(obj)) {
        var parts = arrObjKeys(obj, inspect);
        if (!('cause' in Error.prototype) && 'cause' in obj && !isEnumerable.call(obj, 'cause')) {
            return '{ [' + String(obj) + '] ' + $join.call($concat.call('[cause]: ' + inspect(obj.cause), parts), ', ') + ' }';
        }
        if (parts.length === 0) { return '[' + String(obj) + ']'; }
        return '{ [' + String(obj) + '] ' + $join.call(parts, ', ') + ' }';
    }
    if (typeof obj === 'object' && customInspect) {
        if (inspectSymbol && typeof obj[inspectSymbol] === 'function' && utilInspect) {
            return utilInspect(obj, { depth: maxDepth - depth });
        } else if (customInspect !== 'symbol' && typeof obj.inspect === 'function') {
            return obj.inspect();
        }
    }
    if (isMap(obj)) {
        var mapParts = [];
        if (mapForEach) {
            mapForEach.call(obj, function (value, key) {
                mapParts.push(inspect(key, obj, true) + ' => ' + inspect(value, obj));
            });
        }
        return collectionOf('Map', mapSize.call(obj), mapParts, indent);
    }
    if (isSet(obj)) {
        var setParts = [];
        if (setForEach) {
            setForEach.call(obj, function (value) {
                setParts.push(inspect(value, obj));
            });
        }
        return collectionOf('Set', setSize.call(obj), setParts, indent);
    }
    if (isWeakMap(obj)) {
        return weakCollectionOf('WeakMap');
    }
    if (isWeakSet(obj)) {
        return weakCollectionOf('WeakSet');
    }
    if (isWeakRef(obj)) {
        return weakCollectionOf('WeakRef');
    }
    if (isNumber(obj)) {
        return markBoxed(inspect(Number(obj)));
    }
    if (isBigInt(obj)) {
        return markBoxed(inspect(bigIntValueOf.call(obj)));
    }
    if (isBoolean(obj)) {
        return markBoxed(booleanValueOf.call(obj));
    }
    if (isString(obj)) {
        return markBoxed(inspect(String(obj)));
    }
    if (!isDate(obj) && !isRegExp(obj)) {
        var ys = arrObjKeys(obj, inspect);
        var isPlainObject = gPO ? gPO(obj) === Object.prototype : obj instanceof Object || obj.constructor === Object;
        var protoTag = obj instanceof Object ? '' : 'null prototype';
        var stringTag = !isPlainObject && toStringTag && Object(obj) === obj && toStringTag in obj ? $slice.call(toStr(obj), 8, -1) : protoTag ? 'Object' : '';
        var constructorTag = isPlainObject || typeof obj.constructor !== 'function' ? '' : obj.constructor.name ? obj.constructor.name + ' ' : '';
        var tag = constructorTag + (stringTag || protoTag ? '[' + $join.call($concat.call([], stringTag || [], protoTag || []), ': ') + '] ' : '');
        if (ys.length === 0) { return tag + '{}'; }
        if (indent) {
            return tag + '{' + indentedJoin(ys, indent) + '}';
        }
        return tag + '{ ' + $join.call(ys, ', ') + ' }';
    }
    return String(obj);
};

function wrapQuotes(s, defaultStyle, opts) {
    var quoteChar = (opts.quoteStyle || defaultStyle) === 'double' ? '"' : "'";
    return quoteChar + s + quoteChar;
}

function quote(s) {
    return $replace.call(String(s), /"/g, '&quot;');
}

function isArray(obj) { return toStr(obj) === '[object Array]' && (!toStringTag || !(typeof obj === 'object' && toStringTag in obj)); }
function isDate(obj) { return toStr(obj) === '[object Date]' && (!toStringTag || !(typeof obj === 'object' && toStringTag in obj)); }
function isRegExp(obj) { return toStr(obj) === '[object RegExp]' && (!toStringTag || !(typeof obj === 'object' && toStringTag in obj)); }
function isError(obj) { return toStr(obj) === '[object Error]' && (!toStringTag || !(typeof obj === 'object' && toStringTag in obj)); }
function isString(obj) { return toStr(obj) === '[object String]' && (!toStringTag || !(typeof obj === 'object' && toStringTag in obj)); }
function isNumber(obj) { return toStr(obj) === '[object Number]' && (!toStringTag || !(typeof obj === 'object' && toStringTag in obj)); }
function isBoolean(obj) { return toStr(obj) === '[object Boolean]' && (!toStringTag || !(typeof obj === 'object' && toStringTag in obj)); }

// Symbol and BigInt do have Symbol.toStringTag by spec, so that can't be used to eliminate false positives
function isSymbol(obj) {
    if (hasShammedSymbols) {
        return obj && typeof obj === 'object' && obj instanceof Symbol;
    }
    if (typeof obj === 'symbol') {
        return true;
    }
    if (!obj || typeof obj !== 'object' || !symToString) {
        return false;
    }
    try {
        symToString.call(obj);
        return true;
    } catch (e) {}
    return false;
}

function isBigInt(obj) {
    if (!obj || typeof obj !== 'object' || !bigIntValueOf) {
        return false;
    }
    try {
        bigIntValueOf.call(obj);
        return true;
    } catch (e) {}
    return false;
}

var hasOwn = Object.prototype.hasOwnProperty || function (key) { return key in this; };
function has(obj, key) {
    return hasOwn.call(obj, key);
}

function toStr(obj) {
    return objectToString.call(obj);
}

function nameOf(f) {
    if (f.name) { return f.name; }
    var m = $match.call(functionToString.call(f), /^function\s*([\w$]+)/);
    if (m) { return m[1]; }
    return null;
}

function indexOf(xs, x) {
    if (xs.indexOf) { return xs.indexOf(x); }
    for (var i = 0, l = xs.length; i < l; i++) {
        if (xs[i] === x) { return i; }
    }
    return -1;
}

function isMap(x) {
    if (!mapSize || !x || typeof x !== 'object') {
        return false;
    }
    try {
        mapSize.call(x);
        try {
            setSize.call(x);
        } catch (s) {
            return true;
        }
        return x instanceof Map; // core-js workaround, pre-v2.5.0
    } catch (e) {}
    return false;
}

function isWeakMap(x) {
    if (!weakMapHas || !x || typeof x !== 'object') {
        return false;
    }
    try {
        weakMapHas.call(x, weakMapHas);
        try {
            weakSetHas.call(x, weakSetHas);
        } catch (s) {
            return true;
        }
        return x instanceof WeakMap; // core-js workaround, pre-v2.5.0
    } catch (e) {}
    return false;
}

function isWeakRef(x) {
    if (!weakRefDeref || !x || typeof x !== 'object') {
        return false;
    }
    try {
        weakRefDeref.call(x);
        return true;
    } catch (e) {}
    return false;
}

function isSet(x) {
    if (!setSize || !x || typeof x !== 'object') {
        return false;
    }
    try {
        setSize.call(x);
        try {
            mapSize.call(x);
        } catch (m) {
            return true;
        }
        return x instanceof Set; // core-js workaround, pre-v2.5.0
    } catch (e) {}
    return false;
}

function isWeakSet(x) {
    if (!weakSetHas || !x || typeof x !== 'object') {
        return false;
    }
    try {
        weakSetHas.call(x, weakSetHas);
        try {
            weakMapHas.call(x, weakMapHas);
        } catch (s) {
            return true;
        }
        return x instanceof WeakSet; // core-js workaround, pre-v2.5.0
    } catch (e) {}
    return false;
}

function isElement(x) {
    if (!x || typeof x !== 'object') { return false; }
    if (typeof HTMLElement !== 'undefined' && x instanceof HTMLElement) {
        return true;
    }
    return typeof x.nodeName === 'string' && typeof x.getAttribute === 'function';
}

function inspectString(str, opts) {
    if (str.length > opts.maxStringLength) {
        var remaining = str.length - opts.maxStringLength;
        var trailer = '... ' + remaining + ' more character' + (remaining > 1 ? 's' : '');
        return inspectString($slice.call(str, 0, opts.maxStringLength), opts) + trailer;
    }
    // eslint-disable-next-line no-control-regex
    var s = $replace.call($replace.call(str, /(['\\])/g, '\\$1'), /[\x00-\x1f]/g, lowbyte);
    return wrapQuotes(s, 'single', opts);
}

function lowbyte(c) {
    var n = c.charCodeAt(0);
    var x = {
        8: 'b',
        9: 't',
        10: 'n',
        12: 'f',
        13: 'r'
    }[n];
    if (x) { return '\\' + x; }
    return '\\x' + (n < 0x10 ? '0' : '') + $toUpperCase.call(n.toString(16));
}

function markBoxed(str) {
    return 'Object(' + str + ')';
}

function weakCollectionOf(type) {
    return type + ' { ? }';
}

function collectionOf(type, size, entries, indent) {
    var joinedEntries = indent ? indentedJoin(entries, indent) : $join.call(entries, ', ');
    return type + ' (' + size + ') {' + joinedEntries + '}';
}

function singleLineValues(xs) {
    for (var i = 0; i < xs.length; i++) {
        if (indexOf(xs[i], '\n') >= 0) {
            return false;
        }
    }
    return true;
}

function getIndent(opts, depth) {
    var baseIndent;
    if (opts.indent === '\t') {
        baseIndent = '\t';
    } else if (typeof opts.indent === 'number' && opts.indent > 0) {
        baseIndent = $join.call(Array(opts.indent + 1), ' ');
    } else {
        return null;
    }
    return {
        base: baseIndent,
        prev: $join.call(Array(depth + 1), baseIndent)
    };
}

function indentedJoin(xs, indent) {
    if (xs.length === 0) { return ''; }
    var lineJoiner = '\n' + indent.prev + indent.base;
    return lineJoiner + $join.call(xs, ',' + lineJoiner) + '\n' + indent.prev;
}

function arrObjKeys(obj, inspect) {
    var isArr = isArray(obj);
    var xs = [];
    if (isArr) {
        xs.length = obj.length;
        for (var i = 0; i < obj.length; i++) {
            xs[i] = has(obj, i) ? inspect(obj[i], obj) : '';
        }
    }
    var syms = typeof gOPS === 'function' ? gOPS(obj) : [];
    var symMap;
    if (hasShammedSymbols) {
        symMap = {};
        for (var k = 0; k < syms.length; k++) {
            symMap['$' + syms[k]] = syms[k];
        }
    }

    for (var key in obj) { // eslint-disable-line no-restricted-syntax
        if (!has(obj, key)) { continue; } // eslint-disable-line no-restricted-syntax, no-continue
        if (isArr && String(Number(key)) === key && key < obj.length) { continue; } // eslint-disable-line no-restricted-syntax, no-continue
        if (hasShammedSymbols && symMap['$' + key] instanceof Symbol) {
            // this is to prevent shammed Symbols, which are stored as strings, from being included in the string key section
            continue; // eslint-disable-line no-restricted-syntax, no-continue
        } else if ($test.call(/[^\w$]/, key)) {
            xs.push(inspect(key, obj) + ': ' + inspect(obj[key], obj));
        } else {
            xs.push(key + ': ' + inspect(obj[key], obj));
        }
    }
    if (typeof gOPS === 'function') {
        for (var j = 0; j < syms.length; j++) {
            if (isEnumerable.call(obj, syms[j])) {
                xs.push('[' + inspect(syms[j]) + ']: ' + inspect(obj[syms[j]], obj));
            }
        }
    }
    return xs;
}


/***/ }),

/***/ 8650:
/***/ ((module) => {


var NativeProgressEvent = window.ProgressEvent;
var useNative = !!NativeProgressEvent;

try {
  (function () {
    var p = new NativeProgressEvent('loaded');
    useNative = 'loaded' === p.type;
    p = null;
  })();
} catch (e) {
  useNative = false;
}

/**
 * Cross-browser `ProgressEvent` constructor.
 *
 * https://developer.mozilla.org/en-US/docs/Web/API/ProgressEvent.ProgressEvent
 *
 * @public
 */

module.exports = useNative ? NativeProgressEvent :

// IE >= 9
'function' === typeof document.createEvent ? function ProgressEvent (type, props) {
  var e = document.createEvent('Event');
  e.initEvent(type, false, false);
  if (props) {
    e.lengthComputable = Boolean(props.lengthComputable);
    e.loaded = Number(props.loaded) || 0;
    e.total = Number(props.total) || 0;
  } else {
    e.lengthComputable = false;
    e.loaded = e.total = 0;
  }
  return e;
} :

// IE <= 8
function ProgressEvent (type, props) {
  var e = document.createEventObject();
  e.type = type;
  if (props) {
    e.lengthComputable = Boolean(props.lengthComputable);
    e.loaded = Number(props.loaded) || 0;
    e.total = Number(props.total) || 0;
  } else {
    e.lengthComputable = false;
    e.loaded = e.total = 0;
  }
  return e;
}


/***/ }),

/***/ 5527:
/***/ ((module) => {

"use strict";


var replace = String.prototype.replace;
var percentTwenties = /%20/g;

var Format = {
    RFC1738: 'RFC1738',
    RFC3986: 'RFC3986'
};

module.exports = {
    'default': Format.RFC3986,
    formatters: {
        RFC1738: function (value) {
            return replace.call(value, percentTwenties, '+');
        },
        RFC3986: function (value) {
            return String(value);
        }
    },
    RFC1738: Format.RFC1738,
    RFC3986: Format.RFC3986
};


/***/ }),

/***/ 9126:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var stringify = __webpack_require__(6845);
var parse = __webpack_require__(9166);
var formats = __webpack_require__(5527);

module.exports = {
    formats: formats,
    parse: parse,
    stringify: stringify
};


/***/ }),

/***/ 9166:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var utils = __webpack_require__(2493);

var has = Object.prototype.hasOwnProperty;
var isArray = Array.isArray;

var defaults = {
    allowDots: false,
    allowPrototypes: false,
    allowSparse: false,
    arrayLimit: 20,
    charset: 'utf-8',
    charsetSentinel: false,
    comma: false,
    decoder: utils.decode,
    delimiter: '&',
    depth: 5,
    ignoreQueryPrefix: false,
    interpretNumericEntities: false,
    parameterLimit: 1000,
    parseArrays: true,
    plainObjects: false,
    strictNullHandling: false
};

var interpretNumericEntities = function (str) {
    return str.replace(/&#(\d+);/g, function ($0, numberStr) {
        return String.fromCharCode(parseInt(numberStr, 10));
    });
};

var parseArrayValue = function (val, options) {
    if (val && typeof val === 'string' && options.comma && val.indexOf(',') > -1) {
        return val.split(',');
    }

    return val;
};

// This is what browsers will submit when the ✓ character occurs in an
// application/x-www-form-urlencoded body and the encoding of the page containing
// the form is iso-8859-1, or when the submitted form has an accept-charset
// attribute of iso-8859-1. Presumably also with other charsets that do not contain
// the ✓ character, such as us-ascii.
var isoSentinel = 'utf8=%26%2310003%3B'; // encodeURIComponent('&#10003;')

// These are the percent-encoded utf-8 octets representing a checkmark, indicating that the request actually is utf-8 encoded.
var charsetSentinel = 'utf8=%E2%9C%93'; // encodeURIComponent('✓')

var parseValues = function parseQueryStringValues(str, options) {
    var obj = {};
    var cleanStr = options.ignoreQueryPrefix ? str.replace(/^\?/, '') : str;
    var limit = options.parameterLimit === Infinity ? undefined : options.parameterLimit;
    var parts = cleanStr.split(options.delimiter, limit);
    var skipIndex = -1; // Keep track of where the utf8 sentinel was found
    var i;

    var charset = options.charset;
    if (options.charsetSentinel) {
        for (i = 0; i < parts.length; ++i) {
            if (parts[i].indexOf('utf8=') === 0) {
                if (parts[i] === charsetSentinel) {
                    charset = 'utf-8';
                } else if (parts[i] === isoSentinel) {
                    charset = 'iso-8859-1';
                }
                skipIndex = i;
                i = parts.length; // The eslint settings do not allow break;
            }
        }
    }

    for (i = 0; i < parts.length; ++i) {
        if (i === skipIndex) {
            continue;
        }
        var part = parts[i];

        var bracketEqualsPos = part.indexOf(']=');
        var pos = bracketEqualsPos === -1 ? part.indexOf('=') : bracketEqualsPos + 1;

        var key, val;
        if (pos === -1) {
            key = options.decoder(part, defaults.decoder, charset, 'key');
            val = options.strictNullHandling ? null : '';
        } else {
            key = options.decoder(part.slice(0, pos), defaults.decoder, charset, 'key');
            val = utils.maybeMap(
                parseArrayValue(part.slice(pos + 1), options),
                function (encodedVal) {
                    return options.decoder(encodedVal, defaults.decoder, charset, 'value');
                }
            );
        }

        if (val && options.interpretNumericEntities && charset === 'iso-8859-1') {
            val = interpretNumericEntities(val);
        }

        if (part.indexOf('[]=') > -1) {
            val = isArray(val) ? [val] : val;
        }

        if (has.call(obj, key)) {
            obj[key] = utils.combine(obj[key], val);
        } else {
            obj[key] = val;
        }
    }

    return obj;
};

var parseObject = function (chain, val, options, valuesParsed) {
    var leaf = valuesParsed ? val : parseArrayValue(val, options);

    for (var i = chain.length - 1; i >= 0; --i) {
        var obj;
        var root = chain[i];

        if (root === '[]' && options.parseArrays) {
            obj = [].concat(leaf);
        } else {
            obj = options.plainObjects ? Object.create(null) : {};
            var cleanRoot = root.charAt(0) === '[' && root.charAt(root.length - 1) === ']' ? root.slice(1, -1) : root;
            var index = parseInt(cleanRoot, 10);
            if (!options.parseArrays && cleanRoot === '') {
                obj = { 0: leaf };
            } else if (
                !isNaN(index)
                && root !== cleanRoot
                && String(index) === cleanRoot
                && index >= 0
                && (options.parseArrays && index <= options.arrayLimit)
            ) {
                obj = [];
                obj[index] = leaf;
            } else if (cleanRoot !== '__proto__') {
                obj[cleanRoot] = leaf;
            }
        }

        leaf = obj;
    }

    return leaf;
};

var parseKeys = function parseQueryStringKeys(givenKey, val, options, valuesParsed) {
    if (!givenKey) {
        return;
    }

    // Transform dot notation to bracket notation
    var key = options.allowDots ? givenKey.replace(/\.([^.[]+)/g, '[$1]') : givenKey;

    // The regex chunks

    var brackets = /(\[[^[\]]*])/;
    var child = /(\[[^[\]]*])/g;

    // Get the parent

    var segment = options.depth > 0 && brackets.exec(key);
    var parent = segment ? key.slice(0, segment.index) : key;

    // Stash the parent if it exists

    var keys = [];
    if (parent) {
        // If we aren't using plain objects, optionally prefix keys that would overwrite object prototype properties
        if (!options.plainObjects && has.call(Object.prototype, parent)) {
            if (!options.allowPrototypes) {
                return;
            }
        }

        keys.push(parent);
    }

    // Loop through children appending to the array until we hit depth

    var i = 0;
    while (options.depth > 0 && (segment = child.exec(key)) !== null && i < options.depth) {
        i += 1;
        if (!options.plainObjects && has.call(Object.prototype, segment[1].slice(1, -1))) {
            if (!options.allowPrototypes) {
                return;
            }
        }
        keys.push(segment[1]);
    }

    // If there's a remainder, just add whatever is left

    if (segment) {
        keys.push('[' + key.slice(segment.index) + ']');
    }

    return parseObject(keys, val, options, valuesParsed);
};

var normalizeParseOptions = function normalizeParseOptions(opts) {
    if (!opts) {
        return defaults;
    }

    if (opts.decoder !== null && opts.decoder !== undefined && typeof opts.decoder !== 'function') {
        throw new TypeError('Decoder has to be a function.');
    }

    if (typeof opts.charset !== 'undefined' && opts.charset !== 'utf-8' && opts.charset !== 'iso-8859-1') {
        throw new TypeError('The charset option must be either utf-8, iso-8859-1, or undefined');
    }
    var charset = typeof opts.charset === 'undefined' ? defaults.charset : opts.charset;

    return {
        allowDots: typeof opts.allowDots === 'undefined' ? defaults.allowDots : !!opts.allowDots,
        allowPrototypes: typeof opts.allowPrototypes === 'boolean' ? opts.allowPrototypes : defaults.allowPrototypes,
        allowSparse: typeof opts.allowSparse === 'boolean' ? opts.allowSparse : defaults.allowSparse,
        arrayLimit: typeof opts.arrayLimit === 'number' ? opts.arrayLimit : defaults.arrayLimit,
        charset: charset,
        charsetSentinel: typeof opts.charsetSentinel === 'boolean' ? opts.charsetSentinel : defaults.charsetSentinel,
        comma: typeof opts.comma === 'boolean' ? opts.comma : defaults.comma,
        decoder: typeof opts.decoder === 'function' ? opts.decoder : defaults.decoder,
        delimiter: typeof opts.delimiter === 'string' || utils.isRegExp(opts.delimiter) ? opts.delimiter : defaults.delimiter,
        // eslint-disable-next-line no-implicit-coercion, no-extra-parens
        depth: (typeof opts.depth === 'number' || opts.depth === false) ? +opts.depth : defaults.depth,
        ignoreQueryPrefix: opts.ignoreQueryPrefix === true,
        interpretNumericEntities: typeof opts.interpretNumericEntities === 'boolean' ? opts.interpretNumericEntities : defaults.interpretNumericEntities,
        parameterLimit: typeof opts.parameterLimit === 'number' ? opts.parameterLimit : defaults.parameterLimit,
        parseArrays: opts.parseArrays !== false,
        plainObjects: typeof opts.plainObjects === 'boolean' ? opts.plainObjects : defaults.plainObjects,
        strictNullHandling: typeof opts.strictNullHandling === 'boolean' ? opts.strictNullHandling : defaults.strictNullHandling
    };
};

module.exports = function (str, opts) {
    var options = normalizeParseOptions(opts);

    if (str === '' || str === null || typeof str === 'undefined') {
        return options.plainObjects ? Object.create(null) : {};
    }

    var tempObj = typeof str === 'string' ? parseValues(str, options) : str;
    var obj = options.plainObjects ? Object.create(null) : {};

    // Iterate over the keys and setup the new object

    var keys = Object.keys(tempObj);
    for (var i = 0; i < keys.length; ++i) {
        var key = keys[i];
        var newObj = parseKeys(key, tempObj[key], options, typeof str === 'string');
        obj = utils.merge(obj, newObj, options);
    }

    if (options.allowSparse === true) {
        return obj;
    }

    return utils.compact(obj);
};


/***/ }),

/***/ 6845:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var getSideChannel = __webpack_require__(4294);
var utils = __webpack_require__(2493);
var formats = __webpack_require__(5527);
var has = Object.prototype.hasOwnProperty;

var arrayPrefixGenerators = {
    brackets: function brackets(prefix) {
        return prefix + '[]';
    },
    comma: 'comma',
    indices: function indices(prefix, key) {
        return prefix + '[' + key + ']';
    },
    repeat: function repeat(prefix) {
        return prefix;
    }
};

var isArray = Array.isArray;
var split = String.prototype.split;
var push = Array.prototype.push;
var pushToArray = function (arr, valueOrArray) {
    push.apply(arr, isArray(valueOrArray) ? valueOrArray : [valueOrArray]);
};

var toISO = Date.prototype.toISOString;

var defaultFormat = formats['default'];
var defaults = {
    addQueryPrefix: false,
    allowDots: false,
    charset: 'utf-8',
    charsetSentinel: false,
    delimiter: '&',
    encode: true,
    encoder: utils.encode,
    encodeValuesOnly: false,
    format: defaultFormat,
    formatter: formats.formatters[defaultFormat],
    // deprecated
    indices: false,
    serializeDate: function serializeDate(date) {
        return toISO.call(date);
    },
    skipNulls: false,
    strictNullHandling: false
};

var isNonNullishPrimitive = function isNonNullishPrimitive(v) {
    return typeof v === 'string'
        || typeof v === 'number'
        || typeof v === 'boolean'
        || typeof v === 'symbol'
        || typeof v === 'bigint';
};

var sentinel = {};

var stringify = function stringify(
    object,
    prefix,
    generateArrayPrefix,
    commaRoundTrip,
    strictNullHandling,
    skipNulls,
    encoder,
    filter,
    sort,
    allowDots,
    serializeDate,
    format,
    formatter,
    encodeValuesOnly,
    charset,
    sideChannel
) {
    var obj = object;

    var tmpSc = sideChannel;
    var step = 0;
    var findFlag = false;
    while ((tmpSc = tmpSc.get(sentinel)) !== void undefined && !findFlag) {
        // Where object last appeared in the ref tree
        var pos = tmpSc.get(object);
        step += 1;
        if (typeof pos !== 'undefined') {
            if (pos === step) {
                throw new RangeError('Cyclic object value');
            } else {
                findFlag = true; // Break while
            }
        }
        if (typeof tmpSc.get(sentinel) === 'undefined') {
            step = 0;
        }
    }

    if (typeof filter === 'function') {
        obj = filter(prefix, obj);
    } else if (obj instanceof Date) {
        obj = serializeDate(obj);
    } else if (generateArrayPrefix === 'comma' && isArray(obj)) {
        obj = utils.maybeMap(obj, function (value) {
            if (value instanceof Date) {
                return serializeDate(value);
            }
            return value;
        });
    }

    if (obj === null) {
        if (strictNullHandling) {
            return encoder && !encodeValuesOnly ? encoder(prefix, defaults.encoder, charset, 'key', format) : prefix;
        }

        obj = '';
    }

    if (isNonNullishPrimitive(obj) || utils.isBuffer(obj)) {
        if (encoder) {
            var keyValue = encodeValuesOnly ? prefix : encoder(prefix, defaults.encoder, charset, 'key', format);
            if (generateArrayPrefix === 'comma' && encodeValuesOnly) {
                var valuesArray = split.call(String(obj), ',');
                var valuesJoined = '';
                for (var i = 0; i < valuesArray.length; ++i) {
                    valuesJoined += (i === 0 ? '' : ',') + formatter(encoder(valuesArray[i], defaults.encoder, charset, 'value', format));
                }
                return [formatter(keyValue) + (commaRoundTrip && isArray(obj) && valuesArray.length === 1 ? '[]' : '') + '=' + valuesJoined];
            }
            return [formatter(keyValue) + '=' + formatter(encoder(obj, defaults.encoder, charset, 'value', format))];
        }
        return [formatter(prefix) + '=' + formatter(String(obj))];
    }

    var values = [];

    if (typeof obj === 'undefined') {
        return values;
    }

    var objKeys;
    if (generateArrayPrefix === 'comma' && isArray(obj)) {
        // we need to join elements in
        objKeys = [{ value: obj.length > 0 ? obj.join(',') || null : void undefined }];
    } else if (isArray(filter)) {
        objKeys = filter;
    } else {
        var keys = Object.keys(obj);
        objKeys = sort ? keys.sort(sort) : keys;
    }

    var adjustedPrefix = commaRoundTrip && isArray(obj) && obj.length === 1 ? prefix + '[]' : prefix;

    for (var j = 0; j < objKeys.length; ++j) {
        var key = objKeys[j];
        var value = typeof key === 'object' && typeof key.value !== 'undefined' ? key.value : obj[key];

        if (skipNulls && value === null) {
            continue;
        }

        var keyPrefix = isArray(obj)
            ? typeof generateArrayPrefix === 'function' ? generateArrayPrefix(adjustedPrefix, key) : adjustedPrefix
            : adjustedPrefix + (allowDots ? '.' + key : '[' + key + ']');

        sideChannel.set(object, step);
        var valueSideChannel = getSideChannel();
        valueSideChannel.set(sentinel, sideChannel);
        pushToArray(values, stringify(
            value,
            keyPrefix,
            generateArrayPrefix,
            commaRoundTrip,
            strictNullHandling,
            skipNulls,
            encoder,
            filter,
            sort,
            allowDots,
            serializeDate,
            format,
            formatter,
            encodeValuesOnly,
            charset,
            valueSideChannel
        ));
    }

    return values;
};

var normalizeStringifyOptions = function normalizeStringifyOptions(opts) {
    if (!opts) {
        return defaults;
    }

    if (opts.encoder !== null && typeof opts.encoder !== 'undefined' && typeof opts.encoder !== 'function') {
        throw new TypeError('Encoder has to be a function.');
    }

    var charset = opts.charset || defaults.charset;
    if (typeof opts.charset !== 'undefined' && opts.charset !== 'utf-8' && opts.charset !== 'iso-8859-1') {
        throw new TypeError('The charset option must be either utf-8, iso-8859-1, or undefined');
    }

    var format = formats['default'];
    if (typeof opts.format !== 'undefined') {
        if (!has.call(formats.formatters, opts.format)) {
            throw new TypeError('Unknown format option provided.');
        }
        format = opts.format;
    }
    var formatter = formats.formatters[format];

    var filter = defaults.filter;
    if (typeof opts.filter === 'function' || isArray(opts.filter)) {
        filter = opts.filter;
    }

    return {
        addQueryPrefix: typeof opts.addQueryPrefix === 'boolean' ? opts.addQueryPrefix : defaults.addQueryPrefix,
        allowDots: typeof opts.allowDots === 'undefined' ? defaults.allowDots : !!opts.allowDots,
        charset: charset,
        charsetSentinel: typeof opts.charsetSentinel === 'boolean' ? opts.charsetSentinel : defaults.charsetSentinel,
        delimiter: typeof opts.delimiter === 'undefined' ? defaults.delimiter : opts.delimiter,
        encode: typeof opts.encode === 'boolean' ? opts.encode : defaults.encode,
        encoder: typeof opts.encoder === 'function' ? opts.encoder : defaults.encoder,
        encodeValuesOnly: typeof opts.encodeValuesOnly === 'boolean' ? opts.encodeValuesOnly : defaults.encodeValuesOnly,
        filter: filter,
        format: format,
        formatter: formatter,
        serializeDate: typeof opts.serializeDate === 'function' ? opts.serializeDate : defaults.serializeDate,
        skipNulls: typeof opts.skipNulls === 'boolean' ? opts.skipNulls : defaults.skipNulls,
        sort: typeof opts.sort === 'function' ? opts.sort : null,
        strictNullHandling: typeof opts.strictNullHandling === 'boolean' ? opts.strictNullHandling : defaults.strictNullHandling
    };
};

module.exports = function (object, opts) {
    var obj = object;
    var options = normalizeStringifyOptions(opts);

    var objKeys;
    var filter;

    if (typeof options.filter === 'function') {
        filter = options.filter;
        obj = filter('', obj);
    } else if (isArray(options.filter)) {
        filter = options.filter;
        objKeys = filter;
    }

    var keys = [];

    if (typeof obj !== 'object' || obj === null) {
        return '';
    }

    var arrayFormat;
    if (opts && opts.arrayFormat in arrayPrefixGenerators) {
        arrayFormat = opts.arrayFormat;
    } else if (opts && 'indices' in opts) {
        arrayFormat = opts.indices ? 'indices' : 'repeat';
    } else {
        arrayFormat = 'indices';
    }

    var generateArrayPrefix = arrayPrefixGenerators[arrayFormat];
    if (opts && 'commaRoundTrip' in opts && typeof opts.commaRoundTrip !== 'boolean') {
        throw new TypeError('`commaRoundTrip` must be a boolean, or absent');
    }
    var commaRoundTrip = generateArrayPrefix === 'comma' && opts && opts.commaRoundTrip;

    if (!objKeys) {
        objKeys = Object.keys(obj);
    }

    if (options.sort) {
        objKeys.sort(options.sort);
    }

    var sideChannel = getSideChannel();
    for (var i = 0; i < objKeys.length; ++i) {
        var key = objKeys[i];

        if (options.skipNulls && obj[key] === null) {
            continue;
        }
        pushToArray(keys, stringify(
            obj[key],
            key,
            generateArrayPrefix,
            commaRoundTrip,
            options.strictNullHandling,
            options.skipNulls,
            options.encode ? options.encoder : null,
            options.filter,
            options.sort,
            options.allowDots,
            options.serializeDate,
            options.format,
            options.formatter,
            options.encodeValuesOnly,
            options.charset,
            sideChannel
        ));
    }

    var joined = keys.join(options.delimiter);
    var prefix = options.addQueryPrefix === true ? '?' : '';

    if (options.charsetSentinel) {
        if (options.charset === 'iso-8859-1') {
            // encodeURIComponent('&#10003;'), the "numeric entity" representation of a checkmark
            prefix += 'utf8=%26%2310003%3B&';
        } else {
            // encodeURIComponent('✓')
            prefix += 'utf8=%E2%9C%93&';
        }
    }

    return joined.length > 0 ? prefix + joined : '';
};


/***/ }),

/***/ 2493:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var formats = __webpack_require__(5527);

var has = Object.prototype.hasOwnProperty;
var isArray = Array.isArray;

var hexTable = (function () {
    var array = [];
    for (var i = 0; i < 256; ++i) {
        array.push('%' + ((i < 16 ? '0' : '') + i.toString(16)).toUpperCase());
    }

    return array;
}());

var compactQueue = function compactQueue(queue) {
    while (queue.length > 1) {
        var item = queue.pop();
        var obj = item.obj[item.prop];

        if (isArray(obj)) {
            var compacted = [];

            for (var j = 0; j < obj.length; ++j) {
                if (typeof obj[j] !== 'undefined') {
                    compacted.push(obj[j]);
                }
            }

            item.obj[item.prop] = compacted;
        }
    }
};

var arrayToObject = function arrayToObject(source, options) {
    var obj = options && options.plainObjects ? Object.create(null) : {};
    for (var i = 0; i < source.length; ++i) {
        if (typeof source[i] !== 'undefined') {
            obj[i] = source[i];
        }
    }

    return obj;
};

var merge = function merge(target, source, options) {
    /* eslint no-param-reassign: 0 */
    if (!source) {
        return target;
    }

    if (typeof source !== 'object') {
        if (isArray(target)) {
            target.push(source);
        } else if (target && typeof target === 'object') {
            if ((options && (options.plainObjects || options.allowPrototypes)) || !has.call(Object.prototype, source)) {
                target[source] = true;
            }
        } else {
            return [target, source];
        }

        return target;
    }

    if (!target || typeof target !== 'object') {
        return [target].concat(source);
    }

    var mergeTarget = target;
    if (isArray(target) && !isArray(source)) {
        mergeTarget = arrayToObject(target, options);
    }

    if (isArray(target) && isArray(source)) {
        source.forEach(function (item, i) {
            if (has.call(target, i)) {
                var targetItem = target[i];
                if (targetItem && typeof targetItem === 'object' && item && typeof item === 'object') {
                    target[i] = merge(targetItem, item, options);
                } else {
                    target.push(item);
                }
            } else {
                target[i] = item;
            }
        });
        return target;
    }

    return Object.keys(source).reduce(function (acc, key) {
        var value = source[key];

        if (has.call(acc, key)) {
            acc[key] = merge(acc[key], value, options);
        } else {
            acc[key] = value;
        }
        return acc;
    }, mergeTarget);
};

var assign = function assignSingleSource(target, source) {
    return Object.keys(source).reduce(function (acc, key) {
        acc[key] = source[key];
        return acc;
    }, target);
};

var decode = function (str, decoder, charset) {
    var strWithoutPlus = str.replace(/\+/g, ' ');
    if (charset === 'iso-8859-1') {
        // unescape never throws, no try...catch needed:
        return strWithoutPlus.replace(/%[0-9a-f]{2}/gi, unescape);
    }
    // utf-8
    try {
        return decodeURIComponent(strWithoutPlus);
    } catch (e) {
        return strWithoutPlus;
    }
};

var encode = function encode(str, defaultEncoder, charset, kind, format) {
    // This code was originally written by Brian White (mscdex) for the io.js core querystring library.
    // It has been adapted here for stricter adherence to RFC 3986
    if (str.length === 0) {
        return str;
    }

    var string = str;
    if (typeof str === 'symbol') {
        string = Symbol.prototype.toString.call(str);
    } else if (typeof str !== 'string') {
        string = String(str);
    }

    if (charset === 'iso-8859-1') {
        return escape(string).replace(/%u[0-9a-f]{4}/gi, function ($0) {
            return '%26%23' + parseInt($0.slice(2), 16) + '%3B';
        });
    }

    var out = '';
    for (var i = 0; i < string.length; ++i) {
        var c = string.charCodeAt(i);

        if (
            c === 0x2D // -
            || c === 0x2E // .
            || c === 0x5F // _
            || c === 0x7E // ~
            || (c >= 0x30 && c <= 0x39) // 0-9
            || (c >= 0x41 && c <= 0x5A) // a-z
            || (c >= 0x61 && c <= 0x7A) // A-Z
            || (format === formats.RFC1738 && (c === 0x28 || c === 0x29)) // ( )
        ) {
            out += string.charAt(i);
            continue;
        }

        if (c < 0x80) {
            out = out + hexTable[c];
            continue;
        }

        if (c < 0x800) {
            out = out + (hexTable[0xC0 | (c >> 6)] + hexTable[0x80 | (c & 0x3F)]);
            continue;
        }

        if (c < 0xD800 || c >= 0xE000) {
            out = out + (hexTable[0xE0 | (c >> 12)] + hexTable[0x80 | ((c >> 6) & 0x3F)] + hexTable[0x80 | (c & 0x3F)]);
            continue;
        }

        i += 1;
        c = 0x10000 + (((c & 0x3FF) << 10) | (string.charCodeAt(i) & 0x3FF));
        /* eslint operator-linebreak: [2, "before"] */
        out += hexTable[0xF0 | (c >> 18)]
            + hexTable[0x80 | ((c >> 12) & 0x3F)]
            + hexTable[0x80 | ((c >> 6) & 0x3F)]
            + hexTable[0x80 | (c & 0x3F)];
    }

    return out;
};

var compact = function compact(value) {
    var queue = [{ obj: { o: value }, prop: 'o' }];
    var refs = [];

    for (var i = 0; i < queue.length; ++i) {
        var item = queue[i];
        var obj = item.obj[item.prop];

        var keys = Object.keys(obj);
        for (var j = 0; j < keys.length; ++j) {
            var key = keys[j];
            var val = obj[key];
            if (typeof val === 'object' && val !== null && refs.indexOf(val) === -1) {
                queue.push({ obj: obj, prop: key });
                refs.push(val);
            }
        }
    }

    compactQueue(queue);

    return value;
};

var isRegExp = function isRegExp(obj) {
    return Object.prototype.toString.call(obj) === '[object RegExp]';
};

var isBuffer = function isBuffer(obj) {
    if (!obj || typeof obj !== 'object') {
        return false;
    }

    return !!(obj.constructor && obj.constructor.isBuffer && obj.constructor.isBuffer(obj));
};

var combine = function combine(a, b) {
    return [].concat(a, b);
};

var maybeMap = function maybeMap(val, fn) {
    if (isArray(val)) {
        var mapped = [];
        for (var i = 0; i < val.length; i += 1) {
            mapped.push(fn(val[i]));
        }
        return mapped;
    }
    return fn(val);
};

module.exports = {
    arrayToObject: arrayToObject,
    assign: assign,
    combine: combine,
    compact: compact,
    decode: decode,
    encode: encode,
    isBuffer: isBuffer,
    isRegExp: isRegExp,
    maybeMap: maybeMap,
    merge: merge
};


/***/ }),

/***/ 4294:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var GetIntrinsic = __webpack_require__(7286);
var callBound = __webpack_require__(2680);
var inspect = __webpack_require__(9500);

var $TypeError = GetIntrinsic('%TypeError%');
var $WeakMap = GetIntrinsic('%WeakMap%', true);
var $Map = GetIntrinsic('%Map%', true);

var $weakMapGet = callBound('WeakMap.prototype.get', true);
var $weakMapSet = callBound('WeakMap.prototype.set', true);
var $weakMapHas = callBound('WeakMap.prototype.has', true);
var $mapGet = callBound('Map.prototype.get', true);
var $mapSet = callBound('Map.prototype.set', true);
var $mapHas = callBound('Map.prototype.has', true);

/*
 * This function traverses the list returning the node corresponding to the
 * given key.
 *
 * That node is also moved to the head of the list, so that if it's accessed
 * again we don't need to traverse the whole list. By doing so, all the recently
 * used nodes can be accessed relatively quickly.
 */
var listGetNode = function (list, key) { // eslint-disable-line consistent-return
	for (var prev = list, curr; (curr = prev.next) !== null; prev = curr) {
		if (curr.key === key) {
			prev.next = curr.next;
			curr.next = list.next;
			list.next = curr; // eslint-disable-line no-param-reassign
			return curr;
		}
	}
};

var listGet = function (objects, key) {
	var node = listGetNode(objects, key);
	return node && node.value;
};
var listSet = function (objects, key, value) {
	var node = listGetNode(objects, key);
	if (node) {
		node.value = value;
	} else {
		// Prepend the new node to the beginning of the list
		objects.next = { // eslint-disable-line no-param-reassign
			key: key,
			next: objects.next,
			value: value
		};
	}
};
var listHas = function (objects, key) {
	return !!listGetNode(objects, key);
};

module.exports = function getSideChannel() {
	var $wm;
	var $m;
	var $o;
	var channel = {
		assert: function (key) {
			if (!channel.has(key)) {
				throw new $TypeError('Side channel does not contain ' + inspect(key));
			}
		},
		get: function (key) { // eslint-disable-line consistent-return
			if ($WeakMap && key && (typeof key === 'object' || typeof key === 'function')) {
				if ($wm) {
					return $weakMapGet($wm, key);
				}
			} else if ($Map) {
				if ($m) {
					return $mapGet($m, key);
				}
			} else {
				if ($o) { // eslint-disable-line no-lonely-if
					return listGet($o, key);
				}
			}
		},
		has: function (key) {
			if ($WeakMap && key && (typeof key === 'object' || typeof key === 'function')) {
				if ($wm) {
					return $weakMapHas($wm, key);
				}
			} else if ($Map) {
				if ($m) {
					return $mapHas($m, key);
				}
			} else {
				if ($o) { // eslint-disable-line no-lonely-if
					return listHas($o, key);
				}
			}
			return false;
		},
		set: function (key, value) {
			if ($WeakMap && key && (typeof key === 'object' || typeof key === 'function')) {
				if (!$wm) {
					$wm = new $WeakMap();
				}
				$weakMapSet($wm, key, value);
			} else if ($Map) {
				if (!$m) {
					$m = new $Map();
				}
				$mapSet($m, key, value);
			} else {
				if (!$o) {
					/*
					 * Initialize the linked list as an empty node, so that we don't have
					 * to special-case handling of the first node: we can always refer to
					 * it as (previous node).next, instead of something like (list).head
					 */
					$o = { key: {}, next: null };
				}
				listSet($o, key, value);
			}
		}
	};
	return channel;
};


/***/ }),

/***/ 9830:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (/* binding */ Tannin)
/* harmony export */ });
/* harmony import */ var _tannin_plural_forms__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(1184);


/**
 * Tannin constructor options.
 *
 * @typedef {Object} TanninOptions
 *
 * @property {string}   [contextDelimiter] Joiner in string lookup with context.
 * @property {Function} [onMissingKey]     Callback to invoke when key missing.
 */

/**
 * Domain metadata.
 *
 * @typedef {Object} TanninDomainMetadata
 *
 * @property {string}            [domain]       Domain name.
 * @property {string}            [lang]         Language code.
 * @property {(string|Function)} [plural_forms] Plural forms expression or
 *                                              function evaluator.
 */

/**
 * Domain translation pair respectively representing the singular and plural
 * translation.
 *
 * @typedef {[string,string]} TanninTranslation
 */

/**
 * Locale data domain. The key is used as reference for lookup, the value an
 * array of two string entries respectively representing the singular and plural
 * translation.
 *
 * @typedef {{[key:string]:TanninDomainMetadata|TanninTranslation,'':TanninDomainMetadata|TanninTranslation}} TanninLocaleDomain
 */

/**
 * Jed-formatted locale data.
 *
 * @see http://messageformat.github.io/Jed/
 *
 * @typedef {{[domain:string]:TanninLocaleDomain}} TanninLocaleData
 */

/**
 * Default Tannin constructor options.
 *
 * @type {TanninOptions}
 */
var DEFAULT_OPTIONS = {
	contextDelimiter: '\u0004',
	onMissingKey: null,
};

/**
 * Given a specific locale data's config `plural_forms` value, returns the
 * expression.
 *
 * @example
 *
 * ```
 * getPluralExpression( 'nplurals=2; plural=(n != 1);' ) === '(n != 1)'
 * ```
 *
 * @param {string} pf Locale data plural forms.
 *
 * @return {string} Plural forms expression.
 */
function getPluralExpression( pf ) {
	var parts, i, part;

	parts = pf.split( ';' );

	for ( i = 0; i < parts.length; i++ ) {
		part = parts[ i ].trim();
		if ( part.indexOf( 'plural=' ) === 0 ) {
			return part.substr( 7 );
		}
	}
}

/**
 * Tannin constructor.
 *
 * @class
 *
 * @param {TanninLocaleData} data      Jed-formatted locale data.
 * @param {TanninOptions}    [options] Tannin options.
 */
function Tannin( data, options ) {
	var key;

	/**
	 * Jed-formatted locale data.
	 *
	 * @name Tannin#data
	 * @type {TanninLocaleData}
	 */
	this.data = data;

	/**
	 * Plural forms function cache, keyed by plural forms string.
	 *
	 * @name Tannin#pluralForms
	 * @type {Object<string,Function>}
	 */
	this.pluralForms = {};

	/**
	 * Effective options for instance, including defaults.
	 *
	 * @name Tannin#options
	 * @type {TanninOptions}
	 */
	this.options = {};

	for ( key in DEFAULT_OPTIONS ) {
		this.options[ key ] = options !== undefined && key in options
			? options[ key ]
			: DEFAULT_OPTIONS[ key ];
	}
}

/**
 * Returns the plural form index for the given domain and value.
 *
 * @param {string} domain Domain on which to calculate plural form.
 * @param {number} n      Value for which plural form is to be calculated.
 *
 * @return {number} Plural form index.
 */
Tannin.prototype.getPluralForm = function( domain, n ) {
	var getPluralForm = this.pluralForms[ domain ],
		config, plural, pf;

	if ( ! getPluralForm ) {
		config = this.data[ domain ][ '' ];

		pf = (
			config[ 'Plural-Forms' ] ||
			config[ 'plural-forms' ] ||
			// Ignore reason: As known, there's no way to document the empty
			// string property on a key to guarantee this as metadata.
			// @ts-ignore
			config.plural_forms
		);

		if ( typeof pf !== 'function' ) {
			plural = getPluralExpression(
				config[ 'Plural-Forms' ] ||
				config[ 'plural-forms' ] ||
				// Ignore reason: As known, there's no way to document the empty
				// string property on a key to guarantee this as metadata.
				// @ts-ignore
				config.plural_forms
			);

			pf = (0,_tannin_plural_forms__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z)( plural );
		}

		getPluralForm = this.pluralForms[ domain ] = pf;
	}

	return getPluralForm( n );
};

/**
 * Translate a string.
 *
 * @param {string}      domain   Translation domain.
 * @param {string|void} context  Context distinguishing terms of the same name.
 * @param {string}      singular Primary key for translation lookup.
 * @param {string=}     plural   Fallback value used for non-zero plural
 *                               form index.
 * @param {number=}     n        Value to use in calculating plural form.
 *
 * @return {string} Translated string.
 */
Tannin.prototype.dcnpgettext = function( domain, context, singular, plural, n ) {
	var index, key, entry;

	if ( n === undefined ) {
		// Default to singular.
		index = 0;
	} else {
		// Find index by evaluating plural form for value.
		index = this.getPluralForm( domain, n );
	}

	key = singular;

	// If provided, context is prepended to key with delimiter.
	if ( context ) {
		key = context + this.options.contextDelimiter + singular;
	}

	entry = this.data[ domain ][ key ];

	// Verify not only that entry exists, but that the intended index is within
	// range and non-empty.
	if ( entry && entry[ index ] ) {
		return entry[ index ];
	}

	if ( this.options.onMissingKey ) {
		this.options.onMissingKey( singular, domain );
	}

	// If entry not found, fall back to singular vs. plural with zero index
	// representing the singular value.
	return index === 0 ? singular : plural;
};


/***/ }),

/***/ 6274:
/***/ ((__unused_webpack_module, __unused_webpack___webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony import */ var _automattic_data_stores__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(8077);

_automattic_data_stores__WEBPACK_IMPORTED_MODULE_0__/* .register */ .z2();

/***/ }),

/***/ 3857:
/***/ ((__unused_webpack_module, __unused_webpack___webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony import */ var _automattic_data_stores__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(9734);

_automattic_data_stores__WEBPACK_IMPORTED_MODULE_0__/* .register */ .z2();

/***/ }),

/***/ 561:
/***/ ((__unused_webpack_module, __unused_webpack___webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony import */ var _automattic_data_stores__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(2369);

_automattic_data_stores__WEBPACK_IMPORTED_MODULE_0__/* .register */ .z2({
  client_id: '',
  client_secret: ''
});

/***/ }),

/***/ 9770:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
// TODO: Revisit whether it is useful for the Desktop app to override the following properties:
// signup_url, login_url, logout_url and discover_logged_out_redirect_url
const config = {
  env: 'production',
  env_id: 'desktop',
  client_slug: 'desktop',
  readerFollowingSource: 'desktop',
  boom_analytics_key: 'desktop',
  google_recaptcha_site_key: '6LdoXcAUAAAAAM61KvdgP8xwnC19YuzAiOWn5Wtn'
};
const features = {
  desktop: true,
  'desktop-promo': false,
  'sign-in-with-apple': false,
  // Note: there is also a sign-in-with-apple/redirect flag
  // that may/may not be relevant to override for the Desktop app.
  'signup/social': false,
  'login/magic-login': false,
  'bilmur-script': false
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (data => {
  data = Object.assign(data, config);

  if (data.features) {
    data.features = Object.assign(data.features, features);
  }

  if (window.electron && window.electron.features) {
    data.features = Object.assign(data.features ?? {}, window.electron.features);
  }

  return data;
});

/***/ }),

/***/ 899:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "_k": () => (/* binding */ isEnabled)
/* harmony export */ });
/* unused harmony exports isCalypsoLive, enabledFeatures, enable, disable */
/* harmony import */ var _automattic_create_calypso_config__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(544);
/* harmony import */ var cookie__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(3421);
/* harmony import */ var _desktop__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(9770);




/**
 * Manages config flags for various deployment builds
 *
 * @module config/index
 */
if ('undefined' === typeof window) {
  throw new Error('Trying to initialize the configuration outside of a browser context.');
}

if (!window.configData) {
  if (false) {}

  window.configData = {};
}

const isDesktop = window.electron !== undefined;
let configData;

if (isDesktop) {
  configData = (0,_desktop__WEBPACK_IMPORTED_MODULE_2__/* ["default"] */ .Z)(window.configData);
} else {
  configData = window.configData;
} // calypso.live matches
// hash-abcd1234.calypso.live matches
// calypso.live.com doesn't match


const CALYPSO_LIVE_REGEX = /^([a-zA-Z0-9-]+\.)?calypso\.live$/; // check if the current browser location is *.calypso.live

function isCalypsoLive() {
  return typeof window !== 'undefined' && CALYPSO_LIVE_REGEX.test(window.location.host);
}

function applyFlags(flagsString, modificationMethod) {
  const flags = flagsString.split(',');
  flags.forEach(flagRaw => {
    const flag = flagRaw.replace(/^[-+]/, '');
    const enabled = !/^-/.test(flagRaw);

    if (configData.features) {
      configData.features[flag] = enabled; // eslint-disable-next-line no-console

      console.log('%cConfig flag %s via %s: %s', 'font-weight: bold;', enabled ? 'enabled' : 'disabled', modificationMethod, flag);
    }
  });
}

const flagEnvironments = ['wpcalypso', 'horizon', 'stage', 'jetpack-cloud-stage'];

if ( false || flagEnvironments.includes(configData.env_id) || isCalypsoLive()) {
  const cookies = cookie__WEBPACK_IMPORTED_MODULE_1__.parse(document.cookie);

  if (cookies.flags) {
    applyFlags(cookies.flags, 'cookie');
  }

  try {
    const session = window.sessionStorage.getItem('flags');

    if (session) {
      applyFlags(session, 'sessionStorage');
    }
  } catch (e) {// in private context, accessing session storage can throw
  }

  const match = document.location.search && document.location.search.match(/[?&]flags=([^&]+)(&|$)/);

  if (match) {
    applyFlags(decodeURIComponent(match[1]), 'URL');
  }
}

const configApi = (0,_automattic_create_calypso_config__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z)(configData);
/* unused harmony default export */ var __WEBPACK_DEFAULT_EXPORT__ = ((/* unused pure expression or super */ null && (configApi)));
const isEnabled = configApi.isEnabled;
const enabledFeatures = configApi.enabledFeatures;
const enable = configApi.enable;
const disable = configApi.disable;

/***/ }),

/***/ 544:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * Returns configuration value for given key
 *
 * If the requested key isn't defined in the configuration
 * data then this will report the failure with either an
 * error or a console warning.
 *
 * When in the 'development' NODE_ENV it will raise an error
 * to crash execution early. However, because many modules
 * call this function in the module-global scope a failure
 * here can not only crash that module but also entire
 * application flows as well as trigger unexpected and
 * unwanted behaviors. Therefore if the NODE_ENV is not
 * 'development' we will return `undefined` and log a message
 * to the console instead of halting the execution thread.
 *
 * The config files are loaded in sequence: _shared.json, {env}.json, {env}.local.json
 *
 * @see server/config/parser.js
 * @param data Configurat data.
 * @throws {ReferenceError} when key not defined in the config (NODE_ENV=development only)
 * @returns A function that gets the value of property named by the key
 */
const config = data => key => {
  if (key in data) {
    return data[key];
  }

  if (false) {} // display console error only in a browser
  // (not in tests, for example)


  if ('undefined' !== typeof window) {
    // eslint-disable-next-line no-console
    console.error('%cCore Error: ' + `%cCould not find config value for key %c${key}%c. ` + 'Please make sure that if you need it then it has a default value assigned in ' + '%cconfig/_shared.json' + '%c.', 'color: red; font-size: 120%', // error prefix
    'color: black;', // message
    'color: blue;', // key name
    'color: black;', // message
    'color: blue;', // config file reference
    'color: black' // message
    );
  }

  return undefined;
};
/**
 * Checks whether a specific feature is enabled.
 *
 * @param data the json environment configuration to use for getting config values
 * @returns A function that takes a feature name and returns true when the feature is enabled.
 */


const isEnabled = data => feature => data.features && !!data.features[feature] || false;
/**
 * Gets a list of all enabled features.
 *
 * @param data A set of config data (Not used by general users, is pre-filled via currying).
 * @returns List of enabled features (strings).
 */


const enabledFeatures = data => () => {
  if (!data.features) {
    return [];
  }

  return Object.entries(data.features).reduce((enabled, _ref) => {
    let [feature, isEnabled] = _ref;
    return isEnabled ? [...enabled, feature] : enabled;
  }, []);
};
/**
 * Enables a specific feature.
 *
 * @param data the json environment configuration to use for getting config values
 */


const enable = data => feature => {
  if (data.features) {
    data.features[feature] = true;
  }
};
/**
 * Disables a specific feature.
 *
 * @param data the json environment configuration to use for getting config values
 */


const disable = data => feature => {
  if (data.features) {
    data.features[feature] = false;
  }
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (data => {
  const configApi = config(data);
  configApi.isEnabled = isEnabled(data);
  configApi.enabledFeatures = enabledFeatures(data);
  configApi.enable = enable(data);
  configApi.disable = disable(data);
  return configApi;
});

/***/ }),

/***/ 3600:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "receiveCategories": () => (/* binding */ receiveCategories),
/* harmony export */   "fetchDomainSuggestions": () => (/* binding */ fetchDomainSuggestions),
/* harmony export */   "receiveDomainAvailability": () => (/* binding */ receiveDomainAvailability),
/* harmony export */   "receiveDomainSuggestionsSuccess": () => (/* binding */ receiveDomainSuggestionsSuccess),
/* harmony export */   "receiveDomainSuggestionsError": () => (/* binding */ receiveDomainSuggestionsError)
/* harmony export */ });
const receiveCategories = categories => ({
  type: 'RECEIVE_CATEGORIES',
  categories
});
const fetchDomainSuggestions = () => ({
  type: 'FETCH_DOMAIN_SUGGESTIONS',
  timeStamp: Date.now()
});
const receiveDomainAvailability = (domainName, availability) => ({
  type: 'RECEIVE_DOMAIN_AVAILABILITY',
  domainName,
  availability
});
const receiveDomainSuggestionsSuccess = (queryObject, suggestions) => ({
  type: 'RECEIVE_DOMAIN_SUGGESTIONS_SUCCESS',
  queryObject,
  suggestions,
  timeStamp: Date.now()
});
const receiveDomainSuggestionsError = errorMessage => ({
  type: 'RECEIVE_DOMAIN_SUGGESTIONS_ERROR',
  errorMessage,
  timeStamp: Date.now()
});

/***/ }),

/***/ 584:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "L": () => (/* binding */ STORE_KEY),
/* harmony export */   "r": () => (/* binding */ DataStatus)
/* harmony export */ });
const STORE_KEY = 'automattic/domains/suggestions';
let DataStatus;

(function (DataStatus) {
  DataStatus["Failure"] = "failure";
  DataStatus["Pending"] = "pending";
  DataStatus["Success"] = "success";
  DataStatus["Uninitialized"] = "uninitialized";
})(DataStatus || (DataStatus = {}));

/***/ }),

/***/ 8077:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "z2": () => (/* binding */ register)
/* harmony export */ });
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(9818);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wpcom_request_controls__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(3661);
/* harmony import */ var _actions__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(3600);
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(584);
/* harmony import */ var _reducer__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(3717);
/* harmony import */ var _resolvers__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(2269);
/* harmony import */ var _selectors__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(267);











let isRegistered = false;
function register() {
  if (!isRegistered) {
    isRegistered = true;
    (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.registerStore)(_constants__WEBPACK_IMPORTED_MODULE_1__/* .STORE_KEY */ .L, {
      actions: _actions__WEBPACK_IMPORTED_MODULE_2__,
      controls: _wpcom_request_controls__WEBPACK_IMPORTED_MODULE_3__/* .controls */ .ai,
      reducer: _reducer__WEBPACK_IMPORTED_MODULE_4__/* ["default"] */ .Z,
      resolvers: _resolvers__WEBPACK_IMPORTED_MODULE_5__,
      selectors: _selectors__WEBPACK_IMPORTED_MODULE_6__
    });
  }

  return _constants__WEBPACK_IMPORTED_MODULE_1__/* .STORE_KEY */ .L;
}

/***/ }),

/***/ 3717:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* unused harmony export domainSuggestions */
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(9818);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(584);
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(4211);



const initialDomainSuggestionState = {
  state: _constants__WEBPACK_IMPORTED_MODULE_1__/* .DataStatus.Uninitialized */ .r.Uninitialized,
  data: {},
  errorMessage: null,
  lastUpdated: -Infinity,
  pendingSince: undefined
};
const domainSuggestions = function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : initialDomainSuggestionState;
  let action = arguments.length > 1 ? arguments[1] : undefined;

  if (action.type === 'FETCH_DOMAIN_SUGGESTIONS') {
    return { ...state,
      state: _constants__WEBPACK_IMPORTED_MODULE_1__/* .DataStatus.Pending */ .r.Pending,
      errorMessage: null,
      pendingSince: action.timeStamp
    };
  }

  if (action.type === 'RECEIVE_DOMAIN_SUGGESTIONS_SUCCESS') {
    return { ...state,
      state: _constants__WEBPACK_IMPORTED_MODULE_1__/* .DataStatus.Success */ .r.Success,
      data: { ...state.data,
        [(0,_utils__WEBPACK_IMPORTED_MODULE_2__/* .stringifyDomainQueryObject */ .Le)(action.queryObject)]: action.suggestions
      },
      errorMessage: null,
      lastUpdated: action.timeStamp,
      pendingSince: undefined
    };
  }

  if (action.type === 'RECEIVE_DOMAIN_SUGGESTIONS_ERROR') {
    return { ...state,
      state: _constants__WEBPACK_IMPORTED_MODULE_1__/* .DataStatus.Failure */ .r.Failure,
      errorMessage: action.errorMessage,
      lastUpdated: action.timeStamp,
      pendingSince: undefined
    };
  }

  return state;
};

const categories = function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
  let action = arguments.length > 1 ? arguments[1] : undefined;

  if (action.type === 'RECEIVE_CATEGORIES') {
    return action.categories;
  }

  return state;
};

const availability = function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  if (action.type === 'RECEIVE_DOMAIN_AVAILABILITY') {
    return { ...state,
      [action.domainName]: action.availability
    };
  }

  return state;
};

const reducer = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.combineReducers)({
  categories,
  domainSuggestions,
  availability
});
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (reducer);

/***/ }),

/***/ 2269:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "isAvailable": () => (/* binding */ isAvailable),
/* harmony export */   "getCategories": () => (/* binding */ getCategories),
/* harmony export */   "__internalGetDomainSuggestions": () => (/* binding */ __internalGetDomainSuggestions)
/* harmony export */ });
/* harmony import */ var i18n_calypso__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(1481);
/* harmony import */ var qs__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(9126);
/* harmony import */ var qs__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(qs__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var validator__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(6468);
/* harmony import */ var validator__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(validator__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(3661);
/* harmony import */ var _actions__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(3600);
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(4211);







function getAvailabilityURL(domainName) {
  return `https://public-api.wordpress.com/rest/v1.3/domains/${encodeURIComponent(domainName)}/is-available?is_cart_pre_check=true`;
}

function suggestionsLackThisFQDN(suggestions, domainName) {
  return validator__WEBPACK_IMPORTED_MODULE_1___default().isFQDN(domainName) && !suggestions.some(s => s.domain_name.toLowerCase() === domainName);
}

const isAvailable = function* isAvailable(domainName) {
  const url = getAvailabilityURL(domainName);

  try {
    const {
      body
    } = yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__/* .fetchAndParse */ .An)(url);
    return (0,_actions__WEBPACK_IMPORTED_MODULE_3__.receiveDomainAvailability)(domainName, body);
  } catch {
    // the API returns a status of 'unknown' if it can not accurately determine
    // availability, we will return the same status if the API request fails.
    return (0,_actions__WEBPACK_IMPORTED_MODULE_3__.receiveDomainAvailability)(domainName, {
      domain_name: domainName,
      mappable: 'unknown',
      status: 'unknown',
      supports_privacy: false
    });
  }
};
function* getCategories() {
  const {
    body
  } = yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__/* .fetchAndParse */ .An)('https://public-api.wordpress.com/wpcom/v2/onboarding/domains/categories');
  return (0,_actions__WEBPACK_IMPORTED_MODULE_3__.receiveCategories)(body);
}
function* __internalGetDomainSuggestions(queryObject) {
  // If normalized search string (`query`) contains no alphanumerics, endpoint 404s
  if (!queryObject.query) {
    return (0,_actions__WEBPACK_IMPORTED_MODULE_3__.receiveDomainSuggestionsError)('Empty query');
  }

  yield (0,_actions__WEBPACK_IMPORTED_MODULE_3__.fetchDomainSuggestions)();

  try {
    const suggestions = yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__/* .wpcomRequest */ ._9)({
      apiVersion: '1.1',
      path: '/domains/suggestions',
      query: (0,qs__WEBPACK_IMPORTED_MODULE_0__.stringify)(queryObject)
    });

    if (!Array.isArray(suggestions)) {
      // Other internal server errors
      return (0,_actions__WEBPACK_IMPORTED_MODULE_3__.receiveDomainSuggestionsError)((0,i18n_calypso__WEBPACK_IMPORTED_MODULE_4__/* .translate */ .Iu)('Invalid response from the server'));
    } // if the query is a FQDN and the results don't have it,
    // this implies that the user is searching for an unavailable domain name
    // TODO: query the availability endpoint to find the exact reason why it's unavailable
    // all the possible responses can be found here https://github.com/Automattic/wp-calypso/blob/trunk/client/lib/domains/registration/availability-messages.js#L40-L390


    if (suggestionsLackThisFQDN(suggestions, queryObject.query)) {
      const unavailableSuggestion = {
        domain_name: queryObject.query,
        unavailable: true,
        cost: '',
        raw_price: 0,
        currency_code: ''
      };
      suggestions.unshift(unavailableSuggestion);
    }

    const processedSuggestions = suggestions.map(suggestion => {
      if (suggestion.unavailable) {
        return suggestion;
      }

      return { ...suggestion,
        ...(suggestion.raw_price && suggestion.currency_code && {
          cost: (0,_utils__WEBPACK_IMPORTED_MODULE_5__/* .getFormattedPrice */ ._B)(suggestion.raw_price, suggestion.currency_code)
        })
      };
    });
    return (0,_actions__WEBPACK_IMPORTED_MODULE_3__.receiveDomainSuggestionsSuccess)(queryObject, processedSuggestions);
  } catch (e) {
    // e.g. no connection, or JSON parsing error
    return (0,_actions__WEBPACK_IMPORTED_MODULE_3__.receiveDomainSuggestionsError)(e.message || (0,i18n_calypso__WEBPACK_IMPORTED_MODULE_4__/* .translate */ .Iu)('Error while fetching server response'));
  }
}

/***/ }),

/***/ 267:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "getCategories": () => (/* binding */ getCategories),
/* harmony export */   "getDomainSuggestions": () => (/* binding */ getDomainSuggestions),
/* harmony export */   "getDomainState": () => (/* binding */ getDomainState),
/* harmony export */   "getDomainErrorMessage": () => (/* binding */ getDomainErrorMessage),
/* harmony export */   "isLoadingDomainSuggestions": () => (/* binding */ isLoadingDomainSuggestions),
/* harmony export */   "__internalGetDomainSuggestions": () => (/* binding */ __internalGetDomainSuggestions),
/* harmony export */   "isAvailable": () => (/* binding */ isAvailable),
/* harmony export */   "getDomainAvailabilities": () => (/* binding */ getDomainAvailabilities)
/* harmony export */ });
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(9818);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(584);
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(4211);



const getCategories = state => {
  // Sort domain categories by tier, then by title.
  return [...state.categories.filter(_ref => {
    let {
      tier
    } = _ref;
    return tier !== null;
  }).sort((a, b) => a > b ? 1 : -1), ...state.categories.filter(_ref2 => {
    let {
      tier
    } = _ref2;
    return tier === null;
  }).sort((a, b) => a.title.localeCompare(b.title))];
};
const getDomainSuggestions = function (_state, search) {
  let options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  const normalizedQuery = (0,_utils__WEBPACK_IMPORTED_MODULE_1__/* .normalizeDomainSuggestionQuery */ .o0)(search, options); // We need to go through the `select` store to get the resolver action

  return (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.select)(_constants__WEBPACK_IMPORTED_MODULE_2__/* .STORE_KEY */ .L).__internalGetDomainSuggestions(normalizedQuery);
};
const getDomainState = state => {
  return state.domainSuggestions.state;
};
const getDomainErrorMessage = state => {
  return state.domainSuggestions.errorMessage;
}; // TODO: reconcile this function with "Pending" status?
// It doesn't seem to be used

const isLoadingDomainSuggestions = function (_state, search) {
  let options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  const normalizedQuery = (0,_utils__WEBPACK_IMPORTED_MODULE_1__/* .normalizeDomainSuggestionQuery */ .o0)(search, options);
  return (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.select)('core/data').isResolving(_constants__WEBPACK_IMPORTED_MODULE_2__/* .STORE_KEY */ .L, '__internalGetDomainSuggestions', [normalizedQuery]);
};
/**
 * Do not use this selector. It is for internal use.
 *
 * @param state Store state
 * @param queryObject Normalized object representing the query
 * @returns suggestions
 */

const __internalGetDomainSuggestions = (state, queryObject) => {
  return state.domainSuggestions.data[(0,_utils__WEBPACK_IMPORTED_MODULE_1__/* .stringifyDomainQueryObject */ .Le)(queryObject)];
};
const isAvailable = (state, domainName) => {
  return state.availability[domainName];
};
const getDomainAvailabilities = state => {
  return state.availability;
};

/***/ }),

/***/ 4211:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Le": () => (/* binding */ stringifyDomainQueryObject),
/* harmony export */   "_B": () => (/* binding */ getFormattedPrice),
/* harmony export */   "o0": () => (/* binding */ normalizeDomainSuggestionQuery)
/* harmony export */ });
/* harmony import */ var _automattic_format_currency__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(2645);
/* harmony import */ var fast_json_stable_stringify__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(5695);
/* harmony import */ var fast_json_stable_stringify__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(fast_json_stable_stringify__WEBPACK_IMPORTED_MODULE_0__);



/**
 * Stable transform to an object key for storage and access.
 *
 * @see client/state/domains/suggestions/utils.js
 */
const stringifyDomainQueryObject = (fast_json_stable_stringify__WEBPACK_IMPORTED_MODULE_0___default());
/**
 * Formats the domain suggestion price according to 'format-currency' package rules
 * We use this for consistency in prices formats across plans and domains
 *
 * @param price the domain suggestion raw price
 * @param currencyCode the currency code to be used when formatting price
 */

function getFormattedPrice(price, currencyCode) {
  return (0,_automattic_format_currency__WEBPACK_IMPORTED_MODULE_1__/* ["default"] */ .ZP)(price, currencyCode, {
    stripZeros: true
  });
}
/**
 * Normalize domain query
 *
 * It's important to have a consistent, reproduceable representation of a domains query so that the result can be
 * stored and retrieved.
 *
 * @see client/state/domains/suggestions/utils.js
 * @see client/components/data/query-domains-suggestions/index.jsx
 * @param search       Domain search string
 * @param queryOptions Optional paramaters for the query
 * @returns Normalized query object
 */

function normalizeDomainSuggestionQuery(search, queryOptions) {
  return {
    // Defaults
    include_wordpressdotcom: queryOptions.only_wordpressdotcom || false,
    include_dotblogsubdomain: false,
    only_wordpressdotcom: false,
    quantity: 5,
    vendor: 'variation2_front',
    // Merge options
    ...queryOptions,
    // Add the search query
    query: search.trim().toLocaleLowerCase()
  };
}

/***/ }),

/***/ 9068:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "setFeatures": () => (/* binding */ setFeatures),
/* harmony export */   "setFeaturesByType": () => (/* binding */ setFeaturesByType),
/* harmony export */   "setPlans": () => (/* binding */ setPlans),
/* harmony export */   "setPlanProducts": () => (/* binding */ setPlanProducts),
/* harmony export */   "resetPlan": () => (/* binding */ resetPlan)
/* harmony export */ });
const setFeatures = (features, locale) => ({
  type: 'SET_FEATURES',
  features,
  locale
});
const setFeaturesByType = (featuresByType, locale) => ({
  type: 'SET_FEATURES_BY_TYPE',
  featuresByType,
  locale
});
const setPlans = (plans, locale) => ({
  type: 'SET_PLANS',
  plans,
  locale
});
const setPlanProducts = products => ({
  type: 'SET_PLAN_PRODUCTS',
  products
});
const resetPlan = () => ({
  type: 'RESET_PLAN'
});

/***/ }),

/***/ 4703:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Ls": () => (/* binding */ STORE_KEY),
/* harmony export */   "Ho": () => (/* binding */ FREE_PLAN_PRODUCT_ID),
/* harmony export */   "TO": () => (/* binding */ TIMELESS_PLAN_FREE),
/* harmony export */   "Gz": () => (/* binding */ TIMELESS_PLAN_PREMIUM),
/* harmony export */   "iL": () => (/* binding */ TIMELESS_PLAN_ECOMMERCE),
/* harmony export */   "AX": () => (/* binding */ DEFAULT_PAID_PLAN),
/* harmony export */   "bS": () => (/* binding */ annualSlugs),
/* harmony export */   "UB": () => (/* binding */ monthlySlugs),
/* harmony export */   "BV": () => (/* binding */ plansProductSlugs),
/* harmony export */   "nN": () => (/* binding */ FEATURE_IDS_THAT_REQUIRE_ANNUALLY_BILLED_PLAN)
/* harmony export */ });
/* unused harmony exports TIMELESS_PLAN_PERSONAL, TIMELESS_PLAN_BUSINESS, plansSlugs, PLAN_FREE, PLAN_PERSONAL, PLAN_PREMIUM, PLAN_BUSINESS, PLAN_ECOMMERCE, PLAN_PERSONAL_MONTHLY, PLAN_PREMIUM_MONTHLY, PLAN_BUSINESS_MONTHLY, PLAN_ECOMMERCE_MONTHLY, PLAN_ECOMMERCE_TRIAL_MONTHLY, PLAN_MONTHLY_PERIOD, PLAN_ANNUAL_PERIOD, PLAN_BIENNIAL_PERIOD, PLAN_TRIENNIAL_PERIOD, PERIOD_LIST */
const STORE_KEY = 'automattic/onboard/plans';
const FREE_PLAN_PRODUCT_ID = 1; // plans constants

const TIMELESS_PLAN_FREE = 'free';
const TIMELESS_PLAN_PERSONAL = 'personal';
const TIMELESS_PLAN_PREMIUM = 'premium';
const TIMELESS_PLAN_BUSINESS = 'business';
const TIMELESS_PLAN_ECOMMERCE = 'ecommerce';
const plansSlugs = [TIMELESS_PLAN_FREE, TIMELESS_PLAN_PERSONAL, TIMELESS_PLAN_PREMIUM, TIMELESS_PLAN_BUSINESS, TIMELESS_PLAN_ECOMMERCE];
const DEFAULT_PAID_PLAN = TIMELESS_PLAN_PREMIUM; // plan products constants

const PLAN_FREE = 'free_plan';
const PLAN_PERSONAL = 'personal-bundle';
const PLAN_PREMIUM = 'value_bundle';
const PLAN_BUSINESS = 'business-bundle';
const PLAN_ECOMMERCE = 'ecommerce-bundle';
const PLAN_PERSONAL_MONTHLY = 'personal-bundle-monthly';
const PLAN_PREMIUM_MONTHLY = 'value_bundle_monthly';
const PLAN_BUSINESS_MONTHLY = 'business-bundle-monthly';
const PLAN_ECOMMERCE_MONTHLY = 'ecommerce-bundle-monthly';
const PLAN_ECOMMERCE_TRIAL_MONTHLY = 'ecommerce-trial-bundle-monthly';
const annualSlugs = [PLAN_PERSONAL, PLAN_PREMIUM, PLAN_BUSINESS, PLAN_ECOMMERCE];
const monthlySlugs = [PLAN_PERSONAL_MONTHLY, PLAN_PREMIUM_MONTHLY, PLAN_BUSINESS_MONTHLY, PLAN_ECOMMERCE_MONTHLY, PLAN_ECOMMERCE_TRIAL_MONTHLY];
const plansProductSlugs = [PLAN_FREE, ...annualSlugs, ...monthlySlugs];
const FEATURE_IDS_THAT_REQUIRE_ANNUALLY_BILLED_PLAN = ['custom-domain', 'support-live', 'priority-support'];
const PLAN_MONTHLY_PERIOD = 31;
const PLAN_ANNUAL_PERIOD = 365;
const PLAN_BIENNIAL_PERIOD = 730;
const PLAN_TRIENNIAL_PERIOD = 1095;
const PERIOD_LIST = [PLAN_MONTHLY_PERIOD, PLAN_ANNUAL_PERIOD, PLAN_BIENNIAL_PERIOD, PLAN_TRIENNIAL_PERIOD];

/***/ }),

/***/ 9734:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "z2": () => (/* binding */ register)
/* harmony export */ });
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(9818);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wpcom_request_controls__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(3661);
/* harmony import */ var _actions__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(9068);
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(4703);
/* harmony import */ var _reducer__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(9438);
/* harmony import */ var _resolvers__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(5261);
/* harmony import */ var _selectors__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(7738);







// plansSlugs is a list with the identifiers for each plan and they are agnostic of billing period; eg: 'free', 'personal'
// plansSlugs is also used to construct the route that accepts plan slugs like '/free', '/personal', '/business'
// plansProductSlugs is a list with the identifiers for each plan product (including the billing period); eg: 'personal-bundle', 'personal-bundle-monthly'
// TIMELESS_* is the slug for each plan

let isRegistered = false;
function register() {
  if (!isRegistered) {
    isRegistered = true;
    (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.registerStore)(_constants__WEBPACK_IMPORTED_MODULE_1__/* .STORE_KEY */ .Ls, {
      resolvers: _resolvers__WEBPACK_IMPORTED_MODULE_2__,
      actions: _actions__WEBPACK_IMPORTED_MODULE_3__,
      controls: _wpcom_request_controls__WEBPACK_IMPORTED_MODULE_4__/* .controls */ .ai,
      reducer: _reducer__WEBPACK_IMPORTED_MODULE_5__/* ["default"] */ .ZP,
      selectors: _selectors__WEBPACK_IMPORTED_MODULE_6__
    });
  }

  return _constants__WEBPACK_IMPORTED_MODULE_1__/* .STORE_KEY */ .Ls;
}

/***/ }),

/***/ 9438:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "ZP": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* unused harmony exports features, featuresByType, plans, planProducts */
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(9818);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);

const features = function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'SET_FEATURES':
      return { ...state,
        [action.locale]: action.features
      };

    default:
      return state;
  }
};
const featuresByType = function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'SET_FEATURES_BY_TYPE':
      return { ...state,
        [action.locale]: action.featuresByType
      };

    default:
      return state;
  }
};
const plans = function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'SET_PLANS':
      return { ...state,
        [action.locale]: action.plans
      };

    default:
      return state;
  }
};
const planProducts = function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'SET_PLAN_PRODUCTS':
      return action.products;

    default:
      return state;
  }
};
const reducer = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.combineReducers)({
  features,
  featuresByType,
  planProducts,
  plans
});
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (reducer);

/***/ }),

/***/ 5261:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "getSupportedPlans": () => (/* binding */ getSupportedPlans)
/* harmony export */ });
/* harmony import */ var _automattic_format_currency__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(2645);
/* harmony import */ var qs__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(9126);
/* harmony import */ var qs__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(qs__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wpcom_request_controls__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(3661);
/* harmony import */ var _actions__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(9068);
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(4703);





const MONTHLY_PLAN_BILLING_PERIOD = 31;
/**
 * Calculates the monthly price of a plan
 * Annual plans are only priced yearly
 *
 * @param plan the plan object
 */

function getMonthlyPrice(plan) {
  return (0,_automattic_format_currency__WEBPACK_IMPORTED_MODULE_1__/* ["default"] */ .ZP)(plan.raw_price / 12, plan.currency_code, {
    stripZeros: true
  });
}
/**
 * Calculates the yearly price of a monthly plan
 *
 * @param plan the plan object
 */


function getAnnualPrice(plan) {
  return (0,_automattic_format_currency__WEBPACK_IMPORTED_MODULE_1__/* ["default"] */ .ZP)(plan.raw_price * 12, plan.currency_code, {
    stripZeros: true
  });
}
/**
 * Formats the plan price according to 'format-currency' package rules
 * We use this for consistency in prices formats across monthly and annual plans
 *
 * @param plan the plan object
 */


function getFormattedPrice(plan) {
  return (0,_automattic_format_currency__WEBPACK_IMPORTED_MODULE_1__/* ["default"] */ .ZP)(plan.raw_price, plan.currency_code, {
    stripZeros: true
  });
}

function calculateDiscounts(planProducts) {
  // calculate discounts
  for (let i = 0; i < _constants__WEBPACK_IMPORTED_MODULE_2__/* .annualSlugs.length */ .bS.length; i++) {
    const annualPlan = planProducts.find(plan => plan.storeSlug === _constants__WEBPACK_IMPORTED_MODULE_2__/* .annualSlugs */ .bS[i]);
    const monthlyPlan = planProducts.find(plan => plan.storeSlug === _constants__WEBPACK_IMPORTED_MODULE_2__/* .monthlySlugs */ .UB[i]);

    if (annualPlan && monthlyPlan) {
      const annualCostIfPaidMonthly = monthlyPlan.rawPrice * 12;
      const annualCostIfPaidAnnually = annualPlan.rawPrice;
      const discount = Math.floor(100 * (1 - annualCostIfPaidAnnually / annualCostIfPaidMonthly));
      annualPlan.annualDiscount = discount;
      monthlyPlan.annualDiscount = discount;
    }
  }
}

function processFeatures(features) {
  return features.reduce((features, feature) => {
    features[feature.id] = {
      id: feature.id,
      name: feature.name,
      description: feature.description,
      type: 'checkbox',
      requiresAnnuallyBilledPlan: _constants__WEBPACK_IMPORTED_MODULE_2__/* .FEATURE_IDS_THAT_REQUIRE_ANNUALLY_BILLED_PLAN.indexOf */ .nN.indexOf(feature.id) > -1
    };
    return features;
  }, {});
}

function featureRequiresAnnual(featureName, allFeaturesData) {
  const matchedFeatureId = Object.keys(allFeaturesData).find(featureId => allFeaturesData[featureId].name === featureName);

  if (matchedFeatureId) {
    return allFeaturesData[matchedFeatureId].requiresAnnuallyBilledPlan;
  }

  return false;
}

function processPlanFeatures(planData, allFeaturesData) {
  const features = planData.highlighted_features.map(featureName => ({
    name: featureName,
    requiresAnnuallyBilledPlan: featureRequiresAnnual(featureName, allFeaturesData)
  })); // Features requiring an annually billed plan should be first in the array.

  features.sort((a, b) => Number(b.requiresAnnuallyBilledPlan) - Number(a.requiresAnnuallyBilledPlan));
  return features;
}

function normalizePlanProducts(pricedPlans, periodAgnosticPlans) {
  const plansProducts = _constants__WEBPACK_IMPORTED_MODULE_2__/* .plansProductSlugs.reduce */ .BV.reduce((plans, slug) => {
    const planProduct = pricedPlans.find(pricedPlan => pricedPlan.product_slug === slug);

    if (!planProduct) {
      return plans;
    }

    const periodAgnosticPlan = periodAgnosticPlans.find(plan => plan.productIds.indexOf(planProduct.product_id) > -1);
    plans.push({
      productId: planProduct.product_id,
      // This means that free plan is considered "annually billed"
      billingPeriod: planProduct.bill_period === MONTHLY_PLAN_BILLING_PERIOD ? 'MONTHLY' : 'ANNUALLY',
      periodAgnosticSlug: periodAgnosticPlan.periodAgnosticSlug,
      storeSlug: planProduct.product_slug,
      rawPrice: planProduct.raw_price,
      // Not all plans returned from /plans have a `path_slug` (e.g. monthly plans don't have)
      pathSlug: planProduct.path_slug,
      price: (planProduct === null || planProduct === void 0 ? void 0 : planProduct.bill_period) === MONTHLY_PLAN_BILLING_PERIOD || planProduct.raw_price === 0 ? getFormattedPrice(planProduct) : getMonthlyPrice(planProduct),
      annualPrice: (planProduct === null || planProduct === void 0 ? void 0 : planProduct.bill_period) === MONTHLY_PLAN_BILLING_PERIOD ? getAnnualPrice(planProduct) : getFormattedPrice(planProduct)
    });
    return plans;
  }, []);
  calculateDiscounts(plansProducts);
  return plansProducts;
}

function* getSupportedPlans() {
  let locale = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'en';
  const pricedPlans = yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_3__/* .wpcomRequest */ ._9)({
    path: '/plans',
    query: (0,qs__WEBPACK_IMPORTED_MODULE_0__.stringify)({
      locale
    }),
    apiVersion: '1.5'
  });
  const {
    body: plansFeatures
  } = yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_3__/* .fetchAndParse */ .An)(`https://public-api.wordpress.com/wpcom/v2/plans/details?locale=${encodeURIComponent(locale)}`, {
    mode: 'cors',
    credentials: 'omit'
  });
  const features = processFeatures(plansFeatures.features);
  const periodAgnosticPlans = plansFeatures.plans.map(plan => {
    var _plan$nonlocalized_sh;

    const planSlug = (_plan$nonlocalized_sh = plan.nonlocalized_short_name) === null || _plan$nonlocalized_sh === void 0 ? void 0 : _plan$nonlocalized_sh.toLowerCase();
    return {
      description: plan.tagline,
      features: processPlanFeatures(plan, features),
      storage: plan.storage,
      title: plan.short_name,
      featuresSlugs: plan.features.reduce((slugs, slug) => {
        slugs[slug] = true;
        return slugs;
      }, {}),
      isFree: planSlug === _constants__WEBPACK_IMPORTED_MODULE_2__/* .TIMELESS_PLAN_FREE */ .TO,
      isPopular: planSlug === _constants__WEBPACK_IMPORTED_MODULE_2__/* .TIMELESS_PLAN_PREMIUM */ .Gz,
      periodAgnosticSlug: planSlug,
      productIds: plan.products.map(_ref => {
        let {
          plan_id
        } = _ref;
        return plan_id;
      })
    };
  });
  const planProducts = normalizePlanProducts(pricedPlans, periodAgnosticPlans);
  yield (0,_actions__WEBPACK_IMPORTED_MODULE_4__.setPlans)(periodAgnosticPlans, locale);
  yield (0,_actions__WEBPACK_IMPORTED_MODULE_4__.setPlanProducts)(planProducts);
  yield (0,_actions__WEBPACK_IMPORTED_MODULE_4__.setFeatures)(features, locale);
  yield (0,_actions__WEBPACK_IMPORTED_MODULE_4__.setFeaturesByType)(plansFeatures.features_by_type, locale);
}

/***/ }),

/***/ 7738:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "getFeatures": () => (/* binding */ getFeatures),
/* harmony export */   "getFeaturesByType": () => (/* binding */ getFeaturesByType),
/* harmony export */   "getPlanByProductId": () => (/* binding */ getPlanByProductId),
/* harmony export */   "getPlanProductById": () => (/* binding */ getPlanProductById),
/* harmony export */   "getPlanByPeriodAgnosticSlug": () => (/* binding */ getPlanByPeriodAgnosticSlug),
/* harmony export */   "getDefaultPaidPlan": () => (/* binding */ getDefaultPaidPlan),
/* harmony export */   "getDefaultFreePlan": () => (/* binding */ getDefaultFreePlan),
/* harmony export */   "getSupportedPlans": () => (/* binding */ getSupportedPlans),
/* harmony export */   "getPlansProducts": () => (/* binding */ getPlansProducts),
/* harmony export */   "getPrices": () => (/* binding */ getPrices),
/* harmony export */   "getPlanByPath": () => (/* binding */ getPlanByPath),
/* harmony export */   "getPlanProduct": () => (/* binding */ getPlanProduct),
/* harmony export */   "isPlanEcommerce": () => (/* binding */ isPlanEcommerce),
/* harmony export */   "isPlanFree": () => (/* binding */ isPlanFree),
/* harmony export */   "isPlanProductFree": () => (/* binding */ isPlanProductFree)
/* harmony export */ });
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(9818);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_deprecated__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(7180);
/* harmony import */ var _wordpress_deprecated__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_deprecated__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(4703);



// Some of these selectors require unused parameters because those
// params are used by the associated resolver.

/* eslint-disable @typescript-eslint/no-unused-vars */
const getFeatures = (state, locale) => state.features[locale] ?? {};
const getFeaturesByType = (state, locale) => state.featuresByType[locale] ?? [];
const getPlanByProductId = (_state, productId, locale) => {
  if (!productId) {
    return undefined;
  }

  return (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.select)(_constants__WEBPACK_IMPORTED_MODULE_2__/* .STORE_KEY */ .Ls).getSupportedPlans(locale).find(plan => plan.productIds.indexOf(productId) > -1);
};
const getPlanProductById = (_state, productId) => {
  if (!productId) {
    return undefined;
  }

  return (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.select)(_constants__WEBPACK_IMPORTED_MODULE_2__/* .STORE_KEY */ .Ls).getPlansProducts().find(product => product.productId === productId);
};
const getPlanByPeriodAgnosticSlug = (_state, slug, locale) => {
  if (!slug) {
    return undefined;
  }

  return (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.select)(_constants__WEBPACK_IMPORTED_MODULE_2__/* .STORE_KEY */ .Ls).getSupportedPlans(locale).find(plan => plan.periodAgnosticSlug === slug);
};
const getDefaultPaidPlan = (_, locale) => {
  return (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.select)(_constants__WEBPACK_IMPORTED_MODULE_2__/* .STORE_KEY */ .Ls).getSupportedPlans(locale).find(plan => plan.periodAgnosticSlug === _constants__WEBPACK_IMPORTED_MODULE_2__/* .DEFAULT_PAID_PLAN */ .AX);
};
const getDefaultFreePlan = (_, locale) => {
  return (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.select)(_constants__WEBPACK_IMPORTED_MODULE_2__/* .STORE_KEY */ .Ls).getSupportedPlans(locale).find(plan => plan.periodAgnosticSlug === _constants__WEBPACK_IMPORTED_MODULE_2__/* .TIMELESS_PLAN_FREE */ .TO);
};
const getSupportedPlans = (state, _locale) => {
  return state.plans[_locale] ?? [];
};
const getPlansProducts = state => {
  return state.planProducts;
};
/**
 * @deprecated  getPrices is deprecated, please use plan.price directly
 * @param _state the state
 * @param _locale the locale
 */

const getPrices = (_state, _locale) => {
  _wordpress_deprecated__WEBPACK_IMPORTED_MODULE_1___default()('getPrices', {
    alternative: 'getPlanProduct().price'
  });
  return (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.select)(_constants__WEBPACK_IMPORTED_MODULE_2__/* .STORE_KEY */ .Ls).getPlansProducts().reduce((prices, plan) => {
    prices[plan.storeSlug] = plan.price;
    return prices;
  }, {});
};
const getPlanByPath = (_state, path, locale) => {
  if (!path) {
    return undefined;
  }

  const planProduct = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.select)(_constants__WEBPACK_IMPORTED_MODULE_2__/* .STORE_KEY */ .Ls).getPlansProducts().find(product => product.pathSlug === path);

  if (!planProduct) {
    return undefined;
  }

  return (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.select)(_constants__WEBPACK_IMPORTED_MODULE_2__/* .STORE_KEY */ .Ls).getSupportedPlans(locale).find(plan => plan.periodAgnosticSlug === planProduct.periodAgnosticSlug);
};
const getPlanProduct = (_state, periodAgnosticSlug, billingPeriod) => {
  if (!periodAgnosticSlug || !billingPeriod) {
    return undefined;
  }

  return (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.select)(_constants__WEBPACK_IMPORTED_MODULE_2__/* .STORE_KEY */ .Ls).getPlansProducts().find(product => {
    const matchesSlug = product.periodAgnosticSlug === periodAgnosticSlug; // The billing period doesn't matter when dealing with free plan

    const matchesBillingPeriod = periodAgnosticSlug === _constants__WEBPACK_IMPORTED_MODULE_2__/* .TIMELESS_PLAN_FREE */ .TO || product.billingPeriod === billingPeriod;
    return matchesSlug && matchesBillingPeriod;
  });
};
const isPlanEcommerce = (_, planSlug) => {
  return planSlug === _constants__WEBPACK_IMPORTED_MODULE_2__/* .TIMELESS_PLAN_ECOMMERCE */ .iL;
};
const isPlanFree = (_, planSlug) => {
  return planSlug === _constants__WEBPACK_IMPORTED_MODULE_2__/* .TIMELESS_PLAN_FREE */ .TO;
};
const isPlanProductFree = (_, planProductId) => planProductId === _constants__WEBPACK_IMPORTED_MODULE_2__/* .FREE_PLAN_PRODUCT_ID */ .Ho;

/***/ }),

/***/ 6963:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "z": () => (/* binding */ registerPlugins)
/* harmony export */ });
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(9818);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _one_week_persistence_config__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(7246);


let isRegistered = false;
const registerPlugins = () => {
  if (isRegistered) {
    return;
  }

  isRegistered = true;
  /**
   * Register plugins for data-stores
   */

  (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.use)(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.plugins.persistence, _one_week_persistence_config__WEBPACK_IMPORTED_MODULE_1__/* ["default"] */ .Z);
};

/***/ }),

/***/ 7246:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/*
    Defines the options used for the @wp/data persistence plugin, 
    which include a persistent storage implementation to add data expiration handling.
*/
const storageKey = 'WPCOM_7_DAYS_PERSISTENCE';
const PERSISTENCE_INTERVAL = 7 * 24 * 3600000; // days * hours in days * ms in hour

const STORAGE_KEY = storageKey;
const STORAGE_TS_KEY = storageKey + '_TS'; // A plain object fallback if localStorage is not available

const objStore = {};
const objStorage = {
  getItem(key) {
    if (objStore.hasOwnProperty(key)) {
      return objStore[key];
    }

    return null;
  },

  setItem(key, value) {
    objStore[key] = String(value);
  },

  removeItem(key) {
    delete objStore[key];
  }

}; // Make sure localStorage support exists

const localStorageSupport = () => {
  try {
    window.localStorage.setItem('WP_ONBOARD_TEST', '1');
    window.localStorage.removeItem('WP_ONBOARD_TEST');
    return true;
  } catch (e) {
    return false;
  }
}; // Choose the right storage implementation


const storageHandler = localStorageSupport() ? window.localStorage : objStorage; // Persisted data expires after seven days

const isNotExpired = timestampStr => {
  const timestamp = Number(timestampStr);
  return Boolean(timestamp) && timestamp + PERSISTENCE_INTERVAL > Date.now();
}; // Check for "fresh" query param


const hasFreshParam = () => {
  return new URLSearchParams(window.location.search).has('fresh');
}; // Handle data expiration by providing a storage object override to the @wp/data persistence plugin.


const storage = {
  getItem(key) {
    const timestamp = storageHandler.getItem(STORAGE_TS_KEY);

    if (timestamp && isNotExpired(timestamp) && !hasFreshParam()) {
      return storageHandler.getItem(key);
    }

    storageHandler.removeItem(STORAGE_KEY);
    storageHandler.removeItem(STORAGE_TS_KEY);
    return null;
  },

  setItem(key, value) {
    storageHandler.setItem(STORAGE_TS_KEY, JSON.stringify(Date.now()));
    storageHandler.setItem(key, value);
  }

};
const persistOptions = {
  storageKey: STORAGE_KEY,
  storage
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (persistOptions);

/***/ }),

/***/ 8459:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "d": () => (/* binding */ createActions)
/* harmony export */ });
/* harmony import */ var _automattic_calypso_config__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(899);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(5736);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(3661);
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(2005);
/* harmony import */ var _types__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(9639);


const __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__;



function createActions(clientCreds) {
  const fetchSite = () => ({
    type: 'FETCH_SITE'
  });

  const fetchNewSite = () => ({
    type: 'FETCH_NEW_SITE'
  });

  const receiveNewSite = response => ({
    type: 'RECEIVE_NEW_SITE',
    response
  });

  const receiveNewSiteFailed = error => ({
    type: 'RECEIVE_NEW_SITE_FAILED',
    error
  });

  function* createSite(params) {
    yield fetchNewSite();

    try {
      const {
        authToken,
        ...providedParams
      } = params;
      const defaultParams = {
        client_id: clientCreds.client_id,
        client_secret: clientCreds.client_secret,
        // will find an available `*.wordpress.com` url based on the `blog_name`
        find_available_url: true,
        // Private site is default, but overridable, setting
        public: -1
      };
      const mergedParams = { ...defaultParams,
        ...providedParams,
        // Set to false because site validation should be a separate action
        validate: false
      };
      const newSite = yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__/* .wpcomRequest */ ._9)({
        path: '/sites/new',
        apiVersion: '1.1',
        method: 'post',
        body: mergedParams,
        token: authToken
      });
      yield receiveNewSite(newSite);
      return true;
    } catch (err) {
      yield receiveNewSiteFailed(err);
      return false;
    }
  }

  const receiveSite = (siteId, response) => ({
    type: 'RECEIVE_SITE',
    siteId,
    response
  });

  const receiveSiteTitle = (siteId, name) => ({
    type: 'RECEIVE_SITE_TITLE',
    siteId,
    name
  });

  const receiveSiteTagline = (siteId, tagline) => ({
    type: 'RECEIVE_SITE_TAGLINE',
    siteId,
    tagline
  });

  const receiveSiteVerticalId = (siteId, verticalId) => ({
    type: 'RECEIVE_SITE_VERTICAL_ID',
    siteId,
    verticalId
  });

  const receiveSiteFailed = (siteId, response) => ({
    type: 'RECEIVE_SITE_FAILED',
    siteId,
    response
  });

  const reset = () => ({
    type: 'RESET_SITE_STORE'
  });

  const resetNewSiteFailed = () => ({
    type: 'RESET_RECEIVE_NEW_SITE_FAILED'
  });

  const launchSiteStart = siteId => ({
    type: 'LAUNCH_SITE_START',
    siteId
  });

  const launchSiteSuccess = siteId => ({
    type: 'LAUNCH_SITE_SUCCESS',
    siteId
  });

  const launchSiteFailure = (siteId, error) => ({
    type: 'LAUNCH_SITE_FAILURE',
    siteId,
    error
  });

  function* launchSite(siteId) {
    yield launchSiteStart(siteId);

    try {
      yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__/* .wpcomRequest */ ._9)({
        path: `/sites/${siteId}/launch`,
        apiVersion: '1.1',
        method: 'post'
      });
      yield launchSiteSuccess(siteId);
    } catch (_) {
      yield launchSiteFailure(siteId, _types__WEBPACK_IMPORTED_MODULE_3__/* .SiteLaunchError.INTERNAL */ .Hc.INTERNAL);
    }
  } // TODO: move getCart and setCart to a 'cart' data-store


  function* getCart(siteId) {
    const success = yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__/* .wpcomRequest */ ._9)({
      path: '/me/shopping-cart/' + siteId,
      apiVersion: '1.1',
      method: 'GET'
    });
    return success;
  }

  const receiveSiteDomains = (siteId, domains) => ({
    type: 'RECEIVE_SITE_DOMAINS',
    siteId,
    domains
  });

  const receiveSiteTheme = (siteId, theme) => ({
    type: 'RECEIVE_SITE_THEME',
    siteId,
    theme
  });

  const receiveSiteSettings = (siteId, settings) => ({
    type: 'RECEIVE_SITE_SETTINGS',
    siteId,
    settings
  });

  const updateSiteSettings = (siteId, settings) => ({
    type: 'UPDATE_SITE_SETTINGS',
    siteId,
    settings
  });

  function* setCart(siteId, cartData) {
    const success = yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__/* .wpcomRequest */ ._9)({
      path: '/me/shopping-cart/' + siteId,
      apiVersion: '1.1',
      method: 'POST',
      body: cartData
    });
    return success;
  }

  const receiveSiteGlobalStyles = (siteId, globalStyles) => ({
    type: 'RECEIVE_SITE_GLOBAL_STYLES',
    siteId,
    globalStyles
  });

  function* getGlobalStyles(siteId, stylesheet) {
    const globalStyles = yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__/* .wpcomRequest */ ._9)({
      path: `/sites/${siteId}/global-styles/themes/${stylesheet}`,
      apiNamespace: 'wp/v2'
    });
    yield receiveSiteGlobalStyles(siteId, globalStyles);
    return globalStyles;
  }

  function* setGlobalStyles(siteIdOrSlug, stylesheet, globalStyles, activatedTheme) {
    // only update if there settings or styles to update
    if (Object.keys(globalStyles.settings ?? {}).length || Object.keys(globalStyles.styles ?? {}).length) {
      const globalStylesId = (activatedTheme === null || activatedTheme === void 0 ? void 0 : activatedTheme.global_styles_id) || (yield getGlobalStylesId(siteIdOrSlug, stylesheet));
      const updatedGlobalStyles = yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__/* .wpcomRequest */ ._9)({
        path: `/sites/${encodeURIComponent(siteIdOrSlug)}/global-styles/${globalStylesId}`,
        apiNamespace: 'wp/v2',
        method: 'POST',
        body: {
          id: globalStylesId,
          settings: globalStyles.settings ?? {},
          styles: globalStyles.styles ?? {}
        }
      });
      return updatedGlobalStyles;
    }
  }

  function* getGlobalStylesId(siteIdOrSlug, stylesheet) {
    var _theme$_links, _theme$_links$wpUser, _theme$_links$wpUser$;

    const theme = yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__/* .wpcomRequest */ ._9)({
      path: `/sites/${encodeURIComponent(siteIdOrSlug)}/themes/${stylesheet}`,
      method: 'GET',
      apiNamespace: 'wp/v2'
    });
    const globalStylesUrl = theme === null || theme === void 0 ? void 0 : (_theme$_links = theme._links) === null || _theme$_links === void 0 ? void 0 : (_theme$_links$wpUser = _theme$_links['wp:user-global-styles']) === null || _theme$_links$wpUser === void 0 ? void 0 : (_theme$_links$wpUser$ = _theme$_links$wpUser[0]) === null || _theme$_links$wpUser$ === void 0 ? void 0 : _theme$_links$wpUser$.href;

    if (globalStylesUrl) {
      // eslint-disable-next-line no-useless-escape
      const match = globalStylesUrl.match(/global-styles\/(?<id>[\/\w-]+)/);

      if (match && match.groups) {
        return match.groups.id;
      }
    }

    return null;
  }

  function* getGlobalStylesVariations(siteIdOrSlug, stylesheet) {
    const variations = yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__/* .wpcomRequest */ ._9)({
      path: `/sites/${encodeURIComponent(siteIdOrSlug)}/global-styles/themes/${stylesheet}/variations`,
      method: 'GET',
      apiNamespace: 'wp/v2'
    });
    return variations;
  }

  function* saveSiteSettings(siteId, settings) {
    try {
      // extract this into its own function as a generic settings setter
      yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__/* .wpcomRequest */ ._9)({
        path: `/sites/${encodeURIComponent(siteId)}/settings`,
        apiVersion: '1.4',
        body: settings,
        method: 'POST'
      });

      if ('blogname' in settings) {
        yield receiveSiteTitle(siteId, settings.blogname);
      }

      if ('blogdescription' in settings) {
        yield receiveSiteTagline(siteId, settings.blogdescription);
      }

      if ('site_vertical_id' in settings) {
        yield receiveSiteVerticalId(siteId, settings.site_vertical_id);
      }

      yield updateSiteSettings(siteId, settings);
    } catch (e) {}
  }

  function* setIntentOnSite(siteSlug, intent) {
    try {
      yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__/* .wpcomRequest */ ._9)({
        path: `/sites/${encodeURIComponent(siteSlug)}/site-intent`,
        apiNamespace: 'wpcom/v2',
        body: {
          site_intent: intent
        },
        method: 'POST'
      });
    } catch (e) {}
  }

  function* setStaticHomepageOnSite(siteID, pageId) {
    try {
      yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__/* .wpcomRequest */ ._9)({
        path: `/sites/${encodeURIComponent(siteID)}/homepage`,
        apiVersion: '1.1',
        body: {
          is_page_on_front: true,
          page_on_front_id: pageId
        },
        method: 'POST'
      });
    } catch (e) {}
  }

  function* setGoalsOnSite(siteSlug, goals) {
    try {
      yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__/* .wpcomRequest */ ._9)({
        path: `/sites/${encodeURIComponent(siteSlug)}/site-goals`,
        apiNamespace: 'wpcom/v2',
        body: {
          site_goals: goals
        },
        method: 'POST'
      });
    } catch (e) {}
  }

  function* saveSiteTitle(siteId, blogname) {
    yield saveSiteSettings(siteId, {
      blogname
    });
  }

  function* saveSiteTagline(siteId, blogdescription) {
    yield saveSiteSettings(siteId, {
      blogdescription
    });
  }

  function* installTheme(siteSlugOrId, themeSlug) {
    yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__/* .wpcomRequest */ ._9)({
      path: `/sites/${siteSlugOrId}/themes/${themeSlug}/install`,
      apiVersion: '1.1',
      method: 'POST'
    });
  }

  function* setThemeOnSite(siteSlug, theme, styleVariationSlug) {
    let keepHomepage = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : true;
    const activatedTheme = yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__/* .wpcomRequest */ ._9)({
      path: `/sites/${siteSlug}/themes/mine`,
      apiVersion: '1.1',
      body: {
        theme: theme,
        dont_change_homepage: keepHomepage
      },
      method: 'POST'
    });

    if (styleVariationSlug) {
      const variations = yield getGlobalStylesVariations(siteSlug, activatedTheme.stylesheet);
      const currentVariation = variations.find(variation => variation.title && variation.title.split(' ').join('-').toLowerCase() === styleVariationSlug);

      if (currentVariation) {
        yield setGlobalStyles(siteSlug, activatedTheme.stylesheet, currentVariation, activatedTheme);
      }
    }

    return activatedTheme;
  }

  function createCustomHomeTemplateContent(stylesheet, hasHeader, hasFooter, hasSections) {
    const content = [];

    if (hasHeader) {
      content.push(`<!-- wp:template-part {"slug":"header","tagName":"header","theme":"${stylesheet}"} /-->`);
    }

    if (hasSections) {
      content.push(`
	<!-- wp:group {"tagName":"main"} -->
		<main class="wp-block-group">
		</main>
	<!-- /wp:group -->`);
    }

    if (hasFooter) {
      content.push(`<!-- wp:template-part {"slug":"footer","tagName":"footer","theme":"${stylesheet}","className":"site-footer-container"} /-->`);
    }

    return content.join('\n');
  }

  function* runThemeSetupOnSite(siteSlug, selectedDesign, options) {
    const {
      recipe,
      verticalizable
    } = selectedDesign;
    /*
     * Anchor themes are set up directly via Headstart on the server side
     * so exclude them from theme setup.
     */

    const anchorDesigns = ['hannah', 'gilbert', 'riley'];

    if (anchorDesigns.indexOf(selectedDesign.template) >= 0) {
      return;
    }

    const themeSetupOptions = {
      trim_content: (options === null || options === void 0 ? void 0 : options.trimContent) ?? true
    };

    if (options !== null && options !== void 0 && options.posts_source_site_id) {
      themeSetupOptions.posts_source_site_id = options.posts_source_site_id;
    }

    if (verticalizable) {
      themeSetupOptions.vertical_id = options === null || options === void 0 ? void 0 : options.verticalId;
    }

    if (recipe !== null && recipe !== void 0 && recipe.pattern_ids) {
      themeSetupOptions.pattern_ids = recipe === null || recipe === void 0 ? void 0 : recipe.pattern_ids;
    }

    if (recipe !== null && recipe !== void 0 && recipe.header_pattern_ids) {
      themeSetupOptions.header_pattern_ids = recipe === null || recipe === void 0 ? void 0 : recipe.header_pattern_ids;
    }

    if (recipe !== null && recipe !== void 0 && recipe.footer_pattern_ids) {
      themeSetupOptions.footer_pattern_ids = recipe === null || recipe === void 0 ? void 0 : recipe.footer_pattern_ids;
    }

    const response = yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__/* .wpcomRequest */ ._9)({
      path: `/sites/${encodeURIComponent(siteSlug)}/theme-setup`,
      apiNamespace: 'wpcom/v2',
      body: themeSetupOptions,
      method: 'POST'
    });
    return response;
  }

  function* setDesignOnSite(siteSlug, selectedDesign, options) {
    var _selectedDesign$recip, _selectedDesign$recip2, _options$styleVariati;

    const theme = yield* setThemeOnSite(siteSlug, ((_selectedDesign$recip = selectedDesign.recipe) === null || _selectedDesign$recip === void 0 ? void 0 : (_selectedDesign$recip2 = _selectedDesign$recip.stylesheet) === null || _selectedDesign$recip2 === void 0 ? void 0 : _selectedDesign$recip2.split('/')[1]) || selectedDesign.theme, options === null || options === void 0 ? void 0 : (_options$styleVariati = options.styleVariation) === null || _options$styleVariati === void 0 ? void 0 : _options$styleVariati.slug);
    yield* runThemeSetupOnSite(siteSlug, selectedDesign, options);
    return theme;
  }

  function* createCustomTemplate(siteSlug, stylesheet, slug, title, content) {
    const templateId = `${stylesheet}//${slug}`;
    let existed = true;

    try {
      yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__/* .wpcomRequest */ ._9)({
        path: `/sites/${encodeURIComponent(siteSlug)}/templates/${templateId}`,
        apiNamespace: 'wp/v2',
        method: 'GET'
      });
    } catch {
      existed = false;
    }

    const templatePath = `templates/${existed ? templateId : ''}`;
    yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__/* .wpcomRequest */ ._9)({
      path: `/sites/${encodeURIComponent(siteSlug)}/${templatePath}`,
      apiNamespace: 'wp/v2',
      body: {
        slug,
        theme: stylesheet,
        title,
        content,
        status: 'publish',
        is_wp_suggestion: true
      },
      method: 'POST'
    });
  }

  function* applyThemeWithPatterns(siteSlug, design) {
    var _design$recipe, _design$recipe2, _design$recipe2$heade, _design$recipe3, _design$recipe3$foote, _design$recipe4, _design$recipe4$patte;

    let globalStyles = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
    let sourceSiteId = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : _constants__WEBPACK_IMPORTED_MODULE_4__/* .PLACEHOLDER_SITE_ID */ .K;
    const stylesheet = (design === null || design === void 0 ? void 0 : (_design$recipe = design.recipe) === null || _design$recipe === void 0 ? void 0 : _design$recipe.stylesheet) || '';
    const theme = (stylesheet === null || stylesheet === void 0 ? void 0 : stylesheet.split('/')[1]) || design.theme; // We have to switch theme first. Otherwise, the unique suffix might append to
    // the slug of newly created Home template if the current activated theme has
    // modified Home template.

    const activatedTheme = yield setThemeOnSite(siteSlug, theme, undefined, false);

    if ((0,_automattic_calypso_config__WEBPACK_IMPORTED_MODULE_0__/* .isEnabled */ ._k)('pattern-assembler/color-and-fonts') && globalStyles) {
      yield setGlobalStyles(siteSlug, stylesheet, globalStyles, activatedTheme);
    }

    const hasHeader = !!(design !== null && design !== void 0 && (_design$recipe2 = design.recipe) !== null && _design$recipe2 !== void 0 && (_design$recipe2$heade = _design$recipe2.header_pattern_ids) !== null && _design$recipe2$heade !== void 0 && _design$recipe2$heade.length);
    const hasFooter = !!(design !== null && design !== void 0 && (_design$recipe3 = design.recipe) !== null && _design$recipe3 !== void 0 && (_design$recipe3$foote = _design$recipe3.footer_pattern_ids) !== null && _design$recipe3$foote !== void 0 && _design$recipe3$foote.length);
    const hasSections = !!(design !== null && design !== void 0 && (_design$recipe4 = design.recipe) !== null && _design$recipe4 !== void 0 && (_design$recipe4$patte = _design$recipe4.pattern_ids) !== null && _design$recipe4$patte !== void 0 && _design$recipe4$patte.length);
    yield createCustomTemplate(siteSlug, stylesheet, 'home', __('Home'), createCustomHomeTemplateContent(stylesheet, hasHeader, hasFooter, hasSections));
    yield runThemeSetupOnSite(siteSlug, design, {
      trimContent: false,
      posts_source_site_id: sourceSiteId
    });
    return activatedTheme;
  }

  const setSiteSetupError = (error, message) => ({
    type: 'SET_SITE_SETUP_ERROR',
    error,
    message
  });

  const clearSiteSetupError = siteId => ({
    type: 'CLEAR_SITE_SETUP_ERROR',
    siteId
  });

  const atomicTransferStart = (siteId, softwareSet) => ({
    type: 'ATOMIC_TRANSFER_START',
    siteId,
    softwareSet
  });

  const atomicTransferSuccess = (siteId, softwareSet) => ({
    type: 'ATOMIC_TRANSFER_SUCCESS',
    siteId,
    softwareSet
  });

  const atomicTransferFailure = (siteId, softwareSet, error) => ({
    type: 'ATOMIC_TRANSFER_FAILURE',
    siteId,
    softwareSet,
    error
  });

  function* initiateAtomicTransfer(siteId, softwareSet) {
    yield atomicTransferStart(siteId, softwareSet);

    try {
      yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__/* .wpcomRequest */ ._9)({
        path: `/sites/${encodeURIComponent(siteId)}/atomic/transfers`,
        apiNamespace: 'wpcom/v2',
        method: 'POST',
        ...(softwareSet ? {
          body: {
            software_set: encodeURIComponent(softwareSet)
          }
        } : {})
      });
      yield atomicTransferSuccess(siteId, softwareSet);
    } catch (_) {
      yield atomicTransferFailure(siteId, softwareSet, _types__WEBPACK_IMPORTED_MODULE_3__/* .AtomicTransferError.INTERNAL */ .Hf.INTERNAL);
    }
  }

  const latestAtomicTransferStart = siteId => ({
    type: 'LATEST_ATOMIC_TRANSFER_START',
    siteId
  });

  const latestAtomicTransferSuccess = (siteId, transfer) => ({
    type: 'LATEST_ATOMIC_TRANSFER_SUCCESS',
    siteId,
    transfer
  });

  const latestAtomicTransferFailure = (siteId, error) => ({
    type: 'LATEST_ATOMIC_TRANSFER_FAILURE',
    siteId,
    error
  });

  function* requestLatestAtomicTransfer(siteId) {
    yield latestAtomicTransferStart(siteId);

    try {
      const transfer = yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__/* .wpcomRequest */ ._9)({
        path: `/sites/${encodeURIComponent(siteId)}/atomic/transfers/latest`,
        apiNamespace: 'wpcom/v2',
        method: 'GET'
      });
      yield latestAtomicTransferSuccess(siteId, transfer);
    } catch (err) {
      yield latestAtomicTransferFailure(siteId, err);
    }
  }

  const atomicSoftwareStatusStart = (siteId, softwareSet) => ({
    type: 'ATOMIC_SOFTWARE_STATUS_START',
    siteId,
    softwareSet
  });

  const atomicSoftwareStatusSuccess = (siteId, softwareSet, status) => ({
    type: 'ATOMIC_SOFTWARE_STATUS_SUCCESS',
    siteId,
    softwareSet,
    status
  });

  const atomicSoftwareStatusFailure = (siteId, softwareSet, error) => ({
    type: 'ATOMIC_SOFTWARE_STATUS_FAILURE',
    siteId,
    softwareSet,
    error
  });

  function* requestAtomicSoftwareStatus(siteId, softwareSet) {
    yield atomicSoftwareStatusStart(siteId, softwareSet);

    try {
      const status = yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__/* .wpcomRequest */ ._9)({
        path: `/sites/${encodeURIComponent(siteId)}/atomic/software/${encodeURIComponent(softwareSet)}`,
        apiNamespace: 'wpcom/v2',
        method: 'GET'
      });
      yield atomicSoftwareStatusSuccess(siteId, softwareSet, status);
    } catch (err) {
      yield atomicSoftwareStatusFailure(siteId, softwareSet, err);
    }
  }

  const atomicSoftwareInstallStart = (siteId, softwareSet) => ({
    type: 'ATOMIC_SOFTWARE_INSTALL_START',
    siteId,
    softwareSet
  });

  const atomicSoftwareInstallSuccess = (siteId, softwareSet) => ({
    type: 'ATOMIC_SOFTWARE_INSTALL_SUCCESS',
    siteId,
    softwareSet
  });

  const atomicSoftwareInstallFailure = (siteId, softwareSet, error) => ({
    type: 'ATOMIC_SOFTWARE_INSTALL_FAILURE',
    siteId,
    softwareSet,
    error
  });

  function* initiateSoftwareInstall(siteId, softwareSet) {
    yield atomicSoftwareInstallStart(siteId, softwareSet);

    try {
      yield (0,_wpcom_request_controls__WEBPACK_IMPORTED_MODULE_2__/* .wpcomRequest */ ._9)({
        path: `/sites/${encodeURIComponent(siteId)}/atomic/software/${encodeURIComponent(softwareSet)}`,
        apiNamespace: 'wpcom/v2',
        method: 'POST',
        body: {}
      });
      yield atomicSoftwareInstallSuccess(siteId, softwareSet);
    } catch (err) {
      yield atomicSoftwareInstallFailure(siteId, softwareSet, err);
    }
  }

  const setBundledPluginSlug = (siteSlug, pluginSlug) => ({
    type: 'SET_BUNDLED_PLUGIN_SLUG',
    siteSlug,
    pluginSlug
  });

  return {
    receiveSiteDomains,
    receiveSiteSettings,
    receiveSiteTheme,
    saveSiteTitle,
    saveSiteSettings,
    setIntentOnSite,
    setStaticHomepageOnSite,
    setGoalsOnSite,
    receiveSiteTitle,
    fetchNewSite,
    fetchSite,
    receiveNewSite,
    receiveNewSiteFailed,
    resetNewSiteFailed,
    installTheme,
    setThemeOnSite,
    runThemeSetupOnSite,
    setDesignOnSite,
    createCustomTemplate,
    applyThemeWithPatterns,
    createSite,
    receiveSite,
    receiveSiteFailed,
    receiveSiteTagline,
    receiveSiteVerticalId,
    updateSiteSettings,
    saveSiteTagline,
    reset,
    launchSite,
    launchSiteStart,
    launchSiteSuccess,
    launchSiteFailure,
    getCart,
    setCart,
    getGlobalStyles,
    setGlobalStyles,
    receiveSiteGlobalStyles,
    setSiteSetupError,
    clearSiteSetupError,
    initiateAtomicTransfer,
    atomicTransferStart,
    atomicTransferSuccess,
    atomicTransferFailure,
    latestAtomicTransferStart,
    latestAtomicTransferSuccess,
    latestAtomicTransferFailure,
    requestLatestAtomicTransfer,
    atomicSoftwareStatusStart,
    atomicSoftwareStatusSuccess,
    atomicSoftwareStatusFailure,
    requestAtomicSoftwareStatus,
    initiateSoftwareInstall,
    atomicSoftwareInstallStart,
    atomicSoftwareInstallSuccess,
    atomicSoftwareInstallFailure,
    setBundledPluginSlug
  };
}

/***/ }),

/***/ 2005:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "L": () => (/* binding */ STORE_KEY),
/* harmony export */   "K": () => (/* binding */ PLACEHOLDER_SITE_ID)
/* harmony export */ });
const STORE_KEY = 'automattic/site';
const PLACEHOLDER_SITE_ID = 211865921; // blankcanvas3demo.wordpress.com

/***/ }),

/***/ 2369:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "z2": () => (/* binding */ register)
/* harmony export */ });
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(9818);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _plugins__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(6963);
/* harmony import */ var _wpcom_request_controls__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(3661);
/* harmony import */ var _actions__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(8459);
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(2005);
/* harmony import */ var _reducer__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(2701);
/* harmony import */ var _resolvers__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(7862);
/* harmony import */ var _selectors__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(4309);










let isRegistered = false;
function register(clientCreds) {
  if (!isRegistered) {
    (0,_plugins__WEBPACK_IMPORTED_MODULE_1__/* .registerPlugins */ .z)();
    isRegistered = true;
    (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.registerStore)(_constants__WEBPACK_IMPORTED_MODULE_2__/* .STORE_KEY */ .L, {
      actions: (0,_actions__WEBPACK_IMPORTED_MODULE_3__/* .createActions */ .d)(clientCreds),
      controls: _wpcom_request_controls__WEBPACK_IMPORTED_MODULE_4__/* .controls */ .ai,
      reducer: _reducer__WEBPACK_IMPORTED_MODULE_5__/* ["default"] */ .ZP,
      resolvers: _resolvers__WEBPACK_IMPORTED_MODULE_6__,
      selectors: _selectors__WEBPACK_IMPORTED_MODULE_7__,
      persist: ['bundledPluginSlug']
    });
  }

  return _constants__WEBPACK_IMPORTED_MODULE_2__/* .STORE_KEY */ .L;
}

/***/ }),

/***/ 2701:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "ZP": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* unused harmony exports newSiteData, newSiteError, isFetchingSite, fetchingSiteError, isFetchingSiteDetails, sites, sitesDomains, sitesSettings, siteTheme, sitesGlobalStyles, launchStatus, siteSetupErrors, atomicTransferStatus, latestAtomicTransferStatus, atomicSoftwareStatus, atomicSoftwareInstallStatus */
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(9818);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _types__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(9639);



const newSiteData = (state, action) => {
  if (action.type === 'RECEIVE_NEW_SITE') {
    const {
      response
    } = action;
    return response.blog_details;
  } else if (action.type === 'RECEIVE_NEW_SITE_FAILED') {
    return undefined;
  } else if (action.type === 'RESET_SITE_STORE') {
    return undefined;
  }

  return state;
};
const newSiteError = (state, action) => {
  switch (action.type) {
    case 'FETCH_NEW_SITE':
    case 'RECEIVE_NEW_SITE':
    case 'RESET_SITE_STORE':
    case 'RESET_RECEIVE_NEW_SITE_FAILED':
      return undefined;

    case 'RECEIVE_NEW_SITE_FAILED':
      return {
        error: action.error.error,
        status: action.error.status,
        statusCode: action.error.statusCode,
        name: action.error.name,
        message: action.error.message
      };
  }

  return state;
};
const isFetchingSite = function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'FETCH_NEW_SITE':
      return true;

    case 'RECEIVE_NEW_SITE':
    case 'RECEIVE_NEW_SITE_FAILED':
    case 'RESET_SITE_STORE':
    case 'RESET_RECEIVE_NEW_SITE_FAILED':
      return false;
  }

  return state;
};
const fetchingSiteError = (state, action) => {
  switch (action.type) {
    case 'RECEIVE_SITE_FAILED':
      return {
        error: action.response.error,
        message: action.response.message
      };
  }

  return state;
};
const isFetchingSiteDetails = function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
  let action = arguments.length > 1 ? arguments[1] : undefined;

  switch (action.type) {
    case 'FETCH_SITE':
      return true;

    case 'RECEIVE_SITE':
    case 'RECEIVE_SITE_FAILED':
      return false;
  }

  return state;
};
const sites = function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  if (action.type === 'RECEIVE_SITE') {
    if (action.response) {
      return { ...state,
        [action.response.ID]: action.response
      };
    }

    return state;
  } else if (action.type === 'RECEIVE_SITE_FAILED') {
    const {
      [action.siteId]: idToBeRemoved,
      ...remainingState
    } = state;
    return { ...remainingState
    };
  } else if (action.type === 'RESET_SITE_STORE') {
    return {};
  } else if (action.type === 'RECEIVE_SITE_TITLE') {
    return { ...state,
      [action.siteId]: { ...state[action.siteId],
        name: action.name
      }
    };
  } else if (action.type === 'RECEIVE_SITE_TAGLINE') {
    return { ...state,
      [action.siteId]: { ...state[action.siteId],
        description: action.tagline ?? ''
      }
    };
  } else if (action.type === 'RECEIVE_SITE_VERTICAL_ID') {
    var _state$action$siteId;

    return { ...state,
      [action.siteId]: { ...state[action.siteId],
        options: { ...((_state$action$siteId = state[action.siteId]) === null || _state$action$siteId === void 0 ? void 0 : _state$action$siteId.options),
          site_vertical_id: action.verticalId
        }
      }
    };
  }

  return state;
};
const sitesDomains = function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  if (action.type === 'RECEIVE_SITE_DOMAINS') {
    return { ...state,
      [action.siteId]: action.domains
    };
  }

  return state;
};
const sitesSettings = function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  if (action.type === 'RECEIVE_SITE_SETTINGS') {
    return { ...state,
      [action.siteId]: action.settings
    };
  }

  if (action.type === 'UPDATE_SITE_SETTINGS') {
    return { ...state,
      [action.siteId]: { ...(state === null || state === void 0 ? void 0 : state[action.siteId]),
        ...action.settings
      }
    };
  }

  return state;
};
const siteTheme = function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  if (action.type === 'RECEIVE_SITE_THEME') {
    return { ...state,
      [action.siteId]: action.theme
    };
  }

  return state;
};
const sitesGlobalStyles = function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  if (action.type === 'RECEIVE_SITE_GLOBAL_STYLES') {
    return { ...state,
      [action.siteId]: { ...(state === null || state === void 0 ? void 0 : state[action.siteId]),
        ...action.globalStyles
      }
    };
  }

  return state;
};
const launchStatus = function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  if (action.type === 'LAUNCH_SITE_START') {
    return { ...state,
      [action.siteId]: {
        status: _types__WEBPACK_IMPORTED_MODULE_1__/* .SiteLaunchStatus.IN_PROGRESS */ .uS.IN_PROGRESS,
        errorCode: undefined
      }
    };
  }

  if (action.type === 'LAUNCH_SITE_SUCCESS') {
    return { ...state,
      [action.siteId]: {
        status: _types__WEBPACK_IMPORTED_MODULE_1__/* .SiteLaunchStatus.SUCCESS */ .uS.SUCCESS,
        errorCode: undefined
      }
    };
  }

  if (action.type === 'LAUNCH_SITE_FAILURE') {
    return { ...state,
      [action.siteId]: {
        status: _types__WEBPACK_IMPORTED_MODULE_1__/* .SiteLaunchStatus.FAILURE */ .uS.FAILURE,
        errorCode: action.error
      }
    };
  }

  return state;
};
const siteSetupErrors = function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  if (action.type === 'SET_SITE_SETUP_ERROR') {
    const {
      error,
      message
    } = action;
    return {
      error,
      message
    };
  }

  if (action.type === 'CLEAR_SITE_SETUP_ERROR') {
    return {};
  }

  return state;
};
const atomicTransferStatus = function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  if (action.type === 'ATOMIC_TRANSFER_START') {
    return { ...state,
      [action.siteId]: {
        status: _types__WEBPACK_IMPORTED_MODULE_1__/* .AtomicTransferStatus.IN_PROGRESS */ .bc.IN_PROGRESS,
        softwareSet: action.softwareSet,
        errorCode: undefined
      }
    };
  }

  if (action.type === 'ATOMIC_TRANSFER_SUCCESS') {
    return { ...state,
      [action.siteId]: {
        status: _types__WEBPACK_IMPORTED_MODULE_1__/* .AtomicTransferStatus.SUCCESS */ .bc.SUCCESS,
        softwareSet: action.softwareSet,
        errorCode: undefined
      }
    };
  }

  if (action.type === 'ATOMIC_TRANSFER_FAILURE') {
    return { ...state,
      [action.siteId]: {
        status: _types__WEBPACK_IMPORTED_MODULE_1__/* .AtomicTransferStatus.FAILURE */ .bc.FAILURE,
        softwareSet: action.softwareSet,
        errorCode: action.error
      }
    };
  }

  return state;
};
const latestAtomicTransferStatus = function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  if (action.type === 'LATEST_ATOMIC_TRANSFER_START') {
    return { ...state,
      [action.siteId]: {
        status: _types__WEBPACK_IMPORTED_MODULE_1__/* .LatestAtomicTransferStatus.IN_PROGRESS */ .O.IN_PROGRESS,
        transfer: undefined,
        errorCode: undefined
      }
    };
  }

  if (action.type === 'LATEST_ATOMIC_TRANSFER_SUCCESS') {
    return { ...state,
      [action.siteId]: {
        status: _types__WEBPACK_IMPORTED_MODULE_1__/* .LatestAtomicTransferStatus.SUCCESS */ .O.SUCCESS,
        transfer: action.transfer,
        errorCode: undefined
      }
    };
  }

  if (action.type === 'LATEST_ATOMIC_TRANSFER_FAILURE') {
    return { ...state,
      [action.siteId]: {
        status: _types__WEBPACK_IMPORTED_MODULE_1__/* .LatestAtomicTransferStatus.FAILURE */ .O.FAILURE,
        transfer: undefined,
        errorCode: action.error
      }
    };
  }

  return state;
};
const atomicSoftwareStatus = function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  if (action.type === 'ATOMIC_SOFTWARE_STATUS_START') {
    return { ...state,
      [action.siteId]: {
        [action.softwareSet]: {
          status: undefined,
          error: undefined
        }
      }
    };
  }

  if (action.type === 'ATOMIC_SOFTWARE_STATUS_SUCCESS') {
    return { ...state,
      [action.siteId]: {
        [action.softwareSet]: {
          status: action.status,
          error: undefined
        }
      }
    };
  }

  if (action.type === 'ATOMIC_SOFTWARE_STATUS_FAILURE') {
    return { ...state,
      [action.siteId]: {
        [action.softwareSet]: {
          status: undefined,
          error: action.error
        }
      }
    };
  }

  return state;
};
const atomicSoftwareInstallStatus = function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  if (action.type === 'ATOMIC_SOFTWARE_INSTALL_START') {
    return { ...state,
      [action.siteId]: {
        [action.softwareSet]: {
          status: _types__WEBPACK_IMPORTED_MODULE_1__/* .AtomicSoftwareInstallStatus.IN_PROGRESS */ .sw.IN_PROGRESS,
          error: undefined
        }
      }
    };
  }

  if (action.type === 'ATOMIC_SOFTWARE_INSTALL_SUCCESS') {
    return { ...state,
      [action.siteId]: {
        [action.softwareSet]: {
          status: _types__WEBPACK_IMPORTED_MODULE_1__/* .AtomicSoftwareInstallStatus.SUCCESS */ .sw.SUCCESS,
          error: undefined
        }
      }
    };
  }

  if (action.type === 'ATOMIC_SOFTWARE_INSTALL_FAILURE') {
    return { ...state,
      [action.siteId]: {
        [action.softwareSet]: {
          status: _types__WEBPACK_IMPORTED_MODULE_1__/* .AtomicSoftwareInstallStatus.FAILURE */ .sw.FAILURE,
          error: action.error
        }
      }
    };
  }

  return state;
};

const bundledPluginSlug = function () {
  let state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  let action = arguments.length > 1 ? arguments[1] : undefined;

  if (action.type === 'SET_BUNDLED_PLUGIN_SLUG') {
    return { ...state,
      [action.siteSlug]: action.pluginSlug
    };
  }

  if (action.type === 'RESET_SITE_STORE') {
    return {};
  }

  return state;
};

const newSite = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.combineReducers)({
  data: newSiteData,
  error: newSiteError,
  isFetching: isFetchingSite
});
const reducer = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.combineReducers)({
  isFetchingSiteDetails,
  newSite,
  fetchingSiteError,
  sites,
  launchStatus,
  sitesDomains,
  sitesSettings,
  siteTheme,
  sitesGlobalStyles,
  siteSetupErrors,
  atomicTransferStatus,
  latestAtomicTransferStatus,
  atomicSoftwareStatus,
  atomicSoftwareInstallStatus,
  bundledPluginSlug
});
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (reducer);

/***/ }),

/***/ 7862:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "getSite": () => (/* binding */ getSite),
/* harmony export */   "getSiteDomains": () => (/* binding */ getSiteDomains),
/* harmony export */   "getSiteSettings": () => (/* binding */ getSiteSettings),
/* harmony export */   "getSiteTheme": () => (/* binding */ getSiteTheme)
/* harmony export */ });
/* harmony import */ var wpcom_proxy_request__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(8552);
// wpcomRequest is a temporary rename while we're working on migrating generators to thunks


/**
 * Attempt to find a site based on its id, and if not return undefined.
 * We are currently ignoring error messages and silently failing if we can't find a
 * site. This could be extended in the future by retrieving the `error` and
 * `message` strings returned by the API.
 *
 * @param siteId {number}	The site to look up
 */
const getSite = siteId => async _ref => {
  let {
    dispatch
  } = _ref;
  dispatch.fetchSite();

  try {
    const existingSite = await (0,wpcom_proxy_request__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .ZP)({
      path: '/sites/' + encodeURIComponent(siteId),
      apiVersion: '1.1'
    });
    dispatch.receiveSite(siteId, existingSite);
  } catch (err) {
    dispatch.receiveSiteFailed(siteId, err);
  }
};
/**
 * Get all site domains
 *
 * @param siteId {number} The site id
 */

const getSiteDomains = siteId => async _ref2 => {
  let {
    dispatch
  } = _ref2;
  const result = await (0,wpcom_proxy_request__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .ZP)({
    path: '/sites/' + encodeURIComponent(siteId) + '/domains',
    apiVersion: '1.2'
  });
  dispatch.receiveSiteDomains(siteId, result === null || result === void 0 ? void 0 : result.domains);
};
/**
 * Get all site settings
 *
 * @param siteId {number} The site id
 */

const getSiteSettings = siteId => async _ref3 => {
  let {
    dispatch
  } = _ref3;
  const result = await (0,wpcom_proxy_request__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .ZP)({
    path: '/sites/' + encodeURIComponent(siteId) + '/settings',
    apiVersion: '1.4'
  });
  dispatch.receiveSiteSettings(siteId, result === null || result === void 0 ? void 0 : result.settings);
};
/**
 * Get current site theme
 *
 * @param siteId {number} The site id
 */

const getSiteTheme = siteId => async _ref4 => {
  let {
    dispatch
  } = _ref4;
  const theme = await (0,wpcom_proxy_request__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .ZP)({
    path: '/sites/' + encodeURIComponent(siteId) + '/themes/mine',
    apiVersion: '1.1'
  });
  dispatch.receiveSiteTheme(siteId, theme);
};

/***/ }),

/***/ 4309:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "getState": () => (/* binding */ getState),
/* harmony export */   "getNewSite": () => (/* binding */ getNewSite),
/* harmony export */   "getNewSiteError": () => (/* binding */ getNewSiteError),
/* harmony export */   "isFetchingSite": () => (/* binding */ isFetchingSite),
/* harmony export */   "getFetchingSiteError": () => (/* binding */ getFetchingSiteError),
/* harmony export */   "isFetchingSiteDetails": () => (/* binding */ isFetchingSiteDetails),
/* harmony export */   "isNewSite": () => (/* binding */ isNewSite),
/* harmony export */   "getSite": () => (/* binding */ getSite),
/* harmony export */   "getSiteIdBySlug": () => (/* binding */ getSiteIdBySlug),
/* harmony export */   "getSiteTitle": () => (/* binding */ getSiteTitle),
/* harmony export */   "getSiteVerticalId": () => (/* binding */ getSiteVerticalId),
/* harmony export */   "isSiteLaunched": () => (/* binding */ isSiteLaunched),
/* harmony export */   "isSiteLaunching": () => (/* binding */ isSiteLaunching),
/* harmony export */   "isSiteAtomic": () => (/* binding */ isSiteAtomic),
/* harmony export */   "isSiteWPForTeams": () => (/* binding */ isSiteWPForTeams),
/* harmony export */   "getSiteDomains": () => (/* binding */ getSiteDomains),
/* harmony export */   "getSiteSettings": () => (/* binding */ getSiteSettings),
/* harmony export */   "getSiteTheme": () => (/* binding */ getSiteTheme),
/* harmony export */   "getSiteGlobalStyles": () => (/* binding */ getSiteGlobalStyles),
/* harmony export */   "getSiteSetupError": () => (/* binding */ getSiteSetupError),
/* harmony export */   "getSiteOptions": () => (/* binding */ getSiteOptions),
/* harmony export */   "getSiteOption": () => (/* binding */ getSiteOption),
/* harmony export */   "getPrimarySiteDomain": () => (/* binding */ getPrimarySiteDomain),
/* harmony export */   "getSiteSubdomain": () => (/* binding */ getSiteSubdomain),
/* harmony export */   "getSiteLatestAtomicTransfer": () => (/* binding */ getSiteLatestAtomicTransfer),
/* harmony export */   "getSiteLatestAtomicTransferError": () => (/* binding */ getSiteLatestAtomicTransferError),
/* harmony export */   "getAtomicSoftwareStatus": () => (/* binding */ getAtomicSoftwareStatus),
/* harmony export */   "getAtomicSoftwareError": () => (/* binding */ getAtomicSoftwareError),
/* harmony export */   "getAtomicSoftwareInstallError": () => (/* binding */ getAtomicSoftwareInstallError),
/* harmony export */   "siteHasFeature": () => (/* binding */ siteHasFeature),
/* harmony export */   "requiresUpgrade": () => (/* binding */ requiresUpgrade),
/* harmony export */   "isJetpackSite": () => (/* binding */ isJetpackSite),
/* harmony export */   "isEligibleForProPlan": () => (/* binding */ isEligibleForProPlan),
/* harmony export */   "getBundledPluginSlug": () => (/* binding */ getBundledPluginSlug)
/* harmony export */ });
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(9818);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(2005);
/* harmony import */ var _types__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(9639);



const getState = state => state;
const getNewSite = state => state.newSite.data;
const getNewSiteError = state => state.newSite.error;
const isFetchingSite = state => state.newSite.isFetching;
const getFetchingSiteError = state => state.fetchingSiteError;
const isFetchingSiteDetails = state => state.isFetchingSiteDetails;
const isNewSite = state => !!state.newSite.data;
/**
 * Get a site matched by id. This selector has a matching
 * resolver that uses the `siteId` parameter to fetch an existing site. If the
 * site cannot be found, invalidate the resolution cache.
 *
 * @param state {State}		state object
 * @param siteId {number}	id of the site to look up
 */

const getSite = (state, siteId) => {
  return (// Try matching numeric site ID
    state.sites[siteId] || // Then try matching primary domain
    Object.values(state.sites).find(site => site && new URL(site.URL).host === siteId) || // Then try matching second domain
    Object.values(state.sites).find(site => {
      var _site$options;

      return (site === null || site === void 0 ? void 0 : (_site$options = site.options) === null || _site$options === void 0 ? void 0 : _site$options.unmapped_url) && new URL(site.options.unmapped_url).host === siteId;
    })
  );
};
const getSiteIdBySlug = (_, slug) => {
  var _getSite;

  return (_getSite = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.select)(_constants__WEBPACK_IMPORTED_MODULE_1__/* .STORE_KEY */ .L).getSite(slug)) === null || _getSite === void 0 ? void 0 : _getSite.ID;
};
const getSiteTitle = (_, siteId) => {
  var _getSite2;

  return (_getSite2 = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.select)(_constants__WEBPACK_IMPORTED_MODULE_1__/* .STORE_KEY */ .L).getSite(siteId)) === null || _getSite2 === void 0 ? void 0 : _getSite2.name;
};
const getSiteVerticalId = (_, siteId) => {
  var _getSite3, _getSite3$options;

  return (_getSite3 = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.select)(_constants__WEBPACK_IMPORTED_MODULE_1__/* .STORE_KEY */ .L).getSite(siteId)) === null || _getSite3 === void 0 ? void 0 : (_getSite3$options = _getSite3.options) === null || _getSite3$options === void 0 ? void 0 : _getSite3$options.site_vertical_id;
}; // @TODO: Return LaunchStatus instead of a boolean

const isSiteLaunched = (state, siteId) => {
  var _state$launchStatus$s;

  return ((_state$launchStatus$s = state.launchStatus[siteId]) === null || _state$launchStatus$s === void 0 ? void 0 : _state$launchStatus$s.status) === _types__WEBPACK_IMPORTED_MODULE_2__/* .SiteLaunchStatus.SUCCESS */ .uS.SUCCESS;
}; // @TODO: Return LaunchStatus instead of a boolean

const isSiteLaunching = (state, siteId) => {
  var _state$launchStatus$s2;

  return ((_state$launchStatus$s2 = state.launchStatus[siteId]) === null || _state$launchStatus$s2 === void 0 ? void 0 : _state$launchStatus$s2.status) === _types__WEBPACK_IMPORTED_MODULE_2__/* .SiteLaunchStatus.IN_PROGRESS */ .uS.IN_PROGRESS;
};
const isSiteAtomic = (state, siteId) => {
  var _getSite4, _getSite4$options;

  return ((_getSite4 = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.select)(_constants__WEBPACK_IMPORTED_MODULE_1__/* .STORE_KEY */ .L).getSite(siteId)) === null || _getSite4 === void 0 ? void 0 : (_getSite4$options = _getSite4.options) === null || _getSite4$options === void 0 ? void 0 : _getSite4$options.is_wpcom_atomic) === true;
};
const isSiteWPForTeams = (state, siteId) => {
  var _getSite5, _getSite5$options;

  return ((_getSite5 = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.select)(_constants__WEBPACK_IMPORTED_MODULE_1__/* .STORE_KEY */ .L).getSite(siteId)) === null || _getSite5 === void 0 ? void 0 : (_getSite5$options = _getSite5.options) === null || _getSite5$options === void 0 ? void 0 : _getSite5$options.is_wpforteams_site) === true;
};
const getSiteDomains = (state, siteId) => {
  return state.sitesDomains[siteId];
};
const getSiteSettings = (state, siteId) => {
  return state.sitesSettings[siteId];
};
const getSiteTheme = (state, siteId) => {
  return state.siteTheme[siteId];
};
const getSiteGlobalStyles = (state, siteId) => {
  return state.sitesGlobalStyles[siteId];
};
const getSiteSetupError = state => {
  return state.siteSetupErrors;
};
const getSiteOptions = (state, siteId) => {
  var _state$sites$siteId;

  return (_state$sites$siteId = state.sites[siteId]) === null || _state$sites$siteId === void 0 ? void 0 : _state$sites$siteId.options;
};
const getSiteOption = (state, siteId, optionName) => {
  var _state$sites$siteId2, _state$sites$siteId2$;

  return (_state$sites$siteId2 = state.sites[siteId]) === null || _state$sites$siteId2 === void 0 ? void 0 : (_state$sites$siteId2$ = _state$sites$siteId2.options) === null || _state$sites$siteId2$ === void 0 ? void 0 : _state$sites$siteId2$[optionName];
};
const getPrimarySiteDomain = (_, siteId) => {
  var _getSiteDomains;

  return (_getSiteDomains = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.select)(_constants__WEBPACK_IMPORTED_MODULE_1__/* .STORE_KEY */ .L).getSiteDomains(siteId)) === null || _getSiteDomains === void 0 ? void 0 : _getSiteDomains.find(domain => domain.primary_domain);
};
const getSiteSubdomain = (_, siteId) => {
  var _getSiteDomains2;

  return (_getSiteDomains2 = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.select)(_constants__WEBPACK_IMPORTED_MODULE_1__/* .STORE_KEY */ .L).getSiteDomains(siteId)) === null || _getSiteDomains2 === void 0 ? void 0 : _getSiteDomains2.find(domain => domain.is_subdomain);
};
const getSiteLatestAtomicTransfer = (state, siteId) => {
  var _state$latestAtomicTr;

  return (_state$latestAtomicTr = state.latestAtomicTransferStatus[siteId]) === null || _state$latestAtomicTr === void 0 ? void 0 : _state$latestAtomicTr.transfer;
};
const getSiteLatestAtomicTransferError = (state, siteId) => {
  var _state$latestAtomicTr2;

  return (_state$latestAtomicTr2 = state.latestAtomicTransferStatus[siteId]) === null || _state$latestAtomicTr2 === void 0 ? void 0 : _state$latestAtomicTr2.errorCode;
};
const getAtomicSoftwareStatus = (state, siteId, softwareSet) => {
  var _state$atomicSoftware, _state$atomicSoftware2;

  return (_state$atomicSoftware = state.atomicSoftwareStatus[siteId]) === null || _state$atomicSoftware === void 0 ? void 0 : (_state$atomicSoftware2 = _state$atomicSoftware[softwareSet]) === null || _state$atomicSoftware2 === void 0 ? void 0 : _state$atomicSoftware2.status;
};
const getAtomicSoftwareError = (state, siteId, softwareSet) => {
  var _state$atomicSoftware3, _state$atomicSoftware4;

  return (_state$atomicSoftware3 = state.atomicSoftwareStatus[siteId]) === null || _state$atomicSoftware3 === void 0 ? void 0 : (_state$atomicSoftware4 = _state$atomicSoftware3[softwareSet]) === null || _state$atomicSoftware4 === void 0 ? void 0 : _state$atomicSoftware4.error;
};
const getAtomicSoftwareInstallError = (state, siteId, softwareSet) => {
  var _state$atomicSoftware5, _state$atomicSoftware6;

  return (_state$atomicSoftware5 = state.atomicSoftwareInstallStatus[siteId]) === null || _state$atomicSoftware5 === void 0 ? void 0 : (_state$atomicSoftware6 = _state$atomicSoftware5[softwareSet]) === null || _state$atomicSoftware6 === void 0 ? void 0 : _state$atomicSoftware6.error;
};
const siteHasFeature = (_, siteId, featureKey) => {
  var _getSite6, _getSite6$plan;

  return Boolean(siteId && ((_getSite6 = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.select)(_constants__WEBPACK_IMPORTED_MODULE_1__/* .STORE_KEY */ .L).getSite(siteId)) === null || _getSite6 === void 0 ? void 0 : (_getSite6$plan = _getSite6.plan) === null || _getSite6$plan === void 0 ? void 0 : _getSite6$plan.features.active.includes(featureKey)));
}; // TODO: The `0` here seems wrong and should likely be addressed.

const requiresUpgrade = (state, siteId) => {
  return siteId && !(0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.select)(_constants__WEBPACK_IMPORTED_MODULE_1__/* .STORE_KEY */ .L).siteHasFeature(siteId, 'woop');
};
function isJetpackSite(state, siteId) {
  var _getSite7;

  return Boolean(siteId && ((_getSite7 = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.select)(_constants__WEBPACK_IMPORTED_MODULE_1__/* .STORE_KEY */ .L).getSite(siteId)) === null || _getSite7 === void 0 ? void 0 : _getSite7.jetpack));
}
function isEligibleForProPlan(state, siteId) {
  if (!siteId) {
    return false;
  }

  if (isJetpackSite(state, siteId) && !isSiteAtomic(state, siteId) || isSiteWPForTeams(state, siteId)) {
    return false;
  }

  return true;
}
const getBundledPluginSlug = (state, siteSlug) => state.bundledPluginSlug[siteSlug];

/***/ }),

/***/ 9639:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Hc": () => (/* binding */ SiteLaunchError),
/* harmony export */   "uS": () => (/* binding */ SiteLaunchStatus),
/* harmony export */   "bc": () => (/* binding */ AtomicTransferStatus),
/* harmony export */   "Hf": () => (/* binding */ AtomicTransferError),
/* harmony export */   "O": () => (/* binding */ LatestAtomicTransferStatus),
/* harmony export */   "sw": () => (/* binding */ AtomicSoftwareInstallStatus)
/* harmony export */ });
/* unused harmony export Visibility */
let Visibility;

(function (Visibility) {
  Visibility[Visibility["PublicIndexed"] = 1] = "PublicIndexed";
  Visibility[Visibility["PublicNotIndexed"] = 0] = "PublicNotIndexed";
  Visibility[Visibility["Private"] = -1] = "Private";
})(Visibility || (Visibility = {}));

let SiteLaunchError;

(function (SiteLaunchError) {
  SiteLaunchError["INTERNAL"] = "internal";
})(SiteLaunchError || (SiteLaunchError = {}));

let SiteLaunchStatus;

(function (SiteLaunchStatus) {
  SiteLaunchStatus["UNINITIALIZED"] = "unintialized";
  SiteLaunchStatus["IN_PROGRESS"] = "in_progress";
  SiteLaunchStatus["SUCCESS"] = "success";
  SiteLaunchStatus["FAILURE"] = "failure";
})(SiteLaunchStatus || (SiteLaunchStatus = {}));

let AtomicTransferStatus;

(function (AtomicTransferStatus) {
  AtomicTransferStatus["UNINITIALIZED"] = "unintialized";
  AtomicTransferStatus["IN_PROGRESS"] = "in_progress";
  AtomicTransferStatus["SUCCESS"] = "success";
  AtomicTransferStatus["FAILURE"] = "failure";
})(AtomicTransferStatus || (AtomicTransferStatus = {}));

let AtomicTransferError;

(function (AtomicTransferError) {
  AtomicTransferError["INTERNAL"] = "internal";
})(AtomicTransferError || (AtomicTransferError = {}));

let LatestAtomicTransferStatus;

(function (LatestAtomicTransferStatus) {
  LatestAtomicTransferStatus["UNINITIALIZED"] = "unintialized";
  LatestAtomicTransferStatus["IN_PROGRESS"] = "in_progress";
  LatestAtomicTransferStatus["SUCCESS"] = "success";
  LatestAtomicTransferStatus["FAILURE"] = "failure";
})(LatestAtomicTransferStatus || (LatestAtomicTransferStatus = {}));

let AtomicSoftwareInstallStatus;

(function (AtomicSoftwareInstallStatus) {
  AtomicSoftwareInstallStatus["UNINITIALIZED"] = "unintialized";
  AtomicSoftwareInstallStatus["IN_PROGRESS"] = "in_progress";
  AtomicSoftwareInstallStatus["SUCCESS"] = "success";
  AtomicSoftwareInstallStatus["FAILURE"] = "failure";
})(AtomicSoftwareInstallStatus || (AtomicSoftwareInstallStatus = {}));

/***/ }),

/***/ 3661:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "_9": () => (/* binding */ wpcomRequest),
/* harmony export */   "An": () => (/* binding */ fetchAndParse),
/* harmony export */   "ai": () => (/* binding */ controls)
/* harmony export */ });
/* unused harmony exports reloadProxy, requestAllBlogsAccess, wait */
/* harmony import */ var wpcom_proxy_request__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(8552);

const wpcomRequest = request => ({
  type: 'WPCOM_REQUEST',
  request
});
/**
 * Action for performing a fetching using `window.fetch()` and parsing the response body.
 * It's different from `apiFetch()` from
 * `@wordpress/data-controls` in that it doesn't use any middleware to add extra parameters.
 *
 * @param resource the resource you wish to fetch
 * @param options request options
 */

const fetchAndParse = (resource, options) => ({
  type: 'FETCH_AND_PARSE',
  resource,
  options
});
const reloadProxy = () => ({
  type: 'RELOAD_PROXY'
});
const requestAllBlogsAccess = () => ({
  type: 'REQUEST_ALL_BLOGS_ACCESS'
});
const wait = ms => ({
  type: 'WAIT',
  ms
});
const controls = {
  WPCOM_REQUEST: _ref => {
    let {
      request
    } = _ref;
    return (0,wpcom_proxy_request__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .ZP)(request);
  },
  FETCH_AND_PARSE: async _ref2 => {
    let {
      resource,
      options
    } = _ref2;
    const response = await window.fetch(resource, options);
    return {
      ok: response.ok,
      body: await response.json()
    };
  },
  RELOAD_PROXY: () => {
    (0,wpcom_proxy_request__WEBPACK_IMPORTED_MODULE_0__/* .reloadProxy */ .sS)();
  },
  REQUEST_ALL_BLOGS_ACCESS: () => (0,wpcom_proxy_request__WEBPACK_IMPORTED_MODULE_0__/* .requestAllBlogsAccess */ .Vw)(),
  WAIT: _ref3 => {
    let {
      ms
    } = _ref3;
    return new Promise(resolve => setTimeout(resolve, ms));
  }
};

/***/ }),

/***/ 7066:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "D": () => (/* binding */ defaultCurrencyOverrides)
/* harmony export */ });
const defaultCurrencyOverrides = {
  AED: {
    symbol: 'د.إ.‏'
  },
  AFN: {
    symbol: '؋'
  },
  ALL: {
    symbol: 'Lek'
  },
  AMD: {
    symbol: '֏'
  },
  ANG: {
    symbol: 'ƒ'
  },
  AOA: {
    symbol: 'Kz'
  },
  ARS: {
    symbol: '$'
  },
  AUD: {
    symbol: 'A$'
  },
  AWG: {
    symbol: 'ƒ'
  },
  AZN: {
    symbol: '₼'
  },
  BAM: {
    symbol: 'КМ'
  },
  BBD: {
    symbol: 'Bds$'
  },
  BDT: {
    symbol: '৳'
  },
  BGN: {
    symbol: 'лв.'
  },
  BHD: {
    symbol: 'د.ب.‏'
  },
  BIF: {
    symbol: 'FBu'
  },
  BMD: {
    symbol: '$'
  },
  BND: {
    symbol: '$'
  },
  BOB: {
    symbol: 'Bs'
  },
  BRL: {
    symbol: 'R$'
  },
  BSD: {
    symbol: '$'
  },
  BTC: {
    symbol: 'Ƀ'
  },
  BTN: {
    symbol: 'Nu.'
  },
  BWP: {
    symbol: 'P'
  },
  BYR: {
    symbol: 'р.'
  },
  BZD: {
    symbol: 'BZ$'
  },
  CAD: {
    symbol: 'C$'
  },
  CDF: {
    symbol: 'FC'
  },
  CHF: {
    symbol: 'CHF'
  },
  CLP: {
    symbol: '$'
  },
  CNY: {
    symbol: '¥'
  },
  COP: {
    symbol: '$'
  },
  CRC: {
    symbol: '₡'
  },
  CUC: {
    symbol: 'CUC'
  },
  CUP: {
    symbol: '$MN'
  },
  CVE: {
    symbol: '$'
  },
  CZK: {
    symbol: 'Kč'
  },
  DJF: {
    symbol: 'Fdj'
  },
  DKK: {
    symbol: 'kr.'
  },
  DOP: {
    symbol: 'RD$'
  },
  DZD: {
    symbol: 'د.ج.‏'
  },
  EGP: {
    symbol: 'ج.م.‏'
  },
  ERN: {
    symbol: 'Nfk'
  },
  ETB: {
    symbol: 'ETB'
  },
  EUR: {
    symbol: '€'
  },
  FJD: {
    symbol: 'FJ$'
  },
  FKP: {
    symbol: '£'
  },
  GBP: {
    symbol: '£'
  },
  GEL: {
    symbol: 'Lari'
  },
  GHS: {
    symbol: '₵'
  },
  GIP: {
    symbol: '£'
  },
  GMD: {
    symbol: 'D'
  },
  GNF: {
    symbol: 'FG'
  },
  GTQ: {
    symbol: 'Q'
  },
  GYD: {
    symbol: 'G$'
  },
  HKD: {
    symbol: 'HK$'
  },
  HNL: {
    symbol: 'L.'
  },
  HRK: {
    symbol: 'kn'
  },
  HTG: {
    symbol: 'G'
  },
  HUF: {
    symbol: 'Ft'
  },
  IDR: {
    symbol: 'Rp'
  },
  ILS: {
    symbol: '₪'
  },
  INR: {
    symbol: '₹'
  },
  IQD: {
    symbol: 'د.ع.‏'
  },
  IRR: {
    symbol: '﷼'
  },
  ISK: {
    symbol: 'kr.'
  },
  JMD: {
    symbol: 'J$'
  },
  JOD: {
    symbol: 'د.ا.‏'
  },
  JPY: {
    symbol: '¥'
  },
  KES: {
    symbol: 'S'
  },
  KGS: {
    symbol: 'сом'
  },
  KHR: {
    symbol: '៛'
  },
  KMF: {
    symbol: 'CF'
  },
  KPW: {
    symbol: '₩'
  },
  KRW: {
    symbol: '₩'
  },
  KWD: {
    symbol: 'د.ك.‏'
  },
  KYD: {
    symbol: '$'
  },
  KZT: {
    symbol: '₸'
  },
  LAK: {
    symbol: '₭'
  },
  LBP: {
    symbol: 'ل.ل.‏'
  },
  LKR: {
    symbol: '₨'
  },
  LRD: {
    symbol: 'L$'
  },
  LSL: {
    symbol: 'M'
  },
  LYD: {
    symbol: 'د.ل.‏'
  },
  MAD: {
    symbol: 'د.م.‏'
  },
  MDL: {
    symbol: 'lei'
  },
  MGA: {
    symbol: 'Ar'
  },
  MKD: {
    symbol: 'ден.'
  },
  MMK: {
    symbol: 'K'
  },
  MNT: {
    symbol: '₮'
  },
  MOP: {
    symbol: 'MOP$'
  },
  MRO: {
    symbol: 'UM'
  },
  MTL: {
    symbol: '₤'
  },
  MUR: {
    symbol: '₨'
  },
  MVR: {
    symbol: 'MVR'
  },
  MWK: {
    symbol: 'MK'
  },
  MXN: {
    symbol: 'MX$'
  },
  MYR: {
    symbol: 'RM'
  },
  MZN: {
    symbol: 'MT'
  },
  NAD: {
    symbol: 'N$'
  },
  NGN: {
    symbol: '₦'
  },
  NIO: {
    symbol: 'C$'
  },
  NOK: {
    symbol: 'kr'
  },
  NPR: {
    symbol: '₨'
  },
  NZD: {
    symbol: 'NZ$'
  },
  OMR: {
    symbol: '﷼'
  },
  PAB: {
    symbol: 'B/.'
  },
  PEN: {
    symbol: 'S/.'
  },
  PGK: {
    symbol: 'K'
  },
  PHP: {
    symbol: '₱'
  },
  PKR: {
    symbol: '₨'
  },
  PLN: {
    symbol: 'zł'
  },
  PYG: {
    symbol: '₲'
  },
  QAR: {
    symbol: '﷼'
  },
  RON: {
    symbol: 'lei'
  },
  RSD: {
    symbol: 'Дин.'
  },
  RUB: {
    symbol: '₽'
  },
  RWF: {
    symbol: 'RWF'
  },
  SAR: {
    symbol: '﷼'
  },
  SBD: {
    symbol: 'S$'
  },
  SCR: {
    symbol: '₨'
  },
  SDD: {
    symbol: 'LSd'
  },
  SDG: {
    symbol: '£‏'
  },
  SEK: {
    symbol: 'kr'
  },
  SGD: {
    symbol: 'S$'
  },
  SHP: {
    symbol: '£'
  },
  SLL: {
    symbol: 'Le'
  },
  SOS: {
    symbol: 'S'
  },
  SRD: {
    symbol: '$'
  },
  STD: {
    symbol: 'Db'
  },
  SVC: {
    symbol: '₡'
  },
  SYP: {
    symbol: '£'
  },
  SZL: {
    symbol: 'E'
  },
  THB: {
    symbol: '฿'
  },
  TJS: {
    symbol: 'TJS'
  },
  TMT: {
    symbol: 'm'
  },
  TND: {
    symbol: 'د.ت.‏'
  },
  TOP: {
    symbol: 'T$'
  },
  TRY: {
    symbol: 'TL'
  },
  TTD: {
    symbol: 'TT$'
  },
  TVD: {
    symbol: '$T'
  },
  TWD: {
    symbol: 'NT$'
  },
  TZS: {
    symbol: 'TSh'
  },
  UAH: {
    symbol: '₴'
  },
  UGX: {
    symbol: 'USh'
  },
  USD: {// No override. Do what the locale thinks is best.
  },
  UYU: {
    symbol: '$U'
  },
  UZS: {
    symbol: 'сўм'
  },
  VEB: {
    symbol: 'Bs.'
  },
  VEF: {
    symbol: 'Bs. F.'
  },
  VND: {
    symbol: '₫'
  },
  VUV: {
    symbol: 'VT'
  },
  WST: {
    symbol: 'WS$'
  },
  XAF: {
    symbol: 'F'
  },
  XCD: {
    symbol: '$'
  },
  XOF: {
    symbol: 'F'
  },
  XPF: {
    symbol: 'F'
  },
  YER: {
    symbol: '﷼'
  },
  ZAR: {
    symbol: 'R'
  },
  ZMW: {
    symbol: 'ZK'
  },
  WON: {
    symbol: '₩'
  }
};

/***/ }),

/***/ 2645:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "ZP": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* unused harmony exports createFormatter, geolocateCurrencySymbol, formatCurrency, getCurrencyObject, setDefaultLocale, setCurrencySymbol */
/* harmony import */ var _currencies__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(7066);


const formatterCache = new Map();
const fallbackLocale = 'en';
const fallbackCurrency = 'USD';
const geolocationEndpointUrl = 'https://public-api.wordpress.com/geo/';
function createFormatter() {
  const currencyOverrides = {};
  let defaultLocale = undefined;
  let geoLocation = ''; // If the user is inside the US using USD, they should only see `$` and not `US$`.

  async function geolocateCurrencySymbol() {
    var _globalThis$fetch;

    const geoData = await ((_globalThis$fetch = globalThis.fetch) === null || _globalThis$fetch === void 0 ? void 0 : _globalThis$fetch.call(globalThis, geolocationEndpointUrl).then(response => response.json()).catch(error => {
      // Do nothing if the fetch fails.
      // eslint-disable-next-line no-console
      console.warn('Fetching geolocation for format-currency failed.', error);
    }));

    if (!containsGeolocationCountry(geoData)) {
      return;
    }

    if (!geoData.country_short) {
      return;
    }

    geoLocation = geoData.country_short;
  }

  function getFormatter(number, code, options) {
    const locale = options.locale ?? defaultLocale ?? getLocaleFromBrowser();
    return getCachedFormatter({
      locale,
      currency: code,
      noDecimals: isNoDecimals(number, options)
    });
  }
  /**
   * Formats money with a given currency code.
   *
   * The currency will define the properties to use for this formatting, but
   * those properties can be overridden using the options. Be careful when doing
   * this.
   *
   * For currencies that include decimals, this will always return the amount
   * with decimals included, even if those decimals are zeros. To exclude the
   * zeros, use the `stripZeros` option. For example, the function will normally
   * format `10.00` in `USD` as `$10.00` but when this option is true, it will
   * return `$10` instead.
   *
   * Since rounding errors are common in floating point math, sometimes a price
   * is provided as an integer in the smallest unit of a currency (eg: cents in
   * USD or yen in JPY). Set the `isSmallestUnit` to change the function to
   * operate on integer numbers instead. If this option is not set or false, the
   * function will format the amount `1025` in `USD` as `$1,025.00`, but when the
   * option is true, it will return `$10.25` instead.
   *
   * If the number is NaN, it will be treated as 0.
   *
   * If the currency code is not known, this will assume a default currency
   * similar to USD.
   *
   * If `isSmallestUnit` is set and the number is not an integer, it will be
   * rounded to an integer.
   *
   * @param      {number}                   number     number to format; assumed to be a float unless isSmallestUnit is set.
   * @param      {string}                   code       currency code e.g. 'USD'
   * @param      {CurrencyObjectOptions}    options    options object
   * @returns    {string}                  A formatted string.
   */


  function formatCurrency(number, code) {
    let options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
    const locale = options.locale ?? defaultLocale ?? getLocaleFromBrowser();
    code = getValidCurrency(code);
    const currencyOverride = getCurrencyOverride(code);
    const currencyPrecision = getPrecisionForLocaleAndCurrency(locale, code);
    const numberAsFloat = prepareNumberForFormatting(number, currencyPrecision, options);
    const formatter = getFormatter(numberAsFloat, code, options);
    const parts = formatter.formatToParts(numberAsFloat);
    return parts.reduce((formatted, part) => {
      switch (part.type) {
        case 'currency':
          if (currencyOverride !== null && currencyOverride !== void 0 && currencyOverride.symbol) {
            return formatted + currencyOverride.symbol;
          }

          return formatted + part.value;

        default:
          return formatted + part.value;
      }
    }, '');
  }
  /**
   * Returns a formatted price object.
   *
   * The currency will define the properties to use for this formatting, but
   * those properties can be overridden using the options. Be careful when doing
   * this.
   *
   * For currencies that include decimals, this will always return the amount
   * with decimals included, even if those decimals are zeros. To exclude the
   * zeros, use the `stripZeros` option. For example, the function will normally
   * format `10.00` in `USD` as `$10.00` but when this option is true, it will
   * return `$10` instead.
   *
   * Since rounding errors are common in floating point math, sometimes a price
   * is provided as an integer in the smallest unit of a currency (eg: cents in
   * USD or yen in JPY). Set the `isSmallestUnit` to change the function to
   * operate on integer numbers instead. If this option is not set or false, the
   * function will format the amount `1025` in `USD` as `$1,025.00`, but when the
   * option is true, it will return `$10.25` instead.
   *
   * Note that the `integer` return value of this function is not a number, but a
   * locale-formatted string which may include symbols like spaces, commas, or
   * periods as group separators. Similarly, the `fraction` property is a string
   * that contains the decimal separator.
   *
   * If the number is NaN, it will be treated as 0.
   *
   * If the currency code is not known, this will assume a default currency
   * similar to USD.
   *
   * If `isSmallestUnit` is set and the number is not an integer, it will be
   * rounded to an integer.
   *
   * @param      {number}                   number     number to format; assumed to be a float unless isSmallestUnit is set.
   * @param      {string}                   code       currency code e.g. 'USD'
   * @param      {CurrencyObjectOptions}    options    options object
   * @returns    {CurrencyObject}          A formatted string e.g. { symbol:'$', integer: '$99', fraction: '.99', sign: '-' }
   */


  function getCurrencyObject(number, code) {
    let options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
    const locale = options.locale ?? defaultLocale ?? getLocaleFromBrowser();
    code = getValidCurrency(code);
    const currencyOverride = getCurrencyOverride(code);
    const currencyPrecision = getPrecisionForLocaleAndCurrency(locale, code);
    const numberAsFloat = prepareNumberForFormatting(number, currencyPrecision, options);
    const formatter = getFormatter(numberAsFloat, code, options);
    const parts = formatter.formatToParts(numberAsFloat);
    let sign = '';
    let symbol = '$';
    let symbolPosition = 'before';
    let hasAmountBeenSet = false;
    let hasDecimalBeenSet = false;
    let integer = '';
    let fraction = '';
    parts.forEach(part => {
      switch (part.type) {
        case 'currency':
          symbol = (currencyOverride === null || currencyOverride === void 0 ? void 0 : currencyOverride.symbol) ?? part.value;

          if (hasAmountBeenSet) {
            symbolPosition = 'after';
          }

          return;

        case 'group':
          integer += part.value;
          hasAmountBeenSet = true;
          return;

        case 'decimal':
          fraction += part.value;
          hasAmountBeenSet = true;
          hasDecimalBeenSet = true;
          return;

        case 'integer':
          integer += part.value;
          hasAmountBeenSet = true;
          return;

        case 'fraction':
          fraction += part.value;
          hasAmountBeenSet = true;
          hasDecimalBeenSet = true;
          return;

        case 'minusSign':
          sign = '-';
          return;
      }
    });
    const hasNonZeroFraction = !Number.isInteger(numberAsFloat) && hasDecimalBeenSet;
    return {
      sign,
      symbol,
      symbolPosition,
      integer,
      fraction,
      hasNonZeroFraction
    };
  }

  function getValidCurrency(code) {
    if (!doesCurrencyExist(code)) {
      // eslint-disable-next-line no-console
      console.warn(`getCurrencyObject was called with a non-existent currency "${code}"; falling back to ${fallbackCurrency}`);
      return fallbackCurrency;
    }

    return code;
  }
  /**
   * Change the currency symbol override used by formatting.
   *
   * By default, `formatCurrency` and `getCurrencyObject` use a currency symbol
   * from a list of hard-coded overrides in this package keyed by the currency
   * code. For example, `CAD` is always rendered as `C$` even if the locale is
   * `en-CA` which would normally render the symbol `$`.
   *
   * With this function, you can change the override used by any given currency.
   *
   * Note that this is global and will take effect no matter the locale! Use it
   * with care.
   */


  function setCurrencySymbol(currencyCode, currencySymbol) {
    if (!currencyOverrides[currencyCode]) {
      currencyOverrides[currencyCode] = {};
    }

    currencyOverrides[currencyCode].symbol = currencySymbol;
  }

  function getCurrencyOverride(code) {
    if (code === 'USD' && geoLocation !== '' && geoLocation !== 'US') {
      return {
        symbol: 'US$'
      };
    }

    return currencyOverrides[code] ?? _currencies__WEBPACK_IMPORTED_MODULE_0__/* .defaultCurrencyOverrides */ .D[code];
  }

  function doesCurrencyExist(code) {
    return Boolean(getCurrencyOverride(code));
  }
  /**
   * Set a default locale for use by `formatCurrency` and `getCurrencyObject`.
   *
   * Note that this is global and will override any browser locale that is set!
   * Use it with care.
   */


  function setDefaultLocale(locale) {
    defaultLocale = locale;
  }

  return {
    formatCurrency,
    getCurrencyObject,
    setCurrencySymbol,
    setDefaultLocale,
    geolocateCurrencySymbol
  };
}

function getLocaleFromBrowser() {
  var _window$navigator, _window$navigator$lan, _window$navigator2;

  if (typeof window === 'undefined') {
    return fallbackLocale;
  }

  if (((_window$navigator = window.navigator) === null || _window$navigator === void 0 ? void 0 : (_window$navigator$lan = _window$navigator.languages) === null || _window$navigator$lan === void 0 ? void 0 : _window$navigator$lan.length) > 0) {
    return window.navigator.languages[0];
  }

  return ((_window$navigator2 = window.navigator) === null || _window$navigator2 === void 0 ? void 0 : _window$navigator2.language) ?? fallbackLocale;
}

function getFormatterCacheKey(_ref) {
  let {
    locale,
    currency,
    noDecimals
  } = _ref;
  return `currency:${currency},locale:${locale},noDecimals:${noDecimals}`;
}

function isNoDecimals(number, options) {
  if (options.stripZeros && Number.isInteger(number)) {
    return true;
  }

  return false;
}
/**
 * Creating an Intl.NumberFormat is expensive, so this allows caching.
 */


function getCachedFormatter(_ref2) {
  let {
    locale,
    currency,
    noDecimals
  } = _ref2;
  const cacheKey = getFormatterCacheKey({
    locale,
    currency,
    noDecimals
  });

  if (formatterCache.has(cacheKey)) {
    const formatter = formatterCache.get(cacheKey);

    if (formatter) {
      return formatter;
    }
  }

  try {
    const formatter = new Intl.NumberFormat(addNumberingSystemToLocale(locale), {
      style: 'currency',
      currency,
      // There's an option called `trailingZeroDisplay` but it does not yet work
      // in FF so we have to strip zeros manually.
      ...(noDecimals ? {
        maximumFractionDigits: 0,
        minimumFractionDigits: 0
      } : {})
    });
    formatterCache.set(cacheKey, formatter);
    return formatter;
  } catch (error) {
    // If the locale is invalid, creating the NumberFormat will throw.
    // eslint-disable-next-line no-console
    console.warn(`formatCurrency was called with a non-existent locale "${locale}"; falling back to ${fallbackLocale}`);
    return getCachedFormatter({
      locale: fallbackLocale,
      currency,
      noDecimals
    });
  }
}
/**
 * `numberingSystem` is an option to `Intl.NumberFormat` and is available
 * in all major browsers according to
 * https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Intl/NumberFormat/NumberFormat#options
 * but is not part of the TypeScript types in `es2020`:
 *
 * https://github.com/microsoft/TypeScript/blob/cfd472f7aa5a2010a3115263bf457b30c5b489f3/src/lib/es2020.intl.d.ts#L272
 *
 * However, it is part of the TypeScript types in `es5`:
 *
 * https://github.com/microsoft/TypeScript/blob/cfd472f7aa5a2010a3115263bf457b30c5b489f3/src/lib/es5.d.ts#L4310
 *
 * Apparently calypso uses `es2020` so we cannot use that option here right
 * now. Instead, we will use the unicode extension to the locale, documented
 * here:
 *
 * https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Intl/Locale/numberingSystem#adding_a_numbering_system_via_the_locale_string
 */


function addNumberingSystemToLocale(locale) {
  const numberingSystem = 'latn';
  const numberingSystemUnicodeLocaleExtension = `-u-nu-${numberingSystem}`;
  const localeWithNumberingSystem = `${locale}${numberingSystemUnicodeLocaleExtension}`;
  return localeWithNumberingSystem;
}

function getPrecisionForLocaleAndCurrency(locale, currency) {
  const defaultFormatter = getCachedFormatter({
    locale,
    currency,
    noDecimals: false
  });
  return defaultFormatter.resolvedOptions().maximumFractionDigits;
}

function prepareNumberForFormatting(number, // currencyPrecision here must be the precision of the currency, regardless
// of what precision is requested for display!
currencyPrecision, options) {
  if (isNaN(number)) {
    // eslint-disable-next-line no-console
    console.warn('formatCurrency was called with NaN');
    number = 0;
  }

  if (options.isSmallestUnit) {
    if (!Number.isInteger(number)) {
      // eslint-disable-next-line no-console
      console.warn('formatCurrency was called with isSmallestUnit and a float which will be rounded', number);
      number = Math.round(number);
    }

    number = convertPriceForSmallestUnit(number, currencyPrecision);
  }

  const scale = Math.pow(10, currencyPrecision);
  return Math.round(number * scale) / scale;
}

function convertPriceForSmallestUnit(price, precision) {
  return price / getSmallestUnitDivisor(precision);
}

function getSmallestUnitDivisor(precision) {
  return 10 ** precision;
}

function containsGeolocationCountry(response) {
  return typeof (response === null || response === void 0 ? void 0 : response.country_short) === 'string';
}

const defaultFormatter = createFormatter();
async function geolocateCurrencySymbol() {
  return defaultFormatter.geolocateCurrencySymbol();
}
function formatCurrency() {
  return defaultFormatter.formatCurrency(...arguments);
}
function getCurrencyObject() {
  return defaultFormatter.getCurrencyObject(...arguments);
}
function setDefaultLocale() {
  return defaultFormatter.setDefaultLocale(...arguments);
}
function setCurrencySymbol() {
  return defaultFormatter.setCurrencySymbol(...arguments);
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (defaultFormatter.formatCurrency);

/***/ }),

/***/ 4724:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(914);

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (new _i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z());

/***/ }),

/***/ 914:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var events__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(2699);
/* harmony import */ var events__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(events__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _automattic_interpolate_components__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(2594);
/* harmony import */ var _tannin_sprintf__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(6668);
/* harmony import */ var debug__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(8049);
/* harmony import */ var debug__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(debug__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var hash_js_lib_hash_sha_1__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(5079);
/* harmony import */ var hash_js_lib_hash_sha_1__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(hash_js_lib_hash_sha_1__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var lru__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(7839);
/* harmony import */ var lru__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(lru__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var tannin__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(9830);
/* harmony import */ var _number_format__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(3);








/**
 * Module variables
 */

const debug = debug__WEBPACK_IMPORTED_MODULE_1___default()('i18n-calypso');
/**
 * Constants
 */

const decimal_point_translation_key = 'number_format_decimals';
const thousands_sep_translation_key = 'number_format_thousands_sep';
const domain_key = 'messages';
const translationLookup = [// By default don't modify the options when looking up translations.
function (options) {
  return options;
}];
const hashCache = {}; // raise a console warning

function warn() {
  if (!I18N.throwErrors) {
    return;
  }

  if ('undefined' !== typeof window && window.console && window.console.warn) {
    window.console.warn.apply(window.console, arguments);
  }
} // turns Function.arguments into an array


function simpleArguments(args) {
  return Array.prototype.slice.call(args);
}
/**
 * Coerce the possible arguments and normalize to a single object.
 *
 * @param   {any} args - arguments passed in from `translate()`
 * @returns {Object}         - a single object describing translation needs
 */


function normalizeTranslateArguments(args) {
  const original = args[0]; // warn about older deprecated syntax

  if (typeof original !== 'string' || args.length > 3 || args.length > 2 && typeof args[1] === 'object' && typeof args[2] === 'object') {
    warn('Deprecated Invocation: `translate()` accepts ( string, [string], [object] ). These arguments passed:', simpleArguments(args), '. See https://github.com/Automattic/i18n-calypso#translate-method');
  }

  if (args.length === 2 && typeof original === 'string' && typeof args[1] === 'string') {
    warn('Invalid Invocation: `translate()` requires an options object for plural translations, but passed:', simpleArguments(args));
  } // options could be in position 0, 1, or 2
  // sending options as the first object is deprecated and will raise a warning


  let options = {};

  for (let i = 0; i < args.length; i++) {
    if (typeof args[i] === 'object') {
      options = args[i];
    }
  } // `original` can be passed as first parameter or as part of the options object
  // though passing original as part of the options is a deprecated approach and will be removed


  if (typeof original === 'string') {
    options.original = original;
  } else if (typeof options.original === 'object') {
    options.plural = options.original.plural;
    options.count = options.original.count;
    options.original = options.original.single;
  }

  if (typeof args[1] === 'string') {
    options.plural = args[1];
  }

  if (typeof options.original === 'undefined') {
    throw new Error('Translate called without a `string` value as first argument.');
  }

  return options;
}
/**
 * Takes translate options object and coerces to a Tannin request to retrieve translation.
 *
 * @param   {Object} tannin  - tannin data object
 * @param   {Object} options - object describing translation
 * @returns {string}         - the returned translation from Tannin
 */


function getTranslationFromTannin(tannin, options) {
  return tannin.dcnpgettext(domain_key, options.context, options.original, options.plural, options.count);
}

function getTranslation(i18n, options) {
  for (let i = translationLookup.length - 1; i >= 0; i--) {
    const lookup = translationLookup[i](Object.assign({}, options));
    const key = lookup.context ? lookup.context + '\u0004' + lookup.original : lookup.original; // Only get the translation from tannin if it exists.

    if (i18n.state.locale[key]) {
      return getTranslationFromTannin(i18n.state.tannin, lookup);
    }
  }

  return null;
}

function I18N() {
  if (!(this instanceof I18N)) {
    return new I18N();
  }

  this.defaultLocaleSlug = 'en'; // Tannin always needs a plural form definition, or it fails when dealing with plurals.

  this.defaultPluralForms = n => n === 1 ? 0 : 1;

  this.state = {
    numberFormatSettings: {},
    tannin: undefined,
    locale: undefined,
    localeSlug: undefined,
    localeVariant: undefined,
    textDirection: undefined,
    translations: lru__WEBPACK_IMPORTED_MODULE_3___default()({
      max: 100
    })
  };
  this.componentUpdateHooks = [];
  this.translateHooks = [];
  this.stateObserver = new events__WEBPACK_IMPORTED_MODULE_0__.EventEmitter(); // Because the higher-order component can wrap a ton of React components,
  // we need to bump the number of listeners to infinity and beyond
  // FIXME: still valid?

  this.stateObserver.setMaxListeners(0); // default configuration

  this.configure();
}

I18N.throwErrors = false;

I18N.prototype.on = function () {
  this.stateObserver.on(...arguments);
};

I18N.prototype.off = function () {
  this.stateObserver.off(...arguments);
};

I18N.prototype.emit = function () {
  this.stateObserver.emit(...arguments);
};
/**
 * Formats numbers using locale settings and/or passed options.
 *
 * @param   {string|number}  number to format (required)
 * @param   {number | Object}  options  Number of decimal places or options object (optional)
 * @returns {string}         Formatted number as string
 */


I18N.prototype.numberFormat = function (number) {
  let options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
  const decimals = typeof options === 'number' ? options : options.decimals || 0;
  const decPoint = options.decPoint || this.state.numberFormatSettings.decimal_point || '.';
  const thousandsSep = options.thousandsSep || this.state.numberFormatSettings.thousands_sep || ',';
  return (0,_number_format__WEBPACK_IMPORTED_MODULE_5__/* ["default"] */ .Z)(number, decimals, decPoint, thousandsSep);
};

I18N.prototype.configure = function (options) {
  Object.assign(this, options || {});
  this.setLocale();
};

I18N.prototype.setLocale = function (localeData) {
  var _this$state$locale$te, _this$state$locale$, _this$state$locale$$m;

  if (localeData && localeData[''] && localeData['']['key-hash']) {
    const keyHash = localeData['']['key-hash'];

    const transform = function (string, hashLength) {
      const lookupPrefix = hashLength === false ? '' : String(hashLength);

      if (typeof hashCache[lookupPrefix + string] !== 'undefined') {
        return hashCache[lookupPrefix + string];
      }

      const hash = hash_js_lib_hash_sha_1__WEBPACK_IMPORTED_MODULE_2___default()().update(string).digest('hex');

      if (hashLength) {
        return hashCache[lookupPrefix + string] = hash.substr(0, hashLength);
      }

      return hashCache[lookupPrefix + string] = hash;
    };

    const generateLookup = function (hashLength) {
      return function (options) {
        if (options.context) {
          options.original = transform(options.context + String.fromCharCode(4) + options.original, hashLength);
          delete options.context;
        } else {
          options.original = transform(options.original, hashLength);
        }

        return options;
      };
    };

    if (keyHash.substr(0, 4) === 'sha1') {
      if (keyHash.length === 4) {
        translationLookup.push(generateLookup(false));
      } else {
        const variableHashLengthPos = keyHash.substr(5).indexOf('-');

        if (variableHashLengthPos < 0) {
          const hashLength = Number(keyHash.substr(5));
          translationLookup.push(generateLookup(hashLength));
        } else {
          const minHashLength = Number(keyHash.substr(5, variableHashLengthPos));
          const maxHashLength = Number(keyHash.substr(6 + variableHashLengthPos));

          for (let hashLength = minHashLength; hashLength <= maxHashLength; hashLength++) {
            translationLookup.push(generateLookup(hashLength));
          }
        }
      }
    }
  } // if localeData is not given, assumes default locale and reset


  if (!localeData || !localeData[''].localeSlug) {
    this.state.locale = {
      '': {
        localeSlug: this.defaultLocaleSlug,
        plural_forms: this.defaultPluralForms
      }
    };
  } else if (localeData[''].localeSlug === this.state.localeSlug) {
    // Exit if same data as current (comparing references only)
    if (localeData === this.state.locale) {
      return;
    } // merge new data into existing one


    Object.assign(this.state.locale, localeData);
  } else {
    this.state.locale = Object.assign({}, localeData);
  }

  this.state.localeSlug = this.state.locale[''].localeSlug;
  this.state.localeVariant = this.state.locale[''].localeVariant; // extract the `textDirection` info (LTR or RTL) from either:
  // - the translation for the special string "ltr" (standard in Core, not present in Calypso)
  // - or the `momentjs_locale.textDirection` property present in Calypso translation files

  this.state.textDirection = ((_this$state$locale$te = this.state.locale['text direction\u0004ltr']) === null || _this$state$locale$te === void 0 ? void 0 : _this$state$locale$te[0]) || ((_this$state$locale$ = this.state.locale['']) === null || _this$state$locale$ === void 0 ? void 0 : (_this$state$locale$$m = _this$state$locale$.momentjs_locale) === null || _this$state$locale$$m === void 0 ? void 0 : _this$state$locale$$m.textDirection);
  this.state.tannin = new tannin__WEBPACK_IMPORTED_MODULE_4__/* ["default"] */ .Z({
    [domain_key]: this.state.locale
  }); // Updates numberFormat preferences with settings from translations

  this.state.numberFormatSettings.decimal_point = getTranslationFromTannin(this.state.tannin, normalizeTranslateArguments([decimal_point_translation_key]));
  this.state.numberFormatSettings.thousands_sep = getTranslationFromTannin(this.state.tannin, normalizeTranslateArguments([thousands_sep_translation_key])); // If translation isn't set, define defaults.

  if (this.state.numberFormatSettings.decimal_point === decimal_point_translation_key) {
    this.state.numberFormatSettings.decimal_point = '.';
  }

  if (this.state.numberFormatSettings.thousands_sep === thousands_sep_translation_key) {
    this.state.numberFormatSettings.thousands_sep = ',';
  }

  this.stateObserver.emit('change');
};

I18N.prototype.getLocale = function () {
  return this.state.locale;
};
/**
 * Get the current locale slug.
 *
 * @returns {string} The string representing the currently loaded locale
 */


I18N.prototype.getLocaleSlug = function () {
  return this.state.localeSlug;
};
/**
 * Get the current locale variant. That's set for some special locales that don't have a
 * standard ISO code, like `de_formal` or `sr_latin`.
 *
 * @returns {string|undefined} The string representing the currently loaded locale's variant
 */


I18N.prototype.getLocaleVariant = function () {
  return this.state.localeVariant;
};
/**
 * Get the current text direction, left-to-right (LTR) or right-to-left (RTL).
 *
 * @returns {boolean} `true` in case the current locale has RTL text direction
 */


I18N.prototype.isRtl = function () {
  return this.state.textDirection === 'rtl';
};
/**
 * Adds new translations to the locale data, overwriting any existing translations with a matching key.
 *
 * @param {Object} localeData Locale data
 */


I18N.prototype.addTranslations = function (localeData) {
  for (const prop in localeData) {
    if (prop !== '') {
      this.state.tannin.data.messages[prop] = localeData[prop];
    }
  }

  this.stateObserver.emit('change');
};
/**
 * Checks whether the given original has a translation.
 *
 * @returns {boolean} whether a translation exists
 */


I18N.prototype.hasTranslation = function () {
  return !!getTranslation(this, normalizeTranslateArguments(arguments));
};
/**
 * Exposes single translation method.
 * See sibling README
 *
 * @returns {string | Object | undefined} translated text or an object containing React children that can be inserted into a parent component
 */


I18N.prototype.translate = function () {
  const options = normalizeTranslateArguments(arguments);
  let translation = getTranslation(this, options);

  if (!translation) {
    // This purposefully calls tannin for a case where there is no translation,
    // so that tannin gives us the expected object with English text.
    translation = getTranslationFromTannin(this.state.tannin, options);
  } // handle any string substitution


  if (options.args) {
    const sprintfArgs = Array.isArray(options.args) ? options.args.slice(0) : [options.args];
    sprintfArgs.unshift(translation);

    try {
      translation = (0,_tannin_sprintf__WEBPACK_IMPORTED_MODULE_6__/* ["default"] */ .Z)(...sprintfArgs);
    } catch (error) {
      if (!window || !window.console) {
        return;
      }

      const errorMethod = this.throwErrors ? 'error' : 'warn';

      if (typeof error !== 'string') {
        window.console[errorMethod](error);
      } else {
        window.console[errorMethod]('i18n sprintf error:', sprintfArgs);
      }
    }
  } // interpolate any components


  if (options.components) {
    translation = (0,_automattic_interpolate_components__WEBPACK_IMPORTED_MODULE_7__/* ["default"] */ .Z)({
      mixedString: translation,
      components: options.components,
      throwErrors: this.throwErrors
    });
  } // run any necessary hooks


  this.translateHooks.forEach(function (hook) {
    translation = hook(translation, options);
  });
  return translation;
};
/**
 * Causes i18n to re-render all translations.
 *
 * This can be necessary if an extension makes changes that i18n is unaware of
 * and needs those changes manifested immediately (e.g. adding an important
 * translation hook, or modifying the behaviour of an existing hook).
 *
 * If at all possible, react components should try to use the more local
 * updateTranslation() function inherited from the mixin.
 */


I18N.prototype.reRenderTranslations = function () {
  debug('Re-rendering all translations due to external request');
  this.stateObserver.emit('change');
};

I18N.prototype.registerComponentUpdateHook = function (callback) {
  this.componentUpdateHooks.push(callback);
};

I18N.prototype.registerTranslateHook = function (callback) {
  this.translateHooks.push(callback);
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (I18N);

/***/ }),

/***/ 1481:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Iu": () => (/* binding */ translate)
/* harmony export */ });
/* unused harmony exports numberFormat, configure, setLocale, getLocale, getLocaleSlug, getLocaleVariant, isRtl, addTranslations, reRenderTranslations, registerComponentUpdateHook, registerTranslateHook, state, stateObserver, on, off, emit */
/* harmony import */ var _default_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(4724);







/* unused harmony default export */ var __WEBPACK_DEFAULT_EXPORT__ = ((/* unused pure expression or super */ null && (i18n))); // Export the default instance's properties and bound methods for convenience
// These should be deprecated eventually, exposing only the default `i18n` instance

const numberFormat = _default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"].numberFormat.bind */ .Z.numberFormat.bind(_default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z);
const translate = _default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"].translate.bind */ .Z.translate.bind(_default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z);
const configure = _default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"].configure.bind */ .Z.configure.bind(_default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z);
const setLocale = _default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"].setLocale.bind */ .Z.setLocale.bind(_default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z);
const getLocale = _default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"].getLocale.bind */ .Z.getLocale.bind(_default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z);
const getLocaleSlug = _default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"].getLocaleSlug.bind */ .Z.getLocaleSlug.bind(_default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z);
const getLocaleVariant = _default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"].getLocaleVariant.bind */ .Z.getLocaleVariant.bind(_default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z);
const isRtl = _default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"].isRtl.bind */ .Z.isRtl.bind(_default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z);
const addTranslations = _default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"].addTranslations.bind */ .Z.addTranslations.bind(_default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z);
const reRenderTranslations = _default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"].reRenderTranslations.bind */ .Z.reRenderTranslations.bind(_default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z);
const registerComponentUpdateHook = _default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"].registerComponentUpdateHook.bind */ .Z.registerComponentUpdateHook.bind(_default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z);
const registerTranslateHook = _default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"].registerTranslateHook.bind */ .Z.registerTranslateHook.bind(_default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z);
const state = _default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"].state */ .Z.state;
const stateObserver = _default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"].stateObserver */ .Z.stateObserver;
const on = _default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"].on.bind */ .Z.on.bind(_default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z);
const off = _default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"].off.bind */ .Z.off.bind(_default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z);
const emit = _default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"].emit.bind */ .Z.emit.bind(_default_i18n__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z);

/***/ }),

/***/ 3:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (/* binding */ number_format)
/* harmony export */ });
/*
 * Exposes number format capability
 *
 * @copyright Copyright (c) 2013 Kevin van Zonneveld (http://kvz.io) and Contributors (http://phpjs.org/authors).
 * @license See CREDITS.md
 * @see https://github.com/kvz/phpjs/blob/ffe1356af23a6f2512c84c954dd4e828e92579fa/functions/strings/number_format.js
 */
function toFixedFix(n, prec) {
  const k = Math.pow(10, prec);
  return '' + (Math.round(n * k) / k).toFixed(prec);
}

function number_format(number, decimals, dec_point, thousands_sep) {
  number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
  const n = !isFinite(+number) ? 0 : +number;
  const prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
  const sep = typeof thousands_sep === 'undefined' ? ',' : thousands_sep;
  const dec = typeof dec_point === 'undefined' ? '.' : dec_point;
  let s = ''; // Fix for IE parseFloat(0.55).toFixed(0) = 0;

  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');

  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }

  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }

  return s.join(dec);
}

/***/ }),

/***/ 2594:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (/* binding */ interpolate)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(9196);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _tokenize__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(1310);



function getCloseIndex(openIndex, tokens) {
  const openToken = tokens[openIndex];
  let nestLevel = 0;

  for (let i = openIndex + 1; i < tokens.length; i++) {
    const token = tokens[i];

    if (token.value === openToken.value) {
      if (token.type === 'componentOpen') {
        nestLevel++;
        continue;
      }

      if (token.type === 'componentClose') {
        if (nestLevel === 0) {
          return i;
        }

        nestLevel--;
      }
    }
  } // if we get this far, there was no matching close token


  throw new Error('Missing closing component token `' + openToken.value + '`');
}

function buildChildren(tokens, components) {
  let children = [];
  let openComponent;
  let openIndex;

  for (let i = 0; i < tokens.length; i++) {
    const token = tokens[i];

    if (token.type === 'string') {
      children.push(token.value);
      continue;
    } // component node should at least be set


    if (components[token.value] === undefined) {
      throw new Error(`Invalid interpolation, missing component node: \`${token.value}\``);
    } // should be either ReactElement or null (both type "object"), all other types deprecated


    if (typeof components[token.value] !== 'object') {
      throw new Error(`Invalid interpolation, component node must be a ReactElement or null: \`${token.value}\``);
    } // we should never see a componentClose token in this loop


    if (token.type === 'componentClose') {
      throw new Error(`Missing opening component token: \`${token.value}\``);
    }

    if (token.type === 'componentOpen') {
      openComponent = components[token.value];
      openIndex = i;
      break;
    } // componentSelfClosing token


    children.push(components[token.value]);
    continue;
  }

  if (openComponent) {
    const closeIndex = getCloseIndex(openIndex, tokens);
    const grandChildTokens = tokens.slice(openIndex + 1, closeIndex);
    const grandChildren = buildChildren(grandChildTokens, components);
    const clonedOpenComponent = /*#__PURE__*/(0,react__WEBPACK_IMPORTED_MODULE_0__.cloneElement)(openComponent, {}, grandChildren);
    children.push(clonedOpenComponent);

    if (closeIndex < tokens.length - 1) {
      const siblingTokens = tokens.slice(closeIndex + 1);
      const siblings = buildChildren(siblingTokens, components);
      children = children.concat(siblings);
    }
  }

  children = children.filter(Boolean);

  if (children.length === 0) {
    return null;
  }

  if (children.length === 1) {
    return children[0];
  }

  return /*#__PURE__*/(0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(react__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, ...children);
}

function interpolate(options) {
  const {
    mixedString,
    components,
    throwErrors
  } = options;

  if (!components) {
    return mixedString;
  }

  if (typeof components !== 'object') {
    if (throwErrors) {
      throw new Error(`Interpolation Error: unable to process \`${mixedString}\` because components is not an object`);
    }

    return mixedString;
  }

  const tokens = (0,_tokenize__WEBPACK_IMPORTED_MODULE_1__/* ["default"] */ .Z)(mixedString);

  try {
    return buildChildren(tokens, components);
  } catch (error) {
    if (throwErrors) {
      throw new Error(`Interpolation Error: unable to process \`${mixedString}\` because of error \`${error.message}\``);
    }

    return mixedString;
  }
}

/***/ }),

/***/ 1310:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (/* binding */ tokenize)
/* harmony export */ });
function identifyToken(item) {
  // {{/example}}
  if (item.startsWith('{{/')) {
    return {
      type: 'componentClose',
      value: item.replace(/\W/g, '')
    };
  } // {{example /}}


  if (item.endsWith('/}}')) {
    return {
      type: 'componentSelfClosing',
      value: item.replace(/\W/g, '')
    };
  } // {{example}}


  if (item.startsWith('{{')) {
    return {
      type: 'componentOpen',
      value: item.replace(/\W/g, '')
    };
  }

  return {
    type: 'string',
    value: item
  };
}

function tokenize(mixedString) {
  const tokenStrings = mixedString.split(/(\{\{\/?\s*\w+\s*\/?\}\})/g); // split to components and strings

  return tokenStrings.map(identifyToken);
}

/***/ }),

/***/ 8552:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Vw": () => (/* binding */ requestAllBlogsAccess),
/* harmony export */   "ZP": () => (__WEBPACK_DEFAULT_EXPORT__),
/* harmony export */   "sS": () => (/* binding */ reloadProxy)
/* harmony export */ });
/* unused harmony export canAccessWpcomApis */
/* harmony import */ var debug__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(8049);
/* harmony import */ var debug__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(debug__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var progress_event__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(8650);
/* harmony import */ var progress_event__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(progress_event__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var uuid__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(8767);
/* harmony import */ var wp_error__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(2884);
/* harmony import */ var wp_error__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(wp_error__WEBPACK_IMPORTED_MODULE_2__);




/**
 * debug instance
 */

const debug = debug__WEBPACK_IMPORTED_MODULE_0___default()('wpcom-proxy-request');
/**
 * WordPress.com REST API base endpoint.
 */

const proxyOrigin = 'https://public-api.wordpress.com';
/**
 * "Origin" of the current HTML page.
 */

const origin = window.location.protocol + '//' + window.location.host;
let onStreamRecord = null;
/**
 * Detecting support for the structured clone algorithm. IE8 and 9, and Firefox
 * 6.0 and below only support strings as postMessage's message. This browsers
 * will try to use the toString method.
 *
 * https://developer.mozilla.org/en-US/docs/Web/API/Window/postMessage
 * https://developer.mozilla.org/en-US/docs/Web/Guide/API/DOM/The_structured_clone_algorithm
 * https://github.com/Modernizr/Modernizr/issues/388#issuecomment-31127462
 */

const postStrings = (() => {
  let r = false;

  try {
    window.postMessage({
      toString: function () {
        r = true;
      }
    }, '*');
  } catch (e) {
    /* empty */
  }

  return r;
})();
/**
 * Test if the browser supports constructing a new `File` object. Not present on Edge and IE.
 */


const supportsFileConstructor = (() => {
  try {
    // eslint-disable-next-line no-new
    new window.File(['a'], 'test.jpg', {
      type: 'image/jpeg'
    });
    return true;
  } catch (e) {
    return false;
  }
})();
/**
 * Reference to the <iframe> DOM element.
 * Gets set in the install() function.
 */


let iframe = null;
/**
 * Set to `true` upon the iframe's "load" event.
 */

let loaded = false;
/**
 * Array of buffered API requests. Added to when API requests are done before the
 * proxy <iframe> is "loaded", and fulfilled once the "load" DOM event on the
 * iframe occurs.
 */

let buffered;
/**
 * In-flight API request XMLHttpRequest dummy "proxy" instances.
 */

const requests = {};
/**
 * Are HTML5 XMLHttpRequest2 "progress" events supported?
 * See: http://goo.gl/xxYf6D
 */

const supportsProgress = !!window.ProgressEvent && !!window.FormData;
debug('using "origin": %o', origin);
/**
 * Performs a "proxied REST API request". This happens by calling
 * `iframe.postMessage()` on the proxy iframe instance, which from there
 * takes care of WordPress.com user authentication (via the currently
 * logged-in user's cookies).
 *
 * @param {Object} originalParams - request parameters
 * @param {Function} [fn] - callback response
 * @returns {window.XMLHttpRequest} XMLHttpRequest instance
 */

const makeRequest = (originalParams, fn) => {
  const params = Object.assign({}, originalParams);
  debug('request(%o)', params); // inject the <iframe> upon the first proxied API request

  if (!iframe) {
    install();
  } // generate a uuid for this API request


  const id = (0,uuid__WEBPACK_IMPORTED_MODULE_3__/* ["default"] */ .Z)();
  params.callback = id;
  params.supports_args = true; // supports receiving variable amount of arguments

  params.supports_error_obj = true; // better Error object info

  params.supports_progress = supportsProgress; // supports receiving XHR "progress" events
  // force uppercase "method" since that's what the <iframe> is expecting

  params.method = String(params.method || 'GET').toUpperCase();
  debug('params object: %o', params);
  const xhr = new window.XMLHttpRequest();
  xhr.params = params; // store the `XMLHttpRequest` instance so that "onmessage" can access it again

  requests[id] = xhr;

  if ('function' === typeof fn) {
    // a callback function was provided
    let called = false;

    const xhrOnLoad = e => {
      if (called) {
        return;
      }

      called = true;
      const body = e.response ?? xhr.response;
      debug('body: ', body);
      debug('headers: ', e.headers);
      fn(null, body, e.headers);
    };

    const xhrOnError = e => {
      if (called) {
        return;
      }

      called = true;
      const error = e.error ?? e.err ?? e;
      debug('error: ', error);
      debug('headers: ', e.headers);
      fn(error, null, e.headers);
    };

    xhr.addEventListener('load', xhrOnLoad);
    xhr.addEventListener('abort', xhrOnError);
    xhr.addEventListener('error', xhrOnError);
  }

  if ('function' === typeof params.onStreamRecord) {
    // remove onStreamRecord param, which can’t be cloned
    onStreamRecord = params.onStreamRecord;
    delete params.onStreamRecord; // FIXME @azabani implement stream mode processing
    // Hint: port the algorithm from wpcom-xhr-request@1.2.0 to /public.api/rest-proxy/provider-
    // v2.0.js in rWP, then plumb stream records from onmessage below to onStreamRecord (or add
    // the XMLHttpRequest#response to ondownloadprogress there, then parse the chunks here).
  }

  if (loaded) {
    submitRequest(params);
  } else {
    debug('buffering API request since proxying <iframe> is not yet loaded');
    buffered.push(params);
  }

  return xhr;
};
/**
 * Performs a "proxied REST API request". This happens by calling
 * `iframe.postMessage()` on the proxy iframe instance, which from there
 * takes care of WordPress.com user authentication (via the currently
 * logged-in user's cookies).
 *
 * If no function is specified as second parameter, a promise is returned.
 *
 * @param {Object} originalParams - request parameters
 * @param {Function} [fn] - callback response
 * @returns {window.XMLHttpRequest|Promise} XMLHttpRequest instance or Promise
 */


const request = (originalParams, fn) => {
  // if callback is provided, behave traditionally
  if ('function' === typeof fn) {
    // request method
    return makeRequest(originalParams, fn);
  } // but if not, return a Promise


  return new Promise((res, rej) => {
    makeRequest(originalParams, (err, response) => {
      err ? rej(err) : res(response);
    });
  });
};
/**
 * Set proxy to "access all users' blogs" mode.
 */


function requestAllBlogsAccess() {
  return request({
    metaAPI: {
      accessAllUsersBlogs: true
    }
  });
}
/**
 * Calls the `postMessage()` function on the <iframe>.
 *
 * @param {Object} params
 */

function submitRequest(params) {
  debug('sending API request to proxy <iframe> %o', params); // `formData` needs to be patched if it contains `File` objects to work around
  // a Chrome bug. See `patchFileObjects` description for more details.

  if (params.formData) {
    patchFileObjects(params.formData);
  }

  iframe.contentWindow.postMessage(postStrings ? JSON.stringify(params) : params, proxyOrigin);
}
/**
 * Returns `true` if `v` is a DOM File instance, `false` otherwise.
 *
 * @param {any} v - instance to analyze
 * @returns {boolean} `true` if `v` is a DOM File instance
 */


function isFile(v) {
  return v && Object.prototype.toString.call(v) === '[object File]';
}
/*
 * Find a `File` object in a form data value. It can be either the value itself, or
 * in a `fileContents` property of the value.
 */


function getFileValue(v) {
  if (isFile(v)) {
    return v;
  }

  if (typeof v === 'object' && isFile(v.fileContents)) {
    return v.fileContents;
  }

  return null;
}
/**
 * Finds all `File` instances in `formData` and creates a new `File` instance whose storage is
 * forced to be a `Blob` instead of being backed by a file on disk. That works around a bug in
 * Chrome where `File` instances with `has_backing_file` flag cannot be sent over a process
 * boundary when site isolation is on.
 *
 * @see https://bugs.chromium.org/p/chromium/issues/detail?id=866805
 * @see https://bugs.chromium.org/p/chromium/issues/detail?id=631877
 * @param {Array} formData Form data to patch
 */


function patchFileObjects(formData) {
  // There are several landmines to avoid when making file uploads work on all browsers:
  // - the `new File()` constructor trick breaks file uploads on Safari 10 in a way that's
  //   impossible to detect: it will send empty files in the multipart/form-data body.
  //   Therefore we need to detect Chrome.
  // - IE11 and Edge don't support the `new File()` constructor at all. It will throw exception,
  //   so it's detectable by the `supportsFileConstructor` code.
  // - `window.chrome` exists also on Edge (!), `window.chrome.webstore` is only in Chrome and
  //   not in other Chromium based browsers (which have the site isolation bug, too).
  if (!window.chrome || !supportsFileConstructor) {
    return;
  }

  for (let i = 0; i < formData.length; i++) {
    const val = getFileValue(formData[i][1]);

    if (val) {
      formData[i][1] = new window.File([val], val.name, {
        type: val.type
      });
    }
  }
}
/**
 * Injects the proxy <iframe> instance in the <body> of the current
 * HTML page.
 */


function install() {
  debug('install()');

  if (iframe) {
    uninstall();
  }

  buffered = []; // listen to messages sent to `window`

  window.addEventListener('message', onmessage); // create the <iframe>

  iframe = document.createElement('iframe'); // set `src` and hide the iframe

  iframe.src = proxyOrigin + '/wp-admin/rest-proxy/?v=2.0#' + origin;
  iframe.style.display = 'none'; // inject the <iframe> into the <body>

  document.body.appendChild(iframe);
}
/**
 * Reloads the proxy iframe.
 */


const reloadProxy = () => {
  install();
};
/**
 * Removes the <iframe> proxy instance from the <body> of the page.
 */


function uninstall() {
  debug('uninstall()');
  window.removeEventListener('message', onmessage);
  document.body.removeChild(iframe);
  loaded = false;
  iframe = null;
}
/**
 * The proxy <iframe> instance's "load" event callback function.
 */


function onload() {
  debug('proxy <iframe> "load" event');
  loaded = true; // flush any buffered API calls

  if (buffered) {
    for (let i = 0; i < buffered.length; i++) {
      submitRequest(buffered[i]);
    }

    buffered = null;
  }
}
/**
 * The main `window` object's "message" event callback function.
 *
 * @param {window.Event} e
 */


function onmessage(e) {
  var _iframe;

  // If the iframe was never loaded, this message might be unrelated.
  if (!((_iframe = iframe) !== null && _iframe !== void 0 && _iframe.contentWindow)) {
    return;
  }

  debug('onmessage'); // Filter out messages from different origins

  if (e.origin !== proxyOrigin) {
    debug('ignoring message... %o !== %o', e.origin, proxyOrigin);
    return;
  } // Filter out messages from different iframes


  if (e.source !== iframe.contentWindow) {
    debug('ignoring message... iframe elements do not match');
    return;
  }

  let {
    data
  } = e;

  if (!data) {
    return debug('no `data`, bailing');
  } // Once the iframe is loaded, we can start using it.


  if (data === 'ready') {
    onload();
    return;
  }

  if (postStrings && 'string' === typeof data) {
    data = JSON.parse(data);
  } // check if we're receiving a "progress" event


  if (data.upload || data.download) {
    return onprogress(data);
  }

  if (!data.length) {
    return debug("`e.data` doesn't appear to be an Array, bailing...");
  } // first get the `xhr` instance that we're interested in


  const id = data[data.length - 1];

  if (!(id in requests)) {
    return debug('bailing, no matching request with callback: %o', id);
  }

  const xhr = requests[id]; // Build `error` and `body` object from the `data` object

  const {
    params
  } = xhr;
  const body = data[0];
  let statusCode = data[1];
  const headers = data[2]; // We don't want to delete requests while we're processing stream messages

  if (statusCode === 207) {// 207 is a signal from rest-proxy. It means, "this isn't the final
    // response to the query." The proxy supports WebSocket connections
    // by invoking the original success callback for each message received.
  } else {
    // this is the final response to this query
    delete requests[id];
  }

  if (!params.metaAPI) {
    debug('got %o status code for URL: %o', statusCode, params.path);
  } else {
    statusCode = body === 'metaAPIupdated' ? 200 : 500;
  }

  if (typeof headers === 'object') {
    // add statusCode into headers object
    headers.status = statusCode;

    if (shouldProcessInStreamMode(headers['Content-Type'])) {
      if (statusCode === 207) {
        onStreamRecord(body);
        return;
      }
    }
  }

  if (statusCode && 2 === Math.floor(statusCode / 100)) {
    // 2xx status code, success
    resolve(xhr, body, headers);
  } else {
    // any other status code is a failure
    const wpe = wp_error__WEBPACK_IMPORTED_MODULE_2___default()(params, statusCode, body);
    reject(xhr, wpe, headers);
  }
}
/**
 * Returns true iff stream mode processing is required (see wpcom-xhr-request@1.2.0).
 *
 * @param {string} contentType response Content-Type header value
 */


function shouldProcessInStreamMode(contentType) {
  return /^application[/]x-ndjson($|;)/.test(contentType);
}
/**
 * Handles a "progress" event being proxied back from the iframe page.
 *
 * @param {Object} data
 */


function onprogress(data) {
  debug('got "progress" event: %o', data);
  const xhr = requests[data.callbackId];

  if (xhr) {
    const prog = new (progress_event__WEBPACK_IMPORTED_MODULE_1___default())('progress', data);
    const target = data.upload ? xhr.upload : xhr;
    target.dispatchEvent(prog);
  }
}
/**
 * Emits the "load" event on the `xhr`.
 *
 * @param {window.XMLHttpRequest} xhr
 * @param {Object} body
 */


function resolve(xhr, body, headers) {
  const e = new (progress_event__WEBPACK_IMPORTED_MODULE_1___default())('load');
  e.data = e.body = e.response = body;
  e.headers = headers;
  xhr.dispatchEvent(e);
}
/**
 * Emits the "error" event on the `xhr`.
 *
 * @param {window.XMLHttpRequest} xhr
 * @param {Error} err
 */


function reject(xhr, err, headers) {
  const e = new (progress_event__WEBPACK_IMPORTED_MODULE_1___default())('error');
  e.error = e.err = err;
  e.headers = headers;
  xhr.dispatchEvent(e);
} // list of valid origins for wpcom requests.
// taken from wpcom-proxy-request (rest-proxy/provider-v2.0.js)


const wpcomAllowedOrigins = (/* unused pure expression or super */ null && (['https://wordpress.com', 'https://cloud.jetpack.com', 'http://wpcalypso.wordpress.com', // for running docker on dev instances
'http://widgets.wp.com', 'https://widgets.wp.com', 'https://dev-mc.a8c.com', 'https://mc.a8c.com', 'https://dserve.a8c.com', 'http://calypso.localhost:3000', 'https://calypso.localhost:3000', 'http://jetpack.cloud.localhost:3000', 'https://jetpack.cloud.localhost:3000', 'http://calypso.localhost:3001', 'https://calypso.localhost:3001', 'https://calypso.live', 'http://127.0.0.1:41050', 'http://send.linguine.localhost:3000']));
/**
 * Shelved from rest-proxy/provider-v2.0.js.
 * This returns true for all WPCOM origins except Atomic sites.
 *
 * @param urlOrigin
 * @returns
 */

function isAllowedOrigin(urlOrigin) {
  // sites in the allow-list and some subdomains of "calypso.live" and "wordpress.com"
  // are allowed without further check
  return wpcomAllowedOrigins.includes(urlOrigin) || /^https:\/\/[a-z0-9-]+\.calypso\.live$/.test(urlOrigin) || /^https:\/\/([a-z0-9-]+\.)+wordpress\.com$/.test(urlOrigin);
}

function canAccessWpcomApis() {
  return isAllowedOrigin(window.location.origin);
}
/**
 * Export `request` function.
 */


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (request);


/***/ }),

/***/ 8049:
/***/ ((module, exports, __webpack_require__) => {

/* eslint-env browser */

/**
 * This is the web browser implementation of `debug()`.
 */
exports.formatArgs = formatArgs;
exports.save = save;
exports.load = load;
exports.useColors = useColors;
exports.storage = localstorage();

exports.destroy = (() => {
  let warned = false;
  return () => {
    if (!warned) {
      warned = true;
      console.warn('Instance method `debug.destroy()` is deprecated and no longer does anything. It will be removed in the next major version of `debug`.');
    }
  };
})();
/**
 * Colors.
 */


exports.colors = ['#0000CC', '#0000FF', '#0033CC', '#0033FF', '#0066CC', '#0066FF', '#0099CC', '#0099FF', '#00CC00', '#00CC33', '#00CC66', '#00CC99', '#00CCCC', '#00CCFF', '#3300CC', '#3300FF', '#3333CC', '#3333FF', '#3366CC', '#3366FF', '#3399CC', '#3399FF', '#33CC00', '#33CC33', '#33CC66', '#33CC99', '#33CCCC', '#33CCFF', '#6600CC', '#6600FF', '#6633CC', '#6633FF', '#66CC00', '#66CC33', '#9900CC', '#9900FF', '#9933CC', '#9933FF', '#99CC00', '#99CC33', '#CC0000', '#CC0033', '#CC0066', '#CC0099', '#CC00CC', '#CC00FF', '#CC3300', '#CC3333', '#CC3366', '#CC3399', '#CC33CC', '#CC33FF', '#CC6600', '#CC6633', '#CC9900', '#CC9933', '#CCCC00', '#CCCC33', '#FF0000', '#FF0033', '#FF0066', '#FF0099', '#FF00CC', '#FF00FF', '#FF3300', '#FF3333', '#FF3366', '#FF3399', '#FF33CC', '#FF33FF', '#FF6600', '#FF6633', '#FF9900', '#FF9933', '#FFCC00', '#FFCC33'];
/**
 * Currently only WebKit-based Web Inspectors, Firefox >= v31,
 * and the Firebug extension (any Firefox version) are known
 * to support "%c" CSS customizations.
 *
 * TODO: add a `localStorage` variable to explicitly enable/disable colors
 */
// eslint-disable-next-line complexity

function useColors() {
  // NB: In an Electron preload script, document will be defined but not fully
  // initialized. Since we know we're in Chrome, we'll just detect this case
  // explicitly
  if (typeof window !== 'undefined' && window.process && (window.process.type === 'renderer' || window.process.__nwjs)) {
    return true;
  } // Internet Explorer and Edge do not support colors.


  if (typeof navigator !== 'undefined' && navigator.userAgent && navigator.userAgent.toLowerCase().match(/(edge|trident)\/(\d+)/)) {
    return false;
  } // Is webkit? http://stackoverflow.com/a/16459606/376773
  // document is undefined in react-native: https://github.com/facebook/react-native/pull/1632


  return typeof document !== 'undefined' && document.documentElement && document.documentElement.style && document.documentElement.style.WebkitAppearance || // Is firebug? http://stackoverflow.com/a/398120/376773
  typeof window !== 'undefined' && window.console && (window.console.firebug || window.console.exception && window.console.table) || // Is firefox >= v31?
  // https://developer.mozilla.org/en-US/docs/Tools/Web_Console#Styling_messages
  typeof navigator !== 'undefined' && navigator.userAgent && navigator.userAgent.toLowerCase().match(/firefox\/(\d+)/) && parseInt(RegExp.$1, 10) >= 31 || // Double check webkit in userAgent just in case we are in a worker
  typeof navigator !== 'undefined' && navigator.userAgent && navigator.userAgent.toLowerCase().match(/applewebkit\/(\d+)/);
}
/**
 * Colorize log arguments if enabled.
 *
 * @api public
 */


function formatArgs(args) {
  args[0] = (this.useColors ? '%c' : '') + this.namespace + (this.useColors ? ' %c' : ' ') + args[0] + (this.useColors ? '%c ' : ' ') + '+' + module.exports.humanize(this.diff);

  if (!this.useColors) {
    return;
  }

  const c = 'color: ' + this.color;
  args.splice(1, 0, c, 'color: inherit'); // The final "%c" is somewhat tricky, because there could be other
  // arguments passed either before or after the %c, so we need to
  // figure out the correct index to insert the CSS into

  let index = 0;
  let lastC = 0;
  args[0].replace(/%[a-zA-Z%]/g, match => {
    if (match === '%%') {
      return;
    }

    index++;

    if (match === '%c') {
      // We only are interested in the *last* %c
      // (the user may have provided their own)
      lastC = index;
    }
  });
  args.splice(lastC, 0, c);
}
/**
 * Invokes `console.debug()` when available.
 * No-op when `console.debug` is not a "function".
 * If `console.debug` is not available, falls back
 * to `console.log`.
 *
 * @api public
 */


exports.log = console.debug || console.log || (() => {});
/**
 * Save `namespaces`.
 *
 * @param {String} namespaces
 * @api private
 */


function save(namespaces) {
  try {
    if (namespaces) {
      exports.storage.setItem('debug', namespaces);
    } else {
      exports.storage.removeItem('debug');
    }
  } catch (error) {// Swallow
    // XXX (@Qix-) should we be logging these?
  }
}
/**
 * Load `namespaces`.
 *
 * @return {String} returns the previously persisted debug modes
 * @api private
 */


function load() {
  let r;

  try {
    r = exports.storage.getItem('debug');
  } catch (error) {// Swallow
    // XXX (@Qix-) should we be logging these?
  } // If debug isn't set in LS, and we're in Electron, try to load $DEBUG


  if (!r && typeof process !== 'undefined' && 'env' in process) {
    r = process.env.DEBUG;
  }

  return r;
}
/**
 * Localstorage attempts to return the localstorage.
 *
 * This is necessary because safari throws
 * when a user disables cookies/localstorage
 * and you attempt to access it.
 *
 * @return {LocalStorage}
 * @api private
 */


function localstorage() {
  try {
    // TVMLKit (Apple TV JS Runtime) does not have a window object, just localStorage in the global context
    // The Browser also has localStorage in the global context.
    return localStorage;
  } catch (error) {// Swallow
    // XXX (@Qix-) should we be logging these?
  }
}

module.exports = __webpack_require__(2632)(exports);
const {
  formatters
} = module.exports;
/**
 * Map %j to `JSON.stringify()`, since no Web Inspectors do that by default.
 */

formatters.j = function (v) {
  try {
    return JSON.stringify(v);
  } catch (error) {
    return '[UnexpectedJSONParseError]: ' + error.message;
  }
};

/***/ }),

/***/ 2632:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

/**
 * This is the common logic for both the Node.js and web browser
 * implementations of `debug()`.
 */
function setup(env) {
  createDebug.debug = createDebug;
  createDebug.default = createDebug;
  createDebug.coerce = coerce;
  createDebug.disable = disable;
  createDebug.enable = enable;
  createDebug.enabled = enabled;
  createDebug.humanize = __webpack_require__(5881);
  createDebug.destroy = destroy;
  Object.keys(env).forEach(key => {
    createDebug[key] = env[key];
  });
  /**
  * The currently active debug mode names, and names to skip.
  */

  createDebug.names = [];
  createDebug.skips = [];
  /**
  * Map of special "%n" handling functions, for the debug "format" argument.
  *
  * Valid key names are a single, lower or upper-case letter, i.e. "n" and "N".
  */

  createDebug.formatters = {};
  /**
  * Selects a color for a debug namespace
  * @param {String} namespace The namespace string for the debug instance to be colored
  * @return {Number|String} An ANSI color code for the given namespace
  * @api private
  */

  function selectColor(namespace) {
    let hash = 0;

    for (let i = 0; i < namespace.length; i++) {
      hash = (hash << 5) - hash + namespace.charCodeAt(i);
      hash |= 0; // Convert to 32bit integer
    }

    return createDebug.colors[Math.abs(hash) % createDebug.colors.length];
  }

  createDebug.selectColor = selectColor;
  /**
  * Create a debugger with the given `namespace`.
  *
  * @param {String} namespace
  * @return {Function}
  * @api public
  */

  function createDebug(namespace) {
    let prevTime;
    let enableOverride = null;
    let namespacesCache;
    let enabledCache;

    function debug() {
      for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
        args[_key] = arguments[_key];
      }

      // Disabled?
      if (!debug.enabled) {
        return;
      }

      const self = debug; // Set `diff` timestamp

      const curr = Number(new Date());
      const ms = curr - (prevTime || curr);
      self.diff = ms;
      self.prev = prevTime;
      self.curr = curr;
      prevTime = curr;
      args[0] = createDebug.coerce(args[0]);

      if (typeof args[0] !== 'string') {
        // Anything else let's inspect with %O
        args.unshift('%O');
      } // Apply any `formatters` transformations


      let index = 0;
      args[0] = args[0].replace(/%([a-zA-Z%])/g, (match, format) => {
        // If we encounter an escaped % then don't increase the array index
        if (match === '%%') {
          return '%';
        }

        index++;
        const formatter = createDebug.formatters[format];

        if (typeof formatter === 'function') {
          const val = args[index];
          match = formatter.call(self, val); // Now we need to remove `args[index]` since it's inlined in the `format`

          args.splice(index, 1);
          index--;
        }

        return match;
      }); // Apply env-specific formatting (colors, etc.)

      createDebug.formatArgs.call(self, args);
      const logFn = self.log || createDebug.log;
      logFn.apply(self, args);
    }

    debug.namespace = namespace;
    debug.useColors = createDebug.useColors();
    debug.color = createDebug.selectColor(namespace);
    debug.extend = extend;
    debug.destroy = createDebug.destroy; // XXX Temporary. Will be removed in the next major release.

    Object.defineProperty(debug, 'enabled', {
      enumerable: true,
      configurable: false,
      get: () => {
        if (enableOverride !== null) {
          return enableOverride;
        }

        if (namespacesCache !== createDebug.namespaces) {
          namespacesCache = createDebug.namespaces;
          enabledCache = createDebug.enabled(namespace);
        }

        return enabledCache;
      },
      set: v => {
        enableOverride = v;
      }
    }); // Env-specific initialization logic for debug instances

    if (typeof createDebug.init === 'function') {
      createDebug.init(debug);
    }

    return debug;
  }

  function extend(namespace, delimiter) {
    const newDebug = createDebug(this.namespace + (typeof delimiter === 'undefined' ? ':' : delimiter) + namespace);
    newDebug.log = this.log;
    return newDebug;
  }
  /**
  * Enables a debug mode by namespaces. This can include modes
  * separated by a colon and wildcards.
  *
  * @param {String} namespaces
  * @api public
  */


  function enable(namespaces) {
    createDebug.save(namespaces);
    createDebug.namespaces = namespaces;
    createDebug.names = [];
    createDebug.skips = [];
    let i;
    const split = (typeof namespaces === 'string' ? namespaces : '').split(/[\s,]+/);
    const len = split.length;

    for (i = 0; i < len; i++) {
      if (!split[i]) {
        // ignore empty strings
        continue;
      }

      namespaces = split[i].replace(/\*/g, '.*?');

      if (namespaces[0] === '-') {
        createDebug.skips.push(new RegExp('^' + namespaces.slice(1) + '$'));
      } else {
        createDebug.names.push(new RegExp('^' + namespaces + '$'));
      }
    }
  }
  /**
  * Disable debug output.
  *
  * @return {String} namespaces
  * @api public
  */


  function disable() {
    const namespaces = [...createDebug.names.map(toNamespace), ...createDebug.skips.map(toNamespace).map(namespace => '-' + namespace)].join(',');
    createDebug.enable('');
    return namespaces;
  }
  /**
  * Returns true if the given mode name is enabled, false otherwise.
  *
  * @param {String} name
  * @return {Boolean}
  * @api public
  */


  function enabled(name) {
    if (name[name.length - 1] === '*') {
      return true;
    }

    let i;
    let len;

    for (i = 0, len = createDebug.skips.length; i < len; i++) {
      if (createDebug.skips[i].test(name)) {
        return false;
      }
    }

    for (i = 0, len = createDebug.names.length; i < len; i++) {
      if (createDebug.names[i].test(name)) {
        return true;
      }
    }

    return false;
  }
  /**
  * Convert regexp to namespace
  *
  * @param {RegExp} regxep
  * @return {String} namespace
  * @api private
  */


  function toNamespace(regexp) {
    return regexp.toString().substring(2, regexp.toString().length - 2).replace(/\.\*\?$/, '*');
  }
  /**
  * Coerce `val`.
  *
  * @param {Mixed} val
  * @return {Mixed}
  * @api private
  */


  function coerce(val) {
    if (val instanceof Error) {
      return val.stack || val.message;
    }

    return val;
  }
  /**
  * XXX DO NOT USE. This is a temporary stub function.
  * XXX It WILL be removed in the next major release.
  */


  function destroy() {
    console.warn('Instance method `debug.destroy()` is deprecated and no longer does anything. It will be removed in the next major version of `debug`.');
  }

  createDebug.enable(createDebug.load());
  return createDebug;
}

module.exports = setup;

/***/ }),

/***/ 3830:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";

var camelCase = __webpack_require__(956);

module.exports = function () {
	var cased = camelCase.apply(camelCase, arguments);
	return cased.charAt(0).toUpperCase() + cased.slice(1);
};


/***/ }),

/***/ 956:
/***/ ((module) => {

"use strict";

module.exports = function () {
	var str = [].map.call(arguments, function (str) {
		return str.trim();
	}).filter(function (str) {
		return str.length;
	}).join('-');

	if (!str.length) {
		return '';
	}

	if (str.length === 1 || !(/[_.\- ]+/).test(str) ) {
		if (str[0] === str[0].toLowerCase() && str.slice(1) !== str.slice(1).toLowerCase()) {
			return str;
		}

		return str.toLowerCase();
	}

	return str
	.replace(/^[_.\- ]+/, '')
	.toLowerCase()
	.replace(/[_.\- ]+(\w|$)/g, function (m, p1) {
		return p1.toUpperCase();
	});
};


/***/ }),

/***/ 2686:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (/^(?:[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}|00000000-0000-0000-0000-000000000000)$/i);

/***/ }),

/***/ 5302:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (/* binding */ rng)
/* harmony export */ });
// Unique ID creation requires a high quality random # generator. In the browser we therefore
// require the crypto API and do not support built-in fallback to lower quality random number
// generators (like Math.random()).
var getRandomValues;
var rnds8 = new Uint8Array(16);
function rng() {
  // lazy load so that environments that need to polyfill have a chance to do so
  if (!getRandomValues) {
    // getRandomValues needs to be invoked in a context where "this" is a Crypto implementation. Also,
    // find the complete implementation of crypto (msCrypto) on IE11.
    getRandomValues = typeof crypto !== 'undefined' && crypto.getRandomValues && crypto.getRandomValues.bind(crypto) || typeof msCrypto !== 'undefined' && typeof msCrypto.getRandomValues === 'function' && msCrypto.getRandomValues.bind(msCrypto);

    if (!getRandomValues) {
      throw new Error('crypto.getRandomValues() not supported. See https://github.com/uuidjs/uuid#getrandomvalues-not-supported');
    }
  }

  return getRandomValues(rnds8);
}

/***/ }),

/***/ 708:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _validate_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(6525);

/**
 * Convert array of 16 byte values to UUID string format of the form:
 * XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX
 */

var byteToHex = [];

for (var i = 0; i < 256; ++i) {
  byteToHex.push((i + 0x100).toString(16).substr(1));
}

function stringify(arr) {
  var offset = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
  // Note: Be careful editing this code!  It's been tuned for performance
  // and works in ways you may not expect. See https://github.com/uuidjs/uuid/pull/434
  var uuid = (byteToHex[arr[offset + 0]] + byteToHex[arr[offset + 1]] + byteToHex[arr[offset + 2]] + byteToHex[arr[offset + 3]] + '-' + byteToHex[arr[offset + 4]] + byteToHex[arr[offset + 5]] + '-' + byteToHex[arr[offset + 6]] + byteToHex[arr[offset + 7]] + '-' + byteToHex[arr[offset + 8]] + byteToHex[arr[offset + 9]] + '-' + byteToHex[arr[offset + 10]] + byteToHex[arr[offset + 11]] + byteToHex[arr[offset + 12]] + byteToHex[arr[offset + 13]] + byteToHex[arr[offset + 14]] + byteToHex[arr[offset + 15]]).toLowerCase(); // Consistency check for valid UUID.  If this throws, it's likely due to one
  // of the following:
  // - One or more input array values don't map to a hex octet (leading to
  // "undefined" in the uuid)
  // - Invalid input values for the RFC `version` or `variant` fields

  if (!(0,_validate_js__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z)(uuid)) {
    throw TypeError('Stringified UUID is invalid');
  }

  return uuid;
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (stringify);

/***/ }),

/***/ 8767:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _rng_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(5302);
/* harmony import */ var _stringify_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(708);



function v4(options, buf, offset) {
  options = options || {};
  var rnds = options.random || (options.rng || _rng_js__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z)(); // Per 4.4, set bits for version and `clock_seq_hi_and_reserved`

  rnds[6] = rnds[6] & 0x0f | 0x40;
  rnds[8] = rnds[8] & 0x3f | 0x80; // Copy bytes to buffer, if provided

  if (buf) {
    offset = offset || 0;

    for (var i = 0; i < 16; ++i) {
      buf[offset + i] = rnds[i];
    }

    return buf;
  }

  return (0,_stringify_js__WEBPACK_IMPORTED_MODULE_1__/* ["default"] */ .Z)(rnds);
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (v4);

/***/ }),

/***/ 6525:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _regex_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(2686);


function validate(uuid) {
  return typeof uuid === 'string' && _regex_js__WEBPACK_IMPORTED_MODULE_0__/* ["default"].test */ .Z.test(uuid);
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (validate);

/***/ }),

/***/ 6468:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;

var _toDate = _interopRequireDefault(__webpack_require__(7499));

var _toFloat = _interopRequireDefault(__webpack_require__(6840));

var _toInt = _interopRequireDefault(__webpack_require__(7155));

var _toBoolean = _interopRequireDefault(__webpack_require__(8676));

var _equals = _interopRequireDefault(__webpack_require__(7133));

var _contains = _interopRequireDefault(__webpack_require__(4228));

var _matches = _interopRequireDefault(__webpack_require__(401));

var _isEmail = _interopRequireDefault(__webpack_require__(4525));

var _isURL = _interopRequireDefault(__webpack_require__(4922));

var _isMACAddress = _interopRequireDefault(__webpack_require__(5504));

var _isIP = _interopRequireDefault(__webpack_require__(7962));

var _isIPRange = _interopRequireDefault(__webpack_require__(5482));

var _isFQDN = _interopRequireDefault(__webpack_require__(8139));

var _isDate = _interopRequireDefault(__webpack_require__(7869));

var _isBoolean = _interopRequireDefault(__webpack_require__(5982));

var _isLocale = _interopRequireDefault(__webpack_require__(4452));

var _isAlpha = _interopRequireWildcard(__webpack_require__(7780));

var _isAlphanumeric = _interopRequireWildcard(__webpack_require__(7014));

var _isNumeric = _interopRequireDefault(__webpack_require__(3024));

var _isPassportNumber = _interopRequireDefault(__webpack_require__(2249));

var _isPort = _interopRequireDefault(__webpack_require__(7616));

var _isLowercase = _interopRequireDefault(__webpack_require__(8816));

var _isUppercase = _interopRequireDefault(__webpack_require__(1776));

var _isIMEI = _interopRequireDefault(__webpack_require__(1893));

var _isAscii = _interopRequireDefault(__webpack_require__(5670));

var _isFullWidth = _interopRequireDefault(__webpack_require__(749));

var _isHalfWidth = _interopRequireDefault(__webpack_require__(8408));

var _isVariableWidth = _interopRequireDefault(__webpack_require__(8831));

var _isMultibyte = _interopRequireDefault(__webpack_require__(3639));

var _isSemVer = _interopRequireDefault(__webpack_require__(2868));

var _isSurrogatePair = _interopRequireDefault(__webpack_require__(8868));

var _isInt = _interopRequireDefault(__webpack_require__(4503));

var _isFloat = _interopRequireWildcard(__webpack_require__(3612));

var _isDecimal = _interopRequireDefault(__webpack_require__(8250));

var _isHexadecimal = _interopRequireDefault(__webpack_require__(9985));

var _isOctal = _interopRequireDefault(__webpack_require__(6590));

var _isDivisibleBy = _interopRequireDefault(__webpack_require__(102));

var _isHexColor = _interopRequireDefault(__webpack_require__(6941));

var _isRgbColor = _interopRequireDefault(__webpack_require__(8270));

var _isHSL = _interopRequireDefault(__webpack_require__(841));

var _isISRC = _interopRequireDefault(__webpack_require__(6557));

var _isIBAN = _interopRequireDefault(__webpack_require__(7937));

var _isBIC = _interopRequireDefault(__webpack_require__(2740));

var _isMD = _interopRequireDefault(__webpack_require__(6362));

var _isHash = _interopRequireDefault(__webpack_require__(9749));

var _isJWT = _interopRequireDefault(__webpack_require__(1624));

var _isJSON = _interopRequireDefault(__webpack_require__(5067));

var _isEmpty = _interopRequireDefault(__webpack_require__(1964));

var _isLength = _interopRequireDefault(__webpack_require__(6500));

var _isByteLength = _interopRequireDefault(__webpack_require__(2775));

var _isUUID = _interopRequireDefault(__webpack_require__(6368));

var _isMongoId = _interopRequireDefault(__webpack_require__(4246));

var _isAfter = _interopRequireDefault(__webpack_require__(6623));

var _isBefore = _interopRequireDefault(__webpack_require__(5442));

var _isIn = _interopRequireDefault(__webpack_require__(2093));

var _isCreditCard = _interopRequireDefault(__webpack_require__(4235));

var _isIdentityCard = _interopRequireDefault(__webpack_require__(2250));

var _isEAN = _interopRequireDefault(__webpack_require__(9653));

var _isISIN = _interopRequireDefault(__webpack_require__(2390));

var _isISBN = _interopRequireDefault(__webpack_require__(6328));

var _isISSN = _interopRequireDefault(__webpack_require__(2985));

var _isTaxID = _interopRequireDefault(__webpack_require__(6693));

var _isMobilePhone = _interopRequireWildcard(__webpack_require__(5119));

var _isEthereumAddress = _interopRequireDefault(__webpack_require__(3821));

var _isCurrency = _interopRequireDefault(__webpack_require__(7610));

var _isBtcAddress = _interopRequireDefault(__webpack_require__(8369));

var _isISO = _interopRequireDefault(__webpack_require__(8932));

var _isRFC = _interopRequireDefault(__webpack_require__(593));

var _isISO31661Alpha = _interopRequireDefault(__webpack_require__(928));

var _isISO31661Alpha2 = _interopRequireDefault(__webpack_require__(2038));

var _isBase = _interopRequireDefault(__webpack_require__(6533));

var _isBase2 = _interopRequireDefault(__webpack_require__(4039));

var _isBase3 = _interopRequireDefault(__webpack_require__(438));

var _isDataURI = _interopRequireDefault(__webpack_require__(8305));

var _isMagnetURI = _interopRequireDefault(__webpack_require__(2896));

var _isMimeType = _interopRequireDefault(__webpack_require__(7620));

var _isLatLong = _interopRequireDefault(__webpack_require__(2863));

var _isPostalCode = _interopRequireWildcard(__webpack_require__(2456));

var _ltrim = _interopRequireDefault(__webpack_require__(5904));

var _rtrim = _interopRequireDefault(__webpack_require__(1733));

var _trim = _interopRequireDefault(__webpack_require__(3465));

var _escape = _interopRequireDefault(__webpack_require__(7879));

var _unescape = _interopRequireDefault(__webpack_require__(3991));

var _stripLow = _interopRequireDefault(__webpack_require__(4559));

var _whitelist = _interopRequireDefault(__webpack_require__(7224));

var _blacklist = _interopRequireDefault(__webpack_require__(7902));

var _isWhitelisted = _interopRequireDefault(__webpack_require__(9293));

var _normalizeEmail = _interopRequireDefault(__webpack_require__(3517));

var _isSlug = _interopRequireDefault(__webpack_require__(4189));

var _isStrongPassword = _interopRequireDefault(__webpack_require__(2487));

var _isVAT = _interopRequireDefault(__webpack_require__(1809));

function _getRequireWildcardCache() { if (typeof WeakMap !== "function") return null; var cache = new WeakMap(); _getRequireWildcardCache = function _getRequireWildcardCache() { return cache; }; return cache; }

function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } if (obj === null || _typeof(obj) !== "object" && typeof obj !== "function") { return { default: obj }; } var cache = _getRequireWildcardCache(); if (cache && cache.has(obj)) { return cache.get(obj); } var newObj = {}; var hasPropertyDescriptor = Object.defineProperty && Object.getOwnPropertyDescriptor; for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) { var desc = hasPropertyDescriptor ? Object.getOwnPropertyDescriptor(obj, key) : null; if (desc && (desc.get || desc.set)) { Object.defineProperty(newObj, key, desc); } else { newObj[key] = obj[key]; } } } newObj.default = obj; if (cache) { cache.set(obj, newObj); } return newObj; }

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var version = '13.5.2';
var validator = {
  version: version,
  toDate: _toDate.default,
  toFloat: _toFloat.default,
  toInt: _toInt.default,
  toBoolean: _toBoolean.default,
  equals: _equals.default,
  contains: _contains.default,
  matches: _matches.default,
  isEmail: _isEmail.default,
  isURL: _isURL.default,
  isMACAddress: _isMACAddress.default,
  isIP: _isIP.default,
  isIPRange: _isIPRange.default,
  isFQDN: _isFQDN.default,
  isBoolean: _isBoolean.default,
  isIBAN: _isIBAN.default,
  isBIC: _isBIC.default,
  isAlpha: _isAlpha.default,
  isAlphaLocales: _isAlpha.locales,
  isAlphanumeric: _isAlphanumeric.default,
  isAlphanumericLocales: _isAlphanumeric.locales,
  isNumeric: _isNumeric.default,
  isPassportNumber: _isPassportNumber.default,
  isPort: _isPort.default,
  isLowercase: _isLowercase.default,
  isUppercase: _isUppercase.default,
  isAscii: _isAscii.default,
  isFullWidth: _isFullWidth.default,
  isHalfWidth: _isHalfWidth.default,
  isVariableWidth: _isVariableWidth.default,
  isMultibyte: _isMultibyte.default,
  isSemVer: _isSemVer.default,
  isSurrogatePair: _isSurrogatePair.default,
  isInt: _isInt.default,
  isIMEI: _isIMEI.default,
  isFloat: _isFloat.default,
  isFloatLocales: _isFloat.locales,
  isDecimal: _isDecimal.default,
  isHexadecimal: _isHexadecimal.default,
  isOctal: _isOctal.default,
  isDivisibleBy: _isDivisibleBy.default,
  isHexColor: _isHexColor.default,
  isRgbColor: _isRgbColor.default,
  isHSL: _isHSL.default,
  isISRC: _isISRC.default,
  isMD5: _isMD.default,
  isHash: _isHash.default,
  isJWT: _isJWT.default,
  isJSON: _isJSON.default,
  isEmpty: _isEmpty.default,
  isLength: _isLength.default,
  isLocale: _isLocale.default,
  isByteLength: _isByteLength.default,
  isUUID: _isUUID.default,
  isMongoId: _isMongoId.default,
  isAfter: _isAfter.default,
  isBefore: _isBefore.default,
  isIn: _isIn.default,
  isCreditCard: _isCreditCard.default,
  isIdentityCard: _isIdentityCard.default,
  isEAN: _isEAN.default,
  isISIN: _isISIN.default,
  isISBN: _isISBN.default,
  isISSN: _isISSN.default,
  isMobilePhone: _isMobilePhone.default,
  isMobilePhoneLocales: _isMobilePhone.locales,
  isPostalCode: _isPostalCode.default,
  isPostalCodeLocales: _isPostalCode.locales,
  isEthereumAddress: _isEthereumAddress.default,
  isCurrency: _isCurrency.default,
  isBtcAddress: _isBtcAddress.default,
  isISO8601: _isISO.default,
  isRFC3339: _isRFC.default,
  isISO31661Alpha2: _isISO31661Alpha.default,
  isISO31661Alpha3: _isISO31661Alpha2.default,
  isBase32: _isBase.default,
  isBase58: _isBase2.default,
  isBase64: _isBase3.default,
  isDataURI: _isDataURI.default,
  isMagnetURI: _isMagnetURI.default,
  isMimeType: _isMimeType.default,
  isLatLong: _isLatLong.default,
  ltrim: _ltrim.default,
  rtrim: _rtrim.default,
  trim: _trim.default,
  escape: _escape.default,
  unescape: _unescape.default,
  stripLow: _stripLow.default,
  whitelist: _whitelist.default,
  blacklist: _blacklist.default,
  isWhitelisted: _isWhitelisted.default,
  normalizeEmail: _normalizeEmail.default,
  toString: toString,
  isSlug: _isSlug.default,
  isStrongPassword: _isStrongPassword.default,
  isTaxID: _isTaxID.default,
  isDate: _isDate.default,
  isVAT: _isVAT.default
};
var _default = validator;
exports["default"] = _default;
module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 5475:
/***/ ((__unused_webpack_module, exports) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.commaDecimal = exports.dotDecimal = exports.farsiLocales = exports.arabicLocales = exports.englishLocales = exports.decimal = exports.alphanumeric = exports.alpha = void 0;
var alpha = {
  'en-US': /^[A-Z]+$/i,
  'az-AZ': /^[A-VXYZÇƏĞİıÖŞÜ]+$/i,
  'bg-BG': /^[А-Я]+$/i,
  'cs-CZ': /^[A-ZÁČĎÉĚÍŇÓŘŠŤÚŮÝŽ]+$/i,
  'da-DK': /^[A-ZÆØÅ]+$/i,
  'de-DE': /^[A-ZÄÖÜß]+$/i,
  'el-GR': /^[Α-ώ]+$/i,
  'es-ES': /^[A-ZÁÉÍÑÓÚÜ]+$/i,
  'fa-IR': /^[ابپتثجچحخدذرزژسشصضطظعغفقکگلمنوهی]+$/i,
  'fr-FR': /^[A-ZÀÂÆÇÉÈÊËÏÎÔŒÙÛÜŸ]+$/i,
  'it-IT': /^[A-ZÀÉÈÌÎÓÒÙ]+$/i,
  'nb-NO': /^[A-ZÆØÅ]+$/i,
  'nl-NL': /^[A-ZÁÉËÏÓÖÜÚ]+$/i,
  'nn-NO': /^[A-ZÆØÅ]+$/i,
  'hu-HU': /^[A-ZÁÉÍÓÖŐÚÜŰ]+$/i,
  'pl-PL': /^[A-ZĄĆĘŚŁŃÓŻŹ]+$/i,
  'pt-PT': /^[A-ZÃÁÀÂÄÇÉÊËÍÏÕÓÔÖÚÜ]+$/i,
  'ru-RU': /^[А-ЯЁ]+$/i,
  'sl-SI': /^[A-ZČĆĐŠŽ]+$/i,
  'sk-SK': /^[A-ZÁČĎÉÍŇÓŠŤÚÝŽĹŔĽÄÔ]+$/i,
  'sr-RS@latin': /^[A-ZČĆŽŠĐ]+$/i,
  'sr-RS': /^[А-ЯЂЈЉЊЋЏ]+$/i,
  'sv-SE': /^[A-ZÅÄÖ]+$/i,
  'th-TH': /^[ก-๐\s]+$/i,
  'tr-TR': /^[A-ZÇĞİıÖŞÜ]+$/i,
  'uk-UA': /^[А-ЩЬЮЯЄIЇҐі]+$/i,
  'vi-VN': /^[A-ZÀÁẠẢÃÂẦẤẬẨẪĂẰẮẶẲẴĐÈÉẸẺẼÊỀẾỆỂỄÌÍỊỈĨÒÓỌỎÕÔỒỐỘỔỖƠỜỚỢỞỠÙÚỤỦŨƯỪỨỰỬỮỲÝỴỶỸ]+$/i,
  'ku-IQ': /^[ئابپتجچحخدرڕزژسشعغفڤقکگلڵمنوۆھەیێيطؤثآإأكضصةظذ]+$/i,
  ar: /^[ءآأؤإئابةتثجحخدذرزسشصضطظعغفقكلمنهوىيًٌٍَُِّْٰ]+$/,
  he: /^[א-ת]+$/,
  fa: /^['آاءأؤئبپتثجچحخدذرزژسشصضطظعغفقکگلمنوهةی']+$/i
};
exports.alpha = alpha;
var alphanumeric = {
  'en-US': /^[0-9A-Z]+$/i,
  'az-AZ': /^[0-9A-VXYZÇƏĞİıÖŞÜ]+$/i,
  'bg-BG': /^[0-9А-Я]+$/i,
  'cs-CZ': /^[0-9A-ZÁČĎÉĚÍŇÓŘŠŤÚŮÝŽ]+$/i,
  'da-DK': /^[0-9A-ZÆØÅ]+$/i,
  'de-DE': /^[0-9A-ZÄÖÜß]+$/i,
  'el-GR': /^[0-9Α-ω]+$/i,
  'es-ES': /^[0-9A-ZÁÉÍÑÓÚÜ]+$/i,
  'fr-FR': /^[0-9A-ZÀÂÆÇÉÈÊËÏÎÔŒÙÛÜŸ]+$/i,
  'it-IT': /^[0-9A-ZÀÉÈÌÎÓÒÙ]+$/i,
  'hu-HU': /^[0-9A-ZÁÉÍÓÖŐÚÜŰ]+$/i,
  'nb-NO': /^[0-9A-ZÆØÅ]+$/i,
  'nl-NL': /^[0-9A-ZÁÉËÏÓÖÜÚ]+$/i,
  'nn-NO': /^[0-9A-ZÆØÅ]+$/i,
  'pl-PL': /^[0-9A-ZĄĆĘŚŁŃÓŻŹ]+$/i,
  'pt-PT': /^[0-9A-ZÃÁÀÂÄÇÉÊËÍÏÕÓÔÖÚÜ]+$/i,
  'ru-RU': /^[0-9А-ЯЁ]+$/i,
  'sl-SI': /^[0-9A-ZČĆĐŠŽ]+$/i,
  'sk-SK': /^[0-9A-ZÁČĎÉÍŇÓŠŤÚÝŽĹŔĽÄÔ]+$/i,
  'sr-RS@latin': /^[0-9A-ZČĆŽŠĐ]+$/i,
  'sr-RS': /^[0-9А-ЯЂЈЉЊЋЏ]+$/i,
  'sv-SE': /^[0-9A-ZÅÄÖ]+$/i,
  'th-TH': /^[ก-๙\s]+$/i,
  'tr-TR': /^[0-9A-ZÇĞİıÖŞÜ]+$/i,
  'uk-UA': /^[0-9А-ЩЬЮЯЄIЇҐі]+$/i,
  'ku-IQ': /^[٠١٢٣٤٥٦٧٨٩0-9ئابپتجچحخدرڕزژسشعغفڤقکگلڵمنوۆھەیێيطؤثآإأكضصةظذ]+$/i,
  'vi-VN': /^[0-9A-ZÀÁẠẢÃÂẦẤẬẨẪĂẰẮẶẲẴĐÈÉẸẺẼÊỀẾỆỂỄÌÍỊỈĨÒÓỌỎÕÔỒỐỘỔỖƠỜỚỢỞỠÙÚỤỦŨƯỪỨỰỬỮỲÝỴỶỸ]+$/i,
  ar: /^[٠١٢٣٤٥٦٧٨٩0-9ءآأؤإئابةتثجحخدذرزسشصضطظعغفقكلمنهوىيًٌٍَُِّْٰ]+$/,
  he: /^[0-9א-ת]+$/,
  fa: /^['0-9آاءأؤئبپتثجچحخدذرزژسشصضطظعغفقکگلمنوهةی۱۲۳۴۵۶۷۸۹۰']+$/i
};
exports.alphanumeric = alphanumeric;
var decimal = {
  'en-US': '.',
  ar: '٫'
};
exports.decimal = decimal;
var englishLocales = ['AU', 'GB', 'HK', 'IN', 'NZ', 'ZA', 'ZM'];
exports.englishLocales = englishLocales;

for (var locale, i = 0; i < englishLocales.length; i++) {
  locale = "en-".concat(englishLocales[i]);
  alpha[locale] = alpha['en-US'];
  alphanumeric[locale] = alphanumeric['en-US'];
  decimal[locale] = decimal['en-US'];
} // Source: http://www.localeplanet.com/java/


var arabicLocales = ['AE', 'BH', 'DZ', 'EG', 'IQ', 'JO', 'KW', 'LB', 'LY', 'MA', 'QM', 'QA', 'SA', 'SD', 'SY', 'TN', 'YE'];
exports.arabicLocales = arabicLocales;

for (var _locale, _i = 0; _i < arabicLocales.length; _i++) {
  _locale = "ar-".concat(arabicLocales[_i]);
  alpha[_locale] = alpha.ar;
  alphanumeric[_locale] = alphanumeric.ar;
  decimal[_locale] = decimal.ar;
}

var farsiLocales = ['IR', 'AF'];
exports.farsiLocales = farsiLocales;

for (var _locale2, _i2 = 0; _i2 < farsiLocales.length; _i2++) {
  _locale2 = "fa-".concat(farsiLocales[_i2]);
  alphanumeric[_locale2] = alphanumeric.fa;
  decimal[_locale2] = decimal.ar;
} // Source: https://en.wikipedia.org/wiki/Decimal_mark


var dotDecimal = ['ar-EG', 'ar-LB', 'ar-LY'];
exports.dotDecimal = dotDecimal;
var commaDecimal = ['bg-BG', 'cs-CZ', 'da-DK', 'de-DE', 'el-GR', 'en-ZM', 'es-ES', 'fr-CA', 'fr-FR', 'id-ID', 'it-IT', 'ku-IQ', 'hu-HU', 'nb-NO', 'nn-NO', 'nl-NL', 'pl-PL', 'pt-PT', 'ru-RU', 'sl-SI', 'sr-RS@latin', 'sr-RS', 'sv-SE', 'tr-TR', 'uk-UA', 'vi-VN'];
exports.commaDecimal = commaDecimal;

for (var _i3 = 0; _i3 < dotDecimal.length; _i3++) {
  decimal[dotDecimal[_i3]] = decimal['en-US'];
}

for (var _i4 = 0; _i4 < commaDecimal.length; _i4++) {
  decimal[commaDecimal[_i4]] = ',';
}

alpha['fr-CA'] = alpha['fr-FR'];
alphanumeric['fr-CA'] = alphanumeric['fr-FR'];
alpha['pt-BR'] = alpha['pt-PT'];
alphanumeric['pt-BR'] = alphanumeric['pt-PT'];
decimal['pt-BR'] = decimal['pt-PT']; // see #862

alpha['pl-Pl'] = alpha['pl-PL'];
alphanumeric['pl-Pl'] = alphanumeric['pl-PL'];
decimal['pl-Pl'] = decimal['pl-PL']; // see #1455

alpha['fa-AF'] = alpha.fa;

/***/ }),

/***/ 7902:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = blacklist;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function blacklist(str, chars) {
  (0, _assertString.default)(str);
  return str.replace(new RegExp("[".concat(chars, "]+"), 'g'), '');
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 4228:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = contains;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _toString = _interopRequireDefault(__webpack_require__(1589));

var _merge = _interopRequireDefault(__webpack_require__(1778));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var defaulContainsOptions = {
  ignoreCase: false
};

function contains(str, elem, options) {
  (0, _assertString.default)(str);
  options = (0, _merge.default)(options, defaulContainsOptions);
  return options.ignoreCase ? str.toLowerCase().indexOf((0, _toString.default)(elem).toLowerCase()) >= 0 : str.indexOf((0, _toString.default)(elem)) >= 0;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 7133:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = equals;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function equals(str, comparison) {
  (0, _assertString.default)(str);
  return str === comparison;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 7879:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = escape;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function escape(str) {
  (0, _assertString.default)(str);
  return str.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#x27;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\//g, '&#x2F;').replace(/\\/g, '&#x5C;').replace(/`/g, '&#96;');
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 6623:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isAfter;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _toDate = _interopRequireDefault(__webpack_require__(7499));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function isAfter(str) {
  var date = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : String(new Date());
  (0, _assertString.default)(str);
  var comparison = (0, _toDate.default)(date);
  var original = (0, _toDate.default)(str);
  return !!(original && comparison && original > comparison);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 7780:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isAlpha;
exports.locales = void 0;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _alpha = __webpack_require__(5475);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function isAlpha(_str) {
  var locale = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'en-US';
  var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  (0, _assertString.default)(_str);
  var str = _str;
  var ignore = options.ignore;

  if (ignore) {
    if (ignore instanceof RegExp) {
      str = str.replace(ignore, '');
    } else if (typeof ignore === 'string') {
      str = str.replace(new RegExp("[".concat(ignore.replace(/[-[\]{}()*+?.,\\^$|#\\s]/g, '\\$&'), "]"), 'g'), ''); // escape regex for ignore
    } else {
      throw new Error('ignore should be instance of a String or RegExp');
    }
  }

  if (locale in _alpha.alpha) {
    return _alpha.alpha[locale].test(str);
  }

  throw new Error("Invalid locale '".concat(locale, "'"));
}

var locales = Object.keys(_alpha.alpha);
exports.locales = locales;

/***/ }),

/***/ 7014:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isAlphanumeric;
exports.locales = void 0;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _alpha = __webpack_require__(5475);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function isAlphanumeric(str) {
  var locale = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'en-US';
  (0, _assertString.default)(str);

  if (locale in _alpha.alphanumeric) {
    return _alpha.alphanumeric[locale].test(str);
  }

  throw new Error("Invalid locale '".concat(locale, "'"));
}

var locales = Object.keys(_alpha.alphanumeric);
exports.locales = locales;

/***/ }),

/***/ 5670:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isAscii;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/* eslint-disable no-control-regex */
var ascii = /^[\x00-\x7F]+$/;
/* eslint-enable no-control-regex */

function isAscii(str) {
  (0, _assertString.default)(str);
  return ascii.test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 2740:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isBIC;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var isBICReg = /^[A-z]{4}[A-z]{2}\w{2}(\w{3})?$/;

function isBIC(str) {
  (0, _assertString.default)(str);
  return isBICReg.test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 6533:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isBase32;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var base32 = /^[A-Z2-7]+=*$/;

function isBase32(str) {
  (0, _assertString.default)(str);
  var len = str.length;

  if (len % 8 === 0 && base32.test(str)) {
    return true;
  }

  return false;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 4039:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isBase58;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

// Accepted chars - 123456789ABCDEFGH JKLMN PQRSTUVWXYZabcdefghijk mnopqrstuvwxyz
var base58Reg = /^[A-HJ-NP-Za-km-z1-9]*$/;

function isBase58(str) {
  (0, _assertString.default)(str);

  if (base58Reg.test(str)) {
    return true;
  }

  return false;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 438:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isBase64;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _merge = _interopRequireDefault(__webpack_require__(1778));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var notBase64 = /[^A-Z0-9+\/=]/i;
var urlSafeBase64 = /^[A-Z0-9_\-]*$/i;
var defaultBase64Options = {
  urlSafe: false
};

function isBase64(str, options) {
  (0, _assertString.default)(str);
  options = (0, _merge.default)(options, defaultBase64Options);
  var len = str.length;

  if (options.urlSafe) {
    return urlSafeBase64.test(str);
  }

  if (len % 4 !== 0 || notBase64.test(str)) {
    return false;
  }

  var firstPaddingChar = str.indexOf('=');
  return firstPaddingChar === -1 || firstPaddingChar === len - 1 || firstPaddingChar === len - 2 && str[len - 1] === '=';
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 5442:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isBefore;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _toDate = _interopRequireDefault(__webpack_require__(7499));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function isBefore(str) {
  var date = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : String(new Date());
  (0, _assertString.default)(str);
  var comparison = (0, _toDate.default)(date);
  var original = (0, _toDate.default)(str);
  return !!(original && comparison && original < comparison);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 5982:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isBoolean;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function isBoolean(str) {
  (0, _assertString.default)(str);
  return ['true', 'false', '1', '0'].indexOf(str) >= 0;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 8369:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isBtcAddress;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

// supports Bech32 addresses
var btc = /^(bc1|[13])[a-zA-HJ-NP-Z0-9]{25,39}$/;

function isBtcAddress(str) {
  (0, _assertString.default)(str);
  return btc.test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 2775:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isByteLength;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

/* eslint-disable prefer-rest-params */
function isByteLength(str, options) {
  (0, _assertString.default)(str);
  var min;
  var max;

  if (_typeof(options) === 'object') {
    min = options.min || 0;
    max = options.max;
  } else {
    // backwards compatibility: isByteLength(str, min [, max])
    min = arguments[1];
    max = arguments[2];
  }

  var len = encodeURI(str).split(/%..|./).length - 1;
  return len >= min && (typeof max === 'undefined' || len <= max);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 4235:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isCreditCard;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/* eslint-disable max-len */
var creditCard = /^(?:4[0-9]{12}(?:[0-9]{3,6})?|5[1-5][0-9]{14}|(222[1-9]|22[3-9][0-9]|2[3-6][0-9]{2}|27[01][0-9]|2720)[0-9]{12}|6(?:011|5[0-9][0-9])[0-9]{12,15}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11}|6[27][0-9]{14})$/;
/* eslint-enable max-len */

function isCreditCard(str) {
  (0, _assertString.default)(str);
  var sanitized = str.replace(/[- ]+/g, '');

  if (!creditCard.test(sanitized)) {
    return false;
  }

  var sum = 0;
  var digit;
  var tmpNum;
  var shouldDouble;

  for (var i = sanitized.length - 1; i >= 0; i--) {
    digit = sanitized.substring(i, i + 1);
    tmpNum = parseInt(digit, 10);

    if (shouldDouble) {
      tmpNum *= 2;

      if (tmpNum >= 10) {
        sum += tmpNum % 10 + 1;
      } else {
        sum += tmpNum;
      }
    } else {
      sum += tmpNum;
    }

    shouldDouble = !shouldDouble;
  }

  return !!(sum % 10 === 0 ? sanitized : false);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 7610:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isCurrency;

var _merge = _interopRequireDefault(__webpack_require__(1778));

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function currencyRegex(options) {
  var decimal_digits = "\\d{".concat(options.digits_after_decimal[0], "}");
  options.digits_after_decimal.forEach(function (digit, index) {
    if (index !== 0) decimal_digits = "".concat(decimal_digits, "|\\d{").concat(digit, "}");
  });
  var symbol = "(".concat(options.symbol.replace(/\W/, function (m) {
    return "\\".concat(m);
  }), ")").concat(options.require_symbol ? '' : '?'),
      negative = '-?',
      whole_dollar_amount_without_sep = '[1-9]\\d*',
      whole_dollar_amount_with_sep = "[1-9]\\d{0,2}(\\".concat(options.thousands_separator, "\\d{3})*"),
      valid_whole_dollar_amounts = ['0', whole_dollar_amount_without_sep, whole_dollar_amount_with_sep],
      whole_dollar_amount = "(".concat(valid_whole_dollar_amounts.join('|'), ")?"),
      decimal_amount = "(\\".concat(options.decimal_separator, "(").concat(decimal_digits, "))").concat(options.require_decimal ? '' : '?');
  var pattern = whole_dollar_amount + (options.allow_decimal || options.require_decimal ? decimal_amount : ''); // default is negative sign before symbol, but there are two other options (besides parens)

  if (options.allow_negatives && !options.parens_for_negatives) {
    if (options.negative_sign_after_digits) {
      pattern += negative;
    } else if (options.negative_sign_before_digits) {
      pattern = negative + pattern;
    }
  } // South African Rand, for example, uses R 123 (space) and R-123 (no space)


  if (options.allow_negative_sign_placeholder) {
    pattern = "( (?!\\-))?".concat(pattern);
  } else if (options.allow_space_after_symbol) {
    pattern = " ?".concat(pattern);
  } else if (options.allow_space_after_digits) {
    pattern += '( (?!$))?';
  }

  if (options.symbol_after_digits) {
    pattern += symbol;
  } else {
    pattern = symbol + pattern;
  }

  if (options.allow_negatives) {
    if (options.parens_for_negatives) {
      pattern = "(\\(".concat(pattern, "\\)|").concat(pattern, ")");
    } else if (!(options.negative_sign_before_digits || options.negative_sign_after_digits)) {
      pattern = negative + pattern;
    }
  } // ensure there's a dollar and/or decimal amount, and that
  // it doesn't start with a space or a negative sign followed by a space


  return new RegExp("^(?!-? )(?=.*\\d)".concat(pattern, "$"));
}

var default_currency_options = {
  symbol: '$',
  require_symbol: false,
  allow_space_after_symbol: false,
  symbol_after_digits: false,
  allow_negatives: true,
  parens_for_negatives: false,
  negative_sign_before_digits: false,
  negative_sign_after_digits: false,
  allow_negative_sign_placeholder: false,
  thousands_separator: ',',
  decimal_separator: '.',
  allow_decimal: true,
  require_decimal: false,
  digits_after_decimal: [2],
  allow_space_after_digits: false
};

function isCurrency(str, options) {
  (0, _assertString.default)(str);
  options = (0, _merge.default)(options, default_currency_options);
  return currencyRegex(options).test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 8305:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isDataURI;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var validMediaType = /^[a-z]+\/[a-z0-9\-\+]+$/i;
var validAttribute = /^[a-z\-]+=[a-z0-9\-]+$/i;
var validData = /^[a-z0-9!\$&'\(\)\*\+,;=\-\._~:@\/\?%\s]*$/i;

function isDataURI(str) {
  (0, _assertString.default)(str);
  var data = str.split(',');

  if (data.length < 2) {
    return false;
  }

  var attributes = data.shift().trim().split(';');
  var schemeAndMediaType = attributes.shift();

  if (schemeAndMediaType.substr(0, 5) !== 'data:') {
    return false;
  }

  var mediaType = schemeAndMediaType.substr(5);

  if (mediaType !== '' && !validMediaType.test(mediaType)) {
    return false;
  }

  for (var i = 0; i < attributes.length; i++) {
    if (i === attributes.length - 1 && attributes[i].toLowerCase() === 'base64') {// ok
    } else if (!validAttribute.test(attributes[i])) {
      return false;
    }
  }

  for (var _i = 0; _i < data.length; _i++) {
    if (!validData.test(data[_i])) {
      return false;
    }
  }

  return true;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 7869:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isDate;

var _merge = _interopRequireDefault(__webpack_require__(1778));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

function _createForOfIteratorHelper(o, allowArrayLike) { var it; if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e2) { throw _e2; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = o[Symbol.iterator](); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e3) { didErr = true; err = _e3; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

var default_date_options = {
  format: 'YYYY/MM/DD',
  delimiters: ['/', '-'],
  strictMode: false
};

function isValidFormat(format) {
  return /(^(y{4}|y{2})[\/-](m{1,2})[\/-](d{1,2})$)|(^(m{1,2})[\/-](d{1,2})[\/-]((y{4}|y{2})$))|(^(d{1,2})[\/-](m{1,2})[\/-]((y{4}|y{2})$))/gi.test(format);
}

function zip(date, format) {
  var zippedArr = [],
      len = Math.min(date.length, format.length);

  for (var i = 0; i < len; i++) {
    zippedArr.push([date[i], format[i]]);
  }

  return zippedArr;
}

function isDate(input, options) {
  if (typeof options === 'string') {
    // Allow backward compatbility for old format isDate(input [, format])
    options = (0, _merge.default)({
      format: options
    }, default_date_options);
  } else {
    options = (0, _merge.default)(options, default_date_options);
  }

  if (typeof input === 'string' && isValidFormat(options.format)) {
    var formatDelimiter = options.delimiters.find(function (delimiter) {
      return options.format.indexOf(delimiter) !== -1;
    });
    var dateDelimiter = options.strictMode ? formatDelimiter : options.delimiters.find(function (delimiter) {
      return input.indexOf(delimiter) !== -1;
    });
    var dateAndFormat = zip(input.split(dateDelimiter), options.format.toLowerCase().split(formatDelimiter));
    var dateObj = {};

    var _iterator = _createForOfIteratorHelper(dateAndFormat),
        _step;

    try {
      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        var _step$value = _slicedToArray(_step.value, 2),
            dateWord = _step$value[0],
            formatWord = _step$value[1];

        if (dateWord.length !== formatWord.length) {
          return false;
        }

        dateObj[formatWord.charAt(0)] = dateWord;
      }
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }

    return new Date("".concat(dateObj.m, "/").concat(dateObj.d, "/").concat(dateObj.y)).getDate() === +dateObj.d;
  }

  if (!options.strictMode) {
    return Object.prototype.toString.call(input) === '[object Date]' && isFinite(input);
  }

  return false;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 8250:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isDecimal;

var _merge = _interopRequireDefault(__webpack_require__(1778));

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _includes = _interopRequireDefault(__webpack_require__(2900));

var _alpha = __webpack_require__(5475);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function decimalRegExp(options) {
  var regExp = new RegExp("^[-+]?([0-9]+)?(\\".concat(_alpha.decimal[options.locale], "[0-9]{").concat(options.decimal_digits, "})").concat(options.force_decimal ? '' : '?', "$"));
  return regExp;
}

var default_decimal_options = {
  force_decimal: false,
  decimal_digits: '1,',
  locale: 'en-US'
};
var blacklist = ['', '-', '+'];

function isDecimal(str, options) {
  (0, _assertString.default)(str);
  options = (0, _merge.default)(options, default_decimal_options);

  if (options.locale in _alpha.decimal) {
    return !(0, _includes.default)(blacklist, str.replace(/ /g, '')) && decimalRegExp(options).test(str);
  }

  throw new Error("Invalid locale '".concat(options.locale, "'"));
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 102:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isDivisibleBy;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _toFloat = _interopRequireDefault(__webpack_require__(6840));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function isDivisibleBy(str, num) {
  (0, _assertString.default)(str);
  return (0, _toFloat.default)(str) % parseInt(num, 10) === 0;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 9653:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isEAN;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/**
 * The most commonly used EAN standard is
 * the thirteen-digit EAN-13, while the
 * less commonly used 8-digit EAN-8 barcode was
 * introduced for use on small packages.
 * EAN consists of:
 * GS1 prefix, manufacturer code, product code and check digit
 * Reference: https://en.wikipedia.org/wiki/International_Article_Number
 */

/**
 * Define EAN Lenghts; 8 for EAN-8; 13 for EAN-13
 * and Regular Expression for valid EANs (EAN-8, EAN-13),
 * with exact numberic matching of 8 or 13 digits [0-9]
 */
var LENGTH_EAN_8 = 8;
var validEanRegex = /^(\d{8}|\d{13})$/;
/**
 * Get position weight given:
 * EAN length and digit index/position
 *
 * @param {number} length
 * @param {number} index
 * @return {number}
 */

function getPositionWeightThroughLengthAndIndex(length, index) {
  if (length === LENGTH_EAN_8) {
    return index % 2 === 0 ? 3 : 1;
  }

  return index % 2 === 0 ? 1 : 3;
}
/**
 * Calculate EAN Check Digit
 * Reference: https://en.wikipedia.org/wiki/International_Article_Number#Calculation_of_checksum_digit
 *
 * @param {string} ean
 * @return {number}
 */


function calculateCheckDigit(ean) {
  var checksum = ean.slice(0, -1).split('').map(function (char, index) {
    return Number(char) * getPositionWeightThroughLengthAndIndex(ean.length, index);
  }).reduce(function (acc, partialSum) {
    return acc + partialSum;
  }, 0);
  var remainder = 10 - checksum % 10;
  return remainder < 10 ? remainder : 0;
}
/**
 * Check if string is valid EAN:
 * Matches EAN-8/EAN-13 regex
 * Has valid check digit.
 *
 * @param {string} str
 * @return {boolean}
 */


function isEAN(str) {
  (0, _assertString.default)(str);
  var actualCheckDigit = Number(str.slice(-1));
  return validEanRegex.test(str) && actualCheckDigit === calculateCheckDigit(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 4525:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isEmail;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _merge = _interopRequireDefault(__webpack_require__(1778));

var _isByteLength = _interopRequireDefault(__webpack_require__(2775));

var _isFQDN = _interopRequireDefault(__webpack_require__(8139));

var _isIP = _interopRequireDefault(__webpack_require__(7962));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

var default_email_options = {
  allow_display_name: false,
  require_display_name: false,
  allow_utf8_local_part: true,
  require_tld: true,
  blacklisted_chars: '',
  ignore_max_length: false
};
/* eslint-disable max-len */

/* eslint-disable no-control-regex */

var splitNameAddress = /^([^\x00-\x1F\x7F-\x9F\cX]+)<(.+)>$/i;
var emailUserPart = /^[a-z\d!#\$%&'\*\+\-\/=\?\^_`{\|}~]+$/i;
var gmailUserPart = /^[a-z\d]+$/;
var quotedEmailUser = /^([\s\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e]|(\\[\x01-\x09\x0b\x0c\x0d-\x7f]))*$/i;
var emailUserUtf8Part = /^[a-z\d!#\$%&'\*\+\-\/=\?\^_`{\|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+$/i;
var quotedEmailUserUtf8 = /^([\s\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|(\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*$/i;
var defaultMaxEmailLength = 254;
/* eslint-enable max-len */

/* eslint-enable no-control-regex */

/**
 * Validate display name according to the RFC2822: https://tools.ietf.org/html/rfc2822#appendix-A.1.2
 * @param {String} display_name
 */

function validateDisplayName(display_name) {
  var trim_quotes = display_name.match(/^"(.+)"$/i);
  var display_name_without_quotes = trim_quotes ? trim_quotes[1] : display_name; // display name with only spaces is not valid

  if (!display_name_without_quotes.trim()) {
    return false;
  } // check whether display name contains illegal character


  var contains_illegal = /[\.";<>]/.test(display_name_without_quotes);

  if (contains_illegal) {
    // if contains illegal characters,
    // must to be enclosed in double-quotes, otherwise it's not a valid display name
    if (!trim_quotes) {
      return false;
    } // the quotes in display name must start with character symbol \


    var all_start_with_back_slash = display_name_without_quotes.split('"').length === display_name_without_quotes.split('\\"').length;

    if (!all_start_with_back_slash) {
      return false;
    }
  }

  return true;
}

function isEmail(str, options) {
  (0, _assertString.default)(str);
  options = (0, _merge.default)(options, default_email_options);

  if (options.require_display_name || options.allow_display_name) {
    var display_email = str.match(splitNameAddress);

    if (display_email) {
      var display_name;

      var _display_email = _slicedToArray(display_email, 3);

      display_name = _display_email[1];
      str = _display_email[2];

      // sometimes need to trim the last space to get the display name
      // because there may be a space between display name and email address
      // eg. myname <address@gmail.com>
      // the display name is `myname` instead of `myname `, so need to trim the last space
      if (display_name.endsWith(' ')) {
        display_name = display_name.substr(0, display_name.length - 1);
      }

      if (!validateDisplayName(display_name)) {
        return false;
      }
    } else if (options.require_display_name) {
      return false;
    }
  }

  if (!options.ignore_max_length && str.length > defaultMaxEmailLength) {
    return false;
  }

  var parts = str.split('@');
  var domain = parts.pop();
  var user = parts.join('@');
  var lower_domain = domain.toLowerCase();

  if (options.domain_specific_validation && (lower_domain === 'gmail.com' || lower_domain === 'googlemail.com')) {
    /*
      Previously we removed dots for gmail addresses before validating.
      This was removed because it allows `multiple..dots@gmail.com`
      to be reported as valid, but it is not.
      Gmail only normalizes single dots, removing them from here is pointless,
      should be done in normalizeEmail
    */
    user = user.toLowerCase(); // Removing sub-address from username before gmail validation

    var username = user.split('+')[0]; // Dots are not included in gmail length restriction

    if (!(0, _isByteLength.default)(username.replace('.', ''), {
      min: 6,
      max: 30
    })) {
      return false;
    }

    var _user_parts = username.split('.');

    for (var i = 0; i < _user_parts.length; i++) {
      if (!gmailUserPart.test(_user_parts[i])) {
        return false;
      }
    }
  }

  if (options.ignore_max_length === false && (!(0, _isByteLength.default)(user, {
    max: 64
  }) || !(0, _isByteLength.default)(domain, {
    max: 254
  }))) {
    return false;
  }

  if (!(0, _isFQDN.default)(domain, {
    require_tld: options.require_tld
  })) {
    if (!options.allow_ip_domain) {
      return false;
    }

    if (!(0, _isIP.default)(domain)) {
      if (!domain.startsWith('[') || !domain.endsWith(']')) {
        return false;
      }

      var noBracketdomain = domain.substr(1, domain.length - 2);

      if (noBracketdomain.length === 0 || !(0, _isIP.default)(noBracketdomain)) {
        return false;
      }
    }
  }

  if (user[0] === '"') {
    user = user.slice(1, user.length - 1);
    return options.allow_utf8_local_part ? quotedEmailUserUtf8.test(user) : quotedEmailUser.test(user);
  }

  var pattern = options.allow_utf8_local_part ? emailUserUtf8Part : emailUserPart;
  var user_parts = user.split('.');

  for (var _i2 = 0; _i2 < user_parts.length; _i2++) {
    if (!pattern.test(user_parts[_i2])) {
      return false;
    }
  }

  if (options.blacklisted_chars) {
    if (user.search(new RegExp("[".concat(options.blacklisted_chars, "]+"), 'g')) !== -1) return false;
  }

  return true;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 1964:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isEmpty;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _merge = _interopRequireDefault(__webpack_require__(1778));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var default_is_empty_options = {
  ignore_whitespace: false
};

function isEmpty(str, options) {
  (0, _assertString.default)(str);
  options = (0, _merge.default)(options, default_is_empty_options);
  return (options.ignore_whitespace ? str.trim().length : str.length) === 0;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 3821:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isEthereumAddress;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var eth = /^(0x)[0-9a-f]{40}$/i;

function isEthereumAddress(str) {
  (0, _assertString.default)(str);
  return eth.test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 8139:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isFQDN;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _merge = _interopRequireDefault(__webpack_require__(1778));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var default_fqdn_options = {
  require_tld: true,
  allow_underscores: false,
  allow_trailing_dot: false,
  allow_numeric_tld: false
};

function isFQDN(str, options) {
  (0, _assertString.default)(str);
  options = (0, _merge.default)(options, default_fqdn_options);
  /* Remove the optional trailing dot before checking validity */

  if (options.allow_trailing_dot && str[str.length - 1] === '.') {
    str = str.substring(0, str.length - 1);
  }

  var parts = str.split('.');
  var tld = parts[parts.length - 1];

  if (options.require_tld) {
    // disallow fqdns without tld
    if (parts.length < 2) {
      return false;
    }

    if (!/^([a-z\u00a1-\uffff]{2,}|xn[a-z0-9-]{2,})$/i.test(tld)) {
      return false;
    } // disallow spaces && special characers


    if (/[\s\u2002-\u200B\u202F\u205F\u3000\uFEFF\uDB40\uDC20\u00A9\uFFFD]/.test(tld)) {
      return false;
    }
  } // reject numeric TLDs


  if (!options.allow_numeric_tld && /^\d+$/.test(tld)) {
    return false;
  }

  return parts.every(function (part) {
    if (part.length > 63) {
      return false;
    }

    if (!/^[a-z_\u00a1-\uffff0-9-]+$/i.test(part)) {
      return false;
    } // disallow full-width chars


    if (/[\uff01-\uff5e]/.test(part)) {
      return false;
    } // disallow parts starting or ending with hyphen


    if (/^-|-$/.test(part)) {
      return false;
    }

    if (!options.allow_underscores && /_/.test(part)) {
      return false;
    }

    return true;
  });
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 3612:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isFloat;
exports.locales = void 0;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _alpha = __webpack_require__(5475);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function isFloat(str, options) {
  (0, _assertString.default)(str);
  options = options || {};
  var float = new RegExp("^(?:[-+])?(?:[0-9]+)?(?:\\".concat(options.locale ? _alpha.decimal[options.locale] : '.', "[0-9]*)?(?:[eE][\\+\\-]?(?:[0-9]+))?$"));

  if (str === '' || str === '.' || str === '-' || str === '+') {
    return false;
  }

  var value = parseFloat(str.replace(',', '.'));
  return float.test(str) && (!options.hasOwnProperty('min') || value >= options.min) && (!options.hasOwnProperty('max') || value <= options.max) && (!options.hasOwnProperty('lt') || value < options.lt) && (!options.hasOwnProperty('gt') || value > options.gt);
}

var locales = Object.keys(_alpha.decimal);
exports.locales = locales;

/***/ }),

/***/ 749:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isFullWidth;
exports.fullWidth = void 0;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var fullWidth = /[^\u0020-\u007E\uFF61-\uFF9F\uFFA0-\uFFDC\uFFE8-\uFFEE0-9a-zA-Z]/;
exports.fullWidth = fullWidth;

function isFullWidth(str) {
  (0, _assertString.default)(str);
  return fullWidth.test(str);
}

/***/ }),

/***/ 841:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isHSL;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var hslcomma = /^(hsl)a?\(\s*((\+|\-)?([0-9]+(\.[0-9]+)?(e(\+|\-)?[0-9]+)?|\.[0-9]+(e(\+|\-)?[0-9]+)?))(deg|grad|rad|turn|\s*)(\s*,\s*(\+|\-)?([0-9]+(\.[0-9]+)?(e(\+|\-)?[0-9]+)?|\.[0-9]+(e(\+|\-)?[0-9]+)?)%){2}\s*(,\s*((\+|\-)?([0-9]+(\.[0-9]+)?(e(\+|\-)?[0-9]+)?|\.[0-9]+(e(\+|\-)?[0-9]+)?)%?)\s*)?\)$/i;
var hslspace = /^(hsl)a?\(\s*((\+|\-)?([0-9]+(\.[0-9]+)?(e(\+|\-)?[0-9]+)?|\.[0-9]+(e(\+|\-)?[0-9]+)?))(deg|grad|rad|turn|\s)(\s*(\+|\-)?([0-9]+(\.[0-9]+)?(e(\+|\-)?[0-9]+)?|\.[0-9]+(e(\+|\-)?[0-9]+)?)%){2}\s*(\/\s*((\+|\-)?([0-9]+(\.[0-9]+)?(e(\+|\-)?[0-9]+)?|\.[0-9]+(e(\+|\-)?[0-9]+)?)%?)\s*)?\)$/i;

function isHSL(str) {
  (0, _assertString.default)(str);
  return hslcomma.test(str) || hslspace.test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 8408:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isHalfWidth;
exports.halfWidth = void 0;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var halfWidth = /[\u0020-\u007E\uFF61-\uFF9F\uFFA0-\uFFDC\uFFE8-\uFFEE0-9a-zA-Z]/;
exports.halfWidth = halfWidth;

function isHalfWidth(str) {
  (0, _assertString.default)(str);
  return halfWidth.test(str);
}

/***/ }),

/***/ 9749:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isHash;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var lengths = {
  md5: 32,
  md4: 32,
  sha1: 40,
  sha256: 64,
  sha384: 96,
  sha512: 128,
  ripemd128: 32,
  ripemd160: 40,
  tiger128: 32,
  tiger160: 40,
  tiger192: 48,
  crc32: 8,
  crc32b: 8
};

function isHash(str, algorithm) {
  (0, _assertString.default)(str);
  var hash = new RegExp("^[a-fA-F0-9]{".concat(lengths[algorithm], "}$"));
  return hash.test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 6941:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isHexColor;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var hexcolor = /^#?([0-9A-F]{3}|[0-9A-F]{4}|[0-9A-F]{6}|[0-9A-F]{8})$/i;

function isHexColor(str) {
  (0, _assertString.default)(str);
  return hexcolor.test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 9985:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isHexadecimal;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var hexadecimal = /^(0x|0h)?[0-9A-F]+$/i;

function isHexadecimal(str) {
  (0, _assertString.default)(str);
  return hexadecimal.test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 7937:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isIBAN;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/**
 * List of country codes with
 * corresponding IBAN regular expression
 * Reference: https://en.wikipedia.org/wiki/International_Bank_Account_Number
 */
var ibanRegexThroughCountryCode = {
  AD: /^(AD[0-9]{2})\d{8}[A-Z0-9]{12}$/,
  AE: /^(AE[0-9]{2})\d{3}\d{16}$/,
  AL: /^(AL[0-9]{2})\d{8}[A-Z0-9]{16}$/,
  AT: /^(AT[0-9]{2})\d{16}$/,
  AZ: /^(AZ[0-9]{2})[A-Z0-9]{4}\d{20}$/,
  BA: /^(BA[0-9]{2})\d{16}$/,
  BE: /^(BE[0-9]{2})\d{12}$/,
  BG: /^(BG[0-9]{2})[A-Z]{4}\d{6}[A-Z0-9]{8}$/,
  BH: /^(BH[0-9]{2})[A-Z]{4}[A-Z0-9]{14}$/,
  BR: /^(BR[0-9]{2})\d{23}[A-Z]{1}[A-Z0-9]{1}$/,
  BY: /^(BY[0-9]{2})[A-Z0-9]{4}\d{20}$/,
  CH: /^(CH[0-9]{2})\d{5}[A-Z0-9]{12}$/,
  CR: /^(CR[0-9]{2})\d{18}$/,
  CY: /^(CY[0-9]{2})\d{8}[A-Z0-9]{16}$/,
  CZ: /^(CZ[0-9]{2})\d{20}$/,
  DE: /^(DE[0-9]{2})\d{18}$/,
  DK: /^(DK[0-9]{2})\d{14}$/,
  DO: /^(DO[0-9]{2})[A-Z]{4}\d{20}$/,
  EE: /^(EE[0-9]{2})\d{16}$/,
  EG: /^(EG[0-9]{2})\d{25}$/,
  ES: /^(ES[0-9]{2})\d{20}$/,
  FI: /^(FI[0-9]{2})\d{14}$/,
  FO: /^(FO[0-9]{2})\d{14}$/,
  FR: /^(FR[0-9]{2})\d{10}[A-Z0-9]{11}\d{2}$/,
  GB: /^(GB[0-9]{2})[A-Z]{4}\d{14}$/,
  GE: /^(GE[0-9]{2})[A-Z0-9]{2}\d{16}$/,
  GI: /^(GI[0-9]{2})[A-Z]{4}[A-Z0-9]{15}$/,
  GL: /^(GL[0-9]{2})\d{14}$/,
  GR: /^(GR[0-9]{2})\d{7}[A-Z0-9]{16}$/,
  GT: /^(GT[0-9]{2})[A-Z0-9]{4}[A-Z0-9]{20}$/,
  HR: /^(HR[0-9]{2})\d{17}$/,
  HU: /^(HU[0-9]{2})\d{24}$/,
  IE: /^(IE[0-9]{2})[A-Z0-9]{4}\d{14}$/,
  IL: /^(IL[0-9]{2})\d{19}$/,
  IQ: /^(IQ[0-9]{2})[A-Z]{4}\d{15}$/,
  IR: /^(IR[0-9]{2})0\d{2}0\d{18}$/,
  IS: /^(IS[0-9]{2})\d{22}$/,
  IT: /^(IT[0-9]{2})[A-Z]{1}\d{10}[A-Z0-9]{12}$/,
  JO: /^(JO[0-9]{2})[A-Z]{4}\d{22}$/,
  KW: /^(KW[0-9]{2})[A-Z]{4}[A-Z0-9]{22}$/,
  KZ: /^(KZ[0-9]{2})\d{3}[A-Z0-9]{13}$/,
  LB: /^(LB[0-9]{2})\d{4}[A-Z0-9]{20}$/,
  LC: /^(LC[0-9]{2})[A-Z]{4}[A-Z0-9]{24}$/,
  LI: /^(LI[0-9]{2})\d{5}[A-Z0-9]{12}$/,
  LT: /^(LT[0-9]{2})\d{16}$/,
  LU: /^(LU[0-9]{2})\d{3}[A-Z0-9]{13}$/,
  LV: /^(LV[0-9]{2})[A-Z]{4}[A-Z0-9]{13}$/,
  MC: /^(MC[0-9]{2})\d{10}[A-Z0-9]{11}\d{2}$/,
  MD: /^(MD[0-9]{2})[A-Z0-9]{20}$/,
  ME: /^(ME[0-9]{2})\d{18}$/,
  MK: /^(MK[0-9]{2})\d{3}[A-Z0-9]{10}\d{2}$/,
  MR: /^(MR[0-9]{2})\d{23}$/,
  MT: /^(MT[0-9]{2})[A-Z]{4}\d{5}[A-Z0-9]{18}$/,
  MU: /^(MU[0-9]{2})[A-Z]{4}\d{19}[A-Z]{3}$/,
  NL: /^(NL[0-9]{2})[A-Z]{4}\d{10}$/,
  NO: /^(NO[0-9]{2})\d{11}$/,
  PK: /^(PK[0-9]{2})[A-Z0-9]{4}\d{16}$/,
  PL: /^(PL[0-9]{2})\d{24}$/,
  PS: /^(PS[0-9]{2})[A-Z0-9]{4}\d{21}$/,
  PT: /^(PT[0-9]{2})\d{21}$/,
  QA: /^(QA[0-9]{2})[A-Z]{4}[A-Z0-9]{21}$/,
  RO: /^(RO[0-9]{2})[A-Z]{4}[A-Z0-9]{16}$/,
  RS: /^(RS[0-9]{2})\d{18}$/,
  SA: /^(SA[0-9]{2})\d{2}[A-Z0-9]{18}$/,
  SC: /^(SC[0-9]{2})[A-Z]{4}\d{20}[A-Z]{3}$/,
  SE: /^(SE[0-9]{2})\d{20}$/,
  SI: /^(SI[0-9]{2})\d{15}$/,
  SK: /^(SK[0-9]{2})\d{20}$/,
  SM: /^(SM[0-9]{2})[A-Z]{1}\d{10}[A-Z0-9]{12}$/,
  SV: /^(SV[0-9]{2})[A-Z0-9]{4}\d{20}$/,
  TL: /^(TL[0-9]{2})\d{19}$/,
  TN: /^(TN[0-9]{2})\d{20}$/,
  TR: /^(TR[0-9]{2})\d{5}[A-Z0-9]{17}$/,
  UA: /^(UA[0-9]{2})\d{6}[A-Z0-9]{19}$/,
  VA: /^(VA[0-9]{2})\d{18}$/,
  VG: /^(VG[0-9]{2})[A-Z0-9]{4}\d{16}$/,
  XK: /^(XK[0-9]{2})\d{16}$/
};
/**
 * Check whether string has correct universal IBAN format
 * The IBAN consists of up to 34 alphanumeric characters, as follows:
 * Country Code using ISO 3166-1 alpha-2, two letters
 * check digits, two digits and
 * Basic Bank Account Number (BBAN), up to 30 alphanumeric characters.
 * NOTE: Permitted IBAN characters are: digits [0-9] and the 26 latin alphabetic [A-Z]
 *
 * @param {string} str - string under validation
 * @return {boolean}
 */

function hasValidIbanFormat(str) {
  // Strip white spaces and hyphens
  var strippedStr = str.replace(/[\s\-]+/gi, '').toUpperCase();
  var isoCountryCode = strippedStr.slice(0, 2).toUpperCase();
  return isoCountryCode in ibanRegexThroughCountryCode && ibanRegexThroughCountryCode[isoCountryCode].test(strippedStr);
}
/**
   * Check whether string has valid IBAN Checksum
   * by performing basic mod-97 operation and
   * the remainder should equal 1
   * -- Start by rearranging the IBAN by moving the four initial characters to the end of the string
   * -- Replace each letter in the string with two digits, A -> 10, B = 11, Z = 35
   * -- Interpret the string as a decimal integer and
   * -- compute the remainder on division by 97 (mod 97)
   * Reference: https://en.wikipedia.org/wiki/International_Bank_Account_Number
   *
   * @param {string} str
   * @return {boolean}
   */


function hasValidIbanChecksum(str) {
  var strippedStr = str.replace(/[^A-Z0-9]+/gi, '').toUpperCase(); // Keep only digits and A-Z latin alphabetic

  var rearranged = strippedStr.slice(4) + strippedStr.slice(0, 4);
  var alphaCapsReplacedWithDigits = rearranged.replace(/[A-Z]/g, function (char) {
    return char.charCodeAt(0) - 55;
  });
  var remainder = alphaCapsReplacedWithDigits.match(/\d{1,7}/g).reduce(function (acc, value) {
    return Number(acc + value) % 97;
  }, '');
  return remainder === 1;
}

function isIBAN(str) {
  (0, _assertString.default)(str);
  return hasValidIbanFormat(str) && hasValidIbanChecksum(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 1893:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isIMEI;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var imeiRegexWithoutHypens = /^[0-9]{15}$/;
var imeiRegexWithHypens = /^\d{2}-\d{6}-\d{6}-\d{1}$/;

function isIMEI(str, options) {
  (0, _assertString.default)(str);
  options = options || {}; // default regex for checking imei is the one without hyphens

  var imeiRegex = imeiRegexWithoutHypens;

  if (options.allow_hyphens) {
    imeiRegex = imeiRegexWithHypens;
  }

  if (!imeiRegex.test(str)) {
    return false;
  }

  str = str.replace(/-/g, '');
  var sum = 0,
      mul = 2,
      l = 14;

  for (var i = 0; i < l; i++) {
    var digit = str.substring(l - i - 1, l - i);
    var tp = parseInt(digit, 10) * mul;

    if (tp >= 10) {
      sum += tp % 10 + 1;
    } else {
      sum += tp;
    }

    if (mul === 1) {
      mul += 1;
    } else {
      mul -= 1;
    }
  }

  var chk = (10 - sum % 10) % 10;

  if (chk !== parseInt(str.substring(14, 15), 10)) {
    return false;
  }

  return true;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 7962:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isIP;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/**
11.3.  Examples

   The following addresses

             fe80::1234 (on the 1st link of the node)
             ff02::5678 (on the 5th link of the node)
             ff08::9abc (on the 10th organization of the node)

   would be represented as follows:

             fe80::1234%1
             ff02::5678%5
             ff08::9abc%10

   (Here we assume a natural translation from a zone index to the
   <zone_id> part, where the Nth zone of any scope is translated into
   "N".)

   If we use interface names as <zone_id>, those addresses could also be
   represented as follows:

            fe80::1234%ne0
            ff02::5678%pvc1.3
            ff08::9abc%interface10

   where the interface "ne0" belongs to the 1st link, "pvc1.3" belongs
   to the 5th link, and "interface10" belongs to the 10th organization.
 * * */
var ipv4Maybe = /^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/;
var ipv6Block = /^[0-9A-F]{1,4}$/i;

function isIP(str) {
  var version = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
  (0, _assertString.default)(str);
  version = String(version);

  if (!version) {
    return isIP(str, 4) || isIP(str, 6);
  } else if (version === '4') {
    if (!ipv4Maybe.test(str)) {
      return false;
    }

    var parts = str.split('.').sort(function (a, b) {
      return a - b;
    });
    return parts[3] <= 255;
  } else if (version === '6') {
    var addressAndZone = [str]; // ipv6 addresses could have scoped architecture
    // according to https://tools.ietf.org/html/rfc4007#section-11

    if (str.includes('%')) {
      addressAndZone = str.split('%');

      if (addressAndZone.length !== 2) {
        // it must be just two parts
        return false;
      }

      if (!addressAndZone[0].includes(':')) {
        // the first part must be the address
        return false;
      }

      if (addressAndZone[1] === '') {
        // the second part must not be empty
        return false;
      }
    }

    var blocks = addressAndZone[0].split(':');
    var foundOmissionBlock = false; // marker to indicate ::
    // At least some OS accept the last 32 bits of an IPv6 address
    // (i.e. 2 of the blocks) in IPv4 notation, and RFC 3493 says
    // that '::ffff:a.b.c.d' is valid for IPv4-mapped IPv6 addresses,
    // and '::a.b.c.d' is deprecated, but also valid.

    var foundIPv4TransitionBlock = isIP(blocks[blocks.length - 1], 4);
    var expectedNumberOfBlocks = foundIPv4TransitionBlock ? 7 : 8;

    if (blocks.length > expectedNumberOfBlocks) {
      return false;
    } // initial or final ::


    if (str === '::') {
      return true;
    } else if (str.substr(0, 2) === '::') {
      blocks.shift();
      blocks.shift();
      foundOmissionBlock = true;
    } else if (str.substr(str.length - 2) === '::') {
      blocks.pop();
      blocks.pop();
      foundOmissionBlock = true;
    }

    for (var i = 0; i < blocks.length; ++i) {
      // test for a :: which can not be at the string start/end
      // since those cases have been handled above
      if (blocks[i] === '' && i > 0 && i < blocks.length - 1) {
        if (foundOmissionBlock) {
          return false; // multiple :: in address
        }

        foundOmissionBlock = true;
      } else if (foundIPv4TransitionBlock && i === blocks.length - 1) {// it has been checked before that the last
        // block is a valid IPv4 address
      } else if (!ipv6Block.test(blocks[i])) {
        return false;
      }
    }

    if (foundOmissionBlock) {
      return blocks.length >= 1;
    }

    return blocks.length === expectedNumberOfBlocks;
  }

  return false;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 5482:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isIPRange;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _isIP = _interopRequireDefault(__webpack_require__(7962));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var subnetMaybe = /^\d{1,2}$/;

function isIPRange(str) {
  (0, _assertString.default)(str);
  var parts = str.split('/'); // parts[0] -> ip, parts[1] -> subnet

  if (parts.length !== 2) {
    return false;
  }

  if (!subnetMaybe.test(parts[1])) {
    return false;
  } // Disallow preceding 0 i.e. 01, 02, ...


  if (parts[1].length > 1 && parts[1].startsWith('0')) {
    return false;
  }

  return (0, _isIP.default)(parts[0], 4) && parts[1] <= 32 && parts[1] >= 0;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 6328:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isISBN;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var isbn10Maybe = /^(?:[0-9]{9}X|[0-9]{10})$/;
var isbn13Maybe = /^(?:[0-9]{13})$/;
var factor = [1, 3];

function isISBN(str) {
  var version = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
  (0, _assertString.default)(str);
  version = String(version);

  if (!version) {
    return isISBN(str, 10) || isISBN(str, 13);
  }

  var sanitized = str.replace(/[\s-]+/g, '');
  var checksum = 0;
  var i;

  if (version === '10') {
    if (!isbn10Maybe.test(sanitized)) {
      return false;
    }

    for (i = 0; i < 9; i++) {
      checksum += (i + 1) * sanitized.charAt(i);
    }

    if (sanitized.charAt(9) === 'X') {
      checksum += 10 * 10;
    } else {
      checksum += 10 * sanitized.charAt(9);
    }

    if (checksum % 11 === 0) {
      return !!sanitized;
    }
  } else if (version === '13') {
    if (!isbn13Maybe.test(sanitized)) {
      return false;
    }

    for (i = 0; i < 12; i++) {
      checksum += factor[i % 2] * sanitized.charAt(i);
    }

    if (sanitized.charAt(12) - (10 - checksum % 10) % 10 === 0) {
      return !!sanitized;
    }
  }

  return false;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 2390:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isISIN;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var isin = /^[A-Z]{2}[0-9A-Z]{9}[0-9]$/;

function isISIN(str) {
  (0, _assertString.default)(str);

  if (!isin.test(str)) {
    return false;
  }

  var checksumStr = str.replace(/[A-Z]/g, function (character) {
    return parseInt(character, 36);
  });
  var sum = 0;
  var digit;
  var tmpNum;
  var shouldDouble = true;

  for (var i = checksumStr.length - 2; i >= 0; i--) {
    digit = checksumStr.substring(i, i + 1);
    tmpNum = parseInt(digit, 10);

    if (shouldDouble) {
      tmpNum *= 2;

      if (tmpNum >= 10) {
        sum += tmpNum + 1;
      } else {
        sum += tmpNum;
      }
    } else {
      sum += tmpNum;
    }

    shouldDouble = !shouldDouble;
  }

  return parseInt(str.substr(str.length - 1), 10) === (10000 - sum) % 10;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 928:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isISO31661Alpha2;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _includes = _interopRequireDefault(__webpack_require__(2900));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

// from https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
var validISO31661Alpha2CountriesCodes = ['AD', 'AE', 'AF', 'AG', 'AI', 'AL', 'AM', 'AO', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AW', 'AX', 'AZ', 'BA', 'BB', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BL', 'BM', 'BN', 'BO', 'BQ', 'BR', 'BS', 'BT', 'BV', 'BW', 'BY', 'BZ', 'CA', 'CC', 'CD', 'CF', 'CG', 'CH', 'CI', 'CK', 'CL', 'CM', 'CN', 'CO', 'CR', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ', 'DE', 'DJ', 'DK', 'DM', 'DO', 'DZ', 'EC', 'EE', 'EG', 'EH', 'ER', 'ES', 'ET', 'FI', 'FJ', 'FK', 'FM', 'FO', 'FR', 'GA', 'GB', 'GD', 'GE', 'GF', 'GG', 'GH', 'GI', 'GL', 'GM', 'GN', 'GP', 'GQ', 'GR', 'GS', 'GT', 'GU', 'GW', 'GY', 'HK', 'HM', 'HN', 'HR', 'HT', 'HU', 'ID', 'IE', 'IL', 'IM', 'IN', 'IO', 'IQ', 'IR', 'IS', 'IT', 'JE', 'JM', 'JO', 'JP', 'KE', 'KG', 'KH', 'KI', 'KM', 'KN', 'KP', 'KR', 'KW', 'KY', 'KZ', 'LA', 'LB', 'LC', 'LI', 'LK', 'LR', 'LS', 'LT', 'LU', 'LV', 'LY', 'MA', 'MC', 'MD', 'ME', 'MF', 'MG', 'MH', 'MK', 'ML', 'MM', 'MN', 'MO', 'MP', 'MQ', 'MR', 'MS', 'MT', 'MU', 'MV', 'MW', 'MX', 'MY', 'MZ', 'NA', 'NC', 'NE', 'NF', 'NG', 'NI', 'NL', 'NO', 'NP', 'NR', 'NU', 'NZ', 'OM', 'PA', 'PE', 'PF', 'PG', 'PH', 'PK', 'PL', 'PM', 'PN', 'PR', 'PS', 'PT', 'PW', 'PY', 'QA', 'RE', 'RO', 'RS', 'RU', 'RW', 'SA', 'SB', 'SC', 'SD', 'SE', 'SG', 'SH', 'SI', 'SJ', 'SK', 'SL', 'SM', 'SN', 'SO', 'SR', 'SS', 'ST', 'SV', 'SX', 'SY', 'SZ', 'TC', 'TD', 'TF', 'TG', 'TH', 'TJ', 'TK', 'TL', 'TM', 'TN', 'TO', 'TR', 'TT', 'TV', 'TW', 'TZ', 'UA', 'UG', 'UM', 'US', 'UY', 'UZ', 'VA', 'VC', 'VE', 'VG', 'VI', 'VN', 'VU', 'WF', 'WS', 'YE', 'YT', 'ZA', 'ZM', 'ZW'];

function isISO31661Alpha2(str) {
  (0, _assertString.default)(str);
  return (0, _includes.default)(validISO31661Alpha2CountriesCodes, str.toUpperCase());
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 2038:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isISO31661Alpha3;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _includes = _interopRequireDefault(__webpack_require__(2900));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

// from https://en.wikipedia.org/wiki/ISO_3166-1_alpha-3
var validISO31661Alpha3CountriesCodes = ['AFG', 'ALA', 'ALB', 'DZA', 'ASM', 'AND', 'AGO', 'AIA', 'ATA', 'ATG', 'ARG', 'ARM', 'ABW', 'AUS', 'AUT', 'AZE', 'BHS', 'BHR', 'BGD', 'BRB', 'BLR', 'BEL', 'BLZ', 'BEN', 'BMU', 'BTN', 'BOL', 'BES', 'BIH', 'BWA', 'BVT', 'BRA', 'IOT', 'BRN', 'BGR', 'BFA', 'BDI', 'KHM', 'CMR', 'CAN', 'CPV', 'CYM', 'CAF', 'TCD', 'CHL', 'CHN', 'CXR', 'CCK', 'COL', 'COM', 'COG', 'COD', 'COK', 'CRI', 'CIV', 'HRV', 'CUB', 'CUW', 'CYP', 'CZE', 'DNK', 'DJI', 'DMA', 'DOM', 'ECU', 'EGY', 'SLV', 'GNQ', 'ERI', 'EST', 'ETH', 'FLK', 'FRO', 'FJI', 'FIN', 'FRA', 'GUF', 'PYF', 'ATF', 'GAB', 'GMB', 'GEO', 'DEU', 'GHA', 'GIB', 'GRC', 'GRL', 'GRD', 'GLP', 'GUM', 'GTM', 'GGY', 'GIN', 'GNB', 'GUY', 'HTI', 'HMD', 'VAT', 'HND', 'HKG', 'HUN', 'ISL', 'IND', 'IDN', 'IRN', 'IRQ', 'IRL', 'IMN', 'ISR', 'ITA', 'JAM', 'JPN', 'JEY', 'JOR', 'KAZ', 'KEN', 'KIR', 'PRK', 'KOR', 'KWT', 'KGZ', 'LAO', 'LVA', 'LBN', 'LSO', 'LBR', 'LBY', 'LIE', 'LTU', 'LUX', 'MAC', 'MKD', 'MDG', 'MWI', 'MYS', 'MDV', 'MLI', 'MLT', 'MHL', 'MTQ', 'MRT', 'MUS', 'MYT', 'MEX', 'FSM', 'MDA', 'MCO', 'MNG', 'MNE', 'MSR', 'MAR', 'MOZ', 'MMR', 'NAM', 'NRU', 'NPL', 'NLD', 'NCL', 'NZL', 'NIC', 'NER', 'NGA', 'NIU', 'NFK', 'MNP', 'NOR', 'OMN', 'PAK', 'PLW', 'PSE', 'PAN', 'PNG', 'PRY', 'PER', 'PHL', 'PCN', 'POL', 'PRT', 'PRI', 'QAT', 'REU', 'ROU', 'RUS', 'RWA', 'BLM', 'SHN', 'KNA', 'LCA', 'MAF', 'SPM', 'VCT', 'WSM', 'SMR', 'STP', 'SAU', 'SEN', 'SRB', 'SYC', 'SLE', 'SGP', 'SXM', 'SVK', 'SVN', 'SLB', 'SOM', 'ZAF', 'SGS', 'SSD', 'ESP', 'LKA', 'SDN', 'SUR', 'SJM', 'SWZ', 'SWE', 'CHE', 'SYR', 'TWN', 'TJK', 'TZA', 'THA', 'TLS', 'TGO', 'TKL', 'TON', 'TTO', 'TUN', 'TUR', 'TKM', 'TCA', 'TUV', 'UGA', 'UKR', 'ARE', 'GBR', 'USA', 'UMI', 'URY', 'UZB', 'VUT', 'VEN', 'VNM', 'VGB', 'VIR', 'WLF', 'ESH', 'YEM', 'ZMB', 'ZWE'];

function isISO31661Alpha3(str) {
  (0, _assertString.default)(str);
  return (0, _includes.default)(validISO31661Alpha3CountriesCodes, str.toUpperCase());
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 8932:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isISO8601;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/* eslint-disable max-len */
// from http://goo.gl/0ejHHW
var iso8601 = /^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-3])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$/; // same as above, except with a strict 'T' separator between date and time

var iso8601StrictSeparator = /^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-3])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T]((([01]\d|2[0-3])((:?)[0-5]\d)?|24:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$/;
/* eslint-enable max-len */

var isValidDate = function isValidDate(str) {
  // str must have passed the ISO8601 check
  // this check is meant to catch invalid dates
  // like 2009-02-31
  // first check for ordinal dates
  var ordinalMatch = str.match(/^(\d{4})-?(\d{3})([ T]{1}\.*|$)/);

  if (ordinalMatch) {
    var oYear = Number(ordinalMatch[1]);
    var oDay = Number(ordinalMatch[2]); // if is leap year

    if (oYear % 4 === 0 && oYear % 100 !== 0 || oYear % 400 === 0) return oDay <= 366;
    return oDay <= 365;
  }

  var match = str.match(/(\d{4})-?(\d{0,2})-?(\d*)/).map(Number);
  var year = match[1];
  var month = match[2];
  var day = match[3];
  var monthString = month ? "0".concat(month).slice(-2) : month;
  var dayString = day ? "0".concat(day).slice(-2) : day; // create a date object and compare

  var d = new Date("".concat(year, "-").concat(monthString || '01', "-").concat(dayString || '01'));

  if (month && day) {
    return d.getUTCFullYear() === year && d.getUTCMonth() + 1 === month && d.getUTCDate() === day;
  }

  return true;
};

function isISO8601(str) {
  var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
  (0, _assertString.default)(str);
  var check = options.strictSeparator ? iso8601StrictSeparator.test(str) : iso8601.test(str);
  if (check && options.strict) return isValidDate(str);
  return check;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 6557:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isISRC;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

// see http://isrc.ifpi.org/en/isrc-standard/code-syntax
var isrc = /^[A-Z]{2}[0-9A-Z]{3}\d{2}\d{5}$/;

function isISRC(str) {
  (0, _assertString.default)(str);
  return isrc.test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 2985:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isISSN;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var issn = '^\\d{4}-?\\d{3}[\\dX]$';

function isISSN(str) {
  var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
  (0, _assertString.default)(str);
  var testIssn = issn;
  testIssn = options.require_hyphen ? testIssn.replace('?', '') : testIssn;
  testIssn = options.case_sensitive ? new RegExp(testIssn) : new RegExp(testIssn, 'i');

  if (!testIssn.test(str)) {
    return false;
  }

  var digits = str.replace('-', '').toUpperCase();
  var checksum = 0;

  for (var i = 0; i < digits.length; i++) {
    var digit = digits[i];
    checksum += (digit === 'X' ? 10 : +digit) * (8 - i);
  }

  return checksum % 11 === 0;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 2250:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isIdentityCard;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var validators = {
  ES: function ES(str) {
    (0, _assertString.default)(str);
    var DNI = /^[0-9X-Z][0-9]{7}[TRWAGMYFPDXBNJZSQVHLCKE]$/;
    var charsValue = {
      X: 0,
      Y: 1,
      Z: 2
    };
    var controlDigits = ['T', 'R', 'W', 'A', 'G', 'M', 'Y', 'F', 'P', 'D', 'X', 'B', 'N', 'J', 'Z', 'S', 'Q', 'V', 'H', 'L', 'C', 'K', 'E']; // sanitize user input

    var sanitized = str.trim().toUpperCase(); // validate the data structure

    if (!DNI.test(sanitized)) {
      return false;
    } // validate the control digit


    var number = sanitized.slice(0, -1).replace(/[X,Y,Z]/g, function (char) {
      return charsValue[char];
    });
    return sanitized.endsWith(controlDigits[number % 23]);
  },
  IN: function IN(str) {
    var DNI = /^[1-9]\d{3}\s?\d{4}\s?\d{4}$/; // multiplication table

    var d = [[0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [1, 2, 3, 4, 0, 6, 7, 8, 9, 5], [2, 3, 4, 0, 1, 7, 8, 9, 5, 6], [3, 4, 0, 1, 2, 8, 9, 5, 6, 7], [4, 0, 1, 2, 3, 9, 5, 6, 7, 8], [5, 9, 8, 7, 6, 0, 4, 3, 2, 1], [6, 5, 9, 8, 7, 1, 0, 4, 3, 2], [7, 6, 5, 9, 8, 2, 1, 0, 4, 3], [8, 7, 6, 5, 9, 3, 2, 1, 0, 4], [9, 8, 7, 6, 5, 4, 3, 2, 1, 0]]; // permutation table

    var p = [[0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [1, 5, 7, 6, 2, 8, 3, 0, 9, 4], [5, 8, 0, 3, 7, 9, 6, 1, 4, 2], [8, 9, 1, 6, 0, 4, 3, 5, 2, 7], [9, 4, 5, 3, 1, 2, 6, 8, 7, 0], [4, 2, 8, 6, 5, 7, 3, 9, 0, 1], [2, 7, 9, 3, 8, 0, 6, 4, 1, 5], [7, 0, 4, 6, 9, 1, 3, 2, 5, 8]]; // sanitize user input

    var sanitized = str.trim(); // validate the data structure

    if (!DNI.test(sanitized)) {
      return false;
    }

    var c = 0;
    var invertedArray = sanitized.replace(/\s/g, '').split('').map(Number).reverse();
    invertedArray.forEach(function (val, i) {
      c = d[c][p[i % 8][val]];
    });
    return c === 0;
  },
  IT: function IT(str) {
    if (str.length !== 9) return false;
    if (str === 'CA00000AA') return false; // https://it.wikipedia.org/wiki/Carta_d%27identit%C3%A0_elettronica_italiana

    return str.search(/C[A-Z][0-9]{5}[A-Z]{2}/i) > -1;
  },
  NO: function NO(str) {
    var sanitized = str.trim();
    if (isNaN(Number(sanitized))) return false;
    if (sanitized.length !== 11) return false;
    if (sanitized === '00000000000') return false; // https://no.wikipedia.org/wiki/F%C3%B8dselsnummer

    var f = sanitized.split('').map(Number);
    var k1 = (11 - (3 * f[0] + 7 * f[1] + 6 * f[2] + 1 * f[3] + 8 * f[4] + 9 * f[5] + 4 * f[6] + 5 * f[7] + 2 * f[8]) % 11) % 11;
    var k2 = (11 - (5 * f[0] + 4 * f[1] + 3 * f[2] + 2 * f[3] + 7 * f[4] + 6 * f[5] + 5 * f[6] + 4 * f[7] + 3 * f[8] + 2 * k1) % 11) % 11;
    if (k1 !== f[9] || k2 !== f[10]) return false;
    return true;
  },
  'he-IL': function heIL(str) {
    var DNI = /^\d{9}$/; // sanitize user input

    var sanitized = str.trim(); // validate the data structure

    if (!DNI.test(sanitized)) {
      return false;
    }

    var id = sanitized;
    var sum = 0,
        incNum;

    for (var i = 0; i < id.length; i++) {
      incNum = Number(id[i]) * (i % 2 + 1); // Multiply number by 1 or 2

      sum += incNum > 9 ? incNum - 9 : incNum; // Sum the digits up and add to total
    }

    return sum % 10 === 0;
  },
  'ar-TN': function arTN(str) {
    var DNI = /^\d{8}$/; // sanitize user input

    var sanitized = str.trim(); // validate the data structure

    if (!DNI.test(sanitized)) {
      return false;
    }

    return true;
  },
  'zh-CN': function zhCN(str) {
    var provincesAndCities = ['11', // 北京
    '12', // 天津
    '13', // 河北
    '14', // 山西
    '15', // 内蒙古
    '21', // 辽宁
    '22', // 吉林
    '23', // 黑龙江
    '31', // 上海
    '32', // 江苏
    '33', // 浙江
    '34', // 安徽
    '35', // 福建
    '36', // 江西
    '37', // 山东
    '41', // 河南
    '42', // 湖北
    '43', // 湖南
    '44', // 广东
    '45', // 广西
    '46', // 海南
    '50', // 重庆
    '51', // 四川
    '52', // 贵州
    '53', // 云南
    '54', // 西藏
    '61', // 陕西
    '62', // 甘肃
    '63', // 青海
    '64', // 宁夏
    '65', // 新疆
    '71', // 台湾
    '81', // 香港
    '82', // 澳门
    '91' // 国外
    ];
    var powers = ['7', '9', '10', '5', '8', '4', '2', '1', '6', '3', '7', '9', '10', '5', '8', '4', '2'];
    var parityBit = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];

    var checkAddressCode = function checkAddressCode(addressCode) {
      return provincesAndCities.includes(addressCode);
    };

    var checkBirthDayCode = function checkBirthDayCode(birDayCode) {
      var yyyy = parseInt(birDayCode.substring(0, 4), 10);
      var mm = parseInt(birDayCode.substring(4, 6), 10);
      var dd = parseInt(birDayCode.substring(6), 10);
      var xdata = new Date(yyyy, mm - 1, dd);

      if (xdata > new Date()) {
        return false; // eslint-disable-next-line max-len
      } else if (xdata.getFullYear() === yyyy && xdata.getMonth() === mm - 1 && xdata.getDate() === dd) {
        return true;
      }

      return false;
    };

    var getParityBit = function getParityBit(idCardNo) {
      var id17 = idCardNo.substring(0, 17);
      var power = 0;

      for (var i = 0; i < 17; i++) {
        power += parseInt(id17.charAt(i), 10) * parseInt(powers[i], 10);
      }

      var mod = power % 11;
      return parityBit[mod];
    };

    var checkParityBit = function checkParityBit(idCardNo) {
      return getParityBit(idCardNo) === idCardNo.charAt(17).toUpperCase();
    };

    var check15IdCardNo = function check15IdCardNo(idCardNo) {
      var check = /^[1-9]\d{7}((0[1-9])|(1[0-2]))((0[1-9])|([1-2][0-9])|(3[0-1]))\d{3}$/.test(idCardNo);
      if (!check) return false;
      var addressCode = idCardNo.substring(0, 2);
      check = checkAddressCode(addressCode);
      if (!check) return false;
      var birDayCode = "19".concat(idCardNo.substring(6, 12));
      check = checkBirthDayCode(birDayCode);
      if (!check) return false;
      return true;
    };

    var check18IdCardNo = function check18IdCardNo(idCardNo) {
      var check = /^[1-9]\d{5}[1-9]\d{3}((0[1-9])|(1[0-2]))((0[1-9])|([1-2][0-9])|(3[0-1]))\d{3}(\d|x|X)$/.test(idCardNo);
      if (!check) return false;
      var addressCode = idCardNo.substring(0, 2);
      check = checkAddressCode(addressCode);
      if (!check) return false;
      var birDayCode = idCardNo.substring(6, 14);
      check = checkBirthDayCode(birDayCode);
      if (!check) return false;
      return checkParityBit(idCardNo);
    };

    var checkIdCardNo = function checkIdCardNo(idCardNo) {
      var check = /^\d{15}|(\d{17}(\d|x|X))$/.test(idCardNo);
      if (!check) return false;

      if (idCardNo.length === 15) {
        return check15IdCardNo(idCardNo);
      }

      return check18IdCardNo(idCardNo);
    };

    return checkIdCardNo(str);
  },
  'zh-TW': function zhTW(str) {
    var ALPHABET_CODES = {
      A: 10,
      B: 11,
      C: 12,
      D: 13,
      E: 14,
      F: 15,
      G: 16,
      H: 17,
      I: 34,
      J: 18,
      K: 19,
      L: 20,
      M: 21,
      N: 22,
      O: 35,
      P: 23,
      Q: 24,
      R: 25,
      S: 26,
      T: 27,
      U: 28,
      V: 29,
      W: 32,
      X: 30,
      Y: 31,
      Z: 33
    };
    var sanitized = str.trim().toUpperCase();
    if (!/^[A-Z][0-9]{9}$/.test(sanitized)) return false;
    return Array.from(sanitized).reduce(function (sum, number, index) {
      if (index === 0) {
        var code = ALPHABET_CODES[number];
        return code % 10 * 9 + Math.floor(code / 10);
      }

      if (index === 9) {
        return (10 - sum % 10 - Number(number)) % 10 === 0;
      }

      return sum + Number(number) * (9 - index);
    }, 0);
  }
};

function isIdentityCard(str, locale) {
  (0, _assertString.default)(str);

  if (locale in validators) {
    return validators[locale](str);
  } else if (locale === 'any') {
    for (var key in validators) {
      // https://github.com/gotwarlost/istanbul/blob/master/ignoring-code-for-coverage.md#ignoring-code-for-coverage-purposes
      // istanbul ignore else
      if (validators.hasOwnProperty(key)) {
        var validator = validators[key];

        if (validator(str)) {
          return true;
        }
      }
    }

    return false;
  }

  throw new Error("Invalid locale '".concat(locale, "'"));
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 2093:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isIn;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _toString = _interopRequireDefault(__webpack_require__(1589));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function isIn(str, options) {
  (0, _assertString.default)(str);
  var i;

  if (Object.prototype.toString.call(options) === '[object Array]') {
    var array = [];

    for (i in options) {
      // https://github.com/gotwarlost/istanbul/blob/master/ignoring-code-for-coverage.md#ignoring-code-for-coverage-purposes
      // istanbul ignore else
      if ({}.hasOwnProperty.call(options, i)) {
        array[i] = (0, _toString.default)(options[i]);
      }
    }

    return array.indexOf(str) >= 0;
  } else if (_typeof(options) === 'object') {
    return options.hasOwnProperty(str);
  } else if (options && typeof options.indexOf === 'function') {
    return options.indexOf(str) >= 0;
  }

  return false;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 4503:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isInt;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var int = /^(?:[-+]?(?:0|[1-9][0-9]*))$/;
var intLeadingZeroes = /^[-+]?[0-9]+$/;

function isInt(str, options) {
  (0, _assertString.default)(str);
  options = options || {}; // Get the regex to use for testing, based on whether
  // leading zeroes are allowed or not.

  var regex = options.hasOwnProperty('allow_leading_zeroes') && !options.allow_leading_zeroes ? int : intLeadingZeroes; // Check min/max/lt/gt

  var minCheckPassed = !options.hasOwnProperty('min') || str >= options.min;
  var maxCheckPassed = !options.hasOwnProperty('max') || str <= options.max;
  var ltCheckPassed = !options.hasOwnProperty('lt') || str < options.lt;
  var gtCheckPassed = !options.hasOwnProperty('gt') || str > options.gt;
  return regex.test(str) && minCheckPassed && maxCheckPassed && ltCheckPassed && gtCheckPassed;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 5067:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isJSON;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _merge = _interopRequireDefault(__webpack_require__(1778));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

var default_json_options = {
  allow_primitives: false
};

function isJSON(str, options) {
  (0, _assertString.default)(str);

  try {
    options = (0, _merge.default)(options, default_json_options);
    var primitives = [];

    if (options.allow_primitives) {
      primitives = [null, false, true];
    }

    var obj = JSON.parse(str);
    return primitives.includes(obj) || !!obj && _typeof(obj) === 'object';
  } catch (e) {
    /* ignore */
  }

  return false;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 1624:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isJWT;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _isBase = _interopRequireDefault(__webpack_require__(438));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function isJWT(str) {
  (0, _assertString.default)(str);
  var dotSplit = str.split('.');
  var len = dotSplit.length;

  if (len > 3 || len < 2) {
    return false;
  }

  return dotSplit.reduce(function (acc, currElem) {
    return acc && (0, _isBase.default)(currElem, {
      urlSafe: true
    });
  }, true);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 2863:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isLatLong;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _merge = _interopRequireDefault(__webpack_require__(1778));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var lat = /^\(?[+-]?(90(\.0+)?|[1-8]?\d(\.\d+)?)$/;
var long = /^\s?[+-]?(180(\.0+)?|1[0-7]\d(\.\d+)?|\d{1,2}(\.\d+)?)\)?$/;
var latDMS = /^(([1-8]?\d)\D+([1-5]?\d|60)\D+([1-5]?\d|60)(\.\d+)?|90\D+0\D+0)\D+[NSns]?$/i;
var longDMS = /^\s*([1-7]?\d{1,2}\D+([1-5]?\d|60)\D+([1-5]?\d|60)(\.\d+)?|180\D+0\D+0)\D+[EWew]?$/i;
var defaultLatLongOptions = {
  checkDMS: false
};

function isLatLong(str, options) {
  (0, _assertString.default)(str);
  options = (0, _merge.default)(options, defaultLatLongOptions);
  if (!str.includes(',')) return false;
  var pair = str.split(',');
  if (pair[0].startsWith('(') && !pair[1].endsWith(')') || pair[1].endsWith(')') && !pair[0].startsWith('(')) return false;

  if (options.checkDMS) {
    return latDMS.test(pair[0]) && longDMS.test(pair[1]);
  }

  return lat.test(pair[0]) && long.test(pair[1]);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 6500:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isLength;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

/* eslint-disable prefer-rest-params */
function isLength(str, options) {
  (0, _assertString.default)(str);
  var min;
  var max;

  if (_typeof(options) === 'object') {
    min = options.min || 0;
    max = options.max;
  } else {
    // backwards compatibility: isLength(str, min [, max])
    min = arguments[1] || 0;
    max = arguments[2];
  }

  var surrogatePairs = str.match(/[\uD800-\uDBFF][\uDC00-\uDFFF]/g) || [];
  var len = str.length - surrogatePairs.length;
  return len >= min && (typeof max === 'undefined' || len <= max);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 4452:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isLocale;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var localeReg = /^[A-z]{2,4}([_-]([A-z]{4}|[\d]{3}))?([_-]([A-z]{2}|[\d]{3}))?$/;

function isLocale(str) {
  (0, _assertString.default)(str);

  if (str === 'en_US_POSIX' || str === 'ca_ES_VALENCIA') {
    return true;
  }

  return localeReg.test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 8816:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isLowercase;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function isLowercase(str) {
  (0, _assertString.default)(str);
  return str === str.toLowerCase();
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 5504:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isMACAddress;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var macAddress = /^([0-9a-fA-F][0-9a-fA-F]:){5}([0-9a-fA-F][0-9a-fA-F])$/;
var macAddressNoColons = /^([0-9a-fA-F]){12}$/;
var macAddressWithHyphen = /^([0-9a-fA-F][0-9a-fA-F]-){5}([0-9a-fA-F][0-9a-fA-F])$/;
var macAddressWithSpaces = /^([0-9a-fA-F][0-9a-fA-F]\s){5}([0-9a-fA-F][0-9a-fA-F])$/;
var macAddressWithDots = /^([0-9a-fA-F]{4}).([0-9a-fA-F]{4}).([0-9a-fA-F]{4})$/;

function isMACAddress(str, options) {
  (0, _assertString.default)(str);

  if (options && options.no_colons) {
    return macAddressNoColons.test(str);
  }

  return macAddress.test(str) || macAddressWithHyphen.test(str) || macAddressWithSpaces.test(str) || macAddressWithDots.test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 6362:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isMD5;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var md5 = /^[a-f0-9]{32}$/;

function isMD5(str) {
  (0, _assertString.default)(str);
  return md5.test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 2896:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isMagnetURI;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var magnetURI = /^magnet:\?xt=urn:[a-z0-9]+:[a-z0-9]{32,40}&dn=.+&tr=.+$/i;

function isMagnetURI(url) {
  (0, _assertString.default)(url);
  return magnetURI.test(url.trim());
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 7620:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isMimeType;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/*
  Checks if the provided string matches to a correct Media type format (MIME type)

  This function only checks is the string format follows the
  etablished rules by the according RFC specifications.
  This function supports 'charset' in textual media types
  (https://tools.ietf.org/html/rfc6657).

  This function does not check against all the media types listed
  by the IANA (https://www.iana.org/assignments/media-types/media-types.xhtml)
  because of lightness purposes : it would require to include
  all these MIME types in this librairy, which would weigh it
  significantly. This kind of effort maybe is not worth for the use that
  this function has in this entire librairy.

  More informations in the RFC specifications :
  - https://tools.ietf.org/html/rfc2045
  - https://tools.ietf.org/html/rfc2046
  - https://tools.ietf.org/html/rfc7231#section-3.1.1.1
  - https://tools.ietf.org/html/rfc7231#section-3.1.1.5
*/
// Match simple MIME types
// NB :
//   Subtype length must not exceed 100 characters.
//   This rule does not comply to the RFC specs (what is the max length ?).
var mimeTypeSimple = /^(application|audio|font|image|message|model|multipart|text|video)\/[a-zA-Z0-9\.\-\+]{1,100}$/i; // eslint-disable-line max-len
// Handle "charset" in "text/*"

var mimeTypeText = /^text\/[a-zA-Z0-9\.\-\+]{1,100};\s?charset=("[a-zA-Z0-9\.\-\+\s]{0,70}"|[a-zA-Z0-9\.\-\+]{0,70})(\s?\([a-zA-Z0-9\.\-\+\s]{1,20}\))?$/i; // eslint-disable-line max-len
// Handle "boundary" in "multipart/*"

var mimeTypeMultipart = /^multipart\/[a-zA-Z0-9\.\-\+]{1,100}(;\s?(boundary|charset)=("[a-zA-Z0-9\.\-\+\s]{0,70}"|[a-zA-Z0-9\.\-\+]{0,70})(\s?\([a-zA-Z0-9\.\-\+\s]{1,20}\))?){0,2}$/i; // eslint-disable-line max-len

function isMimeType(str) {
  (0, _assertString.default)(str);
  return mimeTypeSimple.test(str) || mimeTypeText.test(str) || mimeTypeMultipart.test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 5119:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isMobilePhone;
exports.locales = void 0;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/* eslint-disable max-len */
var phones = {
  'am-AM': /^(\+?374|0)((10|[9|7][0-9])\d{6}$|[2-4]\d{7}$)/,
  'ar-AE': /^((\+?971)|0)?5[024568]\d{7}$/,
  'ar-BH': /^(\+?973)?(3|6)\d{7}$/,
  'ar-DZ': /^(\+?213|0)(5|6|7)\d{8}$/,
  'ar-LB': /^(\+?961)?((3|81)\d{6}|7\d{7})$/,
  'ar-EG': /^((\+?20)|0)?1[0125]\d{8}$/,
  'ar-IQ': /^(\+?964|0)?7[0-9]\d{8}$/,
  'ar-JO': /^(\+?962|0)?7[789]\d{7}$/,
  'ar-KW': /^(\+?965)[569]\d{7}$/,
  'ar-LY': /^((\+?218)|0)?(9[1-6]\d{7}|[1-8]\d{7,9})$/,
  'ar-MA': /^(?:(?:\+|00)212|0)[5-7]\d{8}$/,
  'ar-SA': /^(!?(\+?966)|0)?5\d{8}$/,
  'ar-SY': /^(!?(\+?963)|0)?9\d{8}$/,
  'ar-TN': /^(\+?216)?[2459]\d{7}$/,
  'az-AZ': /^(\+994|0)(5[015]|7[07]|99)\d{7}$/,
  'bs-BA': /^((((\+|00)3876)|06))((([0-3]|[5-6])\d{6})|(4\d{7}))$/,
  'be-BY': /^(\+?375)?(24|25|29|33|44)\d{7}$/,
  'bg-BG': /^(\+?359|0)?8[789]\d{7}$/,
  'bn-BD': /^(\+?880|0)1[13456789][0-9]{8}$/,
  'ca-AD': /^(\+376)?[346]\d{5}$/,
  'cs-CZ': /^(\+?420)? ?[1-9][0-9]{2} ?[0-9]{3} ?[0-9]{3}$/,
  'da-DK': /^(\+?45)?\s?\d{2}\s?\d{2}\s?\d{2}\s?\d{2}$/,
  'de-DE': /^(\+49)?0?[1|3]([0|5][0-45-9]\d|6([23]|0\d?)|7([0-57-9]|6\d))\d{7}$/,
  'de-AT': /^(\+43|0)\d{1,4}\d{3,12}$/,
  'de-CH': /^(\+41|0)(7[5-9])\d{1,7}$/,
  'de-LU': /^(\+352)?((6\d1)\d{6})$/,
  'el-GR': /^(\+?30|0)?(69\d{8})$/,
  'en-AU': /^(\+?61|0)4\d{8}$/,
  'en-GB': /^(\+?44|0)7\d{9}$/,
  'en-GG': /^(\+?44|0)1481\d{6}$/,
  'en-GH': /^(\+233|0)(20|50|24|54|27|57|26|56|23|28)\d{7}$/,
  'en-HK': /^(\+?852[-\s]?)?[456789]\d{3}[-\s]?\d{4}$/,
  'en-MO': /^(\+?853[-\s]?)?[6]\d{3}[-\s]?\d{4}$/,
  'en-IE': /^(\+?353|0)8[356789]\d{7}$/,
  'en-IN': /^(\+?91|0)?[6789]\d{9}$/,
  'en-KE': /^(\+?254|0)(7|1)\d{8}$/,
  'en-MT': /^(\+?356|0)?(99|79|77|21|27|22|25)[0-9]{6}$/,
  'en-MU': /^(\+?230|0)?\d{8}$/,
  'en-NG': /^(\+?234|0)?[789]\d{9}$/,
  'en-NZ': /^(\+?64|0)[28]\d{7,9}$/,
  'en-PK': /^((\+92)|(0092))-{0,1}\d{3}-{0,1}\d{7}$|^\d{11}$|^\d{4}-\d{7}$/,
  'en-PH': /^(09|\+639)\d{9}$/,
  'en-RW': /^(\+?250|0)?[7]\d{8}$/,
  'en-SG': /^(\+65)?[689]\d{7}$/,
  'en-SL': /^(?:0|94|\+94)?(7(0|1|2|5|6|7|8)( |-)?\d)\d{6}$/,
  'en-TZ': /^(\+?255|0)?[67]\d{8}$/,
  'en-UG': /^(\+?256|0)?[7]\d{8}$/,
  'en-US': /^((\+1|1)?( |-)?)?(\([2-9][0-9]{2}\)|[2-9][0-9]{2})( |-)?([2-9][0-9]{2}( |-)?[0-9]{4})$/,
  'en-ZA': /^(\+?27|0)\d{9}$/,
  'en-ZM': /^(\+?26)?09[567]\d{7}$/,
  'en-ZW': /^(\+263)[0-9]{9}$/,
  'es-AR': /^\+?549(11|[2368]\d)\d{8}$/,
  'es-BO': /^(\+?591)?(6|7)\d{7}$/,
  'es-CO': /^(\+?57)?([1-8]{1}|3[0-9]{2})?[2-9]{1}\d{6}$/,
  'es-CL': /^(\+?56|0)[2-9]\d{1}\d{7}$/,
  'es-CR': /^(\+506)?[2-8]\d{7}$/,
  'es-DO': /^(\+?1)?8[024]9\d{7}$/,
  'es-HN': /^(\+?504)?[9|8]\d{7}$/,
  'es-EC': /^(\+?593|0)([2-7]|9[2-9])\d{7}$/,
  'es-ES': /^(\+?34)?[6|7]\d{8}$/,
  'es-PE': /^(\+?51)?9\d{8}$/,
  'es-MX': /^(\+?52)?(1|01)?\d{10,11}$/,
  'es-PA': /^(\+?507)\d{7,8}$/,
  'es-PY': /^(\+?595|0)9[9876]\d{7}$/,
  'es-UY': /^(\+598|0)9[1-9][\d]{6}$/,
  'et-EE': /^(\+?372)?\s?(5|8[1-4])\s?([0-9]\s?){6,7}$/,
  'fa-IR': /^(\+?98[\-\s]?|0)9[0-39]\d[\-\s]?\d{3}[\-\s]?\d{4}$/,
  'fi-FI': /^(\+?358|0)\s?(4(0|1|2|4|5|6)?|50)\s?(\d\s?){4,8}\d$/,
  'fj-FJ': /^(\+?679)?\s?\d{3}\s?\d{4}$/,
  'fo-FO': /^(\+?298)?\s?\d{2}\s?\d{2}\s?\d{2}$/,
  'fr-FR': /^(\+?33|0)[67]\d{8}$/,
  'fr-GF': /^(\+?594|0|00594)[67]\d{8}$/,
  'fr-GP': /^(\+?590|0|00590)[67]\d{8}$/,
  'fr-MQ': /^(\+?596|0|00596)[67]\d{8}$/,
  'fr-RE': /^(\+?262|0|00262)[67]\d{8}$/,
  'he-IL': /^(\+972|0)([23489]|5[012345689]|77)[1-9]\d{6}$/,
  'hu-HU': /^(\+?36)(20|30|70)\d{7}$/,
  'id-ID': /^(\+?62|0)8(1[123456789]|2[1238]|3[1238]|5[12356789]|7[78]|9[56789]|8[123456789])([\s?|\d]{5,11})$/,
  'it-IT': /^(\+?39)?\s?3\d{2} ?\d{6,7}$/,
  'it-SM': /^((\+378)|(0549)|(\+390549)|(\+3780549))?6\d{5,9}$/,
  'ja-JP': /^(\+81[ \-]?(\(0\))?|0)[6789]0[ \-]?\d{4}[ \-]?\d{4}$/,
  'ka-GE': /^(\+?995)?(5|79)\d{7}$/,
  'kk-KZ': /^(\+?7|8)?7\d{9}$/,
  'kl-GL': /^(\+?299)?\s?\d{2}\s?\d{2}\s?\d{2}$/,
  'ko-KR': /^((\+?82)[ \-]?)?0?1([0|1|6|7|8|9]{1})[ \-]?\d{3,4}[ \-]?\d{4}$/,
  'lt-LT': /^(\+370|8)\d{8}$/,
  'ms-MY': /^(\+?6?01){1}(([0145]{1}(\-|\s)?\d{7,8})|([236789]{1}(\s|\-)?\d{7}))$/,
  'nb-NO': /^(\+?47)?[49]\d{7}$/,
  'ne-NP': /^(\+?977)?9[78]\d{8}$/,
  'nl-BE': /^(\+?32|0)4?\d{8}$/,
  'nl-NL': /^(((\+|00)?31\(0\))|((\+|00)?31)|0)6{1}\d{8}$/,
  'nn-NO': /^(\+?47)?[49]\d{7}$/,
  'pl-PL': /^(\+?48)? ?[5-8]\d ?\d{3} ?\d{2} ?\d{2}$/,
  'pt-BR': /^((\+?55\ ?[1-9]{2}\ ?)|(\+?55\ ?\([1-9]{2}\)\ ?)|(0[1-9]{2}\ ?)|(\([1-9]{2}\)\ ?)|([1-9]{2}\ ?))((\d{4}\-?\d{4})|(9[2-9]{1}\d{3}\-?\d{4}))$/,
  'pt-PT': /^(\+?351)?9[1236]\d{7}$/,
  'ro-RO': /^(\+?4?0)\s?7\d{2}(\/|\s|\.|\-)?\d{3}(\s|\.|\-)?\d{3}$/,
  'ru-RU': /^(\+?7|8)?9\d{9}$/,
  'sl-SI': /^(\+386\s?|0)(\d{1}\s?\d{3}\s?\d{2}\s?\d{2}|\d{2}\s?\d{3}\s?\d{3})$/,
  'sk-SK': /^(\+?421)? ?[1-9][0-9]{2} ?[0-9]{3} ?[0-9]{3}$/,
  'sq-AL': /^(\+355|0)6[789]\d{6}$/,
  'sr-RS': /^(\+3816|06)[- \d]{5,9}$/,
  'sv-SE': /^(\+?46|0)[\s\-]?7[\s\-]?[02369]([\s\-]?\d){7}$/,
  'th-TH': /^(\+66|66|0)\d{9}$/,
  'tr-TR': /^(\+?90|0)?5\d{9}$/,
  'uk-UA': /^(\+?38|8)?0\d{9}$/,
  'uz-UZ': /^(\+?998)?(6[125-79]|7[1-69]|88|9\d)\d{7}$/,
  'vi-VN': /^(\+?84|0)((3([2-9]))|(5([2689]))|(7([0|6-9]))|(8([1-6|89]))|(9([0-9])))([0-9]{7})$/,
  'zh-CN': /^((\+|00)86)?1([3568][0-9]|4[579]|6[67]|7[01235678]|9[012356789])[0-9]{8}$/,
  'zh-TW': /^(\+?886\-?|0)?9\d{8}$/
};
/* eslint-enable max-len */
// aliases

phones['en-CA'] = phones['en-US'];
phones['fr-CA'] = phones['en-CA'];
phones['fr-BE'] = phones['nl-BE'];
phones['zh-HK'] = phones['en-HK'];
phones['zh-MO'] = phones['en-MO'];
phones['ga-IE'] = phones['en-IE'];

function isMobilePhone(str, locale, options) {
  (0, _assertString.default)(str);

  if (options && options.strictMode && !str.startsWith('+')) {
    return false;
  }

  if (Array.isArray(locale)) {
    return locale.some(function (key) {
      // https://github.com/gotwarlost/istanbul/blob/master/ignoring-code-for-coverage.md#ignoring-code-for-coverage-purposes
      // istanbul ignore else
      if (phones.hasOwnProperty(key)) {
        var phone = phones[key];

        if (phone.test(str)) {
          return true;
        }
      }

      return false;
    });
  } else if (locale in phones) {
    return phones[locale].test(str); // alias falsey locale as 'any'
  } else if (!locale || locale === 'any') {
    for (var key in phones) {
      // istanbul ignore else
      if (phones.hasOwnProperty(key)) {
        var phone = phones[key];

        if (phone.test(str)) {
          return true;
        }
      }
    }

    return false;
  }

  throw new Error("Invalid locale '".concat(locale, "'"));
}

var locales = Object.keys(phones);
exports.locales = locales;

/***/ }),

/***/ 4246:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isMongoId;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _isHexadecimal = _interopRequireDefault(__webpack_require__(9985));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function isMongoId(str) {
  (0, _assertString.default)(str);
  return (0, _isHexadecimal.default)(str) && str.length === 24;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 3639:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isMultibyte;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/* eslint-disable no-control-regex */
var multibyte = /[^\x00-\x7F]/;
/* eslint-enable no-control-regex */

function isMultibyte(str) {
  (0, _assertString.default)(str);
  return multibyte.test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 3024:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isNumeric;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _alpha = __webpack_require__(5475);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var numericNoSymbols = /^[0-9]+$/;

function isNumeric(str, options) {
  (0, _assertString.default)(str);

  if (options && options.no_symbols) {
    return numericNoSymbols.test(str);
  }

  return new RegExp("^[+-]?([0-9]*[".concat((options || {}).locale ? _alpha.decimal[options.locale] : '.', "])?[0-9]+$")).test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 6590:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isOctal;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var octal = /^(0o)?[0-7]+$/i;

function isOctal(str) {
  (0, _assertString.default)(str);
  return octal.test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 2249:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isPassportNumber;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/**
 * Reference:
 * https://en.wikipedia.org/ -- Wikipedia
 * https://docs.microsoft.com/en-us/microsoft-365/compliance/eu-passport-number -- EU Passport Number
 * https://countrycode.org/ -- Country Codes
 */
var passportRegexByCountryCode = {
  AM: /^[A-Z]{2}\d{7}$/,
  // ARMENIA
  AR: /^[A-Z]{3}\d{6}$/,
  // ARGENTINA
  AT: /^[A-Z]\d{7}$/,
  // AUSTRIA
  AU: /^[A-Z]\d{7}$/,
  // AUSTRALIA
  BE: /^[A-Z]{2}\d{6}$/,
  // BELGIUM
  BG: /^\d{9}$/,
  // BULGARIA
  BY: /^[A-Z]{2}\d{7}$/,
  // BELARUS
  CA: /^[A-Z]{2}\d{6}$/,
  // CANADA
  CH: /^[A-Z]\d{7}$/,
  // SWITZERLAND
  CN: /^[GE]\d{8}$/,
  // CHINA [G=Ordinary, E=Electronic] followed by 8-digits
  CY: /^[A-Z](\d{6}|\d{8})$/,
  // CYPRUS
  CZ: /^\d{8}$/,
  // CZECH REPUBLIC
  DE: /^[CFGHJKLMNPRTVWXYZ0-9]{9}$/,
  // GERMANY
  DK: /^\d{9}$/,
  // DENMARK
  DZ: /^\d{9}$/,
  // ALGERIA
  EE: /^([A-Z]\d{7}|[A-Z]{2}\d{7})$/,
  // ESTONIA (K followed by 7-digits), e-passports have 2 UPPERCASE followed by 7 digits
  ES: /^[A-Z0-9]{2}([A-Z0-9]?)\d{6}$/,
  // SPAIN
  FI: /^[A-Z]{2}\d{7}$/,
  // FINLAND
  FR: /^\d{2}[A-Z]{2}\d{5}$/,
  // FRANCE
  GB: /^\d{9}$/,
  // UNITED KINGDOM
  GR: /^[A-Z]{2}\d{7}$/,
  // GREECE
  HR: /^\d{9}$/,
  // CROATIA
  HU: /^[A-Z]{2}(\d{6}|\d{7})$/,
  // HUNGARY
  IE: /^[A-Z0-9]{2}\d{7}$/,
  // IRELAND
  IN: /^[A-Z]{1}-?\d{7}$/,
  // INDIA
  IS: /^(A)\d{7}$/,
  // ICELAND
  IT: /^[A-Z0-9]{2}\d{7}$/,
  // ITALY
  JP: /^[A-Z]{2}\d{7}$/,
  // JAPAN
  KR: /^[MS]\d{8}$/,
  // SOUTH KOREA, REPUBLIC OF KOREA, [S=PS Passports, M=PM Passports]
  LT: /^[A-Z0-9]{8}$/,
  // LITHUANIA
  LU: /^[A-Z0-9]{8}$/,
  // LUXEMBURG
  LV: /^[A-Z0-9]{2}\d{7}$/,
  // LATVIA
  MT: /^\d{7}$/,
  // MALTA
  NL: /^[A-Z]{2}[A-Z0-9]{6}\d$/,
  // NETHERLANDS
  PO: /^[A-Z]{2}\d{7}$/,
  // POLAND
  PT: /^[A-Z]\d{6}$/,
  // PORTUGAL
  RO: /^\d{8,9}$/,
  // ROMANIA
  RU: /^\d{2}\d{2}\d{6}$/,
  // RUSSIAN FEDERATION
  SE: /^\d{8}$/,
  // SWEDEN
  SL: /^(P)[A-Z]\d{7}$/,
  // SLOVANIA
  SK: /^[0-9A-Z]\d{7}$/,
  // SLOVAKIA
  TR: /^[A-Z]\d{8}$/,
  // TURKEY
  UA: /^[A-Z]{2}\d{6}$/,
  // UKRAINE
  US: /^\d{9}$/ // UNITED STATES

};
/**
 * Check if str is a valid passport number
 * relative to provided ISO Country Code.
 *
 * @param {string} str
 * @param {string} countryCode
 * @return {boolean}
 */

function isPassportNumber(str, countryCode) {
  (0, _assertString.default)(str);
  /** Remove All Whitespaces, Convert to UPPERCASE */

  var normalizedStr = str.replace(/\s/g, '').toUpperCase();
  return countryCode.toUpperCase() in passportRegexByCountryCode && passportRegexByCountryCode[countryCode].test(normalizedStr);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 7616:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isPort;

var _isInt = _interopRequireDefault(__webpack_require__(4503));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function isPort(str) {
  return (0, _isInt.default)(str, {
    min: 0,
    max: 65535
  });
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 2456:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isPostalCode;
exports.locales = void 0;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

// common patterns
var threeDigit = /^\d{3}$/;
var fourDigit = /^\d{4}$/;
var fiveDigit = /^\d{5}$/;
var sixDigit = /^\d{6}$/;
var patterns = {
  AD: /^AD\d{3}$/,
  AT: fourDigit,
  AU: fourDigit,
  AZ: /^AZ\d{4}$/,
  BE: fourDigit,
  BG: fourDigit,
  BR: /^\d{5}-\d{3}$/,
  BY: /2[1-4]{1}\d{4}$/,
  CA: /^[ABCEGHJKLMNPRSTVXY]\d[ABCEGHJ-NPRSTV-Z][\s\-]?\d[ABCEGHJ-NPRSTV-Z]\d$/i,
  CH: fourDigit,
  CN: /^(0[1-7]|1[012356]|2[0-7]|3[0-6]|4[0-7]|5[1-7]|6[1-7]|7[1-5]|8[1345]|9[09])\d{4}$/,
  CZ: /^\d{3}\s?\d{2}$/,
  DE: fiveDigit,
  DK: fourDigit,
  DO: fiveDigit,
  DZ: fiveDigit,
  EE: fiveDigit,
  ES: /^(5[0-2]{1}|[0-4]{1}\d{1})\d{3}$/,
  FI: fiveDigit,
  FR: /^\d{2}\s?\d{3}$/,
  GB: /^(gir\s?0aa|[a-z]{1,2}\d[\da-z]?\s?(\d[a-z]{2})?)$/i,
  GR: /^\d{3}\s?\d{2}$/,
  HR: /^([1-5]\d{4}$)/,
  HT: /^HT\d{4}$/,
  HU: fourDigit,
  ID: fiveDigit,
  IE: /^(?!.*(?:o))[A-z]\d[\dw]\s\w{4}$/i,
  IL: /^(\d{5}|\d{7})$/,
  IN: /^((?!10|29|35|54|55|65|66|86|87|88|89)[1-9][0-9]{5})$/,
  IR: /\b(?!(\d)\1{3})[13-9]{4}[1346-9][013-9]{5}\b/,
  IS: threeDigit,
  IT: fiveDigit,
  JP: /^\d{3}\-\d{4}$/,
  KE: fiveDigit,
  LI: /^(948[5-9]|949[0-7])$/,
  LT: /^LT\-\d{5}$/,
  LU: fourDigit,
  LV: /^LV\-\d{4}$/,
  MX: fiveDigit,
  MT: /^[A-Za-z]{3}\s{0,1}\d{4}$/,
  MY: fiveDigit,
  NL: /^\d{4}\s?[a-z]{2}$/i,
  NO: fourDigit,
  NP: /^(10|21|22|32|33|34|44|45|56|57)\d{3}$|^(977)$/i,
  NZ: fourDigit,
  PL: /^\d{2}\-\d{3}$/,
  PR: /^00[679]\d{2}([ -]\d{4})?$/,
  PT: /^\d{4}\-\d{3}?$/,
  RO: sixDigit,
  RU: sixDigit,
  SA: fiveDigit,
  SE: /^[1-9]\d{2}\s?\d{2}$/,
  SG: sixDigit,
  SI: fourDigit,
  SK: /^\d{3}\s?\d{2}$/,
  TH: fiveDigit,
  TN: fourDigit,
  TW: /^\d{3}(\d{2})?$/,
  UA: fiveDigit,
  US: /^\d{5}(-\d{4})?$/,
  ZA: fourDigit,
  ZM: fiveDigit
};
var locales = Object.keys(patterns);
exports.locales = locales;

function isPostalCode(str, locale) {
  (0, _assertString.default)(str);

  if (locale in patterns) {
    return patterns[locale].test(str);
  } else if (locale === 'any') {
    for (var key in patterns) {
      // https://github.com/gotwarlost/istanbul/blob/master/ignoring-code-for-coverage.md#ignoring-code-for-coverage-purposes
      // istanbul ignore else
      if (patterns.hasOwnProperty(key)) {
        var pattern = patterns[key];

        if (pattern.test(str)) {
          return true;
        }
      }
    }

    return false;
  }

  throw new Error("Invalid locale '".concat(locale, "'"));
}

/***/ }),

/***/ 593:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isRFC3339;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/* Based on https://tools.ietf.org/html/rfc3339#section-5.6 */
var dateFullYear = /[0-9]{4}/;
var dateMonth = /(0[1-9]|1[0-2])/;
var dateMDay = /([12]\d|0[1-9]|3[01])/;
var timeHour = /([01][0-9]|2[0-3])/;
var timeMinute = /[0-5][0-9]/;
var timeSecond = /([0-5][0-9]|60)/;
var timeSecFrac = /(\.[0-9]+)?/;
var timeNumOffset = new RegExp("[-+]".concat(timeHour.source, ":").concat(timeMinute.source));
var timeOffset = new RegExp("([zZ]|".concat(timeNumOffset.source, ")"));
var partialTime = new RegExp("".concat(timeHour.source, ":").concat(timeMinute.source, ":").concat(timeSecond.source).concat(timeSecFrac.source));
var fullDate = new RegExp("".concat(dateFullYear.source, "-").concat(dateMonth.source, "-").concat(dateMDay.source));
var fullTime = new RegExp("".concat(partialTime.source).concat(timeOffset.source));
var rfc3339 = new RegExp("".concat(fullDate.source, "[ tT]").concat(fullTime.source));

function isRFC3339(str) {
  (0, _assertString.default)(str);
  return rfc3339.test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 8270:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isRgbColor;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var rgbColor = /^rgb\((([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5]),){2}([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\)$/;
var rgbaColor = /^rgba\((([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5]),){3}(0?\.\d|1(\.0)?|0(\.0)?)\)$/;
var rgbColorPercent = /^rgb\((([0-9]%|[1-9][0-9]%|100%),){2}([0-9]%|[1-9][0-9]%|100%)\)/;
var rgbaColorPercent = /^rgba\((([0-9]%|[1-9][0-9]%|100%),){3}(0?\.\d|1(\.0)?|0(\.0)?)\)/;

function isRgbColor(str) {
  var includePercentValues = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
  (0, _assertString.default)(str);

  if (!includePercentValues) {
    return rgbColor.test(str) || rgbaColor.test(str);
  }

  return rgbColor.test(str) || rgbaColor.test(str) || rgbColorPercent.test(str) || rgbaColorPercent.test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 2868:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isSemVer;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _multilineRegex = _interopRequireDefault(__webpack_require__(8041));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/**
 * Regular Expression to match
 * semantic versioning (SemVer)
 * built from multi-line, multi-parts regexp
 * Reference: https://semver.org/
 */
var semanticVersioningRegex = (0, _multilineRegex.default)(['^(0|[1-9]\\d*)\\.(0|[1-9]\\d*)\\.(0|[1-9]\\d*)', '(?:-((?:0|[1-9]\\d*|\\d*[a-z-][0-9a-z-]*)(?:\\.(?:0|[1-9]\\d*|\\d*[a-z-][0-9a-z-]*))*))', '?(?:\\+([0-9a-z-]+(?:\\.[0-9a-z-]+)*))?$'], 'i');

function isSemVer(str) {
  (0, _assertString.default)(str);
  return semanticVersioningRegex.test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 4189:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isSlug;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var charsetRegex = /^[^\s-_](?!.*?[-_]{2,})([a-z0-9-\\]{1,})[^\s]*[^-_\s]$/;

function isSlug(str) {
  (0, _assertString.default)(str);
  return charsetRegex.test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 2487:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isStrongPassword;

var _merge = _interopRequireDefault(__webpack_require__(1778));

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var upperCaseRegex = /^[A-Z]$/;
var lowerCaseRegex = /^[a-z]$/;
var numberRegex = /^[0-9]$/;
var symbolRegex = /^[-#!$%^&*()_+|~=`{}\[\]:";'<>?,.\/ ]$/;
var defaultOptions = {
  minLength: 8,
  minLowercase: 1,
  minUppercase: 1,
  minNumbers: 1,
  minSymbols: 1,
  returnScore: false,
  pointsPerUnique: 1,
  pointsPerRepeat: 0.5,
  pointsForContainingLower: 10,
  pointsForContainingUpper: 10,
  pointsForContainingNumber: 10,
  pointsForContainingSymbol: 10
};
/* Counts number of occurrences of each char in a string
 * could be moved to util/ ?
*/

function countChars(str) {
  var result = {};
  Array.from(str).forEach(function (char) {
    var curVal = result[char];

    if (curVal) {
      result[char] += 1;
    } else {
      result[char] = 1;
    }
  });
  return result;
}
/* Return information about a password */


function analyzePassword(password) {
  var charMap = countChars(password);
  var analysis = {
    length: password.length,
    uniqueChars: Object.keys(charMap).length,
    uppercaseCount: 0,
    lowercaseCount: 0,
    numberCount: 0,
    symbolCount: 0
  };
  Object.keys(charMap).forEach(function (char) {
    if (upperCaseRegex.test(char)) {
      analysis.uppercaseCount += charMap[char];
    } else if (lowerCaseRegex.test(char)) {
      analysis.lowercaseCount += charMap[char];
    } else if (numberRegex.test(char)) {
      analysis.numberCount += charMap[char];
    } else if (symbolRegex.test(char)) {
      analysis.symbolCount += charMap[char];
    }
  });
  return analysis;
}

function scorePassword(analysis, scoringOptions) {
  var points = 0;
  points += analysis.uniqueChars * scoringOptions.pointsPerUnique;
  points += (analysis.length - analysis.uniqueChars) * scoringOptions.pointsPerRepeat;

  if (analysis.lowercaseCount > 0) {
    points += scoringOptions.pointsForContainingLower;
  }

  if (analysis.uppercaseCount > 0) {
    points += scoringOptions.pointsForContainingUpper;
  }

  if (analysis.numberCount > 0) {
    points += scoringOptions.pointsForContainingNumber;
  }

  if (analysis.symbolCount > 0) {
    points += scoringOptions.pointsForContainingSymbol;
  }

  return points;
}

function isStrongPassword(str) {
  var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
  (0, _assertString.default)(str);
  var analysis = analyzePassword(str);
  options = (0, _merge.default)(options || {}, defaultOptions);

  if (options.returnScore) {
    return scorePassword(analysis, options);
  }

  return analysis.length >= options.minLength && analysis.lowercaseCount >= options.minLowercase && analysis.uppercaseCount >= options.minUppercase && analysis.numberCount >= options.minNumbers && analysis.symbolCount >= options.minSymbols;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 8868:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isSurrogatePair;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var surrogatePair = /[\uD800-\uDBFF][\uDC00-\uDFFF]/;

function isSurrogatePair(str) {
  (0, _assertString.default)(str);
  return surrogatePair.test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 6693:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isTaxID;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var algorithms = _interopRequireWildcard(__webpack_require__(257));

var _isDate = _interopRequireDefault(__webpack_require__(7869));

function _getRequireWildcardCache() { if (typeof WeakMap !== "function") return null; var cache = new WeakMap(); _getRequireWildcardCache = function _getRequireWildcardCache() { return cache; }; return cache; }

function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } if (obj === null || _typeof(obj) !== "object" && typeof obj !== "function") { return { default: obj }; } var cache = _getRequireWildcardCache(); if (cache && cache.has(obj)) { return cache.get(obj); } var newObj = {}; var hasPropertyDescriptor = Object.defineProperty && Object.getOwnPropertyDescriptor; for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) { var desc = hasPropertyDescriptor ? Object.getOwnPropertyDescriptor(obj, key) : null; if (desc && (desc.get || desc.set)) { Object.defineProperty(newObj, key, desc); } else { newObj[key] = obj[key]; } } } newObj.default = obj; if (cache) { cache.set(obj, newObj); } return newObj; }

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && Symbol.iterator in Object(iter)) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

/**
 * TIN Validation
 * Validates Tax Identification Numbers (TINs) from the US, EU member states and the United Kingdom.
 *
 * EU-UK:
 * National TIN validity is calculated using public algorithms as made available by DG TAXUD.
 *
 * See `https://ec.europa.eu/taxation_customs/tin/specs/FS-TIN%20Algorithms-Public.docx` for more information.
 *
 * US:
 * An Employer Identification Number (EIN), also known as a Federal Tax Identification Number,
 *  is used to identify a business entity.
 *
 * NOTES:
 *  - Prefix 47 is being reserved for future use
 *  - Prefixes 26, 27, 45, 46 and 47 were previously assigned by the Philadelphia campus.
 *
 * See `http://www.irs.gov/Businesses/Small-Businesses-&-Self-Employed/How-EINs-are-Assigned-and-Valid-EIN-Prefixes`
 * for more information.
 */
// Locale functions

/*
 * bg-BG validation function
 * (Edinen graždanski nomer (EGN/ЕГН), persons only)
 * Checks if birth date (first six digits) is valid and calculates check (last) digit
 */
function bgBgCheck(tin) {
  // Extract full year, normalize month and check birth date validity
  var century_year = tin.slice(0, 2);
  var month = parseInt(tin.slice(2, 4), 10);

  if (month > 40) {
    month -= 40;
    century_year = "20".concat(century_year);
  } else if (month > 20) {
    month -= 20;
    century_year = "18".concat(century_year);
  } else {
    century_year = "19".concat(century_year);
  }

  if (month < 10) {
    month = "0".concat(month);
  }

  var date = "".concat(century_year, "/").concat(month, "/").concat(tin.slice(4, 6));

  if (!(0, _isDate.default)(date, 'YYYY/MM/DD')) {
    return false;
  } // split digits into an array for further processing


  var digits = tin.split('').map(function (a) {
    return parseInt(a, 10);
  }); // Calculate checksum by multiplying digits with fixed values

  var multip_lookup = [2, 4, 8, 5, 10, 9, 7, 3, 6];
  var checksum = 0;

  for (var i = 0; i < multip_lookup.length; i++) {
    checksum += digits[i] * multip_lookup[i];
  }

  checksum = checksum % 11 === 10 ? 0 : checksum % 11;
  return checksum === digits[9];
}
/*
 * cs-CZ validation function
 * (Rodné číslo (RČ), persons only)
 * Checks if birth date (first six digits) is valid and divisibility by 11
 * Material not in DG TAXUD document sourced from:
 * -`https://lorenc.info/3MA381/overeni-spravnosti-rodneho-cisla.htm`
 * -`https://www.mvcr.cz/clanek/rady-a-sluzby-dokumenty-rodne-cislo.aspx`
 */


function csCzCheck(tin) {
  tin = tin.replace(/\W/, ''); // Extract full year from TIN length

  var full_year = parseInt(tin.slice(0, 2), 10);

  if (tin.length === 10) {
    if (full_year < 54) {
      full_year = "20".concat(full_year);
    } else {
      full_year = "19".concat(full_year);
    }
  } else {
    if (tin.slice(6) === '000') {
      return false;
    } // Three-zero serial not assigned before 1954


    if (full_year < 54) {
      full_year = "19".concat(full_year);
    } else {
      return false; // No 18XX years seen in any of the resources
    }
  } // Add missing zero if needed


  if (full_year.length === 3) {
    full_year = [full_year.slice(0, 2), '0', full_year.slice(2)].join('');
  } // Extract month from TIN and normalize


  var month = parseInt(tin.slice(2, 4), 10);

  if (month > 50) {
    month -= 50;
  }

  if (month > 20) {
    // Month-plus-twenty was only introduced in 2004
    if (parseInt(full_year, 10) < 2004) {
      return false;
    }

    month -= 20;
  }

  if (month < 10) {
    month = "0".concat(month);
  } // Check date validity


  var date = "".concat(full_year, "/").concat(month, "/").concat(tin.slice(4, 6));

  if (!(0, _isDate.default)(date, 'YYYY/MM/DD')) {
    return false;
  } // Verify divisibility by 11


  if (tin.length === 10) {
    if (parseInt(tin, 10) % 11 !== 0) {
      // Some numbers up to and including 1985 are still valid if
      // check (last) digit equals 0 and modulo of first 9 digits equals 10
      var checkdigit = parseInt(tin.slice(0, 9), 10) % 11;

      if (parseInt(full_year, 10) < 1986 && checkdigit === 10) {
        if (parseInt(tin.slice(9), 10) !== 0) {
          return false;
        }
      } else {
        return false;
      }
    }
  }

  return true;
}
/*
 * de-AT validation function
 * (Abgabenkontonummer, persons/entities)
 * Verify TIN validity by calling luhnCheck()
 */


function deAtCheck(tin) {
  return algorithms.luhnCheck(tin);
}
/*
 * de-DE validation function
 * (Steueridentifikationsnummer (Steuer-IdNr.), persons only)
 * Tests for single duplicate/triplicate value, then calculates ISO 7064 check (last) digit
 * Partial implementation of spec (same result with both algorithms always)
 */


function deDeCheck(tin) {
  // Split digits into an array for further processing
  var digits = tin.split('').map(function (a) {
    return parseInt(a, 10);
  }); // Fill array with strings of number positions

  var occurences = [];

  for (var i = 0; i < digits.length - 1; i++) {
    occurences.push('');

    for (var j = 0; j < digits.length - 1; j++) {
      if (digits[i] === digits[j]) {
        occurences[i] += j;
      }
    }
  } // Remove digits with one occurence and test for only one duplicate/triplicate


  occurences = occurences.filter(function (a) {
    return a.length > 1;
  });

  if (occurences.length !== 2 && occurences.length !== 3) {
    return false;
  } // In case of triplicate value only two digits are allowed next to each other


  if (occurences[0].length === 3) {
    var trip_locations = occurences[0].split('').map(function (a) {
      return parseInt(a, 10);
    });
    var recurrent = 0; // Amount of neighbour occurences

    for (var _i = 0; _i < trip_locations.length - 1; _i++) {
      if (trip_locations[_i] + 1 === trip_locations[_i + 1]) {
        recurrent += 1;
      }
    }

    if (recurrent === 2) {
      return false;
    }
  }

  return algorithms.iso7064Check(tin);
}
/*
 * dk-DK validation function
 * (CPR-nummer (personnummer), persons only)
 * Checks if birth date (first six digits) is valid and assigned to century (seventh) digit,
 * and calculates check (last) digit
 */


function dkDkCheck(tin) {
  tin = tin.replace(/\W/, ''); // Extract year, check if valid for given century digit and add century

  var year = parseInt(tin.slice(4, 6), 10);
  var century_digit = tin.slice(6, 7);

  switch (century_digit) {
    case '0':
    case '1':
    case '2':
    case '3':
      year = "19".concat(year);
      break;

    case '4':
    case '9':
      if (year < 37) {
        year = "20".concat(year);
      } else {
        year = "19".concat(year);
      }

      break;

    default:
      if (year < 37) {
        year = "20".concat(year);
      } else if (year > 58) {
        year = "18".concat(year);
      } else {
        return false;
      }

      break;
  } // Add missing zero if needed


  if (year.length === 3) {
    year = [year.slice(0, 2), '0', year.slice(2)].join('');
  } // Check date validity


  var date = "".concat(year, "/").concat(tin.slice(2, 4), "/").concat(tin.slice(0, 2));

  if (!(0, _isDate.default)(date, 'YYYY/MM/DD')) {
    return false;
  } // Split digits into an array for further processing


  var digits = tin.split('').map(function (a) {
    return parseInt(a, 10);
  });
  var checksum = 0;
  var weight = 4; // Multiply by weight and add to checksum

  for (var i = 0; i < 9; i++) {
    checksum += digits[i] * weight;
    weight -= 1;

    if (weight === 1) {
      weight = 7;
    }
  }

  checksum %= 11;

  if (checksum === 1) {
    return false;
  }

  return checksum === 0 ? digits[9] === 0 : digits[9] === 11 - checksum;
}
/*
 * el-CY validation function
 * (Arithmos Forologikou Mitroou (AFM/ΑΦΜ), persons only)
 * Verify TIN validity by calculating ASCII value of check (last) character
 */


function elCyCheck(tin) {
  // split digits into an array for further processing
  var digits = tin.slice(0, 8).split('').map(function (a) {
    return parseInt(a, 10);
  });
  var checksum = 0; // add digits in even places

  for (var i = 1; i < digits.length; i += 2) {
    checksum += digits[i];
  } // add digits in odd places


  for (var _i2 = 0; _i2 < digits.length; _i2 += 2) {
    if (digits[_i2] < 2) {
      checksum += 1 - digits[_i2];
    } else {
      checksum += 2 * (digits[_i2] - 2) + 5;

      if (digits[_i2] > 4) {
        checksum += 2;
      }
    }
  }

  return String.fromCharCode(checksum % 26 + 65) === tin.charAt(8);
}
/*
 * el-GR validation function
 * (Arithmos Forologikou Mitroou (AFM/ΑΦΜ), persons/entities)
 * Verify TIN validity by calculating check (last) digit
 * Algorithm not in DG TAXUD document- sourced from:
 * - `http://epixeirisi.gr/%CE%9A%CE%A1%CE%99%CE%A3%CE%99%CE%9C%CE%91-%CE%98%CE%95%CE%9C%CE%91%CE%A4%CE%91-%CE%A6%CE%9F%CE%A1%CE%9F%CE%9B%CE%9F%CE%93%CE%99%CE%91%CE%A3-%CE%9A%CE%91%CE%99-%CE%9B%CE%9F%CE%93%CE%99%CE%A3%CE%A4%CE%99%CE%9A%CE%97%CE%A3/23791/%CE%91%CF%81%CE%B9%CE%B8%CE%BC%CF%8C%CF%82-%CE%A6%CE%BF%CF%81%CE%BF%CE%BB%CE%BF%CE%B3%CE%B9%CE%BA%CE%BF%CF%8D-%CE%9C%CE%B7%CF%84%CF%81%CF%8E%CE%BF%CF%85`
 */


function elGrCheck(tin) {
  // split digits into an array for further processing
  var digits = tin.split('').map(function (a) {
    return parseInt(a, 10);
  });
  var checksum = 0;

  for (var i = 0; i < 8; i++) {
    checksum += digits[i] * Math.pow(2, 8 - i);
  }

  return checksum % 11 === digits[8];
}
/*
 * en-GB validation function (should go here if needed)
 * (National Insurance Number (NINO) or Unique Taxpayer Reference (UTR),
 * persons/entities respectively)
 */

/*
 * en-IE validation function
 * (Personal Public Service Number (PPS No), persons only)
 * Verify TIN validity by calculating check (second to last) character
 */


function enIeCheck(tin) {
  var checksum = algorithms.reverseMultiplyAndSum(tin.split('').slice(0, 7).map(function (a) {
    return parseInt(a, 10);
  }), 8);

  if (tin.length === 9 && tin[8] !== 'W') {
    checksum += (tin[8].charCodeAt(0) - 64) * 9;
  }

  checksum %= 23;

  if (checksum === 0) {
    return tin[7].toUpperCase() === 'W';
  }

  return tin[7].toUpperCase() === String.fromCharCode(64 + checksum);
} // Valid US IRS campus prefixes


var enUsCampusPrefix = {
  andover: ['10', '12'],
  atlanta: ['60', '67'],
  austin: ['50', '53'],
  brookhaven: ['01', '02', '03', '04', '05', '06', '11', '13', '14', '16', '21', '22', '23', '25', '34', '51', '52', '54', '55', '56', '57', '58', '59', '65'],
  cincinnati: ['30', '32', '35', '36', '37', '38', '61'],
  fresno: ['15', '24'],
  internet: ['20', '26', '27', '45', '46', '47'],
  kansas: ['40', '44'],
  memphis: ['94', '95'],
  ogden: ['80', '90'],
  philadelphia: ['33', '39', '41', '42', '43', '46', '48', '62', '63', '64', '66', '68', '71', '72', '73', '74', '75', '76', '77', '81', '82', '83', '84', '85', '86', '87', '88', '91', '92', '93', '98', '99'],
  sba: ['31']
}; // Return an array of all US IRS campus prefixes

function enUsGetPrefixes() {
  var prefixes = [];

  for (var location in enUsCampusPrefix) {
    // https://github.com/gotwarlost/istanbul/blob/master/ignoring-code-for-coverage.md#ignoring-code-for-coverage-purposes
    // istanbul ignore else
    if (enUsCampusPrefix.hasOwnProperty(location)) {
      prefixes.push.apply(prefixes, _toConsumableArray(enUsCampusPrefix[location]));
    }
  }

  return prefixes;
}
/*
 * en-US validation function
 * Verify that the TIN starts with a valid IRS campus prefix
 */


function enUsCheck(tin) {
  return enUsGetPrefixes().indexOf(tin.substr(0, 2)) !== -1;
}
/*
 * es-ES validation function
 * (Documento Nacional de Identidad (DNI)
 * or Número de Identificación de Extranjero (NIE), persons only)
 * Verify TIN validity by calculating check (last) character
 */


function esEsCheck(tin) {
  // Split characters into an array for further processing
  var chars = tin.toUpperCase().split(''); // Replace initial letter if needed

  if (isNaN(parseInt(chars[0], 10)) && chars.length > 1) {
    var lead_replace = 0;

    switch (chars[0]) {
      case 'Y':
        lead_replace = 1;
        break;

      case 'Z':
        lead_replace = 2;
        break;

      default:
    }

    chars.splice(0, 1, lead_replace); // Fill with zeros if smaller than proper
  } else {
    while (chars.length < 9) {
      chars.unshift(0);
    }
  } // Calculate checksum and check according to lookup


  var lookup = ['T', 'R', 'W', 'A', 'G', 'M', 'Y', 'F', 'P', 'D', 'X', 'B', 'N', 'J', 'Z', 'S', 'Q', 'V', 'H', 'L', 'C', 'K', 'E'];
  chars = chars.join('');
  var checksum = parseInt(chars.slice(0, 8), 10) % 23;
  return chars[8] === lookup[checksum];
}
/*
 * et-EE validation function
 * (Isikukood (IK), persons only)
 * Checks if birth date (century digit and six following) is valid and calculates check (last) digit
 * Material not in DG TAXUD document sourced from:
 * - `https://www.oecd.org/tax/automatic-exchange/crs-implementation-and-assistance/tax-identification-numbers/Estonia-TIN.pdf`
 */


function etEeCheck(tin) {
  // Extract year and add century
  var full_year = tin.slice(1, 3);
  var century_digit = tin.slice(0, 1);

  switch (century_digit) {
    case '1':
    case '2':
      full_year = "18".concat(full_year);
      break;

    case '3':
    case '4':
      full_year = "19".concat(full_year);
      break;

    default:
      full_year = "20".concat(full_year);
      break;
  } // Check date validity


  var date = "".concat(full_year, "/").concat(tin.slice(3, 5), "/").concat(tin.slice(5, 7));

  if (!(0, _isDate.default)(date, 'YYYY/MM/DD')) {
    return false;
  } // Split digits into an array for further processing


  var digits = tin.split('').map(function (a) {
    return parseInt(a, 10);
  });
  var checksum = 0;
  var weight = 1; // Multiply by weight and add to checksum

  for (var i = 0; i < 10; i++) {
    checksum += digits[i] * weight;
    weight += 1;

    if (weight === 10) {
      weight = 1;
    }
  } // Do again if modulo 11 of checksum is 10


  if (checksum % 11 === 10) {
    checksum = 0;
    weight = 3;

    for (var _i3 = 0; _i3 < 10; _i3++) {
      checksum += digits[_i3] * weight;
      weight += 1;

      if (weight === 10) {
        weight = 1;
      }
    }

    if (checksum % 11 === 10) {
      return digits[10] === 0;
    }
  }

  return checksum % 11 === digits[10];
}
/*
 * fi-FI validation function
 * (Henkilötunnus (HETU), persons only)
 * Checks if birth date (first six digits plus century symbol) is valid
 * and calculates check (last) digit
 */


function fiFiCheck(tin) {
  // Extract year and add century
  var full_year = tin.slice(4, 6);
  var century_symbol = tin.slice(6, 7);

  switch (century_symbol) {
    case '+':
      full_year = "18".concat(full_year);
      break;

    case '-':
      full_year = "19".concat(full_year);
      break;

    default:
      full_year = "20".concat(full_year);
      break;
  } // Check date validity


  var date = "".concat(full_year, "/").concat(tin.slice(2, 4), "/").concat(tin.slice(0, 2));

  if (!(0, _isDate.default)(date, 'YYYY/MM/DD')) {
    return false;
  } // Calculate check character


  var checksum = parseInt(tin.slice(0, 6) + tin.slice(7, 10), 10) % 31;

  if (checksum < 10) {
    return checksum === parseInt(tin.slice(10), 10);
  }

  checksum -= 10;
  var letters_lookup = ['A', 'B', 'C', 'D', 'E', 'F', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y'];
  return letters_lookup[checksum] === tin.slice(10);
}
/*
 * fr/nl-BE validation function
 * (Numéro national (N.N.), persons only)
 * Checks if birth date (first six digits) is valid and calculates check (last two) digits
 */


function frBeCheck(tin) {
  // Zero month/day value is acceptable
  if (tin.slice(2, 4) !== '00' || tin.slice(4, 6) !== '00') {
    // Extract date from first six digits of TIN
    var date = "".concat(tin.slice(0, 2), "/").concat(tin.slice(2, 4), "/").concat(tin.slice(4, 6));

    if (!(0, _isDate.default)(date, 'YY/MM/DD')) {
      return false;
    }
  }

  var checksum = 97 - parseInt(tin.slice(0, 9), 10) % 97;
  var checkdigits = parseInt(tin.slice(9, 11), 10);

  if (checksum !== checkdigits) {
    checksum = 97 - parseInt("2".concat(tin.slice(0, 9)), 10) % 97;

    if (checksum !== checkdigits) {
      return false;
    }
  }

  return true;
}
/*
 * fr-FR validation function
 * (Numéro fiscal de référence (numéro SPI), persons only)
 * Verify TIN validity by calculating check (last three) digits
 */


function frFrCheck(tin) {
  tin = tin.replace(/\s/g, '');
  var checksum = parseInt(tin.slice(0, 10), 10) % 511;
  var checkdigits = parseInt(tin.slice(10, 13), 10);
  return checksum === checkdigits;
}
/*
 * fr/lb-LU validation function
 * (numéro d’identification personnelle, persons only)
 * Verify birth date validity and run Luhn and Verhoeff checks
 */


function frLuCheck(tin) {
  // Extract date and check validity
  var date = "".concat(tin.slice(0, 4), "/").concat(tin.slice(4, 6), "/").concat(tin.slice(6, 8));

  if (!(0, _isDate.default)(date, 'YYYY/MM/DD')) {
    return false;
  } // Run Luhn check


  if (!algorithms.luhnCheck(tin.slice(0, 12))) {
    return false;
  } // Remove Luhn check digit and run Verhoeff check


  return algorithms.verhoeffCheck("".concat(tin.slice(0, 11)).concat(tin[12]));
}
/*
 * hr-HR validation function
 * (Osobni identifikacijski broj (OIB), persons/entities)
 * Verify TIN validity by calling iso7064Check(digits)
 */


function hrHrCheck(tin) {
  return algorithms.iso7064Check(tin);
}
/*
 * hu-HU validation function
 * (Adóazonosító jel, persons only)
 * Verify TIN validity by calculating check (last) digit
 */


function huHuCheck(tin) {
  // split digits into an array for further processing
  var digits = tin.split('').map(function (a) {
    return parseInt(a, 10);
  });
  var checksum = 8;

  for (var i = 1; i < 9; i++) {
    checksum += digits[i] * (i + 1);
  }

  return checksum % 11 === digits[9];
}
/*
 * lt-LT validation function (should go here if needed)
 * (Asmens kodas, persons/entities respectively)
 * Current validation check is alias of etEeCheck- same format applies
 */

/*
 * it-IT first/last name validity check
 * Accepts it-IT TIN-encoded names as a three-element character array and checks their validity
 * Due to lack of clarity between resources ("Are only Italian consonants used?
 * What happens if a person has X in their name?" etc.) only two test conditions
 * have been implemented:
 * Vowels may only be followed by other vowels or an X character
 * and X characters after vowels may only be followed by other X characters.
 */


function itItNameCheck(name) {
  // true at the first occurence of a vowel
  var vowelflag = false; // true at the first occurence of an X AFTER vowel
  // (to properly handle last names with X as consonant)

  var xflag = false;

  for (var i = 0; i < 3; i++) {
    if (!vowelflag && /[AEIOU]/.test(name[i])) {
      vowelflag = true;
    } else if (!xflag && vowelflag && name[i] === 'X') {
      xflag = true;
    } else if (i > 0) {
      if (vowelflag && !xflag) {
        if (!/[AEIOU]/.test(name[i])) {
          return false;
        }
      }

      if (xflag) {
        if (!/X/.test(name[i])) {
          return false;
        }
      }
    }
  }

  return true;
}
/*
 * it-IT validation function
 * (Codice fiscale (TIN-IT), persons only)
 * Verify name, birth date and codice catastale validity
 * and calculate check character.
 * Material not in DG-TAXUD document sourced from:
 * `https://en.wikipedia.org/wiki/Italian_fiscal_code`
 */


function itItCheck(tin) {
  // Capitalize and split characters into an array for further processing
  var chars = tin.toUpperCase().split(''); // Check first and last name validity calling itItNameCheck()

  if (!itItNameCheck(chars.slice(0, 3))) {
    return false;
  }

  if (!itItNameCheck(chars.slice(3, 6))) {
    return false;
  } // Convert letters in number spaces back to numbers if any


  var number_locations = [6, 7, 9, 10, 12, 13, 14];
  var number_replace = {
    L: '0',
    M: '1',
    N: '2',
    P: '3',
    Q: '4',
    R: '5',
    S: '6',
    T: '7',
    U: '8',
    V: '9'
  };

  for (var _i4 = 0, _number_locations = number_locations; _i4 < _number_locations.length; _i4++) {
    var i = _number_locations[_i4];

    if (chars[i] in number_replace) {
      chars.splice(i, 1, number_replace[chars[i]]);
    }
  } // Extract month and day, and check date validity


  var month_replace = {
    A: '01',
    B: '02',
    C: '03',
    D: '04',
    E: '05',
    H: '06',
    L: '07',
    M: '08',
    P: '09',
    R: '10',
    S: '11',
    T: '12'
  };
  var month = month_replace[chars[8]];
  var day = parseInt(chars[9] + chars[10], 10);

  if (day > 40) {
    day -= 40;
  }

  if (day < 10) {
    day = "0".concat(day);
  }

  var date = "".concat(chars[6]).concat(chars[7], "/").concat(month, "/").concat(day);

  if (!(0, _isDate.default)(date, 'YY/MM/DD')) {
    return false;
  } // Calculate check character by adding up even and odd characters as numbers


  var checksum = 0;

  for (var _i5 = 1; _i5 < chars.length - 1; _i5 += 2) {
    var char_to_int = parseInt(chars[_i5], 10);

    if (isNaN(char_to_int)) {
      char_to_int = chars[_i5].charCodeAt(0) - 65;
    }

    checksum += char_to_int;
  }

  var odd_convert = {
    // Maps of characters at odd places
    A: 1,
    B: 0,
    C: 5,
    D: 7,
    E: 9,
    F: 13,
    G: 15,
    H: 17,
    I: 19,
    J: 21,
    K: 2,
    L: 4,
    M: 18,
    N: 20,
    O: 11,
    P: 3,
    Q: 6,
    R: 8,
    S: 12,
    T: 14,
    U: 16,
    V: 10,
    W: 22,
    X: 25,
    Y: 24,
    Z: 23,
    0: 1,
    1: 0
  };

  for (var _i6 = 0; _i6 < chars.length - 1; _i6 += 2) {
    var _char_to_int = 0;

    if (chars[_i6] in odd_convert) {
      _char_to_int = odd_convert[chars[_i6]];
    } else {
      var multiplier = parseInt(chars[_i6], 10);
      _char_to_int = 2 * multiplier + 1;

      if (multiplier > 4) {
        _char_to_int += 2;
      }
    }

    checksum += _char_to_int;
  }

  if (String.fromCharCode(65 + checksum % 26) !== chars[15]) {
    return false;
  }

  return true;
}
/*
 * lv-LV validation function
 * (Personas kods (PK), persons only)
 * Check validity of birth date and calculate check (last) digit
 * Support only for old format numbers (not starting with '32', issued before 2017/07/01)
 * Material not in DG TAXUD document sourced from:
 * `https://boot.ritakafija.lv/forums/index.php?/topic/88314-personas-koda-algoritms-%C4%8Deksumma/`
 */


function lvLvCheck(tin) {
  tin = tin.replace(/\W/, ''); // Extract date from TIN

  var day = tin.slice(0, 2);

  if (day !== '32') {
    // No date/checksum check if new format
    var month = tin.slice(2, 4);

    if (month !== '00') {
      // No date check if unknown month
      var full_year = tin.slice(4, 6);

      switch (tin[6]) {
        case '0':
          full_year = "18".concat(full_year);
          break;

        case '1':
          full_year = "19".concat(full_year);
          break;

        default:
          full_year = "20".concat(full_year);
          break;
      } // Check date validity


      var date = "".concat(full_year, "/").concat(tin.slice(2, 4), "/").concat(day);

      if (!(0, _isDate.default)(date, 'YYYY/MM/DD')) {
        return false;
      }
    } // Calculate check digit


    var checksum = 1101;
    var multip_lookup = [1, 6, 3, 7, 9, 10, 5, 8, 4, 2];

    for (var i = 0; i < tin.length - 1; i++) {
      checksum -= parseInt(tin[i], 10) * multip_lookup[i];
    }

    return parseInt(tin[10], 10) === checksum % 11;
  }

  return true;
}
/*
 * mt-MT validation function
 * (Identity Card Number or Unique Taxpayer Reference, persons/entities)
 * Verify Identity Card Number structure (no other tests found)
 */


function mtMtCheck(tin) {
  if (tin.length !== 9) {
    // No tests for UTR
    var chars = tin.toUpperCase().split(''); // Fill with zeros if smaller than proper

    while (chars.length < 8) {
      chars.unshift(0);
    } // Validate format according to last character


    switch (tin[7]) {
      case 'A':
      case 'P':
        if (parseInt(chars[6], 10) === 0) {
          return false;
        }

        break;

      default:
        {
          var first_part = parseInt(chars.join('').slice(0, 5), 10);

          if (first_part > 32000) {
            return false;
          }

          var second_part = parseInt(chars.join('').slice(5, 7), 10);

          if (first_part === second_part) {
            return false;
          }
        }
    }
  }

  return true;
}
/*
 * nl-NL validation function
 * (Burgerservicenummer (BSN) or Rechtspersonen Samenwerkingsverbanden Informatie Nummer (RSIN),
 * persons/entities respectively)
 * Verify TIN validity by calculating check (last) digit (variant of MOD 11)
 */


function nlNlCheck(tin) {
  return algorithms.reverseMultiplyAndSum(tin.split('').slice(0, 8).map(function (a) {
    return parseInt(a, 10);
  }), 9) % 11 === parseInt(tin[8], 10);
}
/*
 * pl-PL validation function
 * (Powszechny Elektroniczny System Ewidencji Ludności (PESEL)
 * or Numer identyfikacji podatkowej (NIP), persons/entities)
 * Verify TIN validity by validating birth date (PESEL) and calculating check (last) digit
 */


function plPlCheck(tin) {
  // NIP
  if (tin.length === 10) {
    // Calculate last digit by multiplying with lookup
    var lookup = [6, 5, 7, 2, 3, 4, 5, 6, 7];
    var _checksum = 0;

    for (var i = 0; i < lookup.length; i++) {
      _checksum += parseInt(tin[i], 10) * lookup[i];
    }

    _checksum %= 11;

    if (_checksum === 10) {
      return false;
    }

    return _checksum === parseInt(tin[9], 10);
  } // PESEL
  // Extract full year using month


  var full_year = tin.slice(0, 2);
  var month = parseInt(tin.slice(2, 4), 10);

  if (month > 80) {
    full_year = "18".concat(full_year);
    month -= 80;
  } else if (month > 60) {
    full_year = "22".concat(full_year);
    month -= 60;
  } else if (month > 40) {
    full_year = "21".concat(full_year);
    month -= 40;
  } else if (month > 20) {
    full_year = "20".concat(full_year);
    month -= 20;
  } else {
    full_year = "19".concat(full_year);
  } // Add leading zero to month if needed


  if (month < 10) {
    month = "0".concat(month);
  } // Check date validity


  var date = "".concat(full_year, "/").concat(month, "/").concat(tin.slice(4, 6));

  if (!(0, _isDate.default)(date, 'YYYY/MM/DD')) {
    return false;
  } // Calculate last digit by mulitplying with odd one-digit numbers except 5


  var checksum = 0;
  var multiplier = 1;

  for (var _i7 = 0; _i7 < tin.length - 1; _i7++) {
    checksum += parseInt(tin[_i7], 10) * multiplier % 10;
    multiplier += 2;

    if (multiplier > 10) {
      multiplier = 1;
    } else if (multiplier === 5) {
      multiplier += 2;
    }
  }

  checksum = 10 - checksum % 10;
  return checksum === parseInt(tin[10], 10);
}
/*
 * pt-PT validation function
 * (Número de identificação fiscal (NIF), persons/entities)
 * Verify TIN validity by calculating check (last) digit (variant of MOD 11)
 */


function ptPtCheck(tin) {
  var checksum = 11 - algorithms.reverseMultiplyAndSum(tin.split('').slice(0, 8).map(function (a) {
    return parseInt(a, 10);
  }), 9) % 11;

  if (checksum > 9) {
    return parseInt(tin[8], 10) === 0;
  }

  return checksum === parseInt(tin[8], 10);
}
/*
 * ro-RO validation function
 * (Cod Numeric Personal (CNP) or Cod de înregistrare fiscală (CIF),
 * persons only)
 * Verify CNP validity by calculating check (last) digit (test not found for CIF)
 * Material not in DG TAXUD document sourced from:
 * `https://en.wikipedia.org/wiki/National_identification_number#Romania`
 */


function roRoCheck(tin) {
  if (tin.slice(0, 4) !== '9000') {
    // No test found for this format
    // Extract full year using century digit if possible
    var full_year = tin.slice(1, 3);

    switch (tin[0]) {
      case '1':
      case '2':
        full_year = "19".concat(full_year);
        break;

      case '3':
      case '4':
        full_year = "18".concat(full_year);
        break;

      case '5':
      case '6':
        full_year = "20".concat(full_year);
        break;

      default:
    } // Check date validity


    var date = "".concat(full_year, "/").concat(tin.slice(3, 5), "/").concat(tin.slice(5, 7));

    if (date.length === 8) {
      if (!(0, _isDate.default)(date, 'YY/MM/DD')) {
        return false;
      }
    } else if (!(0, _isDate.default)(date, 'YYYY/MM/DD')) {
      return false;
    } // Calculate check digit


    var digits = tin.split('').map(function (a) {
      return parseInt(a, 10);
    });
    var multipliers = [2, 7, 9, 1, 4, 6, 3, 5, 8, 2, 7, 9];
    var checksum = 0;

    for (var i = 0; i < multipliers.length; i++) {
      checksum += digits[i] * multipliers[i];
    }

    if (checksum % 11 === 10) {
      return digits[12] === 1;
    }

    return digits[12] === checksum % 11;
  }

  return true;
}
/*
 * sk-SK validation function
 * (Rodné číslo (RČ) or bezvýznamové identifikačné číslo (BIČ), persons only)
 * Checks validity of pre-1954 birth numbers (rodné číslo) only
 * Due to the introduction of the pseudo-random BIČ it is not possible to test
 * post-1954 birth numbers without knowing whether they are BIČ or RČ beforehand
 */


function skSkCheck(tin) {
  if (tin.length === 9) {
    tin = tin.replace(/\W/, '');

    if (tin.slice(6) === '000') {
      return false;
    } // Three-zero serial not assigned before 1954
    // Extract full year from TIN length


    var full_year = parseInt(tin.slice(0, 2), 10);

    if (full_year > 53) {
      return false;
    }

    if (full_year < 10) {
      full_year = "190".concat(full_year);
    } else {
      full_year = "19".concat(full_year);
    } // Extract month from TIN and normalize


    var month = parseInt(tin.slice(2, 4), 10);

    if (month > 50) {
      month -= 50;
    }

    if (month < 10) {
      month = "0".concat(month);
    } // Check date validity


    var date = "".concat(full_year, "/").concat(month, "/").concat(tin.slice(4, 6));

    if (!(0, _isDate.default)(date, 'YYYY/MM/DD')) {
      return false;
    }
  }

  return true;
}
/*
 * sl-SI validation function
 * (Davčna številka, persons/entities)
 * Verify TIN validity by calculating check (last) digit (variant of MOD 11)
 */


function slSiCheck(tin) {
  var checksum = 11 - algorithms.reverseMultiplyAndSum(tin.split('').slice(0, 7).map(function (a) {
    return parseInt(a, 10);
  }), 8) % 11;

  if (checksum === 10) {
    return parseInt(tin[7], 10) === 0;
  }

  return checksum === parseInt(tin[7], 10);
}
/*
 * sv-SE validation function
 * (Personnummer or samordningsnummer, persons only)
 * Checks validity of birth date and calls luhnCheck() to validate check (last) digit
 */


function svSeCheck(tin) {
  // Make copy of TIN and normalize to two-digit year form
  var tin_copy = tin.slice(0);

  if (tin.length > 11) {
    tin_copy = tin_copy.slice(2);
  } // Extract date of birth


  var full_year = '';
  var month = tin_copy.slice(2, 4);
  var day = parseInt(tin_copy.slice(4, 6), 10);

  if (tin.length > 11) {
    full_year = tin.slice(0, 4);
  } else {
    full_year = tin.slice(0, 2);

    if (tin.length === 11 && day < 60) {
      // Extract full year from centenarian symbol
      // Should work just fine until year 10000 or so
      var current_year = new Date().getFullYear().toString();
      var current_century = parseInt(current_year.slice(0, 2), 10);
      current_year = parseInt(current_year, 10);

      if (tin[6] === '-') {
        if (parseInt("".concat(current_century).concat(full_year), 10) > current_year) {
          full_year = "".concat(current_century - 1).concat(full_year);
        } else {
          full_year = "".concat(current_century).concat(full_year);
        }
      } else {
        full_year = "".concat(current_century - 1).concat(full_year);

        if (current_year - parseInt(full_year, 10) < 100) {
          return false;
        }
      }
    }
  } // Normalize day and check date validity


  if (day > 60) {
    day -= 60;
  }

  if (day < 10) {
    day = "0".concat(day);
  }

  var date = "".concat(full_year, "/").concat(month, "/").concat(day);

  if (date.length === 8) {
    if (!(0, _isDate.default)(date, 'YY/MM/DD')) {
      return false;
    }
  } else if (!(0, _isDate.default)(date, 'YYYY/MM/DD')) {
    return false;
  }

  return algorithms.luhnCheck(tin.replace(/\W/, ''));
} // Locale lookup objects

/*
 * Tax id regex formats for various locales
 *
 * Where not explicitly specified in DG-TAXUD document both
 * uppercase and lowercase letters are acceptable.
 */


var taxIdFormat = {
  'bg-BG': /^\d{10}$/,
  'cs-CZ': /^\d{6}\/{0,1}\d{3,4}$/,
  'de-AT': /^\d{9}$/,
  'de-DE': /^[1-9]\d{10}$/,
  'dk-DK': /^\d{6}-{0,1}\d{4}$/,
  'el-CY': /^[09]\d{7}[A-Z]$/,
  'el-GR': /^([0-4]|[7-9])\d{8}$/,
  'en-GB': /^\d{10}$|^(?!GB|NK|TN|ZZ)(?![DFIQUV])[A-Z](?![DFIQUVO])[A-Z]\d{6}[ABCD ]$/i,
  'en-IE': /^\d{7}[A-W][A-IW]{0,1}$/i,
  'en-US': /^\d{2}[- ]{0,1}\d{7}$/,
  'es-ES': /^(\d{0,8}|[XYZKLM]\d{7})[A-HJ-NP-TV-Z]$/i,
  'et-EE': /^[1-6]\d{6}(00[1-9]|0[1-9][0-9]|[1-6][0-9]{2}|70[0-9]|710)\d$/,
  'fi-FI': /^\d{6}[-+A]\d{3}[0-9A-FHJ-NPR-Y]$/i,
  'fr-BE': /^\d{11}$/,
  'fr-FR': /^[0-3]\d{12}$|^[0-3]\d\s\d{2}(\s\d{3}){3}$/,
  // Conforms both to official spec and provided example
  'fr-LU': /^\d{13}$/,
  'hr-HR': /^\d{11}$/,
  'hu-HU': /^8\d{9}$/,
  'it-IT': /^[A-Z]{6}[L-NP-V0-9]{2}[A-EHLMPRST][L-NP-V0-9]{2}[A-ILMZ][L-NP-V0-9]{3}[A-Z]$/i,
  'lv-LV': /^\d{6}-{0,1}\d{5}$/,
  // Conforms both to DG TAXUD spec and original research
  'mt-MT': /^\d{3,7}[APMGLHBZ]$|^([1-8])\1\d{7}$/i,
  'nl-NL': /^\d{9}$/,
  'pl-PL': /^\d{10,11}$/,
  'pt-PT': /^\d{9}$/,
  'ro-RO': /^\d{13}$/,
  'sk-SK': /^\d{6}\/{0,1}\d{3,4}$/,
  'sl-SI': /^[1-9]\d{7}$/,
  'sv-SE': /^(\d{6}[-+]{0,1}\d{4}|(18|19|20)\d{6}[-+]{0,1}\d{4})$/
}; // taxIdFormat locale aliases

taxIdFormat['lb-LU'] = taxIdFormat['fr-LU'];
taxIdFormat['lt-LT'] = taxIdFormat['et-EE'];
taxIdFormat['nl-BE'] = taxIdFormat['fr-BE']; // Algorithmic tax id check functions for various locales

var taxIdCheck = {
  'bg-BG': bgBgCheck,
  'cs-CZ': csCzCheck,
  'de-AT': deAtCheck,
  'de-DE': deDeCheck,
  'dk-DK': dkDkCheck,
  'el-CY': elCyCheck,
  'el-GR': elGrCheck,
  'en-IE': enIeCheck,
  'en-US': enUsCheck,
  'es-ES': esEsCheck,
  'et-EE': etEeCheck,
  'fi-FI': fiFiCheck,
  'fr-BE': frBeCheck,
  'fr-FR': frFrCheck,
  'fr-LU': frLuCheck,
  'hr-HR': hrHrCheck,
  'hu-HU': huHuCheck,
  'it-IT': itItCheck,
  'lv-LV': lvLvCheck,
  'mt-MT': mtMtCheck,
  'nl-NL': nlNlCheck,
  'pl-PL': plPlCheck,
  'pt-PT': ptPtCheck,
  'ro-RO': roRoCheck,
  'sk-SK': skSkCheck,
  'sl-SI': slSiCheck,
  'sv-SE': svSeCheck
}; // taxIdCheck locale aliases

taxIdCheck['lb-LU'] = taxIdCheck['fr-LU'];
taxIdCheck['lt-LT'] = taxIdCheck['et-EE'];
taxIdCheck['nl-BE'] = taxIdCheck['fr-BE']; // Regexes for locales where characters should be omitted before checking format

var allsymbols = /[-\\\/!@#$%\^&\*\(\)\+\=\[\]]+/g;
var sanitizeRegexes = {
  'de-AT': allsymbols,
  'de-DE': /[\/\\]/g,
  'fr-BE': allsymbols
}; // sanitizeRegexes locale aliases

sanitizeRegexes['nl-BE'] = sanitizeRegexes['fr-BE'];
/*
 * Validator function
 * Return true if the passed string is a valid tax identification number
 * for the specified locale.
 * Throw an error exception if the locale is not supported.
 */

function isTaxID(str) {
  var locale = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'en-US';
  (0, _assertString.default)(str); // Copy TIN to avoid replacement if sanitized

  var strcopy = str.slice(0);

  if (locale in taxIdFormat) {
    if (locale in sanitizeRegexes) {
      strcopy = strcopy.replace(sanitizeRegexes[locale], '');
    }

    if (!taxIdFormat[locale].test(strcopy)) {
      return false;
    }

    if (locale in taxIdCheck) {
      return taxIdCheck[locale](strcopy);
    } // Fallthrough; not all locales have algorithmic checks


    return true;
  }

  throw new Error("Invalid locale '".concat(locale, "'"));
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 4922:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isURL;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _isFQDN = _interopRequireDefault(__webpack_require__(8139));

var _isIP = _interopRequireDefault(__webpack_require__(7962));

var _merge = _interopRequireDefault(__webpack_require__(1778));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/*
options for isURL method

require_protocol - if set as true isURL will return false if protocol is not present in the URL
require_valid_protocol - isURL will check if the URL's protocol is present in the protocols option
protocols - valid protocols can be modified with this option
require_host - if set as false isURL will not check if host is present in the URL
require_port - if set as true isURL will check if port is present in the URL
allow_protocol_relative_urls - if set as true protocol relative URLs will be allowed
validate_length - if set as false isURL will skip string length validation (IE maximum is 2083)

*/
var default_url_options = {
  protocols: ['http', 'https', 'ftp'],
  require_tld: true,
  require_protocol: false,
  require_host: true,
  require_port: false,
  require_valid_protocol: true,
  allow_underscores: false,
  allow_trailing_dot: false,
  allow_protocol_relative_urls: false,
  validate_length: true
};
var wrapped_ipv6 = /^\[([^\]]+)\](?::([0-9]+))?$/;

function isRegExp(obj) {
  return Object.prototype.toString.call(obj) === '[object RegExp]';
}

function checkHost(host, matches) {
  for (var i = 0; i < matches.length; i++) {
    var match = matches[i];

    if (host === match || isRegExp(match) && match.test(host)) {
      return true;
    }
  }

  return false;
}

function isURL(url, options) {
  (0, _assertString.default)(url);

  if (!url || /[\s<>]/.test(url)) {
    return false;
  }

  if (url.indexOf('mailto:') === 0) {
    return false;
  }

  options = (0, _merge.default)(options, default_url_options);

  if (options.validate_length && url.length >= 2083) {
    return false;
  }

  var protocol, auth, host, hostname, port, port_str, split, ipv6;
  split = url.split('#');
  url = split.shift();
  split = url.split('?');
  url = split.shift();
  split = url.split('://');

  if (split.length > 1) {
    protocol = split.shift().toLowerCase();

    if (options.require_valid_protocol && options.protocols.indexOf(protocol) === -1) {
      return false;
    }
  } else if (options.require_protocol) {
    return false;
  } else if (url.substr(0, 2) === '//') {
    if (!options.allow_protocol_relative_urls) {
      return false;
    }

    split[0] = url.substr(2);
  }

  url = split.join('://');

  if (url === '') {
    return false;
  }

  split = url.split('/');
  url = split.shift();

  if (url === '' && !options.require_host) {
    return true;
  }

  split = url.split('@');

  if (split.length > 1) {
    if (options.disallow_auth) {
      return false;
    }

    auth = split.shift();

    if (auth.indexOf(':') === -1 || auth.indexOf(':') >= 0 && auth.split(':').length > 2) {
      return false;
    }
  }

  hostname = split.join('@');
  port_str = null;
  ipv6 = null;
  var ipv6_match = hostname.match(wrapped_ipv6);

  if (ipv6_match) {
    host = '';
    ipv6 = ipv6_match[1];
    port_str = ipv6_match[2] || null;
  } else {
    split = hostname.split(':');
    host = split.shift();

    if (split.length) {
      port_str = split.join(':');
    }
  }

  if (port_str !== null) {
    port = parseInt(port_str, 10);

    if (!/^[0-9]+$/.test(port_str) || port <= 0 || port > 65535) {
      return false;
    }
  } else if (options.require_port) {
    return false;
  }

  if (!(0, _isIP.default)(host) && !(0, _isFQDN.default)(host, options) && (!ipv6 || !(0, _isIP.default)(ipv6, 6))) {
    return false;
  }

  host = host || ipv6;

  if (options.host_whitelist && !checkHost(host, options.host_whitelist)) {
    return false;
  }

  if (options.host_blacklist && checkHost(host, options.host_blacklist)) {
    return false;
  }

  return true;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 6368:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isUUID;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var uuid = {
  3: /^[0-9A-F]{8}-[0-9A-F]{4}-3[0-9A-F]{3}-[0-9A-F]{4}-[0-9A-F]{12}$/i,
  4: /^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i,
  5: /^[0-9A-F]{8}-[0-9A-F]{4}-5[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i,
  all: /^[0-9A-F]{8}-[0-9A-F]{4}-[0-9A-F]{4}-[0-9A-F]{4}-[0-9A-F]{12}$/i
};

function isUUID(str) {
  var version = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'all';
  (0, _assertString.default)(str);
  var pattern = uuid[version];
  return pattern && pattern.test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 1776:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isUppercase;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function isUppercase(str) {
  (0, _assertString.default)(str);
  return str === str.toUpperCase();
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 1809:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isVAT;
exports.vatMatchers = void 0;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var vatMatchers = {
  GB: /^GB((\d{3} \d{4} ([0-8][0-9]|9[0-6]))|(\d{9} \d{3})|(((GD[0-4])|(HA[5-9]))[0-9]{2}))$/
};
exports.vatMatchers = vatMatchers;

function isVAT(str, countryCode) {
  (0, _assertString.default)(str);
  (0, _assertString.default)(countryCode);

  if (countryCode in vatMatchers) {
    return vatMatchers[countryCode].test(str);
  }

  throw new Error("Invalid country code: '".concat(countryCode, "'"));
}

/***/ }),

/***/ 8831:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isVariableWidth;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _isFullWidth = __webpack_require__(749);

var _isHalfWidth = __webpack_require__(8408);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function isVariableWidth(str) {
  (0, _assertString.default)(str);
  return _isFullWidth.fullWidth.test(str) && _isHalfWidth.halfWidth.test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 9293:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = isWhitelisted;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function isWhitelisted(str, chars) {
  (0, _assertString.default)(str);

  for (var i = str.length - 1; i >= 0; i--) {
    if (chars.indexOf(str[i]) === -1) {
      return false;
    }
  }

  return true;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 5904:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = ltrim;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function ltrim(str, chars) {
  (0, _assertString.default)(str); // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Regular_Expressions#Escaping

  var pattern = chars ? new RegExp("^[".concat(chars.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), "]+"), 'g') : /^\s+/g;
  return str.replace(pattern, '');
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 401:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = matches;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function matches(str, pattern, modifiers) {
  (0, _assertString.default)(str);

  if (Object.prototype.toString.call(pattern) !== '[object RegExp]') {
    pattern = new RegExp(pattern, modifiers);
  }

  return pattern.test(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 3517:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = normalizeEmail;

var _merge = _interopRequireDefault(__webpack_require__(1778));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var default_normalize_email_options = {
  // The following options apply to all email addresses
  // Lowercases the local part of the email address.
  // Please note this may violate RFC 5321 as per http://stackoverflow.com/a/9808332/192024).
  // The domain is always lowercased, as per RFC 1035
  all_lowercase: true,
  // The following conversions are specific to GMail
  // Lowercases the local part of the GMail address (known to be case-insensitive)
  gmail_lowercase: true,
  // Removes dots from the local part of the email address, as that's ignored by GMail
  gmail_remove_dots: true,
  // Removes the subaddress (e.g. "+foo") from the email address
  gmail_remove_subaddress: true,
  // Conversts the googlemail.com domain to gmail.com
  gmail_convert_googlemaildotcom: true,
  // The following conversions are specific to Outlook.com / Windows Live / Hotmail
  // Lowercases the local part of the Outlook.com address (known to be case-insensitive)
  outlookdotcom_lowercase: true,
  // Removes the subaddress (e.g. "+foo") from the email address
  outlookdotcom_remove_subaddress: true,
  // The following conversions are specific to Yahoo
  // Lowercases the local part of the Yahoo address (known to be case-insensitive)
  yahoo_lowercase: true,
  // Removes the subaddress (e.g. "-foo") from the email address
  yahoo_remove_subaddress: true,
  // The following conversions are specific to Yandex
  // Lowercases the local part of the Yandex address (known to be case-insensitive)
  yandex_lowercase: true,
  // The following conversions are specific to iCloud
  // Lowercases the local part of the iCloud address (known to be case-insensitive)
  icloud_lowercase: true,
  // Removes the subaddress (e.g. "+foo") from the email address
  icloud_remove_subaddress: true
}; // List of domains used by iCloud

var icloud_domains = ['icloud.com', 'me.com']; // List of domains used by Outlook.com and its predecessors
// This list is likely incomplete.
// Partial reference:
// https://blogs.office.com/2013/04/17/outlook-com-gets-two-step-verification-sign-in-by-alias-and-new-international-domains/

var outlookdotcom_domains = ['hotmail.at', 'hotmail.be', 'hotmail.ca', 'hotmail.cl', 'hotmail.co.il', 'hotmail.co.nz', 'hotmail.co.th', 'hotmail.co.uk', 'hotmail.com', 'hotmail.com.ar', 'hotmail.com.au', 'hotmail.com.br', 'hotmail.com.gr', 'hotmail.com.mx', 'hotmail.com.pe', 'hotmail.com.tr', 'hotmail.com.vn', 'hotmail.cz', 'hotmail.de', 'hotmail.dk', 'hotmail.es', 'hotmail.fr', 'hotmail.hu', 'hotmail.id', 'hotmail.ie', 'hotmail.in', 'hotmail.it', 'hotmail.jp', 'hotmail.kr', 'hotmail.lv', 'hotmail.my', 'hotmail.ph', 'hotmail.pt', 'hotmail.sa', 'hotmail.sg', 'hotmail.sk', 'live.be', 'live.co.uk', 'live.com', 'live.com.ar', 'live.com.mx', 'live.de', 'live.es', 'live.eu', 'live.fr', 'live.it', 'live.nl', 'msn.com', 'outlook.at', 'outlook.be', 'outlook.cl', 'outlook.co.il', 'outlook.co.nz', 'outlook.co.th', 'outlook.com', 'outlook.com.ar', 'outlook.com.au', 'outlook.com.br', 'outlook.com.gr', 'outlook.com.pe', 'outlook.com.tr', 'outlook.com.vn', 'outlook.cz', 'outlook.de', 'outlook.dk', 'outlook.es', 'outlook.fr', 'outlook.hu', 'outlook.id', 'outlook.ie', 'outlook.in', 'outlook.it', 'outlook.jp', 'outlook.kr', 'outlook.lv', 'outlook.my', 'outlook.ph', 'outlook.pt', 'outlook.sa', 'outlook.sg', 'outlook.sk', 'passport.com']; // List of domains used by Yahoo Mail
// This list is likely incomplete

var yahoo_domains = ['rocketmail.com', 'yahoo.ca', 'yahoo.co.uk', 'yahoo.com', 'yahoo.de', 'yahoo.fr', 'yahoo.in', 'yahoo.it', 'ymail.com']; // List of domains used by yandex.ru

var yandex_domains = ['yandex.ru', 'yandex.ua', 'yandex.kz', 'yandex.com', 'yandex.by', 'ya.ru']; // replace single dots, but not multiple consecutive dots

function dotsReplacer(match) {
  if (match.length > 1) {
    return match;
  }

  return '';
}

function normalizeEmail(email, options) {
  options = (0, _merge.default)(options, default_normalize_email_options);
  var raw_parts = email.split('@');
  var domain = raw_parts.pop();
  var user = raw_parts.join('@');
  var parts = [user, domain]; // The domain is always lowercased, as it's case-insensitive per RFC 1035

  parts[1] = parts[1].toLowerCase();

  if (parts[1] === 'gmail.com' || parts[1] === 'googlemail.com') {
    // Address is GMail
    if (options.gmail_remove_subaddress) {
      parts[0] = parts[0].split('+')[0];
    }

    if (options.gmail_remove_dots) {
      // this does not replace consecutive dots like example..email@gmail.com
      parts[0] = parts[0].replace(/\.+/g, dotsReplacer);
    }

    if (!parts[0].length) {
      return false;
    }

    if (options.all_lowercase || options.gmail_lowercase) {
      parts[0] = parts[0].toLowerCase();
    }

    parts[1] = options.gmail_convert_googlemaildotcom ? 'gmail.com' : parts[1];
  } else if (icloud_domains.indexOf(parts[1]) >= 0) {
    // Address is iCloud
    if (options.icloud_remove_subaddress) {
      parts[0] = parts[0].split('+')[0];
    }

    if (!parts[0].length) {
      return false;
    }

    if (options.all_lowercase || options.icloud_lowercase) {
      parts[0] = parts[0].toLowerCase();
    }
  } else if (outlookdotcom_domains.indexOf(parts[1]) >= 0) {
    // Address is Outlook.com
    if (options.outlookdotcom_remove_subaddress) {
      parts[0] = parts[0].split('+')[0];
    }

    if (!parts[0].length) {
      return false;
    }

    if (options.all_lowercase || options.outlookdotcom_lowercase) {
      parts[0] = parts[0].toLowerCase();
    }
  } else if (yahoo_domains.indexOf(parts[1]) >= 0) {
    // Address is Yahoo
    if (options.yahoo_remove_subaddress) {
      var components = parts[0].split('-');
      parts[0] = components.length > 1 ? components.slice(0, -1).join('-') : components[0];
    }

    if (!parts[0].length) {
      return false;
    }

    if (options.all_lowercase || options.yahoo_lowercase) {
      parts[0] = parts[0].toLowerCase();
    }
  } else if (yandex_domains.indexOf(parts[1]) >= 0) {
    if (options.all_lowercase || options.yandex_lowercase) {
      parts[0] = parts[0].toLowerCase();
    }

    parts[1] = 'yandex.ru'; // all yandex domains are equal, 1st preferred
  } else if (options.all_lowercase) {
    // Any other address
    parts[0] = parts[0].toLowerCase();
  }

  return parts.join('@');
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 1733:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = rtrim;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function rtrim(str, chars) {
  (0, _assertString.default)(str); // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Regular_Expressions#Escaping

  var pattern = chars ? new RegExp("[".concat(chars.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), "]+$"), 'g') : /\s+$/g;
  return str.replace(pattern, '');
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 4559:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = stripLow;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

var _blacklist = _interopRequireDefault(__webpack_require__(7902));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function stripLow(str, keep_new_lines) {
  (0, _assertString.default)(str);
  var chars = keep_new_lines ? '\\x00-\\x09\\x0B\\x0C\\x0E-\\x1F\\x7F' : '\\x00-\\x1F\\x7F';
  return (0, _blacklist.default)(str, chars);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 8676:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = toBoolean;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function toBoolean(str, strict) {
  (0, _assertString.default)(str);

  if (strict) {
    return str === '1' || /^true$/i.test(str);
  }

  return str !== '0' && !/^false$/i.test(str) && str !== '';
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 7499:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = toDate;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function toDate(date) {
  (0, _assertString.default)(date);
  date = Date.parse(date);
  return !isNaN(date) ? new Date(date) : null;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 6840:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = toFloat;

var _isFloat = _interopRequireDefault(__webpack_require__(3612));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function toFloat(str) {
  if (!(0, _isFloat.default)(str)) return NaN;
  return parseFloat(str);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 7155:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = toInt;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function toInt(str, radix) {
  (0, _assertString.default)(str);
  return parseInt(str, radix || 10);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 3465:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = trim;

var _rtrim = _interopRequireDefault(__webpack_require__(1733));

var _ltrim = _interopRequireDefault(__webpack_require__(5904));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function trim(str, chars) {
  return (0, _rtrim.default)((0, _ltrim.default)(str, chars), chars);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 3991:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = unescape;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function unescape(str) {
  (0, _assertString.default)(str);
  return str.replace(/&amp;/g, '&').replace(/&quot;/g, '"').replace(/&#x27;/g, "'").replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&#x2F;/g, '/').replace(/&#x5C;/g, '\\').replace(/&#96;/g, '`');
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 257:
/***/ ((__unused_webpack_module, exports) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.iso7064Check = iso7064Check;
exports.luhnCheck = luhnCheck;
exports.reverseMultiplyAndSum = reverseMultiplyAndSum;
exports.verhoeffCheck = verhoeffCheck;

/**
 * Algorithmic validation functions
 * May be used as is or implemented in the workflow of other validators.
 */

/*
 * ISO 7064 validation function
 * Called with a string of numbers (incl. check digit)
 * to validate according to ISO 7064 (MOD 11, 10).
 */
function iso7064Check(str) {
  var checkvalue = 10;

  for (var i = 0; i < str.length - 1; i++) {
    checkvalue = (parseInt(str[i], 10) + checkvalue) % 10 === 0 ? 10 * 2 % 11 : (parseInt(str[i], 10) + checkvalue) % 10 * 2 % 11;
  }

  checkvalue = checkvalue === 1 ? 0 : 11 - checkvalue;
  return checkvalue === parseInt(str[10], 10);
}
/*
 * Luhn (mod 10) validation function
 * Called with a string of numbers (incl. check digit)
 * to validate according to the Luhn algorithm.
 */


function luhnCheck(str) {
  var checksum = 0;
  var second = false;

  for (var i = str.length - 1; i >= 0; i--) {
    if (second) {
      var product = parseInt(str[i], 10) * 2;

      if (product > 9) {
        // sum digits of product and add to checksum
        checksum += product.toString().split('').map(function (a) {
          return parseInt(a, 10);
        }).reduce(function (a, b) {
          return a + b;
        }, 0);
      } else {
        checksum += product;
      }
    } else {
      checksum += parseInt(str[i], 10);
    }

    second = !second;
  }

  return checksum % 10 === 0;
}
/*
 * Reverse TIN multiplication and summation helper function
 * Called with an array of single-digit integers and a base multiplier
 * to calculate the sum of the digits multiplied in reverse.
 * Normally used in variations of MOD 11 algorithmic checks.
 */


function reverseMultiplyAndSum(digits, base) {
  var total = 0;

  for (var i = 0; i < digits.length; i++) {
    total += digits[i] * (base - i);
  }

  return total;
}
/*
 * Verhoeff validation helper function
 * Called with a string of numbers
 * to validate according to the Verhoeff algorithm.
 */


function verhoeffCheck(str) {
  var d_table = [[0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [1, 2, 3, 4, 0, 6, 7, 8, 9, 5], [2, 3, 4, 0, 1, 7, 8, 9, 5, 6], [3, 4, 0, 1, 2, 8, 9, 5, 6, 7], [4, 0, 1, 2, 3, 9, 5, 6, 7, 8], [5, 9, 8, 7, 6, 0, 4, 3, 2, 1], [6, 5, 9, 8, 7, 1, 0, 4, 3, 2], [7, 6, 5, 9, 8, 2, 1, 0, 4, 3], [8, 7, 6, 5, 9, 3, 2, 1, 0, 4], [9, 8, 7, 6, 5, 4, 3, 2, 1, 0]];
  var p_table = [[0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [1, 5, 7, 6, 2, 8, 3, 0, 9, 4], [5, 8, 0, 3, 7, 9, 6, 1, 4, 2], [8, 9, 1, 6, 0, 4, 3, 5, 2, 7], [9, 4, 5, 3, 1, 2, 6, 8, 7, 0], [4, 2, 8, 6, 5, 7, 3, 9, 0, 1], [2, 7, 9, 3, 8, 0, 6, 4, 1, 5], [7, 0, 4, 6, 9, 1, 3, 2, 5, 8]]; // Copy (to prevent replacement) and reverse

  var str_copy = str.split('').reverse().join('');
  var checksum = 0;

  for (var i = 0; i < str_copy.length; i++) {
    checksum = d_table[checksum][p_table[i % 8][parseInt(str_copy[i], 10)]];
  }

  return checksum === 0;
}

/***/ }),

/***/ 7359:
/***/ ((module, exports) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = assertString;

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function assertString(input) {
  var isString = typeof input === 'string' || input instanceof String;

  if (!isString) {
    var invalidType = _typeof(input);

    if (input === null) invalidType = 'null';else if (invalidType === 'object') invalidType = input.constructor.name;
    throw new TypeError("Expected a string but received a ".concat(invalidType));
  }
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 2900:
/***/ ((module, exports) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;

var includes = function includes(arr, val) {
  return arr.some(function (arrVal) {
    return val === arrVal;
  });
};

var _default = includes;
exports["default"] = _default;
module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 1778:
/***/ ((module, exports) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = merge;

function merge() {
  var obj = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  var defaults = arguments.length > 1 ? arguments[1] : undefined;

  for (var key in defaults) {
    if (typeof obj[key] === 'undefined') {
      obj[key] = defaults[key];
    }
  }

  return obj;
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 8041:
/***/ ((module, exports) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = multilineRegexp;

/**
 * Build RegExp object from an array
 * of multiple/multi-line regexp parts
 *
 * @param {string[]} parts
 * @param {string} flags
 * @return {object} - RegExp object
 */
function multilineRegexp(parts, flags) {
  var regexpAsStringLiteral = parts.join('');
  return new RegExp(regexpAsStringLiteral, flags);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 1589:
/***/ ((module, exports) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = toString;

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function toString(input) {
  if (_typeof(input) === 'object' && input !== null) {
    if (typeof input.toString === 'function') {
      input = input.toString();
    } else {
      input = '[object Object]';
    }
  } else if (input === null || typeof input === 'undefined' || isNaN(input) && !input.length) {
    input = '';
  }

  return String(input);
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 7224:
/***/ ((module, exports, __webpack_require__) => {

"use strict";


Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = whitelist;

var _assertString = _interopRequireDefault(__webpack_require__(7359));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function whitelist(str, chars) {
  (0, _assertString.default)(str);
  return str.replace(new RegExp("[^".concat(chars, "]+"), 'g'), '');
}

module.exports = exports.default;
module.exports["default"] = exports.default;

/***/ }),

/***/ 2884:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var uppercamelcase = __webpack_require__(3830);
var statusCodes = __webpack_require__(6744);

module.exports = WPError;

function WPError () {
  var self = new Error();

  for (var i = 0; i < arguments.length; i++) {
    process(self, arguments[i]);
  }

  if (typeof Error.captureStackTrace === 'function') {
    Error.captureStackTrace(self, WPError);
  }

  return self;
}

function process ( self, data ) {
  if ( ! data ) { 
    return;
  }
  
  if (typeof data === 'number') {
    setStatusCode( self, data );

  } else {
    // assume it's a plain 'ol Object with some props to copy over
    if ( data.status_code ) {
      setStatusCode( self, data.status_code );
    }

    if ( data.error ) {
      self.name = toName( data.error );
    }

    if ( data.error_description ) {
      self.message = data.error_description;
    }

    var errors = data.errors;
    if ( errors ) {
      var first = errors.length ? errors[0] : errors;
      process( self, first );
    }

    for ( var i in data ) {
      self[i] = data[i];
    }

    if ( self.status && ( data.method || data.path ) ) {
      setStatusCodeMessage( self );
    }
  }
}

function setStatusCode ( self, code ) {
  self.name = toName( statusCodes[ code ] );
  self.status = self.statusCode = code;
  setStatusCodeMessage( self );
}

function setStatusCodeMessage ( self ) {
  var code = self.status;
  var method = self.method;
  var path = self.path;

  var m = code + ' status code';
  var extended = method || path;

  if ( extended ) m += ' for "';
  if ( method ) m += method;
  if ( extended ) m += ' ';
  if ( path ) m += path;
  if ( extended ) m += '"';

  self.message = m;
}

function toName ( str ) {
  return uppercamelcase( String(str).replace(/error$/i, ''), 'error' );
}


/***/ }),

/***/ 9196:
/***/ ((module) => {

"use strict";
module.exports = window["React"];

/***/ }),

/***/ 9818:
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["data"];

/***/ }),

/***/ 7180:
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["deprecated"];

/***/ }),

/***/ 5736:
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["i18n"];

/***/ }),

/***/ 3260:
/***/ (() => {

/* (ignored) */

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
/* harmony import */ var _domain_suggestions__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(6274);
/* harmony import */ var _plans__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(3857);
/* harmony import */ var _site__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(561);



})();

window.EditingToolkit = __webpack_exports__;
/******/ })()
;