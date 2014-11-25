//PhantomJS script to read reviews with messages
/**
 It accepts the following commands line arguments when ran from the terminal
  * @username Biz account username to be used for logging in
  * @password Password of above account
  * @password Biz id to be used while sending the JSON
  * @userAgent User agent to be used (Optional)

  Usage:
  phantomjs yelp_read_reviews_with_messages.js '<yelp_biz_username>' '<password>' '<biz_id'
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
    'account': {},
    'userAgent': null
};

//Template object to store reviews
var reviewsObject = {
    "business_id": null,
    "reviews": []
};

//Message scraping steps 
var messageSteps = [];
var messagesHashMap = {};


/**
 *Function to read the command line arguments
 **/

function readArguments() {
    //system.args[0] is for file name which is being executed

    if (system.args.length < 4 || system.args.length > 5) {
        console.log(utils.invalidArgumentsError);
        phantom.exit();
    }

    yelpObject.account['username'] = system.args[1];
    yelpObject.account['password'] = system.args[2];
    reviewsObject.business_id = system.args[3];

    //Check for user-agent, if yes then set to the page settings
    if (system.args[4]) {
        yelpObject.userAgent = system.args[4];
        page.settings.userAgent = yelpObject.userAgent;
    }
}
//Read the command line arguments for options
readArguments();

page.onCallback = function (json) {
    if (json.method == "append_reviews") {
        //Append messages
        reviewsObject.reviews.push(json.data);


    } else if (json.method == "add_scrapper_page") {
        steps.push(function () {
            page.evaluate(function (nextPage) {
                document.location.href = nextPage;
            }, json.url);
        });

        steps.push(pageScraper);

    } else if (json.method == "review_scrapper_step") {
        steps.push(scraperMethod);

    } else if (json.method == "message_scrapper_step") {
        messageSteps.push(function () {
            page.evaluate(function (nextPage) {
                document.location.href = nextPage;
            }, json.url);
        });

        messageSteps.push(messageScraperMethod);

    } else if (json.method == "append_messages") {
        //Append messages
        messagesHashMap[json.thread_id] = json.data;

    } else if (json.method == "check_if_last_review") {
        //C
        if (typeof steps[stepIndex + 1] != "function") {
            //Add message scrapper steps to main STEPS array
            for (j = 0; j < messageSteps.length; j++) {
                steps.push(messageSteps[j]);
            }
        }
    }
};

var pageScraper = function () {
    page.evaluate(function () {

        //Call the actual scrapper
        window.callPhantom({
            "method": "review_scrapper_step"
        });

        //Check if there is a next page
        var paginationDiv = document.querySelector('#pagination_direction');

        if (paginationDiv && paginationDiv.innerText.indexOf("Next") > -1) {
            //Pagination exists
            //Add next URL to steps
            var pages = paginationDiv.querySelectorAll('a'); //This will include both Next and previous
            var nextPageUrl = pages[pages.length - 1].href; //Choose NEXT
            window.callPhantom({
                "method": "add_scrapper_page",
                "url": nextPageUrl
            });
        }
    });
};

