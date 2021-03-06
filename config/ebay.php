<?php
return [
    'mode' => env('EBAY_MODE', 'production'),

    'siteId' => env('EBAY_SITE_ID','0'),

    'sandbox' => [
        'credentials' => [
            'devId' => env('EBAY_SANDBOX_DEV_ID'),
            'appId' => env('EBAY_SANDBOX_APP_ID'),
            'certId' => env('EBAY_SANDBOX_CERT_ID'),
        ],
        'ruName' => 'Noriko_Suzuki-NorikoSu-getite-smaymzhib',
        'authToken' => env('EBAY_SANDBOX_AUTH_TOKEN'),
        'oauthUserToken' => env('EBAY_SANDBOX_OAUTH_USER_TOKEN'),
    ],
    'production' => [
        'credentials' => [
            'devId' => env('EBAY_PROD_DEV_ID'),
            'appId' => env('EBAY_PROD_APP_ID'),
            'certId' => env('EBAY_PROD_CERT_ID'),
        ],
        'ruName' => 'Noriko_Suzuki-NorikoSu-getite-smaymzhib',
        'authToken' => env('EBAY_PROD_AUTH_TOKEN'),
        'oauthUserToken' => env('EBAY_PROD_OAUTH_USER_TOKEN'),
    ]
];