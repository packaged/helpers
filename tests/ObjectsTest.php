<?php
namespace Packaged\Tests;

use InvalidArgumentException;
use Packaged\Helpers\Branch;
use Packaged\Helpers\Objects;
use Packaged\Helpers\Strings;
use Packaged\Tests\Objects\MFilterTestHelper;
use Packaged\Tests\Objects\Pancake;
use Packaged\Tests\Objects\PropertyClass;
use Packaged\Tests\Objects\Thing;
use Packaged\Tests\Objects\TreeThing;
use PHPUnit_Framework_TestCase;
use stdClass;

class ObjectsTest extends PHPUnit_Framework_TestCase
{
  public function testMFilterNullMethodThrowException()
  {
    $caught = null;
    try
    {
      Objects::mfilter([], null);
    }
    catch(InvalidArgumentException $ex)
    {
      $caught = $ex;
    }

    $this->assertEquals(true, ($caught instanceof InvalidArgumentException));
  }

  public function testMFilterWithEmptyValueFiltered()
  {
    $a = new MFilterTestHelper('o', 'p', 'q');
    $b = new MFilterTestHelper('o', '', 'q');
    $c = new MFilterTestHelper('o', 'p', 'q');

    $list = [
      'a' => $a,
      'b' => $b,
      'c' => $c,
    ];

    $actual = Objects::mfilter($list, 'getI');
    $expected = [
      'a' => $a,
      'c' => $c,
    ];

    $this->assertEquals($expected, $actual);
  }

  public function testMFilterWithEmptyValueNegateFiltered()
  {
    $a = new MFilterTestHelper('o', 'p', 'q');
    $b = new MFilterTestHelper('o', '', 'q');
    $c = new MFilterTestHelper('o', 'p', 'q');

    $list = [
      'a' => $a,
      'b' => $b,
      'c' => $c,
    ];

    $actual = Objects::mfilter($list, 'getI', true);
    $expected = [
      'b' => $b,
    ];

    $this->assertEquals($expected, $actual);
  }

  public function testNewv()
  {
    $expect = new Pancake('Blueberry', "Maple Syrup");
    $this->assertEquals(
      $expect,
      Objects::create('Packaged\Tests\Objects\Pancake', ['Blueberry', "Maple Syrup"])
    );
    $expect = new Pancake();
    $this->assertEquals(
      $expect,
      Objects::create('Packaged\Tests\Objects\Pancake', [])
    );
  }

  public function testPropertyNonEmpty()
  {
    $object = new PropertyClass();
    $object->name = 't_name';
    $object->age = 't_age';

    $this->assertEquals(
      't_age',
      Objects::pnonempty($object, ['miss', 'age', 'name'])
    );
    $this->assertNull(Objects::pnonempty($object, ['miss1', 'miss2']));
    $this->assertNull(Objects::pnonempty($object, []));
    $this->assertEquals(
      'no',
      Objects::pnonempty($object, ['miss1', 'miss2'], 'no')
    );
    $this->assertEquals('no', Objects::pnonempty($object, [], 'no'));
  }

  public function testHydrate()
  {
    $dest = new stdClass();
    $dest->nullify = 'Please';

    $source = new PropertyClass();
    $source->name = 'Test';
    $source->age = 19;
    $source->nullify = null;

    Objects::hydrate($dest, $source, [null]);
    $this->assertEquals('Please', $dest->nullify);

    Objects::hydrate($dest, $source, ['nullify'], false);
    $this->assertEquals('Please', $dest->nullify);

    Objects::hydrate($dest, $source, ['nullify'], true);
    $this->assertNull($dest->nullify);

    Objects::hydrate($dest, $source, ['name']);

    $this->assertObjectHasAttribute('name', $dest);
    $this->assertEquals('Test', $dest->name);

    $this->assertObjectNotHasAttribute('age', $dest);
    Objects::hydrate($dest, $source, ['age']);

    $this->assertObjectHasAttribute('age', $dest);
    $this->assertEquals('19', $dest->age);

    $dest->name = null;
    Objects::hydrate($dest, $source);
    $this->assertObjectHasAttribute('name', $dest);
    $this->assertEquals('Test', $dest->name);

    $this->setExpectedException("Exception");
    Objects::hydrate(['' => ''], $source, []);
  }

  public function testClassShortName()
  {
    $expectations = [
      ['Strings', "Strings"],
      ['\Packaged\Helpers\Strings', "Strings"],
      [new Strings(), "Strings"],
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals($expect[1], Objects::classShortname($expect[0]));
    }
  }

  public function testProperties()
  {
    $expect = ['name' => null, 'age' => null];
    $class = new PropertyClass();
    $this->assertNotEquals($expect, $class->objectVars());
    $this->assertEquals($expect, $class->publicVars());
    $this->assertEquals($expect, get_object_vars($class));
    $this->assertEquals($expect, Objects::propertyValues($class));
    $this->assertEquals(['name', 'age'], Objects::properties($class));
  }

