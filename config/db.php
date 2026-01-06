<?php

$config = [
    'db' => [
        'host' => '',
        'port' => null,
        'dbname' => '', 
        'user' => '',
        'password' => '',
        'charset' => '',
    ]
];

$localDbFile = __DIR__ . '/db.local.php';
// Vérifier si le fichier de configuration locale existe
if (is_file($localDbFile)) {
    $config['db'] = array_replace($config['db'], (require $localDbFile)['db']);
}
return $config;