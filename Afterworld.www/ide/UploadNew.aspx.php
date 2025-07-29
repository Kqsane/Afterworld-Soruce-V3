<?php
if (!isset($_COOKIE["_ROBLOSECURITY"]) && !getuserinfo($_COOKIE["_ROBLOSECURITY"]))
    die(header("Location: https://devopstest1.aftwld.xyz/login"));

$assetId = $_GET["placeId"] ?? 0;



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" xmlns:fb="http://www.facebook.com/2008/fbml">
<head data-machine-id="nil">
    <!-- MachineID: nil -->
    <title>Publish New Model</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,requiresActiveX=true" />
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="author" content="ROBLOX Corporation" />
<meta name="description" content="ROBLOX is powered by a growing community of over 300,000 creators who produce an infinite variety of highly immersive experiences. These experiences range from 3D multiplayer games and competitions, to interactive adventures where friends can take on new personas imagining what it would be like to be a dinosaur, a miner in a quarry or an astronaut on a space exploration." />
<meta name="keywords" content="free games, online games, building games, virtual worlds, free mmo, gaming cloud, physics engine" />

    <meta name="apple-itunes-app" content="app-id=431946152" />



<script type="application/ld+json">
    {
    "@context" : "http://schema.org",
    "@type" : "Organization",
    "name" : "Roblox",
    "url" : "https://devopstest1.aftwld.xyz/",
    "logo": "https://images.rbxcdn.com/e870a0b9bcd987fbe7f730c8002f8faa.png",
    "sameAs" : [
    "https://www.facebook.com/ROBLOX/",
    "https://twitter.com/roblox",
    "https://www.linkedin.com/company/147977",
    "https://www.instagram.com/roblox/",
    "https://www.youtube.com/user/roblox",
    "https://plus.google.com/+roblox",
    "https://www.twitch.tv/roblox"
    ]
    }
</script>
    <meta ng-csp="no-unsafe-eval">

    
    <link href="https://images.rbxcdn.com/7aee41db80c1071f60377c3575a0ed87.ico" rel="icon" />


    
    
<link onerror='Roblox.BundleDetector && Roblox.BundleDetector.reportBundleError(this)' rel='stylesheet'  href='https://static.rbxcdn.com/css/studio___37a1532dfd340c90425ec3f92abd0d2d_m.css/fetch' />

    
<link onerror='Roblox.BundleDetector && Roblox.BundleDetector.reportBundleError(this)' rel='stylesheet'  href='https://static.rbxcdn.com/css/page___a728f0284e3e0112adcd0482fc50935c_m.css/fetch' />



    <script type='text/javascript' src='https://js.rbxcdn.com/3719f3fb35135d05cf6b72d5b0f46333.js'></script>
 <script type='text/javascript'>
Roblox.config.externalResources = [];
Roblox.config.paths['Pages.Catalog'] = 'https://js.rbxcdn.com/943dbead6327ef7e601925fc45ffbeb0.js';
Roblox.config.paths['Pages.CatalogShared'] = 'https://js.rbxcdn.com/496e8f05b3aabfcd72a147ddb49aaf1e.js';
Roblox.config.paths['Widgets.AvatarImage'] = 'https://js.rbxcdn.com/6bac93e9bb6716f32f09db749cec330b.js';
Roblox.config.paths['Widgets.DropdownMenu'] = 'https://js.rbxcdn.com/7b436bae917789c0b84f40fdebd25d97.js';
Roblox.config.paths['Widgets.GroupImage'] = 'https://js.rbxcdn.com/33d82b98045d49ec5a1f635d14cc7010.js';
Roblox.config.paths['Widgets.HierarchicalDropdown'] = 'https://js.rbxcdn.com/3368571372da9b2e1713bb54ca42a65a.js';
Roblox.config.paths['Widgets.ItemImage'] = 'https://js.rbxcdn.com/e79fc9c586a76e2eabcddc240298e52c.js';
Roblox.config.paths['Widgets.PlaceImage'] = 'https://js.rbxcdn.com/31df1ed92170ebf3231defcd9b841008.js';
Roblox.config.paths['Widgets.SurveyModal'] = 'https://js.rbxcdn.com/d6e979598c460090eafb6d38231159f6.js';
</script>
    <script type='text/javascript' src='https://js.rbxcdn.com/a1ec30f16d5202192e1d89ed4aca2c58.js'></script>

    <script type="text/javascript">
