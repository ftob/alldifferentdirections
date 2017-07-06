<?php
namespace Ftob\AllDifferentDirections\Contracts;

use Ftob\AllDifferentDirections\Direction;

/**
 * Interface DirectionsCollectionInterface
 * @package Ftob\AllDifferentDirections
 */
interface DirectionsCollectionInterface
{
    public function addDirection(string $direction);
    public function getAvgDestination(Direction $avgDirection): float;
    public function getAvgDirection(): Direction;
    public function getDirections(): array;
}
