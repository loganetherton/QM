//PhantomJS script to post reviews to a business
/**
 It accepts the following commands line arguments when ran from the terminal
  * @url  URL of the business
  * @username Account username to be used for logging in
  * @password Password of above account
  * @reviewContent   Content of the review to be published.
  * @starRating   Star rating to be given to the review [1-5]
  * @userAgent User agent to be used (Optional)

  Usage:
  phantomjs Yelp_post_review.js <Yelp_business_url> '<yelp_username>' '<password>' "<Review_Content>" Star_Rating
*/


var page = new WebPage();
var system = require('system');
var stepIndex = 0,loadInProgress = false,pgImg=0;
var yelpObject = {
    'url':null,
    'account':{},
    'reviewContent':null,
    'starRating':null,
    'userAgent':null
};


page.settings.loadImages = false;

//Read the command line arguments for options
readArguments();

 /**
  *Function to read the command line arguments
  **/
function readArguments(){
    //system.args[0] is for file name which is being executed
    console.log('Reading arguments')
    
    if(system.args.length < 6 || system.args.length > 7){
        console.log('Invalid number of arguments. Please check the arguments passed!');    
        phantom.exit();
    }
    
    yelpObject.url = system.args[1];
    yelpObject.account['username'] = system.args[2];
    yelpObject.account['password'] = system.args[3];
    yelpObject.reviewContent = system.args[4];
    if(system.args[5] < 1 || system.args[5] > 5){
        console.log('Invalid Star rating');
        phantom.exit();
    }else{
        yelpObject.starRating = system.args[5];    
    }
    
    //Check for user-agent, if yes then set to the page settings
    if(system.args[6]){
        yelpObject.userAgent = system.args[6];
        page.settings.userAgent = yelpObject.userAgent;
    }
}

/*Page statuses*/
page.onConsoleMessage = function (msg) {
    console.log(msg);
};

page.onLoadStarted = function () {
    loadInProgress = true;
};

page.onLoadFinished = function () {
     loadInProgress = false;
     page.render('images/'+pgImg+'.png');
     pgImg++;
     console.log("load finished");
};


var steps = [function () {
        //Load Login Page
        page.open("https://www.yelp.com/login");
    }, function () {
        //Enter Credentials
        page.evaluate(function (username,password) {
            document.querySelector("input[type='email']").value= username;
            document.querySelector("input[type='password']").value= password;
            document.querySelector("button[name='action_submit']").click();

        },yelpObject.account.username,yelpObject.account.password);

    }, function () {
        //Redirect to the business page
        page.evaluate(function (url) {
            document.location.href = url;
        },yelpObject.url);

    }, function () {
        //Redirect to the review writing
        page.evaluate(function () {
            document.location.href = document.querySelector("#bizWriteReview").href;
        });
    }, function () {
        //Fill the review form and submit it
        page.evaluate(function (rating,content) {
            var ratingSelector = "#rating-"+rating;
            document.querySelector(ratingSelector).click();
            document.querySelector("#review-text").focus();
            document.querySelector("#review-text").value= content;
            document.querySelector("#review-submit-button").focus();
            document.querySelector("#review-submit-button").click();
            document.querySelector("#review_rate_form").submit();
        }, yelpObject.starRating,yelpObject.reviewContent);
    }, function () {
        page.render('Post_review/'+yelpObject.url+'.png');
    }
];


interval = setInterval(function () {
    if (!loadInProgress && typeof steps[stepIndex] == "function") {
        steps[stepIndex]();
        stepIndex++;
    }
    if (typeof steps[stepIndex] != "function") {
        phantom.exit();
    }
}, 100);
