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
        console.log(utils.invalidArgumentsError);
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
    }
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
            "review_previous": {}
        }


        var reviewsThread = document.querySelectorAll('.filtered-reviews-list .review');
        var _reviewTemplate = JSON.parse(JSON.stringify(reviewTemplate));

        console.log("reviewsThread.length:" + reviewsThread.length)
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

                _reviewTemplate.review_previous.review_id = prevReview.id.split(":")[1];
                _reviewTemplate.review_previous.review_star_rating = parseInt(prevReview.querySelector('.rating-small i').classList[1].split('_')[1]);
                _reviewTemplate.review_previous.review_date = prevReview.querySelector('em').innerText;
                _reviewTemplate.review_previous.review_content = reviewsThread[i].querySelector('.review_comment').innerText;
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
        var file = fs.open("DATA_JSON/filtered_reviews_json.txt", "w");
        file.write(JSON.stringify(reviewsObject));
        file.close();

        //Print to console
        console.log(JSON.stringify(reviewsObject));
        phantom.exit();
    }
}, utils.randomInterval());