//PhantomJS script to upload a photo
/**
 It accepts the following commands line arguments when ran from the terminal
 * @username Biz account username to be used for logging in
 * @password Password of above account
 * @biz_id Biz id for the account
 * @photo_path Path to the photo to be uploaded
 * @caption Caption to be added to the image
 * @userAgent User agent to be used (Optional)

 Usage:
 phantomjs yelp_photos_upload.js '<yelp_biz_username>' '<password>' '<biz_id>' '<photo_path>' '<caption>'
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

var addPhoto = {
	path : null,
	caption : null
}

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
    
	addPhoto.path = system.args[4];
	addPhoto.caption = system.args[5];

	//Check for user-agent, if yes then set to the page settings
	if (system.args[6]) {
		yelpObject.userAgent = system.args[6];
		page.settings.userAgent = yelpObject.userAgent;
	}
}

//Read the command line arguments for options
readArguments();

page.onCallback = function(json) {
	if (json.method == "logged_out") {
		//Not logged in
		console.log(constants.loggedOutSessionError);
		utils.phantomExit();
	} else if (json.method == "add_verification_step") {
		if(json.redirect){
			steps.push(function(){
				page.evaluate(function(url){
					document.location.href = url;
				},json.url);
			})
		}
		
		steps.push(verificationStep);
	} else if (json.method == "status") {
		var result = '{"status":"' + json.status + '","data": ' + JSON.stringify(json.data) + '}';
		console.log(result);
		utils.phantomExit();
	} 

	//Check for generic callbacks
	utils.checkGenericErrors(json.method);
}
var verificationStep = function() {
	page.evaluate(function() {
		var photosContArray = document.querySelectorAll('#biz-photos .local-photos-container');
		var index = photosContArray.length-1;
		var _photo = {
			"id" : null,
			"url" : null,
			"caption" : null,
			"from_owner" : false,
			"from" : {},
			"actions" : {
				"edit_caption" : false,
				"delete" : false,
				"flag" : false
			}
		};

		_photo.url = photosContArray[index].querySelector('.photo-box img').src;
		_photo.id = _photo.url.split('/')[_photo.url.split('/').length - 2];
		_photo.actions['delete'] = true;
		_photo.actions['edit_caption'] = true;
		_photo.from_owner = true;
		_photo.caption = photosContArray[index].querySelector('.photo-actions textarea[name="caption"]').value;
		_photo.from = "owner";

		window.callPhantom({
			"method" : "status",
			"status" : "success",
			"data" : _photo
		});
	});

};

//ACTUAL SCRAPER STEPS
var steps = [common.openLogin, common.login, common.checkLoginStatus,common.multiBizRedirect(yelpObject.account.biz_id),
function() {
	//Open the photos page
	page.evaluate(function() {
		document.location.href = document.querySelector(".photos a").href;
	});
},
function() {
	page.uploadFile('#photo_file', addPhoto.path);
	page.evaluate(function(caption) {
		document.querySelector('#caption_new').value = caption;
		document.querySelector('#upload_photo_form').submit();
	}, addPhoto.caption);
},
function() {
	//Photo uploaded
	page.evaluate(function() {
		var success_message = document.querySelector('.info-notice,.success');

		if (success_message) {
			//Surprise surprise, redirection happens on its own ;)
			window.callPhantom({
				"method" : "add_verification_step",
				"redirect" : false
			});
		} else {
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
		utils.log('Exiting');
		phantom.exit();
	}
}, utils.randomInterval()); 