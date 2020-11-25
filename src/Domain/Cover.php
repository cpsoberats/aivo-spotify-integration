<?php


namespace App\Domain;


use JsonSerializable;

/**
 * Class Cover
 * @package App\Domain
 */
class Cover implements JsonSerializable
{

    /**
     * @var int
     */
    private int $height;

    /**
     * @var int
     */
    private int $width;

    /**
     * @var string
     */
    private string $url;

    public function __construct($image)
    {
        $this->height = $image->height;
        $this->width = $image->width;
        $this->url = $image->url;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'height' => $this->height,
            'width' => $this->width,
            'url' => $this->url
        ];
    }
}