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
      mfilter(array(), null);
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

    $list = array(
      'a' => $a,
      'b' => $b,
      'c' => $c,
    );

    $actual   = mfilter($list, 'getI');
    $expected = array(
      'a' => $a,
      'c' => $c,
    );

    $this->assertEquals($expected, $actual);
  }

  public function testMFilterWithEmptyValueNegateFiltered()
  {
    $a = new MFilterTestHelper('o', 'p', 'q');
    $b = new MFilterTestHelper('o', '', 'q');
    $c = new MFilterTestHelper('o', 'p', 'q');

    $list = array(
      'a' => $a,
      'b' => $b,
      'c' => $c,
    );

    $actual   = mfilter($list, 'getI', true);
    $expected = array(
      'b' => $b,
    );

    $this->assertEquals($expected, $actual);
  }

  public function testIFilterInvalidIndexThrowException()
  {
    $caught = null;
    try
    {
      ifilter(array(), null);
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
    $list = array(
      'a' => array('h' => 'o', 'i' => 'p', 'j' => 'q',),
      'b' => array('h' => 'o', 'i' => '', 'j' => 'q',),
      'c' => array('h' => 'o', 'i' => 'p', 'j' => 'q',),
      'd' => array('h' => 'o', 'i' => 0, 'j' => 'q',),
      'e' => array('h' => 'o', 'i' => null, 'j' => 'q',),
      'f' => array('h' => 'o', 'i' => false, 'j' => 'q',),
    );

    $actual   = ifilter($list, 'i');
    $expected = array(
      'a' => array('h' => 'o', 'i' => 'p', 'j' => 'q',),
      'c' => array('h' => 'o', 'i' => 'p', 'j' => 'q',),
    );

    $this->assertEquals($expected, $actual);
  }

  public function testIFilterIndexNotExistsAllFiltered()
  {
    $list = array(
      'a' => array('h' => 'o', 'i' => 'p', 'j' => 'q',),
      'b' => array('h' => 'o', 'i' => '', 'j' => 'q',),
    );

    $actual   = ifilter($list, 'NoneExisting');
    $expected = array();

    $this->assertEquals($expected, $actual);
  }

  public function testIFilterWithEmptyValueNegateFiltered()
  {
    $list = array(
      'a' => array('h' => 'o', 'i' => 'p', 'j' => 'q',),
      'b' => array('h' => 'o', 'i' => '', 'j' => 'q',),
      'c' => array('h' => 'o', 'i' => 'p', 'j' => 'q',),
      'd' => array('h' => 'o', 'i' => 0, 'j' => 'q',),
      'e' => array('h' => 'o', 'i' => null, 'j' => 'q',),
      'f' => array('h' => 'o', 'i' => false, 'j' => 'q',),
    );

    $actual   = ifilter($list, 'i', true);
    $expected = array(
      'b' => array('h' => 'o', 'i' => '', 'j' => 'q',),
      'd' => array('h' => 'o', 'i' => 0, 'j' => 'q',),
      'e' => array('h' => 'o', 'i' => null, 'j' => 'q',),
      'f' => array('h' => 'o', 'i' => false, 'j' => 'q',),
    );

    $this->assertEquals($expected, $actual);
  }

  public function testIFilterIndexNotExistsNotFiltered()
  {
    $list = array(
      'a' => array('h' => 'o', 'i' => 'p', 'j' => 'q',),
      'b' => array('h' => 'o', 'i' => '', 'j' => 'q',),
    );

    $actual   = ifilter($list, 'NoneExisting', true);
    $expected = array(
      'a' => array('h' => 'o', 'i' => 'p', 'j' => 'q',),
      'b' => array('h' => 'o', 'i' => '', 'j' => 'q',),
    );

    $this->assertEquals($expected, $actual);
  }

  public function testmergevMergingBasicallyWorksCorrectly()
  {
    $this->assertEquals(
      array(),
      array_mergev(
        array( // <empty>
        )
      )
    );

    $this->assertEquals(
      array(),
      array_mergev(
        array(
             array(),
             array(),
             array(),
        )
      )
    );

    $this->assertEquals(
      array(1, 2, 3, 4, 5),
      array_mergev(
        array(
             array(1, 2),
             array(3),
             array(),
             array(4, 5),
        )
      )
    );
  }

  public function testNonempty()
  {
    $this->assertEquals(
      'zebra',
      nonempty(false, null, 0, '', array(), 'zebra')
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

    $labels    = array_keys($inputs);
    $inputs    = array_values($inputs);
    $expecting = array_values($expect);
    foreach($inputs as $idx => $input)
    {
      $expect = $expecting[$idx];
      $label  = $labels[$idx];

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
    $inputs = array(
      'empty'               => array(),
      'stdClass'            => array($object, $object),
      'PhutilUtilsTestCase' => array($object, $this),
      'array'               => array(array(), array()),
      'integer'             => array($object, 1),
    );

    $this->_tryTestCases(
      $inputs,
      array(true, true, false, false, false),
      array($this, '_tryAssertInstancesOfStdClass'),
      'InvalidArgumentException'
    );

    $this->_tryTestCases(
      $inputs,
      array(true, false, false, true, false),
      array($this, '_tryAssertInstancesOfArray'),
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

    $obj    = (object)array();
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

    $array  = array(
      "foo" => "bar",
      "bar" => "foo",
    );
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
      head_key(array('a' => 0, 'b' => 1))
    );
    $this->assertEquals(
      'b',
      last_key(array('a' => 0, 'b' => 1))
    );
    $this->assertEquals(null, head_key(array()));
    $this->assertEquals(null, last_key(array()));
  }

  public function testID()
  {
    $this->assertEquals(true, id(true));
    $this->assertEquals(false, id(false));
  }

  public function testIdx()
  {
    $array = array(
      'present' => true,
      'null'    => null,
    );
    $this->assertEquals(true, idx($array, 'present'));
    $this->assertEquals(true, idx($array, 'present', false));
    $this->assertEquals(null, idx($array, 'null'));
    $this->assertEquals(null, idx($array, 'null', false));
    $this->assertEquals(null, idx($array, 'missing'));
    $this->assertEquals(false, idx($array, 'missing', false));
  }

  public function testSplitLines()
  {
    $retain_cases = array(
      ""              => array(""),
      "x"             => array("x"),
      "x\n"           => array("x\n"),
      "\n"            => array("\n"),
      "\n\n\n"        => array("\n", "\n", "\n"),
      "\r\n"          => array("\r\n"),
      "x\r\ny\n"      => array("x\r\n", "y\n"),
      "x\ry\nz\r\n"   => array("x\ry\n", "z\r\n"),
      "x\ry\nz\r\n\n" => array("x\ry\n", "z\r\n", "\n"),
    );

    foreach($retain_cases as $input => $expect)
    {
      $this->assertEquals(
        $expect,
        phutil_split_lines($input, $retain_endings = true),
        ("(Retained) " . addcslashes($input, "\r\n\\"))
      );
    }

    $discard_cases = array(
      ""              => array(""),
      "x"             => array("x"),
      "x\n"           => array("x"),
      "\n"            => array(""),
      "\n\n\n"        => array("", "", ""),
      "\r\n"          => array(""),
      "x\r\ny\n"      => array("x", "y"),
      "x\ry\nz\r\n"   => array("x\ry", "z"),
      "x\ry\nz\r\n\n" => array("x\ry", "z", ""),
    );

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
    $this->assertEquals(array(), array_fuse(array()));
    $this->assertEquals(array('x' => 'x'), array_fuse(array('x')));
  }

  public function testArrayInterleave()
  {
    $this->assertEquals(array(), array_interleave('x', array()));
    $this->assertEquals(array('y'), array_interleave('x', array('y')));

    $this->assertEquals(
      array('y', 'x', 'z'),
      array_interleave('x', array('y', 'z'))
    );

    $this->assertEquals(
      array('y', 'x', 'z'),
      array_interleave(
        'x',
        array(
             'kangaroo' => 'y',
             'marmoset' => 'z',
        )
      )
    );

    $obj1 = (object)array();
    $obj2 = (object)array();

    $this->assertEquals(
      array($obj1, $obj2, $obj1, $obj2, $obj1),
      array_interleave(
        $obj2,
        array(
             $obj1,
             $obj1,
             $obj1,
        )
      )
    );

    $implode_tests = array(
      ''  => array(1, 2, 3),
      'x' => array(1, 2, 3),
      'y' => array(),
      'z' => array(1),
    );

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
    $a    = new MFilterTestHelper('1', 'a', 'q');
    $b    = new MFilterTestHelper('2', 'b', 'q');
    $c    = new MFilterTestHelper('3', 'c', 'q');
    $list = array($a, $b, $c);

    $expected = array(1, 2, 3);
    $this->assertEquals($expected, mpull($list, 'getH'));

    $expected = array('a' => 1, 'b' => 2, 'c' => 3);
    $this->assertEquals($expected, mpull($list, 'getH', 'getI'));

    $expected = array('a' => $a, 'b' => $b, 'c' => $c);
    $this->assertEquals($expected, mpull($list, null, 'getI'));
  }

  public function testPpull()
  {
    $a        = new stdClass();
    $a->name  = "a";
    $a->value = 1;
    $b        = new stdClass();
    $b->name  = "b";
    $b->value = 2;
    $c        = new stdClass();
    $c->name  = "c";
    $c->value = 3;
    $list     = array($a, $b, $c);

    $expected = array("a", "b", "c");
    $this->assertEquals($expected, ppull($list, 'name'));

    $expected = array('a' => 1, 'b' => 2, 'c' => 3);
    $this->assertEquals($expected, ppull($list, 'value', 'name'));

    $expected = array('a' => $a, 'b' => $b, 'c' => $c);
    $this->assertEquals($expected, ppull($list, null, 'name'));
  }

  public function testIpull()
  {
    $list = array(
      array('name' => 'a', 'value' => 1),
      array('name' => 'b', 'value' => 2),
      array('name' => 'c', 'value' => 3),
    );

    $expected = array("a", "b", "c");
    $this->assertEquals($expected, ipull($list, 'name'));

    $expected = array('a' => 1, 'b' => 2, 'c' => 3);
    $this->assertEquals($expected, ipull($list, 'value', 'name'));

    $expected = array(
      'a' => array('name' => 'a', 'value' => 1),
      'b' => array('name' => 'b', 'value' => 2),
      'c' => array('name' => 'c', 'value' => 3),
    );
    $this->assertEquals($expected, ipull($list, null, 'name'));
  }

  public function testMsort()
  {
    $a    = new MFilterTestHelper('1', 'a', 'q');
    $b    = new MFilterTestHelper('2', 'b', 'q');
    $c    = new MFilterTestHelper('3', 'c', 'q');
    $list = array("b" => $b, "a" => $a, "c" => $c);

    $expected = array("a" => $a, "b" => $b, "c" => $c);
    $this->assertEquals($expected, msort($list, 'getI'));
  }

  public function testIsort()
  {
    $list = array(
      'b' => array('name' => 'b', 'value' => 2),
      'a' => array('name' => 'a', 'value' => 1),
      'c' => array('name' => 'c', 'value' => 3),
    );

    $expected = array(
      'a' => array('name' => 'a', 'value' => 1),
      'b' => array('name' => 'b', 'value' => 2),
      'c' => array('name' => 'c', 'value' => 3),
    );
    $this->assertEquals($expected, isort($list, 'name'));
  }

  public function testArraySelectKeys()
  {
    $list = array(
      'a' => 1,
      'b' => 2,
      'c' => 3
    );

    $expect = array('a' => 1, 'b' => 2);
    $this->assertEquals($expect, array_select_keys($list, array('a', 'b')));
  }

  public function testNewv()
  {
    $expect = new Pancake('Blueberry', "Maple Syrup");
    $this->assertEquals(
      $expect,
      newv('Pancake', array('Blueberry', "Maple Syrup"))
    );
    $expect = new Pancake();
    $this->assertEquals(
      $expect,
      newv('Pancake', array())
    );
  }

  public function testIGroup()
  {
    $apple  = array(
      'name'   => 'Apple',
      'type'   => 'fruit',
      'colour' => 'green',
      'group'  => 'food'
    );
    $bear   = array(
      'name'   => 'Bear',
      'type'   => 'animal',
      'colour' => 'brown',
      'group'  => 'creature'
    );
    $carrot = array(
      'name'   => 'Carrot',
      'type'   => 'vegetable',
      'colour' => 'brown',
      'group'  => 'food'
    );

    $list = array('a' => $apple, 'b' => $bear, 'c' => $carrot);

    $expect = array(
      'fruit'     => array('a' => $apple),
      'animal'    => array('b' => $bear),
      'vegetable' => array('c' => $carrot),
    );
    $this->assertEquals($expect, igroup($list, 'type'));

    $expect = array(
      'food'     => array(
        'fruit'     => array('a' => $apple),
        'vegetable' => array('c' => $carrot)
      ),
      'creature' => array(
        'animal' => array('b' => $bear)
      ),
    );
    $this->assertEquals($expect, igroup($list, 'group', 'type'));

    $expect = array(
      'food'     => array(
        'a' => $apple,
        'c' => $carrot
      ),
      'creature' => array(
        'b' => $bear
      ),
    );
    $this->assertEquals($expect, igroup($list, 'group'));
  }

  public function testMGroup()
  {
    $apple  = new Thing('Apple', 'fruit', 'green', 'food');
    $bear   = new Thing('Bear', 'animal', 'brown', 'creature');
    $carrot = new Thing('Carrot', 'vegetable', 'brown', 'food');

    $list = array('a' => $apple, 'b' => $bear, 'c' => $carrot);

    $expect = array(
      'fruit'     => array('a' => $apple),
      'animal'    => array('b' => $bear),
      'vegetable' => array('c' => $carrot),
    );
    $this->assertEquals($expect, mgroup($list, 'type'));

    $expect = array(
      'food'     => array(
        'fruit'     => array('a' => $apple),
        'vegetable' => array('c' => $carrot)
      ),
      'creature' => array(
        'animal' => array('b' => $bear)
      ),
    );
    $this->assertEquals($expect, mgroup($list, 'group', 'type'));

    $expect = array(
      'food'     => array(
        'a' => $apple,
        'c' => $carrot
      ),
      'creature' => array(
        'b' => $bear
      ),
    );
    $this->assertEquals($expect, mgroup($list, 'group'));
  }
}

final class Thing
{
  public function __construct($name, $type, $colour, $group)
  {
    $this->_name   = $name;
    $this->_type   = $type;
    $this->_colour = $colour;
    $this->_group  = $group;
  }

  public function type()
  {
    return $this->_type;
  }

  public function group()
  {
    return $this->_group;
  }
}

final class Pancake
{
  public $fruit;
  public $sauce;

  public function __construct($fruit = null, $sauce = null)
  {
    $this->fruit = $fruit;
    $this->sauce = $sauce;
  }

  public function getFruit()
  {
    return $this->fruit;
  }

  public function getSauce()
  {
    return $this->sauce;
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
