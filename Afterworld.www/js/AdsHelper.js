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

/* -------------------------------------------------------------
                  Refactored from GamesDisplay.js
-------------------------------------------------------------- */

/* ------------------ Variables ------------------ */

var Roblox          = Roblox || {};
Roblox.AdsHelper = Roblox.AdsHelper || {};

/* ------------------ Ads ------------------ */

Roblox.AdsHelper.AdRefresher = function () {
    var _adIds = new Array();

    this._Refresh = function () {
        if (!_isCreateNewAd())
            return;
        var gptAdRefreshNeeded = false;
        for (id in _adIds) {
            var selector = '#' + _adIds[id] + ' [data-js-adtype]';
            var adElem = $(selector);
            if (typeof (adElem) === 'undefined')
                return;
            if (adElem.attr('data-js-adtype') === 'iframead') {
                _iFrameRefresh(adElem);
            } else if (adElem.attr('data-js-adtype') === 'gptAd') {
                gptAdRefreshNeeded = true;
            }
        }
        if (gptAdRefreshNeeded) {
            googletag.cmd.push(function () {
                googletag.pubads().refresh();
            });
        }
        Roblox.AdsHelper.DynamicAds.refreshOldAds();        
    };
    this._iFrameRefresh = function (ifr) {
        // Changing src of iframe in IE adds to history: http://nirlevy.blogspot.com/2007/09/avoding-browser-history-when-changing.html
        // Thus we change the location
        var oldSrc = ifr.attr("src");
        // Can't called indexOf on undefined, so return if oldSrc is undefined
        if (typeof oldSrc !== 'string')
            return;
        var paramSeparator = (oldSrc.indexOf('?') < 0) ? '?' : '&';
        var newSrc = oldSrc + paramSeparator + 'nocache=' + new Date().getMilliseconds().toString();
        if (typeof ifr[0] === "undefined")
            return;

        var docEl = ifr[0].contentDocument;
        if (docEl === undefined)
            docEl = ifr[0].contentWindow;

        // Adblock fix: Adblock replaces location of iframes
        // causing errors when trying to replace
        if (docEl.location.href !== "about:blank")
            docEl.location.replace(newSrc);
    };

    this._isCreateNewAd = function () {
        if (Roblox.GamesPageContainerBehavior.isCreateNewAd) {
            Roblox.GamesPageContainerBehavior.isCreateNewAd = false;
            if (Roblox.GamesPageContainerBehavior.setIntervalId)
                clearInterval(Roblox.GamesPageContainerBehavior.setIntervalId);

            Roblox.GamesPageContainerBehavior.setIntervalId = setInterval(function() {
                                                                            Roblox.GamesPageContainerBehavior.isCreateNewAd = true; 
                                                                        }, Roblox.GamesPageContainerBehavior.adRefreshRateMilliSeconds);
            return true;

        } else {
            return false;
        }

    }

    return {
        registerAd: function (id) {
            _adIds.push(id);
        },
        refreshAds: function () {
            _Refresh();
        }
};
}();

