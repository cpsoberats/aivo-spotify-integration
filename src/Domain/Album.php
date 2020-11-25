<?php
declare(strict_types=1);

namespace App\Domain;

use JsonSerializable;

/**
 * Class Album
 * @package App\Domain
 */
class Album implements JsonSerializable
{

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $released;

    /**
     * @var int
     */
    private int $tracks;

    /**
     * @var Cover
     */
    private $cover;

    public function __construct($album)
    {
        $this->name = $album->name;
        $this->released = $album->release_date;
        $this->tracks = $album->total_tracks;
        $this->cover = $this->getAlbumCover($album->images);
    }

    public function getAlbumCover(array $images)
    {
        $image = $images[0];
        return new Cover($image);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'released' => $this->released,
            'tracks' => $this->tracks,
            'cover' => $this->cover,
        ];
    }
}

