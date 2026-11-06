<?php

function scanDirForEmojis($dirPath) {
    $dir = new RecursiveDirectoryIterator($dirPath);
    $iterator = new RecursiveIteratorIterator($dir);
    $emojiRegex = '/[\x{1F000}-\x{1FFFF}\x{2600}-\x{27BF}\x{2B50}\x{FE0F}]/u';

    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $path = $file->getPathname();
            $lines = file($path);
            foreach ($lines as $lineNum => $line) {
                if (preg_match_all($emojiRegex, $line, $matches)) {
                    $emojis = implode(' ', array_unique($matches[0]));
                    echo "$path: line " . ($lineNum + 1) . " [$emojis] -> " . trim($line) . "\n";
                }
            }
        }
    }
}

echo "--- Scanning resources/views ---\n";
scanDirForEmojis('resources/views');

echo "\n--- Scanning app/ ---\n";
scanDirForEmojis('app');
