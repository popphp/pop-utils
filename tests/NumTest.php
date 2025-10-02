<?php

namespace Pop\Utils\Test;

use Pop\Utils\Num;
use PHPUnit\Framework\TestCase;

class NumTest extends TestCase
{

//echo Num::percentage($num);
//echo Num::float($num);
//echo Num::currency($num);
//echo Num::trim($num);
//echo Num::abbreviate($num, 0);

    public function testFloat()
    {
        $this->assertEquals(123.45, Num::float('123.45'));
    }

    public function testCurrency()
    {
        $this->assertEquals('$1,234.56', Num::currency(1234.5643));
    }

    public function testPercentage1()
    {
        $this->assertEquals('12.12%', Num::percentage(12.12));
    }

    public function testPercentage2()
    {
        $this->assertEquals('12.12%', Num::convertPercentage(0.1212));
    }

    public function testAbbreviate()
    {
        $this->assertEquals('12.34M', Num::abbreviate(12341234));
        $this->assertEquals('1.23K', Num::abbreviate(1234));

        $this->assertEquals('123', Num::abbreviate(123));
    }

    public function testReadable()
    {
        $this->assertEquals('12 Million', Num::readable(12341234));
        $this->assertEquals('12 Thousand', Num::readable(12345));
        $this->assertEquals('123', Num::readable(123));
    }

}
