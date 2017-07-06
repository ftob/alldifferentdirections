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

