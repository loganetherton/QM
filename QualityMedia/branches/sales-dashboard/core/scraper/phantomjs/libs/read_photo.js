
var yelpObject = {
	"account" : {},
	"userAgent" : null
};

var businessPhotos = {
	business_id : null,
	photos : []
};

var photoScrapper = function() {
	//Scrape photo pages
	page.evaluate(function() {
		var photoJson = {
			"id" : null,
			"url" : null,
			"caption" : null,
			"from_owner" : false,
			"from" : {},
			"actions" : {
				"edit_caption":false,
				"delete" : false,
				"flag" : false
			},
		}
        var isSlide = document.querySelector('#bizSlide');
        
        if(isSlide){
           //Add data
            window.callPhantom({
                "method" : "slideshow_present",
            }); 
        }
		
        var photosContArray = document.querySelectorAll('#biz-photos .local-photos-container');
		var data = [];
		for ( i = 0; i < photosContArray.length; i++) {
			var _photo = JSON.parse(JSON.stringify(photoJson));

			_photo.url = photosContArray[i].querySelector('.photo-box img').src;
			_photo.id = _photo.url.split('/')[_photo.url.split('/').length - 2];

			var _form = photosContArray[i].querySelector('form');
			if (_form != null) {
				//From owner
				_photo.actions['delete'] = true; 	//.delete removes a node from JSON
				_photo.actions['edit_caption'] = true;
				_photo.from_owner = true;
				_photo.caption = photosContArray[i].querySelector('.photo-actions textarea[name="caption"]').value;
				_photo.from = "owner";
			} else {
				_photo.actions.flag = true;
				_photo.caption = photosContArray[i].querySelector('.photo-actions .caption').innerText;
				_photo.from.name = photosContArray[i].querySelector('.photo-actions .attribution a').innerText;
				_photo.from.user_profile = photosContArray[i].querySelector('.photo-actions .attribution a').href;
				_photo.from.user_id = _photo.from.user_profile.split('?')[1].split('=')[1];
			}

			data.push(_photo);
		}

		//Add data
		window.callPhantom({
			"method" : "add_photos",
			"data" : data
		});

		//Pagination check
		var paginationDiv = document.querySelector('#pagination_direction');
		if (paginationDiv && (paginationDiv.innerText.indexOf("Next") > -1)) {
			var pages = paginationDiv.querySelectorAll('a');
			//This will include both Next and previous
			var nextPageUrl = pages[pages.length - 1].href;
			//Choose NEXT
			window.callPhantom({
				"method" : "add_scrapper_page_photos",
				"url" : nextPageUrl
			});
		} else {
			//End of photos
		}

	});
};


/**
 *Scrapper for slideshow(video+photo)
 */
var slidesScrapper = function() {
	//Scrape photo pages
	page.evaluate(function() {
		var photoJson = {
			"id" : null,
			"url" : null,
			"caption" : null,
			"from_owner" : false,
			"from" : {},
			"actions" : {
				"edit_caption":false,
				"delete" : false,
				"flag" : false
			},
		}
        
        var photosContArray = document.querySelectorAll('#biz-photos .local-photos-container');
		var data = [];
		for ( i = 0; i < photosContArray.length; i++) {
			var _photo = JSON.parse(JSON.stringify(photoJson));

			_photo.url = photosContArray[i].querySelector('.photo-box img').src;
			_photo.id = _photo.url.split('/')[_photo.url.split('/').length - 2];

			var _form = photosContArray[i].querySelector('form');
			if (_form != null) {
				//From owner
				_photo.actions['delete'] = true; 	//.delete removes a node from JSON
				_photo.actions['edit_caption'] = true;
				_photo.from_owner = true;
				_photo.caption = photosContArray[i].querySelector('.photo-actions textarea[name="caption"]').value;
				_photo.from = "owner";
			} else {
				_photo.actions.flag = true;
				_photo.caption = photosContArray[i].querySelector('.photo-actions .caption').innerText;
				_photo.from.name = photosContArray[i].querySelector('.photo-actions .attribution a').innerText;
				_photo.from.user_profile = photosContArray[i].querySelector('.photo-actions .attribution a').href;
				_photo.from.user_id = _photo.from.user_profile.split('?')[1].split('=')[1];
			}

			data.push(_photo);
		}

		//Add data
		window.callPhantom({
			"method" : "add_photos",
			"data" : data
		});

		//Pagination check
		var paginationDiv = document.querySelector('#pagination_direction');
		if (paginationDiv && (paginationDiv.innerText.indexOf("Next") > -1)) {
			var pages = paginationDiv.querySelectorAll('a');
			//This will include both Next and previous
			var nextPageUrl = pages[pages.length - 1].href;
			//Choose NEXT
			window.callPhantom({
				"method" : "add_scrapper_page_photos",
				"url" : nextPageUrl
			});
		} else {
			//End of photos
		}

	});
};


//Redirect to photos section
var redirectToPhotos= function() {
	//Open the photos page
	page.evaluate(function() {
		//document.location.href = document.querySelector(".photos a").href;
        var photos = document.querySelector(".photos a");
        if (photos){
            window.callPhantom({
                "method": "add_photos_scrapper",
                "type": "photos"
            });
            document.location.href = photos.href;
        } else {
            var slideshow = document.querySelector(".slideshow a");
            if(slideshow){
                window.callPhantom({
                    "method": "add_photos_scrapper",
                    "type": "slideshow"
                });
                
                //Process slideshow
                document.location.href = slideshow.href;    
            }
        }    
	});
}
