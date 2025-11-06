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
        $this->assertTrue($file->exists());
        $this->assertEquals('test.txt', $file->getBasename());
        $this->assertEquals('test', $file->getFilename());
        $this->assertEquals('txt', $file->getExtension());
        $this->assertEquals('text/plain', $file->getMimeType());
        $this->assertEquals(__DIR__ . '/tmp', $file->getPath());
        $this->assertGreaterThan(10, $file->getSize());
        $this->assertEquals('Hello World!', trim($file->getContents()));
        $this->assertEquals(__DIR__ . '/tmp/test.txt', (string)$file);
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

    public function testIsFileType()
    {
        $this->assertTrue(File::isImage('image.psd'));
        $this->assertFalse(File::isImage('not-an-image.txt'));
        $this->assertTrue(File::isWebImage('image.jpg'));
        $this->assertFalse(File::isWebImage('not-an-image.txt'));
        $this->assertTrue(File::isVideo('video.mp4'));
        $this->assertFalse(File::isVideo('not-a-video.txt'));
        $this->assertTrue(File::isAudio('track.wav'));
        $this->assertFalse(File::isAudio('test.txt'));
        $this->assertTrue(File::isText('test.txt'));
        $this->assertFalse(File::isText('not-a-text.jpg'));
        $this->assertTrue(File::isCompressed('compressed.tar.bz2'));
        $this->assertFalse(File::isCompressed('not-compressed.txt'));
        $this->assertTrue(File::isWord('document.docx'));
        $this->assertFalse(File::isWord('not-a-document.txt'));
        $this->assertTrue(File::isPdf('document.pdf'));
        $this->assertFalse(File::isPdf('not-a-document.txt'));
    }

    public function testFormatFileSize1()
    {
        $file = new File(__DIR__ . '/tmp/test.txt');
        $this->assertEquals('13 B', $file->formatSize());
    }

    public function testFormatFileSize2()
    {
        $this->assertEquals('1.23 TB', File::formatFileSize(1234657890000));
        $this->assertEquals('1.23 GB', File::formatFileSize(1234657890));
        $this->assertEquals('1.23 MB', File::formatFileSize(1234657));
        $this->assertEquals('1.23 KB', File::formatFileSize(1234));
    }

    public function testToArray()
    {
        $file = new File(__DIR__ . '/tmp/test.txt');
        $fileArray = $file->toArray();
        $this->assertTrue(is_array($fileArray));
        $this->assertTrue(isset($fileArray['basename']));
        $this->assertTrue(isset($fileArray['filename']));
        $this->assertTrue(isset($fileArray['extension']));
        $this->assertTrue(isset($fileArray['path']));
        $this->assertTrue(isset($fileArray['size']));
        $this->assertTrue(isset($fileArray['mime_type']));
    }

}
