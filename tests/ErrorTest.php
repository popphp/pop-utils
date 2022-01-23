<?php

namespace Pop\Utils\Test;

use PHPUnit\Framework\TestCase;
use Pop\Utils\Test\TestAsset\TestError;

class ErrorTest extends TestCase
{

    public function testSetErrorCode()
    {
        $testError = new TestError();
        $testError->setErrorCode(1);
        $this->assertTrue($testError->hasError());
        $this->assertTrue($testError->isError());
    }

    public function testSetErrorCodes()
    {
        $testError = new TestError();
        $testError->setErrorCodes([1, 2]);
        $this->assertEquals([1, 2], $testError->getErrorCodes());
    }

    public function testSetErrorCodeException()
    {
        $this->expectException('Pop\Utils\Exception');
        $testError = new TestError();
        $testError->setErrorCode(0);
    }

    public function testAddErrorCodeException()
    {
        $this->expectException('Pop\Utils\Exception');
        $testError = new TestError();
        $testError->addErrorCode(0);
    }

    public function testGetErrorCodes()
    {
        $testError = new TestError();
        $testError->setErrorCode(1)
            ->addErrorCode(2)
            ->addErrorCode(4);
        $this->assertEquals([1, 2, 4], $testError->getErrorCodes());
    }

    public function testGetErrorMessages()
    {
        $testError = new TestError();
        $testError->setErrorCode(1)
            ->addErrorCode(2)
            ->addErrorCode(4);
        $this->assertEquals([
            1 => 'Error #1',
            2 => 'Error #2',
            4 => 'Error #4'
        ], $testError->getErrorMessages());
    }

    public function testGetAllErrorCodes()
    {
        $testError = new TestError();
        $this->assertEquals([1, 2, 3, 4, 5], $testError->getAllErrorCodes());
    }

    public function testGetAllErrorMessages()
    {
        $testError = new TestError();
        $this->assertEquals([
            1 => 'Error #1',
            2 => 'Error #2',
            3 => 'Error #3',
            4 => 'Error #4',
            5 => 'Error #5',
        ], $testError->getAllErrorMessages());
    }

    public function testGetErrorMessage()
    {
        $testError = new TestError();
        $this->assertEquals('Error #1', $testError->getErrorMessage(1));
    }

    public function testGetErrorMessageException()
    {
        $this->expectException('Pop\Utils\Exception');
        $testError = new TestError();
        $this->assertEquals('Error #1', $testError->getErrorMessage(0));
    }

}