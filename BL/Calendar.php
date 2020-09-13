<?php

namespace Donicom\EventlistManager\BL;

use DateTime;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Spatie\Emoji\Emoji;

class Calendar
{    
    const DAYS_OF_WEEK = ['L', 'M', 'M' , 'G' , 'V', 'S', 'D'];

    public static function CreateHours() {
        $keyboard = new InlineKeyboard([]);
        $i = 1;
        $row = [];
        while ($i < 25) {
            if(count($row) == 6 ) {
                $keyboard->addRow(...$row);
                $row = [];
            }
            $row[] = ['text' => $i, 'callback_data' => 'MINUTE|' . $i];
            $i++;
        }
        $keyboard->addRow(...$row);
        $keyboard->addRow(
            ['text' => Emoji::CHARACTER_LEFT_ARROW . ' Modifica Data', 'callback_data' => 'BACKTODATE']
        );
        return $keyboard;
    }

    public static function CreateMinutes() {
        $keyboard = new InlineKeyboard([]);
        $i = 0;
        $row = [];
        while ($i < 60) {
            if(count($row) == 4 ) {
                $keyboard->addRow(...$row);
                $row = [];
            }
            $row[] = ['text' => $i, 'callback_data' => 'DESCRIPTION|' . $i];
            $i+=5;
        }
        $keyboard->addRow(...$row);
        $keyboard->addRow(
            ['text' => Emoji::CHARACTER_LEFT_ARROW . ' Modifica Ora', 'callback_data' => 'BACKTOHOUR']
        );
        return $keyboard;
    }

    public static function CreateCalendar($year = null, $month = null) {
        $curYear = $year ? $year : date('Y');
        $curMonth = $month ? $month : date('m');
        
        setlocale(LC_TIME, 'it_IT');
        $firstDayOfMonth = mktime(0,0,0,$curMonth,1,$curYear);
        $numberDays = date('t',$firstDayOfMonth);
        $monthName = strftime('%B',$firstDayOfMonth);   
        $dayOfWeek = strftime('%u',$firstDayOfMonth);
        
        $data_ignore = Helper::create_callback_data("IGNORE", $curYear,$curMonth,0);

        $keyboard = new InlineKeyboard([]);
 
        $keyboard->addRow(
            ['text' => "$monthName $curYear", 'callback_data' =>  $data_ignore]
        );

        $row = [];
        foreach (self::DAYS_OF_WEEK as $value) {
            $row[] = [ 'text' => $value, 'callback_data' =>  $data_ignore];
        }
        $keyboard->addRow(...$row);

        $date_now = (new DateTime())->setTime(0,0);
        $prevButton = true;

        $currentDay = 1;
        $row = [];
        for ($i=1; $i < 8; $i++) { 
            if($i < $dayOfWeek) {
                $row[] = [ 'text' => ' ', 'callback_data' =>  $data_ignore];
            } else {
                if($date_now > new DateTime("$curYear-$curMonth-$currentDay")) {
                    $prevButton = false;
                    $row[] = [ 'text' => ' ', 'callback_data' =>  $data_ignore];
                } else {
                    $row[] = [ 'text' => $currentDay, 'callback_data' => Helper::create_callback_data("HOUR", $curYear, $curMonth, $currentDay) ];
                }
                $currentDay++;
            }
        }
        $keyboard->addRow(...$row);

        $row = [];
        while($currentDay <= $numberDays) {
            if(count($row) == 7) {
                $keyboard->addRow(...$row);
                $row = [];
            }
            if($date_now > new DateTime("$curYear-$curMonth-$currentDay")) {
                $row[] = [ 'text' => ' ', 'callback_data' =>  $data_ignore];
            } else {
                $row[] = [ 'text' => $currentDay, 'callback_data' => Helper::create_callback_data("HOUR", $curYear, $curMonth, $currentDay) ];
            }
            $currentDay++;
        }

        if (count($row) > 0) {
            for ($i= count($row); $i < 7; $i++) {
                $row[] = [ 'text' => ' ', 'callback_data' =>  $data_ignore];
            }
            $keyboard->addRow(...$row);
        }

        if($curMonth == 12) {
            $nextYear  = $curYear + 1;
            $nextMonth = 1;
            $prevYear  = $curYear;
            $prevMonth = $curMonth - 1;
        } else if($curMonth == 1) {
            $nextYear  = $curYear;
            $nextMonth = $curMonth + 1;
            $prevYear  = $curYear - 1;
            $prevMonth = 12;
        } else {
            $nextYear  = $curYear;
            $nextMonth = $curMonth + 1;
            $prevYear  = $curYear;
            $prevMonth = $curMonth - 1;
        }

        $keyboard->addRow(
            $prevButton ? [ 'text' =>  Emoji::CHARACTER_LEFT_ARROW, 'callback_data' => Helper::create_callback_data('SUCCMONTH', $prevYear, $prevMonth , 0) ] : $row[] = [ 'text' => ' ', 'callback_data' =>  $data_ignore],            
            [ 'text' =>  Emoji::CHARACTER_CROSS_MARK, 'callback_data' => 'CANCEL' ],            
            [ 'text' =>  Emoji::CHARACTER_RIGHT_ARROW, 'callback_data' => Helper::create_callback_data('SUCCMONTH', $nextYear, $nextMonth , 0) ]
        );
        
        return $keyboard;
    }
}