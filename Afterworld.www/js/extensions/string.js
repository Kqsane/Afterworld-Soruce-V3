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

/** 
 *  Extensions for JavaScript's built-in String class.
**/
$.extend(String.prototype, (function () {

    /**
     *  Escapes potentially dangerous characters into their HTML encoded equivalents.
     *
     *  #### Examples ####
     *
     *      '<div class="Place">This is a place.</div>'.escapeHTML()
     *          => '&lt;div class=&quot;Place&quot;&gt;This is a place.&lt;/div&gt;'
     *
    **/
    function escapeHTML() {
        return this.replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    return {
        escapeHTML:     escapeHTML
    };

})());

}
/*
     FILE ARCHIVED ON 01:58:07 Sep 08, 2014 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:10 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 0.551
  exclusion.robots: 0.077
  exclusion.robots.policy: 0.07
  cdx.remote: 0.042
  esindex: 0.007
  LoadShardBlock: 3999.226 (3)
  PetaboxLoader3.datanode: 2143.91 (4)
  PetaboxLoader3.resolve: 1738.921 (2)
  load_resource: 535.151
*/