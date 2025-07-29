
<?php
if (!class_exists('AssertionError')) {
    class AssertionError extends Exception {}
}

function bufferToString($data) {
    $out = '';
    $length = strlen($data);
    for ($i = 0; $i < $length; $i += 0x8000) {
        $chunk = substr($data, $i, 0x8000);
        $out .= implode('', array_map('chr', array_map('ord', str_split($chunk))));
    }
    return $out;
}

class ByteReader {
    private static $converter = null;
    private $data;
    private $index;
    private $length;
    private $byteOffset;
    private $byteLength;
    
    public static function getConverter() {
        if (self::$converter === null) {
            self::$converter = str_repeat("\0", 8);
        }
        return self::$converter;
    }
    
    public function __construct() {
        $args = func_get_args();
        
        // Handle Uint8Array-like object (simulated as array with data, byteOffset, byteLength properties)
        if (count($args) > 0 && is_array($args[0]) && isset($args[0]['data'])) {
            $args[1] = $args[0]['byteOffset'];
            $args[2] = $args[0]['byteLength'];
            $args[0] = $args[0]['data'];
        }
        
        assert(count($args) > 0 && is_string($args[0]), "buffer is not a string");
        
        $buffer = $args[0];
        $offset = isset($args[1]) ? $args[1] : 0;
        $length = isset($args[2]) ? $args[2] : null;
        
        if ($length !== null) {
            $this->data = substr($buffer, $offset, $length);
            $this->byteOffset = $offset;
            $this->byteLength = $length;
        } else if ($offset > 0) {
            $this->data = substr($buffer, $offset);
            $this->byteOffset = $offset;
            $this->byteLength = strlen($this->data);
        } else {
            $this->data = $buffer;
            $this->byteOffset = 0;
            $this->byteLength = strlen($buffer);
        }
        
        $this->index = 0;
        $this->length = strlen($this->data);
    }
    
    public function SetIndex($n) { 
        $this->index = $n; 
    }
    
    public function GetIndex() { 
        return $this->index; 
    }
    
    public function GetRemaining() { 
        return $this->length - $this->index; 
    }
    
    public function GetLength() { 
        return $this->length; 
    }
    
    public function Jump($n) { 
        $this->index += $n; 
        return $this; // For chaining like in the LZ4Header method
    }
    
    public function Array($n) {
        $result = substr($this->data, $this->index, $n);
        $this->index += $n;
        return $result;
    }
    
    public function Match($match) {
        $index = $this->index;
        
        if (is_string($match)) {
            $matchLength = strlen($match);
            for ($i = 0; $i < $matchLength; $i++) {
                if (ord($match[$i]) !== ord($this->data[$index++] ?? "\0")) {
                    return false;
                }
            }
        } else {
            $matchLength = count($match);
            for ($i = 0; $i < $matchLength; $i++) {
                if ($match[$i] !== ord($this->data[$index++] ?? "\0")) {
                    return false;
                }
            }
        }
        
        $this->index += $matchLength;
        return true;
    }
    
    public function Byte() { 
        return ord($this->data[$this->index++] ?? "\0"); 
    }
    
    public function UInt8() { 
        return ord($this->data[$this->index++] ?? "\0"); 
    }
    
    public function UInt16LE() { 
        $bytes = substr($this->data, $this->index, 2);
        $this->index += 2;
        return unpack('v', $bytes . "\0\0")[1];
    }
    
    public function UInt16BE() { 
        $bytes = substr($this->data, $this->index, 2);
        $this->index += 2;
        return unpack('n', $bytes . "\0\0")[1];
    }
    
    public function UInt32LE() { 
        $bytes = substr($this->data, $this->index, 4);
        $this->index += 4;
        return unpack('V', $bytes . "\0\0\0\0")[1];
    }
    
    public function UInt32BE() { 
        $bytes = substr($this->data, $this->index, 4);
        $this->index += 4;
        return unpack('N', $bytes . "\0\0\0\0")[1];
    }
    
