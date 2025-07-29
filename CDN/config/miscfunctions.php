<?php
use Afterworld\RedisConnection;

// NOTICE: I've rewritten most of the stuff here and re-improved the comments meditext made for better understanding so yea no need to mess with this anymore.
// load the swear file and turns into a dictionary array.
function loadSwearWords(string $filePath): array {
    return file_exists($filePath)
        ? array_filter(array_map('trim', file($filePath)), fn($line) => $line !== '')
        : [];
}

// reduce repeated characters (e.g., "fucck" → "fuck") (bypass detection)
function normalizeText(string $input): string {
    $input = strtolower($input);
    $input = strtr($input, ['@' => 'a', '3' => 'e', '!' => 'i', '1' => 'i', '$' => 's', '*' => '',  '#' => '']);
    $input = preg_replace('/(.)\1+/', '$1', $input);
    return preg_replace('/[^a-z0-9]/', '', $input);
}

// normalize the word(s)
function containsSwearWords(string $input, array $swearWords): bool {
    $normalizedInput = normalizeText($input);
    foreach ($swearWords as $word) {
        if (strpos($normalizedInput, normalizeText($word)) !== false) {
            return true;
        }
    }
    return false;
}

// check if text contains filtered words
function replaceSwearWords(string $input, array $swearWords, string $replacementChar = '#'): string {
    foreach ($swearWords as $word) {
        $normalizedWord = normalizeText($word);
        if ($normalizedWord === '') continue;
        $pattern = '/' . preg_quote($word, '/') . '/i';
        $input = preg_replace_callback($pattern, function ($matches) use ($replacementChar) {
            return str_repeat($replacementChar, strlen($matches[0]));
        }, $input);
    }
    return $input;
}

// written by hadi with the help of stackoverflow
function jobId() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

function rate_limiter($key, $limit, $period) {
    global $user_ip;
    $redis = RedisConnection::connect();

    $dataname = hash('sha256', $key);
    $ip = md5($user_ip);

    $data = [];

    if ($redis->exists($dataname)) {
        $json = $redis->get($dataname);
        $data = json_decode($json, true) ?? [];
    }

    $current_time = time();
    if (isset($data[$ip]) && $current_time - $data[$ip]['last_access_time'] >= $period) {
        $data[$ip]['count'] = 0;
        $data[$ip]['last_access_time'] = $current_time;
    }

    if (isset($data[$ip]) && $data[$ip]['count'] >= $limit) {
        http_response_code(429);
        header('Retry-After: ' . $period - ($current_time - $data[$ip]['last_access_time']));
        header(HEADER_APPLICATION_JSON);
        exit(json_encode([
            "success" => false,
            "error" => "You have been rate limited. Retry in " . $period - ($current_time - $data[$ip]['last_access_time']) . " seconds(s)"
        ]));
    }

    if (!isset($data[$ip])) {
        $data[$ip] = array('count' => 0, 'last_access_time' => 0);
    }
    $data[$ip]['count']++;
    $data[$ip]['last_access_time'] = $current_time;
    $redis->set($dataname, json_encode($data));

    return $period - ($current_time - $data[$ip]['last_access_time']);
}