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
    public static $result;
    public static function getVariables()
    {
        $oClass = new \ReflectionClass(get_called_class());
        return $oClass->getConstants();
    }
    public static function sendNotification($title,$subTitle,$device_mac_adress)
    {
        //$deviceTokens = $device_mac_adress; // Bildirim göndermek istediğiniz cihaz token'larını burada kullanın
        //$title = $title2;
        //$body = $subTitle;

        $datas = [
            'to' => 'fS0SDjeKQA21ov0WgyL7gO:APA91bFUd-WDJCcdNNlw4jsZaXnsJMOYMpxVyNLUC35miLEG3Iplpi5XrZ363IaQsN_k6_7bsMNeWI4dJX3F5ZtO4cLYDNNReSjD-KLJg9HBv9uySQ3aAv6ownF7_vTmzqzQZK6V58RW',
            "collapse_key" => "type_a",
            'notification' => [
                'title' => $title,
                'body' => $subTitle,
            ],
            'data' => [
                "body" => $subTitle,
                "title"=> $title
            ],
        ];

       /*$url = 'https://fcm.googleapis.com/fcm/send';
        $token = 'key=AAAAXKLRl4E:APA91bG7AyWqyR0lI3s1qvcrX7FqZDgahFl-kn2dj4Z0Oahq29bZNnJX_4PrCrBH03Gc-qE1kMpU1qUmOKpUO9iWKoI3BEEzAqurvlZ1Qnfkz3aSl9pVWjITRa6BTQX7NzbrJIjIEfOy';
        //AIzaSyCaFpZY9g5CI497EF5F2Y8t5rT9nfznsdc
        $result = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => $token
        ])->post($url,$datas);*/

        $client = new Client();

        $result = $client->post('https://fcm.googleapis.com/fcm/send', [
            'headers' => [
                'Authorization' => 'key=AAAASgiyCNA:APA91bFeTQgav-8sYXi99rmemRGjRE5m7jtga5MKms110ez_qkJElpuUWCSPsVwwzmPTSIxOucS7pUGrcwUO-cPxHyL8vsvEbOyRoFGApvpAhNT6aoS6hnPOgZoHWYMCBwudaRYTRr99',
                'Content-Type' => 'application/json',
            ],
            'json' => $datas,
        ]);


        $statusCode = $result->getStatusCode();

        if ($statusCode === 200) {
            return response()->json([
                'success'=>'Bildirimler gönderildi.'
            ],401);

        } else {
            return response()->json([
                'Bildirim gönderme hatası: ' . $result->getBody()
            ]);
        }
    }
}
