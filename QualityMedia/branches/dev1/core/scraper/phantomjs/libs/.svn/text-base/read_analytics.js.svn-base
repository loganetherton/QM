//Read analytics data part


var analyticsData = {
	"business_id":null,
	"data":{},
};

var analyticsScrapper = {
	oneMonth : function() {
		page.evaluate(function() {
			var data = document.querySelector('body').innerText;
			window.callPhantom({
				"method" : "add_analytics_data",
				"data" : data,
				"time" : "one_month"
			});
		});
	},

	oneYear : function() {
		page.evaluate(function() {
			var data = document.querySelector('body').innerText;
			window.callPhantom({
				"method" : "add_analytics_data",
				"data" : data,
				"time" : "one_year"
			});
		});
	},

	twoYear : function() {
		page.evaluate(function() {
			var data = document.querySelector('body').innerText;
			window.callPhantom({
				"method" : "add_analytics_data",
				"data" : data,
				"time" : "two_year"
			});
		});
	},

	publishData : function() {
		//Analytics DATA
		page.evaluate(function() {
			window.callPhantom({
				"method" : "publish_info_data"
			});
		});
	}
};



//Initial step for analytics
var redirectToAnalytics = function() {
	//Open the analytics page
	page.evaluate(function() {
		var biz_id = document.location.href.split('/')[4];
		var arpu = document.querySelector('.js-revenue-per-lead-input').value;
		
        window.callPhantom({
			"method" : "base_url",
			"url" : "https://biz.yelp.com/biz_analytics/" + biz_id + "/json?period=",
			"arpu" : arpu
		});
	});
};

