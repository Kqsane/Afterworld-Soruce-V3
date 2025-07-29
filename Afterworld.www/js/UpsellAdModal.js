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

if (typeof Roblox.UpsellAdModal === "undefined") {
    Roblox.UpsellAdModal = function() {
        var open = function() {
            var options = {
                titleText: Roblox.UpsellAdModal.Resources.title,
                bodyContent: Roblox.UpsellAdModal.Resources.body,
                footerText: "",
                overlayClose: true,
                escClose: true,
                acceptText: Roblox.UpsellAdModal.Resources.accept,
                declineText: Roblox.UpsellAdModal.Resources.decline,
                acceptColor: Roblox.GenericConfirmation.green,
                onAccept: function() { window.location.href = '/Upgrades/BuildersClubMemberships.aspx'; },
                imageUrl: '/images/BuildersClub-110x110_small.png'
            };

            Roblox.GenericConfirmation.open(
                options
            );
        };

        return {
            open: open
        };
    } ();
}

$(function () {
    $('a.UpsellAdButton').click(function () {
        Roblox.UpsellAdModal.open();
        return false;
    });
});

}
/*
     FILE ARCHIVED ON 01:58:07 Sep 08, 2014 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:11 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 0.688
  exclusion.robots: 0.092
  exclusion.robots.policy: 0.084
  cdx.remote: 0.063
  esindex: 0.009
  LoadShardBlock: 2142.618 (3)
  PetaboxLoader3.resolve: 1855.875 (4)
  PetaboxLoader3.datanode: 1711.511 (4)
  load_resource: 1812.632
*/