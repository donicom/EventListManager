<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Donicom\EventlistManager\BL\Calendar;
use Donicom\EventlistManager\BL\Helper;
use Donicom\EventlistManager\Models\LastInputCommandDB;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;
use Spatie\Emoji\Emoji;

/**
 * User "/survey" command
 *
 * Command that demonstrated the Conversation funtionality in form of a simple survey.
 */
class DescriptionCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'minute';
    /**
     * @var string
     */
    protected $description = 'Inserimento minuti dell\'evento';
    /**
     * @var string
     */
    protected $version = '0.3.0';
    /**
     * @var bool
     */
    protected $need_mysql = true;
    /**
     * @var bool
     */
    protected $private_only = true;
    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $callback_query    = $this->getCallbackQuery();
        $message =  $callback_query->getMessage();
        $chat_id =  $message->getChat()->getId();
        $msg_id =  $message->message_id;
        $user    = $callback_query->getFrom();
        $user_id = $user->getId();

        $callback_data = Helper::separate_callback_data($callback_query->getData());
        
        $data = LastInputCommandDB::getLIC($chat_id);
        $data['Minuti'] = str_pad($callback_data[1], 2, '0', STR_PAD_LEFT);
        LastInputCommandDB::addLIC($chat_id, $data);
        
        $conversation = new Conversation($user_id, $chat_id, $data['Command']);
        
        return Request::editMessageText([
            'chat_id' => $chat_id,
            'message_id' => $msg_id,
            'text' => 'Data: ' . $data['Data'] . ' ' . $data['Ora'] . ':' . $data['Minuti'] . PHP_EOL . 'Inserire la descrizione dell\'evento',
            'reply_markup' => new InlineKeyboard([['text' => Emoji::CHARACTER_LEFT_ARROW . ' Modifica Minuti', 'callback_data' => 'BACKTOMINUTE']])
        ]);
    }
}
