-- Gear v1.0.3

local assetUrl = "rbxassetid://%assetid%"
local baseUrl = "%url%"
local fileExtension = "PNG"
local x, y = 352, 352
game.DescendantAdded:connect(function(obj)
	-- ASSET FIXER MADE BY MEDITEXT
	-- BaseParts renderers
	if obj:IsA("SpecialMesh") and obj.MeshType == Enum.MeshType.FileMesh then
		if not string.find(obj.MeshId, "roblox.com") and string.find(obj.MeshId, "blox14.lol") then
			return -- ignore
		end
		local a = string.gsub(obj.MeshId, "roblox.com", "aftwld.xyz")
		local b = string.gsub(a, "blox14.lol", "aftwld.xyz")
		obj.MeshId = b
		if not string.find(obj.TextureId, "roblox.com") and not string.find(obj.TextureId, "blox14.lol") then
			return -- ignore
		end
		local c = string.gsub(obj.TextureId, "roblox.com", "aftwld.xyz")
		local d = string.gsub(c, "blox14.lol", "aftwld.xyz")
		obj.TextureId = d
	elseif obj:IsA("Decal") or obj:IsA("Texture") then
		if not string.find(obj.Texture, "roblox.com") and not string.find(obj.Texture, "blox14.lol") then
			return -- ignore
		end
		local a = string.gsub(obj.Texture, "roblox.com", "aftwld.xyz")
		local b = string.gsub(a, "blox14.lol", "aftwld.xyz")
		obj.Texture = b
	-- player exclusive
	elseif obj:IsA("Tool") or obj:IsA("HopperBin") then
		if not string.find(obj.TextureId, "roblox.com") and not string.find(obj.TextureId, "blox14.lol") then
			return -- ignore
		end
		local c = string.gsub(obj.TextureId, "roblox.com", "aftwld.xyz")
		local d = string.gsub(c, "blox14.lol", "aftwld.xyz")
		obj.TextureId = d
	elseif obj:IsA("Shirt") then
		if not string.find(obj.ShirtTemplate, "roblox.com") and not string.find(obj.ShirtTemplate, "blox14.lol") then
			return -- ignore
		end
		local a = string.gsub(obj.ShirtTemplate, "roblox.com", "aftwld.xyz")
		local b = string.gsub(a, "blox14.lol", "aftwld.xyz")
		obj.ShirtTemplate = b
	elseif obj:IsA("ShirtGraphic") then
		if not string.find(obj.Graphic, "roblox.com") and not string.find(obj.Graphic, "blox14.lol") then
			return -- ignore
		end
		local a = string.gsub(obj.Graphic, "roblox.com", "aftwld.xyz")
		local b = string.gsub(a, "blox14.lol", "aftwld.xyz")
		obj.Graphic = a
	elseif obj:IsA("Pants") then
		if not string.find(obj.PantsTemplate, "roblox.com") and not string.find(obj.PantsTemplate, "blox14.lol") then
			return -- ignore
		end
		local a = string.gsub(obj.PantsTemplate, "roblox.com", "aftwld.xyz")
		local b = string.gsub(a, "blox14.lol", "aftwld.xyz")
		obj.PantsTemplate = b
	-- Player UI
	elseif obj:IsA("ImageButton") or obj:IsA("ImageLabel") then
		if not string.find(obj.Image, "roblox.com") and not string.find(obj.Image, "blox14.lol") then
			return -- ignore
		end
		local a = string.gsub(obj.Image, "roblox.com", "aftwld.xyz")
		local b = string.gsub(a, "blox14.lol", "aftwld.xyz")
		obj.Image = b
	-- Legacy/Local scripts
	elseif obj:IsA("Script") or obj:IsA("LocalScript") or obj:IsA("ModuleScript") then
		if not string.find(obj.Source, "roblox.com") and not string.find(obj.Source, "blox14.lol") then
			return -- ignore
		end
		local a = obj.Source
		a = a:gsub("roblox.com","aftwld.xyz"):gsub("blox14.lol","aftwld.xyz")
		obj.Source = a
	-- Sounds and misc
	elseif obj:IsA("Sound") then
		if not string.find(obj.SoundId, "roblox.com") and not string.find(obj.SoundId, "blox14.lol") then
			return -- ignore
		end
		local a = string.gsub(obj.SoundId, "roblox.com", "aftwld.xyz")
		local b = string.gsub(a, "blox14.lol", "aftwld.xyz")
		obj.SoundId = b
	end
end)
local ThumbnailGenerator = game:GetService("ThumbnailGenerator")

pcall(function() game:GetService("ContentProvider"):SetBaseUrl(baseUrl) end)
game:GetService("ScriptContext").ScriptsDisabled = true

for _, object in pairs(game:GetObjects(assetUrl)) do
	object.Parent = workspace
end

local result, requestedUrls = ThumbnailGenerator:Click(fileExtension, x, y, --[[hideSky = ]] true, --[[crop =]] true)

return result, requestedUrls