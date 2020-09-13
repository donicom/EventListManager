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

class EventDB extends DB
{
    /**
     * Initialize conversation table
     */
    public static function initializeEvent()
    {
        if (!defined('TB_EVENT')) {
            define('TB_EVENT', self::$table_prefix . 'my_events');
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
    public static function listEvents($chat_id, $limit = null)
    {
        if (!self::isDbConnected()) {
            return false;
        }
        try {
            $sql = '
              SELECT *
              FROM `' . TB_EVENT . '`
              WHERE `date` >= NOW()
              ORDER BY `date`'
              ;
            if ($limit !== null) {
                $sql .= ' LIMIT :limit';
            }
            $sth = self::$pdo->prepare($sql);
            $sth->bindValue(':chat_id', $chat_id);
            if ($limit !== null) {
                $sth->bindValue(':limit', $limit, PDO::PARAM_INT);
            }
            $sth->execute();
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    public static function getEvent($event_id)
    {
        if (!self::isDbConnected()) {
            return false;
        }
        try {
            $sql = '
              SELECT *
              FROM `' . TB_EVENT . '`
              WHERE `id` = :event_id'
              ;
            $sth = self::$pdo->prepare($sql);
            $sth->bindValue(':event_id', $event_id);
            $sth->execute();
            return $sth->fetch(PDO::FETCH_OBJ);
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
    public static function insertEvent($chat_id, $date, $name, $description)
    {
        if (!self::isDbConnected()) {
            return false;
        }
        try {
            $sth = self::$pdo->prepare('INSERT INTO `' . TB_EVENT . '`
                (`date`, `name`, `description`, `chat_id`)
                VALUES
                (:date, :name, :description, :chat_id)
            ');
            $sth->bindValue(':date', $date);
            $sth->bindValue(':name', $name);
            $sth->bindValue(':description', $description);
            $sth->bindValue(':chat_id', $chat_id);           
            $ok = $sth->execute();
        } catch (Exception $e) {
            throw new TelegramException($e->getMessage());
        }
        return $ok;
    }

    public static function deleteEvent($event_id)
    {
        if (!self::isDbConnected()) {
            return false;
        }
        try {
            $sth = self::$pdo->prepare('DELETE FROM `' . TB_EVENT . '`
                WHERE id = :event_id
            ');
            $sth->bindValue(':event_id', $event_id);
            $ok = $sth->execute();
        } catch (Exception $e) {
            throw new TelegramException($e->getMessage());
        }
    }
    
    /**
     * Update a specific conversation
     *
     * @param array $fields_values
     * @param array $where_fields_values
     *
     * @return bool
     * @throws TelegramException
     */
    public static function updateEvent(array $fields_values, array $where_fields_values)
    {
        // Auto update the update_at field.
        $fields_values['updated_at'] = self::getTimestamp();
        return self::update(TB_EVENT, $fields_values, $where_fields_values);
    }

    public static function listParticipants($event_id)
    {
        if (!self::isDbConnected()) {
            return false;
        }
        try {
            $sql = 'SELECT CONCAT(u.last_name, u.first_name) names
              FROM `' . TB_EVENT_PARTICIPANTS . '` p INNER JOIN `' . TB_USER . '` u ON p.user_id=u.id
              WHERE `p`.`event_id` = :event_id
              ORDER BY names';
            $sth = self::$pdo->prepare($sql);
            $sth->bindValue(':event_id', $event_id);
            $sth->execute();
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new TelegramException($e->getMessage());
        }
        return $ok;
    }
}
