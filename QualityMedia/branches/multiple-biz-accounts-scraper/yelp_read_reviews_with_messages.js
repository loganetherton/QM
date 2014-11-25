//PhantomJS script to read reviews with messages
/**
 It accepts the following commands line arguments when ran from the terminal
  * @username Biz account username to be used for logging in
  * @password Password of above account
  * @password Biz id to be used while sending the JSON
  * @userAgent User agent to be used (Optional)

  Usage:
  phantomjs yelp_read_reviews_with_messages.js '<yelp_biz_username>' '<password>' '<biz_id>'
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


var stepIndex = 0,
    loadInProgress = false,
    pgImg = 0,
    inFilteredReviews = false;
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
        console.log(constants.invalidArgumentsError);
        phantom.exit();
    }

    yelpObject.account['username'] = system.args[1];
    yelpObject.account['password'] = system.args[2];
    yelpObject.account['biz_id'] = system.args[3];
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
        steps.push(scraperMethod(inFilteredReviews));

    } else if (json.method == "message_scrapper_step") {
        messageSteps.push(function () {
            page.evaluate(function (nextPage) {
                console.log('opening url for pm:' + nextPage);
                document.location.href = nextPage;
            }, json.url);
        });

        messageSteps.push(messageScraperMethod);

    } else if (json.method == "append_messages") {
        //Append messages
        messagesHashMap[json.thread_id] = json.data;

    } else if (json.method == "redirect_to_filtered_reviews") {
        steps.push(function () {
            page.evaluate(function (nextPage) {
                console.log('Redirecting to fitered reviews');
                document.location.href = nextPage;
            }, json.url);
        });

        inFilteredReviews = true;
        steps.push(pageScraper);

    } else if (json.method == "end_step") {
        utils.log("PM section start");
        //Extra check to avoid 403 mess
        steps.push(function () {
            page.evaluate(function (biz_id) {
                document.location.href = "https://biz.yelp.com/home/"+biz_id;
            },reviewsObject.business_id);
        });
        steps.push(function () {
            page.evaluate(function () {
                document.location.href = document.querySelector(".messaging a").href;
            });
        });

        steps.push(checkMessageAccess);

        //Add message scrapper steps to main STEPS array
        for (j = 0; j < messageSteps.length; j++) {
            steps.push(messageSteps[j]);
        }
    } else if (json.method == "logged_out") {
        //Not logged in
        console.log(constants.loggedOutSessionError);
        utils.phantomExit();
    } 

    //Check for generic callbacks
    utils.checkGenericErrors(json.method);
};

var pageScraper = function () {
    page.evaluate(function (isFilteredSection) {

        //Call the actual scrapper
        window.callPhantom({
            "method": "review_scrapper_step"
        });

        if (!isFilteredSection) {
            //Check if there is a next page
            var paginationDiv = document.querySelector('#pagination_direction');

            //Normal reviews section
            if (paginationDiv && paginationDiv.innerText.indexOf("Next") > -1) {
                //Pagination exists
                //Add next URL to steps
                var pages = paginationDiv.querySelectorAll('a'); //This will include both Next and previous
                var nextPageUrl = pages[pages.length - 1].href; //Choose NEXT
                window.callPhantom({
                    "method": "add_scrapper_page",
                    "url": nextPageUrl
                });
            } else {
                //Jump to filtered section
                var filteredLink = document.querySelector('#filtered-reviews-link').href;

                window.callPhantom({
                    "method": "redirect_to_filtered_reviews",
                    "url": filteredLink
                });
            }
        } else {
            //This is filtered section
            var pagingDiv = document.querySelector('.pagination_controls table tbody tr td');
            if(pagingDiv){
                var paging = document.querySelector('.pagination_controls table tbody tr td').children;
                
                for (j = 0; j < paging.length; j++) {
                    if (paging[j].tagName.toLowerCase() == "span") {
                        if ((j + 2) <= paging.length) {
                            //Next page is 
                            var nextPageUrl = paging[j + 1].href;
                            window.callPhantom({
                                "method": "add_scrapper_page",
                                "url": nextPageUrl
                            });
                        } else {
                            //end of pagination
                            window.callPhantom({
                                "method": "end_step"
                            });
                        }
                    }
                }
            }else {
                //No pagination
                window.callPhantom({
                    "method": "end_step"
                });
            }
            
        }

    }, inFilteredReviews);
};

var scraperMethod = function (isFilteredSection) {
    return function () {
        page.evaluate(function (inFilteredReviews) {

            //Review template
            var reviewTemplate = {
                "user": {},
                "review": {},
                "previous_reviews": [],
                "public_comment": {},
                "private_msg": {}
            };

            var userTemplate = {
                "user_id": null,
                "user_photo_link": null,
                "user_friend_count": null,
                "user_review_count": null,
                "user_name": null,
                "user_yelp_profile": null,
                "user_location": null,
            }

            var reviewContentTemplate = {
                "id": null,
                "star_rating": null,
                "date": null,
                "content": null,
                "is_filtered": false
            };


            //Scenario where account is not activated yet
            var isAccountConfirmed = document.querySelector('#unconfirmed_tab');
            if (isAccountConfirmed) {
                var accActivateMsg = "To activate this feature please click the link in the confirmation email you received.";
                if (isAccountConfirmed.innerText.indexOf(accActivateMsg) > -1) {
                    window.callPhantom({
                        "method": "account_not_confirmed"
                    });
                }
            }

            var reviewsThread = document.querySelectorAll('.review');
            console.log('reviewThread length=' + reviewsThread.length);

            if (inFilteredReviews) {
                reviewContentTemplate.is_filtered = true;
            }
            for (i = 0; i < reviewsThread.length; i++) {

                var _reviewTemplate = JSON.parse(JSON.stringify(reviewTemplate));
                _reviewTemplate.review = JSON.parse(JSON.stringify(reviewContentTemplate));
                _reviewTemplate.user = JSON.parse(JSON.stringify(userTemplate));

                //Latest review
                _reviewTemplate.review.id = reviewsThread[i].id.split(":")[2];
                _reviewTemplate.review.star_rating = parseInt(reviewsThread[i].querySelector('.review_info .rating i').classList[1].split('_')[1]);
                _reviewTemplate.review.date = reviewsThread[i].querySelector('.review_info .date').innerText.replace("Updated -", "");
                _reviewTemplate.review.content = reviewsThread[i].querySelector('.review_comment').innerText;
                
                //Check if user has blocked biz
                var userBlockedBiz = "This customer has asked not to be contacted by business owners";
                var review_extra_info = reviewsThread[i].querySelector('.review_extra_info').innerText;
                if (review_extra_info.indexOf(userBlockedBiz) > -1) {
                    _reviewTemplate.review.private_reply_blocked = true;
                }else{
                    _reviewTemplate.review.private_reply_blocked = false;
                }
                
                //User info
                _reviewTemplate.user.user_id = reviewsThread[i].querySelector('.user-passport-info .user-name a').href.split("=")[1];
                _reviewTemplate.user.user_photo_link = reviewsThread[i].querySelector('.user-passport .photo-box img').src;
                _reviewTemplate.user.user_friend_count = parseInt(reviewsThread[i].querySelector('.user-stats .friend-count span').innerText.replace('friends', ''));
                _reviewTemplate.user.user_review_count = parseInt(reviewsThread[i].querySelector('.user-stats .review-count span').innerText.replace('reviews', ''));
                //->Is user elite
                var userStatsElite = reviewsThread[i].querySelector('.user-stats > li.is-elite');
                if (userStatsElite) {
                    _reviewTemplate.user.user_elite = userStatsElite.innerText;
                }
                _reviewTemplate.user.user_name = reviewsThread[i].querySelector('.user-passport-info .user-name a').innerText;
                _reviewTemplate.user.user_yelp_profile = reviewsThread[i].querySelector('.user-passport-info .user-name a').href;
                _reviewTemplate.user.user_location = reviewsThread[i].querySelector('.user-passport-info .user-location').innerText;
                
                //Check if it is flagged
                _reviewTemplate.isFlagged = reviewsThread[i].querySelector('.review_extra_info .actions .already-flagged') ? true : false;


                //Check for previous reviews
                if (reviewsThread[i].querySelector('.archived_reviews')) {
                    console.log('There is a previous review:' + i);
                    //There is also a previous review
                    var prevReview = reviewsThread[i].querySelector('.archived_reviews ul,stripped li').querySelectorAll('li');
                    var _reviewContentTemplate = JSON.parse(JSON.stringify(reviewContentTemplate));

                    _reviewContentTemplate.id = reviewsThread[i].querySelector('.archived_reviews').id.split(":")[1];

                    for (j = 0; j < prevReview.length; j++) {
                        _reviewContentTemplate.star_rating = parseInt(prevReview[j].querySelector('.rating-small i').classList[1].split('_')[1]);
                        _reviewContentTemplate.date = prevReview[j].querySelector('em').innerText.replace("Updated -", "");
                        _reviewContentTemplate.content = prevReview[j].querySelector('.review_comment').innerText;
                        _reviewTemplate.previous_reviews.push(JSON.parse(JSON.stringify(_reviewContentTemplate)));
                    }

                } else {
                    _reviewTemplate.previous_reviews = [];
                }


                //Check for public comments
                if (reviewsThread[i].querySelector('.index-inline-comment .review-comment')) {
                    var publicComment = reviewsThread[i].querySelector('.index-inline-comment .review-comment');

                    _reviewTemplate.public_comment.date = publicComment.querySelector('.date').innerText;
                    _reviewTemplate.public_comment.from = publicComment.querySelector('.attribution').innerText.replace(_reviewTemplate.public_comment.date, '');
                    _reviewTemplate.public_comment.comment = publicComment.querySelector('.full').innerText;
                } else {
                    _reviewTemplate.public_comment = {};
                }


                //Check for private message
                var privateMessageLink = reviewsThread[i].querySelector('.review_extra_info').querySelector('a,.sprite-message_customer');

                if (privateMessageLink && privateMessageLink.innerText.indexOf("View Message") > -1) {
                    //Has sent private message
                    //Add a new method to the scrapper
                    _reviewTemplate.private_msg.link = privateMessageLink.href;
                    _reviewTemplate.private_msg.id = privateMessageLink.href.split('/')[privateMessageLink.href.split('/').length - 1];

                    window.callPhantom({
                        "method": "message_scrapper_step",
                        "url": privateMessageLink.href,
                    });

                } else {
                    _reviewTemplate.private_msg = {};
                }


                //Put the first review at root and move the latest review to the beginning of array
                if (_reviewTemplate.previous_reviews.length > 0) {
                    var firstReview = _reviewTemplate.previous_reviews.pop();
                    _reviewTemplate.previous_reviews.unshift(_reviewTemplate.review);
                    _reviewTemplate.review = firstReview;
                }


                //Push this to the JSON
                window.callPhantom({
                    "method": "append_reviews",
                    "data": _reviewTemplate,
                });
            }

        }, isFilteredSection);
    }
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

        //Check for forbidden error
        var header = document.querySelector('#mastHeadUser');
        if (!header) {
            var pageText = document.querySelector('body').innerText;
            if (pageText.indexOf("You don't have permission to access") > -1) {
                console.log('Fobidden access error found');
                window.callPhantom({
                    "method": "forbidden_message_page"
                });
            }
        }

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
            if (!tempR2RThread[i].classList.contains('original_message')) {

                //Conversation message
                tempConversationTemplate.message = JSON.parse(JSON.stringify(convMsgTemplate));
                tempConversationTemplate.from = tempR2RThread[i].querySelector('.message_user').innerText;

                var msgContentDOM = tempR2RThread[i].querySelector('.message_content');
                if (msgContentDOM.classList.contains('message_review')) {

                    //Public comment on the review
                    try {
                        //Check if its been removed
                        var removalCheck = msgContentDOM.querySelector(".padding > em.removed-review-comment");
                        if (!removalCheck) {
                            tempConversationTemplate.type = "comment";
                            var tempHeading = msgContentDOM.querySelector('.padding p').innerText;
                            tempConversationTemplate.message.content = msgContentDOM.querySelector('.padding').innerText.replace(tempHeading, "");

                            //Push this to the messages Array
                            tempMsgTemplate['conversations'].push(tempConversationTemplate);
                        }
                    } catch (err) {
                        console.log('Error in DOM parsing at public comment');
                    }
                } else {
                    tempConversationTemplate.type = "message";
                    tempConversationTemplate.message.time = msgContentDOM.querySelector('em').innerText;
                    tempConversationTemplate.message.content = msgContentDOM.innerText.replace(tempConversationTemplate.message.time, "");

                    //Push this to the messages Array
                    tempMsgTemplate['conversations'].push(tempConversationTemplate);
                }


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
            reviewsObject.reviews[i].private_msg.message = messagesHashMap[reviewsObject.reviews[i].private_msg.id];
        }
    }
};

/**
 * Method to check for 403 page before starting message scrapping
 */
var checkMessageAccess = function () {
    //Navigate to the reviews TAB
    page.evaluate(function () {
        var accessMessage = "Please update your name before messaging your customers.";
        var disabledMessaging = "The ability to message customers has been disabled for this account.";
        var bodyText = document.querySelector('body').innerText;
        
        if (bodyText.indexOf(accessMessage) > -1 || bodyText.indexOf(disabledMessaging) > -1) {
            window.callPhantom({
                "method": "forbidden_message_page"
            });
        }
    });
};


//Steps for the phantom to follow
var steps = [
    common.openLogin, common.login, common.checkLoginStatus,common.multiBizRedirect(yelpObject.account.biz_id),

    function () {
        //Navigate to the reviews TAB
        page.evaluate(function () {
            document.location.href = document.querySelector(".reviews a").href;
        });
    },
    pageScraper
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

        if (config.envDev == true) {
            var file = fs.open("JSON/" + yelpObject.account.username + "/reviews_with_messages.json", "w");
            file.write(JSON.stringify(reviewsObject));
            file.close();
        }

        //Print to console
        console.log(JSON.stringify(reviewsObject));
        phantom.exit();
    }
}, utils.randomInterval());
