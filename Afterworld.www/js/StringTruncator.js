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

//Create a span element that will be used to get the width
var isInitialized = false;
var fitStringSpan = null;

function InitStringTruncator()
{
    if (isInitialized)
        return;

    fitStringSpan = document.createElement("span");
    fitStringSpan.style.display = 'inline-block';
    fitStringSpan.style.visibility = 'hidden';
    fitStringSpan.style.height = '0px';
    fitStringSpan.style.padding = '0px';
    document.body.appendChild(fitStringSpan);

    isInitialized = true;
}

function fitStringToWidth(str, width, className) {
    
    if (!isInitialized)
        InitStringTruncator();
    
    // str    A string where html-entities are allowed but no tags.
    // width  The maximum allowed width in pixels
    // className  A CSS class name with the desired font-name and font-size. (optional)
    // ----
    // _escTag is a helper to escape 'less than' and 'greater than'
    function _escTag(s) { return s.replace("<", "&lt;").replace(">", "&gt;"); }

    

   //Allow a classname to be set to get the right font-size.
    if (className)
        fitStringSpan.className = className;
    

    var result = _escTag(str); // default to the whole string
    fitStringSpan.innerHTML = result;
    // Check if the string will fit in the allowed width. NOTE: if the width
    // can't be determinated (offsetWidth==0) the whole string will be returned.
    if (fitStringSpan.offsetWidth > width)
    {
        var posStart = 0, posMid, posEnd = str.length, posLength;
        // Calculate (posEnd - posStart) integer division by 2 and
        // assign it to posLength. Repeat until posLength is zero.
        while (posLength = (posEnd - posStart) >> 1)
        {
            posMid = posStart + posLength;
            //Get the string from the begining up to posMid;
            fitStringSpan.innerHTML = _escTag(str.substring(0, posMid)) + '&hellip;';

            // Check if the current width is too wide (set new end)
            // or too narrow (set new start)
            if (fitStringSpan.offsetWidth > width) posEnd = posMid; else posStart = posMid;
        }

        result = str.substring(0, posStart) + '&hellip;';
//        result = _escTag(str.substring(0, posStart)) + '&hellip;';
    }
    
    return result;
}

function fitStringToWidthSafe(str, width, className) {
    var safeName = fitStringToWidth(str, width, className);
    if (safeName.indexOf("&hellip;") != -1) {
        var posEnd = safeName.lastIndexOf(" ");
        if (posEnd != -1 && posEnd + 10 <= safeName.length) {
            safeName = safeName.substring(0, posEnd + 2) + "&hellip;";
        }
    }
    return safeName;
}
function fitStringToWidthSafeText(str, width, className) {
    var safeName = fitStringToWidthSafe(str, width, className).replace("&hellip;", "...");
    return safeName;
}


}
/*
     FILE ARCHIVED ON 01:58:05 Sep 08, 2014 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:08 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 0.513
  exclusion.robots: 0.064
  exclusion.robots.policy: 0.056
  cdx.remote: 0.047
  esindex: 0.008
  LoadShardBlock: 205.185 (3)
  PetaboxLoader3.datanode: 438.147 (4)
  load_resource: 1303.781
  PetaboxLoader3.resolve: 978.662
*/