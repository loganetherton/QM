//PhantomJS script to remove business information section
/**
 It accepts the following commands line arguments when ran from the terminal
 * @username Biz account username to be used for logging in
 * @password Password of above account
 * @biz_id Biz id for the account
 * @info_type Info identifier( 'hours','specialties','history' & 'owner_info')
 * @userAgent User agent to be used (Optional)

 Usage:
 phantomjs yelp_info_remove.js '<yelp_biz_username>' '<password>' '<biz_id>' '<info_type>'
 */
var page = new WebPage();
var system = require('system');
var fs = require("fs");

phantom.injectJs("libs/config.ini");
phantom.injectJs("libs/utils.js");

/*Page statuses*/
page.onConsoleMessage = phantomPage.onConsoleMessage;
page.onLoadStarted = phantomPage.onLoadStarted;
page.onLoadFinished = phantomPage.onLoadFinished;
page.settings.loadImages = config.loadImages;

var stepIndex = 0, loadInProgress = false, pgImg = 0;

var yelpObject = {
	"account" : {
		"username" : null,
		"password" : null
	},
	"userAgent" : null,
	"info_to_remove" : null
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
    yelpObject.account['biz_id'] = system.args[3];
    
	yelpObject.info_to_remove = system.args[4];

	//Check for user-agent, if yes then set to the page settings
	if (system.args[5]) {
		yelpObject.userAgent = system.args[5];
		page.settings.userAgent = yelpObject.userAgent;
	}
}

//Read the command line arguments for options
readArguments();

page.onCallback = function(json) {
	if (json.method == "status") {
			console.log('{"status":"' + json.status + '"}');
			utils.phantomExit();
	}

	//Check for generic callbacks
	utils.checkGenericErrors(json.method);
};

//ACTUAL SCRAPER STEPS
var steps = [common.openLogin, common.login, common.checkLoginStatus,common.multiBizRedirect(yelpObject.account.biz_id),
function() {
	//Open the info page
	page.evaluate(function() {
		document.location.href = document.querySelector(".info a").href;
	});
},
function() {
	//Remove the info
	page.evaluate(function(info_type) {
		//
		switch(info_type) {
			case 'hours': document.querySelector('#biz-info-form-hours').submit();
				break;
			case 'specialties': document.querySelector('#biz-info-form-specialties').submit();
				break;
			case 'history': document.querySelector('#biz-info-form-history').submit();
				break;
			case 'owner_info': document.querySelector('#biz-info-form-bio').submit();
				break;
			default:;
		}
	}, yelpObject.info_to_remove);
},
function() {
	page.evaluate(function() {
		var success_message = document.querySelector('.info-notice,.success');
		
		if(success_message){
			window.callPhantom({
				"method" : "status",
				"status" : "success"
			});
		}else{
			window.callPhantom({
				"method" : "status",
				"status" : "error"
			});
		}
	});
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

