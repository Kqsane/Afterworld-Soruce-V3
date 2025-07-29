<?php
  include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
  $script = '
-- Setup studio cmd bar & load core scripts

pcall(function() game:GetService("InsertService"):SetFreeModelUrl("https://devopstest1.aftwld.xyz/Game/Tools/InsertAsset.ashx?type=fm&q=%s&pg=%d&rs=%d") end)
pcall(function() game:GetService("InsertService"):SetFreeDecalUrl("https://devopstest1.aftwld.xyz/Game/Tools/InsertAsset.ashx?type=fd&q=%s&pg=%d&rs=%d") end)

game:GetService("ScriptInformationProvider"):SetAssetUrl("https://devopstest1.aftwld.xyz/Asset/")
game:GetService("InsertService"):SetBaseSetsUrl("https://devopstest1.aftwld.xyz/Game/Tools/InsertAsset.ashx?nsets=10&type=base")
game:GetService("InsertService"):SetUserSetsUrl("https://devopstest1.aftwld.xyz/Game/Tools/InsertAsset.ashx?nsets=20&type=user&userid=%d")
game:GetService("InsertService"):SetCollectionUrl("https://devopstest1.aftwld.xyz/Game/Tools/InsertAsset.ashx?sid=%d")
game:GetService("InsertService"):SetAssetUrl("https://devopstest1.aftwld.xyz/Asset/?id=%d")
game:GetService("InsertService"):SetAssetVersionUrl("https://devopstest1.aftwld.xyz/Asset/?assetversionid=%d")

pcall(function() game:GetService("SocialService"):SetFriendUrl("https://devopstest1.aftwld.xyz/Game/LuaWebService/HandleSocialRequest.ashx?method=IsFriendsWith&playerid=%d&userid=%d") end)
pcall(function() game:GetService("SocialService"):SetBestFriendUrl("https://devopstest1.aftwld.xyz/Game/LuaWebService/HandleSocialRequest.ashx?method=IsBestFriendsWith&playerid=%d&userid=%d") end)
pcall(function() game:GetService("SocialService"):SetGroupUrl("https://devopstest1.aftwld.xyz/Game/LuaWebService/HandleSocialRequest.ashx?method=IsInGroup&playerid=%d&groupid=%d") end)
pcall(function() game:GetService("SocialService"):SetGroupRankUrl("https://devopstest1.aftwld.xyz/Game/LuaWebService/HandleSocialRequest.ashx?method=GetGroupRank&playerid=%d&groupid=%d") end)
pcall(function() game:GetService("SocialService"):SetGroupRoleUrl("https://devopstest1.aftwld.xyz/Game/LuaWebService/HandleSocialRequest.ashx?method=GetGroupRole&playerid=%d&groupid=%d") end)
pcall(function() game:GetService("GamePassService"):SetPlayerHasPassUrl("https://devopstest1.aftwld.xyz/Game/GamePass/GamePassHandler.ashx?Action=HasPass&UserID=%d&PassID=%d") end)
pcall(function() game:GetService("MarketplaceService"):SetProductInfoUrl("https://api.aftwld.xyz/marketplace/productinfo?assetId=%d") end)
pcall(function() game:GetService("MarketplaceService"):SetDevProductInfoUrl("https://api.aftwld.xyz/marketplace/productDetails?productId=%d") end)
pcall(function() game:GetService("MarketplaceService"):SetPlayerOwnsAssetUrl("https://api.aftwld.xyz/ownership/hasasset?userId=%d&assetId=%d") end)

local result = pcall(function() game:GetService("ScriptContext"):AddStarterScript(37801172) end)
if not result then
  pcall(function() game:GetService("ScriptContext"):AddCoreScript(37801172,game:GetService("ScriptContext"),"StarterScript") end)
end
';

$hostscriptsigned = gameUtils::signv1($script);
   header("content-type: text/plain");
exit($hostscriptsigned);