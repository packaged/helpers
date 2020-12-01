<?php
namespace Packaged\Tests;

use Exception;
use InvalidArgumentException;
use Packaged\Helpers\Arrays;
use Packaged\Helpers\Branch;
use Packaged\Helpers\Strings;
use PHPUnit\Framework\TestCase;
use stdClass;

class ArraysTest extends TestCase
{
  public function testIFilterInvalidIndexThrowException()
  {
    $caught = null;
    try
    {
      Arrays::ifilter([], null);
    }
    catch(InvalidArgumentException $ex)
    {
      $caught = $ex;
    }

    static::assertEquals(
      true,
      ($caught instanceof InvalidArgumentException)
    );
  }

  public function testIFilterWithEmptyValueFiltered()
  {
    $list = [
      'a' => ['h' => 'o', 'i' => 'p', 'j' => 'q',],
      'b' => ['h' => 'o', 'i' => '', 'j' => 'q',],
      'c' => ['h' => 'o', 'i' => 'p', 'j' => 'q',],
      'd' => ['h' => 'o', 'i' => 0, 'j' => 'q',],
      'e' => ['h' => 'o', 'i' => null, 'j' => 'q',],
      'f' => ['h' => 'o', 'i' => false, 'j' => 'q',],
    ];

    $actual = Arrays::ifilter($list, 'i');
    $expected = [
      'a' => ['h' => 'o', 'i' => 'p', 'j' => 'q',],
      'c' => ['h' => 'o', 'i' => 'p', 'j' => 'q',],
    ];

    static::assertEquals($expected, $actual);
  }

  public function testIFilterIndexNotExistsAllFiltered()
  {
    $list = [
      'a' => ['h' => 'o', 'i' => 'p', 'j' => 'q',],
      'b' => ['h' => 'o', 'i' => '', 'j' => 'q',],
    ];

    $actual = Arrays::ifilter($list, 'NoneExisting');
    $expected = [];

    static::assertEquals($expected, $actual);
  }

  public function testIFilterWithEmptyValueNegateFiltered()
  {
    $list = [
      'a' => ['h' => 'o', 'i' => 'p', 'j' => 'q',],
      'b' => ['h' => 'o', 'i' => '', 'j' => 'q',],
      'c' => ['h' => 'o', 'i' => 'p', 'j' => 'q',],
      'd' => ['h' => 'o', 'i' => 0, 'j' => 'q',],
      'e' => ['h' => 'o', 'i' => null, 'j' => 'q',],
      'f' => ['h' => 'o', 'i' => false, 'j' => 'q',],
    ];

    $actual = Arrays::ifilter($list, 'i', true);
    $expected = [
      'b' => ['h' => 'o', 'i' => '', 'j' => 'q',],
      'd' => ['h' => 'o', 'i' => 0, 'j' => 'q',],
      'e' => ['h' => 'o', 'i' => null, 'j' => 'q',],
      'f' => ['h' => 'o', 'i' => false, 'j' => 'q',],
    ];

    static::assertEquals($expected, $actual);
  }

  public function testIFilterIndexNotExistsNotFiltered()
  {
    $list = [
      'a' => ['h' => 'o', 'i' => 'p', 'j' => 'q',],
      'b' => ['h' => 'o', 'i' => '', 'j' => 'q',],
    ];

    $actual = Arrays::ifilter($list, 'NoneExisting', true);
    $expected = [
      'a' => ['h' => 'o', 'i' => 'p', 'j' => 'q',],
      'b' => ['h' => 'o', 'i' => '', 'j' => 'q',],
    ];

    static::assertEquals($expected, $actual);
  }

  public function testmergevMergingBasicallyWorksCorrectly()
  {
    static::assertEquals(
      [],
      Arrays::mergev(
        [ // <empty>
        ]
      )
    );

    static::assertEquals(
      [],
      Arrays::mergev(
        [
          [],
          [],
          [],
        ]
      )
    );

    static::assertEquals(
      [1, 2, 3, 4, 5],
      Arrays::mergev(
        [
          [1, 2],
          [3],
          [],
          [4, 5],
        ]
      )
    );
  }

