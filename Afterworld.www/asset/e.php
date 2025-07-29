<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/main.php';


class FileMeshVertexNormalTexture3d {
    public float $vx;
    public float $vy;
    public float $vz;
    public float $nx;
    public float $ny;
    public float $nz;
    public float $tu;
    public float $tv;
    public int $tx;
    public int $ty;
    public int $tz;
    public int $ts;
    public int $r;
    public int $g;
    public int $b;
    public int $a;

    public function readData(string $data): void {
        if (strlen($data) < 40) {
            throw new Exception("FileMeshVertexNormalTexture3d.readData: data is too short (" . strlen($data) . " bytes)");
        }

        $this->vx = unpack('f', substr($data, 0, 4))[1];
        $this->vy = unpack('f', substr($data, 4, 4))[1];
        $this->vz = unpack('f', substr($data, 8, 4))[1];
        $this->nx = unpack('f', substr($data, 12, 4))[1];
        $this->ny = unpack('f', substr($data, 16, 4))[1];
        $this->nz = unpack('f', substr($data, 20, 4))[1];
        $this->tu = unpack('f', substr($data, 24, 4))[1];
        $this->tv = unpack('f', substr($data, 28, 4))[1];
        $this->tx = ord($data[32]);
        $this->ty = ord($data[33]);
        $this->tz = ord($data[34]);
        $this->ts = ord($data[35]);
        $this->r = ord($data[36]);
        $this->g = ord($data[37]);
        $this->b = ord($data[38]);
        $this->a = ord($data[39]);
    }

    public function exportData(): string {
        return pack('f*', $this->vx, $this->vy, $this->vz, $this->nx, $this->ny, $this->nz, $this->tu, $this->tv) .
               pack('c*', $this->tx, $this->ty, $this->tz, $this->ts, $this->r, $this->g, $this->b, $this->a);
    }
}

class FileMeshVertexNormalTexture3dNoRGBA {
    public float $vx;
    public float $vy;
    public float $vz;
    public float $nx;
    public float $ny;
    public float $nz;
    public float $tu;
    public float $tv;
    public int $tx;
    public int $ty;
    public int $tz;
    public int $ts;

    public function readData(string $data): void {
        if (strlen($data) < 36) {
            throw new Exception("FileMeshVertexNormalTexture3dNoRGBA.readData: data is too short (" . strlen($data) . " bytes)");
        }

        $this->vx = unpack('f', substr($data, 0, 4))[1];
        $this->vy = unpack('f', substr($data, 4, 4))[1];
        $this->vz = unpack('f', substr($data, 8, 4))[1];
        $this->nx = unpack('f', substr($data, 12, 4))[1];
        $this->ny = unpack('f', substr($data, 16, 4))[1];
        $this->nz = unpack('f', substr($data, 20, 4))[1];
        $this->tu = unpack('f', substr($data, 24, 4))[1];
        $this->tv = unpack('f', substr($data, 28, 4))[1];
        $this->tx = ord($data[32]);
        $this->ty = ord($data[33]);
        $this->tz = ord($data[34]);
        $this->ts = ord($data[35]);
    }

    public function exportData(): string {
        return pack('f*', $this->vx, $this->vy, $this->vz, $this->nx, $this->ny, $this->nz, $this->tu, $this->tv) .
               pack('c*', $this->tx, $this->ty, $this->tz, $this->ts);
    }
}

class FileMeshFace {
    public int $a;
    public int $b;
    public int $c;

    public function readData(string $data): void {
        if (strlen($data) < 12) {
            throw new Exception("FileMeshFace.readData: data is too short (" . strlen($data) . " bytes)");
        }

        $this->a = unpack('V', substr($data, 0, 4))[1];
        $this->b = unpack('V', substr($data, 4, 4))[1];
        $this->c = unpack('V', substr($data, 8, 4))[1];
    }

    public function exportData(): string {
        return pack('V*', $this->a, $this->b, $this->c);
    }
}

class FileMeshHeader {
    public int $cbSize = 12;
    public int $cbVerticesStride;
    public int $cbFaceStride;
    public int $num_vertices;
    public int $num_faces;

    public function readData(string $data): void {
        if (strlen($data) < 12) {
            throw new Exception("FileMeshHeader.readData: data is too short (" . strlen($data) . " bytes)");
        }

        $this->cbSize = unpack('v', substr($data, 0, 2))[1];
        if ($this->cbSize !== 12) {
            throw new Exception("FileMeshHeader.readData: invalid cbSize ({$this->cbSize})");
        }
        $this->cbVerticesStride = ord($data[2]);
        $this->cbFaceStride = ord($data[3]);
        $this->num_vertices = unpack('V', substr($data, 4, 4))[1];
        $this->num_faces = unpack('V', substr($data, 8, 4))[1];
    }

    public function exportData(): string {
        return pack('v', $this->cbSize) .
               chr($this->cbVerticesStride) .
               chr($this->cbFaceStride) .
               pack('V*', $this->num_vertices, $this->num_faces);
    }
}

class RBXMesh {
    private string $data;
    private FileMeshHeader $header;
    private array $vertices = [];
    private array $faces = [];

    public function __construct(string $data) {
        $this->data = $data;
    }

