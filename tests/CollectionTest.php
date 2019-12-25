<?php

namespace Pop\Utils\Test;

use Pop\Utils\Collection;
use Pop\Utils\ArrayObject;
use Pop\Utils\Test\TestAsset\MockIterator;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{

    public function testConstructor()
    {
        $collection = new Collection([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);
        $this->assertInstanceOf('Pop\Utils\Collection', $collection);
        $this->assertEquals(2, count($collection->toArray()));
    }

    public function testFirstAndLast()
    {
        $collection = new Collection([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        $first = $collection->first();
        $last  = $collection->last();
        $this->assertEquals(1, $first['id']);
        $this->assertEquals(2, $last['id']);
    }

    public function testNextAndCurrent()
    {
        $collection = new Collection([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        $next    = $collection->next();
        $current = $collection->current();
        $this->assertEquals(2, $next['id']);
        $this->assertEquals(2, $current['id']);
        $this->assertEquals(1, $collection->key());
    }

    public function testContains()
    {
        $collection = new Collection([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        $this->assertTrue($collection->contains([
            'id'   => 1,
            'name' => 'John'
        ]));
    }

    public function testHas()
    {
        $collection = new Collection([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        $this->assertTrue($collection->has(1));
    }

    public function testIsEmpty()
    {
        $collection = new Collection([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        $this->assertFalse($collection->isEmpty());
    }

    public function testKeysAndValues()
    {
        $collection = new Collection([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        $this->assertEquals(2, count($collection->keys()));
        $this->assertEquals(2, count($collection->values()));
    }

    public function testEach()
    {
        $collection = new Collection([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        ob_start();
        $collection->each(function($data, $key) {
            echo $data['name'];
        });
        $contents = ob_get_clean();

        $this->assertEquals('JohnJane', $contents);
    }

    public function testEachFail()
    {
        $collection = new Collection([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        ob_start();
        $collection->each(function($data, $key) {
            if ($data['name'] == 'Jane') {
                return false;
            }
            echo $data['name'];
        });
        $contents = ob_get_clean();

        $this->assertEquals('John', $contents);
    }

    public function testEvery()
    {
        $collection = new Collection([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        $newCollection = $collection->every(2);

        $this->assertEquals(1, $newCollection->count());
    }

    public function testFilter()
    {
        $collection = new Collection([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        $newCollection = $collection->filter(function($data) {
            if ($data['id'] == 1) {
                return $data;
            }
        });

        $this->assertEquals(1, $newCollection->count());
    }

    public function testMap()
    {
        $collection = new Collection([' John ', ' Jane ']);

        $newCollection = $collection->map('trim');

        $this->assertEquals('John', $newCollection->{0});
        $this->assertEquals('Jane', $newCollection[1]);
    }

    public function testFlip()
    {
        $collection = new Collection([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        $newCollection = $collection->flip();

        $this->assertEquals('name', $newCollection[0]['John']);
    }

    public function testMerge()
    {
        $collection = new Collection([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        $newCollection1 = $collection->merge([
            [
                'id'   => 3,
                'name' => 'Bob'
            ],
            [
                'id'   => 4,
                'name' => 'Billy'
            ]
        ]);
        $newCollection2 = $collection->merge([
            [
                'id'   => 3,
                'name' => 'Bob'
            ],
            [
                'id'   => 4,
                'name' => 'Billy'
            ]
        ], true);

        $this->assertEquals(4, $newCollection1->count());
        $this->assertEquals(2, $newCollection1->forPage(2, 2)->count());

        $this->assertEquals(4, $newCollection2->count());
        $this->assertEquals(2, $newCollection2->forPage(2, 2)->count());
    }

    public function testPop()
    {
        $collection = new Collection([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        $data = $collection->pop();

        $this->assertEquals(2, count($data));
        $collection->push(            [
            'id'   => 3,
            'name' => 'Bob'
        ]);

        $this->assertEquals(2, $collection->count());
    }

    public function testShift()
    {
        $collection = new Collection([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        $data = $collection->shift();

        $this->assertEquals(2, count($data));
        $this->assertEquals(1, $collection->count());
    }

    public function testSplice()
    {
        $collection = new Collection([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        $newCollection1 = $collection->splice(0, 1);
        $newCollection2 = $collection->splice(0);

        $this->assertEquals(1, $newCollection1->count());
        $this->assertEquals(1, $newCollection2->count());
    }

    public function testSort()
    {
        $collection = new Collection([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        $newCollection1 = $collection->sortByAsc();
        $newCollection2 = $collection->sortByDesc();
        $newCollection3 = $collection->sort();

        $this->assertEquals(2, $newCollection1->count());
        $this->assertEquals(2, $newCollection2->count());
        $this->assertEquals(2, $newCollection3->count());
    }

    public function testSortWithCallback()
    {
        $collection = new Collection([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        $newCollection = $collection->sort(function($a, $b){
            return ($a < $b) ? -1 : 1;
        });
        $this->assertEquals(2, $newCollection->count());
    }

    public function testUnset()
    {
        $collection = new Collection([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        unset($collection[0]);
        $this->assertEquals(1, $collection->count());
    }

    public function testToArray()
    {
        $collection = new Collection([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        $ary = $collection->toArray();
        $this->assertTrue(is_array($ary));
        $this->assertEquals(2, count($ary));
    }

    public function testIterator()
    {
        $collection = new Collection([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        $string = '';

        foreach ($collection as $row) {
            $string .= $row['id'] . $row['name'];
        }

        $this->assertEquals('1John2Jane', $string);
    }

    public function testSetWithName()
    {
        $collection = new Collection();
        $collection->foo = ['bar' => 'baz'];
        $this->assertTrue(is_array($collection->foo));
        $this->assertEquals('baz', $collection->foo['bar']);
    }

    public function testGetDataAsArrayWithSelf()
    {
        $datas = [
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ];

        $collection1 = new Collection($datas);
        $collection2 = new Collection($collection1);

        $this->assertTrue(($datas === $collection2->toArray()));
    }

    public function testGetDataAsArrayWithArrayObject()
    {
        $datas = new \ArrayObject([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ], \ArrayObject::ARRAY_AS_PROPS);

        $collection = new Collection($datas);
        $this->assertTrue(is_array($collection->toArray()));
    }

    public function testGetDataAsArrayWithUtilsArrayObject()
    {
        $datas = new ArrayObject([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        $collection = new Collection($datas);
        $this->assertTrue(is_array($collection->toArray()));
    }

    public function testGetDataAsArrayWithIterator()
    {
        $datas = new MockIterator([
            [
                'id'   => 1,
                'name' => 'John'
            ],
            [
                'id'   => 2,
                'name' => 'Jane'
            ]
        ]);

        $collection = new Collection($datas);
        $this->assertTrue(is_array($collection->toArray()));
    }


}