    public function UInt64LE() { 
        $bytes = substr($this->data, $this->index, 8);
        $this->index += 8;
        $padded = $bytes . str_repeat("\0", 8 - strlen($bytes));
        return gmp_import($padded, 1, GMP_LSW_FIRST | GMP_LITTLE_ENDIAN);
    }
    
    public function UInt64BE() { 
        $bytes = substr($this->data, $this->index, 8);
        $this->index += 8;
        $padded = str_repeat("\0", 8 - strlen($bytes)) . $bytes;
        return gmp_import($padded, 1, GMP_MSW_FIRST | GMP_BIG_ENDIAN);
    }
    
    public function Int8() { 
        $value = ord($this->data[$this->index++] ?? "\0");
        return $value > 127 ? $value - 256 : $value;
    }
    
    public function Int16LE() { 
        $bytes = substr($this->data, $this->index, 2);
        $this->index += 2;
        $value = unpack('v', $bytes . "\0\0")[1];
        return $value > 32767 ? $value - 65536 : $value;
    }
    
    public function Int16BE() { 
        $bytes = substr($this->data, $this->index, 2);
        $this->index += 2;
        $value = unpack('n', $bytes . "\0\0")[1];
        return $value > 32767 ? $value - 65536 : $value;
    }
    
    public function Int32LE() { 
        $bytes = substr($this->data, $this->index, 4);
        $this->index += 4;
        return unpack('l', pack('V', unpack('V', $bytes . "\0\0\0\0")[1]))[1];
    }
    
    public function Int32BE() { 
        $bytes = substr($this->data, $this->index, 4);
        $this->index += 4;
        return unpack('l', pack('N', unpack('N', $bytes . "\0\0\0\0")[1]))[1];
    }
    
    public function Int64LE() { 
        $bytes = substr($this->data, $this->index, 8);
        $this->index += 8;
        $padded = $bytes . str_repeat("\0", 8 - strlen($bytes));
        $unsigned = gmp_import($padded, 1, GMP_LSW_FIRST | GMP_LITTLE_ENDIAN);
        $max = gmp_pow(2, 63);
        return gmp_cmp($unsigned, $max) >= 0 ? gmp_sub($unsigned, gmp_pow(2, 64)) : $unsigned;
    }
    
    public function Int64BE() { 
        $bytes = substr($this->data, $this->index, 8);
        $this->index += 8;
        $padded = str_repeat("\0", 8 - strlen($bytes)) . $bytes;
        $unsigned = gmp_import($padded, 1, GMP_MSW_FIRST | GMP_BIG_ENDIAN);
        $max = gmp_pow(2, 63);
        return gmp_cmp($unsigned, $max) >= 0 ? gmp_sub($unsigned, gmp_pow(2, 64)) : $unsigned;
    }
    
    public function FloatLE() { 
        $bytes = substr($this->data, $this->index, 4);
        $this->index += 4;
        return unpack('g', $bytes . "\0\0\0\0")[1];
    }
    
    public function FloatBE() { 
        $bytes = substr($this->data, $this->index, 4);
        $this->index += 4;
        return unpack('G', $bytes . "\0\0\0\0")[1];
    }
    
    public function DoubleLE() { 
        $bytes = substr($this->data, $this->index, 8);
        $this->index += 8;
        return unpack('e', $bytes . str_repeat("\0", 8 - strlen($bytes)))[1];
    }
    
    public function DoubleBE() { 
        $bytes = substr($this->data, $this->index, 8);
        $this->index += 8;
        return unpack('E', $bytes . str_repeat("\0", 8 - strlen($bytes)))[1];
    }
    
    public function String($n) { 
        return bufferToString($this->Array($n)); 
    }
    
    // LZ4
    
    public function LZ4Header() {
        $comLength = $this->UInt32LE();
        $decomLength = $this->UInt32LE();
        $checksum = $this->Jump(4); // always 0
        
        return [$comLength, $decomLength];
    }
    
