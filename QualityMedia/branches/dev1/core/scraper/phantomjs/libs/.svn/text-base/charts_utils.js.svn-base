/**
 * Constants used in the scripts
 **/
var constants = {
    loginUrl: "https://biz.yelp.com/login",

    invalidArgumentsError: 'Invalid number of arguments. Please check the arguments passed!',

    loggedOutSessionError: '{"error":"logged_out"}',

    incorrectPasswordError: '{"error":"incorrect_password"}',

    missingLoginPageError: '{"error":"missing_login_page"}',

    dailyLimitError: '{"error":"daily_limit_reached"}',

    forbiddenAccessMsgError: '{"error":"forbidden_access_messages"}',

    blankReplyContentError: '{"error":"blank_reply_content"}',

    loggedInSessionMsg: '{"success":"logged_in"}',

    noNetworkError: '{"error":"no_network"}',

    ownerPhotoError: '{"error": "upload_owner_photo"}',
    
    invalidOwnerPhotoError: '{"error": "invalid_owner_photo"}',

    reviewNotFoundError: '{"error": "review_not_found"}',

    slideshowPresentError: '{"error": "slideshow_present"}',

    editBizInfoIncompleteDataError: '{"error": "incomplete_data"}',

    requestTimedOut: '{"error": "request_timed_out"}',

    photoNotFoundError: '{"error": "photo_not_found"}',

    accountNotConfirmedError: '{"error": "account_not_verified"}',

    reviewAlreadyRepliedError: '{"error": "review_already_replied"}',

    bizBlockedByUserError: '{"error": "biz_blocked_by_user"}',
    
    updateNameError : '{"error": "update_name_error"}',

    unsafeSrciptLog: 'Unsafe JavaScript attempt to access',

    insecureContentLog: 'displayed insecure content',



    INFO_WIFI: "Wi-Fi:",
    INFO_CC: "Accepts Credit Cards:",
    INFO_WHEELCHAIR: "Wheelchair Accessible:",
    INFO_PARKING: "Parking:",
    INFO_APPOINTMENT: "By Appointment Only:",
    INFO_INSURANCE: "Accepts Insurance:",
    INFO_RESERVATION: "Takes Reservations:",
    INFO_WAITER: "Waiter Service:",
    INFO_DELIVERY: "Delivery:",
    INFO_TAKEOUT: "Take-out:",
    INFO_OUTDOOR_SEATING: "Outdoor Seating:",
    INFO_DOGS: "Dogs Allowed:",
    INFO_CATERS: "Caters:",
    INFO_ALCOHOL: "Alcohol:",
    INFO_HAPPY_HOUR: "Happy Hour:",
    INFO_SMOKING: "Smoking:",
    INFO_COAT_CHECK: "Coat Check:",
};


var showErrorMessages = {

    loggedOutError: function () {

    },
};

/**
 * Yelp Object used for account credential and user agent
 **/
var yelpObject = {
    "account": {
        "username": null,
        "password": null
    },
    "userAgent": null
};

/**
 * Utility methods
 **/
var utils = {

    getCurrentTime: function () {
        var date = new Date();
        return date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();
    },

    randomInterval: function () {
        //var val= (Math.floor(Math.random() * (config.stepInterval.end - config.stepInterval.start + 1) + config.stepInterval.start) * 1000);
		return (Math.floor(Math.random() * (config.stepInterval.end - config.stepInterval.start + 1) + config.stepInterval.start));
    },

    ajaxRequest: function () {
        var activexmodes = ["Msxml2.XMLHTTP", "Microsoft.XMLHTTP"] //activeX versions to check for in IE
        if (window.ActiveXObject) { //Test for support for ActiveXObject in IE first (as XMLHttpRequest in IE7 is broken)
            for (var i = 0; i < activexmodes.length; i++) {
                try {
                    return new ActiveXObject(activexmodes[i])
                } catch (e) {
                    //suppress error
                }
            }
        } else if (window.XMLHttpRequest) // if Mozilla, Safari etc
            return new XMLHttpRequest()
        else {
            return false;
        }

    },

    checkGenericErrors: function (error) {
        switch (error) {
        case "wrong_password":
            console.log(constants.incorrectPasswordError);
            utils.phantomExit();
            break;
        case "logged_out":
            console.log(constants.loggedOutSessionError);
            utils.phantomExit();
            break;
        case "missing_login":
            console.log(constants.missingLoginPageError);
            utils.phantomExit();
            break;
        case "forbidden_message_page":
            utils.log('Forbidden error caught');
            //console.log(constants.forbiddenAccessMsgError);
            //utils.phantomExit();
            break;
        case "account_not_confirmed":
            console.log(constants.accountNotConfirmedError);
            utils.phantomExit();
            break;
        default:
            ;
        }
    },
    
    checkGenericErrorsCombined: function (error) {
        switch (error) {
        case "wrong_password":
            console.log(constants.incorrectPasswordError);
            utils.phantomExit();
            break;
        case "logged_out":
            console.log(constants.loggedOutSessionError);
            utils.phantomExit();
            break;
        case "missing_login":
            console.log(constants.missingLoginPageError);
            utils.phantomExit();
            break;
        default:
            ;
        }
    },

    objectIsEmpty: function (map) {
        for (var key in map) {
            if (map.hasOwnProperty(key)) {
                return false;
            }
        }
        return true;
    },

    refineJSON: function (data) {
        for (var key in data) {
            if (typeof (data[key]) == "object") {
                if (utils.objectIsEmpty(data[key])) {
                    delete data[key]
                } else {
                    utils.refineJSON(data[key]);
                }
            } else if (data[key] == null || data[key] == "") {
                delete data[key];
            }
        }

        return data;
    },

    intialRun: true,

    log: function (value) {
        var that = this;
        var file = fs.open(config.logFile, "a");

        if (that.intialRun) {
            file.write("\n------------------------------\n------------------------------\n");
            var date = new Date();
            var fullDate = date.getDate() + "/" + (date.getMonth() + 1) + "/" + date.getFullYear() + "  " + date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();
            file.write(fullDate + " ,\t" + system.args[0]);
            if(yelpObject){
                file.write(" ,\tUsername: " + yelpObject.account['username']);
            }
            that.intialRun = false;
        }

        if (value.indexOf(constants.unsafeSrciptLog) != -1) {
            file.write("\n[" + that.getCurrentTime() + "] : " + 'Unsafe script');
        } else if (value.indexOf(constants.insecureContentLog) != -1) {
            file.write("\n[" + that.getCurrentTime() + "] : " + 'Insecure content loaded');
        } else {
            file.write("\n[" + that.getCurrentTime() + "] : " + value);
        }

        file.close();
    },

    phantomExit: function () {
        phantom.exit();
    },
};

