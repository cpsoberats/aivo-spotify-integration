<?php

use Slim\App;

require '../vendor/autoload.php';

$config = [
    'settings' => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,

        'logger' => [
            'name' => 'aivo_spotify_api',
            'level' => Monolog\Logger::DEBUG,
            'path' => __DIR__ . '/../logs/app.log',
        ],
    ],
];

$app = new App($config);

$container = require __DIR__ . '/../app/dependencies.php';
$container($app);

$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

try {
    $app->run();
} catch (Throwable $e) {
    $this->logger->info($e->getMessage());
}