    public function LZ4($buffer = null) {
        list($comLength, $decomLength) = $this->LZ4Header();
        
        if ($comLength === 0) {
            assert($this->GetRemaining() >= $decomLength, "[ByteReader.LZ4Header] unexpected eof");
            return $this->Array($decomLength);
        }
        
        assert($this->GetRemaining() >= $comLength, "[ByteReader.LZ4Header] unexpected eof");
        
        if (!$buffer || strlen($buffer) < $decomLength) {
            $buffer = str_repeat("\0", $decomLength);
        }
        
        $data = strlen($buffer) > $decomLength ? substr($buffer, 0, $decomLength) : $buffer;
        $endIndex = $this->index + $comLength;
        
        $lastByte = 0;
        $index = 0;
        
        while ($index < $decomLength) {
            $token = ord($this->data[$this->index++] ?? "\0");
            $literalLength = $token >> 4;
            
            if ($literalLength === 0xF) {
                do {
                    $lastByte = ord($this->data[$this->index++] ?? "\0");
                    $literalLength += $lastByte;
                } while ($lastByte === 0xFF);
            }
            
            assert($this->index + $literalLength <= $endIndex, "[ByteReader.LZ4] unexpected eof");
            
            for ($i = 0; $i < $literalLength; $i++) {
                $data[$index++] = $this->data[$this->index++];
            }
            
            if ($index < $decomLength) {
                $matchIndex = $index - $this->UInt16LE();
                $matchLength = $token & 0xF;
                
                if ($matchLength === 0xF) {
                    do {
                        $lastByte = ord($this->data[$this->index++] ?? "\0");
                        $matchLength += $lastByte;
                    } while ($lastByte === 0xFF);
                }
                
                $matchLength += 4; // Minimum match is 4 bytes, so 4 is added to the length
                
                assert($index + $matchLength <= $decomLength, "[ByteReader.LZ4] output size mismatch");
                
                for ($i = 0; $i < $matchLength; $i++) {
                    $data[$index++] = $data[$matchIndex++];
                }
            }
        }
        
        assert($this->index === $endIndex, "[ByteReader.LZ4] input size mismatch");
        assert($index === $decomLength, "[ByteReader.LZ4] output size mismatch");
        
        return $data;
    }
    
    // RBX
    
    public function RBXFloat() {
        $uint32 = $this->UInt32LE();
        $converted = ($uint32 << 31) | ($uint32 >> 1);
        return unpack('g', pack('V', $converted))[1];
    }
    
    public function RBXInterleavedUint16($count, &$result) {
        for ($i = 0; $i < $count; $i++) {
            $result[$i] = 
                (ord($this->data[$this->index + $i + $count * 0] ?? "\0") << 8) |
                ord($this->data[$this->index + $i + $count * 1] ?? "\0");
        }
        
        $this->Jump($count * 2);
        return $result;
    }
    
    public function RBXInterleavedUint32($count, &$result) {
        for ($i = 0; $i < $count; $i++) {
            $result[$i] = 
                (ord($this->data[$this->index + $i + $count * 0] ?? "\0") << 24) |
                (ord($this->data[$this->index + $i + $count * 1] ?? "\0") << 16) |
                (ord($this->data[$this->index + $i + $count * 2] ?? "\0") << 8) |
                ord($this->data[$this->index + $i + $count * 3] ?? "\0");
        }
        
        $this->Jump($count * 4);
        return $result;
    }
    
    public function RBXInterleavedUint64($count, &$result) {
        for ($i = 0; $i < $count; $i++) {
            $high = 
                (ord($this->data[$this->index + $i + $count * 0] ?? "\0") << 24) |
                (ord($this->data[$this->index + $i + $count * 1] ?? "\0") << 16) |
                (ord($this->data[$this->index + $i + $count * 2] ?? "\0") << 8) |
                ord($this->data[$this->index + $i + $count * 3] ?? "\0");
            $low = 
                (ord($this->data[$this->index + $i + $count * 4] ?? "\0") << 24) |
                (ord($this->data[$this->index + $i + $count * 5] ?? "\0") << 16) |
                (ord($this->data[$this->index + $i + $count * 6] ?? "\0") << 8) |
                ord($this->data[$this->index + $i + $count * 7] ?? "\0");
            
            $result[$i] = gmp_add(gmp_mul(gmp_init($high), gmp_pow(2, 32)), gmp_init($low));
        }
        
        $this->Jump($count * 8);
        return $result;
    }
    
