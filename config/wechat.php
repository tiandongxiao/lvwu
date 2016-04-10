<?php
return [
    'use_alias'    => env('WECHAT_USE_ALIAS', false),
    'app_id'       => env('WECHAT_APPID', 'YourAppId'), // 必填
    'secret'       => env('WECHAT_SECRET', 'YourSecret'), // 必填
    'token'        => env('WECHAT_TOKEN', 'YourToken'),  // 必填
    'encoding_key' => env('WECHAT_ENCODING_KEY', 'YourEncodingAESKey'), // 加密模式需要，其它模式不需要

    /**
     * 微信支付
     */
     'payment' => [
         'merchant_id'        => env('WECHAT_PAYMENT_MERCHANT_ID', 'your-mch-id'),
         'key'                => env('WECHAT_PAYMENT_KEY', 'key-for-signature'),
         'cert_path'          => env('WECHAT_PAYMENT_CERT_PATH', 'path/to/your/cert.pem'), // XXX: 绝对路径！！！！
         'key_path'           => env('WECHAT_PAYMENT_KEY_PATH', 'path/to/your/key'),      // XXX: 绝对路径！！！！
         // 'device_info'     => env('WECHAT_PAYMENT_DEVICE_INFO', ''),
         // 'sub_app_id'      => env('WECHAT_PAYMENT_SUB_APP_ID', ''),
        // 'sub_merchant_id' => env('WECHAT_PAYMENT_SUB_MERCHANT_ID', ''),
        // ...
    ],
];