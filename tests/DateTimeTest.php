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
        $this->assertEquals('23:09:34', $dateTime);
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

    public function testGetWeekDates1()
    {
        $weekDates = DateTime::getWeekDates();
        $this->assertEquals(7, count($weekDates));
    }

    public function testGetWeekDates2()
    {
        $weekDates = DateTime::getWeekDates(39, 2021);
        $this->assertEquals(7, count($weekDates));
        $this->assertEquals('2021-09-26', $weekDates[0]->format('Y-m-d'));
        $this->assertEquals('2021-09-27', $weekDates[1]->format('Y-m-d'));
        $this->assertEquals('2021-09-28', $weekDates[2]->format('Y-m-d'));
        $this->assertEquals('2021-09-29', $weekDates[3]->format('Y-m-d'));
        $this->assertEquals('2021-09-30', $weekDates[4]->format('Y-m-d'));
        $this->assertEquals('2021-10-01', $weekDates[5]->format('Y-m-d'));
        $this->assertEquals('2021-10-02', $weekDates[6]->format('Y-m-d'));
    }

    public function testGetWeekDates3()
    {
        $weekDates = DateTime::getWeekDates(39, 2021, 'Y-m-d');
        $this->assertEquals(7, count($weekDates));
        $this->assertEquals('2021-09-26', $weekDates[0]);
        $this->assertEquals('2021-09-27', $weekDates[1]);
        $this->assertEquals('2021-09-28', $weekDates[2]);
        $this->assertEquals('2021-09-29', $weekDates[3]);
        $this->assertEquals('2021-09-30', $weekDates[4]);
        $this->assertEquals('2021-10-01', $weekDates[5]);
        $this->assertEquals('2021-10-02', $weekDates[6]);
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

    public function testFormats()
    {
        $this->assertEquals('Y-m-d H:i:s.v', DateTime::detectDateTimeFormat('2024-01-01 12:00:03.002'));
        $this->assertEquals('Y-m-d H:i:s', DateTime::detectDateTimeFormat('2024-01-01 12:00:03'));
        $this->assertEquals('m/d/Y g:i A', DateTime::detectDateTimeFormat('01/01/2024 9:32 AM'));
        $this->assertEquals('m/d/y g:i A', DateTime::detectDateTimeFormat('01/01/24 9:32 AM'));
        $this->assertEquals('Y-m-d', DateTime::detectDateTimeFormat('2024-01-01'));
        $this->assertEquals('m/d/Y', DateTime::detectDateTimeFormat('01/01/2024'));
        $this->assertEquals('d.m.Y', DateTime::detectDateTimeFormat('01.01.2024'));
        $this->assertEquals('m/d/y', DateTime::detectDateTimeFormat('01/01/24'));
        $this->assertEquals('d.m.y', DateTime::detectDateTimeFormat('01.01.24'));
        $this->assertEquals('H:i:s', DateTime::detectDateTimeFormat('12:00:03'));
        $this->assertEquals('g:i A', DateTime::detectDateTimeFormat('9:32 AM'));
    }

}
