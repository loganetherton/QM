//PhantomJS script to read business information
/**
 It accepts the following commands line arguments when ran from the terminal
 * @username Biz account username to be used for logging in
 * @password Password of above account
 * @biz_id Business identifier
 * @userAgent User agent to be used (Optional)

 Usage:
 phantomjs yelp_info_read.js '<yelp_biz_username>' '<password>' '<biz_id>'
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
var infoUrls = {}, sections = ['basic_info', 'hours', 'specialties', 'history', 'owner_info'];
var yelpObject = {
	"account" : {},
	"userAgent" : null
};

var businessInfo = {
	business_id : null,
	info : {
		'basic_info' : {},
		'hours' : [],
		'specialties' : {},
		'history' : {},
		'owner_info' : {}
	}
};

/**
 *Function to read the command line arguments
 **/

function readArguments() {
	//system.args[0] is for file name which is being executed

	if (system.args.length < 4 || system.args.length > 5) {
		console.log(utils.invalidArgumentsError);
		phantom.exit();
	}

	yelpObject.account['username'] = system.args[1];
	yelpObject.account['password'] = system.args[2];
	businessInfo.business_id = system.args[3];

	//Check for user-agent, if yes then set to the page settings
	if (system.args[4]) {
		yelpObject.userAgent = system.args[4];
		page.settings.userAgent = yelpObject.userAgent;
	}
}

//Read the command line arguments for options
readArguments();

page.onCallback = function(json) {
	if (json.method == "add_info") {
		//Add info to JSON
		businessInfo.info[json.type] = json.data;
	} else if (json.method == "add_infoUrl") {
		infoUrls = json.data;
		addScrapperSteps();
	}
};

//Function to add steps to the scrapper
var addScrapperSteps = function() {
	for ( i = 0; i < sections.length; i++) {
		var url = infoUrls[sections[i]];
		//var url = JSON.stringify(infoUrls[sections[i]]); //JS sucks at times :P

		steps.push(redirectStep(url));
		steps.push(scrapper[sections[i]]);
	}
};

var redirectStep = function(_url) {
	return function() {
		page.evaluate(function(url) {
			document.location.href = url;
		}, _url);
	}
}
//Scrappers for various info
var scrapper = {
	//Basic info scrapper
	basic_info : function() {
		page.evaluate(function() {
			var data = {
				"name" : {
					"biz_name" : null,
					"isLocked" : false
				},
				"address" : {
					"line1" : null,
					"line2" : null,
					"city" : null,
					"state" : null,
					"zip" : null
				},
				"phone" : null,
				"website" : null,
				"location" : {
					"latitude" : null,
					"longitude" : null
				}
			}
			var basic_info = document.querySelector('#edit-basic-info');

			data.name.biz_name = basic_info.querySelector('.BusinessName input').value;
			if (basic_info.querySelector('.BusinessName').classList.contains('locked')) {
				data.name.isLocked = true;
			}

			data.address.line1 = basic_info.querySelector('.BusinessStreetAddress1 input').value;
			data.address.line2 = basic_info.querySelector('.BusinessStreetAddress2 input').value;
			data.address.city = basic_info.querySelector('.BusinessCity input').value;
			data.address.state = basic_info.querySelector('.BusinessState input').value;
			data.address.zip = basic_info.querySelector('.BusinessZipCode input').value;

			data.phone = basic_info.querySelector('.BusinessPhoneNumber input').value;
			data.website = basic_info.querySelector('.BusinessUrl input').value;

			data.location.latitude = document.querySelector('#latitude').value;
			data.location.longitude = document.querySelector('#longitude').value;

			//Add data
			window.callPhantom({
				"method" : "add_info",
				"type" : "basic_info",
				"data" : data
			});
		});
	},

	//Hours info scrapper
	hours : function() {
		page.evaluate(function() {
			var data = [];
			var dataTemplate = {
				'day' : null,
				'start_time' : null,
				'end_time' : null,
				'value' : null
			};

			var working_days = document.querySelectorAll('#day-hours-BusinessHours .hours-display .hours');
		
			for ( i = 0; i < working_days.length; i++) {
				var _template = JSON.parse(JSON.stringify(dataTemplate));
				_template.day = working_days[i].querySelector('.weekday').innerText;
				_template.start_time = working_days[i].querySelector('.start').innerText;
				_template.end_time = working_days[i].querySelector('.end').innerText;
				_template.value = working_days[i].querySelector('input[name="BusinessHours"]').value;
				data.push(_template);	
			}
			
			//Add data
			window.callPhantom({
				"method" : "add_info",
				"type" : "hours",
				"data" : data
			});

		});
	},

	//specialties info scrapper
	specialties : function() {
		page.evaluate(function() {
			var data = {
				speciality : null
			};

			data.speciality = document.querySelector('#AboutThisBizSpecialties').value;
			//Add data
			window.callPhantom({
				"method" : "add_info",
				"type" : "specialties",
				"data" : data
			});
		});
	},

	//History info scrapper
	history : function() {
		page.evaluate(function() {
			var data = {
				year_established : null,
				history : null
			};

			data.year_established = document.querySelector('#AboutThisBizYearEstablished').value;
			data.history = document.querySelector('#AboutThisBizHistory').value;

			//Add data
			window.callPhantom({
				"method" : "add_info",
				"type" : "history",
				"data" : data
			});
		});
	},

	//Owner info scrapper
	owner_info : function() {
		page.evaluate(function() {
			var data = {
				first_name : null,
				last_initial : null,
				role : null,
				bio : null,
				photo : null
			}

			data.first_name = document.querySelector('#AboutThisBizBioFirstName').value;
			data.last_initial = document.querySelector('#AboutThisBizBioLastName').value;
			data.role = document.querySelector('#AboutThisBizRole').value;
			data.bio = document.querySelector('#AboutThisBizBio').value;
			data.photo = document.querySelector('#bio_photo_options input:checked').id;
			
			//Add data
			window.callPhantom({
				"method" : "add_info",
				"type" : "owner_info",
				"data" : data
			});
		});
	},
}

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
	//Open the info page
	page.evaluate(function() {
		document.location.href = document.querySelector(".info a").href;
	});
},
function() {
	//Open first section
	page.evaluate(function() {
		var infoUrls = {}
		infoUrls.basic_info = document.querySelector("#basic_business_info a").href;
		infoUrls.hours = document.querySelector("#biz-info-form-hours a").href;
		infoUrls.specialties = document.querySelector("#biz-info-form-specialties a").href;
		infoUrls.history = document.querySelector("#biz-info-form-history a").href;
		infoUrls.owner_info = document.querySelector("#biz-info-form-bio a").href;

		window.callPhantom({
			"method" : "add_infoUrl",
			"data" : infoUrls
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
		/*var file = fs.open("Info/info_read_" + businessInfo.business_id + ".json", "w");
		file.write(JSON.stringify(businessInfo));
		file.close();*/
		utils.log('Exiting');
		console.log(JSON.stringify(businessInfo));
		phantom.exit();
	}
}, utils.randomInterval());

