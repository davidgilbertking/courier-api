<?php

declare(strict_types=1);

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=courier_api',
    'username' => 'devuser',
    'password' => 'secret123',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
