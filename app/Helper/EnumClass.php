<?php

namespace App\Helper;

use Illuminate\Support\Facades\Http;

/**
 * Class EnumClass
 * @package App\Helper\Enum
 */

class EnumClass
{
    public static function getVariables()
    {
        $oClass = new \ReflectionClass(get_called_class());
        return $oClass->getConstants();
    }
    public static function sendNotification($title,$subTitle,$device_mac_adress)
    {
        $datas = [
            "to" => $device_mac_adress,
            "collapse_key" => "type_a",
            "notification" => [
                "title" => $title,
                "body" => $subTitle,
            ],
            'data' => [
                 "body" => "Body of Your Notification in Data",
                    "title"=> "Title of Your Notification in Title",
                "key_1" =>"Value for key_1",
                "key_2" =>"Value for key_2"
                ],
        ];
        $url = 'https://fcm.googleapis.com/fcm/send';
        $token = 'key=BC_Aj-4eZ9DhaUcF7kPibcGEOUtgMExTpJBQfU2SXxE6kDewL22GH6r_Qf-OPksBzmc6oAZo8Stf8BivI2gKoGY';
        $result = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => $token
        ])->post($url,$datas);
    }
}