    public function RBXInterleavedInt16($count, &$result) {
        $this->RBXInterleavedUint16($count, $result);
        
        for ($i = 0; $i < $count; $i++) {
            $value = $result[$i];
            $result[$i] = ($value % 2 ? -(intval($value) + 1) / 2 : intval($value) / 2);
        }
        
        return $result;
    }
    
    public function RBXInterleavedInt32($count, &$result) {
        $this->RBXInterleavedUint32($count, $result);
        
        for ($i = 0; $i < $count; $i++) {
            $value = $result[$i];
            $result[$i] = ($value % 2 ? -(intval($value) + 1) / 2 : intval($value) / 2);
        }
        
        return $result;
    }
    
    public function RBXInterleavedInt64($count, &$result) {
        $this->RBXInterleavedUint64($count, $result);
        
        for ($i = 0; $i < $count; $i++) {
            $value = $result[$i];
            $isOdd = gmp_cmp(gmp_mod($value, 2), 0) !== 0;
            $result[$i] = $isOdd ? 
                gmp_div(gmp_neg(gmp_add($value, 1)), 2) : 
                gmp_div($value, 2);
        }
        
        return $result;
    }
    
    public function RBXInterleavedFloat($count, &$result) {
        $this->RBXInterleavedUint32($count, $result);
        
        for ($i = 0; $i < $count; $i++) {
            $uint32 = $result[$i];
            $converted = ($uint32 << 31) | ($uint32 >> 1);
            $result[$i] = unpack('g', pack('V', $converted))[1];
        }
        
        return $result;
    }
    
    // Peek methods implementation
    public function PeekByte() {
        $index = $this->GetIndex();
        $result = $this->Byte();
        $this->SetIndex($index);
        return $result;
    }
    
    public function PeekUInt8() {
        $index = $this->GetIndex();
        $result = $this->UInt8();
        $this->SetIndex($index);
        return $result;
    }
    
    public function PeekUInt16LE() {
        $index = $this->GetIndex();
        $result = $this->UInt16LE();
        $this->SetIndex($index);
        return $result;
    }
    
    public function PeekUInt16BE() {
        $index = $this->GetIndex();
        $result = $this->UInt16BE();
        $this->SetIndex($index);
        return $result;
    }
    
    public function PeekUInt32LE() {
        $index = $this->GetIndex();
        $result = $this->UInt32LE();
        $this->SetIndex($index);
        return $result;
    }
    
    public function PeekUInt32BE() {
        $index = $this->GetIndex();
        $result = $this->UInt32BE();
        $this->SetIndex($index);
        return $result;
    }
    
    public function PeekFloatLE() {
        $index = $this->GetIndex();
        $result = $this->FloatLE();
        $this->SetIndex($index);
        return $result;
    }
    
    public function PeekFloatBE() {
        $index = $this->GetIndex();
        $result = $this->FloatBE();
        $this->SetIndex($index);
        return $result;
    }
    
    public function PeekDoubleLE() {
        $index = $this->GetIndex();
        $result = $this->DoubleLE();
        $this->SetIndex($index);
        return $result;
    }
    
    public function PeekDoubleBE() {
        $index = $this->GetIndex();
        $result = $this->DoubleBE();
        $this->SetIndex($index);
        return $result;
    }
    
    public function PeekArray($n) {
        $index = $this->GetIndex();
        $result = $this->Array($n);
        $this->SetIndex($index);
        return $result;
    }
    
    public function PeekString($n) {
        $index = $this->GetIndex();
        $result = $this->String($n);
        $this->SetIndex($index);
        return $result;
    }
}

?>

