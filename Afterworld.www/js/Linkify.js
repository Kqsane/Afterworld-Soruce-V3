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

Roblox = Roblox || {};

Roblox.Linkify = function () {
    //this interacts with .toggleDesc, sometimes in a bad way.
    //this converts texts containing links to actual HTML links and also upgrades their protocol execpt for specified domains that do not have https (eg. blog, wiki, etc)
    "use strict";
    var options = {
        enabled: false,
        hasProtocolRegex: /(https?:\/\/)/,
        defaultProtocol: "https://",
        httpProtocol: "http://",
        cssClass: "text-link"
    };

    var optionsLoaded = false;

    var isMobile = (Roblox.FixedUI && Roblox.FixedUI.isMobile);

    function getOptions() {
        var config = $("#roblox-linkify");
        if (config.length) {
            var regexString = config.data("regex");
            var flags = config.data("regex-flags");
            var asHttpRegexString = config.data("as-http-regex");

            var extendedOptions = {
                enabled: config.data("enabled"),
                regex: new RegExp(regexString, flags),
                doNotUpgradeToHttpsRegex: new RegExp(asHttpRegexString)
            };
            setOptions(extendedOptions);
        }
        optionsLoaded = true;
    }
    function setOptions(extendedOptions) {
        $.extend(options, extendedOptions);
    }

    function alreadyLinkified(str) {
        return ($("<div>" + str + "</div>").find("a[href]").length > 0);
        //toggleDesc adds in <a onClick> elements which can make this return true incorrectly.
    }

    function doNotUpgradeProtocol(str) {
        return str.match(options.doNotUpgradeToHttpsRegex);
    }

    function replaceAmpersandEntity(str) {
        return str.replace(/\&amp;/g, "&");
    }

    function removePeriodsAtTheEnd(str) {
        return str.replace(/\.+$/g, "");
    }

    // http://stackoverflow.com/questions/2419749/get-selected-elements-outer-html
    function outerHtml(elem) {
        return elem.clone().wrap("<div>").parent().html();
    }

    function addProtocolIfMissing(str) {
        if (!str.match(options.hasProtocolRegex)) {
            str = doNotUpgradeProtocol(str) ? options.httpProtocol + str : options.defaultProtocol + str;
        }
        return str;
    }
    function changeProtocol(str) {
        return doNotUpgradeProtocol(str) ? str.replace("https://", "http://") : str.replace("http://", "https://");
    }

    function makeLink(match) {
        match = replaceAmpersandEntity(match); //input is often HTML encoded
        var text = match;
        match = removePeriodsAtTheEnd(match);
        var href = addProtocolIfMissing(match);
        href = changeProtocol(href);
       
        var link = $("<a></a>");
        link.addClass(options.cssClass);
        link.attr("href", href);
        link.text(text);
        if (!isMobile) { //mobile links have to open in same tab
            link.attr("target", "_blank");
        }
        
        return outerHtml(link);
    }

    function linkifyString(str) {
        if (!optionsLoaded) {getOptions();}
        if (options.enabled && !alreadyLinkified(str)) {
            str = str.replace(options.regex, makeLink);
        }
        return str;
    }

    return {
        String: linkifyString,
        SetOptions: setOptions
    };
}();

$.fn.linkify = function() {
    return this.each(function() {
        var element = $(this);
        var html = element.html();
        if (typeof html !== "undefined" && html !== null) {
            var newHtml = Roblox.Linkify.String(html);
            element.html(newHtml);
        }
    });
};

$(function () {
    $(".linkify").linkify();
});

}
/*
     FILE ARCHIVED ON 02:07:59 Jan 20, 2017 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:15 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 0.708
  exclusion.robots: 0.088
  exclusion.robots.policy: 0.077
  cdx.remote: 0.063
  esindex: 0.009
  LoadShardBlock: 70.041 (3)
  PetaboxLoader3.datanode: 637.584 (4)
  load_resource: 635.872
  PetaboxLoader3.resolve: 42.72
*/