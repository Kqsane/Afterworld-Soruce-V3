local userId = tonumber("%userid%")
local baseurl = "%url%"

local success, err = pcall(function()
	game:GetService("ContentProvider"):SetBaseUrl("http://devopstest1." .. baseurl)
end)
if not success then
	return "BaseURL Error: " .. tostring(err)
end

game:GetService("ScriptContext").ScriptsDisabled = true
game:GetService("ThumbnailGenerator").GraphicsMode = 6

local plr = game.Players:CreateLocalPlayer(0)
plr.CharacterAppearance = "http://devopstest1." .. baseurl .. "/Asset/characterfetch.ashx?UserID=" .. userId

local ok, loadErr = pcall(function()
	plr:LoadCharacter(false)
end)
if not ok then
	return "LoadCharacter Failed: " .. tostring(loadErr)
end

pcall(function()
	for _, tool in pairs(plr.Backpack:GetChildren()) do
		tool.Parent = plr.Character
	end
end)

pcall(function()
	for _, child in pairs(plr.Character:GetChildren()) do
		if child:IsA("Tool") then
			local shoulder = plr.Character:FindFirstChild("Torso"):FindFirstChild("Right Shoulder")
			if shoulder then
				shoulder.CurrentAngle = math.pi / 2
			end
		end
	end
end)

local renderSuccess, result = pcall(function()
	return game:GetService("ThumbnailGenerator"):Click("OBJ", 512, 512, true)
end)

if not renderSuccess then
	return "Render Failed: " .. tostring(result)
end

return result
