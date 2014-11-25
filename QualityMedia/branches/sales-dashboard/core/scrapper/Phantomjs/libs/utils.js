var utils = {
	
    invalidArgumentsError : 'Invalid number of arguments. Please check the arguments passed!',
    
    randomInterval: function () {
        return (Math.floor(Math.random() * (config.stepInterval.end - config.stepInterval.start + 1) + config.stepInterval.start) * 1000);
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

    objectIsEmpty: function (map) {
        for (var key in map) {
            if (map.hasOwnProperty(key)) {
                return false;
            }
        }
        return true;
    },

    intialRun : true,
    
    log: function (value) {
       	 //console.log(value);
       	 var that = this;
         var file = fs.open("log.txt", "a");
         
         if(that.intialRun){
			file.write("\n------------------------------\n------------------------------\n");
			var date = new Date();
			var fullDate = date.getDate() + "/" + (date.getMonth()-1) + "/"+date.getFullYear() +"  "+ date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds(); 
			file.write(fullDate);
            file.write(" ,\t");
			file.write(system.args[0]);
            file.write(" ,\tUsername: ");
            file.write(yelpObject.account['username']);
            that.intialRun = false;
		 }

       	 file.write("\n");
         file.write(value);
         file.close();
    },
};



var phantomPage = {
    onConsoleMessage: function (msg) {
        utils.log(msg);
    },

    onLoadStarted: function () {
        loadInProgress = true;
    },

    onLoadFinished: function () {
        loadInProgress = false;
        //page.render('images/' + pgImg + '.png');
        pgImg++;
        
        //Check if session is still logged in
        if(page.url.indexOf('biz.yelp.com/login?return_url')>-1){
			console.log('{"error":"logged_out"}');
			phantom.exit();
		}
        utils.log("load finished");
    }
}