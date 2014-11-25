//PhantomJS script to reply to user messages
/**
 It accepts the following commands line arguments when ran from the terminal
  * @username Biz account username to be used for logging in
  * @password Password of above account
  * @userId  User ID of the user to whom reply is to be sent
  * @replyMessage   Message to be sent to user
  * @userAgent User agent to be used (Optional)

  Usage:
  phantomjs Yelp_reply_to_message.js '<yelp_biz_username>' '<password>' '<replyTo_user_id>' "<reply_message>"
*/
var page = new WebPage();
var system = require('system');
var stepIndex = 0,
    loadInProgress = false,
    pgImg = 0;
var yelpObject = {
    'account': {},
    'userID': null,
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

    if (system.args.length < 5 || system.args.length > 6) {
        console.log('Invalid number of arguments. Please check the arguments passed!');
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
        page.evaluate(function (username, password) {
            document.querySelector("#email").value = username;
            document.querySelector("#password").value = password;
            document.querySelector("button[type='submit']").click();
        }, yelpObject.account.username, yelpObject.account.password);
    }, function () {
        //Navigate to the messaging TAB
        page.evaluate(function () {
            document.location.href = document.querySelector(".messaging a").href;
        });
    }, function () {

        //Navigate to the review we want to reply to
        page.evaluate(function (userId) {
            //Hard coded user id
            //var userId = "BJM_CKiTDL2liFKGMjmjUw";

            var messageList = document.querySelectorAll('#mail_container .photo-box a');
            var userLink = "http://www.yelp.com/user_details?userid=" + userId;
            console.log("userlink="+userLink)

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


interval = setInterval(function () {
    if (!loadInProgress && typeof steps[stepIndex] == "function") {
        steps[stepIndex]();
        stepIndex++;
    }
    if (typeof steps[stepIndex] != "function") {
        phantom.exit();
    }
}, 100);