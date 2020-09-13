<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Longman\TelegramBot\Commands\AdminCommands;
use Longman\TelegramBot\Commands\AdminCommand;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;
/**
 * Admin "/sendtoall" command
 */
class SendtoallCommand extends AdminCommand
{
    /**
     * @var string
     */
    protected $name = 'sendtoall';
    /**
     * @var string
     */
    protected $description = 'Invia messaggio a tutti gli iscritti al bot';
    /**
     * @var string
     */
    protected $usage = '/sendtoall <messaggio>';
    /**
     * @var string
     */
    protected $version = '1.5.0';
    /**
     * @var bool
     */
    protected $need_mysql = true;
    /**
     * Execute command
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $text = $this->getMessage()->getText(true);
        $user_id = $this->getMessage()->getFrom()->getId();
        if ($text === '') {
            return $this->replyToChat('Uso: ' . $this->getUsage());
        }
        /** @var ServerResponse[] $results */
        $results = Request::sendToActiveChats(
            'sendMessage',     //callback function to execute (see Request.php methods)
            ['text' => $text], //Param to evaluate the request
            [
                'groups'      => false,
                'supergroups' => false,
                'channels'    => false,
                'users'       => true,
            ],
            $user_id
        );
        if (empty($results)) {
            return $this->replyToChat('Nessun utente iscritto.');
        }
        return $this->replyToChat('Il mesaggio è stato inviato');
    }
}
