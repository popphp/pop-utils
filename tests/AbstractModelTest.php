<?php

namespace Pop\Utils\Test;

use Pop\Utils\AbstractModel;
use Pop\Utils\ArrayObject;
use PHPUnit\Framework\TestCase;

class AbstractModelTest extends TestCase
{

    public function testConstructor()
    {
        $model = new class(['foo' => 'bar']) extends AbstractModel {};
        $this->assertInstanceOf(AbstractModel::class, $model);
        $this->assertInstanceOf(ArrayObject::class, $model);
    }

    public function testGettersAndSetters()
    {
        $model = new class(['foo' => 'bar']) extends AbstractModel {};
        $this->assertEquals('bar', $model->foo);
        $this->assertEquals('bar', $model['foo']);
        $model->baz = '123';
        $this->assertEquals('123', $model['baz']);
    }

    public function testToArray()
    {
        $ary   = ['foo' => 'bar'];
        $model = new class($ary) extends AbstractModel {};
        $this->assertEquals($ary, $model->toArray());
    }

}
