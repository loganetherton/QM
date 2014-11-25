//PhantomJS script to upload a photo
/**
 It accepts the following commands line arguments when ran from the terminal
  * @username Biz account username to be used for logging in
  * @password Password of above account
  * @photo_path Path to the photo to be uploaded
  * @caption Caption to be added to the image
  * @userAgent User agent to be used (Optional)

  Usage:
  phantomjs yelp_photos_upload.js '<yelp_biz_username>' '<password>' '<photo_path>' '<caption>'
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

var stepIndex = 0,loadInProgress = false,pgImg = 0;
var yelpObject = {
    "account": {},
    "userAgent": null
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
        console.log(utils.invalidArgumentsError);
        phantom.exit();
    }

    yelpObject.account['username'] = system.args[1];
    yelpObject.account['password'] = system.args[2];
    addPhoto.path = system.args[3];
	addPhoto.caption = system.args[4];
    
    //Check for user-agent, if yes then set to the page settings
    if (system.args[5]) {
        yelpObject.userAgent = system.args[5];
        page.settings.userAgent = yelpObject.userAgent;
    }
}

//Read the command line arguments for options
readArguments();

//ACTUAL SCRAPER STEPS
var steps = [
    function () {
        //Load Login Page
        page.open("http://biz.yelp.com/login",function(status){
            if (status !== 'success') {
                utils.log('Unable to access network');
            } else {
                utils.log('Success');
            }
        });

    },
    function () {
        //Enter Credentials
        page.evaluate(function (username, password) {
            document.querySelector("#email").value = username;
            document.querySelector("#password").value = password;
            document.querySelector("button[type='submit']").click();
        }, yelpObject.account.username, yelpObject.account.password);
    },
    function () {
        //Open the photos page
        page.evaluate(function () {
            document.location.href = document.querySelector(".photos a").href;
        });
    },
    function () {
        page.uploadFile('#photo_file', addPhoto.path);
        page.evaluate(function (caption) {
            document.querySelector('#caption_new').value = caption;
            document.querySelector('#upload_photo_form').submit();
        },addPhoto.caption);
    },
    function () {
        //Photo uploaded
        page.evaluate(function () {
            
        });
    }
];


//Interval to execute steps
var interval = setInterval(function () {

    if (!loadInProgress && typeof steps[stepIndex] == "function") {
        steps[stepIndex]();
        stepIndex++;
    }

    if (typeof steps[stepIndex] != "function") {
        utils.log('Exiting');
        phantom.exit();
    }
}, utils.randomInterval());


