//PhantomJS script to reply to reviews
/**
 It accepts the following commands line arguments when ran from the terminal
 * @username Biz account username to be used for logging in
 * @password Password of above account
 * @biz_id Biz id for the account
 * @reviewID  ID of the review to which message is to be sent
 * @responseType  type of reponse, public comment or private message or flag
 * @replyMessage   Message to be sent to user/Falgging details
 * @flagReason  Reason for flagging (in case of flagging a review)
 * @userAgent User agent to be used (Optional)

 Usage:
 phantomjs Yelp_reply_to_review.js '<yelp_biz_username>' '<password>' '<biz_id>' '<review_id>' '<response_type>' '<reply_message>'
 phantomjs Yelp_reply_to_review.js '<yelp_biz_username>' '<password>' '<biz_id>' '<review_id>' '<response_type>' '<reply_message>' '<flag_reason>'
 */
var page = new WebPage();
var system = require('system');
var fs = require('fs');

phantom.injectJs("libs/config.ini");
phantom.injectJs("libs/utils.js");

/*Page statuses*/
page.onConsoleMessage    = phantomPage.onConsoleMessage;
page.onLoadStarted       = phantomPage.onLoadStarted;
page.onLoadFinished      = phantomPage.onLoadFinished;
page.onResourceRequested = phantomPage.onResourceRequested;
page.settings.loadImages = config.loadImages;

var stepIndex = 0,
    loadInProgress = false,
    pgImg = 0,
    searchInFiltered = false;
var yelpObject = {
    'account': {},
    'reviewID': null,
    'responseType': null,
    'message': null,
    'userAgent': null,
    'review_url': null,
    'flagReason': null
};


/**
 *Function to read the command line arguments
 **/

function readArguments() {
    //system.args[0] is for file name which is being executed

    if (system.args.length < 7 || system.args.length > 9) {
        console.log(constants.invalidArgumentsError);
        utils.phantomExit();
    }

    yelpObject.account['username'] = system.args[1];
    yelpObject.account['password'] = system.args[2];
    yelpObject.account['biz_id'] = system.args[3];
    
    yelpObject.reviewID = system.args[4];

    if (system.args[5] == "public" || system.args[5] == "private") {
        yelpObject.responseType = system.args[5];
    } else if (system.args[5] == "flag") {
        yelpObject.responseType = system.args[5];
        yelpObject.flagReason = system.args[7];
    } else {
        utils.log('Wrong reponse type. Please choose from "public","private" or "flag".');
        utils.phantomExit();
    }

    yelpObject.message = system.args[6];
    if (yelpObject.message.length <= 0) {
        console.log(constants.blankReplyContentError);
        utils.phantomExit();
    }

    //Check for user-agent, if yes then set to the page settings
    if (system.args[5] == "flag" && system.args[8]) {
        yelpObject.userAgent = system.args[8];
        page.settings.userAgent = yelpObject.userAgent;
    } else if (system.args[7]) {
        yelpObject.userAgent = system.args[7];
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

        //Store the page URL for verifying public comment
        yelpObject.review_url = json.current_url;

        steps.push(composeReply);
        steps.push(publicCommentXtraStep);
        steps.push(verificationStep);
        steps.push(finalStep);
    } else if (json.method == "add_find_step") {
        steps.push(function () {
            page.evaluate(function (nextPage) {
                document.location.href = nextPage;
            }, json.url);
        });

        steps.push(reviewSelector);
    } else if (json.method == "verify_public_comment") {
        var tempFinalStep = steps.pop();
        steps.push(function () {
            page.evaluate(function (nextPage) {
                document.location.href = nextPage;
            }, json.url);
        });
        steps.push(publicCommentVerification);
        steps.push(tempFinalStep);
    } else if (json.method == "post_result") {
        utils.log('{"status":"' + json.status + '","is_filtered":"' + searchInFiltered + '"}');
        console.log('{"status":"' + json.status + '","is_filtered":"' + searchInFiltered + '"}');
        utils.phantomExit();
    } else if (json.method == "error_review_find") {
        //Review not found
        console.log(constants.reviewNotFoundError);
        utils.phantomExit();
    } else if (json.method == "no_photo_error") {
        //Owner photo not present
        console.log(constants.ownerPhotoError);
        utils.phantomExit();
    } else if (json.method == "invalid_photo_error") {
        //Invalid owner photo , face needs to be clear in photo :P
        console.log(constants.invalidOwnerPhotoError);
        utils.phantomExit();
    } else if (json.method == "update_name_error"){
        //Owner needs to update name
        console.log(constants.updateNameError);
        utils.phantomExit();
    }else if (json.method == "check_filtered_reviews") {
        searchInFiltered = true;
        steps.push(function () {
            page.evaluate(function (nextPage) {
                document.location.href = nextPage;
            }, json.url);
        });
        steps.push(reviewSelector);
    } else if (json.method == "daily_limit_reached") {
        //Daily limit reached
        console.log(constants.dailyLimitError);
        utils.phantomExit();
    } else if (json.method == "already_replied") {
        console.log(constants.reviewAlreadyRepliedError);
        utils.phantomExit();
    } else if (json.method == "already_flagged") {
        console.log(constants.reviewAlreadyFlaggedError);
        utils.phantomExit();
    } else if (json.method == "user_blocked_biz") {
        console.log(constants.bizBlockedByUserError);
        utils.phantomExit();
    } else if (json.method == "logged_out") {
        //Not logged in
        console.log(constants.loggedOutSessionError);
        utils.phantomExit();
    }

    //Check for generic callbacks
    utils.checkGenericErrors(json.method);
}
/**
 * Function to fill the reply and submit it
 * */
