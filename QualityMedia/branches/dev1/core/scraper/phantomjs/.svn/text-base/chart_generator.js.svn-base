//PhantomJS script to generate image charts
/**
 It accepts the following commands line arguments when ran from the terminal
 * @url url of the blank page
 * @data JSON data 
 * @options Options
 * @userAgent User agent to be used (Optional)
 
 Usage:
 phantomjs chart_generator.js '<url>' '<data>' '<options>'
 */

var page = new WebPage();
var system = require('system');
var fs = require("fs");

phantom.injectJs("libs/charts_config.ini");
phantom.injectJs("libs/charts_utils.js");
phantom.injectJs("chart_libs/chart_settings.js");

/*Page statuses*/
page.onConsoleMessage = function (msg) {
	utils.log(msg);
};
page.onLoadFinished = function () {
	loadInProgress = false;
	utils.log("load finished");
};
page.settings.loadImages = config.loadImages;

var stepIndex = 0,
	loadInProgress = false;
var charts = {};
var refinedJsonData = {};
var monthsLastDayCollection = [];
var datesArray = new Array();
var dailyValueTypes = ['num_ad_clicks_daily', 'num_page_views_daily', 'has_ads_daily', 'num_customer_actions_daily', 'mobile_percent_this_day'];

/**
 *Function to read the command line arguments
 **/

function readArguments() {
	//system.args[0] is for file name which is being executed

	if (system.args.length < 4 || system.args.length > 5) {
		console.log(constants.invalidArgumentsError);
		phantom.exit();
	}

	charts.url = system.args[1];
	charts.rawData = JSON.parse(system.args[2]);
	charts.options = JSON.parse(system.args[3]);
	charts.settings = chartSettings;

	charts.imageBaseDir = charts.options.imageDir ? charts.options.imageDir : config.imagesDir;
	
	charts.dataType = new Array();
	charts.individualData = {};
	charts.dateOnlyData = JSON.parse(JSON.stringify(charts.rawData));
	
	if(charts.options.width && charts.options.height){
		page.viewportSize = { width: charts.options.width, height: charts.options.height };
	}
	
	delete charts.dateOnlyData['businessId'];
	delete charts.dateOnlyData['dateEnd'];
	delete charts.dateOnlyData['dateStart'];
	delete charts.dateOnlyData['yelpBusinessId'];

	//Check for user-agent, if yes then set to the page settings
	if (system.args[4]) {
		page.settings.userAgent = system.args[4];
	}
}

//Read the command line arguments for options
readArguments();

page.onCallback = function (json) {
};

var injectScript = function () {
	page.injectJs("chart_libs/Chart.js") ? init() : console.log('failed to inject lib');
};

var steps = [

    function () {
		page.open(charts.url, function (status) {
			if (status !== 'success') {
				console.log(constants.noNetworkError);
				utils.phantomExit();
			} else {
				utils.log('Success in opening  page');
			}
		});
    },
    injectScript
];


var utilFunctions = {
	//Function to get js DATE object from string
	createDateObject: function (strDate, separator) {
		var dateObj = new Date();
		var parts;
		strDate = strDate.toString();

		if (separator) {
			parts = strDate.split('-');
		} else {
			parts = [strDate.substr(0, 4), strDate.substr(4, 2), strDate.substr(6, 2)];
		}

		dateObj.setFullYear(parts[0], parts[1] - 1, parts[2]); // year, month (0-based), day
		return dateObj;
	},

	//Function to get the next DATE(js object)
	getNextDateObject: function (date, offset) {
		offset = offset ? parseInt(offset) : 1;
		return new Date(date.getFullYear(), date.getMonth(), date.getDate() + offset); // create new increased date
	},

	//Function to get the next date(string in YYYYMMDD format)
	getDateString: function (date, separator) {
		if (separator) {
			return date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
		} else {
			return date.getFullYear() + "" + (date.getMonth() + 1) + "" + date.getDate();
		}
	},

	monthNames: ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
	],

	getMonthNameByIndex: function (index) {
		return utilFunctions.monthNames[index - 1].substr(0, 3);
	},

	sortNumber: function (a, b) {
		return a - b;
	}
};


