/*
Template Name: TailFox - Responsive Tailwind Admin Dashboard
Author: Myra Studio
Website: https://myrathemes.com/
Contact: myrathemes@gmail.com
File: Ratings
*/


// basic-rater
if (document.querySelector('#basic-rater'))
    var basicRating = raterJs({
        starSize: 22,
        rating: 3,
        element: document.querySelector("#basic-rater"),
        rateCallback: function rateCallback(rating, done) {
            this.setRating(rating);
            done();
        }
    });

// rater-step
if (document.querySelector('#rater-step'))
    var starRatingStep = raterJs({
        starSize: 22,
        rating: 1.5,
        element: document.querySelector("#rater-step"),
        rateCallback: function rateCallback(rating, done) {
            this.setRating(rating);
            done();
        }
    });

// rater-message
var messageDataService = {
    rate: function (rating) {
        return {
            then: function (callback) {
                setTimeout(function () {
                    callback((Math.random() * 5));
                }, 1000);
            }
        }
    }
}

if (document.querySelector('#rater-message'))
    var starRatingmessage = raterJs({
        isBusyText: "Rating in progress. Please wait...",
        starSize: 22,
        element: document.querySelector("#rater-message"),
        rateCallback: function rateCallback(rating, done) {
            starRatingmessage.setRating(rating);
            messageDataService.rate().then(function (avgRating) {
                starRatingmessage.setRating(avgRating);
                done();
            });
        }
    });

// rater-unlimitedstar
if (document.querySelector('#rater-unlimitedstar'))
    var starRatingunlimited = raterJs({
        starSize: 22,
        max: 5,
        readOnly: true,
        rating: 3.5,
        element: document.querySelector("#rater-unlimitedstar")
    });

// rater-onhover
if (document.querySelector('#rater-onhover'))
    var starRatinghover = raterJs({
        starSize: 22,
        rating: 1,
        element: document.querySelector("#rater-onhover"),
        rateCallback: function rateCallback(rating, done) {
            this.setRating(rating);
            done();
        },
        onHover: function (currentIndex, currentRating) {
            document.querySelector('.ratingnum').textContent = currentIndex;
        },
        onLeave: function (currentIndex, currentRating) {
            document.querySelector('.ratingnum').textContent = currentRating;
        }
    });

// rater-reset
if (document.querySelector('#raterreset'))
    var starRatingreset = raterJs({
        starSize: 22,
        rating: 2,
        element: document.querySelector("#raterreset"),
        rateCallback: function rateCallback(rating, done) {
            this.setRating(rating);
            done();
        }
    });

if (document.querySelector('#raterreset-button'))
    document.querySelector('#raterreset-button').addEventListener("click", function () {
        starRatingreset.clear();
    }, false);