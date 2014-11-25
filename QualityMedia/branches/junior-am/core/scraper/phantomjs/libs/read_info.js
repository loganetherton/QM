var infoUrls = {}, sections = ['basic_info', 'hours', 'specialties', 'history', 'owner_info'];

var businessInfo = {
    business_id : null,
    info : {
        'basic_info' : {},
        'hours' : [],
        'specialties' : {},
        'history' : {},
        'owner_info' : {}
    }
};

//Function to add steps to the scrapper
var addInfoScrapperSteps = function() {
    for ( i = 0; i < sections.length; i++) {
        var url = infoUrls[sections[i]];
        if (infoUrls[sections[i]]!=null){
            steps.push(redirectStep(url));
            steps.push(infoScrapper[sections[i]]);
        }
    }
    
    //NEXT PART: Move to next part of data(REVIEWS)
    steps.push(redirectToHome);
    steps.push(redirectToReviews);
    steps.push(pageScraper);
};

var redirectStep = function(_url) {
    return function() {
        page.evaluate(function(url) {
            document.location.href = url;
        }, _url);
    }
}

//Scrappers for various info
var infoScrapper = {
    //Basic info scrapper
    basic_info : function() {
        page.evaluate(function(stringContstants) {
            var data = {};
            var basic_info = document.querySelector('#edit-basic-info');

            
            //MK make a new object
            data.name ={};
            data.lockedAttributes = new Array();
            
            data.name.biz_name = basic_info.querySelector('.BusinessName input').value;
            if (basic_info.querySelector('.BusinessName').classList.contains('locked')) {
                data.lockedAttributes.push('name.biz_name');
            }

            //MK make a new object
            data.address = {};
                
            data.address.line1 = basic_info.querySelector('.BusinessStreetAddress1 input').value;
            if (basic_info.querySelector('.BusinessStreetAddress1').classList.contains('locked')) {
                data.lockedAttributes.push('address.line1');
            }
            
            data.address.line2 = basic_info.querySelector('.BusinessStreetAddress2 input').value;
            if (basic_info.querySelector('.BusinessStreetAddress2').classList.contains('locked')) {
                data.lockedAttributes.push('address.line2');
            }
            data.address.city = basic_info.querySelector('.BusinessCity input').value;
            if (basic_info.querySelector('.BusinessCity').classList.contains('locked')) {
                data.lockedAttributes.push('address.city');
            }
            data.address.state = basic_info.querySelector('.BusinessState input').value;
            if (basic_info.querySelector('.BusinessState').classList.contains('locked')) {
                data.lockedAttributes.push('address.state');
            }
            
            data.address.zip = basic_info.querySelector('.BusinessZipCode input').value;
            if (basic_info.querySelector('.BusinessZipCode').classList.contains('locked')) {
                data.lockedAttributes.push('address.zip');
            }
            
            data.phone = basic_info.querySelector('.BusinessPhoneNumber input').value;
            if (basic_info.querySelector('.BusinessPhoneNumber').classList.contains('locked')) {
                data.lockedAttributes.push('phone');
            }
            data.website = basic_info.querySelector('.BusinessUrl input').value;
            if (basic_info.querySelector('.BusinessUrl').classList.contains('locked')) {
                data.lockedAttributes.push('website');
            }
            
            if(basic_info.querySelector('.MenuUrl input')) {
                data.menu_url = basic_info.querySelector('.MenuUrl input').value;
                if (basic_info.querySelector('.MenuUrl').classList.contains('locked')) {
                    data.lockedAttributes.push('menu_url');
                }
            }
            
            //MK make a new object
            data.location ={};
            
            data.location.latitude = document.querySelector('#latitude').value;
            data.location.longitude = document.querySelector('#longitude').value;

            
            var isLocked = document.querySelector('.BusinessCategoryYelp').querySelector('.locked-attr-note');
            
            if(isLocked){
                var categoriesList = document.querySelector('.BusinessCategoryYelp').querySelectorAll('.attr-field li');
                var _categoriesTemplate ;
                if(categoriesList.length>0){
                    data.categories = new Array();
                }
                for(i=0;i<categoriesList.length;i++){
                    //_categoriesTemplate = JSON.parse(JSON.stringify(categoriesTemplate));
                    _categoriesTemplate = {}
                    _categoriesTemplate.name = categoriesList[i].innerText;
                    data.categories.push(_categoriesTemplate);
                }
                data.lockedAttributes.push('categories');
            
            }else{
                
                var categoriesList = document.querySelectorAll('#BusinessCategoryYelp_categories .category-name');
                var _categoriesTemplate;
                if(categoriesList.length>0){
                    data.categories = new Array();
                }
                for(i=0;i<categoriesList.length;i++){
                    //_categoriesTemplate = JSON.parse(JSON.stringify(categoriesTemplate));
                    _categoriesTemplate = {}
                    _categoriesTemplate.name = categoriesList[i].innerText.replace("Remove","");
                    _categoriesTemplate.value = categoriesList[i].querySelector('input').value;
                    data.categories.push(_categoriesTemplate);
                }   
            }
            
            
            var additional_info = document.querySelectorAll('.additional-info .voteable-attribute');
            if (additional_info) {
                data.additional_info = {}; 
                
                var label;
                for(i=0;i<additional_info.length;i++){
                    label = additional_info[i].querySelector('label').innerText;
                    
                    switch(label){
                        case stringContstants.INFO_CC : 
                                if(additional_info[i].querySelector('#av_box\\:BusinessAcceptsCreditCards\\:True').checked){
                                    data.additional_info.credit_cards =  true;    
                                }else if(additional_info[i].querySelector('#av_box\\:BusinessAcceptsCreditCards\\:False').checked){
                                    data.additional_info.credit_cards =  false;    
                                }else{
                                    data.additional_info.credit_cards =  null;
                                }
                                
                                break;
                        case stringContstants.INFO_WHEELCHAIR : 
                                if(additional_info[i].querySelector('#av_box\\:WheelchairAccessible\\:True').checked){
                                    data.additional_info.wheelchair_accessible = true;
                                }else if(additional_info[i].querySelector('#av_box\\:WheelchairAccessible\\:False').checked){
                                    data.additional_info.wheelchair_accessible = false;
                                }else{
                                    data.additional_info.wheelchair_accessible = null;
                                }
                            
                                break;
                        case stringContstants.INFO_INSURANCE : 
                                if(additional_info[i].querySelector('#av_box\\:AcceptsInsurance\\:True').checked){
                                    data.additional_info.insurance = true
                                }else if(additional_info[i].querySelector('#av_box\\:AcceptsInsurance\\:False').checked){
                                    data.additional_info.insurance = false;
                                }else {
                                    data.additional_info.insurance = null;
                                }
                                
                                break;
                        
                        case stringContstants.INFO_APPOINTMENT : 
                                if(additional_info[i].querySelector('#av_box\\:ByAppointmentOnly\\:True').checked){
                                    data.additional_info.appointment_only = true;
                                }else if(additional_info[i].querySelector('#av_box\\:ByAppointmentOnly\\:False').checked){
                                    data.additional_info.appointment_only = false;
                                }else {
                                    data.additional_info.appointment_only = null;
                                }
                                
                                break;
                        
                        case stringContstants.INFO_RESERVATION : 
                                if(additional_info[i].querySelector('#av_box\\:RestaurantsReservations\\:True').checked){
                                    data.additional_info.reservation = true;
                                }else if(additional_info[i].querySelector('#av_box\\:RestaurantsReservations\\:False').checked){
                                    data.additional_info.reservation = false;
                                }else {
                                    data.additional_info.reservation = null;
                                }
                                
                                break;
                        
                        case stringContstants.INFO_WAITER : 
                                if(additional_info[i].querySelector('#av_box\\:RestaurantsTableService\\:True').checked){
                                    data.additional_info.waiter_service = true;
                                }else if(additional_info[i].querySelector('#av_box\\:RestaurantsTableService\\:False').checked){
                                    data.additional_info.waiter_service = false;
                                }else {
                                    data.additional_info.waiter_service = null;
                                }
                                
                                break;
                        
                        case stringContstants.INFO_DELIVERY : 
                                if(additional_info[i].querySelector('#av_box\\:RestaurantsDelivery\\:True').checked){
                                    data.additional_info.delivery = true;
                                }else if(additional_info[i].querySelector('#av_box\\:RestaurantsDelivery\\:False').checked){
                                    data.additional_info.delivery = false;
                                }else {
                                    data.additional_info.delivery = null;
                                }
                                
                                break;
                        
                        case stringContstants.INFO_TAKEOUT : 
                                if(additional_info[i].querySelector('#av_box\\:RestaurantsTakeOut\\:True').checked){
                                    data.additional_info.takeout = true;
                                }else if(additional_info[i].querySelector('#av_box\\:RestaurantsTakeOut\\:False').checked){
                                    data.additional_info.takeout = false;
                                }else {
                                    data.additional_info.takeout = null;
                                }
                                
                                break;
                        
                        case stringContstants.INFO_OUTDOOR_SEATING : 
                                if(additional_info[i].querySelector('#av_box\\:OutdoorSeating\\:True').checked){
                                    data.additional_info.outdoor_seating = true;
                                }else if(additional_info[i].querySelector('#av_box\\:OutdoorSeating\\:False').checked){
                                    data.additional_info.outdoor_seating = false;
                                }else {
                                    data.additional_info.outdoor_seating = null;
                                }
                                
                                break;
                        case stringContstants.INFO_DOGS : 
                                if(additional_info[i].querySelector('#av_box\\:DogsAllowed\\:True').checked){
                                    data.additional_info.dogs_allowed = true;
                                }else if(additional_info[i].querySelector('#av_box\\:DogsAllowed\\:False').checked){
                                    data.additional_info.dogs_allowed = false;
                                }else {
                                    data.additional_info.dogs_allowed =null;
                                }
                                 
                                break;
                        case stringContstants.INFO_CATERS : 
                                if(additional_info[i].querySelector('#av_box\\:Caters\\:True').checked){
                                    data.additional_info.caters = true;
                                }else if(additional_info[i].querySelector('#av_box\\:Caters\\:False').checked){
                                    data.additional_info.caters = false;
                                }else {
                                    data.additional_info.caters = null;
                                }
                                
                                break;
                        case stringContstants.INFO_HAPPY_HOUR : 
                                if(additional_info[i].querySelector('#av_box\\:HappyHour\\:True').checked){
                                    data.additional_info.happy_hour = true;
                                }else if(additional_info[i].querySelector('#av_box\\:HappyHour\\:False').checked){
                                    data.additional_info.happy_hour = false;
                                }else {
                                    data.additional_info.happy_hour = null;
                                }
                                
                                break;
                        case stringContstants.INFO_COAT_CHECK : 
                                if(additional_info[i].querySelector('#av_box\\:CoatCheck\\:True').checked){
                                    data.additional_info.coat_check = true;
                                }else if(additional_info[i].querySelector('#av_box\\:CoatCheck\\:False').checked){
                                    data.additional_info.coat_check = false;
                                }else {
                                    data.additional_info.coat_check = null;
                                }
                                
                                break;
                                
                        case stringContstants.INFO_PARKING :
                                data.additional_info.parking = true;
                                var types = additional_info[i].querySelectorAll('.inputFields li');
                                if(types.length>0){
                                    data.additional_info.parking_detail ={};
                                }
                                for(j=0;j<types.length;j++){
                                    var field = types[j].querySelector('input');
                                    data.additional_info.parking_detail[field.name.split('_')[1]] = field.checked;
                                }
                                break;
                        case stringContstants.INFO_WIFI : 
                                data.additional_info.wifi = true;
                                var types = additional_info[i].querySelectorAll('.inputFields li');
                                if(types.length>0){
                                    data.additional_info.wifi_detail ={};
                                }
                                for(j=0;j<types.length;j++){
                                    var field = types[j].querySelector('input');
                                    data.additional_info.wifi_detail[field.id.split(':')[2].toLowerCase()] = field.checked;
                                }
                                break;
                                
                        case stringContstants.INFO_ALCOHOL : 
                                data.additional_info.alcohol = true;
                                var types = additional_info[i].querySelectorAll('.inputFields li');
                                if(types.length>0){
                                    data.additional_info.alcohol_detail ={};
                                }
                                for(j=0;j<types.length;j++){
                                    var field = types[j].querySelector('input');
                                    if(field.id.split(':')[2]=="none"){
                                        data.additional_info.alcohol_detail['no'] = field.checked;
                                    }else{
                                        data.additional_info.alcohol_detail[field.id.split(':')[2].toLowerCase()] = field.checked;  
                                    }
                                    
                                }
                                break;
                        case stringContstants.INFO_SMOKING : 
                                data.additional_info.smoking = true;
                                var types = additional_info[i].querySelectorAll('.inputFields li');
                                if(types.length>0){
                                    data.additional_info.smoking_detail ={};
                                }
                                for(j=0;j<types.length;j++){
                                    var field = types[j].querySelector('input');
                                    data.additional_info.smoking_detail[field.id.split(':')[2].toLowerCase()] = field.checked;
                                }
                                break;
                                
                        default :;
                    }
                }
            }
            
            //Add data
            window.callPhantom({
                "method" : "add_info",
                "type" : "basic_info",
                "data" : data
            });
        },constants);   //Page.eval ends
    },

    //Hours info scrapper
    hours : function() {
        page.evaluate(function() {
            var data = [];
            var dataTemplate = {};

            var working_days = document.querySelectorAll('#day-hours-BusinessHours .hours-display .hours');

            for ( i = 0; i < working_days.length; i++) {
                var _template = JSON.parse(JSON.stringify(dataTemplate));
                _template.day = working_days[i].querySelector('.weekday').innerText;
                _template.start_time = working_days[i].querySelector('.start').innerText;
                _template.end_time = working_days[i].querySelector('.end').innerText;
                _template.value = working_days[i].querySelector('input[name="BusinessHours"]').value;
                data.push(_template);
            }

            //Add data
            window.callPhantom({
                "method" : "add_info",
                "type" : "hours",
                "data" : data
            });
        });
    },

    //specialties info scrapper
    specialties : function() {
        page.evaluate(function() {
            var data = {};

            data.speciality = document.querySelector('#AboutThisBizSpecialties').value;
            //Add data
            window.callPhantom({
                "method" : "add_info",
                "type" : "specialties",
                "data" : data
            });
        });
    },

    //History info scrapper
    history : function() {
        page.evaluate(function() {
            var data = {};

            data.year_established = document.querySelector('#AboutThisBizYearEstablished').value;
            data.history = document.querySelector('#AboutThisBizHistory').value;

            //Add data
            window.callPhantom({
                "method" : "add_info",
                "type" : "history",
                "data" : data
            });
        });
    },

    //Owner info scrapper
    owner_info : function() {
        page.evaluate(function() {
            var data = {};

            data.first_name = document.querySelector('#AboutThisBizBioFirstName').value;
            data.last_initial = document.querySelector('#AboutThisBizBioLastName').value;
            data.role = document.querySelector('#AboutThisBizRole').value;
            data.bio = document.querySelector('#AboutThisBizBio').value;
            data.photo = document.querySelector('#bio_photo_options input:checked').id;
            
            var photo_options = document.querySelectorAll('#bio_photo_options input[type="radio"]')
            data.photo_options = new Array();
            for(i=0;i<photo_options.length;i++){
                data.photo_options.push(photo_options[i].id);   
            }
             
            //Add data
            window.callPhantom({
                "method" : "add_info",
                "type" : "owner_info",
                "data" : data
            });
        });
    }
}


var infoInitStep = function() {
    //Open first section
    page.evaluate(function() {
        var infoUrls = {};
        
        var basic = document.querySelector("#basic_business_info a");
        if(basic){
            infoUrls.basic_info = basic.href;   
        }
        
        var hours =  document.querySelector("#biz-info-form-hours a");
        if(hours){
            infoUrls.hours = hours.href;    
        }
        
        var specialties = document.querySelector("#biz-info-form-specialties a");
        if(specialties){
            infoUrls.specialties = specialties.href;    
        }
        
        var history = document.querySelector("#biz-info-form-history a");
        if(history){
            infoUrls.history = history.href;    
        }
        
        var owner_info = document.querySelector("#biz-info-form-bio a");
        if(owner_info){
            infoUrls.owner_info = owner_info.href;  
        }
        
        window.callPhantom({
            "method" : "add_infoUrl",
            "data" : infoUrls
        });
    });
};

//Redirect to info section
var redirectToInfo = function() {
    //Open the info page
    page.evaluate(function() {
        document.location.href = document.querySelector(".info a").href;
    });
};

//ACTUAL SCRAPER STEPS
//var steps = [common.openLogin, common.login, common.checkLoginStatus,redirectToInfo,infoInitStep];
