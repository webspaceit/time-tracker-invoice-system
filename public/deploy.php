<?php

/**
 * GitHub Webhook Auto-Deploy
 * 
 * Configure in GitHub: Settings > Webhooks > Add webhook
 * Payload URL: https://lists.wilderness-explorers.com/deploy.php
 * Content type: application/json
 * Secret: set WEBHOOK_SECRET below
 * Events: Just the push event
 */

$secret = getenv('WEBHOOK_SECRET') ?: 'change-this-secret';

// Verify request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['status' => 'error', 'message' => 'Method not allowed']));
}

// Verify signature
$headers = getallheaders();
$signature = $headers['X-Hub-Signature-256'] ?? '';
$payload = file_get_contents('php://input');
$expected = 'sha256=' . hash_hmac('sha256', $payload, $secret);

if (!hash_equals($expected, $signature)) {
    http_response_code(401);
    die(json_encode(['status' => 'error', 'message' => 'Invalid signature']));
}

// Parse the push event
$data = json_decode($payload, true);
$branch = basename($data['ref'] ?? '');

if ($branch !== 'master') {
    die(json_encode(['status' => 'skipped', 'message' => "Push to '$branch' ignored (only master triggers deploy)"]));
}

$projectDir = __DIR__;
$output = [];
$returnVar = 0;

$commands = [
    "cd $projectDir && git pull 2>&1",
    "cd $projectDir && /usr/local/bin/php /usr/local/bin/composer install --no-interaction --prefer-dist 2>&1",
    "cd $projectDir && /usr/local/bin/php artisan optimize:clear 2>&1",
    "cd $projectDir && /usr/local/bin/php artisan optimize 2>&1",
];

$results = [];
foreach ($commands as $cmd) {
    $cmdOutput = [];
    exec($cmd . ' 2>&1', $cmdOutput, $cmdReturn);
    $results[] = [
        'command' => $cmd,
        'output' => implode("\n", $cmdOutput),
        'return_code' => $cmdReturn,
    ];
    $returnVar = $cmdReturn ?: $returnVar;
}

$status = $returnVar === 0 ? 'success' : 'error';
echo json_encode([
    'status' => $status,
    'results' => $results,
], JSON_PRETTY_PRINT);
