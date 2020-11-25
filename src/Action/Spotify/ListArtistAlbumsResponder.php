<?php


namespace App\Action\Spotify;


use App\Action\ActionError;
use Slim\Http\Response;

class ListArtistAlbumsResponder
{
    /**
     * @param Response $response
     * @param int $statusCode
     * @param string $reasonPhrase
     * @param string $error
     * @return Response
     */
    public function error(Response $response, int $statusCode, string $reasonPhrase, string $error)
    {
        return $response->withStatus($statusCode, $reasonPhrase)
            ->withJson(new ActionError($reasonPhrase, $error));
    }

    /**
     * @param Response $response
     * @param $content
     * @return Response
     */
    public function albumsList(Response $response, $content)
    {
        $newResponse = $response->withAddedHeader('Content-Type', 'application/json');

        return $newResponse->withJson($content);
    }
}