//PhantomJS script to reply to user messages
/**
 It accepts the following commands line arguments when ran from the terminal
  * @username Biz account username to be used for logging in
  * @password Password of above account
  * @reviewID  ID of the review to which message is to be sent
  * @responseType  type of reponse, public comment or private message
  * @replyMessage   Message to be sent to user
  * @userAgent User agent to be used (Optional)

  Usage:
  phantomjs Yelp_reply_to_review.js '<yelp_biz_username>' '<password>' '<review_id>' '<response_type>' "<reply_message>"
*/
var page = new WebPage();
var system = require('system');
var stepIndex = 0,
    loadInProgress = false,
    pgImg = 0;
var yelpObject = {
    'account': {},
    'reviewID': null,
    'responseType': null,
    'message': null,
    'userAgent': null
};

page.settings.loadImages = true;

//Read the command line arguments for options
readArguments();

/**
 *Function to read the command line arguments
 **/
function readArguments() {
    //system.args[0] is for file name which is being executed
    console.log('Reading arguments')

    if (system.args.length < 6 || system.args.length > 7) {
        console.log('Invalid number of arguments. Please check the arguments passed!');
        phantom.exit();
    }

    yelpObject.account['username'] = system.args[1];
    yelpObject.account['password'] = system.args[2];
    yelpObject.reviewID = system.args[3];

    if(system.args[4]=="public" || system.args[4]=="private"){
        yelpObject.responseType = system.args[4];
    }else{
        console.log('Wrong reponse type. Please choose from "public" or "private".');
        phantom.exit();
    }

    yelpObject.message = system.args[5];

    //Check for user-agent, if yes then set to the page settings
    if (system.args[6]) {
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
    page.render('images/' + pgImg + '.png');
    pgImg++;
    console.log("load finished");
};


var steps = [function () {
        //Load Login Page
        page.open("https://biz.yelp.com/login");
    }, function () {
        //Enter Credentials
        //Enter Credentials
        page.evaluate(function (username, password) {
            document.querySelector("#email").value = username;
            document.querySelector("#password").value = password;
            document.querySelector("button[type='submit']").click();
        }, yelpObject.account.username, yelpObject.account.password);
    }, function () {
        //Navigate to the reviews TAB
        page.evaluate(function () {
            document.location.href = document.querySelector(".reviews a").href;
        });
    }, function () {

        //Navigate to the review we want to reply to
        page.evaluate(function (reviewID, responseType) {
            //Escape characters used for non alpha-numeric characters in ID (Need reciewID for this)
            //var reviewSelector = "#review\\:\\:" + "sUft_KZWOaeEvDlykErtBw";
            var reviewSelector = "#review\\:\\:" + reviewID;

            //For public comment: typeOfReplySelector = ".sprite-add_public_comment";
            //For private message: typeOfReplySelector = ".sprite-message_customer";
            var typeOfReplySelector;
            if (responseType == "public") {
                //post public comment
                typeOfReplySelector = " .sprite-add_public_comment";
            } else {
                //Send private message
                typeOfReplySelector = " .sprite-message_customer";
            }

            //Goto send message or post comment
            document.location.href = document.querySelector(reviewSelector + typeOfReplySelector).href;

        }, yelpObject.reviewID, yelpObject.responseType);
    }, function () {
        page.evaluate(function (message , responseType) {
            
            if(responseType == "public"){
                document.querySelector('#comment-field').value = message; 
                document.querySelector('#compose_area form[name="compose_comment"]').submit()  
            }else{
                document.querySelector('#response_message').value = message;   
                document.querySelector('#response_form').submit();
            }
        }, yelpObject.message , yelpObject.responseType);
    }, function () {
        if(yelpObject.responseType == "public"){
            //One more submit remaining
            document.querySelector('#post-comment-container').submit();

        }else{
            page.render('Reply_to_Reviews/final.png');
            phantom.exit();    
        }
    }, function (){
        page.render('Reply_to_Reviews/final.png');
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