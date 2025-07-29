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

/* ---------------------- Variables ----------------------- */

var Roblox = Roblox || {};
Roblox.GamesListBehavior = {};
Roblox.GamesListBehavior.ExtraHeightToShowHover = 50;
Roblox.GamesListBehavior.MaxNumberOfGamesToLoadPerRequest = 200;
Roblox.GamesListBehavior.NumberOfGamesToAppendInHScrollMode = 12;
Roblox.GamesListBehavior.RefreshAdsInGamesPageEnabled = false;

/* ---------------------- Object constructor & prototype ----------------------- */

Roblox.GamesListBehavior.GamesListObject = function (divId, sortFilter, gameFilter, minBCLevel, personalizedUniverseId) {
    this.divId = divId;
    this.isShown = false;
    this.sortFilter = sortFilter;
    this.gameFilter = gameFilter;
    this.timeFilter = 0;            // 0 = Now
    this.minBCLevel = minBCLevel;
    this.personalizedUniverseId = personalizedUniverseId;
    this.genreId = 1;               // 1 = All
    this.numberOfRowsToOccupy = 0;  // number of rows to show on games page
    this.numberOfGamesToFetch = 0;
    this.numberOfGamesOnScreen = 0;
    this.startIndex = 0;

    this.jqxhr = null;
    this.reachedHorizontalScrollMax = false;
    this.numberOfGamesOnLastRow = 0;

    // Only attach hover handlers when device type is "computer" 
    // not "phone" or "tablet" - which trigger class 'rbx-touch'
    if(!$("#navContent").hasClass("rbx-touch")){
        this.attachHoverHandlers();
    }
    this.attachScrollHandlers();
    $("#" + this.divId).on("click", ".games-filter-changer", Roblox.GamesPageContainerBehavior.handleGamesFilterChangerClick);
};