var composeReply = function () {
    page.evaluate(function (message, responseType, flagReason) {
        //message = message.replace(/\\"/g, '"');
        message = message.replace(/\\/g, "");

        //Check to see if photos error is there
        var noPhotoMsgCheck = document.querySelector('body').innerText.indexOf('Please upload a photo of yourself.');
        var invalidPhotoMsgCheck = document.querySelector('body').innerText.indexOf('Please upload a new photo of yourself.');
        var noNameMsgCheck = document.querySelector('body').innerText.indexOf('Please update your name before messaging your customers.');

        if (noPhotoMsgCheck > -1) {
            window.callPhantom({
                "method": "no_photo_error"
            });
        } else if (invalidPhotoMsgCheck > -1) {
            window.callPhantom({
                "method": "invalid_photo_error"
            });
        } else if (noNameMsgCheck > -1) {
            window.callPhantom({
                "method": "update_name_error"
            });
        } else {
            //Fill the response fields
            if (responseType == "public") {
                document.querySelector('#comment-field').value = message;
                document.querySelector('#compose_area form[name="compose_comment"]').submit()
            } else if (responseType == "private") {
                document.querySelector('#response_message').value = message;
                document.querySelector('#response_form').submit();
            } else if (responseType == "flag") {
                document.querySelector('.js-reason-select').value = flagReason;
                document.querySelector('#message-field').value = message;
                document.querySelector('#submit').click();
            }
        }
    }, yelpObject.message, yelpObject.responseType, yelpObject.flagReason);
};

//Function to loop through the reviews and reply
var reviewSelector = function () {
    //Check for the review we want to reply to
    page.evaluate(function (reviewID, responseType, isFilteredSection) {

        //Escape characters used for non alpha-numeric characters in ID (Need reviewID for this)
        var reviewSelector = "#review\\:\\:" + reviewID;

        //For public comment: typeOfReplySelector = ".sprite-add_public_comment" , private message: typeOfReplySelector = ".sprite-message_customer";
        var typeOfReplySelector;
        if (responseType == "public") {
            typeOfReplySelector = " .sprite-add_public_comment";
        } else if (responseType == "private") {
            typeOfReplySelector = " .sprite-message_customer";
        } else if (responseType == "flag") {
            typeOfReplySelector = " .flag-content";
        }

        //Check if the our review is present on this page
        if (document.querySelector(reviewSelector)) {
            //Check if button is present(for public and private replies)
            if (responseType == "public" || responseType == "private") {
                var button = document.querySelector(reviewSelector + typeOfReplySelector);
                if (button) {
                    var nextPageUrl = document.querySelector(reviewSelector + typeOfReplySelector).href;
                    window.callPhantom({
                        "method": "add_compose_step",
                        "url": nextPageUrl,
                        "current_url": document.location.href
                    });
                } else {
                    //Desired buttons missing due to limit/other reason
                    if (responseType == "public") {
                        if (document.querySelector(reviewSelector).querySelector('.index-inline-comment')) {
                            //Already replied
                            window.callPhantom({
                                "method": "already_replied"
                            });
                        }
                    } else if (responseType == "private") {
                        var alreadyMessagedText = "You have already messaged this user";
                        var userBlockedBiz = "This customer has asked not to be contacted by business owners";
                        var review_extra_info = document.querySelector(reviewSelector).querySelector('.review_extra_info').innerText;

                        if (review_extra_info.indexOf(alreadyMessagedText) > -1) {
                            //Already replied
                            window.callPhantom({
                                "method": "already_replied"
                            });
                        } else if (review_extra_info.indexOf(userBlockedBiz) > -1) {
                            //user asked not to be contacted
                            window.callPhantom({
                                "method": "user_blocked_biz"
                            });
                        }
                    }

                    //Daily limit reached
                    window.callPhantom({
                        "method": "daily_limit_reached"
                    });
                }

            } else if (responseType == "flag") {    
                //Check if already flagged
                if (document.querySelector(reviewSelector).querySelector('.already-flagged')) {
                    window.callPhantom({
                        "method": "already_flagged"
                    });
                } else {
                    var nextPageUrl = document.querySelector(reviewSelector + typeOfReplySelector).href;
                    window.callPhantom({
                        "method": "add_compose_step",
                        "url": nextPageUrl,
                        "current_url": document.location.href
                    });
                }
            }

        } else {
            //Check if there is a next page
            if (!isFilteredSection) {
                var paginationDiv = document.querySelector('#pagination_direction');

                if (paginationDiv && paginationDiv.innerText.indexOf("Next") > -1) {
                    var pages = paginationDiv.querySelectorAll('a');
                    var nextPageUrl = pages[pages.length - 1].href;
                    window.callPhantom({
                        "method": "add_find_step",
                        "url": nextPageUrl
                    });
                } else {
                    var filteredLink = document.querySelector('#filtered-reviews-link').href;
                    //Go check in filtered review
                    window.callPhantom({
                        "method": "check_filtered_reviews",
                        "url": filteredLink
                    });
                }
            } else {
                //This is filtered section
               	var pagingDiv = document.querySelector('.pagination-block');
				var pageLinks = Array.prototype.slice.call(pagingDiv.querySelectorAll('.pagination-links li'));
				
				//pagination-block
				if(pagingDiv){
					if( pageLinks.length > 0 && pageLinks[pageLinks.length-1].innerText === "→"){
					//Next page exists
						var nextPageUrl = pageLinks[pageLinks.length-1].querySelector('a').href;
						window.callPhantom({
							"method": "add_find_step",
							"url": nextPageUrl
						});
					}else{
						//end of pagination
						window.callPhantom({
							"method": "error_review_find"
						});
					}
				}else {
					//No pagination
					window.callPhantom({
						"method": "error_review_find"
					});
				}
            }

        }
    }, yelpObject.reviewID, yelpObject.responseType, searchInFiltered);
};

