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

var Roblox = Roblox || {};

Roblox.GamesPageContainerBehavior = function () {

    /* ---------------- Variables & constants ---------------- */

    var FetchIntervalms = 300, ShrinkIntervalms = 50;
    var ColumnWidthIncludingPadding = 202, PageHeaderHeight = 240, GamesListHeaderHeight = 40, InterGamesListSpace = 40;
    var RowHeightIncludingPadding = 168, GamesListSquishHeight = 55;
    var NumberOfGamesToInitiallyFetchInHScrollMode = 12, NumberOfGamesToPrefetchAfterInitialFetchInHScrollMode = 12, NumberOfGamesToFetchInVScrollMode = 60, MaxNumberOfGamesToFetchInHScrollMode = 60;
    var NumberOfGamesToInitiallyFetchInMultirowsMode = 40, NumberOfGamesToFetchAfterInitialFetchInMultirowsMode = 30;
    var GutterMinWidth = 970;
    var SortFiltersForWhichTimeFiltersShouldBeHidden = [];
    var SortFiltersForWhichGenreFiltersShouldBeHidden = [];
    var UserSpecificSortFilters = [5, 6, 10];       // MyFavorite, MyRecent, Purchased
    var Nav2014Width = 175, Nav2014ForceOpenThreshold = 1480;
    var DefaultTimeFilter = 2; // Past Week

    var gamesListArray = [];
    var numberOfShownGamesLists = 0;
    var numberOfColumns;
    var numberOfRowsForEachList;
    var numberOfRowsForTopList = 4; // We show 4 rows for Recommended sort when it is on top
    var referenceWidth, referenceHeight, originalSidebarTop, originalSidebarClass;
    var areMultipleGamesListsDisplayed;
    var prevScrollTop = 0;
    var History = window.History;
    var haveGamesBeenFetched = false, isURLAlreadyUpdated = false, areFiltersAlreadyUpdated = false;
    var topAdEnabled = false, guttersEnabled = false;
    var gamesPageLeftGutter, gamesPageRightGutter, gutterAdWidth;
    var navContainer;
    var distanceFromBottomAtWhichToLoadMoreGames = 400;
    var isProcessingAdRefresh = false;
    var defaultTopRatedToWeekly = false, defaultTopPaidToWeekly = false;
    var featuredSortFilterId = 3;

    var isWorsePerformanceEnabled;
    var worsePerformanceDelay;
    var timeFilterValues = [];
    var isGameSearchOnPage = false;
    var searchQueryDefault = "";
    var numGamesToFetchOnSearch = 40; // should be 40
    var areAdsInGameSearchResults = false;
    var adSpan = 0;
    var initialAdHeight = 1326; // in-game ad initial height
    var subsequentAdHeight = 800; // subsequent loads
    var adAlignment = 0;


    /* ---------------- Ready ---------------- */

    $(function () {
       
        document.title = Roblox.GamesPageContainerBehavior.Resources.pageTitle;
        $("#SortFilter [data-hidetimefilter]").each(function (i, ele) {
             SortFiltersForWhichTimeFiltersShouldBeHidden.push($(ele).val());
        });

        $("#SortFilter [data-hidegenrefilter]").each(function (i, ele) {
             SortFiltersForWhichGenreFiltersShouldBeHidden.push($(ele).val());
        });

        defaultTopRatedToWeekly = $("#FiltersAndSort").data('defaultweeklyratings');
        defaultTopPaidToWeekly = $("#FiltersAndSort").data('defaulttoppaidtoweekly');
        navContainer = $('div.nav-container');
        createGamesListObjects();

        if ($("#GamesPageRightColumnSidebar").length > 0) {
            originalSidebarClass = $("#GamesPageRightColumnSidebar").attr("class");
        }

        // populate search box if default has been set and set isGameSearchOnPage flag 
        isGameSearchOnPage = $("#ResponsiveWrapper").data("gamessearchonpage");

        //change display behavior for games and games search results if ads are in the results
        areAdsInGameSearchResults = $("#ResponsiveWrapper").data("adsingamesearchresultsenabled");

        searchQueryDefault = $("#searchbox").data("default");
        if (isGameSearchOnPage && searchQueryDefault !== "") {
            $("#searchbox").val(searchQueryDefault);
        }

        isWorsePerformanceEnabled = $('div[data-worseperformanceenabled]').data('worseperformanceenabled') === "True";
        worsePerformanceDelay = Number($('div[data-worseperformancedelay]').data('worseperformancedelay'));

        hookUpFiltersToActions();

        History.Adapter.bind(window, "statechange", function () {
            handleURLChange(History.getState().data);
        });
        
        setFilterValuesFromURL();

        /*
         * Pull in the time filter values from HTML for use in toggling
         * time filter state. (Cannot assume values as they come from GamesPageContainerView.cshtml)  
         */
        populateTimeFilterValues();

        updateURL();

        // hooks up search box and cancel
        Roblox.GamesDisplayShared.hookUpEvents();

        setReferenceDimensions();
        
        gamesPageLeftGutter = $("#LeftGutterAdContainer");
        gamesPageRightGutter = $("#RightGutterAdContainer");
        guttersEnabled = gamesPageLeftGutter.length !== 0;
        topAdEnabled = $("#TopAbpContainer").length !== 0;

        $(".games-filter-resetter").click(handleGamesFilterResetClick);
        $(".games-filter-changer").click(handleGamesFilterChangerClick);

        if (guttersEnabled) {
            Roblox.AdsHelper.AdRefresher.registerAd("LeftGutterAdContainer");
            Roblox.AdsHelper.AdRefresher.registerAd("RightGutterAdContainer");
        }
        else {
            Roblox.AdsHelper.AdRefresher.registerAd("GamePageAdDiv1");
            Roblox.AdsHelper.AdRefresher.registerAd("GamePageAdDiv2");
            Roblox.AdsHelper.AdRefresher.registerAd("GamePageAdDiv3");
        }

        if (topAdEnabled) {
            Roblox.AdsHelper.AdRefresher.registerAd("TopAbpContainer");
        }
        
        if (!haveGamesBeenFetched) {
            // History.js doesn't trigger a state change if the page is reloaded
            handleURLChange(History.getState().data);
        }
        
        adjustUIBasedOnWindowDimensions();
        
        // Prevent selection of the scroller image on doube click
        $(".scroller, .scroller .arrow, .scroller .arrow img").on("dblclick", function (event) {
            if (window.getSelection)
                window.getSelection().removeAllRanges();
            else if (document.selection)
                document.selection.empty();

            return false;
        });

        hookUpGutterAdsHiding();
        hookUpFilmStripAdHiding();

        // unlock searching - prevents double search on page load
        // must happen after the "handleURLChange" above
        // cannot be set via server because that is "too soon"
        if (isGameSearchOnPage) {
            $("#SearchResultsContainer").addClass("can-search");
        }

        // If we can go into search mode do the search
        if (isGameSearchOnPage && searchQueryDefault !== ""){
            // Doing search since we are in page load.
            Roblox.GamesDisplayShared.searchOnPage(numGamesToFetchOnSearch);
        }

        // check whether we are in horizonal mode vs vertical mode in order to display in-game ads properly
        Roblox.AdsHelper.GamesPage.checkAdDisplayState();

    });


    /* ---------------- Initial housekeeping ---------------- */

    function createGamesListObjects() {
        var divId, sortFilter, gameFilter, minBCLevel, gamesListObject;

        $(".games-list-container").each(function () {
            divId = $(this).attr("id");
            sortFilter = $(this).data("sortfilter");
            gameFilter = $(this).data("gamefilter");
            minBCLevel = $(this).data("minbclevel");

            gamesListObject = new Roblox.GamesListBehavior.GamesListObject(divId, sortFilter, gameFilter, minBCLevel);
            gamesListArray.push(gamesListObject);   
        });
    }

    function populateVisibleGamesLists() {
        haveGamesBeenFetched = true;

        for (var i = 0; i < gamesListArray.length; i++) {
            if (gamesListArray[i].isShown) {
                if (areMultipleGamesListsDisplayed) {
                    if (gamesListArray[i].numberOfRowsToOccupy > 1) {
                        gamesListArray[i].populateGamesList(Math.max(NumberOfGamesToInitiallyFetchInMultirowsMode, (numberOfColumns + 1) * gamesListArray[i].numberOfRowsToOccupy), NumberOfGamesToFetchAfterInitialFetchInMultirowsMode);
                    } else {
                        gamesListArray[i].populateGamesList(NumberOfGamesToInitiallyFetchInHScrollMode, NumberOfGamesToPrefetchAfterInitialFetchInHScrollMode);
                    }
                }
                else {
                    gamesListArray[i].populateGamesList(NumberOfGamesToFetchInVScrollMode);
                }
            }
        }

        refreshAds();
    }


    /* ---------------- Layout and positioning ---------------- */

    function calculateNumberOfColumns() {
        var leftPadding = parseInt($($(".games-list-container")[0]).css("padding-left"));
        var availableWidth = $("#GamesListsContainer").width();

        if (availableWidth > leftPadding)
            availableWidth -= leftPadding;
        else
            availableWidth = 0;

        numberOfColumns = Math.floor(availableWidth / ColumnWidthIncludingPadding);
        // if we are in ads in games list mode
        if (Roblox.GamesPageContainerBehavior.areAdsInSearchResults()) {
            var tempDiv = $("<div class='ads-in-game-search' id='temp-remove'><div class='game-item'></div></div>");
            $("#Footer").append(tempDiv);
            var currColWidth = tempDiv.children('.game-item').width();
            $("#temp-remove").remove();
            if(currColWidth < ColumnWidthIncludingPadding) {
                currColWidth = ColumnWidthIncludingPadding;
            }
            numberOfColumns = Math.floor(availableWidth / currColWidth);
        }
    }

    function assignRowsToGamesLists() {
        var indexOfLastShownList = -1;
        var containerHeight = parseInt($("#GamesListsContainer").css("min-height")) || 0;
        var availableHeight = Math.max($(window).height(), containerHeight) - (PageHeaderHeight + ((GamesListHeaderHeight + InterGamesListSpace) * numberOfShownGamesLists));
        var maxRowsOnScreen = Math.floor(availableHeight / RowHeightIncludingPadding);

        if (areMultipleGamesListsDisplayed)
            // Change this to enable multi-rows in multi-list view model
            numberOfRowsForEachList = 1;                                                                        // A max of one row is supported in multi-list view mode
        else
            numberOfRowsForEachList = Math.max(1, Math.floor(2 * maxRowsOnScreen / numberOfShownGamesLists));   // ~2x rows as will fit on screen; forces a scrollbar so infi scroll can work

        for (var i = 0; i < gamesListArray.length; i++) {
            if (gamesListArray[i].isShown) {
                // Rows to occupy for lists in multi-list view model
                if (areMultipleGamesListsDisplayed && Roblox.GamesListBehavior.isUserEligibleForMultirowFirstSort && i === 0) {
                    gamesListArray[i].numberOfRowsToOccupy =  numberOfRowsForTopList; // Showing 4 rows for the very first sort and 1 row for the rest of them. 
                }
                else {
                    gamesListArray[i].numberOfRowsToOccupy = numberOfRowsForEachList;
                }
                indexOfLastShownList = i;
            }
        }
    }

    function updateGamesListsHeights() {
        for (var i = 0; i < gamesListArray.length; i++) {
            gamesListArray[i].updateHeight();

            if (!areMultipleGamesListsDisplayed && gamesListArray[i].isShown)
                gamesListArray[i].showOverflow();
            else
                gamesListArray[i].hideOverflow();
        }
        // check if we are in search state. If so we need to show the search games list, 
        // which is always the last array element.
        if($('#GamesPageLeftColumn').attr('data-searchstate') == 'on') {
            gamesListArray[gamesListArray.length-1].showOverflow();
    }
    }

    function unsquishAllGamesLists() {
        for (var i = 0; i < gamesListArray.length; i++) {
            $("#" + gamesListArray[i].divId).css("top", "");
        }
    }

    function squishConsecutiveGamesListsTogether() {
        // Helps with hiding overflowing games when page is shrunk. Needed because
        // containers must have some extra height to accommodate the hover display
        // and must have overflow hidden to prevent a change in height.
        var isThisTheFirstShownList = true;         // No need to squish the top-most list
        var numberOfListsThatHaveBeenSquished = 0;
        var squishHeight;

        for (var i = 0; i < gamesListArray.length; i++) {
            if (gamesListArray[i].isShown) {
                if (isThisTheFirstShownList) {
                    isThisTheFirstShownList = false;
                }
                else {
                    squishHeight = (numberOfListsThatHaveBeenSquished++ + 1) * GamesListSquishHeight;
                    $("#" + gamesListArray[i].divId).css("top", "-" + squishHeight + "px");
                }
            }
        }

        squishHeight = (numberOfShownGamesLists > 2) ? 160 : 110;
        $("#DivToHideOverflowFromLastGamesList").css("top", "-" + squishHeight + "px");
    }

    function updateCarouselButtons() {
        $('.games-list-container').each(function (k, elm) {
            var currentContainerId = $(elm).attr("id");
            var numOfGameItems = $("#" + currentContainerId).find(".game-item").length;
            // outerWidth get the whole element width, true means including margin
            var widthOfAGameItem = $("#" + currentContainerId).find(".game-item").outerWidth(true);
            // get css left value to understand how much the game list is shiftted to the left
            var leftPositionOfGameList = $("#" + currentContainerId).find(".horizontally-scrollable").css("left");

            toggleCarouselButtons($(elm), widthOfAGameItem, numOfGameItems, leftPositionOfGameList);
        });
    }

    function doesNextButtonFitContainer(widthOfContainer, widthOfAGameItem, numOfGameItems, leftPositionOfGameList) {

        var numOfGameItemsFitOnScreen = widthOfContainer / widthOfAGameItem;
        numOfGameItemsFitOnScreen = isNaN(numOfGameItemsFitOnScreen) ?
                                                0 : Math.floor(numOfGameItemsFitOnScreen);

        leftPositionOfGameList = !leftPositionOfGameList || isNaN(parseInt(leftPositionOfGameList)) ?
                                                0 : Math.abs(parseInt(leftPositionOfGameList));

        var numOfLeftGameItems = leftPositionOfGameList / widthOfAGameItem;
        numOfLeftGameItems = isNaN(numOfLeftGameItems) ?
                                                0 : Math.floor(numOfLeftGameItems);
        return (numOfGameItems - numOfLeftGameItems) <= numOfGameItemsFitOnScreen;
    }

    function toggleCarouselButtons(containerElement, widthOfAGameItem, numOfGameItems, leftPositionOfGameList) {
        var isFit = doesNextButtonFitContainer(containerElement.width(),
                                                    widthOfAGameItem, 
                                                    numOfGameItems, 
                                                    leftPositionOfGameList);
        containerElement.find(".scroller.next").toggleClass("hidden", isFit);
    }

    /* ---------------- Resizing ---------------- */
    $(window).resize(function () {
        var newWidth = $(window).width();
        var newHeight = $(window).height();
        updateCarouselButtons();
        if ((newWidth > referenceWidth) || (newHeight > referenceHeight)) {
            setTimeout(function () {
                adjustUIBasedOnWindowDimensions();

                if (!areMultipleGamesListsDisplayed) {
                    // only load more games if we are reaching the bottom of the page
                    // not just because we resize the window
                    if (shouldLoadMoreGames()) {
                        loadMoreGames();
                    }

                    if (newWidth >= 980) {
                        $("#GamesPageRightColumnSidebar.move-with-window-min-left").toggleClass("move-with-window-min-left move-with-window-stick-right");
                    }
                }

                referenceWidth = newWidth;
                referenceHeight = newHeight;
            }, FetchIntervalms);
        }
        else if ((newWidth < referenceWidth) || (newHeight < referenceHeight)) {
            setTimeout(function () {
                if (newWidth < referenceWidth)
                    adjustGutters();

                if (areMultipleGamesListsDisplayed) {
                    adjustUIBasedOnWindowDimensions();
                }
                else {
                    if (newWidth < 980) {
                        $("#GamesPageRightColumnSidebar.move-with-window-stick-right").toggleClass("move-with-window-min-left move-with-window-stick-right");
                    }
                }

                referenceWidth = newWidth;
                referenceHeight = newHeight;
            }, ShrinkIntervalms);
        }

        distanceFromBottomAtWhichToLoadMoreGames = newHeight / 2;
    });

    function adjustUIBasedOnWindowDimensions() {
        adjustGutters();
        calculateNumberOfColumns();
        assignRowsToGamesLists();
        updateGamesListsHeights();      
    }


    /* ---------------------- Filters ----------------------- */

    function hookUpFiltersToActions() {
        var filterType, selectedVal;

        $("div.filter select").each(function () {
            $(this).change(function () {
                filterType = $(this).attr("id");
                selectedVal = $(this).val();

                if (selectedVal && filterType) {
                    handleFilterChange(filterType, selectedVal);

                    // Make sure URL matches the filters. Also, inform the URL change listener not to trigger since this is an internal update.
                    if (!isURLAlreadyUpdated) {
                        areFiltersAlreadyUpdated = true;
                        updateURLFromPageState();
                        areFiltersAlreadyUpdated = false;
                    }

                    populateVisibleGamesLists();
                }
            });
        });
    }

    function handleFilterChange(filterType, filterValue) {
        resetStartIndexOfAllGamesListObjects();
        if (isWorsePerformanceEnabled) {
            $("html").hide();
        }

        switch (filterType) {
            case "SortFilter":
               updateFiltersEnabledOrDisabledStatus(filterValue);
                handleSortFilterChange(filterValue);
                $("#SortFilter").val(filterValue);
                break;
            case "TimeFilter":
                handleTimeFilterChange(filterValue);
                $("#TimeFilter").val(filterValue);
                break;
            case "GenreFilter":
                handleGenreFilterChange(filterValue);
                $("#GenreFilter").val(filterValue);
                break;
        }
        if (isWorsePerformanceEnabled) {
            setTimeout(function () { $("html").show(); }, worsePerformanceDelay);
        }
    }

    function resetStartIndexOfAllGamesListObjects() {
        for (var i = 0; i < gamesListArray.length; i++) {
            gamesListArray[i].resetStartIndex();
        }
    }

    function updateFiltersEnabledOrDisabledStatus(sortFilterValue) {
        // Hide time filters if certain sort filters selected
        if ($.inArray(sortFilterValue, SortFiltersForWhichTimeFiltersShouldBeHidden) !== -1) {
            $("#TimeFilter").prop("disabled", true);
            timeFilterStateChange("disabled");
        }
        else {
            $("#TimeFilter").prop("disabled", false);
            timeFilterStateChange("enabled");
        }

        // Hide genre filters if certain sort filters selected
        if ($.inArray(sortFilterValue, SortFiltersForWhichGenreFiltersShouldBeHidden) !== -1) {
            $("#GenreFilter").prop("disabled", true);
        }
        else {
            $("#GenreFilter").prop("disabled", false);
        }
    }

    function populateTimeFilterValues() {
        /* 
         *  Saving the time filter values from HTML 
         *  for toggling time state to and from "now".
         */
            
        $("#TimeFilter option").each(function (index, value) {
             timeFilterValues[index] = {
                 'label': value.label,
                 'value': value.value
             }
        });
        
    }

    function timeFilterStateChange(state) {
        // get the filter element
        var timeFilter = $("#TimeFilter");
        // clear the list
        timeFilter.find('option').remove();
        switch(state) {
            case "enabled":
                // show "Past Week", "Past Day", "All Time", etc. 
                for (var i = 1; i < timeFilterValues.length; i++) {
                    timeFilter.append('<option value="'+timeFilterValues[i].value+'">'+timeFilterValues[i].label+'</option>');
                }
                timeFilter.val(DefaultTimeFilter); // always select default time filter when enabling
                timeFilter.attr("data-default", DefaultTimeFilter); 
                break;
            case "disabled":
                // we are disabling the time filter so only show "Now" as the disabled option.
                timeFilter.append('<option value="' + timeFilterValues[0].value + '">' + timeFilterValues[0].label + '</option>');
                timeFilter.val(0); // prevent select list from having some other value
                timeFilter.attr("data-default", 0);
                break;
            default:
                break;
        }
    }

    function handleSortFilterChange(sortFilter) {
        hideAllGamesLists();
        unsquishAllGamesLists(); 
        showGamesListsCorrespondingToGivenSortFilter(sortFilter);
        squishConsecutiveGamesListsTogether();
        adjustUIBasedOnWindowDimensions();

        if ($("#GamesPageRightColumnSidebar").length > 0) {
            if (sortFilter === "default" && !$("#GamesPageRightColumnSidebar").hasClass(originalSidebarClass)) {
                $("#GamesPageRightColumnSidebar").removeClass();
                $("#GamesPageRightColumnSidebar").addClass(originalSidebarClass);
            }
        }
    }

    function handleTimeFilterChange(timeFilter) {
        updateTimeFilterOfAllGamesListObjects(timeFilter);
    }

    function handleGenreFilterChange(genreFilter) {
        updateGenreFilterOfAllGamesListObjects(genreFilter);
    }

    function updateGenreFilterOfAllGamesListObjects(newGenreId) {
        for (var i = 0; i < gamesListArray.length; i++) {
            gamesListArray[i].genreId = newGenreId;
        }
    }

    function updateTimeFilterOfAllGamesListObjects(newTimeFilter) {
        for (var i = 0; i < gamesListArray.length; i++) {
            gamesListArray[i].timeFilter = newTimeFilter;
        }
    }


    /* ---------------- URL handling ---------------- */

    function setFilterValuesFromURL() {
        $("#SortFilter").val($("#SortFilter").data("default"));
        $("#TimeFilter").val($("#TimeFilter").data("default"));
        $("#GenreFilter").val($("#GenreFilter").data("default"));
    }

    function updateURL() {
        var state = {};

        // must check to see if we are in search mode
        if (isGameSearchOnPage && searchQueryDefault !== "") {
            // we are in search mode, update the url
            Roblox.GamesDisplayShared.updateURLFromSearchState();
        } else {
            // no search or don't do searches on the page, proceed normally
            state = getURLStringAndObjectFromPageState();
            History.replaceState(state.urlStateObject, Roblox.GamesPageContainerBehavior.Resources.pageTitle, "?" + state.urlStateString);
        }
    }

    function updateURLFromPageState() {
        var state = getURLStringAndObjectFromPageState();
        History.pushState(state.urlStateObject, Roblox.GamesPageContainerBehavior.Resources.pageTitle, "?" + state.urlStateString);
    }

    function getURLStringAndObjectFromPageState() {
        var urlStateString = "";
        var urlStateObject = {};

        $("div.filter select").each(function () {
            filterType = $(this).attr("id");
            selectedVal = $(this).val();

            if (selectedVal && filterType && !$(this).prop("disabled")) {                
                urlStateString += (urlStateString === "" ? "" : "&") + filterType + "=" + selectedVal;
                urlStateObject[filterType] = selectedVal;
            }
        });

        // Make sure the URL changes when switching to BC see-all mode
        if (!areMultipleGamesListsDisplayed) {
            for (var i = 0; i < gamesListArray.length; i++) {
                if (gamesListArray[i].isShown && gamesListArray[i].minBCLevel === 1)
                    urlStateString += (urlStateString === "" ? "" : "&") + "BC=1";
                urlStateObject["BC"] = 1;
            }
        }
        return { "urlStateString": urlStateString, "urlStateObject": urlStateObject };
    }

    function handleURLChange(state) {

        // Respond only to external changes to the URL. Don't do anything if the URL is being changed by an internal handler.
        if (areFiltersAlreadyUpdated) {
            return;
        }

        // Are we in search mode vs filter mode
        if (state.hasOwnProperty("keyword")) {
            $("#searchbox").val(state.keyword);

            //TODO find a better way to trigger a history originated search

            if($("#SearchResultsContainer .search-query-text").text() !== state.keyword) {
               $("#GamesPageSearch .SearchIconButton").click(); 
            }
        } else {
            Roblox.GamesDisplayShared.toggleSearch("reset");
            // Make sure filters match the URL. Also, inform the filter change listener not to do a URL update.
            isURLAlreadyUpdated = true;
            for (var filterType in state) {
                handleFilterChange(filterType, state[filterType]);
            }
            isURLAlreadyUpdated = false;

            populateVisibleGamesLists();

        }

        // check whether we are in horizonal mode vs vertical mode in order to display in-game ads properly
        Roblox.AdsHelper.GamesPage.checkAdDisplayState();
        Roblox.AdsHelper.GamesPage.refreshOldAds();
        
    }


    /* ---------------- Ads ---------------- */

    function hookUpGutterAdsHiding() {                                  // Bind behavior for hiding gutters (in place of actual geotargeting)
        $(document).on("GuttersHidden", function () {
            if (!guttersEnabled) {
                return;
            }
            guttersEnabled = false;
            Roblox.AdsHelper.AdRefresher.registerAd("GamePageAdDiv1");
            Roblox.AdsHelper.AdRefresher.registerAd("GamePageAdDiv2");

            gamesPageLeftGutter = $('#LeftGutterAdContainer');
            gamesPageRightGutter = $('#RightGutterAdContainer');

            $('#GamesPageLeftColumn').css("margin", "0 345px 0 10px");  // Leave room for 300x250 ads on the right
            gamesPageLeftGutter.hide();
            gamesPageRightGutter.hide();
            $('#GutterAdStyles').remove();

            $('#GamesPageRightColumnSidebar').html("<iframe id='GamesRightColumn' src='/games/rightcolumn' scrolling='no' frameBorder='0' style='height:550px;width:330px;border:0px;overflow:hidden'></iframe>");
        });
    }

    function hookUpFilmStripAdHiding() {
        $(document).on("FilmStripHidden", function () {
            $('#GamePageAdDiv3').hide();
            $('#GamesPageRightColumnSidebar').html("<iframe id='GamesRightColumn' src='/games/rightcolumn' scrolling='no' frameBorder='0' style='height:550px;width:330px;border:0px;overflow:hidden'></iframe>");
        });
    }

    function adjustGutters() {
        if (!guttersEnabled) {
            return;
        }

        var screenWidth = Roblox.FixedUI.getWindowWidth();
        //HACK HACK HACK Isaiah is responsible for this! Urgent prod issue
        if (!Roblox.GamesPageContainerBehavior.IsUserLoggedIn) {
            if (screenWidth >= Nav2014ForceOpenThreshold) {
                    $('#LeftGutterAdContainer').css('margin-left', 11);
            }
            else {
                $('#LeftGutterAdContainer').css('margin-left', 0);
            }
            $('#LeftGutterAdContainer').css('z-index', 2);
            $('#RightGutterAdContainer').css('z-index', 5);
        }
        gamesPageLeftGutter = $("#LeftGutterAdContainer");
        gamesPageRightGutter = $("#RightGutterAdContainer");
        gutterAdWidth = gamesPageLeftGutter.width();
        var leftNav = $('.rbx-left-col');
        var navOpen = leftNav && leftNav.css("display") === "block";

        var windowWidth = $("body").width();
        if (Roblox.GamesPageContainerBehavior.IsUserLoggedIn && navOpen && screenWidth >= Nav2014ForceOpenThreshold) {
            windowWidth = windowWidth - Nav2014Width;
        }

        if (windowWidth <= GutterMinWidth) {
            $("#GamesPageLeftColumn").css("margin", "0");
            gamesPageLeftGutter.hide();
            gamesPageRightGutter.hide();
        }
        else {
            var gutterAdsVisibleWidth = getGutterAdVisibleWidth(windowWidth);
            var adjustAmount = gutterAdsVisibleWidth - gutterAdWidth;
            gamesPageLeftGutter.css("left", adjustAmount + "px");
            gamesPageRightGutter.css("right", adjustAmount + "px");
            gamesPageLeftGutter.show();
            gamesPageRightGutter.show();
            $("#GamesPageLeftColumn").css("margin", "0 " + (gutterAdsVisibleWidth + 10) + "px 0 " + (gutterAdsVisibleWidth + 10) + "px");
        }
        
    }

    function getGutterAdVisibleWidth(windowWidth) {
        if (windowWidth > (GutterMinWidth + 20 + (2 * gutterAdWidth))) {     // There's enough room to fully show both gutters
            return gutterAdWidth;
        }
        else {
            return Math.max((windowWidth - 20 - GutterMinWidth) / 2, 0);
        }
    }


    /* ---------------- Helpers ---------------- */

    function getGamesListObjectByIdSuffix(idSuffix) {
        for (var i = 0; i < gamesListArray.length; i++) {
            if (gamesListArray[i].divId === "GamesListContainer" + idSuffix) {
                return gamesListArray[i];
            }
        }

        return null;
    }

    function hideAllGamesLists() {
        for (var i = 0; i < gamesListArray.length; i++) {
            gamesListArray[i].hide();
        }
        numberOfShownGamesLists = 0;
    }

    function showGamesListsCorrespondingToGivenSortFilter(sortFilterToShow) {
        if (Roblox.GamesPageContainerBehavior.FilterValueToGamesListsIdSuffixMapping.hasOwnProperty(sortFilterToShow)) {
            areMultipleGamesListsDisplayed = true;
            for (var i = 0; i < Roblox.GamesPageContainerBehavior.FilterValueToGamesListsIdSuffixMapping[sortFilterToShow].length; i++) {
                showGamesListByIdSuffix(Roblox.GamesPageContainerBehavior.FilterValueToGamesListsIdSuffixMapping[sortFilterToShow][i], areMultipleGamesListsDisplayed);
            }
            $("#DivToHideOverflowFromLastGamesList, #Footer").removeClass("hidden");
        }
        else {
            areMultipleGamesListsDisplayed = false;
            showGamesListByIdSuffix(sortFilterToShow, areMultipleGamesListsDisplayed);
            $("#DivToHideOverflowFromLastGamesList, #Footer").addClass("hidden");
        }
    }

    function showGamesListByIdSuffix(sortFilter, isInMultiGamesListsDisplayMode) {
        var gamesListObject = getGamesListObjectByIdSuffix(sortFilter);

        if (gamesListObject) {
            gamesListObject.show(isInMultiGamesListsDisplayMode);
            numberOfShownGamesLists++;
        }
    }

    function setReferenceDimensions() {
        referenceWidth = $(window).width();
        referenceHeight = $(window).height();

        if ($("#GamesPageRightColumnSidebar").length > 0) {
            originalSidebarTop = $("#GamesPageRightColumnSidebar").position().top;
        }
    }

    function isInHorizontalScrollMode() {
        return areMultipleGamesListsDisplayed || false;
    }

    function areAdsInSearchResults() {
        return areAdsInGameSearchResults;
    }

    // for unit testing
    function setAreAdsInSearchResults(val) {
        areAdsInGameSearchResults = val ? true : false;
    }

    function getNumberOfColumns() {
        return numberOfColumns;
    }
    function getNumberOfRows() {
        return numberOfRowsForEachList;
    }
    
    function isTopRatedDefaultToWeeklyEnabled() {
        return defaultTopRatedToWeekly;
    }

    function isTopPaidDefaultToWeeklyEnabled() {
        return defaultTopPaidToWeekly;
    }
    
    function getDeviceTypeId() {
        return Roblox.GamesPageContainerBehavior.DeviceTypeId;
    }
    
    function handleGamesFilterResetClick(event) {
        // reset the search state

        Roblox.GamesDisplayShared.toggleSearch("reset");

        // Update the filters ...
        handleFilterChange("SortFilter", "default");

        // ... and the URL
        areFiltersAlreadyUpdated = true;
        updateURLFromPageState();
        areFiltersAlreadyUpdated = false;

        populateVisibleGamesLists();
    }

    function getSidebarFixedClassBasedOnWidth() {
        var width = $(window).width() || 0;

        if (width >= 980)
            return "move-with-window-stick-right";
        else
            return "move-with-window-min-left";
    }


    function handleGamesFilterChangerClick(event) {
        var gamesListContainerId = $(this).closest(".games-list-container").attr("id") || "";

        var idSuffix = gamesListContainerId.replace("GamesListContainer", "");

        var gamesListObject = getGamesListObjectByIdSuffix(idSuffix);

        if (gamesListObject != null) {
            // Update the filters ...
            handleFilterChange("SortFilter", idSuffix);

            // ... and the URL
            areFiltersAlreadyUpdated = true;
            updateURLFromPageState();
            areFiltersAlreadyUpdated = false;

            populateVisibleGamesLists();
        }

        Roblox.AdsHelper.GamesPage.checkAdDisplayState();

        return false;
    }


    /* ---------------- Scrolling ---------------- */

    $(window).scroll(function () {
        var newScrollTop = $(window).scrollTop();

        if (!areMultipleGamesListsDisplayed) {

            var sidebarClass = getSidebarFixedClassBasedOnWidth();
            var searchState = $("#GamesPageLeftColumn").data("searchstate");

            // Adjust right column position
            if ((newScrollTop + 60) > originalSidebarTop) {         // The 60px offset is to ensure the sidebar doesn't go up into the menus
                if (!$("#GamesPageRightColumnSidebar").hasClass(sidebarClass)) {
                    $("#GamesPageRightColumnSidebar").removeClass();
                    $("#GamesPageRightColumnSidebar").addClass(sidebarClass);
                }
            }
            else {
                if (!$("#GamesPageRightColumnSidebar").hasClass(originalSidebarClass)) {
                    $("#GamesPageRightColumnSidebar").removeClass();
                    $("#GamesPageRightColumnSidebar").addClass(originalSidebarClass);
                }
            }

            // Load more games and refresh ads
            if ((newScrollTop > prevScrollTop) && (!areMultipleGamesListsDisplayed || (isGameSearchOnPage && searchState == "on" ))) {
                if (shouldLoadMoreGames()) {
                   
                    // if in search mode, load more searches, otherwise more games.
                    if (searchState == "on") {
                        Roblox.GamesDisplayShared.moreSearchResults();
                    } else {
                        loadMoreGames();
                    }

                }
            }
        }

        prevScrollTop = newScrollTop;
    });



    var scheduledRequestId = null;
    function infiniteScrollDebouncer(functionToRun, debounceTime) {
        debounceTime = typeof debounceTime !== 'undefined' ? debounceTime : 1000;

        if (!isProcessingAdRefresh) {
            isProcessingAdRefresh = true;
            functionToRun();

            clearTimeout(scheduledRequestId);
            scheduledRequestId = setTimeout(function () {
                isProcessingAdRefresh = false;
            }, debounceTime);
        }
    }

    function shouldLoadMoreGames() {
        return $(window).scrollTop() >= ($(document).height() - $(window).height() - distanceFromBottomAtWhichToLoadMoreGames);
    }

    function loadMoreGames() {
        for (var i = 0; i < gamesListArray.length; i++) {
            if (gamesListArray[i].isShown) {
                gamesListArray[i].appendToGamesList(gamesListArray[i].numberOfGamesToFetch);
            }
        }
    }

    function getURLBasedOnSortFilter(sortFilter) {
        if ($.inArray(sortFilter, UserSpecificSortFilters) !== -1) {
            return "/games/moreresultsuncached";
        }
        else {
            return "/games/moreresultscached";
        }
    }

   

    function refreshAds() {
        Roblox.AdsHelper.GamesPage.refreshAds();
        var rightColumnIframe = $('#GamesRightColumn')[0];
        if (rightColumnIframe != null) {
            rightColumnIframe.contentWindow.Roblox.RightColumnBehavior.refreshAds();
        }
    }

    function getMultipleGamesListsDisplayedState() {
        return areMultipleGamesListsDisplayed;
    }

    function setMultipleGamesListsDisplayedState(newval) {
        areMultipleGamesListsDisplayed = newval;
    }

    function getAdSpan() {
        return adSpan ? adSpan: 0;
    }

    function setAdSpan(newAdSpan) {
        adSpan = newAdSpan;
    }

    function calcAdSpan(rowHeight, cols, targetHeight) {

        if (isInHorizontalScrollMode() || !Roblox.GamesPageContainerBehavior.areAdsInSearchResults()) {
            return 0;
        }
        var rowsNeeded = Math.ceil(targetHeight / rowHeight);
        var gamesNeeded = rowsNeeded * cols;
        return gamesNeeded;
    }

    function setAdSpanFromHeight(height) {
        setAdSpan(calcAdSpan(RowHeightIncludingPadding, getNumberOfColumns() - 1, height));
    }

    function getAdAlignment() {
        return adAlignment ? adAlignment : 0;
    }

    function setAdAlignment(newAdAlignment) {
        adAlignment = newAdAlignment;
    }

    function calcAdAlignment() {
        // get last ad
        var lastAd = $('.overflow-visible .in-game-search-ad').last();
        var retVal = 0;
        if (lastAd.length > 0) {
            if(lastAd.hasClass('ad-order-even')) {
                retVal = 1;
            }
        }
        return retVal;
    }

    /* ---------------------- Public ----------------------- */

    return {
        GamesListHeaderHeight: GamesListHeaderHeight,
        RowHeightIncludingPadding: RowHeightIncludingPadding,
        ColumnWidthIncludingPadding: ColumnWidthIncludingPadding,
        MaxNumberOfGamesToFetchInHScrollMode: MaxNumberOfGamesToFetchInHScrollMode,
        NumberOfGamesToFetchInVScrollMode: NumberOfGamesToFetchInVScrollMode,
        numGamesToFetchOnSearch: numGamesToFetchOnSearch,
        initialAdHeight: initialAdHeight,
        subsequentAdHeight: subsequentAdHeight,
        getNumberOfColumns: getNumberOfColumns,
        getNumberOfRows: getNumberOfRows,
        handleGamesFilterChangerClick: handleGamesFilterChangerClick,
        handleGamesFilterResetClick: handleGamesFilterResetClick,
        isInHorizontalScrollMode: isInHorizontalScrollMode,
        getURLBasedOnSortFilter: getURLBasedOnSortFilter,
        isTopRatedDefaultToWeeklyEnabled: isTopRatedDefaultToWeeklyEnabled,
        isTopPaidDefaultToWeeklyEnabled: isTopPaidDefaultToWeeklyEnabled,
        getDeviceTypeId: getDeviceTypeId,
        toggleCarouselButtons: toggleCarouselButtons,
        doesNextButtonFitContainer: doesNextButtonFitContainer,
        getMultipleGamesListsDisplayedState: getMultipleGamesListsDisplayedState,
        setMultipleGamesListsDisplayedState: setMultipleGamesListsDisplayedState,
        refreshAds: refreshAds,
        loadMoreGames: loadMoreGames,
        areAdsInSearchResults: areAdsInSearchResults,
        getAdSpan: getAdSpan,
        setAdSpan: setAdSpan,
        setAdSpanFromHeight: setAdSpanFromHeight,
        calcAdSpan: calcAdSpan,
        calcAdAlignment: calcAdAlignment,
        calculateNumberOfColumns: calculateNumberOfColumns,
        getGutterAdVisibleWidth: getGutterAdVisibleWidth,
        setAreAdsInSearchResults: setAreAdsInSearchResults

    };
} ();


}
/*
     FILE ARCHIVED ON 00:15:07 Jan 08, 2016 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:17 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 1.014
  exclusion.robots: 0.144
  exclusion.robots.policy: 0.13
  cdx.remote: 0.091
  esindex: 0.019
  LoadShardBlock: 167.624 (3)
  PetaboxLoader3.datanode: 201.527 (4)
  load_resource: 237.296
  PetaboxLoader3.resolve: 148.869
*/