    public function getMeshVersion(): float {
        if (strlen($this->data) < 12) {
            throw new Exception("getMeshVersion: data is too short (" . strlen($this->data) . " bytes)");
        }

        $versionStr = substr($this->data, 0, 12);
        if (strpos($versionStr, "version ") !== 0) {
            throw new Exception("getMeshVersion: invalid mesh header");
        }

        switch ($versionStr) {
            case "version 1.00": return 1.0;
            case "version 1.01": return 1.1;
            case "version 2.00": return 2.0;
            case "version 3.00": return 3.0;
            case "version 3.01": return 3.1;
            case "version 4.00": return 4.0;
            case "version 4.01": return 4.1;
            case "version 5.00": return 5.0;
            case "version 5.01": return 5.1;
            default:
                throw new Exception("getMeshVersion: unsupported mesh version ($versionStr)");
        }
    }

    public function readMeshV1(float $scale = 0.5, bool $invertUV = true): array {
        $data = substr($this->data, strpos($this->data, "\n") + 1);
        $lines = explode("\n", $data);
        $numFaces = intval($lines[0]);

        preg_match_all('/\[([-\d.]+),([-\d.]+),([-\d.]+)\]/', $data, $matches, PREG_SET_ORDER);
        
        if (count($matches) !== $numFaces * 9) {
            throw new Exception("readMeshV1: invalid number of vertices");
        }

        $vertices = [];
        for ($i = 0; $i < count($matches); $i += 3) {
            $vertPos = array_map('floatval', array_slice($matches[$i], 1));
            $vertNorm = array_map('floatval', array_slice($matches[$i + 1], 1));
            $vertUV = array_map('floatval', array_slice($matches[$i + 2], 1));

            $vertex = new FileMeshVertexNormalTexture3d();
            $vertex->vx = $vertPos[0] * $scale;
            $vertex->vy = $vertPos[1] * $scale;
            $vertex->vz = $vertPos[2] * $scale;
            $vertex->nx = $vertNorm[0];
            $vertex->ny = $vertNorm[1];
            $vertex->nz = $vertNorm[2];
            $vertex->tu = $vertUV[0];
            $vertex->tv = $invertUV ? (1 - $vertUV[1]) : $vertUV[1];
            $vertex->tx = $vertex->ty = $vertex->tz = $vertex->ts = 0;
            $vertex->r = $vertex->g = $vertex->b = $vertex->a = 0;

            $vertices[] = $vertex;
        }

        $faces = [];
        for ($i = 0; $i < $numFaces; $i++) {
            $face = new FileMeshFace();
            $face->a = $i * 3;
            $face->b = $i * 3 + 1;
            $face->c = $i * 3 + 2;
            $faces[] = $face;
        }

        return ['vertices' => $vertices, 'faces' => $faces];
    }

    public function exportMeshV2(array $meshData): string {
        $vertices = $meshData['vertices'];
        $faces = $meshData['faces'];

        if (empty($vertices) || empty($faces)) {
            throw new Exception("exportMeshV2: empty mesh data");
        }

        $header = new FileMeshHeader();
        $header->cbVerticesStride = (isset($vertices[0]) && $vertices[0] instanceof FileMeshVertexNormalTexture3dNoRGBA) ? 36 : 40;
        $header->cbFaceStride = 12;
        $header->num_vertices = count($vertices);
        $header->num_faces = count($faces);

        $output = "version 2.00\n";
        $output .= $header->exportData();

        foreach ($vertices as $vertex) {
            $output .= $vertex->exportData();
        }

        foreach ($faces as $face) {
            $output .= $face->exportData();
        }

        return $output;
    }
}
class RobloxAssetMigrator {
  private PDO $pdo;
  private RBXMesh $meshHandler;

  public function __construct(PDO $pdo) {
      $this->pdo = $pdo;
      //$this->createTables();
  }


  public function migrateAsset(int $assetId, array $assetInfo, bool $keepRobloxId = true): bool {
      try {
          $assetContent = $this->fetchAssetContent($assetId);

          if ($assetInfo['AssetTypeId'] === 4) { 
              $assetContent = $this->processMesh($assetContent, $assetId);
          }

          $stmt = $this->pdo->prepare("INSERT INTO roblox_assets 
              (roblox_asset_id, asset_type, name, description, creator_id) 
              VALUES (?, ?, ?, ?, ?)");

          return $stmt->execute([
              $assetId,
              $assetInfo['AssetTypeId'],
              $assetInfo['Name'] ?? "Asset " . $assetId,
              $assetInfo['Description'] ?? "Migrated from Roblox",
              $assetInfo['CreatorId'] ?? 1
          ]);
      } catch (Exception $e) {
          error_log("Migration failed for asset {$assetId}: " . $e->getMessage());
          return false;
      }
  }

private function processMesh(string $content, int $assetId): string {
    try {
        $meshHandler = new RBXMesh($content);
        $version = $meshHandler->getMeshVersion();

        if ($version < 2.0) {
            $meshData = $meshHandler->readMeshV1();
            return $meshHandler->exportMeshV2($meshData);
        }

        return $content;
    } catch (Exception $e) {
        error_log("Mesh processing failed for asset {$assetId}: " . $e->getMessage());
        return $content;
    }
}

  private function fetchAssetContent(int $assetId): string {
      $response = file_get_contents("https://assetdelivery.aftwld.xyz/v1/asset/?id=" . $assetId);
      if (!$response) {
          throw new Exception("Failed to fetch asset content");
      }
      return $response;
  }
}