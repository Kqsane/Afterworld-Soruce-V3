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

if (typeof Roblox === 'undefined') {
    Roblox = {};
}

Roblox.JSErrorTracker = {
    showAlert: false,
    defaultPixel: 'GA',
    javascriptStackTraceEnabled: false,
    suppressConsoleError: false,
    data: { category: 'JavascriptExceptions' },

    initialize: function (args) {
        $.extend(Roblox.JSErrorTracker, args);
        this.addOnErrorEventHandler(this.errorHandler);
    },

    errorHandler: function (errMsg, errUrl, errLine) {
        try {
            Roblox.JSErrorTracker.data.msg = errMsg;
            Roblox.JSErrorTracker.data.url = errUrl;
            Roblox.JSErrorTracker.data.line = errLine;
            Roblox.JSErrorTracker.data.ua = window.navigator.userAgent;
            Roblox.JSErrorTracker.logException(Roblox.JSErrorTracker.data);
        } catch (e) {
        }
        return Roblox.JSErrorTracker.suppressConsoleError;
    },

    addOnErrorEventHandler: function (fn) {
        var existingErrHandler = window.onerror;
        if (typeof window.onerror === 'function') {
            window.onerror = function (errMsg, errUrl, errLine) {
                existingErrHandler(errMsg, errUrl, errLine);
                fn(errMsg, errUrl, errLine);
            };
        } else {
            window.onerror = fn;
        }
    },

    processException: function (exDetails, pixelToFire) {
        if (typeof (exDetails) === 'undefined') {
            return;
        }
        if (typeof (exDetails.category) === 'undefined')
            exDetails.category = Roblox.JSErrorTracker.data.category;
        switch (pixelToFire) {
            case 'GA':
                var mapping = { category: 'category', url: 'action', msg: 'opt_label', line: 'opt_value' };
                Roblox.JSErrorTracker.fireGAPixel(Roblox.JSErrorTracker.distillGAData(exDetails, mapping));
                break;
            default:
                console.log("Roblox JSErrorTracker received an unknown pixel to fire");
                break;
        }
        return true;
    },

    logException: function (exDetails) {
        Roblox.JSErrorTracker.processException(exDetails, Roblox.JSErrorTracker.defaultPixel);
        Roblox.JSErrorTracker.showErrorMessage(exDetails.msg);
    },

    distillData: function (data, mapping) {
        var distilled = {};
        for (var datakey in mapping) {
            if (typeof data[datakey] !== 'undefined') {
                distilled[mapping[datakey]] = encodeURIComponent(data[datakey]);
            }
        }
        return distilled;
    },

    distillGAData: function (data, mapping) {
        var distilled = Roblox.JSErrorTracker.distillData(data, mapping);
        //Params for GA category, action, opt_label, opt_value
        var eventParams = [decodeURIComponent([distilled.category])];
        if (typeof (distilled.action) !== typeof (undefined)) {
            eventParams = eventParams.concat(decodeURIComponent(distilled.action));
            if (typeof (distilled.opt_label) !== typeof (undefined)) {
                eventParams = eventParams.concat(decodeURIComponent(distilled.opt_label));
                if (typeof (distilled.opt_value) !== typeof (undefined)) {
                    eventParams = eventParams.concat(parseInt(decodeURIComponent(distilled.opt_value)));
                }
            }
        } else {
            //action is a required field
            if (Roblox.JSErrorTracker.showAlert) {
                alert("Missing a required parameter for GA");
            }
        }
        return eventParams;
    },

    createURL: function (url, args, mapping) {
        var urlToFire = url;
        var distilled = Roblox.JSErrorTracker.distillData(args, mapping);
        urlToFire += "?";
        if (distilled != null) {
            for (var arg in distilled) {
                if (typeof (arg) != typeof (undefined) && args.hasOwnProperty(arg))
                    urlToFire += arg + "=" + distilled[arg] + "&";
            }
        }
        urlToFire = urlToFire.slice(0, urlToFire.length - 1);
        return urlToFire;
    },

    fireGAPixel: function (params) {
        if (typeof (_gaq) !== 'undefined') {
            _gaq.push(["c._trackEvent"].concat(params));
        }
    },

    showErrorMessage: function (msg) {
        if (Roblox.JSErrorTracker.showAlert) {
            if (msg !== null)
                alert(msg);
            else
                alert("An error occured");
        }
    }
};

}
/*
     FILE ARCHIVED ON 01:58:04 Sep 08, 2014 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:01 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 0.8
  exclusion.robots: 0.087
  exclusion.robots.policy: 0.077
  cdx.remote: 0.063
  esindex: 0.014
  LoadShardBlock: 70.864 (3)
  PetaboxLoader3.datanode: 68.37 (4)
  load_resource: 204.058
  PetaboxLoader3.resolve: 185.216
*/