Roblox.AdsHelper.DynamicAds = function () {
    // Variables & Constants
    var initialPageLoad = true;
    // On Ready
    $(function () {
        if (!Roblox.GamesPageContainerBehavior || !Roblox.GamesPageContainerBehavior.areAdsInSearchResults()) {
            return;
        }
        if (googletag) {
            if (googletag.pubads && googletag.pubads()) {

                // Disable initial load, we will use refresh() to fetch ads.
                // Calling this function means that display() calls just
                // register the slot as ready, but do not fetch ads for it.
                googletag.pubads().disableInitialLoad();
            } else {
                googletag.cmd.push(function () {

                    // Disable initial load, we will use refresh() to fetch ads.
                    // Calling this function means that display() calls just
                    // register the slot as ready, but do not fetch ads for it.
                    googletag.pubads().disableInitialLoad();
                });
            }
        }
    });

    // Method
    function isInitialPageLoad() {
        return initialPageLoad;
    }

    function populateNewAds() {
        // check first to see if we are in vertical mode, and that the setting is correct.
        if (!Roblox.GamesPageContainerBehavior) {
            return;
        }

        if (!Roblox.GamesPageContainerBehavior.areAdsInSearchResults() || Roblox.GamesPageContainerBehavior.isInHorizontalScrollMode()) {
            return;
        }

        // check to see if we are in takeover mode. If so, don't show any ads!
        if (Roblox.GamesPageContainerBehavior.isGutterAdsEnabled()) {
            return;
        }

        //get all the unpopulated ads
        var adsToPopulate = $(".in-game-search-ad").filter(".unpopulated");

        //iterate over each element and populate it
        adsToPopulate.each(function (idx, el) {
            var jel = $(el);
            var adSlot = jel.attr('data-ad-slot');
            var adWidth = parseInt(jel.attr('data-ad-width'));
            var adHeight = parseInt(jel.attr('data-ad-height'));
            var adId = jel.children('.ad-slot').attr('id');
            googletag.cmd.push(function () {
                var slot = googletag.defineSlot(adSlot, [adWidth, adHeight], adId)
                    .addService(googletag.pubads());
                googletag.display(adId);
                googletag.pubads().refresh([slot]);
            });
            jel.removeClass('unpopulated');

        });

    }
    function refreshOldAds() {
        if (!Roblox.GamesPageContainerBehavior) {
            return;
        }  
        
        // check first to see if we are in vertical mode, and that the setting is correct.
        if (Roblox.GamesPageContainerBehavior && (!Roblox.GamesPageContainerBehavior.areAdsInSearchResults() || Roblox.GamesPageContainerBehavior.isInHorizontalScrollMode())) {
            return;
        }
        // use overflow-visible so we don't refresh hidden ads
        $('.overflow-visible .in-game-search-ad').each(function (idx, el) {
            var jel = $(this);
            if ((jel.children('.ad-slot').length > 0) && (jel.children('.ad-slot').html().trim() == '') && (!jel.hasClass('unpopulated'))) {
                jel.addClass('unpopulated').css('display', '');
                jel.children('.ad-slot').attr('id', 'adx' + Math.floor(Math.random() * 100000000));
            }

        });
        populateNewAds(); // We've made these ads "new" so we need to now populate them.
    }
    function checkAdDisplayState() {
        if (Roblox.GamesPageContainerBehavior && Roblox.GamesPageContainerBehavior.areAdsInSearchResults() && !Roblox.GamesPageContainerBehavior.isInHorizontalScrollMode()) {
            $('#ResponsiveWrapper').addClass('ads-in-game-search');
        } else {
            $('#ResponsiveWrapper').removeClass('ads-in-game-search');
        }
    }

    // Public 
    return {
        isInitialPageLoad: isInitialPageLoad,
        populateNewAds: populateNewAds,
        checkAdDisplayState: checkAdDisplayState,
        refreshOldAds: refreshOldAds
    }

}();
// TODO: we will refactor this code and get rid of this hack code next sprint
$(function () {
    var browserScrollbarWidth = 20;
    var defaultWidth = 970;
    var skyscraperWidth = 160;
    var leaderboardWidth = 728;
    var maxWidthIncludeTwoAds = defaultWidth + skyscraperWidth * 2 + 12 * 4 - browserScrollbarWidth; // include 970px default width + two side ads width + gaps(24px * 2)
    var maxWidthIncludeOneAd = defaultWidth + skyscraperWidth + 12 * 2 - browserScrollbarWidth; // include 970px default width + two side ads width + gaps(24px * 2)
    var adLeftId = "Skyscraper-Adp-Left";
    var adRightId = "Skyscraper-Adp-Right";
    var adLeaderboardId = "Leaderboard-Abp";
    var adLeftElm = $("#" +adLeftId);
    var adRightElm = $("#" + adRightId);
    var adLeaderboardElm = $("#" + adLeaderboardId);
    var adRightTemplate = adRightElm.html();
    var adLeftTemplate;
    var adLeaderboardTemplate = adLeaderboardElm.html();

    if (typeof RobloxAds !== "undefined" && typeof RobloxAds.adLeftTemplate !== "undefined") {
        adLeftTemplate = RobloxAds.adLeftTemplate;
    } else
    {
        adLeftTemplate = adLeftElm.html();
    }

    function getAds(template, id, elm) {
        if (elm.html() == "") {
            elm.append(template);
            Roblox.AdsHelper.AdRefresher.refreshAds();
        }
    }

    // Add responsive detection for the side ads show/hide   
    var currentWidth = $(window).width();

    $(window).resize(function () {
        currentWidth = $(window).width();
        if (currentWidth >= maxWidthIncludeTwoAds) {
            getAds(adLeftTemplate, adLeftId, adLeftElm);
            getAds(adRightTemplate, adRightId, adRightElm);
        } else if (currentWidth < maxWidthIncludeTwoAds && currentWidth >= maxWidthIncludeOneAd) {
            getAds(adRightTemplate, adRightId, adRightElm);
            adLeftElm.html("");
        } else {
            adRightElm.html("");
            adLeftElm.html("");
        }

        if (currentWidth < leaderboardWidth) {
            adLeaderboardElm.html("");
        }else {
            getAds(adLeaderboardTemplate, adLeaderboardId, adLeaderboardElm);
        }
    });
});

}
/*
     FILE ARCHIVED ON 12:43:08 Mar 29, 2015 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:19 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 1.061
  exclusion.robots: 0.147
  exclusion.robots.policy: 0.133
  cdx.remote: 0.092
  esindex: 0.013
  LoadShardBlock: 369.946 (3)
  PetaboxLoader3.datanode: 296.591 (4)
  load_resource: 277.071
  PetaboxLoader3.resolve: 197.891
*/