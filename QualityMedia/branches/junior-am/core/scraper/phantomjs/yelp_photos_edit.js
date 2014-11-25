//PhantomJS script to edit a photo(change caption,delete ,flag)
/**
 It accepts the following commands line arguments when ran from the terminal
 * @username Biz account username to be used for logging in
 * @password Password of above account
 * @biz_id Business identifier
 * @photo_id ID of the photo
 * @action Action to be performed on the photo (delete , flag , add_caption , edit_caption)
 * @content  Content for the action (Case where action is flag , add_caption , edit_caption)
 * @userAgent User agent to be used (Optional)

 Usage:
 phantomjs yelp_photos_edit.js '<yelp_biz_username>' '<password>' '<biz_id>' '<photo_id>' '<action>'
 phantomjs yelp_photos_edit.js '<yelp_biz_username>' '<password>' '<biz_id>' '<photo_id>' '<action>' '<content>'
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
    pgImg = 0;
var yelpObject = {
    "account": {},
    "userAgent": null
};

var editPhoto = {
    business_id: null,
    photo_id: null,
    action: null,
    content: null
}

/**
 *Function to read the command line arguments
 **/

    function readArguments() {
        //system.args[0] is for file name which is being executed

        if (system.args.length < 6 || system.args.length > 8) {
            console.log(constants.invalidArgumentsError);
            phantom.exit();
        }

        yelpObject.account['username'] = system.args[1];
        yelpObject.account['password'] = system.args[2];
        yelpObject.account['biz_id'] = system.args[3];
        editPhoto.business_id = system.args[3];
        editPhoto.photo_id = system.args[4];
        editPhoto.action = system.args[5];

        if (editPhoto.action.indexOf('caption') > -1 || editPhoto.action == 'flag') {
            //Action is related to caption,so read it
            editPhoto.content = system.args[6];

            //Check for user-agent, if yes then set to the page settings
            if (system.args[7]) {
                yelpObject.userAgent = system.args[7];
                page.settings.userAgent = yelpObject.userAgent;
            }

        } else {
            //Check for user-agent, if yes then set to the page settings
            if (system.args[6]) {
                yelpObject.userAgent = system.args[6];
                page.settings.userAgent = yelpObject.userAgent;
            }
        }
    }

    //Read the command line arguments for options
readArguments();

page.onCallback = function (json) {
    if (json.method == "add_flag_scrapper") {
        steps.push(function () {
            page.evaluate(function (content) {
                //Fill flag form & submit it
                window.callPhantom({
                            "method": "add_verification_step",
                            "action": "flag"
                        });
                document.querySelector('#message-field').value = content;
                //document.querySelector('#flag-form').submit();
                document.querySelector('#submit').click()
            }, json.content);
        });
        steps.push(function () {
            page.evaluate(function () {
                //Empty step to complete the last form submit
            });
        });
    } else if (json.method == "add_scrapper_page") {
        steps.push(function () {
            page.evaluate(function (nextPage) {
                document.location.href = nextPage;
            }, json.url);
        });

        if (json.type == "photo") {
            steps.push(editPhotoScrapper);
        } else {
            if (json.home_page && json.home_page == true) {
                //One more click on edit button for first time
                steps.push(function () {
                    page.evaluate(function () {
                        document.location.href = document.querySelector('#bizAdTabContent .edit').href;
                    });
                });

                steps.push(editSlidesScrapper);
            } else {
                steps.push(editSlidesScrapper);
            }
        }
    } else if (json.method == "add_verification_step") {
        steps.push(verificationStep(json.action));
    } else if (json.method == "status") {
        var result = '{"status":"' + json.status + '"}';
        utils.log("Response="+result);
        console.log(result);
        utils.phantomExit();
    } else if (json.method == "finish_step") {
        steps.push(function () {
            page.evaluate(function () {
                //Empty step to complete the last form submit
            });
        });
    } else if (json.method == "logged_out") {
        //Not logged in
        console.log(constants.loggedOutSessionError);
        utils.phantomExit();
    } else if (json.method == "photo_not_found") {
        //photo not found
        console.log(constants.photoNotFoundError);
        utils.phantomExit();
    }

    //Check for generic callbacks
    utils.checkGenericErrors(json.method);
};