  protected function _tryAssertInstancesOfArray($input)
  {
    Arrays::instancesOf($input, 'array');
  }

  protected function _tryAssertInstancesOfStdClass($input)
  {
    Arrays::instancesOf($input, 'stdClass');
  }

  protected function _tryTestCases(
    array $inputs,
    array $expect,
    $callable,
    $exception_class = 'Exception'
  )
  {

    if(count($inputs) !== count($expect))
    {
      $this->fail(
        "Input and expectations must have the same number of values."
      );
    }

    $labels = array_keys($inputs);
    $inputs = array_values($inputs);
    $expecting = array_values($expect);
    foreach($inputs as $idx => $input)
    {
      $expect = $expecting[$idx];
      $label = $labels[$idx];

      $caught = null;
      try
      {
        call_user_func($callable, $input);
      }
      catch(Exception $ex)
      {
        if(!($ex instanceof $exception_class))
        {
          throw $ex;
        }
        $caught = $ex;
      }

      $actual = !($caught instanceof Exception);

      if($expect === $actual)
      {
        if($expect)
        {
          $message = "Test case '{$label}' did not throw, as expected.";
        }
        else
        {
          $message = "Test case '{$label}' threw, as expected.";
        }
      }
      else
      {
        if($expect && isset($ex) && $ex instanceof Exception)
        {
          $message = "Test case '{$label}' was expected to succeed, but it " .
            "raised an exception of class " . get_class($ex) . " with " .
            "message: " . $ex->getMessage();
        }
        else
        {
          $message = "Test case '{$label}' was expected to raise an " .
            "exception, but it did not throw anything.";
        }
      }

      static::assertEquals($expect, $actual, $message);
    }
  }

  public function testAssertInstancesOf()
  {
    $object = new stdClass();
    $inputs = [
      'empty'               => [],
      'stdClass'            => [$object, $object],
      'PhutilUtilsTestCase' => [$object, $this],
      'array'               => [[], []],
      'integer'             => [$object, 1],
    ];

    $this->_tryTestCases(
      $inputs,
      [true, true, false, false, false],
      [$this, '_tryAssertInstancesOfStdClass'],
      'InvalidArgumentException'
    );

    $this->_tryTestCases(
      $inputs,
      [true, false, false, true, false],
      [$this, '_tryAssertInstancesOfArray'],
      'InvalidArgumentException'
    );
  }

  public function testHeadLast()
  {
    static::assertEquals(
      'a',
      Arrays::first(explode('.', 'a.b'))
    );
    static::assertEquals(
      'b',
      Arrays::last(explode('.', 'a.b'))
    );
  }

  public function testHeadKeyLastKey()
  {
    static::assertEquals(
      'a',
      Arrays::firstKey(['a' => 0, 'b' => 1])
    );
    static::assertEquals(
      'b',
      Arrays::lastKey(['a' => 0, 'b' => 1])
    );
    static::assertEquals(null, Arrays::firstKey([]));
    static::assertEquals(null, Arrays::lastKey([]));
  }

  public function testIdx()
  {
    $array = [
      'present' => true,
      'null'    => null,
    ];
    static::assertEquals(true, Arrays::value($array, 'present'));
    static::assertEquals(true, Arrays::value($array, 'present', false));
    static::assertEquals(null, Arrays::value($array, 'null'));
    static::assertEquals(null, Arrays::value($array, 'null', false));
    static::assertEquals(null, Arrays::value($array, 'missing'));
    static::assertEquals(false, Arrays::value($array, 'missing', false));
  }

  public function testArrayFuse()
  {
    static::assertEquals([], Arrays::fuse([]));
    static::assertEquals(['x' => 'x'], Arrays::fuse(['x']));
  }

