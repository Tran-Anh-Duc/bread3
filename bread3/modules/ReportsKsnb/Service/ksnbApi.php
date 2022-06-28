<?php

namespace Modules\ReportsKsnb\Service;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Modules\MongodbCore\Entities\KsnbCodeError;
use Modules\ReportsKsnb\Service\ApiCall;

/**
* Endpoint: api.tienngay.vn
*/

class ksnbApi
{


    const CONTRACT_TYPE_TERM = 4;   // thanh toán kỳ api.tienngay transaction['type']
    const CONTRACT_TYPE_FINAL_SETTLEMENT = 3;   // tất toán api.tienngay transaction['type']
    const CONTRACT_TYPE_PAYMENT_TERM = 1;


    public static function getApiUrl($path) {

        if (env('APP_ENV') == 'dev') {
            return env('API_URL_STAGE') . '/' . $path;
        } elseif (env('APP_ENV') == 'product') {
            return env('API_URL_PROD') . '/' . $path;
        } else {
            return env('API_URL_LOCAL') . '/' . $path;
        }
    }

    /**
     * Get contract information from Api server.
     *
     * @param  string  $contractId
     * @return Colection
     */

    //lấy email tpgd
    // public static function getUserEmailCht($email)
    // {
    //     $url = self::getApiUrl('role/getEmailCht');
    //     $data = [];
    //     $data = [
    //         'email' => $email
    //     ];
    //     $getEmail = Http::asForm()->post($url, $data);
    //     // var_dump($getEmail); die;
    //     Log::channel('reportsksnb')->info('Response API getUserEmailCht :'  . print_r($getEmail->json(), true));
    //     return $getEmail->json();
    // }

    //lấy email asm
    public static function getUserEmailAsm($email)
    {
        $url = self::getApiUrl('role/getEmailAsm');
        $data = [
            'email' => $email
        ];
        $getEmail = Http::asForm()->post($url, $data);
        Log::channel('reportsksnb')->info('Response API getUserEmailAsm :'  . print_r($getEmail->json(), true));
        return $getEmail->json();
    }

    //lay email ksnb
    public static function getUserEmailKsnb()
    {
        $url = self::getApiUrl('role/getAllEmailKsnb');
        $data = [];
        Log::channel('reportsksnb')->info('CALL API getUserEmailKsnb');
        $getEmail = Http::asForm()->post($url, $data);
        Log::channel('reportsksnb')->info('Response API getUserEmailKsnb :'  . print_r($getEmail->json(), true));
        return $getEmail->json();
    }

    // lay email tpbksnb
    public static function getUserEmailTbpKsnb()
    {
        $url = self::getApiUrl('role/getEmailKsnb');
        $data = [];
        $getEmail = Http::asForm()->post($url, $data);
        Log::channel('reportsksnb')->info('Response API getUserEmailAsm :'  . print_r($getEmail->json(), true));
        return $getEmail->json();
    }

    //send email confrim
    public static function sendEmailConfrimReports($data) {
        $url = self::getApiUrl('role/sendEmailConfrim');
        $dataPost = [
            'code' => $data['code'],
            "user_name" => $data['user_name'],
            "user_email" => array_unique($data['user_email']),
            'user_nv' => $data['user_nv'],
            "store_name" => $data['store_name'],
            "code_error" => $data['code_error'],
            "type" => $data['type'],
            "punishment" => $data['punishment'],
            "discipline" => $data['discipline'],
            "urlItem" => $data['urlItem'],
            "description" => $data['description'],
            "created_by" => $data['created_by'],
            "urlImg"    => $data['urlImg'],
            "position" => $data['position'],

        ];
        // var_dump($dataPost); die;
        Log::channel('reportsksnb')->info('Call Api: ' . $url . ' ' . print_r($dataPost, true));
        //call api
        $result = Http::asForm()->post($url, $dataPost);

        // dd($result->json());
        Log::channel('reportsksnb')->info('Result Api: ' . $url . ' ' . print_r($result->json(), true));
        return $result;
    }

