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

if (typeof Roblox === "undefined") {
    Roblox = {};
}

Roblox.InstallationInstructions = (function () {

    function show(mode) {
        if (typeof mode == "undefined") {
            mode = "installation";
        }
        loadImages(mode);
        // Presize modal, if we are showing an image, to fix a bug where some browsers size the modal before loading an image.
        // This bug would show the modal with part of the image cut off.
        // Upon reload, the image is cached, and the modal auto-sizes, "fixing" the bug while the image is cached.
        // But we want to show it right the first time!
        var modalWidth = 0;
        var installInstructionsImage = $('.InstallInstructionsImage');
        if (installInstructionsImage && typeof $(installInstructionsImage).data("modalwidth") != "undefined") {
            modalWidth = $('.InstallInstructionsImage').data('modalwidth');
        }
        if (modalWidth > 0) {
            var leftPercent = ($(window).width() - (modalWidth - 10)) / 2;
            $('#InstallationInstructions').modal({ escClose: true,
                //onClose: function() { Roblox.Client._onCancel(); },
                opacity: 50,
                minWidth: modalWidth,
                maxWidth: modalWidth,
                overlayCss: { backgroundColor: "#000" },
                position: [50, leftPercent]
            });
        } else {
            $('#InstallationInstructions').modal({ escClose: true,
                //onClose: function() { Roblox.Client._onCancel(); },
                opacity: 50,
                maxWidth: ($(window).width() / 2),
                minWidth: ($(window).width() / 2),
                overlayCss: { backgroundColor: "#000" },
                position: [50, "25%"]
            });
        }
    }

    function hide() {
        $.modal.close();
    }

    function loadImages(mode) {
        var installInstructionsImage = $('.InstallInstructionsImage');
        if (navigator.userAgent.match(/Mac OS X 10[_|\.]5/)) {
            if (installInstructionsImage && typeof installInstructionsImage.data("oldmacdelaysrc") != "undefined") {
                installInstructionsImage.attr('src', installInstructionsImage.data('oldmacdelaysrc'));
            }
        }
        else {
            if (mode == "activation" && installInstructionsImage.data("activationsrc") !== undefined) {
                installInstructionsImage.attr('src', installInstructionsImage.data('activationsrc'));
            }
            // default to mode == "installation" if activation image not available
            else if (installInstructionsImage.data("delaysrc") !== undefined) {
                installInstructionsImage.attr('src', installInstructionsImage.data('delaysrc'));
            }
        }
    }

    var my = {
        show: show,
        hide: hide
    };

    return my;
})();

}
/*
     FILE ARCHIVED ON 01:58:12 Sep 08, 2014 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:21 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 0.708
  exclusion.robots: 0.087
  exclusion.robots.policy: 0.077
  cdx.remote: 0.064
  esindex: 0.01
  LoadShardBlock: 416.186 (3)
  PetaboxLoader3.datanode: 292.225 (4)
  PetaboxLoader3.resolve: 462.004 (3)
  load_resource: 344.466
*/