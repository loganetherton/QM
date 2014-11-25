
page.onCallback = function (json) {
    /******* Analytics part *******/
    if (json.method == "publish_info_data") {
        //TODO: Fire the next step here
        steps.push(redirectToHome);
        steps.push(redirectToInfo);
        steps.push(infoInitStep);
    } else if (json.method == "add_analytics_data") {
        analyticsData['data'][json.time] = unescape(json.data);
    } else if (json.method == "base_url") {
        yelpObject.base_url = json.url;
        analyticsData.data.arpu = json.arpu;
        //Fill steps
        steps.push(function () {
            page.evaluate(function (url) {
                document.location.href = url;
            }, yelpObject.base_url + "1m");
        });
        steps.push(analyticsScrapper.oneMonth);

        steps.push(function () {
            page.evaluate(function (url) {
                document.location.href = url;
            }, yelpObject.base_url + "1y");
        });
        steps.push(analyticsScrapper.oneYear);

        steps.push(function () {
            page.evaluate(function (url) {
                document.location.href = url;
            }, yelpObject.base_url + "2y");
        });

        steps.push(analyticsScrapper.twoYear);
        steps.push(analyticsScrapper.publishData);
    }

    
    /******* Photos part *******/
    else if (json.method == "add_photos") {
        //Add photos to array
        businessPhotos.photos = businessPhotos.photos.concat(json.data);
    } else if (json.method == "add_scrapper_page_photos") {
        steps.push(function () {
            page.evaluate(function (nextPage) {
                document.location.href = nextPage;
            }, json.url);
        });

        steps.push(photoScrapper);
    } else if (json.method == "add_photos_scrapper") {
        if (json.type == "photos") {
            steps.push(photoScrapper);
        } else {
            //TODO: WIP
            steps.push(photoScrapper);
        }
    } else if (json.method == "slideshow_present") {
        utils.log("Accounts photo="+JSON.stringify(accountData.photos));
        businessPhotos.photos = JSON.parse(constants.slideshowPresentError);
        utils.log("Accounts photo 2="+JSON.stringify(accountData.photos));
    }

    /******* Info part *******/
    else if (json.method == "add_info") {
        //Add info to JSON
        businessInfo.info[json.type] = json.data;
    } else if (json.method == "add_infoUrl") {
        infoUrls = json.data;
        addInfoScrapperSteps();
    }


    /******* Reviews part *******/
    else if (json.method == "append_reviews") {
        //Append messages
        reviewsObject.reviews.push(json.data);
    } else if (json.method == "add_scrapper_page_reviews") {
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

    } else if (json.method == "forbidden_message_page") {
        utils.log('Forbidden error caught');
        reviewsObject.error = JSON.parse(constants.forbiddenAccessMsgError);
        //NEXT PART: Move to next part of data
        steps.push(redirectToHome);
        steps.push(redirectToPhotos);
        var nextIndex = steps.length-2;
        stepIndex = nextIndex;
        
    } else if (json.method == "account_not_confirmed") {
        utils.log('accountNotConfirmedError');
        accountData.reviews = JSON.parse(constants.accountNotConfirmedError);
        //NEXT PART: Move to next part of data
        steps.push(redirectToHome);
        steps.push(redirectToPhotos);
        var nextIndex = steps.length-3;
        stepIndex = nextIndex;
        
    } else if (json.method == "end_step") {
        utils.log("PM section start");
        //Extra check to avoid 403 mess
        steps.push(function () {
            page.evaluate(function () {
                document.location.href = "https://biz.yelp.com/";
            });
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
        
        //NEXT PART: Move to next part of data
        steps.push(redirectToHome);
        steps.push(redirectToPhotos);
    } else if (json.method == "logged_out") {
        //Not logged in
        console.log(constants.loggedOutSessionError);
        utils.phantomExit();
    }

    //Check for generic callbacks
    utils.checkGenericErrorsCombined(json.method);
};