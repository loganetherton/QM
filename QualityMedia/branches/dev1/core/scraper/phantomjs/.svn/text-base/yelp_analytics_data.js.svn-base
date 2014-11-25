//PhantomJS script to read analytics data
/**
 It accepts the following commands line arguments when ran from the terminal
 * @username Biz account username to be used for logging in
 * @password Password of above account
 * @biz_id Business identifier
 * @userAgent User agent to be used (Optional)

 Usage:
 phantomjs yelp_analytics_data.js '<yelp_biz_username>' '<password>' '<biz_id>'
 */
var page = new WebPage();
var system = require('system');
var fs = require("fs");

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
	"userAgent" : null,
	"base_url" : null
};

var analyticsData = {
	"business_id":null,
	"data":{},
};

/**
 *Function to read the command line arguments
 **/

function readArguments() {
	//system.args[0] is for file name which is being executed

	if (system.args.length < 4 || system.args.length > 5) {
		console.log(constants.invalidArgumentsError);
		phantom.exit();
	}

	yelpObject.account['username'] = system.args[1];
	yelpObject.account['password'] = system.args[2];
    yelpObject.account['biz_id'] = system.args[3];
	analyticsData.business_id = system.args[3];
	//Check for user-agent, if yes then set to the page settings
	if (system.args[4]) {
		yelpObject.userAgent = system.args[4];
		page.settings.userAgent = yelpObject.userAgent;
	}
}

//Read the command line arguments for options
readArguments();

page.onCallback = function(json) {
	if (json.method == "publish") {
		if (config.envDev == true) {
			var file = fs.open("JSON/" + yelpObject.account.username + "/Analytics.json", "w");
			file.write(JSON.stringify(analyticsData));
			file.close();
		}
		console.log(JSON.stringify(analyticsData));
	} else if (json.method == "add_analytics_data") {
		analyticsData['data'][json.time] = unescape(json.data);
	} else if (json.method == "base_url") {
		yelpObject.base_url = json.url;
        analyticsData.data.arpu = json.arpu;
		analyticsData.data.star_rating = json.star_rating;

		//Fill steps
		steps.push(function() {
			page.evaluate(function(url) {
				document.location.href = url;
			}, yelpObject.base_url + "1m");
		});
		steps.push(analyticsScrapper.oneMonth);

		steps.push(function() {
			page.evaluate(function(url) {
				document.location.href = url;
			}, yelpObject.base_url + "1y");
		});
		steps.push(analyticsScrapper.oneYear);

		steps.push(function() {
			page.evaluate(function(url) {
				document.location.href = url;
			}, yelpObject.base_url + "2y");
		});

		steps.push(analyticsScrapper.twoYear);
		steps.push(analyticsScrapper.publishData);
	} else if (json.method == "logged_out") {
		//Not logged in
		console.log(constants.loggedOutSessionError);
		utils.phantomExit();
	}

	//Check for generic callbacks
	utils.checkGenericErrors(json.method);
};

var analyticsScrapper = {
	oneMonth : function() {
		page.evaluate(function() {
			var data = document.querySelector('body').innerText;
			window.callPhantom({
				"method" : "add_analytics_data",
				"data" : data,
				"time" : "one_month"
			});
		});
	},

	oneYear : function() {
		page.evaluate(function() {
			var data = document.querySelector('body').innerText;
			window.callPhantom({
				"method" : "add_analytics_data",
				"data" : data,
				"time" : "one_year"
			});
		});
	},

	twoYear : function() {
		page.evaluate(function() {
			var data = document.querySelector('body').innerText;
			window.callPhantom({
				"method" : "add_analytics_data",
				"data" : data,
				"time" : "two_year"
			});
		});
	},

	publishData : function() {
		//Analytics DATA
		page.evaluate(function() {
			window.callPhantom({
				"method" : "publish"
			});
		});
	}
};

//ACTUAL SCRAPER STEPS
var steps = [common.openLogin, common.login, common.checkLoginStatus,common.multiBizRedirect(yelpObject.account.biz_id),
function() {
	//Open the analytics page
	page.evaluate(function() {
		var biz_id = document.location.href.split('/')[4];
		var arpu = document.querySelector('.js-revenue-per-lead-input').value;
		var star_rating = 0;

        var ratingInfoSelector = document.querySelector('.ratingInfo .rating i');
        if(ratingInfoSelector) {
            star_rating = parseInt(ratingInfoSelector.classList[1].split('_')[1]);
        }

        window.callPhantom({
			"method" : "base_url",
			"url" : "https://biz.yelp.com/biz_analytics/" + biz_id + "/json?period=",
			"arpu" : arpu,
			"star_rating" : star_rating
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

