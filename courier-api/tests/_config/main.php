<?php

return yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../config/web.php'),
    [
        'components' => [
            'db' => [
                'dsn' => 'mysql:host=127.0.0.1;dbname=courier_api',
                'username' => 'devuser',
                'password' => 'secret123',
                'charset' => 'utf8',
            ],
        ],
    ]
);
