<?php

namespace NenadalM\HoursCounter\Tests\Unit;

use NenadalM\HoursCounter\Time;

class TimeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider timeAddWithResultsProvider
     */
    public function testAddShouldAddUpTwoTimesCorrectly(Time $time1, Time $time2, $result)
    {
        $this->assertEquals($result, (string) $time1->add($time2));
    }

    public function timeAddWithResultsProvider()
    {
        return [
            [new Time(5, 25), new Time(3, 25), '8:50 (8.833333)'],
            [new Time(10, 32), new Time(3, 48), '14:20 (14.333333)'],
            [new Time(11, 58), new Time(16, 35), '28:33 (28.550000)'],
            [new Time(3, 2), new Time(5, 3), '8:05 (8.083333)'],
        ];
    }

    /**
     * @dataProvider timeSubbWithResultsProvider
     */
    public function testSubShouldSubbUpTwoTimesCorrectly(Time $time1, Time $time2, $result)
    {
        $this->assertEquals($result, (string) $time1->sub($time2));
    }

    public function timeSubbWithResultsProvider()
    {
        return [
            [new Time(5, 25), new Time(3, 25), '2:00 (2.000000)'],
            [new Time(10, 32), new Time(3, 48), '6:44 (6.733333)'],
        ];
    }

    public function testCreateFromTimeStringShouldReturnCorrectTime()
    {
        $this->assertEquals('3:25 (3.416667)', (string) Time::createFromTimeString('3:25'));
    }

    public function testCreateFromIntervalStringShouldReturnCorrectTime()
    {
        $this->assertEquals('1:00 (1.000000)', (string) Time::createFromIntervalString('3:25 - 4:25'));
    }
}
