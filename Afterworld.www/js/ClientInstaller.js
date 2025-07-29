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

if (typeof Roblox === "undefined") {
    Roblox = {};
}
Roblox.Client = {};

Roblox.Client._installHost = null;
Roblox.Client._installSuccess = null;
Roblox.Client._CLSID = null;
Roblox.Client._continuation = null;
Roblox.Client._skip = null;
Roblox.Client._isIDE = null;
Roblox.Client._isRobloxBrowser = null;
Roblox.Client._isPlaceLaunch = false;
Roblox.Client._silentModeEnabled = false;
Roblox.Client._bringAppToFrontEnabled = false;
Roblox.Client._numLocks = 0;
Roblox.Client._logTiming = false;
Roblox.Client._logStartTime = null;
Roblox.Client._logEndTime = null;
Roblox.Client._hiddenModeEnabled = false;
Roblox.Client._runInstallABTest = function () { };  // will be set if there is an AB test in play
Roblox.Client._currentPluginVersion = "";
Roblox.Client._whyIsRobloxLauncherNotCreated = null;

Roblox.Client.LauncherNotCreatedReasons = {
    pluginNotInstalled: "pluginNotInstalled",
    pluginNotAllowed: "pluginNotAllowed",
    wrongInstallHost: "wrongInstallHost",
    wrongInstallHostAndPluginWasNotAllowed: "wrongInstallHostAndPluginWasNotAllowed",
    versionMismatch: "versionMismatch",
    unknownError: "unknownError"
};

Roblox.Client.ReleaseLauncher = function (o, removeLock, overrideLocks) {
    if (removeLock)
        Roblox.Client._numLocks--;
    if (overrideLocks || Roblox.Client._numLocks <= 0) {
        if (o != null) {
            document.getElementById('pluginObjDiv').innerHTML = '';
            o = null;
        }
        Roblox.Client._numLocks = 0;
    }
    if (Roblox.Client._logTiming) {
        Roblox.Client._logEndTime = new Date();
        var ms = Roblox.Client._logEndTime.getTime() - Roblox.Client._logStartTime.getTime();
        if (console && console.log) {
            console.log("Roblox.Client: " + ms + "ms from Create to Release.");
        }
    }
};

Roblox.Client.IsUpToDateVersion = function (o) {
    // If version checking is not live, current version will be the empty string
    // Example version format: "1, 2, 8, 24"
    var serverVersion = Roblox.Client._currentPluginVersion;
    if (serverVersion == null || serverVersion == "") {
        return true;
    }

    try {
        var installedVersion = o.Get_Version();
        if (installedVersion == "-1" || installedVersion == "undefined") {
            return true; // plugin failed to execute Get_Version
        }
    } catch (ex) {
        return false;
    }

    if (serverVersion === installedVersion) {
        return true;
    }

    var installedVersionValues = $.map(installedVersion.split(","), function (val) { return parseInt(val, 10); });
    var serverVersionValues = $.map(serverVersion.split(","), function (val) { return parseInt(val, 10); });
    var versionStringLength = Math.min(serverVersionValues.length, installedVersionValues.length);

    for (var i = 0; i < versionStringLength; i++) {
        if (serverVersionValues[i] > installedVersionValues[i]) {
            return false;
        } else if (serverVersionValues[i] < installedVersionValues[i]) {
            return true;
        }
    }

    if (installedVersionValues.length !== serverVersionValues.length) {
        return false;
    }

    return true;
};

Roblox.Client.GetInstallHost = function (o) {
    if (Roblox.Client.IsIE())
    {
        return o.InstallHost;
    }
    else 
    {
        // GROSS DISGUSTING HACK:  Firefox plugin for some reason is tacking on an extra character to the end of the install host.
        var val = o.Get_InstallHost();
        if (val.match(/aftwld.xyz$/))
            return val;
        else
            return val.substring(0, val.length - 1);
    }
};

Roblox.Client.IsIE = function () {
    try {
        return !!new ActiveXObject("htmlfile");
    } catch (e) {
        return false;
    }
};

Roblox.Client.browserRequiresPluginActivation = function () {
    return /firefox/i.test(navigator.userAgent) || window.chrome;
};

