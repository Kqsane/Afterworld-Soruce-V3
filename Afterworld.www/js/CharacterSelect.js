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

Roblox.CharacterSelect = (function () {

    var gender = { male: 2, female: 3 };

    $(function () {
        $('.VisitButtonGirlGuest').click(visitButtonGirlGuestClick);
        $('.VisitButtonBoyGuest').click(visitButtonBoyGuestClick);
    });

    function visitButtonBoyGuestClick() {
        my.genderID = gender.male;
        visitButtonClick(1, 'Male', gender.male);
        return false;
    }

    function visitButtonGirlGuestClick() {
        my.genderID = gender.female;
        visitButtonClick(0, 'Female', gender.female);
        return false;
    }

    function visitButtonClick(genderIDforGA, genderNameForRbxEvent, genderIDforReqGame) {
        $.modal.close('.GuestModePromptModal');
        // this is currently handled within generated JS code.  Hopefully someday we will fire these events here instead.
        /*
        $(function () {
        RobloxEventManager.triggerEvent('rbx_evt_play_guest', {
        age: 'Unknown',
        gender: genderNameForRbxEvent
        });
        });
        GoogleAnalyticsEvents.FireEvent(['Play', 'Guest', '', genderIDforGA]);
        */
        my.robloxLaunchFunction(genderIDforReqGame);
    }

    function show() {
        $('#GuestModePrompt_BoyGirl').modal({
            overlayClose: true,
            escClose: true,
            opacity: 80,
            overlayCss: {
                backgroundColor: '#000'
            },
            onShow: correctIE7ModalPosition
        });
    }

    function correctIE7ModalPosition(dialog) {
        if (dialog.container.innerHeight() == 15) {
            // this must be IE 6/7 (or equally stupid).  shift the modal up.
            var shiftDistance = -Math.floor($('#GuestModePrompt_BoyGirl').innerHeight() / 2);
            $('#GuestModePrompt_BoyGirl').css({ position: "relative", top: shiftDistance + "px" });
        }
    }

    function hide() {
        $.modal.close('.GuestModePromptModal');
    }

    // external interface (internally referenced as "my")
    var my = {
        robloxLaunchFunction: function (genderIDforReqGame) { },
        genderID: null,
        show: show,
        hide: hide,
        // This is kind of a hack.  we lose the clicked element's context when we call robloxLaunchFunction().
        // The property below is so that $(this).attr("placeid") returns the correct value.
        // This should be set, but if not, undefined will set placeid = play_placeID (which is something, if not always
        // correct).
        placeid: undefined
    };

    return my;
})();

}
/*
     FILE ARCHIVED ON 01:58:03 Sep 08, 2014 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:16 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 0.909
  exclusion.robots: 0.119
  exclusion.robots.policy: 0.105
  cdx.remote: 0.083
  esindex: 0.013
  LoadShardBlock: 666.599 (3)
  PetaboxLoader3.datanode: 655.697 (4)
  load_resource: 147.648
  PetaboxLoader3.resolve: 139.002
*/