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

EventTracker = new function () {
    var self = this;
    self.logMetrics = false;
    self.transmitMetrics = true;

    var eventStore = new function () {
        var events = {};
        this.get = function (name) {
            return events[name];
        };
        this.set = function (name, time) {
            events[name] = time;
        };
        this.remove = function (name) {
            delete events[name];
        };
    };

    var timestamp = function () {
        return new Date().valueOf();
    };

    var endEachEvent = function (eventNames, reason) {
        var now = timestamp();
        $.each(eventNames, function (idx, name) {
            end(name, reason, now);
        });
    };

    var end = function (name, reason, time) {
        var evt = eventStore.get(name);
        if (evt) {
            eventStore.remove(name);
            var duration = time - evt;
            if (self.logMetrics) {
                console.log('[event]', name, reason, duration);
            }
            if (self.transmitMetrics) {
                var statName = name + "_" + reason;
                $.ajax({
                    type: "POST",
                    timeout: 50000,
                    url: "/game/report-stats?name=" + statName + "&value=" + duration
                });
            }
        } else {
            if (self.logMetrics) {
                console.log('[event]', 'ERROR: event not started -', name, reason);
            }
        }
    };

    self.start = function () {
        var now = timestamp();
        $.each(arguments, function (idx, name) {
            eventStore.set(name, now);
        });
    };

    self.endSuccess = function () {
        endEachEvent(arguments, 'Success');
    };

    self.endCancel = function () {
        endEachEvent(arguments, 'Cancel');
    };

    self.endFailure = function () {
        endEachEvent(arguments, 'Failure');
    };
}

}
/*
     FILE ARCHIVED ON 01:58:03 Sep 08, 2014 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:21 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 0.832
  exclusion.robots: 0.077
  exclusion.robots.policy: 0.066
  cdx.remote: 0.062
  esindex: 0.01
  LoadShardBlock: 530.883 (3)
  PetaboxLoader3.datanode: 771.432 (4)
  load_resource: 535.13
*/