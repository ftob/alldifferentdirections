<?php
namespace Ftob\AllDifferentDirections;



/**
 * Class Direction
 * @package Ftob\AllDifferentDirections
 */
class Direction
{
    /** @var  float */
    protected $x;

    /** @var  float */
    protected $y;

    /**
     * Direction constructor.
     * @param float $latitude
     * @param float $longitude
     */
    public function __construct(float $latitude, float $longitude)
    {
        $this->lat = $latitude;
        $this->lon = $longitude;
    }

    /**
     * @param float $x
     */
    public function setX(float $x) {
        $this->x = $x;
    }

    /**
     * @param float $y
     */
    public function setY(float $y) {
        $this->y = $y;
    }
    /**
     * @return float
     */
    public function getX() {
        return $this->lat;
    }

    /**
     * @return float
     */
    public function getY() {
        return $this->lon;
    }
}