  public function testIdp()
  {
    $object = new stdClass();
    $object->name = "apple";
    $this->assertEquals("apple", Objects::property($object, "name", "pear"));
    $this->assertEquals(
      "orange",
      Objects::property($object, "noprop", "orange")
    );
  }

  public function testGetNamespace()
  {
    $expectations = [
      [null, ''],
      ['', ''],
      ['Strings', ''],
      ['\Packaged\Helpers\Strings', '\Packaged\Helpers'],
      [new Strings(), '\Packaged\Helpers'],
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals($expect[1], Objects::getNamespace($expect[0]));
    }
  }

  public function testMpull()
  {
    $a = new MFilterTestHelper('1', 'a', 'q');
    $b = new MFilterTestHelper('2', 'b', 'q');
    $c = new MFilterTestHelper('3', 'c', 'q');
    $list = [$a, $b, $c];

    $expected = [1, 2, 3];
    $this->assertEquals($expected, Objects::mpull($list, 'getH'));

    $expected = ['a' => 1, 'b' => 2, 'c' => 3];
    $this->assertEquals($expected, Objects::mpull($list, 'getH', 'getI'));

    $expected = ['a' => $a, 'b' => $b, 'c' => $c];
    $this->assertEquals($expected, Objects::mpull($list, null, 'getI'));
  }

  public function testPpull()
  {
    $a = new stdClass();
    $a->name = "a";
    $a->value = 1;
    $b = new stdClass();
    $b->name = "b";
    $b->value = 2;
    $c = new stdClass();
    $c->name = "c";
    $c->value = 3;
    $list = [$a, $b, $c];

    $expected = ["a", "b", "c"];
    $this->assertEquals($expected, Objects::ppull($list, 'name'));

    $expected = ['a' => 1, 'b' => 2, 'c' => 3];
    $this->assertEquals($expected, Objects::ppull($list, 'value', 'name'));

    $expected = ['a' => $a, 'b' => $b, 'c' => $c];
    $this->assertEquals($expected, Objects::ppull($list, null, 'name'));
  }

  public function testApull()
  {
    $a = new stdClass();
    $a->name = "a";
    $a->value1 = 1;
    $a->value2 = 2;
    $b = new stdClass();
    $b->name = "b";
    $b->value1 = 2;
    $b->value2 = 3;
    $c = new stdClass();
    $c->name = "c";
    $c->value1 = 3;
    $c->value2 = 4;
    $list = [$a, $b, $c];

    $this->assertEquals(
      [
        'a' => ['value1' => 1, 'value2' => 2],
        'b' => ['value1' => 2, 'value2' => 3],
        'c' => ['value1' => 3, 'value2' => 4],
      ],
      Objects::apull($list, ['value1', 'value2'], 'name')
    );
  }

  public function testMsort()
  {
    $a = new MFilterTestHelper('1', 'a', 'q');
    $b = new MFilterTestHelper('2', 'b', 'q');
    $c = new MFilterTestHelper('3', 'c', 'q');
    $list = ["b" => $b, "a" => $a, "c" => $c];

    $expected = ["a" => $a, "b" => $b, "c" => $c];
    $this->assertEquals($expected, Objects::msort($list, 'getI'));
  }

  public function testMGroup()
  {
    $apple = new Thing('Apple', 'fruit', 'green', 'food');
    $bear = new Thing('Bear', 'animal', 'brown', 'creature');
    $carrot = new Thing('Carrot', 'vegetable', 'brown', 'food');

    $list = ['a' => $apple, 'b' => $bear, 'c' => $carrot];

    $expect = [
      'fruit'     => ['a' => $apple],
      'animal'    => ['b' => $bear],
      'vegetable' => ['c' => $carrot],
    ];
    $this->assertEquals($expect, Objects::mgroup($list, 'type'));

    $expect = [
      'food'     => [
        'fruit'     => ['a' => $apple],
        'vegetable' => ['c' => $carrot],
      ],
      'creature' => [
        'animal' => ['b' => $bear],
      ],
    ];
    $this->assertEquals($expect, Objects::mgroup($list, 'group', 'type'));

    $expect = [
      'food'     => [
        'a' => $apple,
        'c' => $carrot,
      ],
      'creature' => [
        'b' => $bear,
      ],
    ];
    $this->assertEquals($expect, Objects::mgroup($list, 'group'));
  }

