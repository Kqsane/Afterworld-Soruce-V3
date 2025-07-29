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

MadStatus = {
    // Usage:
    //MadStatus.init($('.MadStatusField'),$('.MadStatusBackBuffer'), 2000, [1000]);
    //MadStatus.start();

    running: false,

    init: function (field, backBuffer, updateInterval, fadeInterval) {
        if (MadStatus.running || MadStatus.timeout || MadStatus.resumeTimeout) {
            MadStatus.stop();
        }
        MadStatus.updateInterval = updateInterval ? updateInterval : 2000;
        MadStatus.fadeInterval = fadeInterval ? fadeInterval : 1000;
        MadStatus.timeout = null;
        MadStatus.resumeTimeout = null;
        MadStatus.running = true;
        MadStatus.field = field;
        MadStatus.backBuffer = backBuffer;

        MadStatus.field.show();
        MadStatus.backBuffer.hide();
    },

    newLib: function () {
        return this.participle[Math.floor(Math.random() * (this.participle.length))] + " " +
			   this.modifier[Math.floor(Math.random() * (this.modifier.length))] + " " +
			   this.subject[Math.floor(Math.random() * (this.subject.length))] + "...";
    },

    start: function () {
        if (MadStatus.timeout == null) {
            MadStatus.timeout = setInterval("MadStatus.update()", MadStatus.updateInterval);                        
            MadStatus.running = true;
        }
    },

    stop: function (msg) {        
        clearInterval(MadStatus.timeout);        
        MadStatus.timeout = null;

        clearTimeout(MadStatus.resumeTimeout);
        MadStatus.resumeTimeout = null;

        if (typeof (msg) != typeof (undefined)) {
            MadStatus.field[0].innerHTML = msg;
        }
        else {
            MadStatus.field[0].innerHTML = "";
        }
        MadStatus.running = false;        
    },

    manualUpdate: function (staticMsg, resumeAfterUpdate, animate) {

        if (MadStatus.timeout || MadStatus.resumeTimeout)
            MadStatus.stop();

        this.update(staticMsg, animate);        

        if (resumeAfterUpdate) {            
            MadStatus.resumeTimeout = setTimeout("MadStatus.start()", 1000);
        } 
    },

    update: function (staticMsg, animate) {
        if (typeof (staticMsg) != typeof (undefined))
            MadStatus.backBuffer[0].innerHTML = staticMsg;
        else
            MadStatus.backBuffer[0].innerHTML = this.newLib();

        if (typeof (animate) != typeof (undefined) && animate == false)
            return;

        this.field.hide();
        this.backBuffer.fadeIn(this.fadeInterval + 2, function () {
            MadStatus.field[0].innerHTML = MadStatus.backBuffer[0].innerHTML;
            MadStatus.field.show();
            MadStatus.backBuffer.hide();
        });
    }
};

// execute after the document has loaded, when resources are available
$(function () {
    if (!MadStatus.Resources) {
        return; // hacky work-around for sign-up page where placelauncher is not included.
    }

    MadStatus.participle = [
        MadStatus.Resources.accelerating,
        MadStatus.Resources.aggregating,
        MadStatus.Resources.allocating,
        MadStatus.Resources.acquiring,
        MadStatus.Resources.automating,
        MadStatus.Resources.backtracing,
        MadStatus.Resources.bloxxing,
        MadStatus.Resources.bootstrapping,
        MadStatus.Resources.calibrating,
        MadStatus.Resources.correlating,
        MadStatus.Resources.denoobing,
        MadStatus.Resources.deionizing,
        MadStatus.Resources.deriving,
        MadStatus.Resources.energizing,
        MadStatus.Resources.filtering,
        MadStatus.Resources.generating,
        MadStatus.Resources.indexing,
        MadStatus.Resources.loading,
        MadStatus.Resources.noobing,
        MadStatus.Resources.optimizing,
        MadStatus.Resources.oxidizing,
        MadStatus.Resources.queueing,
        MadStatus.Resources.parsing,
        MadStatus.Resources.processing,
        MadStatus.Resources.rasterizing,
        MadStatus.Resources.reading,
        MadStatus.Resources.registering,
        MadStatus.Resources.rerouting,
        MadStatus.Resources.resolving,
        MadStatus.Resources.sampling,
        MadStatus.Resources.updating,
        MadStatus.Resources.writing
    ];
    MadStatus.modifier = [
        MadStatus.Resources.blox,
        MadStatus.Resources.countzero,
        MadStatus.Resources.cylon,
        MadStatus.Resources.data,
        MadStatus.Resources.ectoplasm,
        MadStatus.Resources.encryption,
        MadStatus.Resources.event,
        MadStatus.Resources.farnsworth,
        MadStatus.Resources.bebop,
        MadStatus.Resources.fluxcapacitor,
        MadStatus.Resources.fusion,
        MadStatus.Resources.game,
        MadStatus.Resources.gibson,
        MadStatus.Resources.host,
        MadStatus.Resources.mainframe,
        MadStatus.Resources.metaverse,
        MadStatus.Resources.nerfherder,
        MadStatus.Resources.neutron,
        MadStatus.Resources.noob,
        MadStatus.Resources.photon,
        MadStatus.Resources.profile,
        MadStatus.Resources.script,
        MadStatus.Resources.skynet,
        MadStatus.Resources.tardis,
        MadStatus.Resources.virtual
    ];
    MadStatus.subject = [
        MadStatus.Resources.analogs,
        MadStatus.Resources.blocks,
        MadStatus.Resources.cannon,
        MadStatus.Resources.channels,
        MadStatus.Resources.core,
        MadStatus.Resources.database,
        MadStatus.Resources.dimensions,
        MadStatus.Resources.directives,
        MadStatus.Resources.engine,
        MadStatus.Resources.files,
        MadStatus.Resources.gear,
        MadStatus.Resources.index,
        MadStatus.Resources.layer,
        MadStatus.Resources.matrix,
        MadStatus.Resources.paradox,
        MadStatus.Resources.parameters,
        MadStatus.Resources.parsecs,
        MadStatus.Resources.pipeline,
        MadStatus.Resources.players,
        MadStatus.Resources.ports,
        MadStatus.Resources.protocols,
        MadStatus.Resources.reactors,
        MadStatus.Resources.sphere,
        MadStatus.Resources.spooler,
        MadStatus.Resources.stream,
        MadStatus.Resources.switches,
        MadStatus.Resources.table,
        MadStatus.Resources.targets,
        MadStatus.Resources.throttle,
        MadStatus.Resources.tokens,
        MadStatus.Resources.torpedoes,
        MadStatus.Resources.tubes
    ];
});

}
/*
     FILE ARCHIVED ON 01:58:08 Sep 08, 2014 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:17 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 0.779
  exclusion.robots: 0.087
  exclusion.robots.policy: 0.075
  cdx.remote: 0.073
  esindex: 0.012
  LoadShardBlock: 316.553 (3)
  PetaboxLoader3.datanode: 278.753 (4)
  load_resource: 39.985
*/