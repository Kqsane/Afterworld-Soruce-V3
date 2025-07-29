<?php
// these functions are written mostly by chat gpt.
// i hate my life
// - meditext
// TODO: this is just pure dookie, we should rewrite most of the code here.
// load the swear file and turns into a dictionary array.
function loadSwearWords($filePath) {
    if (!file_exists($filePath)) {
        return [];
    }
    return array_map('trim', file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
}

// reduce repeated characters (e.g., "fucck" → "fuck")
function reduceRepeatedCharacters($input) {
    return preg_replace('/(.)\\1{1,}/', '$1', $input);
}

// normalize text
function normalizeText($input) {
    $input = strtolower($input);
    $input = str_replace(['@', '3', '!', '1', '$', '*', '#'], ['a', 'e', 'i', 'i', 's', '', ''], $input);
    $input = reduceRepeatedCharacters($input);
    return preg_replace('/[^a-z0-9]/', '', $input);
}

// check if text contains bad words (including as substrings)
function containsSwearWords($text, $swearWords) {
    $normalizedText = normalizeText($text);
    foreach ($swearWords as $word) {
        $normalizedWord = normalizeText($word);
        if (strpos($normalizedText, $normalizedWord) !== false) {
            return true;
        }
    }
    return false;
}

// give a tag for the bad words.
function replaceSwearWords($text, $swearWords, $replacement = '#') {
    foreach ($swearWords as $word) {
        $normalizedWord = normalizeText($word);
        $pattern = '/' . preg_quote($normalizedWord, '/') . '/i';
        $text = preg_replace_callback($pattern, function ($matches) use ($replacement) {
            return str_repeat($replacement, strlen($matches[0]));
        }, $text);
    }
    return $text;
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
?>