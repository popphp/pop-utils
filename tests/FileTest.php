<?php

namespace Pop\Utils\Test;

use Pop\Utils\File;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{

    public function testConstructor()
    {
        $file = new File(__DIR__ . '/tmp/test.txt');
        $this->assertInstanceOf('Pop\Utils\File', $file);
        $this->assertTrue($file->hasBasename());
        $this->assertTrue($file->hasFilename());
        $this->assertTrue($file->hasExtension());
        $this->assertTrue($file->hasPath());
        $this->assertTrue($file->hasSize());
        $this->assertTrue($file->hasMimeType());
        $this->assertFalse($file->isDefaultMimeType());
        $this->assertEquals('test.txt', $file->getBasename());
        $this->assertEquals('test', $file->getFilename());
        $this->assertEquals('txt', $file->getExtension());
        $this->assertEquals('text/plain', $file->getMimeType());
        $this->assertEquals(__DIR__ . '/tmp', $file->getPath());
        $this->assertGreaterThan(10, $file->getSize());
        $this->assertEquals('Hello World!', trim($file->getContents()));
        $this->assertEquals(__DIR__ . '/tmp/test.txt', (string)$file);

    }

    public function testConstructorException()
    {
        $this->expectException('Pop\Utils\Exception');
        $file = new File(__DIR__ . '/tmp/bad.file');
    }

    public function testDefaultMimeType()
    {
        $file = new File(__DIR__ . '/tmp/test.foo');
        $this->assertEquals($file->getDefaultMimeType(), $file->getMimeType());
    }

    public function testGetMimeTypes()
    {
        $mimeTypes = File::getMimeTypes();
        $this->assertTrue(is_array($mimeTypes));
        $this->assertTrue(isset($mimeTypes['jpeg']));
        $this->assertEquals('image/jpeg', $mimeTypes['jpeg']);
        $this->assertEquals('text/plain', File::getFileMimeType(__DIR__ . '/tmp/test.txt'));
    }

}