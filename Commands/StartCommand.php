<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Longman\TelegramBot\Commands\SystemCommands;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;

/**
 * Start command
 *
 * Gets executed when a user first starts using the bot.
 */
class StartCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'start';
 
    /**
     * @var string
     */
    protected $description = 'Comando start';
 
    /**
     * @var string
     */
    protected $usage = '/start';
 
    /**
     * @var string
     */
    protected $version = '1.1.0';
 
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
        $name = $message->getFrom()->getFirstName() . ' ' . $message->getFrom()->getLastName();
        $chat_id = $message->getChat()->getId();
        $text    = "Ciao {$name}," . PHP_EOL . "invia il comando /help per visualizzare l'elenco dei comandi!";
        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
        ];
        return Request::sendMessage($data);
    }
}
