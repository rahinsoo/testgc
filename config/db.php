<?php

/// config commune à tous, qui est écrasée par le db.local ///
$config = [
    'db' => [
        'host' => '',
        'port' => null,
        'db' => '',
        'user' => '',
        'pass' => '',
        'charset' => '',
    ]
];

$localDbFile = __DIR__ . '/db.local.php';

if (is_file($localDbFile)) {
    $config['db'] = array_replace($config['db'], (require $localDbFile)['db'] ?? []);
}

return $config;