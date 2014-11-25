//PhantomJS script to read biz data
/**
 It accepts the following commands line arguments when ran from the terminal
 * @username Biz account username to be used for logging in
 * @password Password of above account
 * @biz_id Business identifier
 * @sections_array Array of sections that need to be scraped(comma separated). Allowed values : analytics,info,photos,reviews
 * @userAgent User agent to be used (Optional)

 Usage:
 phantomjs yelp_read_data.js '<yelp_biz_username>' '<password>' '<biz_id>' '<sections_array>'
 */
var page = new WebPage();
var system = require('system');
var fs = require("fs");

phantom.injectJs("libs/config.ini");
phantom.injectJs("libs/utils.js");
phantom.injectJs("libs/read_analytics.js");
phantom.injectJs("libs/read_info.js");
phantom.injectJs("libs/read_photo.js");
phantom.injectJs("libs/read_reviews.js");
phantom.injectJs("libs/read_callbacks.js");


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
	"base_url" : null,
    "sections":[]
};

var accountData = {
    "analytics":{},
    "info":{},
    "reviews":{},
    "photos":{}
};

//Function to merge all the indi
function mergeData(){
    accountData.analytics = analyticsData;
    accountData.info = businessInfo;
    accountData.reviews = reviewsObject;
    accountData.photos = businessPhotos;
    
    accountData.analytics.business_id = accountData.photos.business_id = accountData.info.business_id = accountData.reviews.business_id = yelpObject.account.biz_id;
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
    yelpObject.account['sections'] = system.args[4].split(',');
	
	//Check for user-agent, if yes then set to the page settings
	if (system.args[5]) {
		yelpObject.userAgent = system.args[5];
		page.settings.userAgent = yelpObject.userAgent;
	}
}

//Read the command line arguments for options
readArguments();

//Util function to redirect to home page
var redirectToHome = function(){
    page.evaluate(function() {
		document.location.href =  "https://biz.yelp.com/";
	});
};

//Init step
var initStep = function(index){
    utils.log("intiStep index="+index);
    steps.push(redirectToHome); 
    common.multiBizRedirect(yelpObject.account.biz_id);
    
    switch(yelpObject.account.sections[index]){
        case "analytics":
            steps.push(redirectToAnalytics);
            break;
        case "info":
            steps.push(redirectToInfo);
            steps.push(infoInitStep);
            break;
        case "photos":
            steps.push(redirectToPhotos);
            break;
        case "reviews":
            steps.push(redirectToReviews);
            steps.push(pageScraper);
            break;
        default:;
    }
}

//STEPS TO MOVE BETWEEN DIFF SECTIONS
var moveToNextSection = function(currentSection){
    if(currentSection == undefined || currentSection == "" ){
       initStep(0); 
    }else {
       var currentIndex = yelpObject.account.sections.indexOf(currentSection);
       if(currentIndex+1<yelpObject.account.sections.length){
        initStep(currentIndex+1);
       }
    }
}


//ACTUAL SCRAPER STEPS
var steps = [common.openLogin, common.login, common.checkLoginStatus ,common.multiBizRedirect(yelpObject.account.biz_id) , moveToNextSection];

//Interval to execute steps
var interval = setInterval(function() {

	if (!loadInProgress && typeof steps[stepIndex] == "function") {
		steps[stepIndex]();
		stepIndex++;
	}

	if ( typeof steps[stepIndex] != "function") {
		
        //Link messages and reviews
        linkReviewMessages();
        mergeData();
        
        
        if (config.envDev == true) {
            var file = fs.open("JSON/" + yelpObject.account.username + "/data.json", "w");
            file.write(JSON.stringify(accountData));
            file.close();
        }
        utils.log('Exiting');
        console.log(JSON.stringify(accountData));
		phantom.exit();
	}
}, utils.randomInterval());