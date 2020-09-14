<?php

namespace Donicom\EventlistManager\BL;

use Donicom\EventlistManager\Models\UserDB;

class Helper
{
    public static function create_callback_data($action, $year, $month, $day) {
        return join("|",[$action,$year,$month,$day]);
    }

    public static function separate_callback_data($data) {
        return explode('|', $data);
    }

    public static function sendMessage($msg) {
        $users = UserDB::listUsers(947252868);
            $chats = array_map(function ($u) {
                return $u->id;
            }, $users);
        $website="your:bot_api_key";
        $params=[                
            'text'=> $msg,
        ];
        $ch = curl_init($website . '/sendMessage');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        foreach ($chats as $chat_id) {
            $params['chat_id'] = $chat_id;   
            curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
            curl_exec($ch);
        }
        curl_close($ch);
    }
}