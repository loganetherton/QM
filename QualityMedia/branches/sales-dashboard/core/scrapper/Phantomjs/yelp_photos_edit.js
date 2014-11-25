//PhantomJS script to edit a photo(change caption,delete ,flag)
/**
 It accepts the following commands line arguments when ran from the terminal
 * @username Biz account username to be used for logging in
 * @password Password of above account
 * @biz_id Business identifier
 * @photo_id ID of the photo
 * @action Action to be performed on the photo (delete , flag , add_caption , edit_caption)
 * @content  Content for the action (Case where action is flag , add_caption , edit_caption)
 * @userAgent User agent to be used (Optional)

 Usage:
 phantomjs yelp_photos_edit.js '<yelp_biz_username>' '<password>' '<biz_id>' '<photo_id>' '<action>'
 phantomjs yelp_photos_edit.js '<yelp_biz_username>' '<password>' '<biz_id>' '<photo_id>' '<action>' '<content>'
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

var editPhoto = {
	business_id : null,
	photo_id : null,
	action : null,
	content : null
}

/**
 *Function to read the command line arguments
 **/

function readArguments() {
	//system.args[0] is for file name which is being executed

	if (system.args.length < 6 || system.args.length > 8) {
		console.log(utils.invalidArgumentsError);
		phantom.exit();
	}

	yelpObject.account['username'] = system.args[1];
	yelpObject.account['password'] = system.args[2];
	editPhoto.business_id = system.args[3];
	editPhoto.photo_id = system.args[4];
	editPhoto.action = system.args[5];

	if (editPhoto.action.indexOf('caption') > -1 || editPhoto.action == 'flag') {
		//Action is related to captionso read it
		editPhoto.content = system.args[6];
	}

	//Check for user-agent, if yes then set to the page settings
	if (system.args[7]) {
		yelpObject.userAgent = system.args[7];
		page.settings.userAgent = yelpObject.userAgent;
	}
}

//Read the command line arguments for options
readArguments();

page.onCallback = function(json) {
	if (json.method == "add_flag_scrapper") {
		steps.push(function(content) {
			page.evaluate(function() {
				//Fill flag form & submit it
				document.querySelector('#message-field').value = content;
				//document.querySelector('#flag-form').submit();
				document.querySelector('#submit').click()
			}, json.content);
		});
		steps.push(function() {
			page.evaluate(function() {
				//Empty step to complete the last form submit
			});
		});
	} else if (json.method == "add_scrapper_page") {
		steps.push(function() {
			page.evaluate(function(nextPage) {
				document.location.href = nextPage;
			}, json.url);
		});

		steps.push(editScrapper);
	} else if(json.method == "finish_step"){
		steps.push(function() {
			page.evaluate(function() {
				//Empty step to complete the last form submit
			});
		});
	}
};

var editScrapper = function() {
	//Scrape photo pages
	page.evaluate(function(photoObj) {
		var photosContArray = document.querySelectorAll('#biz-photos .local-photos-container');
		var url, id ,photoFound = false;
		
		for ( i = 0; i < photosContArray.length; i++) {
			url = photosContArray[i].querySelector('.photo-box img').src;
			id = url.split('/')[url.split('/').length - 2];

			//Check if this is the photo
			if (photoObj.photo_id == id) {
				
				photoFound = true;
				var _forms = photosContArray[i].querySelectorAll('form');
				if (_forms.length > 0) {
					
					//From owner (possible action = delete , update caption)
					if (photoObj.action == 'delete') {
						_forms[0].submit();
					} else if (photoObj.action.indexOf('caption') > -1) {
						photosContArray[i].querySelector('.photo-actions textarea[name="caption"]').value = photoObj.content;
						_forms[1].submit();
					}
				} else {
					//From user (possible action = flag)
					if(photoObj.action == 'flag'){
					  	//Goto flag photo flag page
					  	window.callPhantom({
							"method" : "add_flag_scrapper",
							"content" : photoObj.content
						});
					  	
					  	document.location.href = photosContArray[i].querySelector('.flag-content').href;
					}
				}
			}

		}

		//Pagination check
		var paginationDiv = document.querySelector('#pagination_direction');
		if (paginationDiv && (paginationDiv.innerText.indexOf("Next") > -1) && !photoFound) {
			var pages = paginationDiv.querySelectorAll('a');
			var nextPageUrl = pages[pages.length - 1].href;

			window.callPhantom({
				"method" : "add_scrapper_page",
				"url" : nextPageUrl
			});
		} else if(photoFound){
			//End of photos
			window.callPhantom({
				"method" : "finish_step"
			});
		}

	}, editPhoto);
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
	//Open the photos page
	page.evaluate(function() {
		document.location.href = document.querySelector(".photos a").href;
	});
}, editScrapper];

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

