
<?php

class RBXMeshParser {
    
    public static function parse($buffer) {
		error_log("RAW HEADER HEX: " . bin2hex(substr($buffer, 0, 12)));
		error_log("RAW HEADER TEXT: " . substr($buffer, 0, 12));	
        $reader = new ByteReader($buffer);
		
        assert($reader->String(8) === "version ", "Invalid mesh file");

        $version = $reader->String(12);
		$version = substr($version, 8, 4);
        switch($version) {
            case "1.00":
            case "1.01":
                return self::parseText(self::bufferToString($buffer));
            case "2.00":
            case "3.00":
            case "3.01":
            case "4.00":
            case "4.01":
            case "5.00":
                return self::parseBin($buffer, $version);
            default:
                throw new Exception("Unsupported mesh version '$version'");
        }
    }

    public static function parseText($str) {
        $lines = preg_split('/\r?\n/', $str);
        assert(count($lines) === 3, "Invalid mesh version 1 file (Wrong amount of lines)");

        $version = $lines[0];
        $faceCount = intval($lines[1]);
        $data = $lines[2];

        $vectors = explode("][", substr(preg_replace('/\s+/', "", $data), 1, -1));
        assert(count($vectors) === $faceCount * 9, "Length mismatch");

        $scaleMultiplier = ($version === "version 1.00") ? 0.5 : 1;
        $vertexCount = $faceCount * 3;
        $vertices = array_fill(0, $vertexCount * 3, 0.0);
        $normals = array_fill(0, $vertexCount * 3, 0.0);
        $uvs = array_fill(0, $vertexCount * 2, 0.0);
        $faces = array_fill(0, $vertexCount, 0);

        for($i = 0; $i < $vertexCount; $i++) {
            $n = $i * 3;
            $vertex = explode(",", $vectors[$n]);
            $normal = explode(",", $vectors[$n + 1]);
            $uv = explode(",", $vectors[$n + 2]);

            $vertices[$n] = floatval($vertex[0]) * $scaleMultiplier;
            $vertices[$n + 1] = floatval($vertex[1]) * $scaleMultiplier;
            $vertices[$n + 2] = floatval($vertex[2]) * $scaleMultiplier;

            $normals[$n] = floatval($normal[0]);
            $normals[$n + 1] = floatval($normal[1]);
            $normals[$n + 2] = floatval($normal[2]);

            $uvs[$i * 2] = floatval($uv[0]);
            $uvs[$i * 2 + 1] = floatval($uv[1]);
            $faces[$i] = $i;
        }

        return [
            'vertices' => $vertices,
            'normals' => $normals,
            'uvs' => $uvs,
            'faces' => $faces,
            'lods' => [0, $faceCount]
        ];
    }

