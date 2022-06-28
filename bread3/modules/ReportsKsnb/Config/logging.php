<?php

return [
    'channels' => [
        'reportsksnb' => [
            'driver' => 'daily',
            'path' => storage_path('ReportsKsnb/reportsksnb.log'),
            'days' => 365,
            'tap' => [ modules\ReportsKsnb\Logging\CustomizeFormatter::class ],
        ],
    ]
];
