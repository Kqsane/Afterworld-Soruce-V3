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

Roblox.BootstrapWidgets = function () {
    // trigger and customize bootstrap js components;
    function SetupTabs() {
        // tabs
        $('#horizontal-tabs a').on("click", function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
        $('#horizontal-tabs a').on("touchstart", function (e) {
            // Angular's ui-sref only works with click event so we need to trigger the click event
            e.preventDefault();
            $(this).trigger("click");
        });
        $('#vertical-tabs a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    }

    function SetupDropdown() {
        // dropdown menu
        $('[data-toggle="dropdown-menu"] li').click(function (e) {
            var target = $(e.currentTarget);
            target.closest('.input-group-btn')
                    .find('[data-bind="label"]')
                    .text(target.text())
                    .end()
                    .toggleClass("open");
            return false;
        });
    }

    function SetupAccordion() {
        // Accordion
        // This event fires immediately when the show instance method is called.
        $('[data-toggle="collapsible-element"]').on('show.bs.collapse', function (e) {
            $(e.target)
                .prev('.panel-heading')
                .find(".icon-down-16x16")
                .removeClass('icon-down-16x16').addClass('icon-up-16x16');
        });
        // This event is fired immediately when the hide method has been called.
        $('[data-toggle="collapsible-element"]').on('hide.bs.collapse', function (e) {
            $(e.target)
                .prev('.panel-heading')
                .find(".icon-up-16x16")
                .removeClass('icon-up-16x16').addClass('icon-down-16x16');
        });
    }

    function SetupTooltip() {
        // tooltips
        if (!('ontouchstart' in window)) {
            $('[data-toggle="tooltip"]').tooltip({
                placement: 'bottom'
            });
        } else {
            $('[data-toggle-mobile="true"]').tooltip({
                placement: 'bottom',
                trigger: 'manual'
            }).unbind().on('touchstart', function () {
                $(this).tooltip('toggle');
            });
        }
    }

    function UpdateTooltip(element, newTitle) {
        $(element).attr('title', newTitle).tooltip('fixTitle');
    }

    function CloseTooltip() {
        $("body").on('click touchstart', function (e) {
            $('[data-toggle="tooltip"]').each(function () {
                //the 'is' for links that trigger tooltips
                //the 'has' for icons within a link that triggers a tooltip
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0) {
                    var canBeHidden = (e.type === "click") ? true : ($('.tooltip').has(e.target).length === 0);
                    if (canBeHidden) {
                        $(this).tooltip('hide');
                    }
                }
            });
        });
    }

    function SetupPopover(placement, viewport) {
        if (!placement) {
            placement = 'bottom';
        }
        if (!viewport) {
            viewport = { selector: 'body', padding: 4 };
        }
        // popover with HTML prototypes
        $("[data-toggle='popover']").popover({
            trigger: 'manual',
            html: true,
            placement: placement,
            viewport: viewport,
            content: function () {
                var selector = $(this).attr('data-bind');
                return $('[data-toggle="' + selector + '"]').html();
            }
        }).unbind().on('click', function () {
            $(this).popover('toggle');
        });

    }

    function ClosePopover() {
        $("body").on('click touchstart', function (e) {
            $('[data-toggle="popover"]').each(function () {
                //the 'is' for links that trigger popups
                //the 'has' for icons within a link that triggers a popup
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0) {
                    var canBeHidden = (e.type === "click") ? true : ($('.popover').has(e.target).length === 0);
                    if (canBeHidden) {
                        $(this).popover('hide');
                    }
                }
            });
        });
    }
    // configure mCustomScrollbar
    function SetupScrollbar() {
        //scroll bar
        $('[data-toggle="scrollbar"]').mCustomScrollbar({
            autoHideScrollbar: false,
            autoExpandScrollbar: false,
            scrollInertia: 0,
            mouseWheel: {
                preventDefault: true
            }
        });
    }

    // configure twbsPagination
    function SetupPagination() {
        // pagination
        var pagination = $('[data-toggle="pagination"]');
        var pager = $('[data-toggle="pager"]');
        if (pagination.twbsPagination || pager.twbsPagination) {
            pagination.twbsPagination({
                totalPages: 35,
                visiblePages: 7,
                first: 1,
                last: 35,
                prev: '<span class="icon-left"></span>',
                next: '<span class="icon-right"></span>',
            });

            pager.twbsPagination({
                isPager: true,
                totalPages: 35,
                visiblePages: 7,
                first: '<span class="icon-first-page"></span>',
                last: '<span class="icon-last-page"></span>',
                prev: '<span class="icon-left"></span>',
                next: '<span class="icon-right"></span>',
            });
        }
    }

    function ToggleSystemMessage(alertElm, timeoutSlideDown, timeoutSlideUp, alertTextReplacement) {
        if (typeof alertElm !== "undefined") {
            var clone, detached;
            if (alertTextReplacement) {
                clone = alertElm.clone();
                clone.html(alertTextReplacement);
                alertElm.after(clone);
                detached = alertElm.detach();
            }
            timeoutSlideDown = typeof timeoutSlideDown === "undefined" ? 200 : timeoutSlideDown;
            timeoutSlideUp = typeof timeoutSlideUp === "undefined" ? 3000 : timeoutSlideUp;
            setTimeout(function () {
                if (clone) {
                    clone.addClass("on");
                }
                else {
                    alertElm.addClass("on");
                }
            }, timeoutSlideDown);

            setTimeout(function () {
                if (clone) {
                    clone.removeClass("on");
                }
                else {
                    alertElm.removeClass("on");
                }
                //this needs to happen after above.
                if (clone && detached) {
                    clone.after(detached);
                    clone.remove();
                }
            }, timeoutSlideUp);
        }

    }
    function SetupSystemFeedback() {
        $("#toggle-alert-loading").click(function () {
            ToggleSystemMessage($(".sg-alert-section .alert-loading"), 100, 1000);
        });
        $("#toggle-alert-success").click(function () {
            ToggleSystemMessage($(".sg-alert-section .alert-success"), 100, 1000);
        });
        $("#toggle-alert-warning").click(function () {
            var alertElm = $(".sg-alert-section .alert-warning");
            setTimeout(function () {
                alertElm.addClass("on");
            }, 100);
            var close = $(".alert-system-feedback #close");
            close.click(function () {
                alertElm.removeClass("on");
            });
        });
    }

    function Placeholder() {
        $('input[placeholder]').focus(function () {
            var input = $(this);
            if (input.val() == input.attr("placeholder")) {
                input.val('');
                input.removeClass("rbx-placeholder");
            }
        }).blur(function () {
            var input = $(this);
            if (input.val() == '' || input.val() == input.attr("placeholder")) {
                input.addClass("rbx-placeholder");
                input.val(input.attr("placeholder"));
            }
        });
    }

    var paraOverflowSelector = "para-overflow";
    var paraOverflowElement = $("." + paraOverflowSelector);
    function IsTruncated() {
        paraOverflowElement.each(function () {
            var elem = $(this);
            var clone = $(this).clone().hide().height("auto");
            clone.width(elem.width());

            $("body").append(clone);
            if (clone.height() <= elem.height()) {
                elem.removeClass(paraOverflowSelector);
                $(this).find(".toggle-para").hide();
            }
            clone.remove();
        });
    }
    function TruncateParagraph(lineHeight, numberOfLines) {
        var paraOverflowToggleSelector = "para-overflow-toggle";
        var paraOverflowToggleElement = $("." + paraOverflowToggleSelector);
        var paraHeightSelector = "para-height";
        var paraOverFlowLoading = "para-overflow-page-loading";
        lineHeight = !lineHeight ? 24 : lineHeight;
        numberOfLines = !numberOfLines ? 5 : numberOfLines;
        $(".toggle-para").show();

        paraOverflowToggleElement.each(function () {
            var elem = $(this);
            var clone = $(this).clone().hide().height("auto");
            clone.width(elem.width());

            $("body").append(clone);
            var maxHeight = lineHeight * numberOfLines;
            if (clone.height() <= maxHeight || clone.height() <= elem.height()) {
                elem.removeClass(paraOverflowToggleSelector)
                    .removeClass(paraHeightSelector);
                elem.find(".toggle-para").last().hide();
            }
            elem.removeClass(paraOverFlowLoading);
            clone.remove();
        });
    }

    function ToggleParagraph(moreTitle, lessTitle) {
        var paraOverflowToggleOffSelector = "para-overflow-toggle-off";
        var paraHeightSelector = "para-height";
        if (!moreTitle) {
            moreTitle = "Read More";
        }
        if (!lessTitle) {
            lessTitle = "Show Less";
        }
        $(".toggle-para").bind("click touchstart", function () {
            var paraOverflowToggleElement = $(".para-overflow-toggle");
            if ($(this).text() === moreTitle) {
                paraOverflowToggleElement.removeClass(paraHeightSelector)
                                        .addClass(paraOverflowToggleOffSelector);
                $(this).text(lessTitle);
            } else {
                paraOverflowToggleElement.removeClass(paraOverflowToggleOffSelector)
                                        .addClass(paraHeightSelector);
                $(this).text(moreTitle);
            }
        });
    }

    function SetupCarousel(carouselId) {
        carouselId = !carouselId ? "#carousel" : carouselId;
        $(carouselId).carousel({
            interval: 6000,
            pause: "hover"
        });
    }

    function SetupToggleButton() {
        $(".btn-toggle").bind("click", function () {
            if ($(this).hasClass("disabled")) {
                return false;
            }
            $(this).toggleClass("on");
            $(this).trigger("toggleBtnClick", {
                id: $(this).attr("id"),
                toggleOn: $(this).hasClass("on")
            });
        });
    }

    return {
        SetupTabs: SetupTabs,
        SetupDropdown: SetupDropdown,
        SetupAccordion: SetupAccordion,
        SetupTooltip: SetupTooltip,
        UpdateTooltip: UpdateTooltip,
        CloseTooltip: CloseTooltip,
        SetupPopover: SetupPopover,
        ClosePopover: ClosePopover,
        SetupScrollbar: SetupScrollbar,
        SetupPagination: SetupPagination,
        Placeholder: Placeholder,
        IsTruncated: IsTruncated,
        TruncateParagraph: TruncateParagraph,
        ToggleParagraph: ToggleParagraph,
        SetupCarousel: SetupCarousel,
        SetupToggleButton: SetupToggleButton,
        SetupSystemFeedback: SetupSystemFeedback,
        ToggleSystemMessage: ToggleSystemMessage
    }
}();

$(function () {
    Roblox.BootstrapWidgets.SetupTabs();
    Roblox.BootstrapWidgets.SetupDropdown();
    Roblox.BootstrapWidgets.SetupAccordion();
    Roblox.BootstrapWidgets.SetupTooltip();
    Roblox.BootstrapWidgets.CloseTooltip();
    Roblox.BootstrapWidgets.SetupPopover();
    Roblox.BootstrapWidgets.ClosePopover();
    Roblox.BootstrapWidgets.SetupScrollbar();
    Roblox.BootstrapWidgets.SetupPagination();

    if (typeof Modernizr != "undefined" && !Modernizr.input.placeholder) {
        Roblox.BootstrapWidgets.Placeholder();
    }

    Roblox.BootstrapWidgets.IsTruncated();
    Roblox.BootstrapWidgets.TruncateParagraph();
    Roblox.BootstrapWidgets.ToggleParagraph();
    Roblox.BootstrapWidgets.SetupCarousel();
    Roblox.BootstrapWidgets.SetupToggleButton();
    Roblox.BootstrapWidgets.SetupSystemFeedback();
    Roblox.BootstrapWidgets.ToggleSystemMessage();
});


}
/*
     FILE ARCHIVED ON 00:37:01 Jan 01, 2017 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:05 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 0.92
  exclusion.robots: 0.105
  exclusion.robots.policy: 0.092
  cdx.remote: 0.083
  esindex: 0.015
  LoadShardBlock: 603.363 (3)
  PetaboxLoader3.datanode: 405.193 (4)
  load_resource: 414.454
  PetaboxLoader3.resolve: 178.241
*/