    //send email not confrim
    public static function sendEmailNotConfrimReports($data) {
        $url = self::getApiUrl('role/sendEmailConfrim');
        $dataPost = [
            'code' => $data['code'],
            "user_name" => $data['user_name'],
            "user_email" => array_unique($data['user_email']),
            'user_nv' => $data['user_nv'],
            "store_name" => $data['store_name'],
            "code_error" => $data['code_error'],
            "type" => $data['type'],
            "punishment" => $data['punishment'],
            "discipline" => $data['discipline'],
            "urlItem" => $data['urlItem'],
            "description" => $data['description'],
            "created_by" => $data['created_by'],
            "position" => $data['position'],
        ];
        Log::channel('reportsksnb')->info('Call Api: ' . $url . ' ' . print_r($dataPost, true));
        //call api
        $result = Http::asForm()->post($url, $dataPost);

        Log::channel('reportsksnb')->info('Result Api: ' . $url . ' ' . print_r($result->json(), true));
        return $result;
    }

    //send email reconfrim
    public static function sendEmailReConfrimReports($data) {
        $url = self::getApiUrl('role/sendEmailConfrim');
        $dataPost = [
            'code' => $data['code'],
            "user_name" => $data['user_name'],
            "user_email" => array_unique($data['user_email']),
            'user_nv' => $data['user_nv'],
            "store_name" => $data['store_name'],
            "code_error" => $data['code_error'],
            "type" => $data['type'],
            "punishment" => $data['punishment'],
            "discipline" => $data['discipline'],
            "urlItem" => $data['urlItem'],
            "description" => $data['description'],
            "created_by" => $data['created_by'],
            "position" => $data['position'],
        ];
        Log::channel('reportsksnb')->info('Call Api: ' . $url . ' ' . print_r($dataPost, true));
        //call api
        $result = Http::asForm()->post($url, $dataPost);

        Log::channel('reportsksnb')->info('Result Api: ' . $url . ' ' . print_r($result->json(), true));
        return $result;
    }

    //send email Kết luận
    public static function sendEmailInferReports($data) {
        $url = self::getApiUrl('role/sendEmailConfrim');
        $dataPost = [
            'code' => $data['code'],
            "user_name" => $data['user_name'],
            "user_email" => array_unique($data['user_email']),
            'user_nv' => $data['user_nv'],
            "store_name" => $data['store_name'],
            "code_error" => $data['code_error'],
            "type" => $data['type'],
            "punishment" => $data['punishment'],
            "discipline" => $data['discipline'],
            "urlItem" => $data['urlItem'],
            "description_error"=> $data['description_error'],
            "description" => $data['description'],
            "created_by" => $data['created_by'],
            "position" =>$data['position'],
            "infer"    =>$data['infer'],
        ];
        Log::channel('reportsksnb')->info('Call Api: ' . $url . ' ' . print_r($dataPost, true));
        //call api
        $result = Http::asForm()->post($url, $dataPost);

        Log::channel('reportsksnb')->info('Result Api: ' . $url . ' ' . print_r($result->json(), true));
        return $result;
    }

    public static function sendEmailWaitInferReports($data) {
        $url = self::getApiUrl('role/sendEmailConfrim');
        $dataPost = [
            'code' => $data['code'],
            "user_name" => $data['user_name'],
            "user_email" => array_unique($data['user_email']),
            'user_nv' => $data['user_nv'],
            "store_name" => $data['store_name'],
            "code_error" => $data['code_error'],
            "type" => $data['type'],
            "punishment" => $data['punishment'],
            "discipline" => $data['discipline'],
            "urlItem" => $data['urlItem'],
            "description_error"=> $data['description_error'],
            "description" => $data['description'],
            "created_by" => $data['created_by'],
            "position" =>$data['position'],
        ];
        Log::channel('reportsksnb')->info('Call Api: ' . $url . ' ' . print_r($dataPost, true));
        //call api
        $result = Http::asForm()->post($url, $dataPost);

        Log::channel('reportsksnb')->info('Result Api: ' . $url . ' ' . print_r($result->json(), true));
        return $result;
    }

