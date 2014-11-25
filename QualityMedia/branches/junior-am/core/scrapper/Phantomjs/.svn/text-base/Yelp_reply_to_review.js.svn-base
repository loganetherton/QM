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
 phantomjs Yelp_reply_to_review.js '<yelp_biz_username>' '<password>' '<review_id>' '<response_type>' '<reply_message>'
 */
var page = new WebPage();
var system = require('system');

phantom.injectJs("libs/config.ini");
phantom.injectJs("libs/utils.js");

/*Page statuses*/
page.onConsoleMessage = phantomPage.onConsoleMessage;
page.onLoadStarted = phantomPage.onLoadStarted;
page.onLoadFinished = phantomPage.onLoadFinished;
page.settings.loadImages = config.loadImages;

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

/**
 *Function to read the command line arguments
 **/

function readArguments() {
    //system.args[0] is for file name which is being executed

    if (system.args.length < 6 || system.args.length > 7) {
        console.log(utils.invalidArgumentsError);
        phantom.exit();
    }

    yelpObject.account['username'] = system.args[1];
    yelpObject.account['password'] = system.args[2];
    yelpObject.reviewID = system.args[3];

    if (system.args[4] == "public" || system.args[4] == "private") {
        yelpObject.responseType = system.args[4];
    } else {
        utils.log('Wrong reponse type. Please choose from "public" or "private".');
        phantom.exit();
    }

    yelpObject.message = system.args[5];

    //Check for user-agent, if yes then set to the page settings
    if (system.args[6]) {
        yelpObject.userAgent = system.args[6];
        page.settings.userAgent = yelpObject.userAgent;
    }
}

//Read the command line arguments for options
readArguments();

page.onCallback = function (json) {
    if (json.method == "add_compose_step") {
        steps.push(function () {
            page.evaluate(function (nextPage) {
                document.location.href = nextPage;
            }, json.url);
        });

        steps.push(composeReply);
        steps.push(publicCommentXtraStep);
        steps.push(finalStep);

    } else if (json.method == "add_find_step") {
        steps.push(function () {
            page.evaluate(function (nextPage) {
                document.location.href = nextPage;
            }, json.url);
        });

        steps.push(reviewSelector);
    } else if (json.method == "error_review_find") {
        console.log('{"error": "Could not find the message"}');
        phatom.exit();
    }
}

/**
 * Function to fill the reply and submit it
 * */
var composeReply = function () {
    page.evaluate(function (message, responseType) {
		//message = message.replace(/\\"/g, '"');
		message = message.replace(/\\/g, "");
        if (responseType == "public") {
            document.querySelector('#comment-field').value = message;
            document.querySelector('#compose_area form[name="compose_comment"]').submit()
        } else {
            document.querySelector('#response_message').value = message;
            document.querySelector('#response_form').submit();
        }
    }, yelpObject.message, yelpObject.responseType);
}

//Function to loop through the reviews and reply
var reviewSelector = function () {
    //Check for the review we want to reply to
    page.evaluate(function (reviewID, responseType) {

        //Escape characters used for non alpha-numeric characters in ID (Need reviewID for this)
        var reviewSelector = "#review\\:\\:" + reviewID;

        //For public comment: typeOfReplySelector = ".sprite-add_public_comment" , private message: typeOfReplySelector = ".sprite-message_customer";
        var typeOfReplySelector;
        if (responseType == "public") {
            typeOfReplySelector = " .sprite-add_public_comment";
        } else {
            typeOfReplySelector = " .sprite-message_customer";
        }

        //Check if the our review is present on this page
        if (document.querySelector(reviewSelector + typeOfReplySelector)) {
            var nextPageUrl = document.querySelector(reviewSelector + typeOfReplySelector).href;
            window.callPhantom({
                "method": "add_compose_step",
                "url": nextPageUrl
            });
        } else {
            //Check if there is a next page
            var paginationDiv = document.querySelector('#pagination_direction');

            if (paginationDiv && paginationDiv.innerText.indexOf("Next") > -1) {
                var pages = paginationDiv.querySelectorAll('a');
                var nextPageUrl = pages[pages.length - 1].href;
                window.callPhantom({
                    "method": "add_find_step",
                    "url": nextPageUrl
                });
            } else {
                window.callPhantom({
                    "method": "error_review_find"
                });
            }
        }
    }, yelpObject.reviewID, yelpObject.responseType);
}
var publicCommentXtraStep = function () {
    if (yelpObject.responseType == "public") {
        //One more submit remaining
        page.evaluate(function () {
            //TODO:
            //document.querySelector('#post-comment-container').submit();
        });
    } else {
        //For private message no more steps
        phantom.exit();
    }
}
var finalStep = function () {
    //End of scrapper
    phantom.exit();
}
//Steps for the phantom to follow
var steps = [
    function () {
        //Load Login Page
        page.open("https://biz.yelp.com/login");
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
        //Navigate to the reviews TAB
        page.evaluate(function () {
            document.location.href = document.querySelector(".reviews a").href;
        });
    },
    reviewSelector
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