    public static function parseBin($buffer, $version) {
        $reader = new ByteReader($buffer);
        assert($reader->String(12) === "version $version", "Bad header");

        $newline = $reader->Byte();
        assert($newline === 0x0A || ($newline === 0x0D && $reader->Byte() === 0x0A), "Bad newline");

        $begin = $reader->GetIndex();
        
        $headerSize = 0;
        $vertexSize = 0;
        $faceSize = 12;
        $lodSize = 4;
        $nameTableSize = 0;
        $facsDataSize = 0;

        $lodCount = 0;
        $vertexCount = 0;
        $faceCount = 0;
        $boneCount = 0;
        $subsetCount = 0;

        if($version === "2.00") {
            $headerSize = $reader->UInt16LE();
            assert($headerSize >= 12, "Invalid header size $headerSize");

            $vertexSize = $reader->Byte();
            $faceSize = $reader->Byte();
            $vertexCount = $reader->UInt32LE();
            $faceCount = $reader->UInt32LE();
            
        } else if(substr($version, 0, 2) === "3.") {
            $headerSize = $reader->UInt16LE();
            assert($headerSize >= 16, "Invalid header size $headerSize");

            $vertexSize = $reader->Byte();
            $faceSize = $reader->Byte();
            $lodSize = $reader->UInt16LE();
            $lodCount = $reader->UInt16LE();
            $vertexCount = $reader->UInt32LE();
            $faceCount = $reader->UInt32LE();
            
        } else if(substr($version, 0, 2) === "4.") {
            $headerSize = $reader->UInt16LE();
            assert($headerSize >= 24, "Invalid header size $headerSize");

            $reader->Jump(2); // uint16 lodType;
            $vertexCount = $reader->UInt32LE();
            $faceCount = $reader->UInt32LE();
            $lodCount = $reader->UInt16LE();
            $boneCount = $reader->UInt16LE();
            $nameTableSize = $reader->UInt32LE();
            $subsetCount = $reader->UInt16LE();
            $reader->Jump(2); // byte numHighQualityLODs, unused;
            
            $vertexSize = 40;
            
        } else if(substr($version, 0, 2) === "5.") {
            $headerSize = $reader->UInt16LE();
            assert($headerSize >= 32, "Invalid header size $headerSize");

            $reader->Jump(2); // uint16 meshCount;
            $vertexCount = $reader->UInt32LE();
            $faceCount = $reader->UInt32LE();
            $lodCount = $reader->UInt16LE();
            $boneCount = $reader->UInt16LE();
            $nameTableSize = $reader->UInt32LE();
            $subsetCount = $reader->UInt16LE();
            $reader->Jump(2); // byte numHighQualityLODs, unused;
            $reader->Jump(4); // uint32 facsDataFormat;
            $facsDataSize = $reader->UInt32LE();
            
            $vertexSize = 40;
        }
        
        $reader->SetIndex($begin + $headerSize);
        
        assert($vertexSize >= 36, "Invalid vertex size $vertexSize");
        assert($faceSize >= 12, "Invalid face size $faceSize");
        assert($lodSize >= 4, "Invalid lod size $lodSize");

        $fileEnd = $reader->GetIndex()
            + ($vertexCount * $vertexSize)
            + (($boneCount > 0) ? ($vertexCount * 8) : 0)
            + ($faceCount * $faceSize)
            + ($lodCount * $lodSize)
            + ($boneCount * 60)
            + ($nameTableSize)
            + ($subsetCount * 72)
            + ($facsDataSize);
        
        assert($fileEnd === $reader->GetLength(), "Invalid file size (expected " . $reader->GetLength() . ", got $fileEnd)");
        
		error_log("vCount=$vertexCount, fCount=$faceCount, bones=$boneCount, subsets=$subsetCount");
        $faces = array_fill(0, $faceCount * 3, 0);
        $vertices = array_fill(0, $vertexCount * 3, 0.0);
        $normals = array_fill(0, $vertexCount * 3, 0.0);
        $uvs = array_fill(0, $vertexCount * 2, 0.0);
        $tangents = array_fill(0, $vertexCount * 4, 0);
        $vertexColors = ($vertexSize >= 40) ? array_fill(0, $vertexCount * 4, 0) : null;
        $lods = [];

        $mesh = [
            'vertexColors' => $vertexColors,
            'vertices' => $vertices,
            'tangents' => $tangents,
            'normals' => $normals,
            'faces' => $faces,
            'lods' => $lods,
            'uvs' => $uvs
        ];
        
        // Vertex[vertexCount]
        
        for($i = 0; $i < $vertexCount; $i++) {
            $vertices[$i * 3] = $reader->FloatLE();
            $vertices[$i * 3 + 1] = $reader->FloatLE();
            $vertices[$i * 3 + 2] = $reader->FloatLE();

            $normals[$i * 3] = $reader->FloatLE();
            $normals[$i * 3 + 1] = $reader->FloatLE();
            $normals[$i * 3 + 2] = $reader->FloatLE();

            $uvs[$i * 2] = $reader->FloatLE();
            $uvs[$i * 2 + 1] = 1 - $reader->FloatLE();
            
            // tangents are mapped from [0, 254] to [-1, 1]
            // byte tx, ty, tz, ts;
            
            $tangents[$i * 4] = $reader->Byte() / 127 - 1;
            $tangents[$i * 4 + 1] = $reader->Byte() / 127 - 1;
            $tangents[$i * 4 + 2] = $reader->Byte() / 127 - 1;
            $tangents[$i * 4 + 3] = $reader->Byte() / 127 - 1;
            
            if($vertexColors) {
                // byte r, g, b, a
                $vertexColors[$i * 4] = $reader->Byte();
                $vertexColors[$i * 4 + 1] = $reader->Byte();
                $vertexColors[$i * 4 + 2] = $reader->Byte();
                $vertexColors[$i * 4 + 3] = $reader->Byte();
                
                $reader->Jump($vertexSize - 40);
            } else {
                $reader->Jump($vertexSize - 36);
            }
        }
        
        // Envelope[vertexCount]
        
        if($boneCount > 0) {
            $mesh['skinIndices'] = array_fill(0, $vertexCount * 4, 0);
            $mesh['skinWeights'] = array_fill(0, $vertexCount * 4, 0.0);
            
            for($i = 0; $i < $vertexCount; $i++) {
                $mesh['skinIndices'][$i * 4 + 0] = $reader->Byte();
                $mesh['skinIndices'][$i * 4 + 1] = $reader->Byte();
                $mesh['skinIndices'][$i * 4 + 2] = $reader->Byte();
                $mesh['skinIndices'][$i * 4 + 3] = $reader->Byte();
                $mesh['skinWeights'][$i * 4 + 0] = $reader->Byte() / 255;
                $mesh['skinWeights'][$i * 4 + 1] = $reader->Byte() / 255;
                $mesh['skinWeights'][$i * 4 + 2] = $reader->Byte() / 255;
                $mesh['skinWeights'][$i * 4 + 3] = $reader->Byte() / 255;
            }
        }
        
        // Face[faceCount]
        
        for($i = 0; $i < $faceCount; $i++) {
            $faces[$i * 3] = $reader->UInt32LE();
            $faces[$i * 3 + 1] = $reader->UInt32LE();
            $faces[$i * 3 + 2] = $reader->UInt32LE();

            $reader->Jump($faceSize - 12);
        }
        
        // LodLevel[lodCount]
        
        if($lodCount <= 2) {
            // Lod levels are pretty much ignored if lodCount
            // is not at least 3, so we can just skip reading
            // them completely.
            
            array_push($lods, 0, $faceCount);
            $reader->Jump($lodCount * $lodSize);
        } else {
            for($i = 0; $i < $lodCount; $i++) {
                array_push($lods, $reader->UInt32LE());
                $reader->Jump($lodSize - 4);
            }
        }
        
        // Bone[boneCount]

        if($boneCount > 0) {
            $nameTableStart = $reader->GetIndex() + $boneCount * 60;
            
            $mesh['bones'] = array_fill(0, $boneCount, null);
            
            for($i = 0; $i < $boneCount; $i++) {
                $bone = [];
                
                $nameStart = $nameTableStart + $reader->UInt32LE();
                $nameEnd = $reader->indexOf(0, $nameStart);
                
                $bone['name'] = self::bufferToString($reader->subarray($nameStart, $nameEnd));
                $bone['parent'] = $mesh['bones'][$reader->UInt16LE()];
                $bone['lodParent'] = $mesh['bones'][$reader->UInt16LE()];
                $bone['culling'] = $reader->FloatLE();
                $bone['cframe'] = array_fill(0, 12, 0.0);
                
                for($j = 0; $j < 9; $j++) {
                    $bone['cframe'][$j + 3] = $reader->FloatLE();
                }
                
                for($j = 0; $j < 3; $j++) {
                    $bone['cframe'][$j] = $reader->FloatLE();
                }
                
                $mesh['bones'][$i] = $bone;
            }
        }
        
        // byte[nameTableSize]

        if($nameTableSize > 0) {
            $reader->Jump($nameTableSize);
        }
        
        // MeshSubset[subsetCount]

        if($subsetCount > 0) {
            $boneIndices = [];
            
            for($i = 0; $i < $subsetCount; $i++) {
                $reader->UInt32LE(); // facesBegin
                $reader->UInt32LE(); // facesLength
                $vertsBegin = $reader->UInt32LE();
                $vertsLength = $reader->UInt32LE();
                $reader->UInt32LE(); // numBoneIndices
                
                for($j = 0; $j < 26; $j++) {
                    $boneIndices[$j] = $reader->UInt16LE();
                }
                
                $vertsEnd = $vertsBegin + $vertsLength;
                for($j = $vertsBegin; $j < $vertsEnd; $j++) {
                    $mesh['skinIndices'][$j * 4 + 0] = $boneIndices[$mesh['skinIndices'][$j * 4 + 0]];
                    $mesh['skinIndices'][$j * 4 + 1] = $boneIndices[$mesh['skinIndices'][$j * 4 + 1]];
                    $mesh['skinIndices'][$j * 4 + 2] = $boneIndices[$mesh['skinIndices'][$j * 4 + 2]];
                    $mesh['skinIndices'][$j * 4 + 3] = $boneIndices[$mesh['skinIndices'][$j * 4 + 3]];
                }
            }
        }
        
        // byte[facsDataSize]
        
        if($facsDataSize > 0) {
            $reader->Jump($facsDataSize);
        }

        //

        return $mesh;
    }

    private static function bufferToString($buffer) {
        if (is_string($buffer)) {
            return $buffer;
        }
        return implode('', array_map('chr', $buffer));
    }
}

