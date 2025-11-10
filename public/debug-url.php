<?php
require_once __DIR__ . '/../bootstrap/app.php';

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>URL Debug</title></head><body>";
echo "<h1>URL Generation Debug</h1>";

echo "<h2>Environment</h2>";
echo "<pre>";
echo "APP_URL from .env: " . env('APP_URL', 'NOT SET') . "\n";
echo "config('app.url'): " . config('app.url') . "\n";
echo "</pre>";

echo "<h2>Generated URLs</h2>";
echo "<pre>";
echo "asset('css/styles.css'): " . asset('css/styles.css') . "\n";
echo "asset('js/bootstrap.min.js'): " . asset('js/bootstrap.min.js') . "\n";
echo "</pre>";

echo "<h2>Server Variables</h2>";
echo "<pre>";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'NOT SET') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NOT SET') . "\n";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "\n";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'NOT SET') . "\n";
echo "</pre>";

echo "<h2>File Check</h2>";
echo "<pre>";
$cssPath = __DIR__ . '/css/styles.css';
echo "CSS file path: $cssPath\n";
echo "CSS exists: " . (file_exists($cssPath) ? 'YES' : 'NO') . "\n";
if (file_exists($cssPath)) {
    echo "CSS size: " . filesize($cssPath) . " bytes\n";
}
echo "</pre>";

echo "<h2>Test Link</h2>";
$url = asset('css/styles.css');
echo "<p>Click to test: <a href='$url' target='_blank'>$url</a></p>";

echo "<h2>HTML Output Test</h2>";
echo "<p>Below is how it appears in HTML:</p>";
echo "<pre>" . htmlspecialchars('<link rel="stylesheet" href="' . asset('css/styles.css') . '">') . "</pre>";

echo "</body></html>";