var chartRenderer = function (data, name) {
	return function () {
		page.evaluate(function (data, settings, name) {
			var canvasElement = document.getElementById("canvas");
			var myLine = new Chart(canvasElement.getContext("2d")).Bar(data,settings);
		}, data, charts.settings, name);
	}
};

var chartCapture = function (name) {
	return function () {
		page.render(charts.imageBaseDir + "/" + name + '.png');
	}
};


var dataProcessor = {
	//Step before main data extraction from JSON
	pre: function (rawData) {
		for (var dataTypeKey in rawData) {
			charts.dataType.push(dataTypeKey);

			for (var dateKey in rawData[dataTypeKey]) {
				if (dateKey != "" && dateKey != null) {
					var dateObject = utilFunctions.createDateObject(dateKey);
					dataProcessor.looper(rawData[dataTypeKey][dateKey], dataTypeKey, dateObject);
				}
			}
		}

		//Last day calculator
		dataProcessor.lastDayProcessor(datesArray);

		//Chart data calculator step
		for (var index in charts.dataType) {
			if (dailyValueTypes.indexOf(charts.dataType[index]) < 0) {
				dataProcessor.chartDataMaker(charts.dataType[index], 'monthly');
			} else {
				dataProcessor.chartDataMaker(charts.dataType[index], 'daily');
				var arr = charts.individualData[charts.dataType[index]]["monthlyAverage"];
				var avgVal = Math.round(arr.reduce(function(prev,cur){return prev + cur})/arr.length);
				
				for(i=0;i<arr.length;i++){
					arr[i] = avgVal;
				}
			}
		}

		//Chart rendering step
		for (var key in charts.individualData) {
			steps.push(chartRenderer(charts.individualData[key], key));
			steps.push(chartCapture(key));
		}

		//Push an empty function at end
		steps.push(function () {});
	},

	looper: function (dataValue, type, date) {
		//Check year object
		if (!refinedJsonData[date.getFullYear()]) {
			refinedJsonData[date.getFullYear()] = {};
			datesArray[date.getFullYear()] = new Array();
		}

		//Check Month object
		if (!refinedJsonData[date.getFullYear()][date.getMonth() + 1]) {
			refinedJsonData[date.getFullYear()][date.getMonth() + 1] = {};
			datesArray[date.getFullYear()][date.getMonth() + 1] = new Array();
		}


		//Check day object
		if (!refinedJsonData[date.getFullYear()][date.getMonth() + 1][date.getDate()]) {
			refinedJsonData[date.getFullYear()][date.getMonth() + 1][date.getDate()] = {};
			//Push date to dates array
			datesArray[date.getFullYear()][date.getMonth() + 1].push(date.getDate());
		}

		//refinedJsonData[date.getFullYear()][date.getMonth() + 1][date.getDate()][type] = parseInt(dataValue);
		refinedJsonData[date.getFullYear()][date.getMonth() + 1][date.getDate()][type] = (dataValue);

	},


	lastDayProcessor: function (datesArray) {
		for (var year in datesArray) {
			for (var month in datesArray[year]) {
				var lastDayIndex;

				//Sorting
				datesArray[year][month].sort(utilFunctions.sortNumber);

				//Get the last day
				for (var day in datesArray[year][month]) {
					lastDayIndex = day;
				}

				var tempIndex = parseInt(year + month);
				monthsLastDayCollection[tempIndex] = datesArray[year][month][lastDayIndex];
			}
		}
	},

	chartDataMaker: function (name, type) {

		charts.individualData[name] = {};
		charts.individualData[name]["labels"] = new Array();
		charts.individualData[name]["datasets"] = new Array();

		if (type === "monthly") {
			charts.individualData[name]["datasets"][0] = {};
			charts.individualData[name]["datasets"][0]["fillColor"] = "rgba(40,155,253,1)";
			charts.individualData[name]["datasets"][0]["negativeColor"] = "rgba(255,68,68,1)";
			charts.individualData[name]["datasets"][0]["labelColor"] = "white";
			charts.individualData[name]["datasets"][0]["data"] = new Array();
			charts.individualData[name]["datasets"][0]["label"] = new Array();
			charts.individualData[name]["dataType"] = "monthly";
			for (var year in datesArray) {
				
				for (var month in datesArray[year]) {
					charts.individualData[name]["labels"].push(utilFunctions.getMonthNameByIndex(parseInt(month)) + " " + year);
					var tempIndex = parseInt(year.toString() + month.toString());
					var actualData = refinedJsonData[year][month][monthsLastDayCollection[tempIndex]][name];
					charts.individualData[name]["datasets"][0]["data"].push(parseInt(actualData));
				}
			}

			charts.individualData[name]["datasets"][0]["label"] = charts.individualData[name]["datasets"][0]["data"];
		} else {
			
			charts.individualData[name]["monthlyAverage"] = new Array();
			charts.individualData[name]["dataType"] = "daily";
			for (var year in datesArray) {
				for (var month in datesArray[year]) {
					charts.individualData[name]["labels"].push(utilFunctions.getMonthNameByIndex(parseInt(month)) + " " + year);
					
					var monthlyTotal = 0,numberOfDays = 0;
					
					for (var day in datesArray[year][month]) {

						//if (refinedJsonData[year][month][datesArray[year][month][day]][name]) {
						if (refinedJsonData[year][month][datesArray[year][month][day]].hasOwnProperty(name)) {
							if (!charts.individualData[name]["datasets"][day]) {
								charts.individualData[name]["datasets"][day] = {}
								charts.individualData[name]["datasets"][day]["data"] = new Array();
								
								if(charts.individualData[name]["labels"].length>1){
									charts.individualData[name]["datasets"][day]["data"].push(null);
								}
							}

							charts.individualData[name]["datasets"][day]["fillColor"] = "rgba(40,155,253,1)";
							charts.individualData[name]["datasets"][day]["negativeColor"] = "rgba(255,68,68,1)";
							charts.individualData[name]["datasets"][day]["data"].push(refinedJsonData[year][month][datesArray[year][month][day]][name]);

							monthlyTotal += refinedJsonData[year][month][datesArray[year][month][day]][name];
							numberOfDays++;
						}
					} //End of for each for DAY
					
					charts.individualData[name]["monthlyAverage"].push(parseInt(monthlyTotal / numberOfDays));
				} //End of for each for MONTH
			} //End of for each for YEAR
		}
	}
};

var init = function () {
	page.evaluate(function (options) {
		//Wipe it clean
		var body = document.getElementsByTagName("body")[0];
		body.innerHTML = "";
		var canvasEl = document.createElement("canvas");
		var height = options.height?options.height:200;
		var width = options.width?options.width:400;

		canvasEl.setAttribute("id", "canvas");
		canvasEl.setAttribute("height", parseInt(height));
		canvasEl.setAttribute("width", parseInt(width));
		body.appendChild(canvasEl);
	}, charts.options);
};


//Start data processor
dataProcessor.pre(charts.dateOnlyData);


//Interval to execute steps
var interval = setInterval(function () {

	if (!loadInProgress && typeof steps[stepIndex] == "function") {
		steps[stepIndex]();
		stepIndex++;
	}

	if (typeof steps[stepIndex] != "function") {
		var date = new Date();
		var fullDate = date.getDate() + "/" + (date.getMonth() + 1) + "/" + date.getFullYear() + "  " + date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();
		utils.log(fullDate);
		utils.log('Exiting');
		phantom.exit();
	}
}, utils.randomInterval());
