<?php

namespace Tests;

use App\Action\Spotify\ListArtistAlbumsAction;
use App\Action\Spotify\ListArtistAlbumsResponder;
use App\Services\SpotifyService;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class SpotifyApiTest extends PHPUnit_TestCase
{
    protected $app;

    public function setUp(): void
    {
        $this->app = (new App());
    }

    public function testGetArtistsAlbumsNotEmpty(){
        $container = $this->app->getContainer();

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

        $action = new ListArtistAlbumsAction($container);
        try {
            $q = "Buena Vista Social Club";
            $env = Environment::mock([
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI'    => "/api/v1/albums",
                'QUERY_STRING' => "q=$q"
            ]);
            $req = Request::createFromEnvironment($env);
            $response = new Response();
            $response = $action($req, $response, []);
            $this->app->getContainer()->get('logger')->info($response);
            $this->assertSame($response->getStatusCode(), 200);
            $result = json_decode($response->getBody(), true);
            $this->assertNotEmpty($result);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }
}
