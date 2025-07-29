<?php
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

function uploadToR2($filePath, $r2Key) {
    $bucket = 'aftwld-cdn';
    $s3 = new S3Client([
        'region' => 'auto',
        'version' => 'latest',
        'endpoint' => 'https://00e5a66bd1380ab5080c21d497e0a5bd.r2.cloudflarestorage.com',
        'credentials' => [
            'key' => '3de2bcb38e9630d92d2868f541e988c6',
            'secret' => '68f5ea918afa10a8825f2da64a2547a121aecce245f12f6ca7b5ee8cefee748d',
        ],
    ]);
    try {
        $result = $s3->putObject([
            'Bucket' => $bucket,
            'Key' => $r2Key,
            'SourceFile' => $filePath,
            'ACL' => 'public-read',
            'ContentType' => 'image/png'
        ]);
        return $result['ObjectURL'];
    } catch (AwsException $e) {
        error_log("upload to R2 failed because " . $e->getMessage());
        return false;
    }
}