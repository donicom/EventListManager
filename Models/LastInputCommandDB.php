<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Donicom\EventlistManager\Models;
use Exception;
use Longman\TelegramBot\DB;
use Longman\TelegramBot\Exception\TelegramException;
use PDO;

class LastInputCommandDB extends DB
{
    /**
     * Initialize conversation table
     */
    public static function initializeLastInputCommand()
    {
        if (!defined('TB_LIC')) {
            define('TB_LIC', self::$table_prefix . 'my_last_input_command');
        }
    }
    
    /**
     * Insert last input command of chat
     *
     * @param string $user_id
     * @param string $date
     * @param string $name
     * @param string $description
     *
     * @return bool
     * @throws TelegramException
     */
    public static function addLIC($chat_id, $data)
    {
        if (!self::isDbConnected()) {
            return false;
        }
        try {
            $sth = self::$pdo->prepare('
                SELECT *
                FROM `' . TB_LIC . '`
                WHERE `chat_id` = :chat_id');
            $sth->bindValue(':chat_id', $chat_id);
            $sth->execute();
            $exist = $sth->rowCount() > 0;
            if($exist) {
                $sth = self::$pdo->prepare('
                UPDATE `' . TB_LIC . '`
                SET `command` = :command
                WHERE `chat_id` = :chat_id');
                $sth->bindValue(':command', serialize($data));
                $sth->bindValue(':chat_id', $chat_id);
                return $sth->execute();
            } else {
                $sth = self::$pdo->prepare('INSERT INTO `' . TB_LIC . '`
                (`chat_id`, `command`)
                VALUES
                (:chat_id, :command)
                ');
                $sth->bindValue(':command', serialize($data));
                $sth->bindValue(':chat_id', $chat_id);
                return $sth->execute();
            }
        } catch (Exception $e) {
            throw new TelegramException($e->getMessage());
        }
    }
    
    /**
     * Delete last input command of chat
     *
     * @param array $fields_values
     * @param array $where_fields_values
     *
     * @return bool
     * @throws TelegramException
     */
    public static function getLIC($chat_id)
    {
        if (!self::isDbConnected()) {
            return false;
        }
        try {
            $sth = self::$pdo->prepare('
                SELECT *
                FROM `' . TB_LIC . '`
                WHERE `chat_id` = :chat_id');
            $sth->bindValue(':chat_id', $chat_id);
            $sth->execute();
            $data = $sth->fetchObject();
            return unserialize($data->command);
        } catch (Exception $e) {
            throw new TelegramException($e->getMessage());
        }
    }
}