  public function testArrayInterleave()
  {
    static::assertEquals([], Arrays::interleave('x', []));
    static::assertEquals(['y'], Arrays::interleave('x', ['y']));

    static::assertEquals(
      ['y', 'x', 'z'],
      Arrays::interleave('x', ['y', 'z'])
    );

    static::assertEquals(
      ['y', 'x', 'z'],
      Arrays::interleave(
        'x',
        [
          'kangaroo' => 'y',
          'marmoset' => 'z',
        ]
      )
    );

    $obj1 = (object)[];
    $obj2 = (object)[];

    static::assertEquals(
      [$obj1, $obj2, $obj1, $obj2, $obj1],
      Arrays::interleave(
        $obj2,
        [
          $obj1,
          $obj1,
          $obj1,
        ]
      )
    );

    $implode_tests = [
      ''  => [1, 2, 3],
      'x' => [1, 2, 3],
      'y' => [],
      'z' => [1],
    ];

    foreach($implode_tests as $x => $y)
    {
      static::assertEquals(
        implode('', Arrays::interleave($x, $y)),
        implode($x, $y)
      );
    }
  }

  public function testIpull()
  {
    $list = [
      ['name' => 'a', 'value' => 1],
      ['name' => 'b', 'value' => 2],
      ['name' => 'c', 'value' => 3],
    ];

    $expected = ["a", "b", "c"];
    static::assertEquals($expected, Arrays::ipull($list, 'name'));

    $expected = ['a' => 1, 'b' => 2, 'c' => 3];
    static::assertEquals($expected, Arrays::ipull($list, 'value', 'name'));

    $expected = [
      'a' => ['name' => 'a', 'value' => 1],
      'b' => ['name' => 'b', 'value' => 2],
      'c' => ['name' => 'c', 'value' => 3],
    ];
    static::assertEquals($expected, Arrays::ipull($list, null, 'name'));
  }

  public function testApull()
  {
    $a = [
      'name'   => "a",
      'value1' => 1,
      'value2' => 2,
    ];
    $b = [
      'name'   => "b",
      'value1' => 2,
      'value2' => 3,
    ];
    $c = [
      'name'   => "c",
      'value1' => 3,
      'value2' => 4,
    ];
    $list = [$a, $b, $c];

    static::assertEquals(
      [
        'a' => ['value1' => 1, 'value2' => 2],
        'b' => ['value1' => 2, 'value2' => 3],
        'c' => ['value1' => 3, 'value2' => 4],
      ],
      Arrays::apull($list, ['value1', 'value2'], 'name')
    );
  }

  public function testIsort()
  {
    $list = [
      'b' => ['name' => 'b', 'value' => 2],
      'a' => ['name' => 'a', 'value' => 1],
      'c' => ['name' => 'c', 'value' => 3],
    ];

    $expected = [
      'a' => ['name' => 'a', 'value' => 1],
      'b' => ['name' => 'b', 'value' => 2],
      'c' => ['name' => 'c', 'value' => 3],
    ];
    static::assertEquals($expected, Arrays::isort($list, 'name'));
  }

  public function testArraySelectKeys()
  {
    $list = [
      'a' => 1,
      'b' => 2,
      'c' => 3,
    ];

    $expect = ['a' => 1, 'b' => 2];
    static::assertEquals($expect, Arrays::selectKeys($list, ['a', 'b']));
  }

