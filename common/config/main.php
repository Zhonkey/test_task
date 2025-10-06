<?php

use common\components\notifier\BooksNotifier;
use common\components\notifier\gateway\sms\SmsGateway;
use common\service\smsProvider\smsPilot\SmsPilotProvider;

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'bookNotifier' => [
            'class' => BooksNotifier::class,
            'gateway' => [
                'class' => SmsGateway::class,
                'provider' => SmsPilotProvider::class
            ]
        ]
    ],
];