if (typeof(Roblox) === "undefined") { Roblox = {}; }
Roblox.Endpoints = Roblox.Endpoints || {};
Roblox.Endpoints.Urls = Roblox.Endpoints.Urls || {};
Roblox.Endpoints.Urls['/asset/'] = 'https://assetgame.aftwld.xyz/asset/';
Roblox.Endpoints.Urls['/client-status/set'] = 'https://devopstest1.aftwld.xyz/client-status/set';
Roblox.Endpoints.Urls['/client-status'] = 'https://devopstest1.aftwld.xyz/client-status';
Roblox.Endpoints.Urls['/game/'] = 'https://assetgame.aftwld.xyz/game/';
Roblox.Endpoints.Urls['/game/edit.ashx'] = 'https://assetgame.aftwld.xyz/game/edit.ashx';
Roblox.Endpoints.Urls['/game/placelauncher.ashx'] = 'https://assetgame.aftwld.xyz/game/placelauncher.ashx';
Roblox.Endpoints.Urls['/game/preloader'] = 'https://assetgame.aftwld.xyz/game/preloader';
Roblox.Endpoints.Urls['/game/report-stats'] = 'https://assetgame.aftwld.xyz/game/report-stats';
Roblox.Endpoints.Urls['/game/report-event'] = 'https://assetgame.aftwld.xyz/game/report-event';
Roblox.Endpoints.Urls['/game/updateprerollcount'] = 'https://assetgame.aftwld.xyz/game/updateprerollcount';
Roblox.Endpoints.Urls['/login/default.aspx'] = 'https://devopstest1.aftwld.xyz/login/default.aspx';
Roblox.Endpoints.Urls['/my/avatar'] = 'https://devopstest1.aftwld.xyz/my/avatar';
Roblox.Endpoints.Urls['/my/money.aspx'] = 'https://devopstest1.aftwld.xyz/my/money.aspx';
Roblox.Endpoints.Urls['/navigation/userdata'] = 'https://devopstest1.aftwld.xyz/navigation/userdata';
Roblox.Endpoints.Urls['/chat/chat'] = 'https://devopstest1.aftwld.xyz/chat/chat';
Roblox.Endpoints.Urls['/chat/data'] = 'https://devopstest1.aftwld.xyz/chat/data';
Roblox.Endpoints.Urls['/friends/list'] = 'https://devopstest1.aftwld.xyz/friends/list';
Roblox.Endpoints.Urls['/navigation/getcount'] = 'https://devopstest1.aftwld.xyz/navigation/getCount';
Roblox.Endpoints.Urls['/regex/email'] = 'https://devopstest1.aftwld.xyz/regex/email';
Roblox.Endpoints.Urls['/catalog/browse.aspx'] = 'https://devopstest1.aftwld.xyz/catalog/browse.aspx';
Roblox.Endpoints.Urls['/catalog/html'] = 'https://search.aftwld.xyz/catalog/html';
Roblox.Endpoints.Urls['/catalog/json'] = 'https://search.aftwld.xyz/catalog/json';
Roblox.Endpoints.Urls['/catalog/contents'] = 'https://search.aftwld.xyz/catalog/contents';
Roblox.Endpoints.Urls['/catalog/lists.aspx'] = 'https://search.aftwld.xyz/catalog/lists.aspx';
Roblox.Endpoints.Urls['/catalog/items'] = 'https://search.aftwld.xyz/catalog/items';
Roblox.Endpoints.Urls['/asset-hash-thumbnail/image'] = 'https://assetgame.aftwld.xyz/asset-hash-thumbnail/image';
Roblox.Endpoints.Urls['/asset-hash-thumbnail/json'] = 'https://assetgame.aftwld.xyz/asset-hash-thumbnail/json';
Roblox.Endpoints.Urls['/asset-thumbnail-3d/json'] = 'https://assetgame.aftwld.xyz/asset-thumbnail-3d/json';
Roblox.Endpoints.Urls['/asset-thumbnail/image'] = 'https://assetgame.aftwld.xyz/asset-thumbnail/image';
Roblox.Endpoints.Urls['/asset-thumbnail/json'] = 'https://assetgame.aftwld.xyz/asset-thumbnail/json';
Roblox.Endpoints.Urls['/asset-thumbnail/url'] = 'https://assetgame.aftwld.xyz/asset-thumbnail/url';
Roblox.Endpoints.Urls['/asset/request-thumbnail-fix'] = 'https://assetgame.aftwld.xyz/asset/request-thumbnail-fix';
Roblox.Endpoints.Urls['/avatar-thumbnail-3d/json'] = 'https://devopstest1.aftwld.xyz/avatar-thumbnail-3d/json';
Roblox.Endpoints.Urls['/avatar-thumbnail/image'] = 'https://devopstest1.aftwld.xyz/avatar-thumbnail/image';
Roblox.Endpoints.Urls['/avatar-thumbnail/json'] = 'https://devopstest1.aftwld.xyz/avatar-thumbnail/json';
Roblox.Endpoints.Urls['/avatar-thumbnails'] = 'https://devopstest1.aftwld.xyz/avatar-thumbnails';
Roblox.Endpoints.Urls['/avatar/request-thumbnail-fix'] = 'https://devopstest1.aftwld.xyz/avatar/request-thumbnail-fix';
Roblox.Endpoints.Urls['/bust-thumbnail/json'] = 'https://devopstest1.aftwld.xyz/bust-thumbnail/json';
Roblox.Endpoints.Urls['/headshot-thumbnail/json'] = 'https://devopstest1.aftwld.xyz/headshot-thumbnail/json';
Roblox.Endpoints.Urls['/item-thumbnails'] = 'https://devopstest1.aftwld.xyz/item-thumbnails';
Roblox.Endpoints.Urls['/outfit-thumbnail/json'] = 'https://devopstest1.aftwld.xyz/outfit-thumbnail/json';
Roblox.Endpoints.Urls['/place-thumbnails'] = 'https://devopstest1.aftwld.xyz/place-thumbnails';
Roblox.Endpoints.Urls['/thumbnail/asset/'] = 'https://devopstest1.aftwld.xyz/thumbnail/asset/';
Roblox.Endpoints.Urls['/thumbnail/avatar-headshot'] = 'https://devopstest1.aftwld.xyz/thumbnail/avatar-headshot';
Roblox.Endpoints.Urls['/thumbnail/avatar-headshots'] = 'https://devopstest1.aftwld.xyz/thumbnail/avatar-headshots';
Roblox.Endpoints.Urls['/thumbnail/user-avatar'] = 'https://devopstest1.aftwld.xyz/thumbnail/user-avatar';
Roblox.Endpoints.Urls['/thumbnail/resolve-hash'] = 'https://devopstest1.aftwld.xyz/thumbnail/resolve-hash';
Roblox.Endpoints.Urls['/thumbnail/place'] = 'https://devopstest1.aftwld.xyz/thumbnail/place';
Roblox.Endpoints.Urls['/thumbnail/get-asset-media'] = 'https://devopstest1.aftwld.xyz/thumbnail/get-asset-media';
Roblox.Endpoints.Urls['/thumbnail/remove-asset-media'] = 'https://devopstest1.aftwld.xyz/thumbnail/remove-asset-media';
Roblox.Endpoints.Urls['/thumbnail/set-asset-media-sort-order'] = 'https://devopstest1.aftwld.xyz/thumbnail/set-asset-media-sort-order';
Roblox.Endpoints.Urls['/thumbnail/place-thumbnails'] = 'https://devopstest1.aftwld.xyz/thumbnail/place-thumbnails';
Roblox.Endpoints.Urls['/thumbnail/place-thumbnails-partial'] = 'https://devopstest1.aftwld.xyz/thumbnail/place-thumbnails-partial';
Roblox.Endpoints.Urls['/thumbnail_holder/g'] = 'https://devopstest1.aftwld.xyz/thumbnail_holder/g';
Roblox.Endpoints.Urls['/users/{id}/profile'] = 'https://devopstest1.aftwld.xyz/users/{id}/profile';
Roblox.Endpoints.Urls['/service-workers/push-notifications'] = 'https://devopstest1.aftwld.xyz/service-workers/push-notifications';
Roblox.Endpoints.Urls['/notification-stream/notification-stream-data'] = 'https://devopstest1.aftwld.xyz/notification-stream/notification-stream-data';
Roblox.Endpoints.Urls['/api/friends/acceptfriendrequest'] = 'https://devopstest1.aftwld.xyz/api/friends/acceptfriendrequest';
Roblox.Endpoints.Urls['/api/friends/declinefriendrequest'] = 'https://devopstest1.aftwld.xyz/api/friends/declinefriendrequest';
Roblox.Endpoints.Urls['/authentication/is-logged-in'] = 'https://devopstest1.aftwld.xyz/authentication/is-logged-in';
Roblox.Endpoints.addCrossDomainOptionsToAllRequests = true;
</script>

    <script type="text/javascript">
