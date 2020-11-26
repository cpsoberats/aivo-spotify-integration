<?php

namespace Tests;

use App\Action\Spotify\ListArtistAlbumsAction;
use App\Action\Spotify\ListArtistAlbumsResponder;
use App\Services\SpotifyService;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class SpotifyApiTest extends PHPUnit_TestCase
{
    protected App $app;
    protected ContainerInterface $container;

    public function setUp(): void
    {
        $this->app = (new App());
        $this->container = $this->app->getContainer();
        $this->container['spotify_service'] = function ($c) {
            return new SpotifyService();
        };

        $this->container['list_albums_responder'] = function ($c) {
            return new ListArtistAlbumsResponder();
        };

        $this->container['logger'] = function($c) {
            $logger = new Logger('aivo_spotify_api');
            $file_handler = new StreamHandler("../logs/app.log");
            $logger->pushHandler($file_handler);
            return $logger;
        };
    }

    public function testGetArtistsAlbumsNotEmpty(){

        $action = new ListArtistAlbumsAction($this->container);
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

            $this->assertSame($response->getStatusCode(), 200);
            $result = json_decode($response->getBody(), true);
            $this->assertNotEmpty($result);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function testGetArtistsAlbumsBadRequest(){

        $action = new ListArtistAlbumsAction($this->container);
        try {
            $env = Environment::mock([
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI'    => "/api/v1/albums"
            ]);
            $req = Request::createFromEnvironment($env);
            $response = new Response();
            $response = $action($req, $response, []);

            $this->assertSame($response->getStatusCode(), 400);
            $result = json_decode($response->getBody(), true);
            $this->assertSame($result['description'], "Missing required 'q' query string parameter. Please provide band name");
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }
}
