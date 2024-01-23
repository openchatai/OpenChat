/*! rater-js. [c] 2018 Fredrik Olsson. MIT License */

let css = require('./style.css'); 

module.exports = function(options) {

	//private fields
	let showToolTip = true; 

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
	let elem = options.element; 
	let reverse = options.reverse;
	let stars = options.max || 5; 
	let starSize = options.starSize || 16; 
	let step = options.step || 1; 
	let onHover = options.onHover; 
	let onLeave = options.onLeave; 
	let rating = null; 
	let myRating; 
	elem.classList.add("star-rating"); 
	let div = document.createElement("div"); 
	div.classList.add("star-value"); 
	if(reverse) {
		div.classList.add("rtl");
	}
	div.style.backgroundSize = starSize + "px"; 
	elem.appendChild(div); 
	elem.style.width = starSize * stars + "px"; 
	elem.style.height = starSize + "px"; 
	elem.style.backgroundSize = starSize + "px"; 
	let callback = options.rateCallback; 
	let disabled =  !!options.readOnly; 
	let disableText; 
	let isRating = false; 
	let isBusyText = options.isBusyText; 
	let currentRating; 
	let ratingText; 
	
	if (typeof options.disableText !== "undefined") {
		disableText = options.disableText; 
	}else {
		disableText = "{rating}/{maxRating}"; 
	}

	if (typeof options.ratingText !== "undefined") {
		ratingText = options.ratingText; 
	}else {
		ratingText = "{rating}/{maxRating}"; 
	}
	
	if (options.rating) {
		setRating(options.rating); 
	}else {
		var dataRating = elem.dataset.rating; 

		if (dataRating) {
			setRating( + dataRating); 
		}
	}

	if ( ! rating) {
		elem.querySelector(".star-value").style.width = "0px"; 
	}

	if (disabled) {
		disable(); 
	}

	//private methods
	function onMouseMove(e) {
		onMove(e,false);
	}

	/**
	 * Called by eventhandlers when mouse or touch events are triggered
	 * @param {MouseEvent} e
	 */
	function onMove(e, isTouch) {

		if (disabled === true || isRating === true) {
			return; 
		}
		
		let xCoor = null;
		let percent;
		let width = elem.offsetWidth;
		let parentOffset = elem.getBoundingClientRect();

		if (reverse) {
			if(isTouch) {
				xCoor = e.changedTouches[0].pageX - parentOffset.left;
			} else {
				xCoor = e.pageX - window.scrollX - parentOffset.left;
			}
  
			let relXRtl = width - xCoor;
			let valueForDivision = width / 100;
  
			percent = relXRtl / valueForDivision;
		} else {
			if(isTouch) {
				xCoor =	e.changedTouches[0].pageX - parentOffset.left;
			} else {
				xCoor = e.offsetX;
			}
		
			percent = xCoor / width * 100;
		}

		if (percent < 101) {
			if (step === 1) {
				currentRating = Math.ceil((percent / 100) * stars); 
			}else {
				let rat = (percent / 100) * stars; 
				for (let i = 0; ; i += step) {
					if (i >= rat) {
						currentRating = i; 
						break; 
					}
				}
			}

			//todo: check why this happens and fix
			if(currentRating > stars) {
				currentRating = stars;
			}

			elem.querySelector(".star-value").style.width = currentRating/stars * 100 + "%"; 
	 
			if (showToolTip) {
				let toolTip = ratingText.replace("{rating}", currentRating); 
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
		}else {
			elem.querySelector(".star-value").style.width = rating/stars * 100 + "%"; 
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
			}else {
				elem.setAttribute("title", isBusyText); 
			}
			
			elem.classList.add("is-busy");
			callback.call(this, myRating, function() {
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
			let toolTip = disableText.replace("{rating}", !!rating ? rating : 0); 
			toolTip = toolTip.replace("{maxRating}", stars); 
			 elem.setAttribute("title", toolTip); 
		}else {
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
		elem.querySelector(".star-value").style.width = value/stars * 100 + "%"; 
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

	let module =  {
		setRating:setRating, 
		getRating:getRating, 
		disable:disable, 
		enable:enable, 
		clear:clear, 
		dispose:dispose,
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
		onMove(e,true);
	}

	/**
	 * Handles touchend event.
	 * @param {TouchEvent} e 
	 */
	function handleEnd(evt) {
		evt.preventDefault();
		onMove(evt,true);
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
}