<?php
return [
    'use_alias'    => env('WECHAT_USE_ALIAS', false),
    'app_id'       => env('WECHAT_APPID', 'YourAppId'), // 必填
    'secret'       => env('WECHAT_SECRET', 'YourSecret'), // 必填
    'token'        => env('WECHAT_TOKEN', 'YourToken'),  // 必填
    'encoding_key' => env('WECHAT_ENCODING_KEY', 'YourEncodingAESKey'), // 加密模式需要，其它模式不需要

    /**
     * OAuth 配置
     *
     * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
     * callback：OAuth授权完成后的回调页地址(如果使用中间件，则随便填写。。。)
     */
     'oauth' => [
         'scopes'   => array_map('trim', explode(',', env('WECHAT_OAUTH_SCOPES', 'snsapi_userinfo'))),
         'callback' => env('WECHAT_OAUTH_CALLBACK', 'www.exingdong.com'),
     ],

    /**
     * 微信支付
     */
     'payment' => [
         'merchant_id'  => env('WECHAT_PAYMENT_MERCHANT_ID', 'your-mch-id'),
         'key'          => env('WECHAT_PAYMENT_KEY', 'key-for-signature'),
         'cert_path'    => env('WECHAT_PAYMENT_CERT_PATH', 'path/to/your/cert.pem'), // XXX: 绝对路径！！！！
         'key_path'     => env('WECHAT_PAYMENT_KEY_PATH', 'path/to/your/key'),      // XXX: 绝对路径！！！！
         'notify_url'   => env('WECHAT_NOTIFY_URL','notify_url'),
         // 'device_info'     => env('WECHAT_PAYMENT_DEVICE_INFO', ''),
         // 'sub_app_id'      => env('WECHAT_PAYMENT_SUB_APP_ID', ''),
        // 'sub_merchant_id' => env('WECHAT_PAYMENT_SUB_MERCHANT_ID', ''),
        // ...
    ],
];