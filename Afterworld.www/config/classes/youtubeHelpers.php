<?php
function getStreamUrlFromDb($streamId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT stream_url FROM streams WHERE id = :id");
    $stmt->execute(['id' => $streamId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['stream_url'] ?? null;
}

function scrapeYoutubeLivestream($channelUrl) {
    $context = stream_context_create([
        'http' => ['user_agent' => 'Mozilla/5.0']
    ]);

    $liveCheckUrl = rtrim($channelUrl, '/') . '/live';
    $html = @file_get_contents($liveCheckUrl, false, $context);
    if (!$html) return ['error' => 'Failed to load channel page'];

    if (preg_match('/<link rel="canonical" href="(https:\/\/www\.youtube\.com\/watch\?v=.+?)"/', $html, $match)) {
        $videoUrl = $match[1];
        $videoHtml = @file_get_contents($videoUrl, false, $context);

        preg_match('/<meta property="og:title" content="(.*?)"/', $videoHtml, $titleMatch);
        preg_match('/<meta property="og:image" content="(.*?)"/', $videoHtml, $thumbMatch);
        preg_match('/<link itemprop="name" content="(.*?)"/', $videoHtml, $channelMatch);
        preg_match('/"startDate":"(.*?)"/', $videoHtml, $startTimeMatch);

        return [
            'isLive' => true,
            'videoUrl' => $videoUrl,
            'title' => $titleMatch[1] ?? null,
            'thumbnail' => $thumbMatch[1] ?? null,
            'channelTitle' => $channelMatch[1] ?? 'Unknown YouTuber',
            'started_at' => isset($startTimeMatch[1]) ? date('M d, Y H:i', strtotime($startTimeMatch[1])) : 'Unknown start time',
            'viewers' => 'Live'
        ];
    }

    return ['isLive' => false];
}