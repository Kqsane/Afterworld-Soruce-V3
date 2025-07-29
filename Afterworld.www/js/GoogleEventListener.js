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

GoogleListener = new RBXBaseEventListener();
GoogleListener.handleEvent = function (event, data) {
    function translateOsString(str) {
        str = str.toLowerCase();
        if (str == "win32")
            str = "Windows";
        else if (str == "osx")
            str = "Mac";
        return str;
    }

    var gEvent, gData, dataMap;

    switch (event.type) {
        case 'rbx_evt_initial_install_begin':
            data['os'] = translateOsString(data['os']);
            data['category'] = 'Bootstrapper Install Begin';
            dataMap = { os: 'action' };
            break;
        case 'rbx_evt_ftp':
            data['os'] = translateOsString(data['os']);
            data['category'] = 'Install Success';
            dataMap = { os: 'action' };
            break;
        case 'rbx_evt_initial_install_success':
            data['os'] = translateOsString(data['os']);
            data['category'] = 'Bootstrapper Install Success';
            dataMap = { os: 'action' };
            break;
        case 'rbx_evt_fmp':
            data['os'] = translateOsString(data['os']);
            data['category'] = 'Five Minute Play';
            dataMap = { os: 'action' };
            break;
        case 'rbx_evt_abtest':
            dataMap = { experiment: 'category', variation: 'action', version: 'opt_label' };
            break;
        case 'rbx_evt_card_redemption':
            data['category'] = "CardRedemption";
            dataMap = { merchant: 'action', cardValue: 'opt_label' };
            break;
        default:
            console.log('GoogleListener - Event registered without handling instructions: ' + event.type);
            return false;
    }

    dataMap['category'] = 'category';

    gData = this.distillData(data, dataMap);
    this.fireEvent(gData);
    return true;
}

GoogleListener.distillData = function (data, mapping) {
    var distilled = {};
    for (dataKey in mapping) {
        if (typeof (data[dataKey]) != typeof (undefined))
            distilled[mapping[dataKey]] = data[dataKey];
    }
    var eventParams = [distilled['category'], distilled['action']];
    if (distilled['opt_label'] != null) {
        eventParams = eventParams.concat(distilled['opt_label']);
    }
    if (distilled['opt_value'] != null) {
        eventParams = eventParams.concat(distilled['opt_value']);
    }

    return eventParams;
}
GoogleListener.fireEvent = function (processedEvent) {
    if (typeof (_gaq) != typeof (undefined)) {
        var eventsArray = ["_trackEvent"];
        var eventsArrayB = ["b._trackEvent"];
        _gaq.push(eventsArray.concat(processedEvent));
        _gaq.push(eventsArrayB.concat(processedEvent));
    }
}
GoogleListener.events = [
    'rbx_evt_initial_install_begin',
    'rbx_evt_ftp',
    'rbx_evt_initial_install_success',
    'rbx_evt_fmp',
    'rbx_evt_abtest',
    'rbx_evt_card_redemption'
];

}
/*
     FILE ARCHIVED ON 01:58:11 Sep 08, 2014 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:09 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 1.191
  exclusion.robots: 0.157
  exclusion.robots.policy: 0.138
  cdx.remote: 0.105
  esindex: 0.018
  LoadShardBlock: 73.576 (3)
  PetaboxLoader3.datanode: 58.237 (4)
  load_resource: 59.93
  PetaboxLoader3.resolve: 56.761
*/