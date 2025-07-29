var _____WB$wombat$assign$function_____ = function(name) {return (self._wb_wombat && self._wb_wombat.local_init && self._wb_wombat.local_init(name)) || self[name]; };
if (!self.__WB_pmw) { self.__WB_pmw = function(obj) { this.__WB_source = obj; return this; } }
{
  let window = _____WB$wombat$assign$function_____("window");
  let self = _____WB$wombat$assign$function_____("self");
  let document = _____WB$wombat$assign$function_____("document");
  let location = _____WB$wombat$assign$function_____("location");
  let top = _____WB$wombat$assign$function_____("top");
  let parent = _____WB$wombat$assign$function_____("parent");
  let frames = _____WB$wombat$assign$function_____("frames");
  let opener = _____WB$wombat$assign$function_____("opener");

var GoogleAnalyticsEvents = {
    LocalEventLog: [],
    FireEvent: function(args) {
        if (window._gaq) {
            if (!window.GoogleAnalyticsDisableRoblox2) {
                var eventsArray = ["_trackEvent"];
                eventsArray = eventsArray.concat(args);
                _gaq.push(eventsArray);
                GoogleAnalyticsEvents.LocalEventLog.push(makeGoogleAnalyticsLogObject(eventsArray));
            }
            var eventsArrayB = ["b._trackEvent"];
            eventsArrayB = eventsArrayB.concat(args);
            _gaq.push(eventsArrayB);
            GoogleAnalyticsEvents.LocalEventLog.push(makeGoogleAnalyticsLogObject(eventsArrayB));
        }
    },
    ViewVirtual: function (url) {
        if (window.GoogleAnalyticsReplaceUrchinWithGAJS) {
            if (window._gaq) {
                !window.GoogleAnalyticsDisableRoblox2 && window._gaq.push(['_trackPageview', url]);
                window._gaq.push(['b._trackPageview', url]);
            }
        } else {
            urchinTracker && urchinTracker(url);
        }
    },
    TrackTransaction: function (orderId, priceTotal) {
        if (window._gaq) {
            var transArray = ['_addTrans', orderId, 'Roblox', priceTotal, '0', '0', 'San Mateo', 'California', 'USA'];
                        //transaction account, transactionID, Store Name, total price, Tax, Shipping, City, State, Country
            if (!window.GoogleAnalyticsDisableRoblox2) {
                _gaq.push(transArray);
                GoogleAnalyticsEvents.LocalEventLog.push(makeGoogleAnalyticsLogObject(transArray));
            }
            transArray[0] = 'b.' + transArray[0];   //transaction account should be 'b._addTrans'
            _gaq.push(transArray);
            GoogleAnalyticsEvents.LocalEventLog.push(makeGoogleAnalyticsLogObject(transArray));
        }
    },
    TrackTransactionItem: function (orderId, sku, name, category, price) {
        if (window._gaq) {
            var itemArray = ['_addItem', orderId, sku, name, category, price, 1];
            var trackTransArray = ['_trackTrans'];
            if (!window.GoogleAnalyticsDisableRoblox2) {
                _gaq.push(itemArray);
                GoogleAnalyticsEvents.LocalEventLog.push(makeGoogleAnalyticsLogObject(itemArray));
                _gaq.push(trackTransArray);
                GoogleAnalyticsEvents.LocalEventLog.push(makeGoogleAnalyticsLogObject(trackTransArray));
            }
            itemArray[0] = 'b.' + itemArray[0];
            trackTransArray[0] = 'b.' + trackTransArray[0];
            _gaq.push(itemArray);
            GoogleAnalyticsEvents.LocalEventLog.push(makeGoogleAnalyticsLogObject(itemArray));
            _gaq.push(trackTransArray);
            GoogleAnalyticsEvents.LocalEventLog.push(makeGoogleAnalyticsLogObject(trackTransArray));
        }
    }
};

function makeGoogleAnalyticsLogObject(event_params) {
    var newLogObject = {};
    newLogObject.event = event_params;
    newLogObject.timestamp = new Date().getTime();
    return newLogObject;
}

function GoogleAnalyticsTimingTracker(category, variable, optLabel, isDebug) {
    this.maxTime = 1 * 60 * 1000;
    this.category = category;
    this.variable = variable;
    this.label = optLabel ? optLabel : undefined;
    this.isDebug = isDebug;
}

GoogleAnalyticsTimingTracker.prototype.getTimeStamp = function() {
    if (window.performance && window.performance.now) {
        return Math.round(window.performance.now());
    }
    return new Date().getTime();
};

GoogleAnalyticsTimingTracker.prototype.start = function () {
    this.startTime = this.getTimeStamp();
};

GoogleAnalyticsTimingTracker.prototype.stop = function () {
    this.elapsedTime = this.getTimeStamp() - this.startTime;
};

/**
 * Send data to Google Analytics with the configured variable, action,
 * elapsed time and label. This function performs a check to ensure that
 * the elapsed time is greater than 0 and less than MAX_TIME. This check
 * ensures no bad data is sent if the browser client time is off. If
 * debug has been enebled, then the sample rate is overridden to 100%
 * and all the tracking parameters are outputted to the console.
 * @return {Object} This TrackTiming instance. Useful for chaining.
 */
GoogleAnalyticsTimingTracker.prototype.send = function () {
    if (0 < this.elapsedTime && this.elapsedTime < this.maxTime) {

        var command = ['b._trackTiming', this.category, this.variable, this.elapsedTime, this.label, 100];

        if (this.isDebug) {
            if (window.console && window.console.log) {
                console.log(command);
            }
        }

        window._gaq.push(command);
    }
};



}
/*
     FILE ARCHIVED ON 01:58:08 Sep 08, 2014 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:00 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 0.672
  exclusion.robots: 0.078
  exclusion.robots.policy: 0.069
  cdx.remote: 0.062
  esindex: 0.009
  LoadShardBlock: 43.885 (3)
  PetaboxLoader3.datanode: 44.335 (4)
  load_resource: 170.13
  PetaboxLoader3.resolve: 166.496
*/