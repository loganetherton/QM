//PhantomJS script to read biz photos
/**
 It accepts the following commands line arguments when ran from the terminal
 * @username Biz account username to be used for logging in
 * @password Password of above account
 * @biz_id Business identifier
 * @userAgent User agent to be used (Optional)

 Usage:
 phantomjs yelp_photos_read.js '<yelp_biz_username>' '<password>' '<biz_id>'
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

var businessPhotos = {
	business_id : null,
	photos : []
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
    businessPhotos.business_id = system.args[3];
	//Check for user-agent, if yes then set to the page settings
	if (system.args[4]) {
		yelpObject.userAgent = system.args[4];
		page.settings.userAgent = yelpObject.userAgent;
	}
}

//Read the command line arguments for options
readArguments();

page.onCallback = function(json) {
	if (json.method == "add_photos") {
		//Add photos to array
		businessPhotos.photos = businessPhotos.photos.concat(json.data);
	} else if (json.method == "add_scrapper_page") {
		steps.push(function() {
			page.evaluate(function(nextPage) {
				document.location.href = nextPage;
			}, json.url);
		});

		steps.push(photoScrapper);
	} else if(json.method == "logged_out"){
		//Not logged in
		console.log(constants.loggedOutSessionError);
		utils.phantomExit();	
	} else if(json.method == "slideshow_present"){
        console.log(constants.slideshowPresentError);
		utils.phantomExit();	
    }
	
	//Check for generic callbacks
	utils.checkGenericErrors(json.method);
};

var photoScrapper = function() {
	//Scrape photo pages
	page.evaluate(function() {
		var photoJson = {
			"id" : null,
			"url" : null,
			"caption" : null,
			"from_owner" : false,
			"from" : {},
			"actions" : {
				"edit_caption":false,
				"delete" : false,
				"flag" : false
			},
		}
        var isSlide = document.querySelector('#bizSlide');
        
        if(isSlide){
           //Add data
            window.callPhantom({
                "method" : "slideshow_present",
            }); 
        }
		
        var photosContArray = document.querySelectorAll('#biz-photos .local-photos-container');
		var data = [];
		for ( i = 0; i < photosContArray.length; i++) {
			var _photo = JSON.parse(JSON.stringify(photoJson));

			_photo.url = photosContArray[i].querySelector('.photo-box img').src;
			_photo.id = _photo.url.split('/')[_photo.url.split('/').length - 2];

			var _form = photosContArray[i].querySelector('form');
			if (_form != null) {
				//From owner
				_photo.actions['delete'] = true; 	//.delete removes a node from JSON
				_photo.actions['edit_caption'] = true;
				_photo.from_owner = true;
				_photo.caption = photosContArray[i].querySelector('.photo-actions textarea[name="caption"]').value;
				_photo.from = "owner";
			} else {
				_photo.actions.flag = true;
				_photo.caption = photosContArray[i].querySelector('.photo-actions .caption').innerText;
				_photo.from.name = photosContArray[i].querySelector('.photo-actions .attribution a').innerText;
				_photo.from.user_profile = photosContArray[i].querySelector('.photo-actions .attribution a').href;
				_photo.from.user_id = _photo.from.user_profile.split('?')[1].split('=')[1];
			}

			data.push(_photo);
		}

		//Add data
		window.callPhantom({
			"method" : "add_photos",
			"data" : data
		});

		//Pagination check
		var paginationDiv = document.querySelector('#pagination_direction');
		if (paginationDiv && (paginationDiv.innerText.indexOf("Next") > -1)) {
			var pages = paginationDiv.querySelectorAll('a');
			//This will include both Next and previous
			var nextPageUrl = pages[pages.length - 1].href;
			//Choose NEXT
			window.callPhantom({
				"method" : "add_scrapper_page",
				"url" : nextPageUrl
			});
		} else {
			//End of photos
		}

	});
}
//ACTUAL SCRAPER STEPS
var steps = [
common.openLogin, common.login,common.checkLoginStatus,common.multiBizRedirect(yelpObject.account.biz_id),
function() {
	//Open the photos page
	page.evaluate(function() {
		//document.location.href = document.querySelector(".photos a").href;
        var photos = document.querySelector(".photos a");
        if (photos){
            document.location.href = photos.href;
        } else {
            var slideshow = document.querySelector(".slideshow a");
            if(slideshow){
                //Process slideshow
                document.location.href = slideshow.href;    
            }
        }
        
        
	});
}, photoScrapper];

//Interval to execute steps
var interval = setInterval(function() {

	if (!loadInProgress && typeof steps[stepIndex] == "function") {
		steps[stepIndex]();
		stepIndex++;
	}

	if ( typeof steps[stepIndex] != "function") {
		if (config.envDev == true) {
			var file = fs.open("JSON/"+ yelpObject.account.username +"/photos_read.json", "w"); 
			file.write(JSON.stringify(businessPhotos));
			file.close();
		}
		console.log(JSON.stringify(businessPhotos));
		phantom.exit();
	}
}, utils.randomInterval());