  public function testIGroup()
  {
    $apple = [
      'name'   => 'Apple',
      'type'   => 'fruit',
      'colour' => 'green',
      'group'  => 'food',
    ];
    $bear = [
      'name'   => 'Bear',
      'type'   => 'animal',
      'colour' => 'brown',
      'group'  => 'creature',
    ];
    $carrot = [
      'name'   => 'Carrot',
      'type'   => 'vegetable',
      'colour' => 'brown',
      'group'  => 'food',
    ];

    $list = ['a' => $apple, 'b' => $bear, 'c' => $carrot];

    $expect = [
      'fruit'     => ['a' => $apple],
      'animal'    => ['b' => $bear],
      'vegetable' => ['c' => $carrot],
    ];
    static::assertEquals($expect, Arrays::igroup($list, 'type'));

    $expect = [
      'food'     => [
        'fruit'     => ['a' => $apple],
        'vegetable' => ['c' => $carrot],
      ],
      'creature' => [
        'animal' => ['b' => $bear],
      ],
    ];
    static::assertEquals($expect, Arrays::igroup($list, 'group', 'type'));

    $expect = [
      'food'     => [
        'a' => $apple,
        'c' => $carrot,
      ],
      'creature' => [
        'b' => $bear,
      ],
    ];
    static::assertEquals($expect, Arrays::igroup($list, 'group'));
  }

  public function testFlipGroup()
  {
    $input = [
      'com'   => 'classic',
      'net'   => 'classic',
      'news'  => 'new',
      'rocks' => 'new',
      'uk'    => 'country',
      'es'    => 'country',
    ];

    $expect = [
      'classic' => ['com' => 'com', 'net' => 'net'],
      'new'     => ['news' => 'news', 'rocks' => 'rocks'],
      'country' => ['uk' => 'uk', 'es' => 'es'],
    ];
    static::assertEquals($expect, Arrays::flipGroup($input));
  }

  public function testXGroup()
  {
    $apple = ['name' => 'Apple', 'type' => 'fruit', 'color' => 'green'];
    $bear = ['name' => 'Bear', 'type' => 'animal', 'color' => 'brown'];
    $carrot = ['name' => 'Carrot', 'type' => 'vegetable', 'color' => 'brown'];

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
    static::assertEquals(
      $expect,
      Arrays::xgroup($list, 'type', ['fruit' => 'food', 'vegetable' => 'food'], 'general')
    );
  }

  public function testArrayNonEmpty()
  {
    $array = ['name' => 't_name', 'age' => 't_age'];

    static::assertEquals(
      't_age',
      Arrays::inonempty($array, ['miss', 'age', 'name'])
    );
    static::assertNull(Arrays::inonempty($array, ['miss1', 'miss2']));
    static::assertNull(Arrays::inonempty($array, []));
    static::assertEquals(
      'no',
      Arrays::inonempty($array, ['miss1', 'miss2'], 'no')
    );
    static::assertEquals('no', Arrays::inonempty($array, [], 'no'));
  }

  public function testInArrayI()
  {
    $array = ['ab', 'cd', 'EF', "GH"];
    static::assertTrue(Arrays::contains($array, 'ab'));
    static::assertTrue(Arrays::contains($array, 'ef'));
    static::assertTrue(Arrays::contains($array, 'CD'));
    static::assertFalse(Arrays::contains($array, 'ij'));
  }

  public function testArrayAdd()
  {
    $initialArray = ["a" => 1, "b" => 2];
    static::assertEquals(
      $initialArray + ["x"],
      Arrays::addValue($initialArray, "x")
    );
    static::assertEquals(
      $initialArray + ["c" => 3],
      Arrays::addValue($initialArray, 3, "c")
    );
  }

  public function testToList()
  {
    $expectations = [
      [[1, 2, 3, 4, 5], ",", "&", "1,2,3,4&5"],
      [[1, 2], ",", "&", "1&2"],
      [[1], ",", "&", "1"],
    ];
    foreach($expectations as $expect)
    {
      static::assertEquals(
        $expect[3],
        Arrays::toList($expect[0], $expect[1], $expect[2])
      );
    }
  }

  public function testIsAssociativeArray()
  {
    static::assertTrue(Arrays::isAssoc(["a" => "A", "b" => "B"]));
    static::assertFalse(Arrays::isAssoc(["A", "B"]));
  }

