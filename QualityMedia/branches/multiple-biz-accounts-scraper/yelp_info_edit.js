//PhantomJS script to edit business information
/**
 It accepts the following commands line arguments when ran from the terminal
 * @username Biz account username to be used for logging in
 * @password Password of above account
 * @info_type Information type to be edited ['basic_info', 'hours', 'specialties', 'history', 'owner_info']
 * @data JSON data containing the new data(only data for that type of information)
 * @userAgent User agent to be used (Optional)

 Usage:
 phantomjs yelp_info_edit.js '<yelp_biz_username>' '<password>' '<info_type>' '<data>'
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

var businessInfo = {
    type: null,
    info: {
		
    }
};

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
    businessInfo.type = system.args[3];
    businessInfo.info = JSON.parse(system.args[4]);

    //Check for user-agent, if yes then set to the page settings
    if (system.args[5]) {
        yelpObject.userAgent = system.args[5];
        page.settings.userAgent = yelpObject.userAgent;
    }
}

//Read the command line arguments for options
readArguments();

page.onCallback = function (json) {
    if (json.method == "logged_out") {
        //Not logged in
        console.log(constants.loggedOutSessionError);
        utils.phantomExit();
    } else if (json.method == "add_next_step") {
        steps.push(redirectStep(json.url));
        steps.push(scrapper[businessInfo.type]);
        steps.push(finalStep);
    } else if (json.method == "status") {
        console.log('{"status":"' + json.status + '"}');
        utils.phantomExit();
    } else if(json.method == "upload_file"){
		page.uploadFile(json.selector,json.path);
	}

    //Check for generic callbacks
    utils.checkGenericErrors(json.method);
};

var redirectStep = function (_url) {
    return function () {
        page.evaluate(function (url) {
            document.location.href = url;
        }, _url);
    }
}

var finalStep = function () {
    page.evaluate(function () {
        var success_message = document.querySelector('.info-notice,.success');

        if (success_message) {
            window.callPhantom({
                "method": "status",
                "status": "success"
            });
        } else {
            window.callPhantom({
                "method": "status",
                "status": "error"
            });
        }
    });
}

//Scrappers for various info
var scrapper = {
    //Basic info scrapper
    basic_info: function () {
        page.evaluate(function (data,stringContstants) {

            var basic_info = document.querySelector('#edit-basic-info');
            basic_info.querySelector('.BusinessName input').value = data.name.biz_name;
            basic_info.querySelector('.BusinessStreetAddress1 input').value = data.address.line1;
            basic_info.querySelector('.BusinessStreetAddress2 input').value = data.address.line2;
            basic_info.querySelector('.BusinessCity input').value = data.address.city;
            basic_info.querySelector('.BusinessState input').value = data.address.state;
            basic_info.querySelector('.BusinessZipCode input').value = data.address.zip;
            basic_info.querySelector('.BusinessPhoneNumber input').value = data.phone;
            basic_info.querySelector('.BusinessUrl input').value = data.website;
            if (data.menu_url) {
                basic_info.querySelector('.MenuUrl input').value = data.menu_url;
            }

            document.querySelector('#latitude').value = data.location.latitude;
            document.querySelector('#longitude').value = data.location.longitude;

			var isLocked = document.querySelector('.BusinessCategoryYelp').querySelector('.locked-attr-note');

            if (!isLocked) {
                var categoriesList = document.querySelectorAll('#BusinessCategoryYelp_categories .category-name');
                var tempCategories = new Array();
                for (i = 0; i < data.categories.length; i++) {
                	tempCategories.push(data.categories[i].name);
                }
                for (i = 0; i < categoriesList.length; i++) {
                    if (tempCategories.indexOf(categoriesList[i].innerText.replace("Remove","")) < 0) {
                        //Remove it
                        var element = categoriesList[i];
                    	element.parentNode.removeChild(element);
                    }
                }
            }


            //ADDITIONAL INFO
            if (data.additional_info != "" && data.additional_info != null) {

                var label;
                var additional_info = document.querySelectorAll('.additional-info .voteable-attribute');
                for (i = 0; i < additional_info.length; i++) {
                    label = additional_info[i].querySelector('label').innerText;
                    //console.log("Value: " + data.additional_info[key]);
					 
                    switch (label) {
                    case stringContstants.INFO_CC:
                        var boolSelector = data.additional_info.credit_cards?"True":"False";
                        document.querySelector('#av_box\\:BusinessAcceptsCreditCards\\:'+boolSelector).checked =
                            true;
                        break;
                    case stringContstants.INFO_WHEELCHAIR:
                    	var boolSelector = data.additional_info.wheelchair_accessible?"True":"False";
                        document.querySelector('#av_box\\:WheelchairAccessible\\:'+boolSelector).checked =
                            true;
                        break;
                    case stringContstants.INFO_INSURANCE:
                    	var boolSelector = data.additional_info.insurance?"True":"False";
                        document.querySelector('#av_box\\:AcceptsInsurance\\:'+boolSelector).checked =
                            true;
                        break;

                    case stringContstants.INFO_APPOINTMENT:
                    	var boolSelector = data.additional_info.appointment_only?"True":"False";
                        document.querySelector('#av_box\\:ByAppointmentOnly\\:'+boolSelector).checked =
                            true;
                        break;
                        
                    case stringContstants.INFO_RESERVATION:
                        var boolSelector = data.additional_info.reservation?"True":"False";
                        document.querySelector('#av_box\\:RestaurantsReservations\\:'+boolSelector).checked =
                            true;
                        break;
                    
                    case stringContstants.INFO_WAITER:
                        var boolSelector = data.additional_info.waiter_service?"True":"False";
                        document.querySelector('#av_box\\:RestaurantsTableService\\:'+boolSelector).checked =
                            true;
                        break;
                        
                    case stringContstants.INFO_DELIVERY:
                        var boolSelector = data.additional_info.delivery?"True":"False";
                        document.querySelector('#av_box\\:RestaurantsDelivery\\:'+boolSelector).checked =
                            true;
                        break;
                        
                    case stringContstants.INFO_TAKEOUT:
                        var boolSelector = data.additional_info.takeout?"True":"False";
                        document.querySelector('#av_box\\:RestaurantsTakeOut\\:'+boolSelector).checked =
                            true;
                        break;
                        
                    case stringContstants.INFO_OUTDOOR_SEATING:
                        var boolSelector = data.additional_info.outdoor_seating?"True":"False";
                        document.querySelector('#av_box\\:OutdoorSeating\\:'+boolSelector).checked =
                            true;
                        break;
                        
                    case stringContstants.INFO_DOGS:
                        var boolSelector = data.additional_info.dogs_allowed?"True":"False";
                        document.querySelector('#av_box\\:DogsAllowed\\:'+boolSelector).checked =
                            true;
                        break;
                        
                    case stringContstants.INFO_CATERS:
                        var boolSelector = data.additional_info.caters?"True":"False";
                        document.querySelector('#av_box\\:Caters\\:'+boolSelector).checked =
                            true;
                        break;
                            
                    case stringContstants.INFO_HAPPY_HOUR : 
                        var boolSelector = data.additional_info.happy_hour?"True":"False";
                        document.querySelector('#av_box\\:HappyHour\\:'+boolSelector).checked =
                            true;
                        break;
                    case stringContstants.INFO_COAT_CHECK : 
                        var boolSelector = data.additional_info.coat_check?"True":"False";
                        document.querySelector('#av_box\\:CoatCheck\\:'+boolSelector).checked =
                            true;
                        break;
                        
                    case stringContstants.INFO_PARKING:
                        var types = additional_info[i].querySelectorAll('.inputFields li');
                        for (j = 0; j < types.length; j++) {
                            var field = types[j].querySelector('input');
                            field.checked = data.additional_info.parking_detail[field.name.split('_')[1]];
                        }
                        break;
                    case stringContstants.INFO_WIFI:
                        var types = additional_info[i].querySelectorAll('.inputFields li');
                        for (j = 0; j < types.length; j++) {
                            var field = types[j].querySelector('input');
                            field.checked = data.additional_info.wifi_detail[field.id.split(':')[2].toLowerCase()];
                        }
                        break;
                    
                    case stringContstants.INFO_SMOKING : 
                        var types = additional_info[i].querySelectorAll('.inputFields li');
                        for (j = 0; j < types.length; j++) {
                            var field = types[j].querySelector('input');
                            field.checked = data.additional_info.smoking_detail[field.id.split(':')[2].toLowerCase()];
                        }
                        break;

                    case stringContstants.INFO_ALCOHOL:
                        var types = additional_info[i].querySelectorAll('.inputFields li');
                        for (j = 0; j < types.length; j++) {
                            var field = types[j].querySelector('input');
                            if (field.id.split(':')[2] == "none") {
                                field.checked = data.additional_info.alcohol_detail['no'];
                            } else {
                                field.checked = data.additional_info.alcohol_detail[field.id.split(':')[2].toLowerCase()];
                            }

                        }
                        break;

                    default:;
                    }
                } //Loop ends
            }

            //Submit form
            document.querySelector('#about_this_biz_edit_form').submit();

        }, businessInfo.info,constants);
    },

    //Hours info scrapper
    hours: function () {
        page.evaluate(function (data) {

            var working_days = document.querySelectorAll('#day-hours-BusinessHours .hours-display .hours');
            var tempValueArray = new Array();
            var timeData = new Array();

            for (i = 0; i < data.length; i++) {
                timeData.push(data[i].value);
            }

            //Removal process
            for (i = 0; i < working_days.length; i++) {
                //Check if this needs to be removed
                var time_value = working_days[i].querySelector('input[name="BusinessHours"]').value;
                var index = timeData.indexOf(time_value);

                if (index < 0) {
                    var element = working_days[i];
                    element.parentNode.removeChild(element);
                } else {
                    tempValueArray.push(index);
                }
            }

            /*var dayOptions = document.querySelectorAll('.hours-select .weekday option');
            var hourOptions = document.querySelectorAll('.hours-select .hours-start option');
            var hourEndOptions = document.querySelectorAll('.hours-select .hours-end option');*/

            //Addition process
            for (i = 0; i < data.length; i++) {
                //Check if its already present
                var isContained = tempValueArray.indexOf(i);
                if (isContained < 0) {
                    //PUSH IT :P
                    document.querySelector('.hours-select .weekday').value = data[i].value.split(' ')[0];
                    document.querySelector('.hours-select .hours-start').value = data[i].value.split(' ')[1];
                    document.querySelector('.hours-select .hours-end').value = data[i].value.split(' ')[2];
                    document.querySelector('.hours-select button').click();
                }
            }

            //Submit form
            document.querySelector('#about_this_biz_edit_form').submit();


            function getOptionValue(options, text) {
                console.log("called");
                for (i = 0; i < options.length; i++) {
                    if (options[i].innerText == text) {
                        console.log('Returning=' + options[i].value)
                        return options[i].value;
                    }
                }
            };

        }, businessInfo.info);
    },

    //specialties info scrapper
    specialties: function () {
        page.evaluate(function (data) {
            document.querySelector('#AboutThisBizSpecialties').value = data.speciality;
            
           	//Submit form
			document.querySelector('#about_this_biz_edit_form').submit();
			
        }, businessInfo.info);
    },

    //History info scrapper
    history: function () {
        page.evaluate(function (data) {
            document.querySelector('#AboutThisBizYearEstablished').value = data.year_established;
            document.querySelector('#AboutThisBizHistory').value = data.history;
            
            //Submit form
			document.querySelector('#about_this_biz_edit_form').submit();

        }, businessInfo.info);
    },

    //Owner info scrapper
    owner_info: function () {
        page.evaluate(function (data) {
            document.querySelector('#AboutThisBizBioFirstName').value = data.first_name;
            document.querySelector('#AboutThisBizBioLastName').value = data.last_initial;
            
            if(data.role == "owner"){
                document.querySelector('#AboutThisBizRole').value = "Business Owner";
            }else{
                document.querySelector('#AboutThisBizRole').value = "Manager";
            }
            document.querySelector('#AboutThisBizBio').value = data.bio;
            
            if(data.photo && data.photo!=""){
                document.querySelector("#"+data.photo).checked = true;
            }
            
            if(data.photo == "upload-new"){
            	window.callPhantom({
                	"method": "upload_file",
                	"path" : data.photo_path,
                	"selector" : 'input[name="photo"]'
            	});
            }
            //Submit
            document.querySelector('#about_this_biz_edit_form').submit();
            
        }, businessInfo.info);
    },
}