Roblox.Client.CreateLauncher = function (addLock) {
    if (Roblox.Client._logTiming) {
        Roblox.Client._logStartTime = new Date();
    }
    if (addLock)
        Roblox.Client._numLocks++;

    if (Roblox.Client._installHost == null || Roblox.Client._CLSID == null)  // Need to init these properties
    {
        if (typeof initClientProps == 'function') {
            initClientProps();
        }
    }

    var pluginObj = document.getElementById('robloxpluginobj');
    var pluginDiv = $('#pluginObjDiv');

    // Check to see if it's already installed
    // If it isn't installed, add it
    if (!pluginObj) {
        Roblox.Client._hiddenModeEnabled = false;
        var pluginString;
        if (Roblox.Client.IsIE()) {
            // browser supports ActiveX
            // Create object element with
            // download URL for IE OCX

            pluginString = "<object classid=\"clsid:" + Roblox.Client._CLSID + "\"";
            pluginString += " id=\"robloxpluginobj\" type=\"application/x-vnd-roblox-launcher\"";
            pluginString += " codebase=\"" + Roblox.Client._installHost + "\"><p>Failed to INIT Plugin</p></object>";

            $(pluginDiv).append(pluginString);
        }
        else {
            // browser supports Netscape Plugin API
            pluginString = "<object id=\"robloxpluginobj\" type=\"application/x-vnd-roblox-launcher\"><p>Please Install the plugin</p></object>";

            $(pluginDiv).append(pluginString);
        }

        pluginObj = document.getElementById('robloxpluginobj');
    }

    if (!pluginObj) {
        Roblox.Client.ReleaseLauncher(pluginObj, addLock, false);
        Roblox.Client._whyIsRobloxLauncherNotCreated = Roblox.Client.LauncherNotCreatedReasons.unknownError;
        return null;
    }


    if ($("#robloxpluginobj p").is(":visible")) {
        // plugin is not installed
        Roblox.Client.ReleaseLauncher(pluginObj, addLock, false);
        Roblox.Client._whyIsRobloxLauncherNotCreated = Roblox.Client.LauncherNotCreatedReasons.pluginNotInstalled;
        return null;
    }

    try {
        pluginObj.Hello(); // fails if object isn't fully loaded
    }
    catch (ex) {
        var browserRequiresPluginActivation = Roblox.Client.browserRequiresPluginActivation();
        if (browserRequiresPluginActivation && !$("#robloxpluginobj p").is(":visible")) {
            // plugin is not allowed, and should be!
            // leave plugin in place so that browser prompts you to allow
            Roblox.Client._whyIsRobloxLauncherNotCreated = Roblox.Client.LauncherNotCreatedReasons.pluginNotAllowed;
        }
        else {
            // this is not a super-secure browser or the plugin obj text is visible.  derp?
            Roblox.Client.ReleaseLauncher();
            Roblox.Client._whyIsRobloxLauncherNotCreated = Roblox.Client.LauncherNotCreatedReasons.unknownError;
        }
        return null;
    }
    try {
        // Get the install host info for this plugin (different for IE vs Mozilla)
        var host = Roblox.Client.GetInstallHost(pluginObj);

        if (!host || host != Roblox.Client._installHost)
            throw "wrong InstallHost: (plugins):  " + host + "  (servers):  " + Roblox.Client._installHost;

    }
    catch (ex) {
        switch (Roblox.Client._whyIsRobloxLauncherNotCreated) {
            case Roblox.Client.LauncherNotCreatedReasons.pluginNotAllowed:
                // we can't tell that the install host is wrong until the plugin is allowed
                // in which case we want to prompt for download, hence treating this differently
                Roblox.Client._whyIsRobloxLauncherNotCreated = Roblox.Client.LauncherNotCreatedReasons.wrongInstallHostAndPluginWasNotAllowed;
                break;
            case Roblox.Client.LauncherNotCreatedReasons.wrongInstallHostAndPluginWasNotAllowed:
                break;
            default:
                Roblox.Client._whyIsRobloxLauncherNotCreated = Roblox.Client.LauncherNotCreatedReasons.wrongInstallHost;
        }
        Roblox.Client.ReleaseLauncher(pluginObj, addLock, false);
        return null;
    }

    if (!Roblox.Client.IsUpToDateVersion(pluginObj)) {
        Roblox.Client._whyIsRobloxLauncherNotCreated = Roblox.Client.LauncherNotCreatedReasons.versionMismatch;
        return null;
    }

    return pluginObj;
};

