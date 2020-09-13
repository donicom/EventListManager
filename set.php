<?php

/**
 * README
 * This file is intended to set the webhook.
 * Uncommented parameters must be filled
 */

// Load composer
require_once __DIR__ . '/vendor/autoload.php';

// Add you bot's API key and name
$bot_api_key  = '935812130:AAEUVIH4xDfOQJPv4mhUx3PBjj-Nqw4lBBE';
$bot_username = '@nemttbot';

// Define the URL to your hook.php file
// $hook_url     = 'https://249162e1.ngrok.io/eventlistmanager/hook.php';
$hook_url     = 'https://eventlistmanager.altervista.org/k1sbr9J0pbP3kyos42rB/hook.php';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Set webhook
    $result = $telegram->setWebhook($hook_url);

    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e->getMessage();
}
