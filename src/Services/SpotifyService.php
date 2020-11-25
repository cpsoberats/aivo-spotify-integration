<?php

namespace App\Services;

use App\Domain\Album;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class SpotifyService
{
    private Client $serviceClient;
    private Client $accountClient;

    public function __construct()
    {
        $this->serviceClient = new Client(['base_uri' => 'https://api.spotify.com']);
        $this->accountClient = new Client(['base_uri' => 'https://accounts.spotify.com/api/']);
    }

    /**
     * @return mixed
     * @throws GuzzleException
     */
    public function getAccessToken()
    {
        try {
            $response = $this->accountClient->request('POST', 'token', [
                    'headers' => [
                        'Authorization' => 'Basic NjE1ZmFiYzFhZGFlNGNiZWJiN2U2NGRiOWJjOTA2ZWE6NjRiZjQ5OGZkNzNhNDg0NzlmMjgwMTUxOTg2Y2MyMDE=',
                        'Content-Type' => 'application/x-www-form-urlencoded'
                    ],
                    'form_params' => [
                        'grant_type' => 'client_credentials'
                    ]
                ]
            );
            return json_decode($response->getBody()->getContents())->access_token;
        } catch (ClientException $e) {
            return null;
        }
    }

    /**
     * @param string $bandName
     * @return array|ResponseInterface
     * @throws GuzzleException
     */
    public function getArtistAlbums(string $bandName)
    {

        try {
            $accessToken = $this->getAccessToken();
            $albums = [];
            $offset = 0;
            $limit = 50;

            do {
                $response = $this->serviceClient->request(
                    'GET', '/v1/search',
                    [
                        'headers' => [
                            'Content-Type' => 'application/json',
                            'Accept' => 'application/json',
                            'Authorization' => "Bearer $accessToken"
                        ],
                        'query' => [
                            'q' => "artist:$bandName",
                            'type' => 'album',
                            'limit' => $limit,
                            'offset' => $offset
                        ]
                    ]
                );
                $content = json_decode($response->getBody()->getContents());
                if ($hasNext = (bool)$content->albums->next) {
                    parse_str($content->albums->next, $params);
                    $limit = $params['limit'];
                    $offset = $params['offset'];
                }

                $albums = array_merge($albums, $content->albums->items);
            } while ($hasNext);

            $response = array();

            foreach ($albums as $album) {
                $response[] = new Album($album);
            }

            return $response;
        } catch (GuzzleException $e) {
            throw $e;
        }
    }

}