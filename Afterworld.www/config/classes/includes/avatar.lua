local userId = tonumber("%userid%")
local baseurl = "%url%"

game:GetService("ScriptContext").ScriptsDisabled = true

local success, err = pcall(function()
    game:GetService("ContentProvider"):SetBaseUrl("http://devopstest1." .. baseurl)
end)

if not success then
    return "BaseURL Error: " .. tostring(err)
end

local plr = game.Players:CreateLocalPlayer(0)
plr.CharacterAppearance = "http://devopstest1." .. baseurl .. "/Asset/characterfetch.ashx?UserID=" .. userId

local loaded, err = pcall(function()
    plr:LoadCharacter(false)
end)

if not loaded then
    return "LoadCharacter Failed: " .. tostring(err)
end

return game:GetService("ThumbnailGenerator"):Click("PNG", 352, 352, true)