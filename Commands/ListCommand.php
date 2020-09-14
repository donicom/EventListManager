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

use Donicom\EventlistManager\Models\EventDB as ModelsEventDB;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\EventDB;
use Longman\TelegramBot\Request;
use Spatie\Emoji\Emoji;
/**
 * User "/inlinekeyboard" command
 *
 * Display an inline keyboard with a few buttons.
 */
class ListCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'list';
    /**
     * @var string
     */
    protected $description = 'List all events';
    /**
     * @var string
     */
    protected $usage = '/list';
    /**
     * @var string
     */
    protected $version = '0.1.0';
    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        // $this->deletLastInputCommand();
        $chat_id = $this->getMessage()->getChat()->getId();
        $user_id = $this->getMessage()->getFrom()->getId();
        $events = ModelsEventDB::listEvents($chat_id);
        $keyboard = new InlineKeyboard([]);
        $new_button = [ 'text' =>  Emoji::CHARACTER_PLUS_SIGN . " " . NEW_EVENT, 'callback_data' => 'NEWEVENT' ];

        $data = [
            'chat_id' => $chat_id
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
        return Request::sendMessage($data);
    }
}
