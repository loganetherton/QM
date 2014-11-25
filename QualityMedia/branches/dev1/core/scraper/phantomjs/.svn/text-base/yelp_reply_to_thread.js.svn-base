//PhantomJS script to reply to private messages
/**
 It accepts the following commands line arguments when ran from the terminal
 * @username Biz account username to be used for logging in
 * @password Password of above account
 * @thread_id Thread ID of the conversation
 * @mesage Message to be sent
 * @userAgent User agent to be used (Optional)

 Usage:
 phantomjs Yelp_reply_to_thread.js '<yelp_biz_username>' '<password>' '<thread_id>' '<message>'
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
	"account" : {},
	"thread_id" : null,
	"userAgent" : null,
	"message" : null
};

/**
 *Function to read the command line arguments
 **/

function readArguments() {
	//system.args[0] is for file name which is being executed

	if (system.args.length < 5 || system.args.length > 6) {
		console.log(constants.invalidArgumentsError);
		phantom.exit();
	}

	yelpObject.account['username'] = system.args[1];
	yelpObject.account['password'] = system.args[2];
	yelpObject.thread_id = system.args[3];
	yelpObject.message = system.args[4]

	//Check for user-agent, if yes then set to the page settings
	if (system.args[5]) {
		yelpObject.userAgent = system.args[5];
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

//ACTUAL SCRAPER STEPS
var steps = [common.openLogin, common.login,common.checkLoginStatus,
function() {
	//Navigate to the particular message
	page.evaluate(function() {
		document.location.href = document.querySelector(".messaging a").href;
	});
},
function() {
	//Navigate to the appropriate message
	page.evaluate(function(id) {

		var url_sample = document.querySelectorAll('#mail_container tr')[0].querySelector('.message_subject').href;
		var base_url = url_sample.split('/');

		//Pop the last part of the url which is ID
		base_url.pop();
		base_url.join().replace(/,/g, "/");

		var thread_url = base_url + "/" + thread_id;
		//Navigate to a particular thread
		document.location.href = thread_url;
	}, yelpObject.thread_id);
},
function() {
	//Reply to the message
	page.evaluate(function(message) {
		//Check if reply is enabled
		var unrespondable = document.querySelector('.unrespondable').style.display;

		if (unrespondable == "none") {
			//You can reply
			document.querySelector("#response_message").value = message;
			//Submit
			document.querySelector("#response_form").submit()
		} else {
			//Cannot reply
			console.log('Cannot reply');
			phantom.exit();
		}
	}, yelpObject.message);
}];

//Interval to execute steps
var interval = setInterval(function() {

	if (!loadInProgress && typeof steps[stepIndex] == "function") {
		steps[stepIndex]();
		stepIndex++;
	}

	if ( typeof steps[stepIndex] != "function") {
		utils.log('Exiting');
		phantom.exit();
	}
}, utils.randomInterval());
