//PhantomJS script to authenticate login credentials
/**
 It accepts the following commands line arguments when ran from the terminal
 * @username Biz account username to be used for logging in
 * @password Password of above account
 * @userAgent User agent to be used (Optional)
 Usage:
 phantomjs yelp_authenticate_login.js '<yelp_biz_username>' '<password>'
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

var stepIndex = 0, loadInProgress = false, pgImg = 0;
var yelpObject = {
    "account": {},
    "userAgent": null,
    "yelp_id": null
};

bizAccountData = {
    "success": "logged_in",
    "biz_list": []
};
/**
 *Function to read the command line arguments
 **/

function readArguments() {
	//system.args[0] is for file name which is being executed
	if (system.args.length < 3 || system.args.length > 4) {
		console.log(constants.invalidArgumentsError);
		phantom.exit();
	}
	yelpObject.account['username'] = system.args[1];
	yelpObject.account['password'] = system.args[2];
    
    //Check for user-agent, if yes then set to the page settings
	if (system.args[3]) {
		yelpObject.userAgent = system.args[3];
		page.settings.userAgent = yelpObject.userAgent;
	}
}

//Read the command line arguments for options
readArguments();

page.onCallback = function (json) {
    if (json.method == "logged_out") {
        //Not logged in
        console.log(constants.loggedOutSessionError);

    } else if (json.method == "logged_in") {

        bizAccountData.biz_list = bizAccountData.biz_list.concat(json.biz_names);

        if (json.single_biz) {
            console.log(JSON.stringify(bizAccountData));
            utils.phantomExit();
        } else {
            for (i = 0; i < json.biz_links.length; i++) {
                steps.push(redirectStep(json.biz_links[i]));
                steps.push(readYelpId(json.biz_names[i].id));
            }

            steps.push(function () {
                console.log(JSON.stringify(bizAccountData));
                utils.phantomExit();
            });

        }

    } else if (json.method == "add_yelp_id") {

        for (i = 0; i < bizAccountData.biz_list.length; i++) {
            if (bizAccountData.biz_list[i].id == json.biz_id) {
                bizAccountData.biz_list[i].yelp_id = json.yelp_id;
            }
        }
    }

    //Check for generic callbacks
    utils.checkGenericErrors(json.method);

};

var readYelpId = function (biz_id) {
    return function () {
        page.evaluate(function (biz_id) {
            //Check the id's here
            var yelpIdSplit = document.querySelector('.view-biz-page').href.split('/');
            var yelp_id = yelpIdSplit[yelpIdSplit.length-1];
            window.callPhantom({
                "method": "add_yelp_id",
                "yelp_id": yelp_id,
                "biz_id": biz_id
            });
        }, biz_id);
    }
};

var redirectStep = function (_url) {
    return function () {
        page.evaluate(function (url) {
            document.location.href = url;
        }, _url);
    }
};



//ACTUAL SCRAPER STEPS
var steps = [common.openLogin, common.login,
function() {
	page.evaluate(function() {
		var header = document.querySelector('#mastHeadUser');
		if (header && header.innerText.indexOf('Logged in as') > -1) {
			
			var bizList = document.querySelectorAll('#biz-nav .biz-list .biz-item');
			var bizNames = [];
            var bizLinks = [];

			if(bizList.length<=0){
				var splitLink = document.querySelector('#biz-nav li').querySelector('.top-nav-link').href.split('/');
                var splitId = document.querySelector('.view-biz-page').href.split('/');
                
                //Only one business
				var bizObject= {
						"name":document.querySelector('#biz-nav li').querySelector('h6').innerText,
                        "address":document.querySelector('#biz-nav li').querySelector('address').innerText,
						"id":splitLink[splitLink.length-2],
                        "yelp_id": splitId[splitId.length-2]
					};
				bizNames.push(bizObject);
			}else{
				//Multiple businesses
				for ( i = 0; i < bizList.length; i++) {
					var splitLink = bizList[i].querySelector('h6 a').href.split('/');
                    
                    var bizObject= {
						"name":bizList[i].querySelector('h6 a').innerText,
                        "address":bizList[i].querySelector('address').innerText,
						"id":splitLink[splitLink.length-2],
                        "yelp_id": ""
					};
					
                    bizLinks.push(bizList[i].querySelector('h6 a').href);
                    bizNames.push(bizObject);
				}	
			}
			

			 if (bizLinks.length > 0) {
                window.callPhantom({
                    "method": "logged_in",
                    "biz_names": bizNames,
                    "single_biz": false,
                    "biz_links": bizLinks
                });
             } else {
                window.callPhantom({
                        "method": "logged_in",
                        "biz_names": bizNames,
                        "single_biz": true
                    });
             }
            
		} else {
			//Check for error message
            var incorrectPassMsg = document.querySelector('.alert-error .alert-message');
            var INCORRECT_PASSWORD = "The password you provided is incorrect.";
            
            if(incorrectPassMsg && incorrectPassMsg.innerText == INCORRECT_PASSWORD){
                window.callPhantom({
                    "method" : "wrong_password"
                });		
            }else{
                window.callPhantom({
                    "method" : "logged_out"
                });	
            }
		}
	});
}];
//Interval to execute steps
var interval = setInterval(function() {
	if (!loadInProgress && typeof steps[stepIndex] == "function") {
		steps[stepIndex]();
		stepIndex++;
	}
	if ( typeof steps[stepIndex] != "function") {
		utils.log('Exiting');
		phantom.exit();
	}
}, utils.randomInterval());