//ACTUAL SCRAPER STEPS
var steps = [
    common.openLogin, common.login, common.checkLoginStatus,
    function () {
        //Open the info page
        page.evaluate(function () {
            document.location.href = document.querySelector(".info a").href;
        });
    },
    function () {
        //Open first section
        page.evaluate(function (info_type) {
            var url;
            switch (info_type) {
            case "basic_info":
                var editLink = document.querySelector("#basic_business_info a");
                if(editLink){
                    url = editLink.href;
                }else{
                    url = document.location.href + "/general";
                }
                break;
            case "hours":
                var editLink = document.querySelector("#biz-info-form-hours a");
                if(editLink){
                    url = editLink.href;
                }else{
                    url = document.location.href + "/hours";
                }
                break;
            case "specialties":
                var editLink = document.querySelector("#biz-info-form-specialties a");
                if(editLink){
                    url = editLink.href;
                }else{
                    url = document.location.href + "/specialties";
                }
                break;
            case "history":
                var editLink = document.querySelector("#biz-info-form-history a");
                if(editLink){
                    url = editLink.href;
                }else{
                    url = document.location.href + "/history";
                }
                break;
            case "owner_info":
                var editLink = document.querySelector("#biz-info-form-bio a");
                if(editLink){
                    url = editLink.href;
                }else{
                    url = document.location.href + "/bio";
                }
                break;
            }

            window.callPhantom({
                "method": "add_next_step",
                "url": url
            });
        }, businessInfo.type);
    }
];

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
