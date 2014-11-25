var inFilteredReviews = false;

//Template object to store reviews
var reviewsObject = {
    "business_id": null,
    "reviews": []
};

//Message scraping steps 
var messageSteps = [];
var messagesHashMap = {};

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
                    "method": "add_scrapper_page_reviews",
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
            var paging = document.querySelector('.pagination_controls table tbody tr td').children;
            for (j = 0; j < paging.length; j++) {
                if (paging[j].tagName.toLowerCase() == "span") {
                    if ((j + 2) <= paging.length) {
                        //Next page is 
                        var nextPageUrl = paging[j + 1].href;
                        window.callPhantom({
                            "method": "add_scrapper_page_reviews",
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


//Redirect to reviews
var redirectToReviews = function () {
    //Open the info page
    page.evaluate(function () {
        document.location.href = document.querySelector(".reviews a").href;
    });
};
