(globalThis["webpackChunkwebpack"] = globalThis["webpackChunkwebpack"] || []).push([[583],{

/***/ 56989:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Connection": () => (/* binding */ Connection),
/* harmony export */   "buildConnectionForCheckingAvailability": () => (/* binding */ buildConnectionForCheckingAvailability),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(56666);
/* harmony import */ var debug__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(34386);
/* harmony import */ var debug__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(debug__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var socket_io_client__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(17768);
/* harmony import */ var socket_io_client__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(socket_io_client__WEBPACK_IMPORTED_MODULE_1__);



const debug = debug__WEBPACK_IMPORTED_MODULE_0___default()('calypso:happychat:connection');

const buildConnection = socket => typeof socket === 'string' ? new (socket_io_client__WEBPACK_IMPORTED_MODULE_1___default())(socket, {
  // force websocket connection since we no longer have sticky connections server side.
  transports: ['websocket']
}) // If socket is an URL, connect to server.
: socket; // If socket is not an url, use it directly. Useful for testing.
//The second one is an identity function, used in 'use-happychat-available' hook


class Connection {
  constructor() {
    let props = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    let closeAfterAccept = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_2__/* ["default"] */ .Z)(this, "receiveAccept", void 0);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_2__/* ["default"] */ .Z)(this, "receiveConnect", void 0);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_2__/* ["default"] */ .Z)(this, "receiveDisconnect", void 0);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_2__/* ["default"] */ .Z)(this, "receiveError", void 0);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_2__/* ["default"] */ .Z)(this, "receiveInit", void 0);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_2__/* ["default"] */ .Z)(this, "receiveLocalizedSupport", void 0);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_2__/* ["default"] */ .Z)(this, "receiveMessage", void 0);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_2__/* ["default"] */ .Z)(this, "receiveHappychatEnv", void 0);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_2__/* ["default"] */ .Z)(this, "receiveMessageOptimistic", void 0);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_2__/* ["default"] */ .Z)(this, "receiveMessageUpdate", void 0);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_2__/* ["default"] */ .Z)(this, "receiveReconnecting", void 0);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_2__/* ["default"] */ .Z)(this, "receiveStatus", void 0);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_2__/* ["default"] */ .Z)(this, "receiveToken", void 0);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_2__/* ["default"] */ .Z)(this, "receiveUnauthorized", void 0);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_2__/* ["default"] */ .Z)(this, "requestTranscript", void 0);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_2__/* ["default"] */ .Z)(this, "closeAfterAccept", void 0);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_2__/* ["default"] */ .Z)(this, "dispatch", void 0);

    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_2__/* ["default"] */ .Z)(this, "openSocket", void 0);

    Object.assign(this, props);
    this.closeAfterAccept = closeAfterAccept;
  }
  /**
   * Init the SocketIO connection: check user authorization and bind socket events
   *
   * @param originalDispatch Redux dispatch function
   * @param auth Authentication promise, will return the user info upon fulfillment
   * @returns Fulfilled (returns the opened socket)
   *                   	 or rejected (returns an error message)
   */


  init(originalDispatch, auth) {
    if (this.openSocket) {
      debug('socket is already connected');
      return this.openSocket;
    }

    const dispatch = action => {
      if (action) {
        return originalDispatch(action);
      }
    };

    this.dispatch = dispatch;
    this.openSocket = new Promise((resolve, reject) => {
      auth.then(_ref => {
        let {
          url,
          user: {
            signer_user_id,
            jwt,
            groups,
            skills,
            geoLocation
          }
        } = _ref;

        // not so clean way to check if the happychat URL is staging or prod one.
        if (typeof url === 'string') {
          if (url.includes('staging')) {
            var _this$receiveHappycha;

            dispatch((_this$receiveHappycha = this.receiveHappychatEnv) === null || _this$receiveHappycha === void 0 ? void 0 : _this$receiveHappycha.call(this, 'staging'));
          } else {
            var _this$receiveHappycha2;

            dispatch((_this$receiveHappycha2 = this.receiveHappychatEnv) === null || _this$receiveHappycha2 === void 0 ? void 0 : _this$receiveHappycha2.call(this, 'production'));
          }
        }

        const socket = buildConnection(url);
        return socket.once('connect', () => {
          var _this$receiveConnect;

          return dispatch((_this$receiveConnect = this.receiveConnect) === null || _this$receiveConnect === void 0 ? void 0 : _this$receiveConnect.call(this));
        }).on('token', handler => {
          var _this$receiveToken;

          dispatch((_this$receiveToken = this.receiveToken) === null || _this$receiveToken === void 0 ? void 0 : _this$receiveToken.call(this));
          handler({
            signer_user_id,
            jwt,
            groups,
            skills
          });
        }).on('init', () => {
          var _this$receiveInit, _this$requestTranscri;

          dispatch((_this$receiveInit = this.receiveInit) === null || _this$receiveInit === void 0 ? void 0 : _this$receiveInit.call(this, {
            signer_user_id,
            groups,
            skills,
            geoLocation
          }));
          dispatch((_this$requestTranscri = this.requestTranscript) === null || _this$requestTranscri === void 0 ? void 0 : _this$requestTranscri.call(this));
          resolve(socket);
        }).on('unauthorized', () => {
          var _this$receiveUnauthor;

          socket.close();
          dispatch((_this$receiveUnauthor = this.receiveUnauthorized) === null || _this$receiveUnauthor === void 0 ? void 0 : _this$receiveUnauthor.call(this, 'User is not authorized'));
          reject('user is not authorized');
        }).on('disconnect', reason => {
          var _this$receiveDisconne;

          return dispatch((_this$receiveDisconne = this.receiveDisconnect) === null || _this$receiveDisconne === void 0 ? void 0 : _this$receiveDisconne.call(this, reason));
        }).on('reconnecting', () => {
          var _this$receiveReconnec;

          return dispatch((_this$receiveReconnec = this.receiveReconnecting) === null || _this$receiveReconnec === void 0 ? void 0 : _this$receiveReconnec.call(this));
        }).on('status', status => {
          var _this$receiveStatus;

          return dispatch((_this$receiveStatus = this.receiveStatus) === null || _this$receiveStatus === void 0 ? void 0 : _this$receiveStatus.call(this, status));
        }).on('accept', accept => {
          var _this$receiveAccept;

          dispatch((_this$receiveAccept = this.receiveAccept) === null || _this$receiveAccept === void 0 ? void 0 : _this$receiveAccept.call(this, accept));

          if (this.closeAfterAccept) {
            socket.close();
          }
        }).on('localized-support', accept => {
          var _this$receiveLocalize;

          return dispatch((_this$receiveLocalize = this.receiveLocalizedSupport) === null || _this$receiveLocalize === void 0 ? void 0 : _this$receiveLocalize.call(this, accept));
        }).on('message', message => {
          var _this$receiveMessage;

          return dispatch((_this$receiveMessage = this.receiveMessage) === null || _this$receiveMessage === void 0 ? void 0 : _this$receiveMessage.call(this, message));
        }).on('message.optimistic', message => {
          var _this$receiveMessageO;

          return dispatch((_this$receiveMessageO = this.receiveMessageOptimistic) === null || _this$receiveMessageO === void 0 ? void 0 : _this$receiveMessageO.call(this, message));
        }).on('message.update', message => {
          var _this$receiveMessageU;

          return dispatch((_this$receiveMessageU = this.receiveMessageUpdate) === null || _this$receiveMessageU === void 0 ? void 0 : _this$receiveMessageU.call(this, message));
        }).on('reconnect_attempt', () => {
          socket.io.opts.transports = ['polling', 'websocket'];
        });
      }).catch(e => reject(e));
    });
    return this.openSocket;
  }
  /**
   * Given a Redux action, emits a SocketIO event.
   *
   * @param  {Object} action A Redux action with props
   *                  	{
   *                  		event: SocketIO event name,
   *                  		payload: contents to be sent,
   *                  		error: message to be shown should the event fails to be sent,
   *                  	}
   * @returns {Promise|undefined} Fulfilled (returns nothing) or rejected
   *                              (returns an error message)
   */


  send(action) {
    if (!this.openSocket) {
      return;
    }

    return this.openSocket.then(socket => socket.emit(action.event, action.payload), e => {
      var _this$dispatch, _this$receiveError;

      (_this$dispatch = this.dispatch) === null || _this$dispatch === void 0 ? void 0 : _this$dispatch.call(this, (_this$receiveError = this.receiveError) === null || _this$receiveError === void 0 ? void 0 : _this$receiveError.call(this, 'failed to send ' + action.event + ': ' + e)); // so we can relay the error message, for testing purposes

      return Promise.reject(e);
    });
  }
  /**
   *
   * Given a Redux action and a timeout, emits a SocketIO event that request
   * some info to the Happychat server.
   *
   * The request can have three states, and will dispatch an action accordingly:
   *
   * - request was succesful: would dispatch action.callback
   * - request was unsucessful: would dispatch receiveError
   *
   * @param  {Object} action A Redux action with props
   *                  	{
   *                  		event: SocketIO event name,
   *                  		payload: contents to be sent,
   *                  		callback: a Redux action creator
   *                  	}
   * @param  {number} timeout How long (in milliseconds) has the server to respond
   * @returns {Promise|undefined} Fulfilled (returns the transcript response)
   *                              or rejected (returns an error message)
   */


  request(action, timeout) {
    if (!this.openSocket) {
      return;
    }

    return this.openSocket.then(socket => {
      const promiseRace = Promise.race([new Promise((resolve, reject) => {
        socket.emit(action.event, action.payload, (e, result) => {
          if (e) {
            return reject(new Error(e)); // request failed
          }

          return resolve(result); // request succesful
        });
      }), new Promise((resolve, reject) => setTimeout(() => {
        return reject(new Error('timeout')); // request timeout
      }, timeout))]); // dispatch the request state upon promise race resolution

      promiseRace.then(result => {
        var _this$dispatch2, _action$callback;

        return (_this$dispatch2 = this.dispatch) === null || _this$dispatch2 === void 0 ? void 0 : _this$dispatch2.call(this, (_action$callback = action.callback) === null || _action$callback === void 0 ? void 0 : _action$callback.call(action, result));
      }, e => {
        if (e.message !== 'timeout') {
          var _this$dispatch3, _this$receiveError2;

          (_this$dispatch3 = this.dispatch) === null || _this$dispatch3 === void 0 ? void 0 : _this$dispatch3.call(this, (_this$receiveError2 = this.receiveError) === null || _this$receiveError2 === void 0 ? void 0 : _this$receiveError2.call(this, action.event + ' request failed: ' + e.message));
        }
      });
      return promiseRace;
    }, e => {
      var _this$dispatch4, _this$receiveError3;

      (_this$dispatch4 = this.dispatch) === null || _this$dispatch4 === void 0 ? void 0 : _this$dispatch4.call(this, (_this$receiveError3 = this.receiveError) === null || _this$receiveError3 === void 0 ? void 0 : _this$receiveError3.call(this, 'failed to send ' + action.event + ': ' + e)); // so we can relay the error message, for testing purposes

      return Promise.reject(e);
    });
  }

} // Used by the Help Center, it closes the socket after receiving 'accept' or 'unauthorized'

const buildConnectionForCheckingAvailability = connectionProps => new Connection(connectionProps, false);
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (connectionProps => new Connection(connectionProps));

/***/ }),

/***/ 18864:
/***/ (() => {

/* (ignored) */

/***/ })

}]);