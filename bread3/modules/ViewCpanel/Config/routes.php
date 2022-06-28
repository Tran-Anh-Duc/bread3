<?php

return [
    'paymentgateway' => [
    	'momo' => [
    		'listTransactionByMonth' 	=> config('domains.paymentgateway') . 'momo/listTransactionByMonth',
    		'searchTransactions'		=> config('domains.paymentgateway') . 'momo/searchTransactions',
            'autoConfirm'               => config('domains.paymentgateway') . 'momo/autoConfirm',
    	],
        'reconciliation' => [
            'create'                    => config('domains.paymentgateway') . 'momo/reconciliation/create',
            'getListByMonth'            => config('domains.paymentgateway') . 'momo/reconciliation/getListByMonth',
            'sendEmail'                 => config('domains.paymentgateway') . 'momo/reconciliation/sendEmail',
            'delete'                    => config('domains.paymentgateway') . 'momo/reconciliation/delete',
        ],
    ],

    'vpbank' => [
        'transaction' => [
            'getListByMonth'            => config('domains.vpbank') . 'transaction/getListByMonth',
            'searchTransactions'        => config('domains.vpbank') . 'transaction/searchTransactions',
        ],
    ],

    'reportForm3' => [
        'search'                        => config('domains.report') . 'reportForm3/search',
    ],

    'api' => [
        'exportAllLead'                 => config('domains.api') . 'lead_custom/get_all_lead_export',
    ],
    'ksnb' => [
        'reportksnb' => [
            'getCodeByType'             => config('domains.reportsksnb') . 'getCodeByType',
            'getPunishmentByCode'       => config('domains.reportsksnb') .'getPunishmentByCode',
            'getDisciplineByCode'       => config('domains.reportsksnb') . 'getDisciplineByCode',

            'getDescription'            => config('domains.reportsksnb') . 'getDescription',
            'saveReport'               => config('domains.reportsksnb') . 'saveReport',

            'updateReport'              => config('domains.reportsksnb') . 'updateReport',
            'updateProcess'             => config('domains.reportsksnb') . 'updateProcess',
            'updateEmailNotConfrim'     => config('domains.reportsksnb') . 'updateEmailNotConfrim',
            'updateEmailReConfrim'      => config('domains.reportsksnb') . 'updateEmailReConfrim',
            'updateInfer'               => config('domains.reportsksnb') . 'updateInfer',
            'sendfeedback'              => config('domains.reportsksnb') . 'sendfeedback',
            'getEmailCHT'               => config('domains.reportsksnb') . 'getEmailCHT',
            'getNameByEmail'            => config('domains.reportsksnb') . 'getNameByEmail',
            'updateWaitConfrim'         => config('domains.reportsksnb') . 'updateEmailWaitConfrim',
            'ksnbFeedback'              => config('domains.reportsksnb') . 'ksnbFeedback',
            'waitInfer'                 => config('domains.reportsksnb') . 'waitInfer',

            'getEmailCHTByStoreId'      => config('domains.reportsksnb') . 'getEmailCHTByStoreId',
            'getEmployeesByStoreId'     => config('domains.reportsksnb') . 'getEmployeesByStoreId',

            'allMailRoll'               => config('domains.reportsksnb') . 'allMailRoll',
            'getAllRoom'               => config('domains.reportsksnb') . 'getAllRoom',

            'getErrorCodeInfo'          => config('domains.reportsksnb') . 'getErrorCodeInfo',
            'cancelRpNv'                => config('domains.reportsksnb') . 'cancelRpNv',
            'cancelRpTbp'               => config('domains.reportsksnb') . 'cancelRpTbp',
            'endTimeReport'             => config('domains.reportsksnb') . 'endTimeReport',
            'getEMailEndTime'             => config('domains.reportsksnb') . 'getEMailEndTime',
        ]
    ]
];
