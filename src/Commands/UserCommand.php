<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Request;


abstract class UserCommand extends Command
{
    

    protected function isGroupAdministrator($chat_id, $user_id) {
        $result = Request::getChatMember([
            'chat_id' => $chat_id,
            'user_id' => $user_id,
        ]);

        return $result->ok && ($result->result->status == 'creator' || $result->result->status == 'creator');
    }
}
