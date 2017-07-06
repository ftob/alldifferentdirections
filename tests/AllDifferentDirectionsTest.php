<?php

namespace Ftob\AllDifferentDirections\Tests;
use Ftob\AllDifferentDirections\DifferentDirections;
use Ftob\AllDifferentDirections\Direction;
use Ftob\AllDifferentDirections\Directions;

/**
 * Test different directions
 * @author Nikita Volkov
 */

class AllDifferentDirectionsTest extends \PHPUnit_Framework_TestCase{

    /**
     * @return array
     */
    public function dataProviderPeopleAnswers()
    {
        return [
            ["87.342 34.30 start 0 walk 10.0"],
            ["2.6762 75.2811 start -45.0 walk 40 turn 40.0 walk 60"],
            ["58.518 93.508 start 270 walk 50 turn 90 walk 40 turn 13 walk 5"],
            ["30 40 start 90 walk 5"],
            ["40 50 start 180 walk 10 turn 90 walk 5"],
        ];
    }

    public function dataProviderFullText()
    {
        return [
            [
                [
                "3",
                "87.342 34.30 start 0 walk 10.0",
                "2.6762 75.2811 start -45.0 walk 40 turn 40.0 walk 60",
                "58.518 93.508 start 270 walk 50 turn 90 walk 40 turn 13 walk 5",
                "2",
                "30 40 start 90 walk 5",
                "40 50 start 180 walk 10 turn 90 walk 5",
                "0"
                    ]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderPeopleAnswers
     * @param string $payload
     */
    public function testDirections_addDirection($payload) {
        $d = new Directions();

        $d->addDirection($payload);
        /** @var Direction $direction */
        foreach ($d->getDirections() as $direction) {
            $this->assertInternalType('float', $direction->getX());
            $this->assertInternalType('float', $direction->getY());
        }

    }

    /**
     * @dataProvider dataProviderPeopleAnswers
     * @param $payload
     */
    public function testDirections_getAvgDestination($payload) {
        $d = new Directions();

        $d->addDirection($payload);
        $res = $d->getAvgDestination($d->getAvgDirection());

        $this->assertInternalType('float', $res);
    }
    /**
     * @dataProvider dataProviderPeopleAnswers
     * @param string $payload
     */
    public function testDirections_getAvgDirection($payload) {
        $d = new Directions();

        $d->addDirection($payload);

        $res = $d->getAvgDirection();
        $this->assertInternalType('float', $res->getY());
        $this->assertInternalType('float', $res->getX());

    }

    /**
     * @dataProvider dataProviderFullText
     * @param $content
     */
    public function testDifferentDirections_all($content) {
        $file = $this->getMockBuilder(\SplFileObject::class)
            ->setConstructorArgs(['/dev/null'])
            ->getMock();

        $calls = 0;
        $file->expects($this->exactly(count($content)))
            ->method('fgets')->will($this->returnCallback(function() use (&$calls, $content){
            return $content[$calls++];
        }));
        /** @var \SplFileObject $file */
        $d = new DifferentDirections($file, new Directions());
        $res = $d->all();
        $this->assertInternalType('array', $res);

    }
    /**
     * @dataProvider dataProviderFullText
     * @param $content
     */
    public function testDifferentDirections___toString($content) {
        $file = $this->getMockBuilder(\SplFileObject::class)
            ->setConstructorArgs(['/dev/null'])
            ->getMock();


        $calls = 0;

        $file->expects($this->exactly(count($content)))
            ->method('fgets')->will($this->returnCallback(function() use (&$calls, $content){
                return $content[$calls++];
            }));
        /** @var \SplFileObject $file */
        $d = new DifferentDirections($file, new Directions());

        $this->assertInternalType('string', (string)$d);

    }


}