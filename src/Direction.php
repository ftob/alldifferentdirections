<?php
namespace Ftob\AllDifferentDirections;



/**
 * Class Direction
 * @package Ftob\AllDifferentDirections
 */
class Direction
{
    /** @var  float */
    protected $lat;

    /** @var  float */
    protected $lon;

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
     * @param float $latitude
     */
    public function setLatitude(float $latitude) {
        $this->lat = $latitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude(float $longitude) {
        $this->lon = $longitude;
    }
    /**
     * @return float
     */
    public function getLatitude() {
        return $this->lat;
    }

    /**
     * @return float
     */
    public function getLongitude() {
        return $this->lon;
    }
}