/**
 * Phantom page states
 **/

var phantomPage = {
    checkStatus: function (url, startTime) {
        if (typeof startTime == 'undefined') {
            startTime = (new Date()).getTime() / 1000;
        }

        if (loadInProgress && ((new Date()).getTime() / 1000) - startTime > 30) {
            console.log(constants.requestTimedOut);
            phantom.exit();
        } else if (loadInProgress) {
            setTimeout(function () {
                phantomPage.checkStatus(url, startTime);
            }, 1000);
        }
    },

    onResourceRequested: function (requestData, networkRequest) {
        utils.log('Request (#' + requestData.id + '): ' + JSON.stringify(requestData));
    },

    onResourceReceived: function (response) {
        utils.log('Response (#' + response.id + ', stage "' + response.stage + '"): ' + JSON.stringify(response));
    },

    onConsoleMessage: function (msg) {
        utils.log(msg);
    },

    onError: function (msg, trace) {
        var msgStack = ['ERROR: ' + msg];
        if (trace && trace.length) {
            msgStack.push('TRACE:');
            trace.forEach(function (t) {
                msgStack.push(' -> ' + t.file + ': ' + t.line + (t.function ? ' (in function "' + t.function +'")' : ''));
            });
        }
        console.error(msgStack.join('\n'));
    },
    onLoadStarted: function () {
        loadInProgress = true;
    },

    onLoadFinished: function () {
        loadInProgress = false;
        if (config.saveSnapShots) {
            page.render(config.imagesDir + yelpObject.account['username'] + "/" + pgImg + '.png');
        }

        pgImg++;

        //Check if session is still logged in
        if (page.url.indexOf('biz.yelp.com/login?return_url') > -1) {
            console.log(constants.loggedOutSessionError);
            phantom.exit();
        }

        utils.log("load finished");
    }
}

/**
 * Common function used in the scripts
 **/
var common = {
    openLogin: function () {
        //Load Login Page
        page.open(constants.loginUrl, function (status) {
            if (status !== 'success') {
                console.log(constants.noNetworkError);
                utils.phantomExit();
            } else {
                utils.log('Success in opening login page');
            }
        });
    },

    login: function () {
        //Enter Credentials
        page.evaluate(function (username, password) {
            if (document.querySelector('#login-form')) {
                document.querySelector("#email").value = username;
                document.querySelector("#password").value = password;
                document.querySelector("#login-form").submit();
            } else {
                //Not logged in
                window.callPhantom({
                    "method": "missing_login",
                });
            }

        }, yelpObject.account.username, yelpObject.account.password);
    },

    checkLoginStatus: function () {
        page.evaluate(function () {
            var header = document.querySelector('#mastHeadUser');

            if (header && header.innerText.indexOf('Logged in as') > -1) {
                console.log("Logged in");
            } else {
                //Check for error message
                var incorrectPassMsg = document.querySelector('.alert-error .alert-message');
                var INCORRECT_PASSWORD = "The password you provided is incorrect.";


                if (incorrectPassMsg && incorrectPassMsg.innerText == INCORRECT_PASSWORD) {
                    window.callPhantom({
                        "method": "wrong_password"
                    });
                } else {
                    window.callPhantom({
                        "method": "logged_out"
                    });
                }
            }
        });
    },

    multiBizRedirect: function (biz_id) {
        return function () {
            page.evaluate(function (biz_id) {
                var bizList = document.querySelectorAll('#biz-nav .biz-list .biz-item');

                if (bizList.length <= 0) {
                    //Only one business, do nothing
                } else {
                    var id, link;
                    //Multiple businesses
                    for (i = 0; i < bizList.length; i++) {
                        var id_array = bizList[i].querySelector('h6 a').href.split('/');
                        id = id_array[id_array.length-2];
                        if (id == biz_id) {
                            document.location.href = bizList[i].querySelector('h6 a').href;
                        }
                    }
                }
            }, biz_id);
        }
    }
}