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

if (typeof Roblox == "undefined") {
    Roblox = {};
}

Roblox.SiteTouchEvent = (function () {
    var key = 'LastActivity';

    function getLastActivity() {
        if (localStorage == null) return new Date(0);
        var lastActivity;
        if (typeof localStorage != "undefined") {
            // assume localStorage is available (IE >= 8, and modern browsers)
            lastActivity = localStorage.getItem(key);
        }
        if (typeof lastActivity == "undefined" || lastActivity === null) {
            // get from cookie
            lastActivity = $.cookie(key);
        }
        var lastActivityTicks = Date.parse(lastActivity);
        if (lastActivity && !isNaN(lastActivityTicks)) {
            return new Date(lastActivityTicks);
        }
        else {
            // no value found, definitely fire an event
            return new Date(0); // Jan 1 1970 00:00:00 GMT
        }
    }

    function setLastActivity(lastActivity) {
        if (localStorage == null) return;
        if (typeof lastActivity == "undefined") {
            lastActivity = new Date(); // default to current date
        }
        // clear the unused storage location, in case we have switched locations
        if (typeof localStorage != "undefined") {
            if (my.useLocalStorage) {
                $.cookie(key, null);
            }
            else {
                localStorage.removeItem(key);
            }
        }
        // write the data
        if (my.useLocalStorage && typeof localStorage != "undefined") {
            // assume localStorage is available (IE >= 8, and modern browsers)
            localStorage.setItem(key, lastActivity);
        }
        else {
            // store in cookie
            $.cookie(key, lastActivity, { expires: 100 }); // 100 days
        }
    }

    function updateLastActivityAndFireEvent() {
        var lastActivity = getLastActivity();
        // 3600000ms = 1 hr
        if (Math.floor(((new Date()) - lastActivity) / 3600000) >= my.dateDiffThresholdInHours) {
            // send an event
            RobloxEventManager.triggerEvent('rbx_evt_sitetouch');
        }
        setLastActivity();
    }

    var my = {
        updateLastActivityAndFireEvent: updateLastActivityAndFireEvent,
        getLastActivity: getLastActivity,
        setLastActivity: setLastActivity,
        dateDiffThresholdInHours: 3,
        useLocalStorage: false
    };

    return my;
})();

}
/*
     FILE ARCHIVED ON 01:58:06 Sep 08, 2014 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:08 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 4.508
  exclusion.robots: 0.103
  exclusion.robots.policy: 0.092
  cdx.remote: 0.077
  esindex: 0.011
  LoadShardBlock: 270.502 (3)
  PetaboxLoader3.datanode: 124.507 (4)
  load_resource: 227.629
  PetaboxLoader3.resolve: 224.341
*/