if (typeof(Roblox) === "undefined") { Roblox = {}; }
Roblox.Endpoints = Roblox.Endpoints || {};
Roblox.Endpoints.Urls = Roblox.Endpoints.Urls || {};
</script>


    <input name="__RequestVerificationToken" type="hidden" value="hUeII5fRjPZwp0EceWZ572eoeYJi2BCRrj2exAtuN_m4u9oIdDx0VsoJMAoUbd_o0dIXk9vM4CC4pMcxPc-DgcyJZmQ1" />
    
    <meta name="csrf-token" data-token="puGF+AOAR/47" />

</head>
<body>
    


<div class="boxed-body" data-return-url="https://devopstest1.aftwld.xyz/ide/publish/model">
    <div id="placeForm">
        <div class="headline">
                    <h2>Basic Settings</h2>

        </div>
        <form id="basicSettingsForm" method="post" action="https://devopstest1.aftwld.xyz/ide/publish/newmodel">
            <input data-val="true" data-val-number="The field GroupId must be a number." id="GroupId" name="GroupId" type="hidden" value="" />

            <input name="__RequestVerificationToken" type="hidden" value="uY3iVreM40B5ZdkSW7q41o--Q7C_WHw8IemvvKKj9Zh9ITOxrafac8lIg6mwC1kFTTAnN5D1g1u38KJEp9h_MeFd4gU1" />

            <label class="form-label" for="Name">Name:</label>
