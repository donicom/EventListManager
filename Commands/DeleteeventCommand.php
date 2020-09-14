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
use Donicom\EventlistManager\Models\EventDB;
use Donicom\EventlistManager\Models\LastInputCommandDB;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;
use Spatie\Emoji\Emoji;

/**
 * User "/survey" command
 *
 * Command that demonstrated the Conversation funtionality in form of a simple survey.
 */
class DeleteeventCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'deleteevent';
    /**
     * @var string
     */
    protected $description = 'Delete evento';
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
        $user    = $callback_query->getFrom();
        $user_id = $user->getId();
        $msg_id =  $message->message_id;
        $callback_data = Helper::separate_callback_data($callback_query->getData());
        $event_id = $callback_data[1];
        
        EventDB::deleteEvent($event_id);

        $results = Request::sendToActiveChats(
            'sendMessage',     //callback function to execute (see Request.php methods)
            ['text' => EVENT_DELETED], //Param to evaluate the request
            [
                'groups'      => false,
                'supergroups' => false,
                'channels'    => false,
                'users'       => true,
            ],
            $user_id
        );

        $events = EventDB::listEvents($chat_id);
        $keyboard = new InlineKeyboard([]);
        $new_button = [ 'text' =>  Emoji::CHARACTER_PLUS_SIGN . " " . NEW_EVENT, 'callback_data' => 'NEWEVENT' ];

        $data = [
            'chat_id' => $chat_id,
            'message_id' => $msg_id
        ];

        if(count($events) == 0) {
            $data['text'] = NO_EVENTS;
            if($this->telegram->isAdmin($user_id)) {
                $keyboard->addRow($new_button);
            }
        } else {
            $data['text'] = EVENTS_LIST;
            $buttons = [];
            setlocale(LC_ALL, "ita", "it_IT", "it-IT", "Italian_Standard.1252");
            foreach ($events as $event) {
                $timestamp = strtotime($event['date']);
                $keyboard->addRow([ 'text' => date('d/m/Y D H:i', $timestamp) . ' - ' . $event['description'], 'callback_data' => 'PARTICIPANTS|' . $event['id'] ]);    
            }
            if($this->telegram->isAdmin($user_id)) {
                $keyboard->addRow($new_button);
            }
        }
        $data['reply_markup'] = $keyboard;
        return Request::editMessageText($data);
    }
}
