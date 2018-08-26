<?php
/**
 * Created by PhpStorm.
 * User: khaled
 * Date: 8/3/18
 * Time: 6:36 PM
 */

namespace Jkhaled\Expedia;


class AllowedType
{

    public static function AllowedDate($value){
        $date_format_pattern = '/[0-9]{2}\/[0-9]{2}\/20[0-9]{2}/';
        return preg_match($date_format_pattern, $value);
    }

    public static function AllowedRoom(array $rooms)
    {
        foreach ($rooms as $room) {
            if (!isset($room['adult']))
                return false;
            if (isset($room['child']) && !is_array($room['child']))
                return false;
        }
        return true;
    }

}