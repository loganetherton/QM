//PhantomJS script to read messages
/**
 It accepts the following commands line arguments when ran from the terminal
 * @username Biz account username to be used for logging in
 * @password Password of above account
 * @biz_id Business identifier
 * @inbox_type Type of inbox to fetch messages (inbox or sent)
 * @userAgent User agent to be used (Optional)

 Usage:
 phantomjs yelp_read_messages.js '<yelp_biz_username>' '<password>' '<biz_id>' '<inbox_type>'
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
	"account" : {},
	"inbox_type" : null,
	"userAgent" : null
};

var businessMessages = {
	"business_id" : null,
	"messages" : []
};

var scrapperSteps = [];
var msgList, totalConversationCount = 0;

/**
 *Function to read the command line arguments
 **/

function readArguments() {
	//system.args[0] is for file name which is being executed

	if (system.args.length < 5 || system.args.length > 6) {
		console.log(constants.invalidArgumentsError);
		phantom.exit();
	}

	yelpObject.account['username'] = system.args[1];
	yelpObject.account['password'] = system.args[2];
	businessMessages.business_id = system.args[3]
	yelpObject.inbox_type = system.args[4]

	//Check for user-agent, if yes then set to the page settings
	if (system.args[5]) {
		yelpObject.userAgent = system.args[5];
		page.settings.userAgent = yelpObject.userAgent;
	}
}

//Read the command line arguments for options
readArguments();

page.onCallback = function(json) {
	//Add step for scraping
	if (json.method == "message_scrapper_step") {
		scrapperSteps.push(function() {
			page.evaluate(function(url) {
				console.log('Opening individual conversation URL:' + url);
				document.location.href = url;
			}, json.addScraper);
		});
		scrapperSteps.push(scraperMethod);

	} else if (json.method == "append_messages") {
		//Append messages
		businessMessages.messages.push(json.data);

	} else if (json.method == "add_scrapper_page") {
		steps.push(function() {
			page.evaluate(function(nextPage) {
				document.location.href = nextPage;
			}, json.url);
		});

		steps.push(pageScraper);

	} else if (json.method == "fill_scrapper_steps") {
		for ( i = 0; i < scrapperSteps.length; i++) {
			steps.push(scrapperSteps[i]);
		}
	} else if (json.method == "logged_out") {
		//Not logged in
		console.log(constants.loggedOutSessionError);
		utils.phantomExit();
	}
	
	//Check for generic callbacks
	utils.checkGenericErrors(json.method);
};

