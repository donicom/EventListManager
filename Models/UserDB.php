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

class UserDB extends DB
{
    
    /**
     * Select a conversation from the DB
     *
     * @param string   $chat_id
     * @param int|null $limit
     *
     * @return array|bool
     * @throws TelegramException
     */
    public static function listUsers($admin_id)
    {
        if (!self::isDbConnected()) {
            return false;
        }
        try {
            $sql = '
              SELECT *
              FROM `' . TB_USER . '` u
              WHERE u.id<>:admin_id and is_bot=0
              ';            
            $sth = self::$pdo->prepare($sql);
            $sth->bindValue(':admin_id', $admin_id);
            $sth->execute();
            return $sth->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            throw new TelegramException($e->getMessage());
        }
    }

}
