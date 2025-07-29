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

if (typeof Roblox === 'undefined') {
	Roblox = {};
}

var RobloxABLaunch = {
	launchGamePage: null,
	launcher: null
};

RobloxABLaunch.RequestGame = function (behaviorID, placeID, gender) {
	RobloxPlaceLauncherService.LogJoinClick();
	if (RobloxABLaunch.launcher === null) {
		RobloxABLaunch.launcher = new Roblox.ABPlaceLauncher();
	}

	RobloxABLaunch.launcher.RequestGame(placeID, gender);
};

RobloxABLaunch.RequestGroupBuildGame = function (behaviorID, placeID) {
	RobloxPlaceLauncherService.LogJoinClick();
	if (RobloxABLaunch.launcher === null) {
		RobloxABLaunch.launcher = new Roblox.ABPlaceLauncher();
	}
	RobloxABLaunch.launcher.RequestGroupBuildGame(placeID);
};

//Called from "Join" under "Load Running Games" on a Place Page.
RobloxABLaunch.RequestGameJob = function (behaviorID, placeId, gameId) {
	RobloxPlaceLauncherService.LogJoinClick();
	if (RobloxABLaunch.launcher === null) {
		RobloxABLaunch.launcher = new Roblox.ABPlaceLauncher();
	}
	RobloxABLaunch.launcher.RequestGameJob(placeId, gameId);
};

RobloxABLaunch.StartGame = function (visitUrl, type, authenticationUrl, authenticationTicket, isEdit) {
	//Fix for the HttpSendRequest,err=0x2F7E
	authenticationUrl = authenticationUrl.replace("http://", "https://");

	try {
		if (typeof window.external !== 'undefined' && window.external.IsRobloxABApp) {
			window.external.StartGame(authenticationTicket, authenticationUrl, visitUrl);
		}
	}
	catch (err) {
		return false;
	}
	return true;
};

Roblox.ABPlaceLauncher = function () {
};

Roblox.ABPlaceLauncher.prototype =
{
	_onGameStatus: function (result) {
		if (result.status === 2) {
			RobloxABLaunch.StartGame(result.joinScriptUrl, "Join", result.authenticationUrl, result.authenticationTicket);
		}
		else if (result.status < 2 || result.status === 6) {
			// Try again
			var onSuccess = function (result, context) { context._onGameStatus(result); };
			var onError = function (result, context) { context._onGameError(result); };
			var self = this;
			var call = function () {
				RobloxPlaceLauncherService.CheckGameJobStatus(result.jobId, onSuccess, onError, self);
			};
			window.setTimeout(call, 2000);
       }
	},
	_onGameError: function (result) {
		console.log("An error occurred. Please try again later -"+result);
	},
	_startUpdatePolling: function (joinGameDelegate) {
		try {
			joinGameDelegate();
		}
		catch (e) {
			joinGameDelegate();
		}
	},
	// TODO: This should only be called once.  What if you call it again???
	RequestGame: function (placeId, gender) {
		// Now send a request to the Grid...
		var onGameSuccess = function (result, context) { context._onGameStatus(result); };
		var onGameError = function (result, context) { context._onGameError(result); };
		var self = this;
		var isPartyLeader = false;

		if (typeof Party !== 'undefined' && typeof Party.AmILeader === 'function') {
			isPartyLeader = Party.AmILeader();
		}

		var gameDelegate = function () { RobloxPlaceLauncherService.RequestGame(placeId, isPartyLeader, gender, onGameSuccess, onGameError, self); };

		this._startUpdatePolling(gameDelegate);

		return false;
	},
	// TODO: This should only be called once.  What if you call it again???
	RequestGroupBuildGame: function (placeId) {
		// Now send a request to the Grid...
		var onGameSuccess = function (result, context) { context._onGameStatus(result, true); };
		var onGameError = function (result, context) { context._onGameError(result); };
		var self = this;
		var gameDelegate = function () { RobloxPlaceLauncherService.RequestGroupBuildGame(placeId, onGameSuccess, onGameError, self); };

		this._startUpdatePolling(gameDelegate);

		return false;
	},
	// TODO: This should only be called once.  What if you call it again???
	RequestGameJob: function (placeId, gameId) {
		// Now send a request to the Grid...
		var onGameSuccess = function (result, context) { context._onGameStatus(result); };
		var onGameError = function (result, context) { context._onGameError(result); };
		var self = this;
		var gameDelegate = function () { RobloxPlaceLauncherService.RequestGameJob(placeId, gameId, onGameSuccess, onGameError, self); };

		this._startUpdatePolling(gameDelegate);

		return false;
	},
	dispose: function () {
		Roblox.ABPlaceLauncher.callBaseMethod(this, 'dispose');
	}
};


}
/*
     FILE ARCHIVED ON 01:58:04 Sep 08, 2014 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 16:42:19 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 0.624
  exclusion.robots: 0.085
  exclusion.robots.policy: 0.075
  cdx.remote: 0.06
  esindex: 0.008
  LoadShardBlock: 1123.496 (3)
  PetaboxLoader3.datanode: 1483.45 (4)
  load_resource: 884.192
  PetaboxLoader3.resolve: 193.068
*/