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
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;
use Spatie\Emoji\Emoji;
/**
 * User "/inlinekeyboard" command
 *
 * Display an inline keyboard with a few buttons.
 */
class ParticipantsCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'participants';
    /**
     * @var string
     */
    protected $description = 'Elenca i partecipanti di un evento';
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
        $user    = $callback_query->getFrom();
        $user_id = $user->getId();
        
        $callback_data = Helper::separate_callback_data($callback_query->getData());
        
        $event_id = $callback_data[1];
        $event = ModelsEventDB::getEvent($event_id);

        $data = [
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML'
        ];

        setlocale(LC_ALL, "ita", "it_IT", "it-IT", "Italian_Standard.1252");
        $data['text'] = "<b>" . (new DateTime($event->date))->format('d/m/Y D H:i') . " - " .  $event->description . "</b>" . PHP_EOL . "Elenco partecipanti:";

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
        if($this->telegram->isAdmin($user_id)) {
            $keyboard->addRow(
                [ 'text' =>  Emoji::CHARACTER_PENCIL . "  Modifica evento", 'callback_data' => 'MODIFYEVENT|' . $event_id  ],
                [ 'text' =>  Emoji::CHARACTER_CROSS_MARK . "  Elimina evento", 'callback_data' => 'CONFIRMDELETEEVENT|' . $event_id  ]
            );
        } else {

            if(count(array_filter($users, function($v, $k) use ($user_id) {
                return $v->id == $user_id;
            }, ARRAY_FILTER_USE_BOTH)) == 0) {
                $keyboard->addRow(
                    [ 'text' =>  Emoji::CHARACTER_PLUS_SIGN . " Partecipa", 'callback_data' => 'ADDTOEVENT|' . $event_id  ],
                );
            } else {
                $keyboard->addRow(
                    [ 'text' =>  Emoji::CHARACTER_CROSS_MARK . " Annulla partecipazione", 'callback_data' => 'REMOVETOEVENT|' . $event_id  ]
                );
            }
        }

        $data['reply_markup'] = $keyboard;
        return Request::sendMessage($data);
    }
}
