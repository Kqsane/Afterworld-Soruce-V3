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

var RobloxThumbs = function() {
    
    /** Private **/
   function _GenerateAvatarThumbHelper(imgTagId, userId, thumbnailFormatId) {
        $.get("/thumbs/rawavatar.ashx",
        {
            UserID: userId,
            ThumbnailFormatID: thumbnailFormatId
        },
        function(data) 
        {
            if (data == "PENDING") 
            {
                window.setTimeout(function() 
                {
                    _GenerateAvatarThumbHelper(imgTagId, userId, thumbnailFormatId);
                }, 3000);
            }
            else if (data.substring(5, 0) == "ERROR") // Should be using JSON...
            {
                // DO something if an error occurs ?
            }
            else // Success
            {
                $('#' + imgTagId).attr('src', data);
            }
        });
    }

    /** Public **/
    return {
        GenerateAvatarThumb: function(imgTagId, userId, thumbnailFormatId) {

            $('#' + imgTagId).attr('src', '/images/spinners/waiting.gif');

            _GenerateAvatarThumbHelper(imgTagId, userId, thumbnailFormatId);
        }
    };
} ();


}
/*
     FILE ARCHIVED ON 01:58:06 Sep 08, 2014 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:13 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 0.614
  exclusion.robots: 0.073
  exclusion.robots.policy: 0.062
  cdx.remote: 0.056
  esindex: 0.009
  LoadShardBlock: 37.615 (3)
  PetaboxLoader3.datanode: 37.713 (4)
  load_resource: 37.18
  PetaboxLoader3.resolve: 31.203
*/