<?php

use App\Action\Spotify\ListArtistAlbumsResponder;
use App\Services\SpotifyService;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
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

$container = $app->getContainer();

$container['spotify_service'] = function ($c) {
    return new SpotifyService();
};

$container['list_albums_responder'] = function ($c) {
    return new ListArtistAlbumsResponder();
};

$container['logger'] = function($c) {
    $logger = new Logger('aivo_spotify_api');
    $file_handler = new StreamHandler("../logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

try {
    $app->run();
} catch (Throwable $e) {
    $this->logger->info($e->getMessage());
}