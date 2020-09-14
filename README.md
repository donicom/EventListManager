# EventListManager
----
Telegram Bot for managing Events written in PHP using [php-telegram-bot/core](https://github.com/php-telegram-bot/core) and include an inline calendar. The data of events are stored in a MySql Database.

# Configuration
----
Create the database schema defined in the file database.sql.

Set in the file hook.php your bot data, list of admin users and the MySql database connection data. 
```
$bot_api_key  = 'your:bot_api_key';
$bot_username = 'username_bot';

$admin_users = [
    user_id
];

$mysql_credentials = [
    'host'     => 'host',
    'user'     => 'username',
    'password' => 'password',
    'database' => 'db_name',
];
```

This bot handle update using Webhook, in order to set Webhook open the file set.php in a browser after set your bot data and hook url.  
```
$bot_api_key  = 'your:bot_api_key';
$bot_username = 'username_bot';
$hook_url     = 'https://your-domain/path/to/hook.php'; 
```
Set your bot api key in the file /BL/Helper.php to use the `/sendtoall` admin command . 
```
$website="your:bot_api_key";
```

# Usage
----
To use the Bot you have to begin a chat with the bot that responde with the available commands list.
The users wich id is present in the `$admin_user` array can manage the events. To create a new one you have to insert the date, time and description of the event. When an event is created, an alert is sent to all users that have opened a chat with the bot.

# License
----
MIT
