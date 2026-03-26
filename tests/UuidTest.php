<?php

namespace Pop\Utils\Test;

use Pop\Utils\Uuid;
use PHPUnit\Framework\TestCase;

class UuidTest extends TestCase
{

    public function testV4()
    {
        $uuidV4 = Uuid::v4();
        $parts  = explode('-', $uuidV4);
        $this->assertEquals(36, strlen($uuidV4));
        $this->assertEquals(5, count($parts));
        $this->assertEquals('4', substr($parts[2], 0, 1));
    }

    public function testV4Linux()
    {
        $this->assertTrue(Uuid::v4LinuxAvailable());
        $uuidV4 = Uuid::v4Linux();
        $parts  = explode('-', $uuidV4);
        $this->assertEquals(36, strlen($uuidV4));
        $this->assertEquals(5, count($parts));
        $this->assertEquals('4', substr($parts[2], 0, 1));
    }

    public function testV4LinuxException()
    {
        $this->expectException('Pop\Utils\Exception');
        $uuidV4 = Uuid::v4Linux('/bad/file');
    }

    public function testV7()
    {
        $uuidV7 = Uuid::v7();
        $parts  = explode('-', $uuidV7);
        $this->assertEquals(35, strlen($uuidV7));
        $this->assertEquals(5, count($parts));
        $this->assertEquals('7', substr($parts[2], 0, 1));
    }

}
