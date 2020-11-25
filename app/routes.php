<?php
declare(strict_types=1);

use App\Action\Spotify\ListArtistAlbumsAction;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        return $response;
    });

    $app->get('/api/v1/albums', ListArtistAlbumsAction::class);

};
