//PhantomJS script to post reviews to a business
/**
 It accepts the following commands line arguments when ran from the terminal
 * @url  URL of the business
 * @username Account username to be used for logging in
 * @password Password of above account
 * @reviewContent   Content of the review to be published.
 * @starRating   Star rating to be given to the review [1-5]
 * @userAgent User agent to be used (Optional)

 Usage:
 phantomjs Yelp_post_review.js <Yelp_business_url> '<yelp_username>' '<password>' "<Review_Content>" Star_Rating
 */

var page = new WebPage();
var system = require('system');
var fs = require('fs');

phantom.injectJs("libs/config.ini");
phantom.injectJs("libs/utils.js");

/*Page statuses*/
page.onConsoleMessage    = phantomPage.onConsoleMessage;
page.onLoadStarted       = phantomPage.onLoadStarted;
page.onLoadFinished      = phantomPage.onLoadFinished;
page.onResourceRequested = phantomPage.onResourceRequested;
page.settings.loadImages = config.loadImages;

var stepIndex = 0, loadInProgress = false, pgImg = 0;
var yelpObject = {
	'url' : null,
	'account' : {},
	'reviewContent' : null,
	'starRating' : null,
	'userAgent' : null
};

/**
 *Function to read the command line arguments
 **/
function readArguments() {
	//system.args[0] is for file name which is being executed

	if (system.args.length < 6 || system.args.length > 7) {
		console.log(constants.invalidArgumentsError);
		phantom.exit();
	}

	yelpObject.url = system.args[1];
	yelpObject.account['username'] = system.args[2];
	yelpObject.account['password'] = system.args[3];
	yelpObject.reviewContent = system.args[4];
	if (system.args[5] < 1 || system.args[5] > 5) {
		utils.log('Invalid Star rating');
		phantom.exit();
	} else {
		yelpObject.starRating = system.args[5];
	}

	//Check for user-agent, if yes then set to the page settings
	if (system.args[6]) {
		yelpObject.userAgent = system.args[6];
		page.settings.userAgent = yelpObject.userAgent;
	}
}

//Read the command line arguments for options
readArguments();

page.onCallback = function (json) {
	if(json.method == "logged_out"){
		//Not logged in
		console.log(constants.loggedOutSessionError);
		utils.phantomExit();	
	}
	
	//Check for generic callbacks
	utils.checkGenericErrors(json.method);
}

//Steps for the phantom to follow
var steps = [common.openLogin, common.login,common.checkLoginStatus,
function() {
	//Redirect to the business page
	page.evaluate(function(url) {
		document.location.href = url;
	}, yelpObject.url);

},
function() {
	//Redirect to the review writing
	page.evaluate(function() {
		document.location.href = document.querySelector("#bizWriteReview").href;
	});
},
function() {
	//Fill the review form and submit it
	page.evaluate(function(rating, content) {
		var ratingSelector = "#rating-" + rating;
		document.querySelector(ratingSelector).click();
		document.querySelector("#review-text").focus();
		document.querySelector("#review-text").value = content;
		document.querySelector("#review-submit-button").focus();
		document.querySelector("#review-submit-button").click();
		document.querySelector("#review_rate_form").submit();
	}, yelpObject.starRating, yelpObject.reviewContent);
},
function() {
	page.render('Post_review/' + yelpObject.url + '.png');
}];

//Interval to execute steps
var interval = setInterval(function() {
	if (!loadInProgress && typeof steps[stepIndex] == "function") {
		steps[stepIndex]();
		stepIndex++;
	}
	if ( typeof steps[stepIndex] != "function") {
		phantom.exit();
	}
}, utils.randomInterval());
