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

use DateTime;
use Donicom\EventlistManager\BL\Helper;
use Donicom\EventlistManager\Models\EventDB as ModelsEventDB;
use Donicom\EventlistManager\Models\ParticipantDB;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;
use Spatie\Emoji\Emoji;
/**
 * User "/inlinekeyboard" command
 *
 * Display an inline keyboard with a few buttons.
 */
class AddtoeventCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'addtoevent';
    /**
     * @var string
     */
    protected $description = 'Sign up user to event';
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
        $callback_query    = $this->getCallbackQuery();
        $message =  $callback_query->getMessage();
        $chat_id =  $message->getChat()->getId();
        $msg_id =  $message->message_id;
        $user    = $callback_query->getFrom();
        $user_id = $user->getId();
        
        $callback_data = Helper::separate_callback_data($callback_query->getData());
        
        $event_id = $callback_data[1];
        ParticipantDB::addParticipant($event_id, $user_id);

        $data = [
            'chat_id' => $chat_id,
            'message_id' => $msg_id,
            'parse_mode' => 'HTML'
        ];

        $event = ModelsEventDB::getEvent($event_id);

        setlocale(LC_ALL, "ita", "it_IT", "it-IT", "Italian_Standard.1252");
        $data['text'] = "<b>" . (new DateTime($event->date))->format('d/m/Y D H:i') . " - " .  $event->description . "</b>" . PHP_EOL . ENROLLED;

        $users = ParticipantDB::listParticipants($event_id);
        $data['text'] .= PHP_EOL . PHP_EOL . join(
            PHP_EOL, 
            array_map(
                function($u, $i) { 
                    return str_pad($i+1, 2, ' ', STR_PAD_LEFT) . '. ' . $u->name; 
                }, 
                $users,
                array_keys($users)
            )
        );   
        
        $keyboard = new InlineKeyboard([]);
        $keyboard->addRow([ 'text' =>  Emoji::CHARACTER_CROSS_MARK . ' ' . UNREGISTER, 'callback_data' => 'REMOVETOEVENT|' . $event_id  ]);
        $data['reply_markup'] = $keyboard;
        return Request::editMessageText($data);
    }
}
