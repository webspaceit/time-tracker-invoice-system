<?php
$path = $argv[1] ?? __DIR__ . '/.env';
$content = file_get_contents($path);
$lines = file($path);
foreach ($lines as $i => $line) {
    if (strpos($line, 'VITE_APP_NAME') !== false) {
        $lines[$i] = 'VITE_APP_NAME="${APP_NAME}"' . PHP_EOL;
        break;
    }
}
$hasWebhook = false;
foreach ($lines as $i => $line) {
    if (strpos($line, 'WEBHOOK_SECRET') !== false) {
        $hasWebhook = true;
        break;
    }
}
if (!$hasWebhook) {
    $lines[] = 'WEBHOOK_SECRET=a888a62b50d76668db363b362d3771733361cff1c45b003e8ec935a566e77327' . PHP_EOL;
}
file_put_contents($path, implode('', $lines));
echo "Done. VITE_APP_NAME and WEBHOOK_SECRET OK\n";
