<?php

namespace App\Helper;
use GuzzleHttp\Client;

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
    public static function sendNotification($title2,$subTitle,$device_mac_adress)
    {
        $deviceTokens = $device_mac_adress; // Bildirim göndermek istediğiniz cihaz token'larını burada kullanın
        $title = $title2;
        $body = $subTitle;

        $datas = [
            'registration_ids' => $deviceTokens,
            'data' => [
                'title' => $title,
                'body' => $body,
            ],
        ];

        $client = new Client();

        $response = $client->post('https://fcm.googleapis.com/fcm/send', [
            'headers' => [
                'Authorization' => 'key=AIzaSyCaFpZY9g5CI497EF5F2Y8t5rT9nfznsdc',
                'Content-Type' => 'application/json',
            ],
            'json' => $datas,
        ]);

        $statusCode = $response->getStatusCode();

        if ($statusCode === 200) {
            echo 'Bildirimler gönderildi.';
        } else {
            echo 'Bildirim gönderme hatası: ' . $response->getBody();
        }
    }
}
