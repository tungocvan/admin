<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Website Route Prefix
    |--------------------------------------------------------------------------
    |
    | Prefix cho toàn bộ frontend website
    | Ví dụ:
    | - 'website'
    | - 'shop'
    | - ''  (root)
    |
    */
    'route_prefix' => '/',
    'name' => 'Website',

    // Thêm cấu hình thanh toán vào đây
    'payment' => [
        'momo' => [
            'endpoint' => env('MOMO_ENDPOINT', 'https://test-payment.momo.vn/v2/gateway/api/create'),
            'partner_code' => env('MOMO_PARTNER_CODE','MOMOBKUN20180529'),
            'access_key' => env('MOMO_ACCESS_KEY','klm05TvNBzhg7h7j'),
            'secret_key' => env('MOMO_SECRET_KEY','at67qH6mk8w5Y1nAyMoYKMWACiEI2bsa'),
        ],
    ],
];
