<?php
namespace Ftob\AllDifferentDirections;
use Ftob\AllDifferentDirections\Contracts\DirectionsCollectionInterface;
use Ftob\AllDifferentDirections\Exceptions\KeyIndefinedException;
use Ftob\AllDifferentDirections\Exceptions\StartNotFoundException;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

/**
 * Class Directions
 */
class Directions implements DirectionsCollectionInterface
{
    const WALK = 'walk';
    const TURN = 'turn';
    const START = 'start';

    /** @var  array */
    protected $directions = [];

    /**
     * @param $angle
     * @param $x
     * @param $y
     * @param $walk
     */
    private function createCoordinatesByWalk($angle, &$x, &$y, $walk) {
        $x += $walk * cos($angle * M_PI / 180);
        $y += $walk * sin($angle * M_PI / 180);
    }

    /**
     * @return array
     */
    public function getDirections(): array{
        return $this->directions;
    }

    /**
     * @param string $directions
     * @throws KeyIndefinedException
     * @throws StartNotFoundException
     */
    public function addDirection(string $directions)
    {
        $directions = explode(' ', $directions);

        $x = floatval($directions[0]); $y = floatval(next($directions));

        // It's start
        if ($this->nextElementIs($directions, self::START)) {
            next($directions); $angle = floatval(next($directions));

        } else {
            throw new StartNotFoundException("Undefined key - Start" );
        }

        // Turn and walk
        while($this->hasNext($directions)) {

            if ($this->nextElementIs($directions, self::TURN)) {
                var_dump(floatval(next($directions)));

                $angle += floatval(next($directions));
            } elseif ($this->nextElementIs($directions, self::WALK)) {
                next($directions);

                $walk = next($directions);
                $this->createCoordinatesByWalk($angle, $x, $y, floatval($walk));
            }
        }

        $this->directions[] = new Direction($x, $y);


    }

    /**
     * @return Direction
     */
    public function getAvgDirection(): Direction {
        $avgDirection = new Direction(0, 0);
        /** @var Direction $direction */
        $n = count($this->directions);
        foreach ($this->directions as $direction) {
            $avgDirection->setX($avgDirection->getX() + $direction->getX());
            $avgDirection->setY($avgDirection->getY() + $direction->getY());
        }
        $avgDirection->setX($avgDirection->getX() / $n);
        $avgDirection->setY($avgDirection->getY() / $n);

        return $avgDirection;
    }

    /**
     * @param Direction $avgDirection
     * @return float
     */
    public function getAvgDestination(Direction $avgDirection): float {
        $result = $destination = (float)0;

        /** @var Direction $direction */
        foreach ($this->directions as $direction) {
            $result = (float) max($destination, hypot(
                    $avgDirection->getX() - $direction->getX(),
                    $avgDirection->getY() - $direction->getY()
                )
            );
        }

        return $result;
    }

    /**
     * @param array $array
     * @param $is
     * @return bool
     */
    private function nextElementIs(array $array, $is) {
        return (next($array) === $is);
    }

    /**
     * @param array $array
     * @return bool
     */
    private function hasNext(array $array): bool
    {
        if (is_array($array)) {
            return (next($array) === false ? false : true);
        } else {
            return false;
        }
    }
}