Roblox.Client.whyIsRobloxLauncherNotCreated = function () {
    return Roblox.Client._whyIsRobloxLauncherNotCreated;
};

Roblox.Client.isIDE = function () {
    if (Roblox.Client._isIDE == null) {
        Roblox.Client._isIDE = false;
        Roblox.Client._isRobloxBrowser = false;

        if (window.external) {
            try {
                if (window.external.IsRobloxAppIDE !== undefined) {
                    Roblox.Client._isIDE = window.external.IsRobloxAppIDE;
                    Roblox.Client._isRobloxBrowser = true;
                }
            }
            catch (ex) {
            }
        }
    }
    return Roblox.Client._isIDE;
};

Roblox.Client.isRobloxBrowser = function () {
    Roblox.Client.isIDE();
    return Roblox.Client._isRobloxBrowser;
};

Roblox.Client.robloxBrowserInstallHost = function () {
    if (window.external) {
        try {
            return window.external.InstallHost;
        }
        catch (ex) {

        }
    }
    return "";
};

Roblox.Client.IsRobloxProxyInstalled = function () {
    var o = Roblox.Client.CreateLauncher(false);
    var isInstalled = false;
    if (o != null) {
         isInstalled = true;
    }
    Roblox.Client.ReleaseLauncher(o, false, false);

    if (isInstalled || Roblox.Client.isRobloxBrowser())
        return true;
    return false;
};

Roblox.Client.IsRobloxInstalled = function () {
    try {
        var o = Roblox.Client.CreateLauncher(false);

        var host = Roblox.Client.GetInstallHost(o);
        Roblox.Client.ReleaseLauncher(o, false, false);

        return host == Roblox.Client._installHost;
    }
    catch (e) {
        if (Roblox.Client.isRobloxBrowser()) {
            host = Roblox.Client.robloxBrowserInstallHost();
            return host == Roblox.Client._installHost;
        }

        return false;
    }
};

Roblox.Client.SetStartInHiddenMode = function (value) {
    try {
        var o = Roblox.Client.CreateLauncher(false);

        if (o !== null) {
            //if (o.SetStartInHiddenMode) {
            o.SetStartInHiddenMode(value);
            Roblox.Client._hiddenModeEnabled = value;
            return true;  // if we can bit flip it, it's enabled.
            //}
        }
    }
    catch (e) {
        // swallow errors
    }
    // if o is null, o.SetStartInHiddenMode doesn't exist or o.SetStartInHiddenMode cannot be run
    return false;
};

Roblox.Client.UnhideApp = function () {
    try {
        if (Roblox.Client._hiddenModeEnabled) {
            var o = Roblox.Client.CreateLauncher(false);
            //if (o.UnhideApp) {
            o.UnhideApp();
            //}
        }
    }
    catch (exp) {
        // swallow errors
    }
};

Roblox.Client.Update = function () {
    EventTracker.start('UpdateClient');
    try {
        var o = Roblox.Client.CreateLauncher(false);
        o.Update();
        Roblox.Client.ReleaseLauncher(o, false, false);
    }
    catch (e) {
        EventTracker.endFailure('UpdateClient');
        alert('Error updating: ' + e);
    }
};

