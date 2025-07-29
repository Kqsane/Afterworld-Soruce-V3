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

/*  -------------------------------------------------------------
  Refactored from GamesDisplay.js to share with games MVC page
-------------------------------------------------------------- */

/* ------------------ Variables ------------------ */

var Roblox                = Roblox || {};
Roblox.GamesDisplayShared = {};


/* ------------------ Search ------------------ */

// old original search

Roblox.GamesDisplayShared.search = function () {
    var keyword = $("#searchbox").val();
    if (typeof keyword == "undefined" || keyword == "Search")
        keyword = "";
    window.location = "/Catalog/Lists.aspx?m=TopFavorites&c=9&t=AllTime&d=All&q=" + keyword;
    return false;
}

// new integrated on page search
Roblox.GamesDisplayShared.searchOnPage = function (numGamesToFetch) {
    var target = $("#SearchResultsContainer");
    
    // if search has not been unlocked, bail
    if(!target.hasClass("can-search") ) {
        return false;
    }
    var numberOfGamesToFetch = Math.min(numGamesToFetch, Roblox.GamesPageContainerBehavior.numGamesToFetchOnSearch);
    var startIndex = 0; // first set of search results so fetch from 0 index

    Roblox.GamesDisplayShared.getPage(startIndex, numberOfGamesToFetch, target);
   
    return false;
}

// infinite scrolling
Roblox.GamesDisplayShared.moreSearchResults = function () {
    var target = $("#SearchResultsContainer");

    // bail if a search is already pending (debounce)
    if (target.hasClass('search-pending')) {
        // we have a search pending! bail.
        return false;
    }

    var maxRows = Roblox.GamesPageContainerBehavior.numGamesToFetchOnSearch;
    var currentStartRows = maxRows; //since we are fetching more results, needs to be larger than 0
    var htmlStartRows = $("#SearchResultsContainer").data("startrows");
    if (htmlStartRows && htmlStartRows > maxRows) {
        currentStartRows = htmlStartRows;
    }

    Roblox.GamesDisplayShared.getPage(currentStartRows, maxRows, target);


}

// refactored page fetching for both first page and infinite scrolling
Roblox.GamesDisplayShared.getPage = function (startIdx, numGamesToFetch, target) {
    var searchBoxVal = $("#searchbox").val();
    var url = Roblox.GamesPageContainerBehavior.getURLBasedOnSortFilter(0);
    var ErrMsg = "<strong>" + Roblox.SearchBox.Resources.zeroResults + "</strong>";
    var gamesList = target.find(".games-list");
    var isFirstRequest = startIdx < 1;

    // if this is the first request, 
    if (isFirstRequest) {
        // empty the list in anticipation of new results,
        gamesList.empty().append("<div class='abp-spacer'></div>");

        // When HideAds is on, we need to hide this placeholder 
        if ($("#GamesPageLeftColumn").hasClass("page-no-ads"))
            gamesList.find('.abp-spacer').hide();

        // update the search title text, 
        $("#SearchResultsContainer .search-query-text").text(searchBoxVal);

        // toggle the search state to "on"
        Roblox.GamesDisplayShared.toggleSearch("on");

        // calculate the Ad Span
        Roblox.GamesPageContainerBehavior.setAdSpanFromHeight(Roblox.GamesPageContainerBehavior.initialAdHeight);
    } else {
        // calculate the Ad Span
        Roblox.GamesPageContainerBehavior.setAdSpanFromHeight(Roblox.GamesPageContainerBehavior.subsequentAdHeight);
    }

    // show wait cursor
    target.addClass("search-pending");

    var requestData = {
        StartRows: startIdx,
        MaxRows: numGamesToFetch,
        IsUserLoggedIn: Roblox.GamesPageContainerBehavior.IsUserLoggedIn,
        NumberOfColumns: Roblox.GamesPageContainerBehavior.getNumberOfColumns(),
        IsInHorizontalScrollMode: Roblox.GamesPageContainerBehavior.isInHorizontalScrollMode(),
        DeviceTypeId: Roblox.GamesPageContainerBehavior.getDeviceTypeId(),
        Keyword: searchBoxVal,
        adSpan: Roblox.GamesPageContainerBehavior.getAdSpan(),
        adAlignment: Roblox.GamesPageContainerBehavior.calcAdAlignment()
    };


    $.get(url, requestData, function (data) {

        var tempDiv = $("<div></div>");

        if (data.search("game-item") > 0) {
            // we got data back that contains game items. Otherwise the return is "blank".
            tempDiv.append(data);

            // increment the startrows now that we got some data
            // for the next search if infinite scroll triggered
            $("#SearchResultsContainer").data("startrows", startIdx + numGamesToFetch);

            // ADS: scan for new dynamic ads and populate them
            // but only if not in horizontal mode
            

        } else if (isFirstRequest) {
            // show the zero results message instead
            // but not if we are getting subsequent results via infinite scroll
            tempDiv.append(ErrMsg);
        }

        // place results into the page
        gamesList.append(tempDiv.html());



        // hide wait cursor
        target.removeClass("search-pending");

        // refresh the Ads
        // but only in first set of results
        if (isFirstRequest) {
          Roblox.GamesPageContainerBehavior.refreshAds();  
        }

        // scan for in-game-results ads to refresh
        if (!Roblox.GamesPageContainerBehavior.isInHorizontalScrollMode()) {

            // I got results, time to scan them for ads to show!
            Roblox.AdsHelper.GamesPage.populateNewAds();
        }
        
        if (isFirstRequest) {
            // Load more Games Once
            var numOfCols = Roblox.GamesPageContainerBehavior.getNumberOfColumns();
            var numOfRows = Roblox.GamesPageContainerBehavior.getNumberOfRows();
            if (!Roblox.GamesPageContainerBehavior.getMultipleGamesListsDisplayedState() &&
                    numOfCols * numOfRows > Roblox.GamesPageContainerBehavior.NumberOfGamesToFetchInVScrollMode) {
                Roblox.GamesDisplayShared.moreSearchResults();
            }
        }
    });
    return false;

    

}

