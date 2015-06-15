<?php

/**
 * Tests ported from
 * https://github.com/facebook/libphutil/blob/master/src/utils/
 * __tests__/PhutilUtilsTestCase.php
 * @author  brooke.bryan
 */
class PhutilTest extends PHPUnit_Framework_TestCase
{
  public function testMFilterNullMethodThrowException()
  {
    $caught = null;
    try
    {
      mfilter([], null);
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

    $actual = mfilter($list, 'getI');
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

    $actual = mfilter($list, 'getI', true);
    $expected = [
      'b' => $b,
    ];

    $this->assertEquals($expected, $actual);
  }

  public function testIFilterInvalidIndexThrowException()
  {
    $caught = null;
    try
    {
      ifilter([], null);
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

    $actual = ifilter($list, 'i');
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

    $actual = ifilter($list, 'NoneExisting');
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

    $actual = ifilter($list, 'i', true);
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

    $actual = ifilter($list, 'NoneExisting', true);
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
      array_mergev(
        [ // <empty>
        ]
      )
    );

    $this->assertEquals(
      [],
      array_mergev(
        [
          [],
          [],
          [],
        ]
      )
    );

    $this->assertEquals(
      [1, 2, 3, 4, 5],
      array_mergev(
        [
          [1, 2],
          [3],
          [],
          [4, 5],
        ]
      )
    );
  }

  public function testNonempty()
  {
    $this->assertEquals(
      'zebra',
      nonempty(false, null, 0, '', [], 'zebra')
    );

    $this->assertEquals(
      null,
      nonempty()
    );

    $this->assertEquals(
      false,
      nonempty(null, false)
    );

    $this->assertEquals(
      null,
      nonempty(false, null)
    );
  }

  protected function _tryAssertInstancesOfArray($input)
  {
    assert_instances_of($input, 'array');
  }

  protected function _tryAssertInstancesOfStdClass($input)
  {
    assert_instances_of($input, 'stdClass');
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

  public function testAssertStringLike()
  {
    assert_stringlike(null);
    assert_stringlike("");
    assert_stringlike("Hello World");
    assert_stringlike(1);
    assert_stringlike(9.9999);
    assert_stringlike(true);
    assert_stringlike(new Exception('.'));

    $obj = (object)[];
    $caught = null;
    try
    {
      assert_stringlike($obj);
    }
    catch(InvalidArgumentException $ex)
    {
      $caught = $ex;
    }
    $this->assertInstanceOf("InvalidArgumentException", $caught);

    $array = [
      "foo" => "bar",
      "bar" => "foo",
    ];
    $caught = null;
    try
    {
      assert_stringlike($array);
    }
    catch(InvalidArgumentException $ex)
    {
      $caught = $ex;
    }
    $this->assertInstanceOf("InvalidArgumentException", $caught);
  }

  public function testCoalesce()
  {
    $this->assertEquals(
      'zebra',
      coalesce(null, 'zebra')
    );

    $this->assertEquals(
      null,
      coalesce()
    );

    $this->assertEquals(
      false,
      coalesce(false, null)
    );

    $this->assertEquals(
      false,
      coalesce(null, false)
    );
  }

  public function testHeadLast()
  {
    $this->assertEquals(
      'a',
      head(explode('.', 'a.b'))
    );
    $this->assertEquals(
      'b',
      last(explode('.', 'a.b'))
    );
  }

  public function testHeadKeyLastKey()
  {
    $this->assertEquals(
      'a',
      head_key(['a' => 0, 'b' => 1])
    );
    $this->assertEquals(
      'b',
      last_key(['a' => 0, 'b' => 1])
    );
    $this->assertEquals(null, head_key([]));
    $this->assertEquals(null, last_key([]));
  }

  public function testIdx()
  {
    $array = [
      'present' => true,
      'null'    => null,
    ];
    $this->assertEquals(true, idx($array, 'present'));
    $this->assertEquals(true, idx($array, 'present', false));
    $this->assertEquals(null, idx($array, 'null'));
    $this->assertEquals(null, idx($array, 'null', false));
    $this->assertEquals(null, idx($array, 'missing'));
    $this->assertEquals(false, idx($array, 'missing', false));
  }

  public function testSplitLines()
  {
    $retain_cases = [
      ""              => [""],
      "x"             => ["x"],
      "x\n"           => ["x\n"],
      "\n"            => ["\n"],
      "\n\n\n"        => ["\n", "\n", "\n"],
      "\r\n"          => ["\r\n"],
      "x\r\ny\n"      => ["x\r\n", "y\n"],
      "x\ry\nz\r\n"   => ["x\ry\n", "z\r\n"],
      "x\ry\nz\r\n\n" => ["x\ry\n", "z\r\n", "\n"],
    ];

    foreach($retain_cases as $input => $expect)
    {
      $this->assertEquals(
        $expect,
        phutil_split_lines($input, $retain_endings = true),
        ("(Retained) " . addcslashes($input, "\r\n\\"))
      );
    }

    $discard_cases = [
      ""              => [""],
      "x"             => ["x"],
      "x\n"           => ["x"],
      "\n"            => [""],
      "\n\n\n"        => ["", "", ""],
      "\r\n"          => [""],
      "x\r\ny\n"      => ["x", "y"],
      "x\ry\nz\r\n"   => ["x\ry", "z"],
      "x\ry\nz\r\n\n" => ["x\ry", "z", ""],
    ];

    foreach($discard_cases as $input => $expect)
    {
      $this->assertEquals(
        $expect,
        phutil_split_lines($input, $retain_endings = false),
        ("(Discarded) " . addcslashes($input, "\r\n\\"))
      );
    }
  }

  public function testArrayFuse()
  {
    $this->assertEquals([], array_fuse([]));
    $this->assertEquals(['x' => 'x'], array_fuse(['x']));
  }

  public function testArrayInterleave()
  {
    $this->assertEquals([], array_interleave('x', []));
    $this->assertEquals(['y'], array_interleave('x', ['y']));

    $this->assertEquals(
      ['y', 'x', 'z'],
      array_interleave('x', ['y', 'z'])
    );

    $this->assertEquals(
      ['y', 'x', 'z'],
      array_interleave(
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
      array_interleave(
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
        implode('', array_interleave($x, $y)),
        implode($x, $y)
      );
    }
  }

  public function testMpull()
  {
    $a = new MFilterTestHelper('1', 'a', 'q');
    $b = new MFilterTestHelper('2', 'b', 'q');
    $c = new MFilterTestHelper('3', 'c', 'q');
    $list = [$a, $b, $c];

    $expected = [1, 2, 3];
    $this->assertEquals($expected, mpull($list, 'getH'));

    $expected = ['a' => 1, 'b' => 2, 'c' => 3];
    $this->assertEquals($expected, mpull($list, 'getH', 'getI'));

    $expected = ['a' => $a, 'b' => $b, 'c' => $c];
    $this->assertEquals($expected, mpull($list, null, 'getI'));
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
    $this->assertEquals($expected, ppull($list, 'name'));

    $expected = ['a' => 1, 'b' => 2, 'c' => 3];
    $this->assertEquals($expected, ppull($list, 'value', 'name'));

    $expected = ['a' => $a, 'b' => $b, 'c' => $c];
    $this->assertEquals($expected, ppull($list, null, 'name'));
  }

  public function testIpull()
  {
    $list = [
      ['name' => 'a', 'value' => 1],
      ['name' => 'b', 'value' => 2],
      ['name' => 'c', 'value' => 3],
    ];

    $expected = ["a", "b", "c"];
    $this->assertEquals($expected, ipull($list, 'name'));

    $expected = ['a' => 1, 'b' => 2, 'c' => 3];
    $this->assertEquals($expected, ipull($list, 'value', 'name'));

    $expected = [
      'a' => ['name' => 'a', 'value' => 1],
      'b' => ['name' => 'b', 'value' => 2],
      'c' => ['name' => 'c', 'value' => 3],
    ];
    $this->assertEquals($expected, ipull($list, null, 'name'));
  }

  public function testMsort()
  {
    $a = new MFilterTestHelper('1', 'a', 'q');
    $b = new MFilterTestHelper('2', 'b', 'q');
    $c = new MFilterTestHelper('3', 'c', 'q');
    $list = ["b" => $b, "a" => $a, "c" => $c];

    $expected = ["a" => $a, "b" => $b, "c" => $c];
    $this->assertEquals($expected, msort($list, 'getI'));
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
    $this->assertEquals($expected, isort($list, 'name'));
  }

  public function testArraySelectKeys()
  {
    $list = [
      'a' => 1,
      'b' => 2,
      'c' => 3
    ];

    $expect = ['a' => 1, 'b' => 2];
    $this->assertEquals($expect, array_select_keys($list, ['a', 'b']));
  }

  public function testNewv()
  {
    $expect = new Pancake('Blueberry', "Maple Syrup");
    $this->assertEquals(
      $expect,
      newv('Pancake', ['Blueberry', "Maple Syrup"])
    );
    $expect = new Pancake();
    $this->assertEquals(
      $expect,
      newv('Pancake', [])
    );
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
    $this->assertEquals($expect, igroup($list, 'type'));

    $expect = [
      'food'     => [
        'fruit'     => ['a' => $apple],
        'vegetable' => ['c' => $carrot]
      ],
      'creature' => [
        'animal' => ['b' => $bear]
      ],
    ];
    $this->assertEquals($expect, igroup($list, 'group', 'type'));

    $expect = [
      'food'     => [
        'a' => $apple,
        'c' => $carrot
      ],
      'creature' => [
        'b' => $bear
      ],
    ];
    $this->assertEquals($expect, igroup($list, 'group'));
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
    $this->assertEquals($expect, mgroup($list, 'type'));

    $expect = [
      'food'     => [
        'fruit'     => ['a' => $apple],
        'vegetable' => ['c' => $carrot]
      ],
      'creature' => [
        'animal' => ['b' => $bear]
      ],
    ];
    $this->assertEquals($expect, mgroup($list, 'group', 'type'));

    $expect = [
      'food'     => [
        'a' => $apple,
        'c' => $carrot
      ],
      'creature' => [
        'b' => $bear
      ],
    ];
    $this->assertEquals($expect, mgroup($list, 'group'));
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
    $this->assertEquals($expect, pgroup($list, 'typeProperty'));

    $expect = [
      'food'     => [
        'fruit'     => ['a' => $apple],
        'vegetable' => ['c' => $carrot]
      ],
      'creature' => [
        'animal' => ['b' => $bear]
      ],
    ];
    $this->assertEquals(
      $expect,
      pgroup($list, 'groupProperty', 'typeProperty')
    );

    $expect = [
      'food'     => [
        'a' => $apple,
        'c' => $carrot
      ],
      'creature' => [
        'b' => $bear
      ],
    ];
    $this->assertEquals($expect, pgroup($list, 'groupProperty'));
  }
}
