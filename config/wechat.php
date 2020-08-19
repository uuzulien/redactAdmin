<?php


return [


//    'wechat_open' => [
//        'token' => 'lenggeNotify',
//        'encodingAesKey' => 'lengge2019wechat86726nptifykey986572niceQAQ',
//        'appid' => 'wxdf137d60cdbfe5fb',
//        'appsecret' => 'e1b71ebdb78eb2b10634e19366ee1904',
//    ],

    /*
     * 开放平台第三方平台
     */
     'open_platform' => [
         'default' => [
             'app_id'  => env('WECHAT_OPEN_PLATFORM_APPID', ''),
             'secret'  => env('WECHAT_OPEN_PLATFORM_SECRET', ''),
             'token'   => env('WECHAT_OPEN_PLATFORM_TOKEN', ''),
             'aes_key' => env('WECHAT_OPEN_PLATFORM_AES_KEY', ''),
         ],
     ],

];
