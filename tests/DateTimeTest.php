<?php

namespace Pop\Utils\Test;

use Pop\Utils\DateTime;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{

    public function testCreate()
    {
        $dateTime = DateTime::create('now', null, 'Y-m-d');
        $this->assertInstanceOf('Pop\Utils\DateTime', $dateTime);
    }

    public function testDateTimeFormats()
    {
        $dateTime = DateTime::create('now', null, 'Y-m-d', 'H:i:s');
        $this->assertTrue($dateTime->hasDefaultDateFormat());
        $this->assertTrue($dateTime->hasDefaultTimeFormat());
        $this->assertEquals('Y-m-d', $dateTime->getDefaultDateFormat());
        $this->assertEquals('H:i:s', $dateTime->getDefaultTimeFormat());
    }

    public function testTotal()
    {
        $times = [
            new \DateInterval('PT12H45M18S'),
            '02:13:58',
            '08:05:09',
               '05:09',
        ];
        $dateTime = DateTime::getTotal($times, '%H:%I:%S');
        $this->assertEquals('22:68:94', $dateTime);
    }

    public function testAverage1()
    {
        $times = [
            '12:45:18',
            '02:13:58',
            '08:05:09'
        ];
        $dateTime = DateTime::getAverage($times, '%H:%I:%S');
        $this->assertEquals('07:41:28', $dateTime);
    }

    public function testAverage2()
    {
        $times = [
            '12:45:18',
            '02:13:58',
            '08:05:09'
        ];
        $dateTime = DateTime::getAverage($times, '%S', true);
        $this->assertEquals('27660', $dateTime);
    }

    public function testAverage3()
    {
        $times = [
            '00:13:58',
            '00:05:09'
        ];
        $dateTime = DateTime::getAverage($times, '%H:%I:%S');
        $this->assertEquals('00:09:33', $dateTime);
    }

    public function testAverage4()
    {
        $times = [
            '00:00:18',
            '00:00:08'
        ];
        $dateTime = DateTime::getAverage($times, '%H:%I:%S');
        $this->assertEquals('00:00:13', $dateTime);
    }

    public function testToString1()
    {
        $dateTime = DateTime::create('12/16/21', null, 'Y-m-d');
        $this->assertEquals('2021-12-16', (string)$dateTime);
    }

    public function testToString2()
    {
        $dateTime = DateTime::create('12/16/21 6:32 PM', null, 'Y-m-d', 'H:i:s');
        $this->assertEquals('2021-12-16 18:32:00', (string)$dateTime);
    }

}