var editPhotoScrapper = function () {
    //Scrape photo pages
    page.evaluate(function (photoObj) {
        var photosContArray = document.querySelectorAll('#biz-photos .local-photos-container');
        var url, id, photoFound = false;

        for (i = 0; i < photosContArray.length; i++) {
            url = photosContArray[i].querySelector('.photo-box img').src;
            id = url.split('/')[url.split('/').length - 2];

            //Check if this is the photo
            if (photoObj.photo_id == id) {

                photoFound = true;
                var _forms = photosContArray[i].querySelectorAll('form');
                if (_forms.length > 0) {

                    //From owner (possible action = delete , update caption)
                    if (photoObj.action == 'delete') {
                        window.callPhantom({
                            "method": "add_verification_step",
                            "action": "delete"
                        });
                        _forms[0].submit();
                    } else if (photoObj.action.indexOf('caption') > -1) {
                        window.callPhantom({
                            "method": "add_verification_step",
                            "action": photoObj.action
                        });
                        
                        photosContArray[i].querySelector('.photo-actions textarea[name="caption"]').value = photoObj.content;
                        _forms[1].submit();
                    }
                } else {
                    //From user (possible action = flag)
                    if (photoObj.action == 'flag') {
                        //Goto flag photo flag page
                        window.callPhantom({
                            "method": "add_flag_scrapper",
                            "content": photoObj.content
                        });

                        document.location.href = photosContArray[i].querySelector('.flag-content').href;
                    }
                }
            }

        }

        //Pagination check
        var paginationDiv = document.querySelector('#pagination_direction');
        if (paginationDiv && (paginationDiv.innerText.indexOf("Next") > -1) && !photoFound) {
            var pages = paginationDiv.querySelectorAll('a');
            var nextPageUrl = pages[pages.length - 1].href;

            window.callPhantom({
                "method": "add_scrapper_page",
                "url": nextPageUrl
            });
        } else if (photoFound) {
            //End of photos
            //Verification step already done
            /*window.callPhantom({
                "method": "finish_step"
            });*/
            
        } else if (!photoFound) {
            //Photo not found
            window.callPhantom({
                "method": "photo_not_found"
            });
        }

    }, editPhoto);
}


/**
 *Scrapper for slideshow(video+photo)
 */
var slidesScrapper = function () {
    //Scrape photo pages
    page.evaluate(function (photoObj) {

        var photosContArray = document.querySelectorAll('#biz_photos li');
        var data = [];
        var url, id, photoFound = false;
        for (i = 0; i < photosContArray.length; i++) {

            url = photosContArray[i].querySelector('.photo-box img').src;
            id = url.split('/')[_photo.url.split('/').length - 2];

            if (photoObj.photo_id == id) {
                photoFound = true;
                var fromUserString = "User submitted photo";
                if (photosContArray[i].querySelector('.actions .content_region').innerText.indexOf("User submitted photo") < 0) {
                    //From owner (possible action = delete , update caption)
                    if (photoObj.action == 'delete') {
                        photosContArray[i].querySelector('.delete_photo').submit();
                    } else if (photoObj.action.indexOf('caption') > -1) {
                        photosContArray[i].querySelector('textarea[name="caption"]').value = photoObj.content;
                        photosContArray[i].querySelector('input[name="is_storefront_photo"]').checked =photoObj.is_store_front;
                        photosContArray[i].querySelector('.caption form').submit();
                    }
                    
                } else {
                    //User photo, flag
                    if (photoObj.action == 'flag') {
                        //Goto flag photo flag page
                        window.callPhantom({
                            "method": "add_flag_scrapper",
                            "content": photoObj.content
                        });

                        document.location.href = photosContArray[i].querySelector('.flag-content').href;
                    }
                }
            }
        }

    }, editPhoto);
};



var verificationStep = function(type){
    return function () {
        page.evaluate(function (action_type) {
            console.log("ACTION TYPE="+action_type);
            var status = false;
            if (action_type == "flag"){
                var info = document.querySelector('.info-notice.info');
                var msg = "Thanks! We'll take a look to see if we agree that this is outside of our guidelines. This process may take several days and our Support team will contact you only if we need more information.";
                if(info && info.innerText == msg){
                    //success
                    status = true;
                }
            
            } else if(action_type == "delete") {
                var info = document.querySelector('.info-notice.info');
                var msg = "Your photo has been removed. You can upload another below.";
                if(info && info.innerText == msg){
                    //success
                    status = true;
                }
            
            } else if(action_type == "add_caption" || action_type == "edit_caption"){
                console.log("caption");
                var info = document.querySelector('.info-notice.success');
                console.log('INFO='+info.innerHTML);
                var msg = "Your new photo caption has been saved.";
                if(info && info.innerText == msg){
                    //success
                    status = true;
                }
            }
            
            window.callPhantom({
                "method" : "status",
                "status" : status
            });
            
        },type);
    }    
};

//ACTUAL SCRAPER STEPS
var steps = [
    common.openLogin, common.login, common.checkLoginStatus,common.multiBizRedirect(yelpObject.account.biz_id),

    function () {
        //Open the photos page
        page.evaluate(function () {
            //document.location.href = document.querySelector(".photos a").href;
            var photos = document.querySelector(".photos a");
            if (photos) {
                var nextPageUrl = photos.href;
                window.callPhantom({
                    "method": "add_scrapper_page",
                    "url": nextPageUrl,
                    "type": "photo"
                });
            } else {
                var slideshow = document.querySelector(".slideshow a");
                if (slideshow) {
                    //Process slideshow
                    var nextPageUrl = slideshow.href;
                    window.callPhantom({
                        "method": "add_scrapper_page",
                        "url": nextPageUrl,
                        "type": "slideshow",
                        "home_page": true
                    });
                }
            }
        });
    }];

//Interval to execute steps
var interval = setInterval(function () {

    if (!loadInProgress && typeof steps[stepIndex] == "function") {
        steps[stepIndex]();
        stepIndex++;
    }

    if (typeof steps[stepIndex] != "function") {
        utils.log('Exiting');
        phantom.exit();
    }
}, utils.randomInterval());