Roblox.GamesDisplayShared.updateURLFromSearchState = function() {
    var keyWord = $("#searchbox").val();
    var state = {
        keyword: keyWord
    }
    var title = Roblox.GamesPageContainerBehavior.Resources.pageTitle;
    var queryString = "?Keyword=" + encodeURIComponent(keyWord);

    History.pushState(state, title, queryString);
}

Roblox.GamesDisplayShared.toggleSearch = function (state) {
    //store state in data attribute on GamesPageLeftColumn 
    var searchStateHolder = $("#GamesPageLeftColumn");
    var target = $("#SearchResultsContainer");
    var alreadyUpdatedHistory = false;
    switch (state) {
        case "on":
            searchStateHolder.attr("data-searchstate", "on");

            // disable all of the filters
            $("#SortFilter").prop("disabled", true);
            $("#TimeFilter").prop("disabled", true);
            $("#GenreFilter").prop("disabled", true);

            // hide all the games lists
            $("#GamesListsContainer .games-list-container").each(function (index) {
                $(this).addClass("hidden");
            });

            // hide the overflow div 
            $("#DivToHideOverflowFromLastGamesList").addClass("hidden");

            // show the game list
            $("#SearchResultsContainer")
                .removeClass("hidden")
                .removeClass("overflow-hidden")
                .addClass("overflow-visible")
                .css("height", "762px"); //specific height set due to GamesListBehavior.js updateHeight method.

            $("#GamesPageSearch .cancel-search").addClass("show-cancel");
            $("#FiltersAndSort").addClass("disabled");

            // set the multple games displayed flag to false
            // used in case when we are loading search directly from url
            // used for infinite scrolling behavior
            Roblox.GamesPageContainerBehavior.setMultipleGamesListsDisplayedState(false);

            // we are in search mode, update the URL
            Roblox.GamesDisplayShared.updateURLFromSearchState();
            

            break;
        case "off":
            Roblox.GamesPageContainerBehavior.handleGamesFilterResetClick();
        case "reset":
            searchStateHolder.attr("data-searchstate", "off");

            // disable all of the filters
            $("#SortFilter").prop("disabled", false);
            $("#TimeFilter").prop("disabled", false);
            $("#GenreFilter").prop("disabled", false);

            $("#GamesPageSearch .cancel-search").removeClass("show-cancel");
            $("#FiltersAndSort").removeClass("disabled");
            $("#searchbox").val("").focus().blur();
            $("#SearchResultsContainer .search-query-text").text("");

            // turn off cursor for pending search
            target.removeClass('search-pending');

            // unhide the overflow div 
            $("#DivToHideOverflowFromLastGamesList").removeClass("hidden");

            // check whether we are in horizonal mode vs vertical mode in order to display in-game ads properly
            Roblox.AdsHelper.GamesPage.checkAdDisplayState();
            break;
        default:
            break;
    }


}



Roblox.GamesDisplayShared.hookUpSearch = function () {
    // determine which search to use
    var useOnPageSearch = $("#ResponsiveWrapper").data("gamessearchonpage");

    // should only have to do this check once.
    var gameSearch = useOnPageSearch ? function () {
        Roblox.GamesDisplayShared.searchOnPage(Roblox.GamesPageContainerBehavior.numGamesToFetchOnSearch);
    } : function() {
        Roblox.GamesDisplayShared.search();
    }

    // hook up magnifying glass icon click
    $("#GamesPageSearch .SearchIconButton").click(function (event) {
        gameSearch();
    });

    // hitting enter in searchbox
    $("#searchbox").keypress(function (e) {
        if (e.which == 13) {
            var val = $.trim(this.value);
            var maxChars = 50;
            // if search box is empty or query too big dont try to search
            if ((val !== "") && (this.value.length <= maxChars)) {
              gameSearch();  
            }
        }
    });

    $("#searchbox").focus(function () {
        $(this).addClass("focus");
        if (this.value == Roblox.SearchBox.Resources.search) {
            this.value = "";
        }
        else {
            this.select();
        }
    });
    $("#searchbox").blur(function () {
        if ($.trim(this.value) == "") {
            $(this).removeClass("focus");
            this.value = Roblox.SearchBox.Resources.search;
        }
    });
};

Roblox.GamesDisplayShared.hookUpCancel = function () {
    $("#GamesPageSearch .cancel-search").click(function () {
        Roblox.GamesDisplayShared.toggleSearch("off");
    });
}

Roblox.GamesDisplayShared.hookUpEvents = function () {
    Roblox.GamesDisplayShared.hookUpSearch();
    Roblox.GamesDisplayShared.hookUpCancel();
}

$(function () {
    $("#searchbox").val(Roblox.SearchBox.Resources.search);
});

}
/*
     FILE ARCHIVED ON 00:15:07 Jan 08, 2016 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:12 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 0.856
  exclusion.robots: 0.076
  exclusion.robots.policy: 0.065
  cdx.remote: 0.119
  esindex: 0.012
  LoadShardBlock: 205.116 (3)
  PetaboxLoader3.datanode: 213.438 (4)
  load_resource: 65.831
  PetaboxLoader3.resolve: 37.811
*/