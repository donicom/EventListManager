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

class ParticipantDB extends DB
{
    /**
     * Initialize conversation table
     */
    public static function initializeEvent()
    {
        if (!defined('TB_EVENT_PARTICIPANTS')) {
            define('TB_EVENT_PARTICIPANTS', self::$table_prefix . 'my_participants');
        }
    }
    
    /**
     * Select a conversation from the DB
     *
     * @param string   $chat_id
     * @param int|null $limit
     *
     * @return array|bool
     * @throws TelegramException
     */
    public static function listParticipants($event_id)
    {
        if (!self::isDbConnected()) {
            return false;
        }
        try {
            $sql = '
              SELECT u.id, CONCAT(u.last_name, \' \', u.first_name) as name
              FROM `' . TB_USER . '` u INNER JOIN `' . TB_EVENT_PARTICIPANTS . '` p ON u.id=p.user_id 
              WHERE p.event_id=:event_id
              ORDER BY name
              ';            
            $sth = self::$pdo->prepare($sql);
            $sth->bindValue(':event_id', $event_id);
            $sth->execute();
            return $sth->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    public static function addParticipant($event_id, $user_id)
    {
        if (!self::isDbConnected()) {
            return false;
        }
        try {
            $sth = self::$pdo->prepare('INSERT INTO `' . TB_EVENT_PARTICIPANTS . '`
                (`user_id`, `event_id`)
                VALUES
                (:user_id, :event_id)'
            );
            $sth->bindValue(':user_id', $user_id);
            $sth->bindValue(':event_id', $event_id);
            $sth->execute();
        } catch (Exception $e) {
            throw new TelegramException($e->getMessage());
        }
    }
    
    /**
     * Insert the conversation in the database
     *
     * @param string $user_id
     * @param string $date
     * @param string $name
     * @param string $description
     *
     * @return bool
     * @throws TelegramException
     */
    public static function removeParticipant($event_id, $user_id)
    {
        if (!self::isDbConnected()) {
            return false;
        }
        try {
            $sth = self::$pdo->prepare('DELETE FROM `' . TB_EVENT_PARTICIPANTS . '`
                WHERE `user_id` = :user_id AND `event_id` = :event_id'
            );
            $sth->bindValue(':user_id', $user_id);
            $sth->bindValue(':event_id', $event_id);
            $sth->execute();
        } catch (Exception $e) {
            throw new TelegramException($e->getMessage());
        }
    }
}