var pageScraper = function() {
	page.evaluate(function() {
		var linkRow = document.querySelectorAll('#mail_container tr');
		var convLinkArray = [];
		for ( i = 0; i < linkRow.length; i++) {
			convLinkArray.push(linkRow[i].querySelector('.message_subject').href);
			window.callPhantom({
				"method" : "message_scrapper_step",
				"addScraper" : linkRow[i].querySelector('.message_subject').href
			});
		}

		//Check if there is a next page
		var paginationDiv = document.querySelector('#pagination_direction');
		if (paginationDiv && paginationDiv.innerText.indexOf("Next") > -1) {
			//Pagination exists
			//Add next URL to steps
			var pages = paginationDiv.querySelectorAll('a');
			//This will include both Next and previous
			var nextPageUrl = pages[pages.length - 1].href;
			//Choose NEXT

			window.callPhantom({
				"method" : "add_scrapper_page",
				"url" : nextPageUrl
			});

		} else {
			//End of road for us
			//START INDIVIDUAL CONVERSATION SCRAPES
			window.callPhantom({
				"method" : "fill_scrapper_steps"
			});
		}
	});
}
//Method to scrape the messages from particular conversaation
var scraperMethod = function() {
	page.evaluate(function() {
		// Array to hold all the conversations
		var messageTemplate = {
			"user_yelp_id" : null,
			"user_name" : null,
			"user_image_url" : null,
			"message_subject" : null,
			"message_excerpt" : null,
			"last_message_time" : null,
			"thread_id" : null,
			"conversations" : []
		}

		var conversationsTemplate = {
			"from" : null,
			"type" : null,
		};
		var reviewMsgTemplate = {
			"heading" : null,
			"rating" : null,
			"time" : null,
			"content" : null
		};
		var convMsgTemplate = {
			"time" : null,
			"content" : null
		};

		var tempMsgTemplate = messageTemplate;
		
		//Check for forbidden error
        var header = document.querySelector('#mastHeadUser');
		if (!header) {
			var pageText = document.querySelector('body').innerText;
			if(pageText.indexOf("You don't have permission to access")>-1){
				window.callPhantom({
                    "method": "forbidden_message_page"
                });
			}
		}
		
		
        //Scenario where account is not activated yet
        var isAccountConfirmed = document.querySelector('#unconfirmed_tab');
        if(isAccountConfirmed){
            var accActivateMsg = "To activate this feature please click the link in the confirmation email you received.";
            if(isAccountConfirmed.innerText.indexOf(accActivateMsg)>-1){
                window.callPhantom({
                    "method": "account_not_confirmed"
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
		for ( i = 0; i < tempR2RThread.length; i++) {
			var tempConversationTemplate = JSON.parse(JSON.stringify(conversationsTemplate));
			if (tempR2RThread[i].classList.contains('original_message')) {

				//Review message
				/*tempConversationTemplate.message = JSON.parse(JSON.stringify(reviewMsgTemplate));

				 tempConversationTemplate.from = tempR2RThread[i].querySelectorAll('.message_user a')[1].innerText;
				 tempConversationTemplate.type = "review";

				 var headingPart1 = tempR2RThread[i].querySelectorAll('.message_content a')[0].innerText;
				 var headingPart2 = tempR2RThread[i].querySelectorAll('.message_content a')[1].innerText;
				 tempConversationTemplate.message.heading = headingPart1 + " review of " + headingPart2;
				 tempConversationTemplate.message.rating = parseInt(tempR2RThread[i].querySelector('.message_content .review_wrapper .rating_wrapper .rating i').title.slice(0, 1));
				 //tempConversationTemplate.message.time = tempR2RThread[i].querySelector('.message_content .review_wrapper .rating_wrapper .review_time').innerText;
				 tempConversationTemplate.message.time = tempR2RThread[i].querySelector('.message_content .review_wrapper .rating_wrapper .date').innerText;
				 tempConversationTemplate.message.content = tempR2RThread[i].querySelector('.message_content .review_wrapper .review_text').innerText;

				 //TODO:push this to the messages Array
				 tempMsgTemplate['conversations'].push(tempConversationTemplate);*/
			} else {

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
					} catch(err) {
						console.log('Comment removed by user')
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
			"method" : "append_messages",
			"data" : tempMsgTemplate,
		});

	});
}
//ACTUAL SCRAPER STEPS
var steps = [common.openLogin, common.login, common.checkLoginStatus,
function() {
	//Navigate to the messages TAB
	page.evaluate(function() {
		document.location.href = document.querySelector(".messaging a").href;
	});
},
function() {
	//Navigate to the appropriate messages
	page.evaluate(function(inbox_type) {
		if (inbox_type == "inbox") {
			//Do nothing already in inbox
		} else {
			//Navigate to outbox
			document.location.href = document.querySelector("#mail_nav #sent a").href;
		}
	}, yelpObject.inbox_type);
}, pageScraper];

//Interval to execute steps
var interval = setInterval(function() {

	if (!loadInProgress && typeof steps[stepIndex] == "function") {
		steps[stepIndex]();
		stepIndex++;
	}

	if ( typeof steps[stepIndex] != "function") {
		if (config.envDev == true) {
			var file = fs.open("JSON/" + yelpObject.account.username + "/messages.json", "w");
			file.write(JSON.stringify(businessMessages));
			file.close();
		}
		//Print to console
		console.log(JSON.stringify(businessMessages));
		phantom.exit();
	}
}, utils.randomInterval());
