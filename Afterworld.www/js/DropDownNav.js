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

;(function () {
    //by convention, all dropdownNav styling should use the class 'active'
    //apply attr 'data-drop-down-nav-container' to the container
    //apply attr 'drop-down-nav-button' to the button
    //this module will run it's self; also note it works for click and hover
    var dropDownNavContainers;
    var dropDownNavButtons;
      
    $(function() {
        dropDownNavContainers = $('[data-drop-down-nav-container]');
        dropDownNavButtons = $('[drop-down-nav-button]');
        
        $('[drop-down-nav-button]').bind('click', openDropDownNavClick);
        $('[drop-down-nav-button]').bind('mouseenter', openDropDownNavHover);
    });

    function showDropDownNav(event) {
        var clicked = $(event.target);
        if (!clicked.attr('drop-down-nav-button')) {
            clicked = clicked.parents('[drop-down-nav-button]');
        }
        clicked.addClass('active');
        var navName = clicked.attr('drop-down-nav-button');
        var navcontainer = dropDownNavContainers.filter('[data-drop-down-nav-container="' + navName + '"]');
        navcontainer.show();
        dropDownNavContainers.not(navcontainer).hide();
        dropDownNavButtons.not(clicked).removeClass('active');
        event.stopPropagation();
        clicked.trigger('showDropDown');
        
    }
    function openDropDownNavHover(event) {
        $('[drop-down-nav-button]').unbind('click', openDropDownNavClick);
        showDropDownNav(event);
        $('[drop-down-nav-button]').bind('mouseleave', closeDropDownNavHover);
    }
    function closeDropDownNavHover() {
        hideDropDownNavs();
        $('[drop-down-nav-button]').unbind('mouseleave', closeDropDownNavHover);
    }
    
    function openDropDownNavClick(event) {
        $('[drop-down-nav-button]').unbind('mouseenter', openDropDownNavHover);
        showDropDownNav(event);
        $(document).bind('click', function(event) {
            hideDropDownNavs();
        });
        $('[drop-down-nav-button]').bind('click', closeDropDownNavClick);
    }
    function closeDropDownNavClick() {
        $(document).unbind('click', function(event) {
            hideDropDownNavs();
        });
        hideDropDownNavs();
        $('[drop-down-nav-button]').bind('click', showDropDownNav);
    }

    function hideDropDownNavs() {
        dropDownNavContainers.hide();
        dropDownNavButtons.removeClass('active');
    }
})();

}
/*
     FILE ARCHIVED ON 01:58:06 Sep 08, 2014 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:19 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 0.703
  exclusion.robots: 0.067
  exclusion.robots.policy: 0.057
  cdx.remote: 0.057
  esindex: 0.012
  LoadShardBlock: 909.352 (3)
  PetaboxLoader3.datanode: 754.096 (4)
  PetaboxLoader3.resolve: 1214.216 (2)
  load_resource: 1134.502
*/