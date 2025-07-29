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

RobloxEventManager = new function () {
    var cookieStoreEvents = [];
    var dataStore = {};
    this.enabled = false;
    this.initialized = false;
    this.eventQueue = [];

    function getCookieValue(cookieName) {
        var regex = new RegExp(cookieName + '=([^;]*)');
        var match = regex.exec(document.cookie);

        if (match)
            return match[1];

        return null;
    }
    
    function parseDotNetCookie(cookieValue) {
        var cookieObject = {};
        var keyVals = cookieValue.split('&');
        for (var i = 0; i < keyVals.length; i++) {
            var keyVal = keyVals[i].split('=');
            cookieObject[keyVal[0]] = keyVal[1];
        }
        return cookieObject;
    }
    
    function getDotNetCookie(name) {
        var value = getCookieValue(name);
        if (value)
            return parseDotNetCookie(value);

        return null;
    }

    this.initialize = function (enabled) {
        this.initialized = true;
        this.enabled = enabled;
        while (this.eventQueue.length > 0) {
            var event = this.eventQueue.pop();
            this.triggerEvent(event.eventName, event.args);
        }
    };

    this.getMarketingGuid = function () {
        var c = getDotNetCookie('RBXEventTracker');
        if (c != null)
            return c['browserid'];
        return -1;
    };

    this.triggerEvent = function (eventName, args) {
        if (this.initialized) {
            if (this.enabled) {
                if (typeof args === 'undefined')
                    args = {};
                args.guid = this.getMarketingGuid();
                if (args.guid != -1)
                    $(document).trigger(eventName, [args]);
            }
        } else {
            this.eventQueue.push({ eventName: eventName, args: args });
        }
    };

    this.registerCookieStoreEvent = function (eventName) {
        cookieStoreEvents.push(eventName);
    };

    this.insertDataStoreKeyValuePair = function (key, value) {
        dataStore[key] = value;
    };

    this.monitorCookieStore = function () {
        try {
            if (typeof Roblox === "undefined" || typeof Roblox.Client === "undefined" || window.location.protocol == "https:")
                return;

            var plugin = Roblox.Client.CreateLauncher(false);
            if (plugin == null)
                return;

            for (var i = 0; i < cookieStoreEvents.length; i++) {
                try {
                    var eventName = cookieStoreEvents[i];
                    var storedValue = plugin.GetKeyValue(eventName);

                    if (storedValue != '' && storedValue != '-1' && storedValue != 'RBX_NOT_VALID') {
                        var args = eval('(' + storedValue + ')');   // has userId and placeId
                        args['userType'] = args['userId'] > 0 ? 'user' : 'guest';
                        RobloxEventManager.triggerEvent(eventName, args);
                        plugin.SetKeyValue(eventName, 'RBX_NOT_VALID');
                    }
                }
                catch (err) {

                }
            }
        }
        catch (err) {
            // If we update in the middle of checking cookies, let the monitor do the remaining cookies at the next interval
        }
    };

    this.startMonitor = function () {
        var interval, timeout, mouseHasMoved;
        function doTimeout() {
            if (mouseHasMoved)
                resetMouse();
            else
                stop();
        }
        function resetMouse() {
            clearTimeout(timeout);
            timeout = setTimeout(doTimeout, RobloxEventManager._idleInterval);
            mouseHasMoved = false;
            // Rebind mouse movement
            $(document).one("mousemove", function () {
                mouseHasMoved = true;
            });
        }
        function start() {
            // Monitor cookie store every 5 secs
            clearInterval(interval);
            interval = setInterval(RobloxEventManager.monitorCookieStore, 5000);
            // Set mouse movement
            resetMouse();
        }
        // Actually stop monitor
        function stop() {
            clearTimeout(timeout);
            clearInterval(interval);
            // Detach plugin
            var pluginObj = document.getElementById('robloxpluginobj');
            Roblox.Client.ReleaseLauncher(pluginObj, false, false);
            // Restart plugin when the mouse moves
            $(document).one("mousemove", start);
        }
        start();
    };
};

function RBXBaseEventListener() {

    if (!(this instanceof RBXBaseEventListener)) {
        return new RBXBaseEventListener();
    }

    this.init = function () {
        for (eventKey in this.events) {
            if (this.events.hasOwnProperty(eventKey)) {
                $(document).bind(this.events[eventKey], $.proxy(this.localCopy, this));
            }
        }
    };
    this.events = [];

    this.localCopy = function (event, data) {
        var localEvent = $.extend(true, {}, event);
        var localData = $.extend(true, {}, data);
        this.handleEvent(localEvent, localData);
    };
    /*
     * INTERFACE FUNCTIONS
     */
    this.distillData = function (data, mapping) {
        console.log('RBXEventListener distillData - Please implement me');
        return false;
    };
    this.handleEvent = function (event) {
        console.log('EventListener handleEvent - Please implement me');
        return false;
    };
    this.fireEvent = function (evtStr) {
        console.log('EventListener fireEvent - Please implement me');
        return false;
    };
}


}
/*
     FILE ARCHIVED ON 01:58:08 Sep 08, 2014 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:02 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 0.873
  exclusion.robots: 0.1
  exclusion.robots.policy: 0.088
  cdx.remote: 0.078
  esindex: 0.016
  LoadShardBlock: 32.067 (3)
  PetaboxLoader3.datanode: 33.093 (4)
  load_resource: 56.697
  PetaboxLoader3.resolve: 52.862
*/