Roblox.GamesListBehavior.GamesListObject.prototype = {

    /* ----------- Fetching games ----------- */
      populateGamesList: function (numberOfGamesToFetch, numberOfGamesToAppendAfterInitialFetch) {
        var that = this;
        if (that.jqxhr)
            jqxhr.abort();

        Roblox.AdsHelper.GamesPage.checkAdDisplayState();
        Roblox.GamesPageContainerBehavior.calculateNumberOfColumns();
        

        var url = Roblox.GamesPageContainerBehavior.getURLBasedOnSortFilter(that.sortFilter);
        var numOfCols = Roblox.GamesPageContainerBehavior.getNumberOfColumns();
        var numOfRows = Roblox.GamesPageContainerBehavior.getNumberOfRows();


        that.numberOfGamesToFetch = Math.min(numberOfGamesToFetch, Roblox.GamesListBehavior.MaxNumberOfGamesToLoadPerRequest);
        that.numberOfGamesOnLastRow = 0;

        that.timeFilter = that.getDefaultTimeFilterForSortFilter(that.sortFilter, that.timeFilter);
        
        // Top Recommended sort doesn't have scrollers. For other sorts: the 'next' scroller is initially visible
        if(Roblox.GamesPageContainerBehavior.isInHorizontalScrollMode() && that.numberOfRowsToOccupy > 1) {
            $("#" + that.divId + " .scroller").remove();
        } else {
            that.reachedHorizontalScrollMax = false;
            $("#" + that.divId + " .scroller.next").removeClass("hidden");
        }
        
        Roblox.GamesPageContainerBehavior.setAdSpanFromHeight(Roblox.GamesPageContainerBehavior.initialAdHeight);

        var requestData = {
            SortFilter: that.sortFilter,
            TimeFilter: that.timeFilter,
            GenreID: that.genreId,
            GameFilter: that.gameFilter,
            MinBCLevel: that.minBCLevel,
            StartRows: that.startIndex,
            MaxRows: that.numberOfGamesToFetch,
            IsUserLoggedIn: Roblox.GamesPageContainerBehavior.IsUserLoggedIn,
            NumberOfRowsToOccupy: that.numberOfRowsToOccupy,
            NumberOfColumns: numOfCols,
            IsInHorizontalScrollMode: Roblox.GamesPageContainerBehavior.isInHorizontalScrollMode(),
            DeviceTypeId: Roblox.GamesPageContainerBehavior.getDeviceTypeId(),
            AdSpan: Roblox.GamesPageContainerBehavior.getAdSpan(),
            AdAlignment: 0,
            PersonalizedUniverseId: that.personalizedUniverseId
        };

        that.showLoadingIndicator();
        that.jqxhr = $.get(url, requestData, function (data) {

            that.jqxhr = null;
            var tempDiv = $("<div></div>").append(data);

            if (!Roblox.GamesPageContainerBehavior.isInHorizontalScrollMode()){
                that.hideExtraGames(tempDiv);
            }

            // check whether we are in horizonal mode vs vertical mode in order to display in-game ads properly
            Roblox.AdsHelper.GamesPage.checkAdDisplayState();

            var holder = $("#" + that.divId + " .games-list");
            // this removes items from mult-rows div when moving from horizontal to vertical display
            $(holder).find(".game-item,.games-list-column, .in-game-search-ad").remove();
            if (Roblox.GamesPageContainerBehavior.isInHorizontalScrollMode()) {
                if (that.numberOfRowsToOccupy < 2) {
                    holder = $("#" + that.divId + " .horizontally-scrollable");
                } else {
                    holder.append('<div class = "multi-rows"></div>');
                    holder = $(holder).find(".multi-rows");
                }
            }
            holder.append(tempDiv.html());
            that.numberOfGamesOnScreen = $(holder).find(".game-item").length;
            Roblox.AdsHelper.GamesPage.populateNewAds();
            that.hideLoadingIndicator();

            // Load more Games Once
            if (!Roblox.GamesPageContainerBehavior.getMultipleGamesListsDisplayedState() &&
                numOfCols * numOfRows > Roblox.GamesPageContainerBehavior.NumberOfGamesToFetchInVScrollMode) {
                Roblox.GamesPageContainerBehavior.loadMoreGames();
            }
            
            if (Roblox.GamesPageContainerBehavior.isInHorizontalScrollMode()) {
                var hScrollableList = $("#" + that.divId + " .horizontally-scrollable");
                
                if (hScrollableList.length > 0) {
                    $(hScrollableList).css("left", 0);
                    $(hScrollableList).siblings(".scroller.prev").addClass("hidden");
                }

                // outerWidth get the whole element width, true means including margin
                var widthOfAGameItem = $(holder).find(".game-item").outerWidth(true);
                Roblox.GamesPageContainerBehavior.toggleCarouselButtons($("#" + that.divId),
                                                                        widthOfAGameItem,
                                                                        that.numberOfGamesOnScreen, 0);

                that.noMoreGamesToLoad = that.numberOfGamesOnScreen < that.numberOfGamesToFetch;

                if (!that.noMoreGamesToLoad && numberOfGamesToAppendAfterInitialFetch > 0) {
                    that.jqxhr = null;
                    that.appendToGamesList(numberOfGamesToAppendAfterInitialFetch);
                }
            }
            // check to see if we need to refresh ads
            Roblox.AdsHelper.GamesPage.refreshOldAds();
        });
    },
    
      appendToGamesList: function (numberOfGamesToFetch) {
        if (this.jqxhr || (Roblox.GamesPageContainerBehavior.isInHorizontalScrollMode() && this.reachedHorizontalScrollMax))
            return;

        var that = this;
        var url = Roblox.GamesPageContainerBehavior.getURLBasedOnSortFilter(that.sortFilter);
        numberOfGamesToFetch = Math.min(numberOfGamesToFetch, Roblox.GamesListBehavior.MaxNumberOfGamesToLoadPerRequest);

        that.timeFilter = that.getDefaultTimeFilterForSortFilter(that.sortFilter, that.timeFilter);

        that.unhideGames();

        // figure out adSpan. This is a subsequent load.
        Roblox.GamesPageContainerBehavior.setAdSpanFromHeight(Roblox.GamesPageContainerBehavior.subsequentAdHeight);

        var requestData = {
            SortFilter: that.sortFilter,
            TimeFilter: that.timeFilter,
            GenreID: that.genreId,
            GameFilter: that.gameFilter,
            MinBCLevel: that.minBCLevel,
            StartRows: that.numberOfGamesOnScreen,
            MaxRows: numberOfGamesToFetch,
            IsUserLoggedIn: Roblox.GamesPageContainerBehavior.IsUserLoggedIn,
            NumberOfRowsToOccupy: that.numberOfRowsToOccupy,
            NumberOfColumns: Roblox.GamesPageContainerBehavior.getNumberOfColumns(),
            IsInHorizontalScrollMode: Roblox.GamesPageContainerBehavior.isInHorizontalScrollMode(),
            DeviceTypeId: Roblox.GamesPageContainerBehavior.getDeviceTypeId(),
            AdSpan: Roblox.GamesPageContainerBehavior.getAdSpan(),
                AdAlignment: Roblox.GamesPageContainerBehavior.calcAdAlignment(),
                PersonalizedUniverseId: that.personalizedUniverseId
        };

        that.showLoadingIndicator();
        that.jqxhr = $.get(url, requestData, function (data) {
            that.jqxhr = null;
            var tempDiv = $("<div></div>").append(data);

            if (!Roblox.GamesPageContainerBehavior.isInHorizontalScrollMode())
                that.hideExtraGames(tempDiv);

            var holder = $("#" + that.divId + " .games-list");
            if (Roblox.GamesPageContainerBehavior.isInHorizontalScrollMode()) {
                holder = that.numberOfRowsToOccupy > 1 ? $("#" + that.divId + " .multi-rows") : $("#" + that.divId + " .horizontally-scrollable");
            }
            holder.append(tempDiv.html());           
            that.numberOfGamesOnScreen = $(holder).find(".game-item").length;

            Roblox.AdsHelper.GamesPage.populateNewAds();
            Roblox.AdsHelper.GamesPage.refreshOldAds();
            that.hideLoadingIndicator();

            if (Roblox.GamesPageContainerBehavior.isInHorizontalScrollMode()) {
                that.noMoreGamesToLoad = $(data).find(".game-item").length < numberOfGamesToFetch;
                that.reachedHorizontalScrollMax = that.numberOfGamesOnScreen > Roblox.GamesPageContainerBehavior.MaxNumberOfGamesToFetchInHScrollMode;
            }
        });
    },

    getDefaultTimeFilterForSortFilter: function (sortFilter, currentTimeFilter) {
        // get the data-default attr for the timefilter and determine whether to return the default value
        var dataDefault = $("#TimeFilter").attr("data-default");
        var gamesPageContainerBehavior = Roblox.GamesPageContainerBehavior;
        var defaultToWeeklyRatings = gamesPageContainerBehavior.isTopRatedDefaultToWeeklyEnabled();
        var defaultTopPaidToWeekly = gamesPageContainerBehavior.isTopPaidDefaultToWeeklyEnabled();
        var isHorizontalScrollMode = gamesPageContainerBehavior.isInHorizontalScrollMode();

        // behavior for single game "page" , such as Popular or Top Earning
        if(dataDefault == 0) {
            currentTimeFilter = 0;
        } else if(currentTimeFilter == 0 ){
            currentTimeFilter = dataDefault;
        }
        
        // if we are on the combined games "page" (isHorizontalScrollMode == true)
        if (isHorizontalScrollMode &&
                ((defaultToWeeklyRatings && sortFilter == 11) || //Top Rated
                (defaultTopPaidToWeekly && sortFilter == 9) || //TopPaid
                (sortFilter == 16))) //Top Retaining
        {
            currentTimeFilter = 2;//Weekly
        }

        return currentTimeFilter;
    },

    /* ----------- Show/hide ----------- */

    show: function (isInMultiGamesListsDisplayMode) {
        if (isInMultiGamesListsDisplayMode) {
            $("#" + this.divId + " .games-list").find(".game-item,.games-list-column,.in-game-search-ad").remove();
        }

        this.isShown = true;
        $("#" + this.divId).removeClass("hidden");

        if (isInMultiGamesListsDisplayMode) {
            $("#" + this.divId + " .show-in-multiview-mode-only").removeClass("hidden");
        }
        else {
            $("#" + this.divId + " .show-in-multiview-mode-only").addClass("hidden");
        }
    },
    hide: function () {
        this.isShown = false;
        this.numberOfRowsToOccupy = 0;
        $("#" + this.divId).addClass("hidden");
    },


    /* ----------- Resizing ----------- */

    updateHeight: function () {
        $("#" + this.divId).height(this.maxHeight());

        if (Roblox.GamesPageContainerBehavior.isInHorizontalScrollMode()) {
            $("#" + this.divId + " .horizontally-scrollable").height(this.numberOfRowsToOccupy * Roblox.GamesPageContainerBehavior.RowHeightIncludingPadding);
        }
    },
    showOverflow: function () {

        $("#" + this.divId).removeClass("overflow-hidden");
        $("#" + this.divId).addClass("overflow-visible");
    },
    hideOverflow: function () {

        $("#" + this.divId).removeClass("overflow-visible");
        $("#" + this.divId).addClass("overflow-hidden");
    },


    /* ----------- Event handlers ----------- */

    attachHoverHandlers: function () {
        $("#" + this.divId).on({
            mouseenter: function () {
                $(this).children(".show-on-hover-only").removeClass("hidden");
                $(this).siblings(".hover-shown").show();
            },
            mouseleave: function () {
                $(this).children(".show-on-hover-only").addClass("hidden");
                $(this).siblings(".hover-shown").hide();
            }
        }, ".always-shown");
    },

    attachScrollHandlers: function () {
        var that = this;

        $("#" + this.divId).on("click", ".scroller", function () {
            var eventSource = this;
            var scrollDirection = "next";

            if (eventSource) {
                if (eventSource.className.indexOf("prev") !== -1) {
                    scrollDirection = "prev";
                }

                that.handleHorizontalScroll(scrollDirection);
            }
        });
    },

    handleHorizontalScroll: function (scrollDirection) {
        var hScrollableList = $("#" + this.divId + " .horizontally-scrollable");

        if (hScrollableList.length > 0) {
            $(hScrollableList).stop("", true, true);                                                // Complete any pending animations and flush the queue

            var hScrollableListCurrentLeft = parseInt($(hScrollableList).css("left")) || 0;
            hScrollableListCurrentLeft = this.getClosestColumnBoundary(hScrollableListCurrentLeft); // Useful during rapid scrolling

            var hScrollableListNewLeft;
            if (scrollDirection === "prev") {
                hScrollableListNewLeft = hScrollableListCurrentLeft + this.getLeftBoundaryOfLastVisibleColumn();
                hScrollableListNewLeft = Math.min(hScrollableListNewLeft, 0);                       // Don't fall off the left edge
                $("#" + this.divId + " .scroller.next").removeClass("hidden");
            }
            else {
                hScrollableListNewLeft = hScrollableListCurrentLeft - this.getLeftBoundaryOfLastVisibleColumn();

                this.appendToGamesList(Roblox.GamesListBehavior.NumberOfGamesToAppendInHScrollMode);

                if ((this.noMoreGamesToLoad || this.reachedHorizontalScrollMax) && !this.isAvailableWidthFullyOccupied(hScrollableListNewLeft))
                    $("#" + this.divId + " .scroller.next").addClass("hidden");
            }

            $(hScrollableList).animate({ "left": hScrollableListNewLeft + "px" });

            if (Roblox.GamesListBehavior.RefreshAdsInGamesPageEnabled) {
                Roblox.GamesPageContainerBehavior.refreshAds();

            }
            

            var shouldPrevScrollerBeVisible = hScrollableListNewLeft < 0;
            if (shouldPrevScrollerBeVisible) {
                $(hScrollableList).siblings(".scroller.prev").removeClass("hidden");
            }
            else {
                $(hScrollableList).siblings(".scroller.prev").addClass("hidden");
            }
        }
    },


    /* ----------- Helpers ----------- */

    showLoadingIndicator: function () {
        if (!Roblox.GamesPageContainerBehavior.isInHorizontalScrollMode())
            $("#" + this.divId).css("cursor", "wait");
    },
    hideLoadingIndicator: function () {
        $("#" + this.divId).css("cursor", "auto");
    },
    resetStartIndex: function () {
        this.startIndex = 0;
        $("#" + this.divId + " .previous").addClass("disabled");
        $("#" + this.divId + " .next").removeClass("disabled");
    },
    hideExtraGames: function (gamesHTML) {
        var numberOfGames = $(gamesHTML).find(".game-item").length;
        var numberOfGamesToRemainVisible = Roblox.GamesPageContainerBehavior.getNumberOfColumns() * this.numberOfRowsToOccupy - this.numberOfGamesOnLastRow;
        var index = 0;

        if (numberOfGames > numberOfGamesToRemainVisible) {
            $(gamesHTML).find(".game-item").each(function () {
                if (index++ >= numberOfGamesToRemainVisible)
                    $(this).addClass("hidden");
            });
        }
    },

    maxHeight: function () {
        var height = 0;

        if (this.isShown) {
            height += (this.numberOfRowsToOccupy * Roblox.GamesPageContainerBehavior.RowHeightIncludingPadding);
            height += Roblox.GamesPageContainerBehavior.GamesListHeaderHeight;
            height += Roblox.GamesListBehavior.ExtraHeightToShowHover;
        }
        return height;
    },

    unhideGames: function () {
        var numberOfHiddenGames = $("#" + this.divId + " .game-item.hidden").length;
        this.numberOfGamesOnLastRow = numberOfHiddenGames % Roblox.GamesPageContainerBehavior.getNumberOfColumns();

        $("#" + this.divId + " .game-item.hidden").each(function () {
            $(this).removeClass("hidden");
        });
    },

    getClosestColumnBoundary: function (startPosition) {
        var differenceToClosestColumnBoundary = Math.abs(startPosition % Roblox.GamesPageContainerBehavior.ColumnWidthIncludingPadding);
        return startPosition + differenceToClosestColumnBoundary;       // startPosition should be <= 0
    },

    getLeftBoundaryOfLastVisibleColumn: function () {
        var gamesListWidth = $("#" + this.divId + " .games-list").width();
        var numberOfFullyVisibleColumns = Math.floor(gamesListWidth / Roblox.GamesPageContainerBehavior.ColumnWidthIncludingPadding);
        return numberOfFullyVisibleColumns * Roblox.GamesPageContainerBehavior.ColumnWidthIncludingPadding;
    },

    isAvailableWidthFullyOccupied: function (startPosition) {
        var gamesListWidth = $("#" + this.divId + " .games-list").width();
        var numberOfColumnsRequiredToFullyOccupyWidth = Math.ceil(gamesListWidth / Roblox.GamesPageContainerBehavior.ColumnWidthIncludingPadding);
        var numberOfGameItemsHiddenToTheLeft = Math.abs(startPosition / Roblox.GamesPageContainerBehavior.ColumnWidthIncludingPadding);
        return this.numberOfGamesOnScreen >= (numberOfGameItemsHiddenToTheLeft + numberOfColumnsRequiredToFullyOccupyWidth);
    }
};


}
/*
     FILE ARCHIVED ON 00:15:07 Jan 08, 2016 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:16 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 0.822
  exclusion.robots: 0.105
  exclusion.robots.policy: 0.093
  cdx.remote: 0.077
  esindex: 0.011
  LoadShardBlock: 80.717 (3)
  PetaboxLoader3.datanode: 121.122 (4)
  load_resource: 187.393
  PetaboxLoader3.resolve: 94.194
*/