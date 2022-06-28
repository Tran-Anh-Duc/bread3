<?php

// local environment
$local = [
    'apicpanel'             => env('CORE_API_LOCAL') . '/api/',
    'paymentgateway'        => env('CORE_PAYMENTGATEWAY_LOCAL') . '/paymentgateway/',
    'vpbank'				=> env('CORE_VPBANK_LOCAL') . '/vpbank/',
    'report'                => env('CORE_REPORT_LOCAL') . '/report/',
    'api'                   => env('API_URL_LOCAL') . '/',
    'reportsksnb'           => env('REPORTKSNB_LOCAL').'/reportsksnb/',
];

// development environment
$dev = [
    'apicpanel'             => env('CORE_API_STAGE') . '/api/',
    'paymentgateway'        => env('CORE_PAYMENTGATEWAY_STAGE') . '/paymentgateway/',
    'vpbank'				=> env('CORE_VPBANK_STAGE') . '/vpbank/',
    'report'                => env('CORE_REPORT_STAGE') . '/report/',
    'api'                   => env('API_URL_STAGE') . '/',
    'reportsksnb'           => env('REPORTKSNB_STAGE').'/reportsksnb/',
];

//product environment
$product = [
    'apicpanel'             => env('CORE_API_PROD') . '/api/',
    'paymentgateway'        => env('CORE_PAYMENTGATEWAY_PROD'). '/paymentgateway/',
    'vpbank'				=> env('CORE_VPBANK_PROD') . '/vpbank/',
    'report'                => env('CORE_REPORT_PROD') . '/report/',
    'api'                   => env('API_URL_PROD') . '/',
    'reportsksnb'           => env('REPORTKSNB_PROD').'/reportsksnb/',
];


if (env('APP_ENV') == 'dev') {
    return $dev;
} elseif (env('APP_ENV') == 'product') {
    return $product;
} else {
    return $local;
}