Roblox.Client.WaitForRoblox = function (continuation) {
    if (Roblox.Client._skip) {
        window.location = Roblox.Client._skip;
        return false;
    }
    Roblox.Client._continuation = continuation;
    Roblox.Client._cancelled = false;

    var osName = "Windows";
    if (navigator.appVersion.indexOf("Mac") != -1)
    {
        osName = "Mac";
    }

    if (Roblox.Client.IsRobloxProxyInstalled()) {
        Roblox.Client._continuation();
        return false;
    }
    else if (Roblox.Client._whyIsRobloxLauncherNotCreated == Roblox.Client.LauncherNotCreatedReasons.pluginNotAllowed) {
        Roblox.InstallationInstructions.show("activation");
        GoogleAnalyticsEvents && GoogleAnalyticsEvents.FireEvent(['Activation Begin', osName]);
        /* we may need this
        // Chrome restarts all processes when a plugin is installed so save our state so we can resume later
        if (window.chrome) {
            window.location.hash = '#chromeInstall';
            $.cookie('chromeInstall', continuation.toString().replace(/play_placeId/, play_placeId.toString()));
        }
        */
    }
    else {
        EventTracker.start('InstallClient');
        Roblox.InstallationInstructions.show("installation");

        Roblox.Client._runInstallABTest();

        //Tracking
        GoogleAnalyticsEvents && GoogleAnalyticsEvents.FireEvent(['Install Begin', osName]);

        // Chrome restarts all processes when a plugin is installed so save our state so we can resume later
        if (window.chrome) {
            window.location.hash = '#chromeInstall';
            $.cookie('chromeInstall', continuation.toString().replace(/play_placeId/, play_placeId.toString()));
        }

        // try to download
        var iframe = document.getElementById("downloadInstallerIFrame");
        iframe.src = "/install/setup.ashx";
    }

    // Set a timer to continue launching the game 
    window.setTimeout(function () { Roblox.Client._ontimer(); }, 1000);
    return true;
};
Roblox.Client.ResumeTimer = function (continuation) {
    Roblox.Client._continuation = continuation;
    Roblox.Client._cancelled = false;
    window.setTimeout(function () { Roblox.Client._ontimer(); }, 0);
};

Roblox.Client.Refresh = function () {
    try {
        navigator.plugins.refresh(false);
    }
    catch (ex) {
    }
};

Roblox.Client._onCancel = function () {
    Roblox.InstallationInstructions.hide();
    Roblox.Client._cancelled = true;
    EventTracker.endCancel('InstallClient');
    return false;
};

Roblox.Client._ontimer = function () {
    if (Roblox.Client._cancelled)
        return;

    Roblox.Client.Refresh();

    if (Roblox.Client.IsRobloxProxyInstalled()) {
        Roblox.InstallationInstructions.hide();
        window.setTimeout(function () {
            if (window.chrome && window.location.hash == '#chromeInstall') {
                // Chrome installed, but did not restart the tab.  Remove the hash tag and cookie.
                window.location.hash = '';
                $.cookie('chromeInstall', null);
            }
        }, 5000);
        EventTracker.endSuccess('InstallClient');
        Roblox.Client._continuation();
        if (Roblox.Client._installSuccess)
            Roblox.Client._installSuccess();
    }
    else if (Roblox.Client._whyIsRobloxLauncherNotCreated == Roblox.Client.LauncherNotCreatedReasons.pluginNotAllowed) {
        Roblox.InstallationInstructions.show("activation");
        window.setTimeout(function () { Roblox.Client._ontimer(); }, 1000);
    }
    else if (Roblox.Client._whyIsRobloxLauncherNotCreated == Roblox.Client.LauncherNotCreatedReasons.wrongInstallHostAndPluginWasNotAllowed) {
        // user hadn't allowed the plugin on this domain, but installed on a different domain
        // now that they have allowed the plugin
        // reset the "why" reason so we don't end up in a download loop
        Roblox.Client._whyIsRobloxLauncherNotCreated = null;
        // prompt the download
        Roblox.InstallationInstructions.hide();
        Roblox.Client.WaitForRoblox(Roblox.Client._continuation);
    }
    else {
        window.setTimeout(function () { Roblox.Client._ontimer(); }, 1000);
    }
};


}
/*
     FILE ARCHIVED ON 01:58:06 Sep 08, 2014 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:22 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 0.779
  exclusion.robots: 0.094
  exclusion.robots.policy: 0.082
  cdx.remote: 0.073
  esindex: 0.012
  LoadShardBlock: 1485.932 (3)
  PetaboxLoader3.datanode: 908.004 (4)
  PetaboxLoader3.resolve: 480.504 (2)
  load_resource: 431.421
*/