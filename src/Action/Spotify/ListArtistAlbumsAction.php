<?php
declare(strict_types=1);

namespace App\Action\Spotify;

use App\Action\ActionError;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class ListArtistAlbumsAction
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getAlbums(Request $request, Response $response)
    {
        $queryParams = $request->getQueryParams();
        if (!isset($queryParams['q']) || empty(trim($queryParams['q']))) {
            return $this->container->get('list_albums_responder')->error($response, 400, ActionError::BAD_REQUEST,
                "Missing required 'q' query string parameter. Please provide band name");
        }
        try {
            $content = $this->container->get('spotify_service')->getArtistAlbums($queryParams['q']);
            return $this->container->get('list_albums_responder')->albumsList($response, $content);

        } catch (\Exception $e) {
            $this->container->get('logger')->info($e->getMessage());
            return $this->container->get('list_albums_responder')->error($response, $e->getCode(), ActionError::SERVER_ERROR,
                $e->getMessage());
        }
    }
}