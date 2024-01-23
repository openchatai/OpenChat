(function(f){if(typeof exports==="object"&&typeof module!=="undefined"){module.exports=f()}else if(typeof define==="function"&&define.amd){define([],f)}else{var g;if(typeof window!=="undefined"){g=window}else if(typeof global!=="undefined"){g=global}else if(typeof self!=="undefined"){g=self}else{g=this}g.raterJs = f()}})(function(){var define,module,exports;return (function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";

/*! rater-js. [c] 2018 Fredrik Olsson. MIT License */
var css = require('./style.css');

module.exports = function (options) {
  //private fields
  var showToolTip = true;

  if (typeof options.element === "undefined" || options.element === null) {
    throw new Error("element required");
  }

  if (typeof options.showToolTip !== "undefined") {
    showToolTip = !!options.showToolTip;
  }

  if (typeof options.step !== "undefined") {
    if (options.step <= 0 || options.step > 1) {
      throw new Error("step must be a number between 0 and 1");
    }
  }

  var elem = options.element;
  var reverse = options.reverse;
  var stars = options.max || 5;
  var starSize = options.starSize || 16;
  var step = options.step || 1;
  var onHover = options.onHover;
  var onLeave = options.onLeave;
  var rating = null;
  var myRating;
  elem.classList.add("star-rating");
  var div = document.createElement("div");
  div.classList.add("star-value");

  if (reverse) {
    div.classList.add("rtl");
  }

  div.style.backgroundSize = starSize + "px";
  elem.appendChild(div);
  elem.style.width = starSize * stars + "px";
  elem.style.height = starSize + "px";
  elem.style.backgroundSize = starSize + "px";
  var callback = options.rateCallback;
  var disabled = !!options.readOnly;
  var disableText;
  var isRating = false;
  var isBusyText = options.isBusyText;
  var currentRating;
  var ratingText;

  if (typeof options.disableText !== "undefined") {
    disableText = options.disableText;
  } else {
    disableText = "{rating}/{maxRating}";
  }

  if (typeof options.ratingText !== "undefined") {
    ratingText = options.ratingText;
  } else {
    ratingText = "{rating}/{maxRating}";
  }

  if (options.rating) {
    setRating(options.rating);
  } else {
    var dataRating = elem.dataset.rating;

    if (dataRating) {
      setRating(+dataRating);
    }
  }

  if (!rating) {
    elem.querySelector(".star-value").style.width = "0px";
  }

  if (disabled) {
    disable();
  } //private methods


  function onMouseMove(e) {
    onMove(e, false);
  }
  /**
   * Called by eventhandlers when mouse or touch events are triggered
   * @param {MouseEvent} e
   */


  function onMove(e, isTouch) {
    if (disabled === true || isRating === true) {
      return;
    }

    var xCoor = null;
    var percent;
    var width = elem.offsetWidth;
    var parentOffset = elem.getBoundingClientRect();

    if (reverse) {
      if (isTouch) {
        xCoor = e.changedTouches[0].pageX - parentOffset.left;
      } else {
        xCoor = e.pageX - window.scrollX - parentOffset.left;
      }

      var relXRtl = width - xCoor;
      var valueForDivision = width / 100;
      percent = relXRtl / valueForDivision;
    } else {
      if (isTouch) {
        xCoor = e.changedTouches[0].pageX - parentOffset.left;
      } else {
        xCoor = e.offsetX;
      }

      percent = xCoor / width * 100;
    }

    if (percent < 101) {
      if (step === 1) {
        currentRating = Math.ceil(percent / 100 * stars);
      } else {
        var rat = percent / 100 * stars;

        for (var i = 0;; i += step) {
          if (i >= rat) {
            currentRating = i;
            break;
          }
        }
      } //todo: check why this happens and fix


      if (currentRating > stars) {
        currentRating = stars;
      }

      elem.querySelector(".star-value").style.width = currentRating / stars * 100 + "%";

      if (showToolTip) {
        var toolTip = ratingText.replace("{rating}", currentRating);
        toolTip = toolTip.replace("{maxRating}", stars);
        elem.setAttribute("title", toolTip);
      }

      if (typeof onHover === "function") {
        onHover(currentRating, rating);
      }
    }
  }
  /**
   * Called when mouse is released. This function will update the view with the rating.
   * @param {MouseEvent} e
   */


  function onStarOut(e) {
    if (!rating) {
      elem.querySelector(".star-value").style.width = "0%";
      elem.removeAttribute("data-rating");
    } else {
      elem.querySelector(".star-value").style.width = rating / stars * 100 + "%";
      elem.setAttribute("data-rating", rating);
    }

    if (typeof onLeave === "function") {
      onLeave(currentRating, rating);
    }
  }
  /**
   * Called when star is clicked.
   * @param {MouseEvent} e
   */


  function onStarClick(e) {
    if (disabled === true) {
      return;
    }

    if (isRating === true) {
      return;
    }

    if (typeof callback !== "undefined") {
      isRating = true;
      myRating = currentRating;

      if (typeof isBusyText === "undefined") {
        elem.removeAttribute("title");
      } else {
        elem.setAttribute("title", isBusyText);
      }

      elem.classList.add("is-busy");
      callback.call(this, myRating, function () {
        if (disabled === false) {
          elem.removeAttribute("title");
        }

        isRating = false;
        elem.classList.remove("is-busy");
      });
    }
  }
  /**
   * Disables the rater so that it's not possible to click the stars.
   */


  function disable() {
    disabled = true;
    elem.classList.add("disabled");

    if (showToolTip && !!disableText) {
      var toolTip = disableText.replace("{rating}", !!rating ? rating : 0);
      toolTip = toolTip.replace("{maxRating}", stars);
      elem.setAttribute("title", toolTip);
    } else {
      elem.removeAttribute("title");
    }
  }
  /**
   * Enabled the rater so that it's possible to click the stars.
   */


  function enable() {
    disabled = false;
    elem.removeAttribute("title");
    elem.classList.remove("disabled");
  }
  /**
   * Sets the rating
   */


  function setRating(value) {
    if (typeof value === "undefined") {
      throw new Error("Value not set.");
    }

    if (value === null) {
      throw new Error("Value cannot be null.");
    }

    if (typeof value !== "number") {
      throw new Error("Value must be a number.");
    }

    if (value < 0 || value > stars) {
      throw new Error("Value too high. Please set a rating of " + stars + " or below.");
    }

    rating = value;
    elem.querySelector(".star-value").style.width = value / stars * 100 + "%";
    elem.setAttribute("data-rating", value);
  }
  /**
   * Gets the rating
   */


  function getRating() {
    return rating;
  }
  /**
   * Set the rating to a value to inducate it's not rated.
   */


  function clear() {
    rating = null;
    elem.querySelector(".star-value").style.width = "0px";
    elem.removeAttribute("title");
  }
  /**
   * Remove event handlers.
   */


  function dispose() {
    elem.removeEventListener("mousemove", onMouseMove);
    elem.removeEventListener("mouseleave", onStarOut);
    elem.removeEventListener("click", onStarClick);
    elem.removeEventListener("touchmove", handleMove, false);
    elem.removeEventListener("touchstart", handleStart, false);
    elem.removeEventListener("touchend", handleEnd, false);
    elem.removeEventListener("touchcancel", handleCancel, false);
  }

  elem.addEventListener("mousemove", onMouseMove);
  elem.addEventListener("mouseleave", onStarOut);
  var module = {
    setRating: setRating,
    getRating: getRating,
    disable: disable,
    enable: enable,
    clear: clear,
    dispose: dispose,

    get element() {
      return elem;
    }

  };
  /**
  * Handles touchmove event.
  * @param {TouchEvent} e
  */

  function handleMove(e) {
    e.preventDefault();
    onMove(e, true);
  }
  /**
   * Handles touchstart event.
   * @param {TouchEvent} e 
   */


  function handleStart(e) {
    e.preventDefault();
    onMove(e, true);
  }
  /**
   * Handles touchend event.
   * @param {TouchEvent} e 
   */


  function handleEnd(evt) {
    evt.preventDefault();
    onMove(evt, true);
    onStarClick.call(module);
  }
  /**
   * Handles touchend event.
   * @param {TouchEvent} e 
   */


  function handleCancel(e) {
    e.preventDefault();
    onStarOut(e);
  }

  elem.addEventListener("click", onStarClick.bind(module));
  elem.addEventListener("touchmove", handleMove, false);
  elem.addEventListener("touchstart", handleStart, false);
  elem.addEventListener("touchend", handleEnd, false);
  elem.addEventListener("touchcancel", handleCancel, false);
  return module;
};

},{"./style.css":2}],2:[function(require,module,exports){
var css = ".star-rating {\n  width: 0;\n  position: relative;\n  display: inline-block;\n  background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDguOSIgaGVpZ2h0PSIxMDMuNiIgdmlld0JveD0iMCAwIDEwOC45IDEwMy42Ij48ZGVmcz48c3R5bGU+LmNscy0xe2ZpbGw6I2UzZTZlNjt9PC9zdHlsZT48L2RlZnM+PHRpdGxlPnN0YXJfMDwvdGl0bGU+PGcgaWQ9IkxheWVyXzIiIGRhdGEtbmFtZT0iTGF5ZXIgMiI+PGcgaWQ9IkxheWVyXzEtMiIgZGF0YS1uYW1lPSJMYXllciAxIj48cG9seWdvbiBjbGFzcz0iY2xzLTEiIHBvaW50cz0iMTA4LjkgMzkuNiA3MS4zIDM0LjEgNTQuNCAwIDM3LjYgMzQuMSAwIDM5LjYgMjcuMiA2Ni4xIDIwLjggMTAzLjYgNTQuNCA4NS45IDg4LjEgMTAzLjYgODEuNyA2Ni4xIDEwOC45IDM5LjYiLz48L2c+PC9nPjwvc3ZnPg0K);\n  background-position: 0 0;\n  background-repeat: repeat-x;\n  cursor: pointer;\n}\n.star-rating .star-value {\n  position: absolute;\n  height: 100%;\n  width: 100%;\n  background: url('data:image/svg+xml;base64,PHN2Zw0KCXhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwOC45IiBoZWlnaHQ9IjEwMy42IiB2aWV3Qm94PSIwIDAgMTA4LjkgMTAzLjYiPg0KCTxkZWZzPg0KCQk8c3R5bGU+LmNscy0xe2ZpbGw6I2YxYzk0Nzt9PC9zdHlsZT4NCgk8L2RlZnM+DQoJPHRpdGxlPnN0YXIxPC90aXRsZT4NCgk8ZyBpZD0iTGF5ZXJfMiIgZGF0YS1uYW1lPSJMYXllciAyIj4NCgkJPGcgaWQ9IkxheWVyXzEtMiIgZGF0YS1uYW1lPSJMYXllciAxIj4NCgkJCTxwb2x5Z29uIGNsYXNzPSJjbHMtMSIgcG9pbnRzPSI1NC40IDAgNzEuMyAzNC4xIDEwOC45IDM5LjYgODEuNyA2Ni4xIDg4LjEgMTAzLjYgNTQuNCA4NS45IDIwLjggMTAzLjYgMjcuMiA2Ni4xIDAgMzkuNiAzNy42IDM0LjEgNTQuNCAwIi8+DQoJCTwvZz4NCgk8L2c+DQo8L3N2Zz4NCg==');\n  background-repeat: repeat-x;\n}\n.star-rating.disabled {\n  cursor: default;\n}\n.star-rating.is-busy {\n  cursor: wait;\n}\n.star-rating .star-value.rtl {\n  -moz-transform: scaleX(-1);\n  -o-transform: scaleX(-1);\n  -webkit-transform: scaleX(-1);\n  transform: scaleX(-1);\n  filter: FlipH;\n  -ms-filter: \"FlipH\";\n  right: 0;\n  left: auto;\n}\n"; (require("browserify-css").createStyle(css, { "href": "lib\\style.css" }, { "insertAt": "bottom" })); module.exports = css;
},{"browserify-css":3}],3:[function(require,module,exports){
'use strict';
// For more information about browser field, check out the browser field at https://github.com/substack/browserify-handbook#browser-field.

var styleElementsInsertedAtTop = [];

var insertStyleElement = function(styleElement, options) {
    var head = document.head || document.getElementsByTagName('head')[0];
    var lastStyleElementInsertedAtTop = styleElementsInsertedAtTop[styleElementsInsertedAtTop.length - 1];

    options = options || {};
    options.insertAt = options.insertAt || 'bottom';

    if (options.insertAt === 'top') {
        if (!lastStyleElementInsertedAtTop) {
            head.insertBefore(styleElement, head.firstChild);
        } else if (lastStyleElementInsertedAtTop.nextSibling) {
            head.insertBefore(styleElement, lastStyleElementInsertedAtTop.nextSibling);
        } else {
            head.appendChild(styleElement);
        }
        styleElementsInsertedAtTop.push(styleElement);
    } else if (options.insertAt === 'bottom') {
        head.appendChild(styleElement);
    } else {
        throw new Error('Invalid value for parameter \'insertAt\'. Must be \'top\' or \'bottom\'.');
    }
};

module.exports = {
    // Create a <link> tag with optional data attributes
    createLink: function(href, attributes) {
        var head = document.head || document.getElementsByTagName('head')[0];
        var link = document.createElement('link');

        link.href = href;
        link.rel = 'stylesheet';

        for (var key in attributes) {
            if ( ! attributes.hasOwnProperty(key)) {
                continue;
            }
            var value = attributes[key];
            link.setAttribute('data-' + key, value);
        }

        head.appendChild(link);
    },
    // Create a <style> tag with optional data attributes
    createStyle: function(cssText, attributes, extraOptions) {
        extraOptions = extraOptions || {};

        var style = document.createElement('style');
        style.type = 'text/css';

        for (var key in attributes) {
            if ( ! attributes.hasOwnProperty(key)) {
                continue;
            }
            var value = attributes[key];
            style.setAttribute('data-' + key, value);
        }

        if (style.sheet) { // for jsdom and IE9+
            style.innerHTML = cssText;
            style.sheet.cssText = cssText;
            insertStyleElement(style, { insertAt: extraOptions.insertAt });
        } else if (style.styleSheet) { // for IE8 and below
            insertStyleElement(style, { insertAt: extraOptions.insertAt });
            style.styleSheet.cssText = cssText;
        } else { // for Chrome, Firefox, and Safari
            style.appendChild(document.createTextNode(cssText));
            insertStyleElement(style, { insertAt: extraOptions.insertAt });
        }
    }
};

},{}]},{},[1])(1)
});
