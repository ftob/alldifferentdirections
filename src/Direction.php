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
     * @param float $x
     * @param float $y
     * @internal param float $latitude
     * @internal param float $longitude
     */
    public function __construct(float $x, float $y)
    {
        $this->x = $x;
        $this->y = $y;
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
        return $this->x;
    }

    /**
     * @return float
     */
    public function getY() {
        return $this->y;
    }
}