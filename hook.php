<?php

/**
 * README
 * This configuration file is intended to run the bot with the webhook method.
 * Uncommented parameters must be filled
 *
 * Please note that if you open this file with your browser you'll get the "Input is empty!" Exception.
 * This is a normal behaviour because this address has to be reached only by the Telegram servers.
 */
// Load composer
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/lang/en.php';
// Add you bot's API key and name
$bot_api_key  = 'your:bot_api_key';
$bot_username = 'username_bot';
// Define all IDs of admin users in this array (leave as empty array if not used)
$admin_users = [
];
// Define all paths for your custom commands in this array (leave as empty array if not used)
$commands_paths = [
    __DIR__ . '/Commands/',
];
// Enter your MySQL database credentials
$mysql_credentials = [
    'host'     => 'host',
    'user'     => 'username',
    'password' => 'password',
    'database' => 'db_name',
];

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);
    
    // Add commands paths containing your custom commands
    $telegram->addCommandsPaths($commands_paths);
    
    // Enable admin users
    $telegram->enableAdmins($admin_users);
    
    // Enable MySQL
    $telegram->enableMySql($mysql_credentials);
    
    // Here you can set some command specific parameters
    // e.g. Google geocode/timezone api key for /date command
    //$telegram->setCommandConfig('date', ['google_api_key' => 'your_google_api_key_here']);
    // Requests Limiter (tries to prevent reaching Telegram API limits)
    $telegram->enableLimiter();
    // Handle telegram webhook request
    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    //echo $e;
    // Log telegram errors
    Longman\TelegramBot\TelegramLog::error($e);
} catch (Longman\TelegramBot\Exception\TelegramLogException $e) {
    // Silence is golden!
    // Uncomment this to catch log initialisation errors
    //echo $e;
}
