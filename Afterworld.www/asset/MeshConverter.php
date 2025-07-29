<?php

class MeshParserV3 {
    private $data;
    private $offset = 0;

    public function __construct(string $binaryData) {
        $this->data = $binaryData;
    }

    private function readUInt16LE(): int {
        $val = unpack('v', substr($this->data, $this->offset, 2))[1];
        $this->offset += 2;
        return $val;
    }

    private function readUInt32LE(): int {
        $val = unpack('V', substr($this->data, $this->offset, 4))[1];
        $this->offset += 4;
        return $val;
    }

    private function readFloatLE(): float {
        $val = unpack('f', substr($this->data, $this->offset, 4))[1];
        $this->offset += 4;
        return $val;
    }

    private function readByte(): int {
        return ord($this->data[$this->offset++]);
    }

    private function jump(int $bytes): void {
        $this->offset += $bytes;
    }

    public function parse(): array {
        $header = substr($this->data, 0, 12);
        if (!str_starts_with($header, "version 3") && !str_starts_with($header, "version 4") && !str_starts_with($header, "version 5")) {
            throw new Exception("Unsupported mesh version");
        }

        $this->offset = 12;
        $newline = $this->readByte();
        if ($newline === 0x0D) $this->readByte();

        $headerSize = $this->readUInt16LE();
        $vertexSize = $this->readByte();
        $faceSize = $this->readByte();
        $this->jump(2); // sizeof_LodOffset
        $lodCount = $this->readUInt16LE();
        $vertexCount = $this->readUInt32LE();
        $faceCount = $this->readUInt32LE();

        $this->offset = 12 + $headerSize; // Skip header

        $vertices = [];
        $faces = [];

        for ($i = 0; $i < $vertexCount; $i++) {
            $vx = new stdClass();
            $vx->x = $this->readFloatLE();
            $vx->y = $this->readFloatLE();
            $vx->z = $this->readFloatLE();
            $vx->nx = $this->readFloatLE();
            $vx->ny = $this->readFloatLE();
            $vx->nz = $this->readFloatLE();
            $vx->u = $this->readFloatLE();
            $vx->v = 1.0 - $this->readFloatLE(); // Flip V
            $vx->tx = $this->readByte(); // tangent x (skipped)
            $vx->ty = $this->readByte();
            $vx->tz = $this->readByte();
            $vx->tw = $this->readByte();
            $vx->r = $this->readByte();
            $vx->g = $this->readByte();
            $vx->b = $this->readByte();
            $vx->a = $this->readByte();
            
            $this->jump($vertexSize - 40); // Skip extra data if present
            $vertices[] = $vx;
        }

        for ($i = 0; $i < $faceCount; $i++) {
            $faces[] = [
                $this->readUInt32LE(),
                $this->readUInt32LE(),
                $this->readUInt32LE(),
            ];
            $this->jump($faceSize - 12);
        }

        return [
            'version' => '2.00',
            'vertices' => $vertices,
            'faces' => $faces
        ];
    }
}