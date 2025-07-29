<?php
function getLiveViewers(string $url): string {
    if (strpos($url, 'twitch.tv') === false) {
        return 'Unknown stream platform';
    }

    if (!preg_match('/twitch\.tv\/([^\/\?]+)/', $url, $matches)) {
        return 'Invalid Twitch URL';
    }

    $channel = $matches[1];
    $clientId = 'b0k582ktre9nq1vhdbb2s9h1hoztoc';
    $accessToken = 'gf2w003el5hfp2qrstleg84n9w50av';

    $headers = [
        "Client-ID: $clientId",
        "Authorization: Bearer $accessToken"
    ];

    $ch = curl_init("https://api.twitch.tv/helix/streams?user_login={$channel}");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 5
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$response || $httpCode !== 200) {
        return 'Error fetching data';
    }

    $data = json_decode($response, true);

    if (!empty($data['data'][0]['viewer_count'])) {
        return (int)$data['data'][0]['viewer_count'] . ' viewers';
    }

    return 'Offline';
}

function getTwitchDisplayName(string $url): string {
    if (!preg_match('/twitch\.tv\/([^\/\?]+)/', $url, $matches)) {
        return 'Invalid Twitch URL';
    }

    $channel = $matches[1];
    $clientId = 'b0k582ktre9nq1vhdbb2s9h1hoztoc';
    $accessToken = 'gf2w003el5hfp2qrstleg84n9w50av';

    $headers = [
        "Client-ID: $clientId",
        "Authorization: Bearer $accessToken"
    ];

    $ch = curl_init("https://api.twitch.tv/helix/users?login={$channel}");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 5
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$response || $httpCode !== 200) {
        return 'Guest';
    }

    $data = json_decode($response, true);

    if (!empty($data['data'][0]['display_name'])) {
        return $data['data'][0]['display_name'];
    }

    return 'Guest';
}

function getTwitchStartTime(string $url): string {
    if (empty($url)) {
        return 'Unknown';
    }

    if (preg_match('/twitch\.tv\/([^\/\?]+)/', $url, $matches)) {
        $channel = $matches[1];
        $clientId = 'b0k582ktre9nq1vhdbb2s9h1hoztoc';
        $accessToken = 'gf2w003el5hfp2qrstleg84n9w50av';

        $headers = [
            "Client-ID: $clientId",
            "Authorization: Bearer $accessToken"
        ];

        $ch = curl_init("https://api.twitch.tv/helix/streams?user_login=$channel");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        if (!empty($data['data'][0]['started_at'])) {
            return date('F j, Y, g:i a', strtotime($data['data'][0]['started_at']));
        }
        return 'Stream not started';
    }

    return 'Invalid Twitch URL';
}

function getTwitchStreamTitle(string $url, string $fallbackTitle): string {
    if (preg_match('/twitch\.tv\/([^\/\?]+)/', $url, $matches)) {
        $channel = $matches[1];
        $clientId = 'b0k582ktre9nq1vhdbb2s9h1hoztoc';
        $accessToken = 'gf2w003el5hfp2qrstleg84n9w50av';

        $headers = [
            "Client-ID: $clientId",
            "Authorization: Bearer $accessToken"
        ];

        $ch = curl_init("https://api.twitch.tv/helix/streams?user_login=$channel");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        if (!empty($data['data'][0]['title'])) {
            return $data['data'][0]['title'];
        }
    }

    return $fallbackTitle;
}

function getTwitchStreamDescription($streamUrl, $fallback = null) {
    if (!preg_match('/twitch\.tv\/([^\/\?]+)/', $streamUrl, $matches)) {
        return $fallback;
    }

    $channel = $matches[1];
    $clientId = 'b0k582ktre9nq1vhdbb2s9h1hoztoc';
    $accessToken = 'gf2w003el5hfp2qrstleg84n9w50av';

    $headers = [
        "Client-ID: $clientId",
        "Authorization: Bearer $accessToken"
    ];

    $ch = curl_init("https://api.twitch.tv/helix/streams?user_login=" . urlencode($channel));
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    return $data['data'][0]['title'] ?? $fallback;
}
