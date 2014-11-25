//PhantomJS script to read filtered reviews
/**
 It accepts the following commands line arguments when ran from the terminal
  * @username Biz account username to be used for logging in
  * @password Password of above account
  * @password Biz id to be used while sending the JSON
  * @userAgent User agent to be used (Optional)

  Usage:
  phantomjs Yelp_read_reviews_filtered.js [yelp_biz_username] [password] [biz_id]
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


var pageCountFlag = true;
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
    reviewsObject.business_id = system.args[3]

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

        steps.push(scraperMethod);
    } else if (json.method == "review_scrapper_step") {
        steps.push(scraperMethod);
    } else if (json.method == "page_count_flag") {
        pageCountFlag = false;
    } else if(json.method == "logged_out"){
		//Not logged in
		console.log(constants.loggedOutSessionError);
		utils.phantomExit();	
	}
	
	//Check for generic callbacks
	utils.checkGenericErrors(json.method);
}

var pageScraper = function () {
    page.evaluate(function (firstPageFlag) {

        if (firstPageFlag) {
            //Call the actual scrapper
            window.callPhantom({
                "method": "review_scrapper_step"
            });
            //Check if there is a next page
            var pages = document.querySelectorAll('.pagination_controls tr td a');

            if (pages && pages.length > 0) {
                //Pagination exists
                //Add next URL to steps
                for (i = 0; i < pages.length; i++) {
                    var nextPageUrl = pages[i].href;
                    window.callPhantom({
                        "method": "add_scrapper_page",
                        "url": nextPageUrl
                    });
                }
            }

            window.callPhantom({
                "method": "page_count_flag"
            });
        }

    }, pageCountFlag);
}

var scraperMethod = function () {
    page.evaluate(function () {
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
            "content": null
        };


        var reviewsThread = document.querySelectorAll('.filtered-reviews-list .review');
        
        

        console.log("reviewsThread.length:" + reviewsThread.length)
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
            var userStatsElite= reviewsThread[i].querySelector('.user-stats > li.is-elite');
            if(userStatsElite){
            	_reviewTemplate.user.user_elite = userStatsElite.innerText;	
            }
            
            _reviewTemplate.user.user_name = reviewsThread[i].querySelector('.user-passport-info .user-name a').innerText;
            _reviewTemplate.user.user_yelp_profile = reviewsThread[i].querySelector('.user-passport-info .user-name a').href;
            _reviewTemplate.user.user_location = reviewsThread[i].querySelector('.user-passport-info .user-location').innerText;

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
            
            if(privateMessageLink && privateMessageLink.innerText.indexOf("View Message")>-1){
                //Has sent private message
                _reviewTemplate.private_msg.link = privateMessageLink.href;
                _reviewTemplate.private_msg.id = privateMessageLink.href.split('/')[privateMessageLink.href.split('/').length - 1];
            } else{
                _reviewTemplate.private_msg = {};
            }

            //Push this to the JSON
            window.callPhantom({
                "method": "append_reviews",
                "data": _reviewTemplate,
            });
        }
    });
}

//Steps for the phantom to follow
var steps = [
    common.openLogin, common.login,common.checkLoginStatus,
    function () {
        //Navigate to the reviews TAB
        page.evaluate(function () {
            document.location.href = document.querySelector(".reviews a").href;
        });
    },
    function () {
        //Check for filtered reviews
        page.evaluate(function () {
            var filtered_reviews = document.querySelector("#filtered-reviews-link");
            if (filtered_reviews) {
                console.log('Found filtered reviews');
                document.location.href = filtered_reviews.href;
            }
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
        if (config.envDev == true) {
        	var file = fs.open("JSON/"+ yelpObject.account.username +"/filtered_reviews.json", "w"); 
        	file.write(JSON.stringify(reviewsObject));
	        file.close();
	     }

        //Print to console
        console.log(JSON.stringify(reviewsObject));
        phantom.exit();
    }
}, utils.randomInterval());