<input class="text-box text-box-medium" data-val="true" data-val-required="Name is required" id="Name" name="Name" type="text" value="" />            <span id="nameRow"><span class="field-validation-valid" data-valmsg-for="Name" data-valmsg-replace="true"></span></span>

            <label class="form-label" for="Description">Description:</label>
<textarea class="text-box text-area-medium" cols="80" id="Description" name="Description" rows="6">
</textarea>            <span class="field-validation-valid" data-valmsg-for="Description" data-valmsg-replace="true"></span>

            <label class="form-label" for="Genre">Genre:</label>
            <select class="form-select" id="Genre" name="Genre"><option selected="selected">All</option>
<option>Adventure</option>
<option>Building</option>
<option>Comedy</option>
<option>Fighting</option>
<option>FPS</option>
<option>Horror</option>
<option>Medieval</option>
<option>Military</option>
<option>Naval</option>
<option>RPG</option>
<option>Sci-Fi</option>
<option>Sports</option>
<option>Town and City</option>
<option>Western</option>
</select>
            <span class="field-validation-valid" data-valmsg-for="Genre" data-valmsg-replace="true"></span>
<label class="form-label" for="IsCopyingAllowed">Allow Copying</label><input data-val="true" data-val-required="The Allow Copying field is required." id="IsCopyingAllowed" name="IsCopyingAllowed" type="checkbox" value="true" /><input name="IsCopyingAllowed" type="hidden" value="false" />                    <span class="checkboxListItem">Allow copying</span>
                    <a href="http://wiki.aftwld.xyz/index.php/Free_models" class="tooltip" target="_blank"><img class="TipsyImg" title="Click here to learn more." height="13" width="12" src="https://static.rbxcdn.com/images/Icons/question.png" alt="Click here to learn more." style="width: 12px; height: 13px; margin-left: 4px;" /></a>

            <label class="form-label" for="AllowComments">Comments:</label>
            <input checked="checked" data-val="true" data-val-required="The Comments: field is required." id="AllowComments" name="AllowComments" type="checkbox" value="true" /><input name="AllowComments" type="hidden" value="false" />
            <span class="checkboxListItem">Allow comments</span>
            <input Value="CreateModel" data-val="true" data-val-required="The Action field is required." id="Action" name="Action" type="hidden" value="CreateModel" />
        </form>
    </div>
</div>
<div class="footer-button-container divider-top">
<a  class="btn-medium btn-primary" id="finishButton">Finish</a><a  class="btn-medium btn-negative" id="cancelButton">Cancel</a>
</div>


    <script type="text/javascript">function urchinTracker() {}</script>

<div class="ConfirmationModal modalPopup unifiedModal smallModal" data-modal-handle="confirmation" style="display:none;">
    <a class="genericmodal-close ImageButton closeBtnCircle_20h"></a>
    <div class="Title"></div>
    <div class="GenericModalBody">
        <div class="TopBody">
            <div class="ImageContainer roblox-item-image" data-image-size="small" data-no-overlays data-no-click>
                <img class="GenericModalImage" alt="generic image" />
            </div>
            <div class="Message"></div>
        </div>
        <div class="ConfirmationModalButtonContainer GenericModalButtonContainer">
            <a href id="roblox-confirm-btn"><span></span></a>
            <a href id="roblox-decline-btn"><span></span></a>
        </div>
        <div class="ConfirmationModalFooter">
        
        </div>  
    </div>  
    <script type="text/javascript">
        Roblox = Roblox || {};
        Roblox.Resources = Roblox.Resources || {};
        
        //<sl:translate>
        Roblox.Resources.GenericConfirmation = {
            yes: "Yes",
            No: "No",
            Confirm: "Confirm",
            Cancel: "Cancel"
        };
        //</sl:translate>
    </script>
</div>
    
    
</body>
</html>
