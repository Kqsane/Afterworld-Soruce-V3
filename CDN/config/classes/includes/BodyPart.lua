-- BodyPart v1.0.6
-- See http://wiki.roblox.com/index.php?title=R15_Compatibility_Guide#Package_Parts for details on how body parts work with R15
-- Modified by meditext: Support for 2015M

local assetUrl = "rbxassetid://%assetid%"
local baseUrl = "%url%"
local fileExtension = "PNG"
local x, y = 352, 352
local R6RigUrl = "rbxassetid://1785197"
local ThumbnailGenerator = game:GetService("ThumbnailGenerator")
game.DescendantAdded:connect(function(obj)
	-- ASSET FIXER MADE BY MEDITEXT
	-- BaseParts renderers
	if obj:IsA("SpecialMesh") and obj.MeshType == Enum.MeshType.FileMesh then
		if not string.find(obj.MeshId, "roblox.com") and string.find(obj.MeshId, "blox14.lol") then
			return -- ignore
		end
		local a = string.gsub(obj.MeshId, "roblox.com", "aftwld.com")
		local b = string.gsub(a, "blox14.lol", "aftwld.com")
		obj.MeshId = b
		if not string.find(obj.TextureId, "roblox.com") and not string.find(obj.TextureId, "blox14.lol") then
			return -- ignore
		end
		local c = string.gsub(obj.TextureId, "roblox.com", "aftwld.com")
		local d = string.gsub(c, "blox14.lol", "aftwld.com")
		obj.TextureId = d
	elseif obj:IsA("Decal") or obj:IsA("Texture") then
		if not string.find(obj.Texture, "roblox.com") and not string.find(obj.Texture, "blox14.lol") then
			return -- ignore
		end
		local a = string.gsub(obj.Texture, "roblox.com", "aftwld.com")
		local b = string.gsub(a, "blox14.lol", "aftwld.com")
		obj.Texture = b
	-- player exclusive
	elseif obj:IsA("Tool") or obj:IsA("HopperBin") then
		if not string.find(obj.TextureId, "roblox.com") and not string.find(obj.TextureId, "blox14.lol") then
			return -- ignore
		end
		local c = string.gsub(obj.TextureId, "roblox.com", "aftwld.com")
		local d = string.gsub(c, "blox14.lol", "aftwld.com")
		obj.TextureId = d
	elseif obj:IsA("Shirt") then
		if not string.find(obj.ShirtTemplate, "roblox.com") and not string.find(obj.ShirtTemplate, "blox14.lol") then
			return -- ignore
		end
		local a = string.gsub(obj.ShirtTemplate, "roblox.com", "aftwld.com")
		local b = string.gsub(a, "blox14.lol", "aftwld.com")
		obj.ShirtTemplate = b
	elseif obj:IsA("ShirtGraphic") then
		if not string.find(obj.Graphic, "roblox.com") and not string.find(obj.Graphic, "blox14.lol") then
			return -- ignore
		end
		local a = string.gsub(obj.Graphic, "roblox.com", "aftwld.com")
		local b = string.gsub(a, "blox14.lol", "aftwld.com")
		obj.Graphic = a
	elseif obj:IsA("Pants") then
		if not string.find(obj.PantsTemplate, "roblox.com") and not string.find(obj.PantsTemplate, "blox14.lol") then
			return -- ignore
		end
		local a = string.gsub(obj.PantsTemplate, "roblox.com", "aftwld.com")
		local b = string.gsub(a, "blox14.lol", "aftwld.com")
		obj.PantsTemplate = b
	-- Player UI
	elseif obj:IsA("ImageButton") or obj:IsA("ImageLabel") then
		if not string.find(obj.Image, "roblox.com") and not string.find(obj.Image, "blox14.lol") then
			return -- ignore
		end
		local a = string.gsub(obj.Image, "roblox.com", "aftwld.com")
		local b = string.gsub(a, "blox14.lol", "aftwld.com")
		obj.Image = b
	-- Legacy/Local scripts
	elseif obj:IsA("Script") or obj:IsA("LocalScript") or obj:IsA("ModuleScript") then
		if not string.find(obj.Source, "roblox.com") and not string.find(obj.Source, "blox14.lol") then
			return -- ignore
		end
		local a = obj.Source
		a = a:gsub("roblox.com","aftwld.com"):gsub("blox14.lol","aftwld.com")
		obj.Source = a
	-- Sounds and misc
	elseif obj:IsA("Sound") then
		if not string.find(obj.SoundId, "roblox.com") and not string.find(obj.SoundId, "blox14.lol") then
			return -- ignore
		end
		local a = string.gsub(obj.SoundId, "roblox.com", "aftwld.com")
		local b = string.gsub(a, "blox14.lol", "aftwld.com")
		obj.SoundId = b
	end
end)
local CreateExtentsMinMax
local MannequinUtility
local ScaleUtility

pcall(function() game:GetService('ContentProvider'):SetBaseUrl(baseUrl) end)
game:GetService('ScriptContext').ScriptsDisabled = true

local objects = game:GetObjects(assetUrl)

local floatMax = math.huge

local mannequin

mannequin = game:GetObjects(R6RigUrl)[1]

mannequin.Parent = workspace

local function addFolderChildren(folder, focusPartNamesOut, focusPartsOut)
	for _, child in pairs(folder:GetChildren()) do
		local existingBodyPart = mannequin:FindFirstChild(child.Name)
		if existingBodyPart then
			existingBodyPart:Destroy()
		end
		child.Parent = mannequin
		table.insert(focusPartNamesOut, child.name)
		table.insert(focusPartsOut, child)
	end
end

local focusParts = {}
local focusPartNames = {}

for _, object in pairs(objects) do
	object.Parent = mannequin
end

local function addToBounds(cornerPosition, focusExtentsOut)
	focusExtentsOut["minx"] = math.min(focusExtentsOut["minx"], cornerPosition.x)
	focusExtentsOut["miny"] = math.min(focusExtentsOut["miny"], cornerPosition.y)
	focusExtentsOut["minz"] = math.min(focusExtentsOut["minz"], cornerPosition.z)
	focusExtentsOut["maxx"] = math.max(focusExtentsOut["maxx"], cornerPosition.x)
	focusExtentsOut["maxy"] = math.max(focusExtentsOut["maxy"], cornerPosition.y)
	focusExtentsOut["maxz"] = math.max(focusExtentsOut["maxz"], cornerPosition.z)
end

local function addCornerToBounds(partCFrame, cornerSelect, halfPartSize, focusExtentsOut)
	local cornerPositionLocal = cornerSelect * halfPartSize
	local cornerPositionWorld = partCFrame * cornerPositionLocal
	addToBounds(cornerPositionWorld, focusExtentsOut)
end

local extentsMinMax
local shouldCrop = false


local focusOnExtents = { minx = floatMax, miny = floatMax, minz = floatMax, maxx = -floatMax, maxy = -floatMax, maxz =  -floatMax }

extentsMinMax = {
	Vector3.new(focusOnExtents["minx"], focusOnExtents["miny"], focusOnExtents["minz"]),
	Vector3.new(focusOnExtents["maxx"], focusOnExtents["maxy"], focusOnExtents["maxz"])
}

local result, requestedUrls = ThumbnailGenerator:Click(fileExtension, x, y, --[[hideSky = ]] true, --[[crop = ]] shouldCrop, extentsMinMax)

return result, requestedUrls