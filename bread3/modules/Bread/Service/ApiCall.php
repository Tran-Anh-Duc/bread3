<?php

namespace Modules\Bread\Service;

class ApiCall
{

    private static function generateRequestID()
    {
        return (string)time() . (string)rand(0, 99);
    }

    public static function getApiUrl($path)
    {
        if (env('APP_ENV') == 'dev') {
            return env('API_URL_LOCAL') . '/' . $path;
        }
    }




}
