//PhantomJS script to reply to user messages
/**
 It accepts the following commands line arguments when ran from the terminal
  * @username Biz account username to be used for logging in
  * @password Password of above account
  * @userId  User ID of the user to whom reply is to be sent
  * @replyMessage   Message to be sent to user
  * @userAgent User agent to be used (Optional)

  Usage:
  phantomjs Yelp_reply_to_message.js '<yelp_biz_username>' '<password>' '<replyTo_user_id>' '<reply_message>'
*/
var page = new WebPage();
var system = require('system');
var fs = require('fs');

phantom.injectJs("libs/config.ini");
phantom.injectJs("libs/utils.js");


/*Page statuses*/
page.onConsoleMessage = phantomPage.onConsoleMessage;
page.onLoadStarted = phantomPage.onLoadStarted;
page.onLoadFinished = phantomPage.onLoadFinished;
page.settings.loadImages = config.loadImages;


var stepIndex = 0,loadInProgress = false,pgImg = 0;
var yelpObject = {
    'account': {},
    'userID': null,
    'message': null,
    'userAgent': null
};

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
    yelpObject.userID = system.args[3];
    yelpObject.message = system.args[4];

    //Check for user-agent, if yes then set to the page settings
    if (system.args[5]) {
        yelpObject.userAgent = system.args[5];
        page.settings.userAgent = yelpObject.userAgent;
    }
}

//Read the command line arguments for options
readArguments();

page.onCallback = function (json) {
	if(json.method == "logged_out"){
		//Not logged in
		console.log(constants.loggedOutSessionError);
		utils.phantomExit();	
	}
	
	//Check for generic callbacks
	utils.checkGenericErrors(json.method);
}


//Steps for the phantom to follow
var steps = [common.openLogin, common.login,common.checkLoginStatus, 
function () {
        //Navigate to the messaging TAB
        page.evaluate(function () {
            document.location.href = document.querySelector(".messaging a").href;
        });
    }, function () {

        //Navigate to the review we want to reply to
        page.evaluate(function (userId) {
            
            var messageList = document.querySelectorAll('#mail_container .photo-box a');
            var userLink = "http://www.yelp.com/user_details?userid=" + userId;
            
            for (i = 0; i < messageList.length; i++) {
                if (messageList[i].href == userLink) {
                    //Get the link to conversation and redirect to it
                    var coversationHyperLink = messageList[i].parentNode.parentNode.parentNode.querySelectorAll('td')[3].querySelector('a');
                    console.log("coversationHyperLink:"+coversationHyperLink.href);
                    document.location.href = coversationHyperLink.href;
                }
            }
        }, yelpObject.userID);
    }, function () {
        page.evaluate(function (message) {
            //Enter your response here
            document.querySelector('#response_message').value = message;

            //Submit the response
            document.querySelector('#response_form').submit();
        }, yelpObject.message);
    }, function () {
        page.render('ConversationMessages/final.png');
    }
];


//Interval to execute steps
var interval = setInterval(function () {
    if (!loadInProgress && typeof steps[stepIndex] == "function") {
        steps[stepIndex]();
        stepIndex++;
    }
    if (typeof steps[stepIndex] != "function") {
        phantom.exit();
    }
}, utils.randomInterval());
