<?php

return [

    'cache' => [

        /**
         * driver
         */
//        'driver' => \Copona\System\Library\Cache\Drivers\Apc::class,
//        'driver' => \Copona\System\Library\Cache\Drivers\Redis::class,
//        'driver' => \Copona\System\Library\Cache\Drivers\Memcached::class,
//        'driver' => \Copona\System\Library\Cache\Drivers\Memcache::class,
        'driver' => \Copona\System\Library\Cache\Drivers\File::class,

        'configs' => [
            "securityKey"         => 'file',
            "path"                => DIR_PUBLIC . '/' . PATH_CACHE_PRIVATE,
            "default_chmod"       => 0755,
            "htaccess"            => true,
            "defaultTtl"          => 3600,
            'ignoreSymfonyNotice' => true,
//            'servers' => [
//                [
//                    'host' => '127.0.0.1',
//                    'port' => 11211,
//                    // 'sasl_user' => false, // optional
//                    // 'sasl_password' => false // optional
//                ],
//            ]
        ]
    ]
];