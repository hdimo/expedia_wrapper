<?php
/**
 * Created by PhpStorm.
 * User: khaled
 * Date: 4/9/18
 * Time: 11:23 AM
 */

namespace Jkhaled\Expedia\Request;


trait RoomTrait
{
    /**
     * get number of rooms with occupancies
     * @example
     *  rooms =
     * [
     *     [
     *         adult => 1,
     *         child => [],
     *     ],
     *     [
     *         adult => number +1
     *         child = [2, 5],
     *     ],
     * ]
     *
     * @param array $rooms
     * @return string
     */
    public function getRooms(array $rooms) :string
    {
        $xml="";
        foreach ($rooms as $room){
            if(!is_array($room))
                throw new \InvalidArgumentException("room must be array");

            $xml .= "<Room>";
            $xml .= "<numberOfAdults>{$room['adult']}</numberOfAdults>";

            $child = !isset($room['child']) ? 0 : count($room['child']);
            $xml .= "<numberOfChildren>{$child}</numberOfChildren>";
            if($child > 0) {
                foreach ($room['child'] as $childAge) {
                    $xml .= "<childAges>{$childAge}</childAges>";
                }
            }
            if(isset($room['firstName']))
                $xml .= "<firstName>{$room['firstName']}</firstName>";
            if(isset($room['lastName']))
                $xml .= "<lastName>{$room['lastName']}</lastName>";
            if(isset($room['bedTypeId']))
                $xml .= "<bedTypeId>{$room['bedTypeId']}</bedTypeId>";
            if(isset($room['smokingPreference']))
                $xml .= "<smokingPreference>{$room['smokingPreference']}</smokingPreference>";
            $xml .= "</Room>";
        }
        return $xml;
    }
}