var scraperMethod = function () {
    page.evaluate(function () {
        //Review template
        var reviewTemplate = {
            "review_id": null,
            "review_star_rating": null,
            "review_date": null,
            "review_content": null,
            "review_user_id": null,
            "review_user_photo_link": null,
            "review_user_friend_count": null,
            "review_user_review_count": null,
            "review_user_name": null,
            "review_user_yelp_profile": null,
            "review_user_location": null,
            "review_previous": {},
            "review_pub_comment": {},
            "review_private_msg": {}
        }


        var reviewsThread = document.querySelectorAll('.review');
        var _reviewTemplate = JSON.parse(JSON.stringify(reviewTemplate));

        for (i = 0; i < reviewsThread.length; i++) {

            _reviewTemplate.review_id = reviewsThread[i].id.split(":")[2];
            _reviewTemplate.review_star_rating = parseInt(reviewsThread[i].querySelector('.review_info .rating i').classList[1].split('_')[1]);
            _reviewTemplate.review_date = reviewsThread[i].querySelector('.review_info .date').innerText;
            _reviewTemplate.review_content = reviewsThread[i].querySelector('.review_comment').innerText;
            //_reviewTemplate.review_isPrevious = reviewsThread[i]

            _reviewTemplate.review_user_id = reviewsThread[i].querySelector('.user-passport-info .user-name a').href.split("=")[1];
            _reviewTemplate.review_user_photo_link = reviewsThread[i].querySelector('.user-passport .photo-box img').src;
            _reviewTemplate.review_user_friend_count = parseInt(reviewsThread[i].querySelector('.user-stats .friend-count span').innerText.replace('friends', ''));
            _reviewTemplate.review_user_review_count = parseInt(reviewsThread[i].querySelector('.user-stats .review-count span').innerText.replace('reviews', ''));
            _reviewTemplate.review_user_name = reviewsThread[i].querySelector('.user-passport-info .user-name a').innerText;
            _reviewTemplate.review_user_yelp_profile = reviewsThread[i].querySelector('.user-passport-info .user-name a').href;
            _reviewTemplate.review_user_location = reviewsThread[i].querySelector('.user-passport-info .user-location').innerText;

            //Check for previous reviews
            if (reviewsThread[i].querySelector('.archived_reviews')) {
                //There is also a previous review
                var prevReview = reviewsThread[i].querySelector('.archived_reviews');

                _reviewTemplate.review_previous.id = prevReview.id.split(":")[1];
                _reviewTemplate.review_previous.star_rating = parseInt(prevReview.querySelector('.rating-small i').classList[1].split('_')[1]);
                _reviewTemplate.review_previous.date = prevReview.querySelector('em').innerText;
                _reviewTemplate.review_previous.content = reviewsThread[i].querySelector('.review_comment').innerText;
            }


            //Check for public comments
            if (reviewsThread[i].querySelector('.index-inline-comment .review-comment')) {
                var publicComment = reviewsThread[i].querySelector('.index-inline-comment .review-comment');

                _reviewTemplate.review_pub_comment.date = publicComment.querySelector('.date').innerText;
                _reviewTemplate.review_pub_comment.from = publicComment.querySelector('.attribution').innerText.replace(_reviewTemplate.review_pub_comment.date, '');
                _reviewTemplate.review_pub_comment.comment = publicComment.querySelector('.full').innerText;
            } else {
                _reviewTemplate.review_pub_comment = {};
            }


            //Check for private message
            var privateMessageLink = reviewsThread[i].querySelector('.review_extra_info').querySelector('a,.sprite-message_customer');

            if (privateMessageLink.innerText.indexOf("View Message") > -1) {
                //Has sent private message
                //Add a new method to the scrapper
                _reviewTemplate.review_private_msg.link = privateMessageLink.href;
                _reviewTemplate.review_private_msg.id = privateMessageLink.href.split('/')[privateMessageLink.href.split('/').length - 1];

                window.callPhantom({
                    "method": "message_scrapper_step",
                    "url": privateMessageLink.href,
                });

            } else {
                _reviewTemplate.review_private_msg = {};
            }

            //Push this to the JSON
            window.callPhantom({
                "method": "append_reviews",
                "data": _reviewTemplate,
            });
        }

        //Check if this is the last review
        window.callPhantom({
            "method": "check_if_last_review"
        });
    });
};


/**
 *Method to scrape the messages from particular conversaation
 */