  public function testShuffleAssoc()
  {
    static::assertEquals('string', Arrays::shuffleAssoc('string'));

    // generate random array
    $testArray = Strings::stringToRange('1-50');
    $shuffled = Arrays::shuffleAssoc($testArray);
    static::assertFalse((bool)array_diff_assoc($testArray, $shuffled));
    static::assertFalse((bool)array_diff_assoc($shuffled, $testArray));

    // three times: shuffle and check that it's different
    $diffCount = 0;
    for($i = 0; $i < 5; $i++)
    {
      $shuffled = Arrays::shuffleAssoc($testArray);
      if($shuffled !== $testArray)
      {
        $diffCount++;
      }
    }
    static::assertGreaterThan(0, $diffCount);
  }

  public function testRandomItem()
  {
    $testArray = Strings::stringToRange('1-50');
    for($i = 0; $i <= 10; $i++)
    {
      $item = Arrays::randomItem($testArray);
      if($item != 1)
      {
        break;
      }
    }
    static::assertLessThan(10, $i);
  }

  public function testRandom()
  {
    $testArray = Strings::stringToRange('1-50');
    static::assertCount(3, Arrays::random($testArray, 3));
    static::assertTrue(Arrays::isAssoc(Arrays::random($testArray, 4, true)));
    static::assertFalse(Arrays::isAssoc(Arrays::random($testArray, 4, false)));
  }

  public function testFlattenExpand()
  {
    $array = [
      'a'  => ['b' => ['c' => 'd']],
      'w'  => ['x' => ['y' => 'z']],
      '1a' => 2,
      'ab' => ['cd' => 3],
    ];

    $expect = [
      'a.b.c' => 'd',
      'w.x.y' => 'z',
      '1a'    => 2,
      'ab.cd' => 3,
    ];
    static::assertEquals($expect, Arrays::flatten($array));
    static::assertEquals($array, Arrays::expand($expect));
  }

  public function testTransform()
  {
    static::assertEquals(
      ['A', 'B', 'C', 'X'],
      Arrays::transformed(
        ['a', 'b', 'c', null],
        function ($value) {
          return strtoupper($value);
        },
        'X'
      )
    );
  }

  public function testFilterTransform()
  {
    $result = Arrays::filterTransform(
      range(1, 20),
      function ($v) { return $v % 2 == 0; },
      function ($v) { return base_convert($v, 10, 36); }
    );
    static::assertEquals([2, 4, 6, 8, 'a', 'c', 'e', 'g', 'i', 'k',], array_values(iterator_to_array($result)));
  }

  public function testCoalesce()
  {
    $data = [
      'one'   => null,
      'two'   => '2',
      'three' => null,
      'four'  => '4',
      'five'  => 0,
    ];

    static::assertEquals('2', Arrays::coalesce($data, 'one', 'three', 'two'));
    static::assertEquals('4', Arrays::coalesce($data, 'one', 'three', 'four', 'two'));
    static::assertEquals(0, Arrays::coalesce($data, 'one', 'three', 'five'));
    static::assertNull(Arrays::coalesce($data, 'one', 'three'));
  }

  public function testNonEmpty()
  {
    $data = [
      'one'   => 0,
      'two'   => '2',
      'three' => null,
      'four'  => '4',
    ];

    static::assertEquals('2', Arrays::nonempty($data, 'one', 'three', 'two'));
    static::assertEquals('4', Arrays::nonempty($data, 'one', 'three', 'four', 'two'));
    static::assertNull(Arrays::nonempty($data, 'one', 'three'));
  }

  public function testTree()
  {
    $tree = Arrays::iTree([], 'id', 'parentId');
    static::assertInstanceOf(Branch::class, $tree);
    static::assertFalse($tree->hasChildren());

    $tree = Arrays::iTree([['id' => 0, 'parentId' => null]], 'id', 'parentId');
    static::assertInstanceOf(Branch::class, $tree);
    static::assertTrue($tree->hasChildren());
    static::assertContainsOnlyInstancesOf(Branch::class, $tree->getChildren());
    static::assertCount(1, $tree->getChildren());
  }
}
