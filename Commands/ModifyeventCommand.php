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
use Longman\TelegramBot\Request;

/**
 * User "/survey" command
 *
 * Command that demonstrated the Conversation funtionality in form of a simple survey.
 */
class ModifyeventCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'modifyevent';
    /**
     * @var string
     */
    protected $description = 'Modifica evento';
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
        $callback_data = Helper::separate_callback_data($callback_query->getData());
        $event_id = $callback_data[1];
        LastInputCommandDB::addLIC($chat_id, ['Command' =>  'updateevent', 'EventId' => $event_id]);
        return Request::sendMessage([
            'chat_id' => $chat_id,
            'text' => 'Inserisci la data dell\'evento:',
            'reply_markup' => Calendar::CreateCalendar()
        ]);
    }
}