var messageScraperMethod = function () {
    page.evaluate(function () {

        // Array to hold all the conversations
        var messageTemplate = {
            "user_yelp_id": null,
            "user_name": null,
            "user_image_url": null,
            "message_subject": null,
            "message_excerpt": null,
            "last_message_time": null,
            "thread_id": null,
            "conversations": []
        }

        var conversationsTemplate = {
            "from": null,
            "type": null,
        };
        var reviewMsgTemplate = {
            "heading": null,
            "rating": null,
            "time": null,
            "content": null
        };
        var convMsgTemplate = {
            "time": null,
            "content": null
        };


        var tempMsgTemplate = messageTemplate;

        var tempR2RThread = document.querySelectorAll("#r2r_thread .mail_message");

        tempMsgTemplate.user_yelp_id = tempR2RThread[0].querySelector('.message_user .photo-box a').href.split('?')[1].split('=')[1];
        tempMsgTemplate.user_name = tempR2RThread[0].querySelectorAll('.message_user a')[1].innerText;
        tempMsgTemplate.user_image_url = tempR2RThread[0].querySelector('.message_user .photo-box img').src;

        var splitPageUrl = document.location.href.split('/');
        tempMsgTemplate.thread_id = splitPageUrl[splitPageUrl.length - 1];

        var headingPart1 = tempR2RThread[0].querySelectorAll('.message_content a')[0].innerText;
        var headingPart2 = tempR2RThread[0].querySelectorAll('.message_content a')[1].innerText;
        tempMsgTemplate.message_subject = headingPart1 + " review of " + headingPart2;


        //Loop through all the threads to fill in the messages
        for (i = 0; i < tempR2RThread.length; i++) {
            var tempConversationTemplate = JSON.parse(JSON.stringify(conversationsTemplate));
            if (tempR2RThread[i].classList.contains('original_message')) {
                //Review message
                tempConversationTemplate.message = JSON.parse(JSON.stringify(reviewMsgTemplate));

                tempConversationTemplate.from = tempR2RThread[i].querySelectorAll('.message_user a')[1].innerText;
                tempConversationTemplate.type = "review";

                var headingPart1 = tempR2RThread[i].querySelectorAll('.message_content a')[0].innerText;
                var headingPart2 = tempR2RThread[i].querySelectorAll('.message_content a')[1].innerText;
                tempConversationTemplate.message.heading = headingPart1 + " review of " + headingPart2;
                tempConversationTemplate.message.rating = parseInt(tempR2RThread[i].querySelector('.message_content .review_wrapper .rating_wrapper .rating i').title.slice(0, 1));
                tempConversationTemplate.message.time = tempR2RThread[i].querySelector('.message_content .review_wrapper .rating_wrapper .review_time').innerText;
                tempConversationTemplate.message.content = tempR2RThread[i].querySelector('.message_content .review_wrapper .review_text').innerText;

                //TODO:push this to the messages Array
                tempMsgTemplate['conversations'].push(tempConversationTemplate);
            } else {

                //Conversation message
                tempConversationTemplate.message = JSON.parse(JSON.stringify(convMsgTemplate));
                tempConversationTemplate.from = tempR2RThread[i].querySelector('.message_user').innerText;

                var msgContentDOM = tempR2RThread[i].querySelector('.message_content');
                if (msgContentDOM.classList.contains('message_review')) {
                    //Public comment on the review
                    try {
                        tempConversationTemplate.type = "comment";
                        var tempHeading = msgContentDOM.querySelector('.padding p').innerText;
                        tempConversationTemplate.message.content = msgContentDOM.querySelector('.padding').innerText.replace(tempHeading, "");
                    } catch (err) {
                        //console.log('Error in DOM parsing');
                    }
                } else {
                    tempConversationTemplate.type = "message";
                    tempConversationTemplate.message.time = msgContentDOM.querySelector('em').innerText;
                    tempConversationTemplate.message.content = msgContentDOM.innerText.replace(tempConversationTemplate.message.time, "");
                }

                //Ppush this to the messages Array
                tempMsgTemplate['conversations'].push(tempConversationTemplate);
            }
        }

        window.callPhantom({
            "method": "append_messages",
            "data": tempMsgTemplate,
            "thread_id": tempMsgTemplate.thread_id
        });

    });
};




/**
 * Method to link the private messages to respective reviews
 */
var linkReviewMessages = function () {

    for (i = 0; i < reviewsObject.reviews.length; i++) {
        if (!utils.objectIsEmpty(reviewsObject.reviews[i])) {
            //Object is not empty
            reviewsObject.reviews[i].review_private_msg.message = messagesHashMap[reviewsObject.reviews[i].review_private_msg.id];
        }
    }
};



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
    pageScraper,
];



//Interval to execute steps
var interval = setInterval(function () {
    if (!loadInProgress && typeof steps[stepIndex] == "function") {
        steps[stepIndex]();
        stepIndex++;
    }

    if (typeof steps[stepIndex] != "function") {
        //Link messages and reviews
        linkReviewMessages();

        var file = fs.open("reviews_with_messages.json", "w");
        file.write(JSON.stringify(reviewsObject));
        file.close();
		
		//Print to console
		console.log(JSON.stringify(reviewsObject));
        
        phantom.exit();
    }
}, utils.randomInterval());