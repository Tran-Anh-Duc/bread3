<?php

namespace Modules\ReportsKsnb\Service;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Response;

class ApiCall {

    private static function generateRequestID() {
        return (string) time() . (string) rand(0, 99);
    }


    public static function getApiUrl($path) {

        if (env('APP_ENV') == 'dev') {
            return env('API_URL_STAGE') . '/' . $path;
        } elseif (env('APP_ENV') == 'product') {
            return env('API_URL_PROD') . '/' . $path;
        } else {
            return env('API_URL_LOCAL') . '/' . $path;
        }
    }

    public static function getUserEmailCht($email)
    {
        $url = self::getApiUrl('role/getEmailCht');
        $data = [
            'email' => $email
        ];
        $getEmail = Http::asForm()->post($url, $data);
        return $getEmail->json();
    }

    public static function getUserEmailAsm($email)
    {
        $url = self::getApiUrl('role/getEmailAsm');
        $data = [
            'email' => $email
        ];
        $getEmail = Http::asForm()->post($url, $data);
        return $getEmail->json();
    }
    public static function getUserEmail($email)
    {
        Log::channel('reportsksnb')->info('Call API getUserEmail :'  . $email);
        $url = self::getApiUrl('role/getUserByEmail_Ksnb');
        $data = [
            'email' => $email
        ];
        $getEmail = Http::asForm()->post($url, $data);
        Log::channel('reportsksnb')->info('Response API getUserEmail :'  . print_r($getEmail->json(), true));
        return $getEmail->json();
    }

}

