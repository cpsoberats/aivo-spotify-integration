<?php

use App\Action\Spotify\ListArtistAlbumsResponder;
use App\Services\SpotifyService;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Slim\App;

return function (App $app){
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
};
