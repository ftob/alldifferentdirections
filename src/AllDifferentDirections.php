<?php
namespace Ftob\AllDifferentDirections;

use SplFileObject;


/**
 * Interface Stringable
 * @package Ftob\AllDifferentDirections
 */
interface Stringable
{
    public function __toString();
}


/**
 * Interface Formatter
 * @package Ftob\AllDifferentDirections
 */
interface Formatter
{
    public function setFormat(string $format);

    public function getFormat(): string;
}

/**
 * Interface DirectionsCollectionInterface
 * @package Ftob\AllDifferentDirections
 */
interface DirectionsCollectionInterface
{
    public function addDirection(string $direction);
    public function getAvgDestination(Direction $avgDirection): float;
    public function getAvgDirection(): Direction;
}

class Directions
{
    const WALK = 'walk';
    const TURN = 'turn';
    const START = 'start';

    /** @var  array */
    protected $directions = [];

    /**
     * @param $angle
     * @param $lat
     * @param $lon
     * @param $walk
     */
    private function createCoordinatesByWalk($angle, &$lat, &$lon, $walk) {
        $lat += $walk * cos(deg2rad($angle));
        $lon += $walk * sin(deg2rad($angle));
    }

    /**
     * @param string $directions
     */
    public function addDirection(string $directions)
    {
        $directions = explode(' ', $directions);

        if (!empty($directions)) {
            $lat = next($directions); $lon = next($directions);

            $angle = 0;

            // It's start
            if ($this->nextElementIs($directions, self::START)) {
                next($directions); $angle = next($directions);
            } else {
                // @todo exception must be start
            }

            // Turn and walk
            while($this->hasNext($directions)) {
                next($directions);
                if ($this->nextElementIs($directions, self::TURN)) {
                    $angle += floatval(next($directions));
                } elseif ($this->nextElementIs($directions, self::WALK)) {
                    $this->createCoordinatesByWalk($angle, $lat, $lon, floatval(next($directions)));
                } else {
                    // @todo exception undefined key
                }
            }

            $this->directions[] = new Direction($lat, $lon);
        } else {
            // @todo exception
        }
    }

    /**
     * @return Direction
     */
    public function getAvgDirection(): Direction {
        $avgDirection = new Direction(0, 0);
        /** @var Direction $direction */
        foreach ($this->directions as $direction) {
            $avgDirection->setLatitude($avgDirection->getLatitude() / $direction->getLatitude());
            $avgDirection->setLongitude($avgDirection->getLongitude() / $direction->getLongitude());
        }

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
                $avgDirection->getLatitude() - $direction->getLatitude(),
                    $avgDirection->getLongitude() - $direction->getLongitude()
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
        return array_pop($array) == $is;
    }

    /**
     * @param array $array
     * @return bool
     */
    private function hasNext(array $array): bool
    {
        if (is_array($array)) {
            if (next($array) === false) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
}


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

class DifferentDirections implements Stringable, Formatter
{
    /** @var  DirectionsCollectionInterface */
    protected $directions;

    /** @var  SplFileObject */
    protected $file;

    protected $format = '%f %f %f';

    public function __construct(SplFileObject $file, DirectionsCollectionInterface $directionsCollection)
    {
        $this->file = $file;
        $this->directions = $directionsCollection;
    }


    /**
     * @param string $format
     */
    public function setFormat(string $format)
    {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }


    public function all(): array
    {
        $result = [];
        $inc = 0;
        while(true) {
            $this->directions = new $this->directions;
            $n = (int)$this->file->fgets();

            if ($n == 0) {
                break;
            }
            for ($i = 0; $i < $n; $i++) {
                $this->directions->addDirection($this->file->fgets());
            }

            $avgDirection = $this->directions->getAvgDirection();
            $avgDestination = $this->directions->getAvgDestination($avgDirection);

            $result[$i][] = $avgDirection;
            $result[$i][] = $avgDestination;

            $inc++;
        }
        return $result;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $arr = $this->all();
        $result = '';
        foreach ($arr as $a) {
            // $a[0] - Direction
            // $a[1] - float
            // Sorry. It's bad trip (trick)
            $result .= sprintf($this->getFormat(), $a[0]->getLatitude(), $a[0]->getLongitude(), $a[2]) . "\n";
        }
        return $result;
    }
}

