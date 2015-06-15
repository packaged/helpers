<?php

use Packaged\Helpers\Arrays;

class ArraysTest extends PHPUnit_Framework_TestCase
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

    $this->assertEquals(
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

    $this->assertEquals($expected, $actual);
  }

  public function testIFilterIndexNotExistsAllFiltered()
  {
    $list = [
      'a' => ['h' => 'o', 'i' => 'p', 'j' => 'q',],
      'b' => ['h' => 'o', 'i' => '', 'j' => 'q',],
    ];

    $actual = Arrays::ifilter($list, 'NoneExisting');
    $expected = [];

    $this->assertEquals($expected, $actual);
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

    $this->assertEquals($expected, $actual);
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

    $this->assertEquals($expected, $actual);
  }

  public function testmergevMergingBasicallyWorksCorrectly()
  {
    $this->assertEquals(
      [],
      Arrays::mergev(
        [ // <empty>
        ]
      )
    );

    $this->assertEquals(
      [],
      Arrays::mergev(
        [
          [],
          [],
          [],
        ]
      )
    );

    $this->assertEquals(
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

      $this->assertEquals($expect, $actual, $message);
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
    $this->assertEquals(
      'a',
      Arrays::first(explode('.', 'a.b'))
    );
    $this->assertEquals(
      'b',
      Arrays::last(explode('.', 'a.b'))
    );
  }

  public function testHeadKeyLastKey()
  {
    $this->assertEquals(
      'a',
      Arrays::firstKey(['a' => 0, 'b' => 1])
    );
    $this->assertEquals(
      'b',
      Arrays::lastKey(['a' => 0, 'b' => 1])
    );
    $this->assertEquals(null, Arrays::firstKey([]));
    $this->assertEquals(null, Arrays::lastKey([]));
  }

  public function testIdx()
  {
    $array = [
      'present' => true,
      'null'    => null,
    ];
    $this->assertEquals(true, Arrays::value($array, 'present'));
    $this->assertEquals(true, Arrays::value($array, 'present', false));
    $this->assertEquals(null, Arrays::value($array, 'null'));
    $this->assertEquals(null, Arrays::value($array, 'null', false));
    $this->assertEquals(null, Arrays::value($array, 'missing'));
    $this->assertEquals(false, Arrays::value($array, 'missing', false));
  }

  public function testArrayFuse()
  {
    $this->assertEquals([], Arrays::fuse([]));
    $this->assertEquals(['x' => 'x'], Arrays::fuse(['x']));
  }

  public function testArrayInterleave()
  {
    $this->assertEquals([], Arrays::interleave('x', []));
    $this->assertEquals(['y'], Arrays::interleave('x', ['y']));

    $this->assertEquals(
      ['y', 'x', 'z'],
      Arrays::interleave('x', ['y', 'z'])
    );

    $this->assertEquals(
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

    $this->assertEquals(
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
      $this->assertEquals(
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
    $this->assertEquals($expected, Arrays::ipull($list, 'name'));

    $expected = ['a' => 1, 'b' => 2, 'c' => 3];
    $this->assertEquals($expected, Arrays::ipull($list, 'value', 'name'));

    $expected = [
      'a' => ['name' => 'a', 'value' => 1],
      'b' => ['name' => 'b', 'value' => 2],
      'c' => ['name' => 'c', 'value' => 3],
    ];
    $this->assertEquals($expected, Arrays::ipull($list, null, 'name'));
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
    $this->assertEquals($expected, Arrays::isort($list, 'name'));
  }

  public function testArraySelectKeys()
  {
    $list = [
      'a' => 1,
      'b' => 2,
      'c' => 3
    ];

    $expect = ['a' => 1, 'b' => 2];
    $this->assertEquals($expect, Arrays::selectKeys($list, ['a', 'b']));
  }

  public function testIGroup()
  {
    $apple = [
      'name'   => 'Apple',
      'type'   => 'fruit',
      'colour' => 'green',
      'group'  => 'food'
    ];
    $bear = [
      'name'   => 'Bear',
      'type'   => 'animal',
      'colour' => 'brown',
      'group'  => 'creature'
    ];
    $carrot = [
      'name'   => 'Carrot',
      'type'   => 'vegetable',
      'colour' => 'brown',
      'group'  => 'food'
    ];

    $list = ['a' => $apple, 'b' => $bear, 'c' => $carrot];

    $expect = [
      'fruit'     => ['a' => $apple],
      'animal'    => ['b' => $bear],
      'vegetable' => ['c' => $carrot],
    ];
    $this->assertEquals($expect, Arrays::igroup($list, 'type'));

    $expect = [
      'food'     => [
        'fruit'     => ['a' => $apple],
        'vegetable' => ['c' => $carrot]
      ],
      'creature' => [
        'animal' => ['b' => $bear]
      ],
    ];
    $this->assertEquals($expect, Arrays::igroup($list, 'group', 'type'));

    $expect = [
      'food'     => [
        'a' => $apple,
        'c' => $carrot
      ],
      'creature' => [
        'b' => $bear
      ],
    ];
    $this->assertEquals($expect, Arrays::igroup($list, 'group'));
  }

  public function testArrayNonEmpty()
  {
    $array = ['name' => 't_name', 'age' => 't_age'];

    $this->assertEquals(
      't_age',
      Arrays::inonempty($array, ['miss', 'age', 'name'])
    );
    $this->assertNull(Arrays::inonempty($array, ['miss1', 'miss2']));
    $this->assertNull(Arrays::inonempty($array, []));
    $this->assertEquals(
      'no',
      Arrays::inonempty($array, ['miss1', 'miss2'], 'no')
    );
    $this->assertEquals('no', Arrays::inonempty($array, [], 'no'));
  }

  public function testInArrayI()
  {
    $array = ['ab', 'cd', 'EF', "GH"];
    $this->assertTrue(Arrays::contains($array, 'ab'));
    $this->assertTrue(Arrays::contains($array, 'ef'));
    $this->assertTrue(Arrays::contains($array, 'CD'));
    $this->assertFalse(Arrays::contains($array, 'ij'));
  }

  public function testArrayAdd()
  {
    $initialArray = ["a" => 1, "b" => 2];
    $this->assertEquals(
      $initialArray + ["x"],
      Arrays::addValue($initialArray, "x")
    );
    $this->assertEquals(
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
      $this->assertEquals(
        $expect[3],
        Arrays::toList($expect[0], $expect[1], $expect[2])
      );
    }
  }

  public function testIsAssociativeArray()
  {
    $this->assertTrue(Arrays::isAssoc(["a" => "A", "b" => "B"]));
    $this->assertFalse(Arrays::isAssoc(["A", "B"]));
  }

  public function testShuffleAssoc()
  {
    $this->assertEquals('string', Arrays::shuffleAssoc('string'));

    $expected = ['x' => 'x', 'y' => 'y', 'z' => 'z'];
    $shuffled = Arrays::shuffleAssoc($expected);
    $this->assertFalse((bool)array_diff_assoc($expected, $shuffled));
    $this->assertFalse((bool)array_diff_assoc($shuffled, $expected));

    srand(40);
    $expected = ['z' => 'z', 'y' => 'y', 'x' => 'x'];
    $shuffled = Arrays::shuffleAssoc($expected);
    $this->assertEquals(json_encode($expected), json_encode($shuffled));
  }
}

final class Thing
{
  public function __construct($name, $type, $colour, $group)
  {
    $this->_name = $this->nameProperty = $name;
    $this->_type = $this->typeProperty = $type;
    $this->_colour = $this->colourProperty = $colour;
    $this->_group = $this->groupProperty = $group;
  }

  public $nameProperty;
  public $typeProperty;
  public $colourProperty;
  public $groupProperty;

  public function type()
  {
    return $this->_type;
  }

  public function group()
  {
    return $this->_group;
  }
}

final class MFilterTestHelper
{

  private $h;
  private $i;
  private $j;

  public function __construct($h_value, $i_value, $j_value)
  {
    $this->h = $h_value;
    $this->i = $i_value;
    $this->j = $j_value;
  }

  public function getH()
  {
    return $this->h;
  }

  public function getI()
  {
    return $this->i;
  }

  public function getJ()
  {
    return $this->j;
  }
}