var publicCommentXtraStep = function () {
    if (yelpObject.responseType == "public") {
        //One more submit remaining
        page.evaluate(function () {
            document.querySelector('#post-comment-container').submit();
        });
    }
};

var verificationStep = function () {
    page.evaluate(function (responseType, replyContent, url) {
        var success = false;
        if (responseType == "public") {
            window.callPhantom({
                "method": "verify_public_comment",
                "url": url
            });
        } else {
            if (responseType == "private") {
                //Private message	- 1st element is user review, 2nd is biz response
                var msgTime = document.querySelectorAll('.mail_message .message_content em')[1];

                if (msgTime && msgTime.innerText == "A moment ago") {
                    //Now check for message content - again the 1st element is user review, 2nd is biz response
                    var msg = document.querySelectorAll('.mail_message .message_content')[1].innerText.replace(msgTime.innerText, "").trim();
                    //Trim used to remove un-necessary spaces
                    success = true;
                }
            } else if (responseType == "flag") {
                var infoNotice = document.querySelector('.info-notice .i-exclamation-common-wrap');
                if (infoNotice) {
                    success = true;
                }
            }

            if (success) {
                window.callPhantom({
                    "method": "post_result",
                    "status": "success"
                });
            } else {
                window.callPhantom({
                    "method": "post_result",
                    "status": "error"
                });
            }

        }

    }, yelpObject.responseType, yelpObject.message, yelpObject.review_url);
};

var publicCommentVerification = function () {

    page.evaluate(function (reviewID) {
        var reviewCommentSelector = "#review\\:\\:" + reviewID + " .index-inline-comment";
        var success = false;

        var review = document.querySelector(reviewCommentSelector);

        if (review) {
            //Comment exists
            success = true;
        }

        if (success) {
            window.callPhantom({
                "method": "post_result",
                "status": "success"
            });
        } else {
            window.callPhantom({
                "method": "post_result",
                "status": "error"
            });
        }
    }, yelpObject.reviewID);

};

var finalStep = function () {
    //End of scrapper
    utils.phantomExit();
};


//Steps for the phantom to follow
var steps = [common.openLogin, common.login, common.checkLoginStatus,common.multiBizRedirect(yelpObject.account.biz_id),

    function () {
        //Navigate to the reviews TAB
        page.evaluate(function () {
            document.location.href = document.querySelector(".biz_site_review a").href;
        });
    },
    reviewSelector];

//Interval to execute steps
var interval = setInterval(function () {
    if (!loadInProgress && typeof steps[stepIndex] == "function") {
        steps[stepIndex]();
        stepIndex++;
    }
    if (typeof steps[stepIndex] != "function") {
        utils.phantomExit();
    }
}, utils.randomInterval());
