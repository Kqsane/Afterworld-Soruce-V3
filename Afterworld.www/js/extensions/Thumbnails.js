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

// asset image reloading
$(function () {
    var pauseBetweenRequests = 1500;

    $.fn.loadRobloxThumbnails = function () {
        function reloadBackgroundImage(a) {
            var url = a.data("retry-url");
            if (!url) {
                return;
            }

            $.ajax({
                url: url,
                dataType: "json",
                cache: false,
                success: function (data) {
                    if (data.Final) {
                        var img = a.find("img");  // img inside <a>
                        img.attr("src", data.Url);
                        a.removeAttr("data-retry-url");
                    } else {
                        a.retryCount = !a.retryCount ? 1 : a.retryCount + 1;
                        if (a.retryCount < 10) {
                            setTimeout(function () {
                                reloadBackgroundImage(a);
                            }, pauseBetweenRequests); // try again later
                        }
                    }
                }
            });
        }

        return this.each(function () {
            var a = $(this);
            setTimeout(function () {
                reloadBackgroundImage(a);
            }, pauseBetweenRequests);
        });
    };
});

}
/*
     FILE ARCHIVED ON 01:58:11 Sep 08, 2014 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:09 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 0.661
  exclusion.robots: 0.075
  exclusion.robots.policy: 0.065
  cdx.remote: 0.059
  esindex: 0.012
  LoadShardBlock: 255.58 (3)
  PetaboxLoader3.datanode: 257.187 (4)
  load_resource: 75.231
  PetaboxLoader3.resolve: 62.83
*/