  public function testPGroup()
  {
    $apple = new Thing('Apple', 'fruit', 'green', 'food');
    $bear = new Thing('Bear', 'animal', 'brown', 'creature');
    $carrot = new Thing('Carrot', 'vegetable', 'brown', 'food');

    $list = ['a' => $apple, 'b' => $bear, 'c' => $carrot];

    $expect = [
      'fruit'     => ['a' => $apple],
      'animal'    => ['b' => $bear],
      'vegetable' => ['c' => $carrot],
    ];
    $this->assertEquals($expect, Objects::pgroup($list, 'typeProperty'));

    $expect = [
      'food'     => [
        'fruit'     => ['a' => $apple],
        'vegetable' => ['c' => $carrot],
      ],
      'creature' => [
        'animal' => ['b' => $bear],
      ],
    ];
    $this->assertEquals(
      $expect,
      Objects::pgroup($list, 'groupProperty', 'typeProperty')
    );

    $expect = [
      'food'     => [
        'a' => $apple,
        'c' => $carrot,
      ],
      'creature' => [
        'b' => $bear,
      ],
    ];
    $this->assertEquals($expect, Objects::pgroup($list, 'groupProperty'));
  }

  public function testXGroup()
  {
    $apple = new Thing('Apple', 'fruit', 'green', 'food');
    $bear = new Thing('Bear', 'animal', 'brown', 'creature');
    $carrot = new Thing('Carrot', 'vegetable', 'brown', 'food');

    $list = ['a' => $apple, 'b' => $bear, 'c' => $carrot];

    $expect = [
      'food'    => [
        'a' => $apple,
        'c' => $carrot,
      ],
      'general' => [
        'b' => $bear,
      ],
    ];
    $this->assertEquals(
      $expect,
      Objects::xgroup($list, 'typeProperty', ['fruit' => 'food', 'vegetable' => 'food'], 'general')
    );
  }

  public function testPsort()
  {
    $apple = new stdClass();
    $apple->name = "apple";
    $pear = new stdClass();
    $pear->name = "pear";
    $grape = new stdClass();
    $grape->name = "grape";

    $expectations = [
      [
        ["apple" => $apple, "pear" => $pear, "grape" => $grape],
        "name",
        ["apple" => $apple, "grape" => $grape, "pear" => $pear],
      ],
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals($expect[2], Objects::psort($expect[0], $expect[1]));
    }
  }

  public function testWith()
  {
    /** @var \Packaged\Tests\Objects\Pancake $pancake */
    $pancake = Objects::with(
      new Pancake(),
      function (Pancake $p) {
        $p->fruit = 'Apples';
      }
    );
    $this->assertEquals('Apples', $pancake->getFruit());
  }

  public function testTreeP()
  {
    $tree = Objects::pTree([], 'id', 'parentId');
    $this->assertInstanceOf(Branch::class, $tree);
    $this->assertFalse($tree->hasChildren());

    $tree = Objects::pTree([(object)['id' => 0, 'parentId' => null]], 'id', 'parentId');
    $this->assertInstanceOf(Branch::class, $tree);
    $this->assertTrue($tree->hasChildren());
    $this->assertContainsOnlyInstancesOf(Branch::class, $tree->getChildren());
    $this->assertCount(1, $tree->getChildren());
  }

  public function testTreeM()
  {

    $tree = Objects::mTree([], 'getId', 'getParentId');
    $this->assertInstanceOf(Branch::class, $tree);
    $this->assertFalse($tree->hasChildren());

    $tree = Objects::mTree([new TreeThing(0, null, 'value', [])], 'getId', 'getParentId');
    $this->assertInstanceOf(Branch::class, $tree);
    $this->assertTrue($tree->hasChildren());
    $this->assertContainsOnlyInstancesOf(Branch::class, $tree->getChildren());
    $this->assertCount(1, $tree->getChildren());
  }

  public function testPFilterNullPropertyThrowException()
  {
    $caught = null;
    try
    {
      Objects::pfilter([], null, 'abc');
    }
    catch(InvalidArgumentException $ex)
    {
      $caught = $ex;
    }

    $this->assertEquals(true, ($caught instanceof InvalidArgumentException));
  }

  public function testPFilter()
  {
    $a = new Pancake("apple", "toffee");
    $b = new Pancake("apple", "strawberry");
    $c = new Pancake("orange", "toffee");
    $d = new Pancake("orange", "gravy");

    $list = ['a' => $a, 'b' => $b, 'c' => $c, 'd' => $d];

    $actual = Objects::pfilter($list, 'fruit', 'apple');
    $this->assertEquals(['a' => $a, 'b' => $b,], $actual);

    $actual = Objects::pfilter($list, 'fruit', 'apple', true);
    $this->assertEquals(['c' => $c, 'd' => $d,], $actual);

    $matchApple = function ($prop) { return $prop == 'apple'; };

    $actual = Objects::pfilter($list, 'fruit', $matchApple);
    $this->assertEquals(['a' => $a, 'b' => $b,], $actual);

    $actual = Objects::pfilter($list, 'fruit', $matchApple, true);
    $this->assertEquals(['c' => $c, 'd' => $d,], $actual);
  }
}