    //send email feedback của ksnb
    public static function sendEmaiKsnbFeedback($data) {
        $url = self::getApiUrl('role/sendEmailConfrim');
        $dataPost = [
            "code" => "report_ksnb_email_ksnb_feedback",
            "code_error" => $data['code_error'],
            'user_name' => $data['user_name'],
            "user_email" => array_unique($data['user_email']),
            "user_nv"       => $data['user_nv'],
            "store_name" => $data['store_name'],
            "type"       => $data['type'],
            "punishment" => $data['punishment'],
            "discipline" => $data['discipline'],
            // "comment"   => $data['comment'],
            "urlItem" => $data['urlItem'],
            "description" => $data['description'],
            "created_by" => $data['created_by'],
            "ksnb_comment" => $data['ksnb_comment'],
        ];
        Log::channel('reportsksnb')->info('Call Api: ' . $url . ' ' . print_r($dataPost, true));
        //call api
        $result = Http::asForm()->post($url, $dataPost);

        Log::channel('reportsksnb')->info('Result Api: ' . $url . ' ' . print_r($result->json(), true));
        return $result;
    }

    //send email khi người vi phạm gửi ý kiến phản hồi
    public static function sendEmailFeedBackReports($data) {
        $url = self::getApiUrl('role/sendEmailConfrim');
        $dataPost = [
            'code' => $data['code'],
            "user_name" => $data['user_name'],
            "user_email" => array_unique($data['user_email']),
            'user_nv' => $data['user_nv'],
            "store_name" => $data['store_name'],
            "code_error" => $data['code_error'],
            "type" => $data['type'],
            "punishment" => $data['punishment'],
            "discipline" => $data['discipline'],
            "urlItem" => $data['urlItem'],
            "comment" => $data['comment'],
            // "description" => $data['description'],
            "created_by" => $data['created_by'],
        ];
        Log::channel('reportsksnb')->info('Call Api: ' . $url . ' ' . print_r($dataPost, true));
        //call api
        $result = Http::asForm()->post($url, $dataPost);

        Log::channel('reportsksnb')->info('Result Api: ' . $url . ' ' . print_r($result->json(), true));
        return $result;
    }
    //send email wait confrim when create report
    public static function sendEmailWaitConfrimReports($data) {
        $url = self::getApiUrl('role/sendEmailConfrim');
        $dataPost = [
            "code" => $data['code'],
            "user_name" => $data['user_name'],
            "user_email" => array_unique($data['user_email']),
            'user_nv' => $data['user_nv'],
            'position' => $data['position'],
            "store_name" => $data['store_name'],
            "code_error" => $data['code_error'],
            "type" => $data['type'],
            "punishment" => $data['punishment'],
            "discipline" => $data['discipline'],
            "urlItem" => $data['urlItem'],
            // "comment" => $data['comment'],
            // "infer" => $data['infer'],
            "description" => $data['description'],
            "created_by" => $data['created_by'],

        ];
        Log::channel('reportsksnb')->info('Call Api: ' . $url . ' ' . print_r($dataPost, true));
        //call api
        $result = Http::asForm()->post($url, $dataPost);

        // dd($result->json());
        Log::channel('reportsksnb')->info('Result Api: ' . $url . ' ' . print_r($result->json(), true));
        return $result;
    }

    public static function sendMailEndTime($data)
    {
        $url = self::getApiUrl('role/sendEmailEndTime');
        $dataPost = [
            "code"              => $data['code'],
            "code_error"        => $data['code_error'],
            'user_name'         => $data['user_name'],
            "user_email"        => $data['user_email'],
            "user_nv"           => $data['user_nv'],
            "store_name"        => $data['store_name'],
            "type"              => $data['type'],
            "punishment"        => $data['punishment'],
            "discipline"        => $data['discipline'],
            "description"       => $data['description'],
            "description_error" => $data['description_error'],
            "created_by"        => $data['created_by'],
            "comment"           => $data['comment'],
            "urlItem"           => $data['urlItem']
        ];
         Log::channel('reportsksnb')->info('Call Api: ' . $url . ' ' . print_r($dataPost, true));
        //call api
        $result = Http::asForm()->post($url, $dataPost);
        Log::channel('reportsksnb')->info('Result Api: ' . $url . ' ' . print_r($result->json(), true));
        return $result;
    }


}
