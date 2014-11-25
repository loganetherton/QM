//PhantomJS script to read analytics data
/**
 It accepts the following commands line arguments when ran from the terminal
 * @username Biz account username to be used for logging in
 * @password Password of above account
 * @userAgent User agent to be used (Optional)

 Usage:
 phantomjs yelp_analytics_data.js '<yelp_biz_username>' '<password>'
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
	"account" : {},
	"userAgent" : null
};

/**
 *Function to read the command line arguments
 **/

function readArguments() {
	//system.args[0] is for file name which is being executed

	if (system.args.length < 3 || system.args.length > 4) {
		console.log(utils.invalidArgumentsError);
		phantom.exit();
	}

	yelpObject.account['username'] = system.args[1];
	yelpObject.account['password'] = system.args[2];

	//Check for user-agent, if yes then set to the page settings
	if (system.args[3]) {
		yelpObject.userAgent = system.args[3];
		page.settings.userAgent = yelpObject.userAgent;
	}
}

//Read the command line arguments for options
readArguments();

page.onCallback = function(json) {
	if (json.method == "analytics_data") {
		var file = fs.open("Analytics.json", "w");
		file.write(json.data);
		file.close();
	}
};

//ACTUAL SCRAPER STEPS
var steps = [
function() {
	//Load Login Page
	page.open("http://biz.yelp.com/login", function(status) {
		if (status !== 'success') {
			utils.log('Unable to access network');
		} else {
			utils.log('Success');
		}
	});

},
function() {
	//Enter Credentials
	page.evaluate(function(username, password) {
		document.querySelector("#email").value = username;
		document.querySelector("#password").value = password;
		document.querySelector("button[type='submit']").click();
	}, yelpObject.account.username, yelpObject.account.password);
},
function() {
	//Open the analytics page
	page.evaluate(function() {
		var biz_id = document.location.href.split('/')[4];
		var analytics_url = "https://biz.yelp.com/biz_analytics/" + biz_id + "/json?period=2y";
		document.location.href = analytics_url;
	});
},
function() {
	//Analytics DATA
	page.evaluate(function() {
		var data = document.querySelector('body').innerText;
		window.callPhantom({
			"method" : "analytics_data",
			"data" : data
		});
	});
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

