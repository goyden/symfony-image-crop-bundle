<?php

namespace App\Goyden\ImageCropBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class GoydenImageCropBundle
 * @package App\Goyden\ImageCropBundle
 */
class GoydenImageCropBundle extends Bundle
{
    /**
     * @var \Imagick
     */
    private $image;

    /**
     * @var array
     */
    private $thumbnails;

    /**
     * @var object
     */
    private $size;

    /**
     * @var object
     */
    private $coordinates;

    /**
     * @param File $file
     * @return $this
     * @throws \ImagickException
     */
    public function setFile(File $file): self
    {
        $this->image = new \Imagick($file->getRealPath());
        return $this;
    }

    /**
     * GoydenImageCropBundle constructor.
     * @param array $thumbnails
     */
    public function __construct(array $thumbnails = [])
    {
        $this->thumbnails = $thumbnails;
        $this->size = (object)['width' => 0, 'height' => 0];
        $this->coordinates = (object)['x' => 0, 'y' => 0];
    }

    /**
     * @param int $x
     * @param int $y
     * @param int $x1
     * @param int $y1
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setCoordinates(int $x, int $y, int $x1 = 0, int $y1 = 0): self
    {
        if ($x < 0) {
            throw new \InvalidArgumentException('Coordinate x could not be less than 0');
        }

        if ($y < 0) {
            throw new \InvalidArgumentException('Coordinate y could not be less than 0');
        }

        if ($x1 < 0) {
            throw new \InvalidArgumentException('Coordinate x1 could not be less than 0');
        }

        if ($y1 < 0) {
            throw new \InvalidArgumentException('Coordinate y1 could not be less than 0');
        }

        if ($x1 > 0 && $x1 < $x) {
            throw new \InvalidArgumentException('Coordinate x1 could not be more than x');
        }

        if ($y1 > 0 && $y1 < $y) {
            throw new \InvalidArgumentException('Coordinate y1 could not be more than y');
        }

        $this->coordinates->x = $x;

        $this->coordinates->y = $y;

        $this->size->width = $x1 - $x;
        $this->size->height = $y1 - $y;

        return $this;
    }

    /**
     * @param string $thumbnailName
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setThumbnail(string $thumbnailName): self
    {
        if (!array_key_exists($thumbnailName, $this->thumbnails)) {
            throw new \InvalidArgumentException("Thumbnail {$thumbnailName} is not set in the config.");
        }

        $newSize = $this->thumbnails[$thumbnailName];

        $this->size->width = $newSize['width'];
        $this->size->height = $newSize['height'];

        return $this;
    }

    /**
     * @return $this
     * @throws \RuntimeException
     */
    public function crop(): self
    {
        if ($this->image === null) {
            throw new \RuntimeException('You have not set an image.');
        }

        $isSuccessfulCrop = $this->image->cropImage(
            $this->size->width, $this->size->height, $this->coordinates->x, $this->coordinates->y
        );

        if (!$isSuccessfulCrop) {
            throw new \RuntimeException('Cropping error.');
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getFileString(): string
    {
        return (string)$this->image;
    }
}