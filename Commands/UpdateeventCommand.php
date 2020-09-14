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

use Donicom\EventlistManager\BL\Helper;
use Donicom\EventlistManager\Models\EventDB;
use Donicom\EventlistManager\Models\LastInputCommandDB;
use Donicom\EventlistManager\Models\UserDB;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Commands\UserCommands\ListCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\User;
use Longman\TelegramBot\Request;

/**
 * User "/survey" command
 *
 * Command that demonstrated the Conversation funtionality in form of a simple survey.
 */
class UpdateeventCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'updateevent';
    /**
     * @var string
     */
    protected $description = 'Save event data';
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
        $message = $this->getMessage();
        $chat    = $message->getChat();
        $user    = $message->getFrom();
        $text    = trim($message->getText(true));
        if ($text == '') {
            return Request::emptyResponse();
        }
        
        $chat_id = $chat->getId();
        $user_id = $user->getId();
        
        $data = LastInputCommandDB::getLIC($chat_id);
        $d = explode("/", $data['Date']);

        if (EventDB::updateEvent([
                'chat_id' => $chat_id,
                'date' => $d[2] . '-' . $d[1] . '-' . $d[0] . ' ' . $data['Hour'] . ':' . $data['Minute'] . ':00',
                'name' => '',
                'description' => $text
            ],
            [
                'id' => $data['EventId']
            ]
        )) {
            $this->conversation = new Conversation($user_id, $chat_id, $this->getName());
            $this->conversation->stop();

            $results = Request::sendToActiveChats(
                'sendMessage',     //callback function to execute (see Request.php methods)
                ['text' => EVENT_UPDATED], //Param to evaluate the request
                [
                    'groups'      => false,
                    'supergroups' => false,
                    'channels'    => false,
                    'users'       => true,
                ],
                $user_id
            );

            $list = new ListCommand($this->telegram, $this->update);
            return $list->execute();
        }
        return Request::emptyResponse();
    }
}
