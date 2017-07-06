<?php
namespace Ftob\AllDifferentDirections;

use Ftob\AllDifferentDirections\Contracts\DirectionsCollectionInterface;
use Ftob\AllDifferentDirections\Contracts\Formatter;
use Ftob\AllDifferentDirections\Contracts\Stringable;
use SplFileObject;

class DifferentDirections implements Stringable, Formatter
{
    /** @var  DirectionsCollectionInterface */
    protected $directions;
    /** @var  SplFileObject */
    protected $file;
    /** @var string  */
    protected $format = '%F %F %F';

    /**
     * DifferentDirections constructor.
     * @param SplFileObject $file
     * @param DirectionsCollectionInterface $directionsCollection
     */
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


    /**
     * @return array
     * array [
     *  0 => [
     *      Direction,
     *      float
     * ],
     * ....
     * ]
     */
    public function all(): array
    {
        // Results
        $result = [];
        while(true) {
            // New instance DirectionsCollectionInterface
            $this->directions = new $this->directions;
            $n = (int)$this->file->fgets();
            // No people and break
            if ($n == 0) {
                break;
            }

            // Make collection directions
            for ($i = 0; $i < $n; $i++) {
                $this->directions->addDirection($this->file->fgets());
            }

            // Average directions
            $avgDirection = $this->directions->getAvgDirection();
            // Max off
            $avgDestination = $this->directions->getAvgDestination($avgDirection);

            // Set direction
            $result[$i][] = $avgDirection;
            // Set float
            $result[$i][] = $avgDestination;

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
            $result .= sprintf($this->getFormat(), $a[0]->getX(), $a[0]->getY(), $a[1]) . "\n";
        }

        return $result;
    }
}

