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

// enable tipsy
$(function () {
    try {
        $('.tooltip').tipsy();
        $('.tooltip-top').tipsy({ gravity: 's' });
        $('.tooltip-right').tipsy({ gravity: 'w' });
        $('.tooltip-left').tipsy({ gravity: 'e' });
        $('.tooltip-bottom').tipsy({ gravity: 'n' });
    }
    catch(err) {
    }


    // <a disabled> anchor tags don't support disabled attributes in HTML5
    // Since this is in our master styleguide we just need to add the disabled property when we detect a disabled button
    $('a.btn-disabled-primary[disabled]').prop('disabled', true);

});

if (typeof Roblox === "undefined") {
    Roblox = {};
}

/* Roblox.FixedUI handles hiding iframe ads when conflicting with the fixed header, 
and unfixing the header when the window is resized or we are on mobile devices */
Roblox.FixedUI = function () {
    var unfixHeaderThreshold = 700;
    var ua = navigator.userAgent.toLowerCase(); /* unfix headers for iphone, mobile, android, blackberry or playbook devices */
    var isMobile = /mobile/i.test(ua) || /ipad/i.test(ua) || /iphone/i.test(ua) || /android/i.test(ua) || /playbook/i.test(ua) || /blackberry/i.test(ua);
    /* Run on load */
    $(function () {
        if (gutterAdsEnabled()) {
            var adFrame = $("#LeftGutterAdContainer iframe");
            if (adFrame.length > 0) {
                var adAnnotation = $(".ad-annotations", adFrame.contents());
                adAnnotation.addClass("left-gutter-ad");
            }
        }
    });

    function getWindowWidth() {
        var winW = 1024;
        if (document.body && document.body.offsetWidth) winW = document.body.offsetWidth; /* ie */
        if (window.innerWidth && window.innerHeight) winW = window.innerWidth; /* other browsers */
        return winW;
    }

    function gutterAdsEnabled() {
        return !$(".nav-container").hasClass("no-gutter-ads");
    }

    function isHeaderFixed() {
        return getWindowWidth() > unfixHeaderThreshold;
    }

    /* Public interface */
    var my = {
        isMobile: isMobile,
        gutterAdsEnabled: gutterAdsEnabled,
        isHeaderFixed: isHeaderFixed,
        getWindowWidth: getWindowWidth
    };
    return my;
} ();


}
/*
     FILE ARCHIVED ON 01:58:11 Sep 08, 2014 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:06 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 1.125
  exclusion.robots: 0.137
  exclusion.robots.policy: 0.123
  cdx.remote: 0.107
  esindex: 0.015
  LoadShardBlock: 76.032 (3)
  PetaboxLoader3.datanode: 77.202 (4)
  load_resource: 173.071
  PetaboxLoader3.resolve: 162.85
*/