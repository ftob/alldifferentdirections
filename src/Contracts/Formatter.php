<?php
namespace Ftob\AllDifferentDirections\Contracts;


/**
 * Interface Formatter
 * @package Ftob\AllDifferentDirections
 */
interface Formatter
{
    public function setFormat(string $format);

    public function getFormat(): string;
}