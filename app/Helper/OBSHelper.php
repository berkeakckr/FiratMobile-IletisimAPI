<?php
namespace App\Helper;

use Illuminate\Support\Facades\Http;

class OBSHelper{
    public static function getCallObs($param=null){
        $api_is_active = true;
        $url = 'https://api.firat.edu.tr/api/obs/student_taken_lessons_v2';
        $token="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNmZkZTI5YjQzYWM0YzJkYjE1MmIyY2MzODUwY2E3NDQxZDcwOTFkYzcyOGExZDc2YzliMjg4NzMxYjgxNzkzOGViNDY2MzZlYjQ4NWJhZjkiLCJpYXQiOjE2NzE4ODMzNzAuMzY0MjgsIm5iZiI6MTY3MTg4MzM3MC4zNjQyODUsImV4cCI6MTY4NzYwODE3MC4zNTUsInN1YiI6IjYiLCJzY29wZXMiOltdfQ.e6ttyh53XXgb_OQfb4fhhrgx4uMxr-ieqzDT5SZS-IiE4sPPWYQO96NwphAMU_aYb7HDUTtl78DvfNxC4qYUBUuOAoZffSU2bbaTmQZfeB5cgcbTN83QExujZPSZxPIZfhkhwm9asUYMKAa6mI9ZkUa4m817B5jn_6B87ep_K6Qgc7fSQ-YtdsrnXbZ6Wtrd8axJ_yHfPz_SJ1P2jadkU_pYT3dBEwwED6D8yYcrlVlZHkasJsQgsEO1ruGJhtA5mrCo2Mgy52qyCbsVrUFkQWHFWM5sZskNvTv1T4wFY7z4VxzzygLMjlUHkSjDkPKxupcOnNVgnH8ZGrLCKn1YwwWgiykhCBiyxeux2_Zje_zPEVNz8COp93qRp-4efaeFdBOBxADTG2ugue7n6f4EWXxll6Bs467apzaRM7HgAr83FlpZPC2YmKaQrWRV4znKMlyZp9g8-rmsUExZFTYatMTLyDiPx-anQ7oegQatqUw4d-HLN3BvOIc4Sjn_e6TMirUGbCeiiENugHCILkhX_JRzW7UVtNvtmSjq6nyCajtZKZXpYqDSZ8WI0PPu935WXlLHwsU2DevXsPJisgwm8Xw7KFCkgqXwijmP3-gT7S7uvjH14eNM-rHtN_l8d7RRIZSWX5NdhiR-Y8eppsPHSbhb2tH8PcL6dRw9JOyyxNs";
        if ($param != null){
            $params = [
                "params"=>$param
            ];
            $result = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->withToken($token)->get($url,$params);
        }else{
            $result = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->withToken($token)->get($url);
        }

        if($result->status()==200){
            $result = json_decode($result->body());
            return $result;
        }else{
            $api_is_active = false;
            return $api_is_active;
        }
    }
}
