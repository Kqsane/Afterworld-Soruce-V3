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

(function (window, undefined) {

    function isIE10() {
        return navigator.userAgent.indexOf("MSIE 10.0") != -1;
    }

    function isActiveXEnabled() {
        try {
            return !!new ActiveXObject("htmlfile");
        } catch (e) {
            return false;
        }
    }

    var waitForRoblox = Roblox.Client.WaitForRoblox;
    Roblox.Client.WaitForRoblox = function (continuation) {
        if (isIE10() && !isActiveXEnabled()) {
            $('#IEMetroInstructions').modal({
                overlayClose: true,
                escClose: true,
                opacity: 80,
                overlayCss: {
                    backgroundColor: "#000"
                }
            });
            return false;
        }

        return waitForRoblox(continuation);
    };

})(window);

}
/*
     FILE ARCHIVED ON 01:58:09 Sep 08, 2014 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:20 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 0.676
  exclusion.robots: 0.083
  exclusion.robots.policy: 0.071
  cdx.remote: 0.064
  esindex: 0.011
  LoadShardBlock: 149.41 (3)
  PetaboxLoader3.datanode: 91.478 (4)
  load_resource: 215.451
  PetaboxLoader3.resolve: 198.209
*/