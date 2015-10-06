<?php
use Packaged\Helpers\Strings;

/**
 * @author  brooke.bryan
 */
class StringsTest extends PHPUnit_Framework_TestCase
{
  public function testSplitOnCamelCase()
  {
    $expectations = [
      ["camelWord", "camel Word"],
      ["camelWordX", "camel Word X"],
      ["userID", "user ID"],
      ["userID Second", "user ID Second"],
      ["ABCd", "AB Cd"],
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals(
        $expect[1],
        \Packaged\Helpers\Strings::splitOnCamelCase($expect[0])
      );
    }
  }

  public function testSplitOnUnderscores()
  {
    $expectations = [
      ["under_score", "under score"],
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals(
        $expect[1],
        \Packaged\Helpers\Strings::splitOnUnderscores($expect[0])
      );
    }
  }

  public function testStringToUnderScore()
  {
    $expectations = [
      ["firstName", "first_name"],
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals(
        $expect[1],
        \Packaged\Helpers\Strings::stringToUnderScore($expect[0])
      );
    }
  }

  public function testStringToCamelCase()
  {
    $expectations = [
      ["first name", "firstName"],
      ["first_name", "firstName"],
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals(
        $expect[1],
        \Packaged\Helpers\Strings::stringToCamelCase($expect[0])
      );
    }
  }

  public function testStringToPascalCase()
  {
    $expectations = [
      ["first name", "FirstName"],
      ["first_name", "FirstName"],
      ["firstName", "FirstName"],
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals(
        $expect[1],
        \Packaged\Helpers\Strings::stringToPascalCase($expect[0])
      );
    }
  }

  public function testTitleize()
  {
    $expectations = [
      ["first name", "First Name"],
      ["first_name", "First Name"],
      ["firstName", "FirstName"],
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals(
        $expect[1],
        \Packaged\Helpers\Strings::titleize($expect[0], false)
      );
    }
  }

  public function testTitleizeCamelSplit()
  {
    $expectations = [
      ["first name", "First Name"],
      ["first_name", "First Name"],
      ["firstName", "First Name"],
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals(
        $expect[1],
        \Packaged\Helpers\Strings::titleize($expect[0], true)
      );
    }
  }

  public function testHumanize()
  {
    $expectations = [
      ["first name", "First name"],
      ["first_name", "First name"],
      ["firstName", "FirstName"],
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals(
        $expect[1],
        \Packaged\Helpers\Strings::humanize($expect[0], false)
      );
    }
  }

  public function testHumanizeCamelSplit()
  {
    $expectations = [
      ["first name", "First name"],
      ["first_name", "First name"],
      ["firstName", "First name"],
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals(
        $expect[1],
        \Packaged\Helpers\Strings::humanize($expect[0], true)
      );
    }
  }

  public function testHyphenate()
  {
    $expectations = [
      ["first name", "first-name"],
      ["first_name", "first-name"],
      ["firstName", "firstName"],
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals(
        $expect[1],
        \Packaged\Helpers\Strings::hyphenate($expect[0])
      );
    }
  }

  public function testUrlize()
  {
    $expectations = [
      ["first name", "first-name"],
      ["first Name", "first-name"],
      ["first_name", "first-name"],
      ["first_namE", "first-name"],
      ["firstName", "firstname"],
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals(
        $expect[1],
        \Packaged\Helpers\Strings::urlize($expect[0])
      );
    }
  }

  public function testStringToRange()
  {
    $expectations = [
      ["one,two,three", ["one", "two", "three"]],
      ["one,two,3-7", ["one", "two", "3", "4", "5", "6", "7"]],
      ["one two,3-7", ["one", "two", "3", "4", "5", "6", "7"]],
      ["1-2,3-5,7", ["1", "2", "3", "4", "5", "7"]],
      ["1;2;3", ["1", "2", "3"]],
      ["1,2|3", ["1", "2", "3"]],
      ["server2-server4", ["server2", "server3", "server4"]],
      ["server2-xyz", ["server2-xyz"]],
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals(
        $expect[1],
        \Packaged\Helpers\Strings::stringToRange($expect[0])
      );
    }
  }

  public function testCommonPrefix()
  {
    $expectations = [
      ["abc1", "abc2", "abc"],
      ["abc1", "abd2", "ab"],
      ["serverName1", "serverName2", "serverName"],
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals(
        $expect[2],
        \Packaged\Helpers\Strings::commonPrefix($expect[0], $expect[1])
      );
    }
  }

  public function testCommonPrefixNotStoppingOnInts()
  {
    $expectations = [
      ["123abc", "123def", "123"],
      ["abc1", "abc2", "abc"],
      ["abc1", "abd2", "ab"],
      ["serverName1", "serverName2", "serverName"],
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals(
        $expect[2],
        \Packaged\Helpers\Strings::commonPrefix($expect[0], $expect[1], false)
      );
    }
  }

  public function testSplitAt()
  {
    $expectations = [
      ["abcdef", 3, ["abc", "def"]],
      ["ab", 3, ["ab", ""]],
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals(
        $expect[2],
        \Packaged\Helpers\Strings::splitAt($expect[0], $expect[1])
      );
    }
  }

  public function testRandomString()
  {
    foreach([1, 10, 50, 100, 500] as $length)
    {
      $this->assertEquals(
        $length,
        strlen(\Packaged\Helpers\Strings::randomString($length))
      );
    }

    $types = [
      \Packaged\Helpers\Strings::RANDOM_STRING_MCRYPT,
      \Packaged\Helpers\Strings::RANDOM_STRING_OPENSSL,
      \Packaged\Helpers\Strings::RANDOM_STRING_URANDOM,
      \Packaged\Helpers\Strings::RANDOM_STRING_CUSTOM,
      'invalid'
    ];
    foreach($types as $type)
    {
      $this->assertEquals(
        40,
        strlen(\Packaged\Helpers\Strings::randomString(40, $type))
      );
    }
  }

  /**
   * @param        $length
   * @param        $expect
   * @param string $append
   * @param null   $string
   * @param bool   $forceOnSpace
   *
   * @dataProvider excerptProvider
   */
  public function testExcerpt(
    $length, $expect, $append = '...', $string = null, $forceOnSpace = false
  )
  {
    if($string === null)
    {
      $string = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. '
        . 'Sed tincidunt arcu eu purus facilisis placerat. '
        . 'Cras elementum massa justo, et aliquam nisl ultricies nec. '
        . 'Sed tempus turpis dolor, vitae iaculis enim imperdiet quis. '
        . 'Ut condimentum lectus a auctor gravida. Nunc pellentesque faucibus '
        . 'ante sed cursus. Pellentesque quis justo bibendum, tempor lectus sit'
        . ' amet, varius nibh. Morbi gravida ut mauris at mattis. '
        . 'Etiam augue augue, tincidunt a tincidunt sed, suscipit quis leo.'
        . ' Pellentesque eu libero pulvinar, tristique sapien in,'
        . ' pellentesque dui. Cras arcu quam, molestie non ante at,'
        . ' facilisis luctus lacus. Donec sodales vitae nulla eu volutpat.'
        . ' Vestibulum ante ipsum primis in faucibus orci luctus et ultrices'
        . ' posuere cubilia Curae; Vestibulum pellentesque porttitor felis,'
        . ' porta viverra dui imperdiet sit amet. Suspendisse tellus neque,'
        . ' euismod sed dui eget, malesuada pretium magna. Proin ac consequat'
        . ' libero. Curabitur egestas sem eu metus porta,'
        . ' at vestibulum lacus luctus.';
    }
    $this->assertEquals(
      $expect,
      \Packaged\Helpers\Strings::excerpt(
        $string,
        $length,
        $append,
        $forceOnSpace
      )
    );
  }

  public function excerptProvider()
  {
    return [
      [10, "qwertyuiop...", '...', 'qwertyuiopasdfghjklzxcvbnm'],
      [5, "qwert...", '...', 'qwertyuiopasdfghjklzxcvbnm'],
      [50, "qwertyuiopasdfghjklzxcvbnm", '...', 'qwertyuiopasdfghjklzxcvbnm'],
      [10, "Lorem..."],
      [15, "Lorem ipsum..."],
      [1, "L..."],
      [5, "Lorem..."],
      [50, "Lorem ipsum dolor sit amet, consectetur adipiscing..."],
      [50, "Lorem ipsum dolor sit amet, consectetur adipiscing..."],
      [10, "once wefhr...", '...', "once wefhrekjgferjgf"],
      [15, "once...", '...', "once wefhrekjgefpiferjgf", true],
    ];
  }

  /**
   * @param $string
   * @param $start
   * @param $end
   * @param $expect
   * @param $inclusive
   *
   * @return array
   *
   * @dataProvider betweenProvider
   */
  public function testBetween($string, $start, $end, $inclusive, $expect)
  {
    $this->assertEquals(
      $expect,
      \Packaged\Helpers\Strings::between($string, $start, $end, $inclusive)
    );
  }

  public function betweenProvider()
  {
    return [
      ["abcdef", "b", "e", false, "cd"],
      ["abcdef", null, "e", false, "abcd"],
      ["abcdef", "b", null, false, "cdef"],
      ["abcdef", "z", null, false, false],
      ["abcdef", "b", 'z', false, false],
      ["abcdef", "b", "e", true, "bcde"],
      ["abcdef", null, "e", true, "abcde"],
      ["abcdef", "b", null, true, "bcdef"],
      ["abcdef", "z", null, true, false],
      ["abcdef", "b", 'z', true, false],
    ];
  }

  public function testAssertStringLike()
  {
    Strings::stringable(null);
    Strings::stringable("");
    Strings::stringable("Hello World");
    Strings::stringable(1);
    Strings::stringable(9.9999);
    Strings::stringable(true);
    Strings::stringable(new Exception('.'));

    $obj = (object)[];
    $caught = null;
    try
    {
      Strings::stringable($obj);
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
      Strings::stringable($array);
    }
    catch(InvalidArgumentException $ex)
    {
      $caught = $ex;
    }
    $this->assertInstanceOf("InvalidArgumentException", $caught);
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
        Strings::splitLines($input, $retainEndings = true),
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
        Strings::splitLines($input, $retainEndings = false),
        ("(Discarded) " . addcslashes($input, "\r\n\\"))
      );
    }
  }

  public function testJsonPretty()
  {
    $this->assertEquals(
      '{
    "x": "y"
}',
      Strings::jsonPretty(["x" => "y"])
    );
  }

  public function testEscape()
  {
    $expectations = [
      ['Strings', "Strings"],
      ['Stri"ngs', "Stri&quot;ngs"],
      ['Stri\'ngs', "Stri&#039;ngs"],
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals($expect[1], Strings::escape($expect[0]));
    }
  }

  public function testStringFrom()
  {
    $this->assertEquals(
      'Views\Dyn',
      Strings::offset('X\Y\Z\Com\Views\Dyn', 'Com\\')
    );
    $this->assertEquals(
      'X\Y\Z\Com\Views\Dyn',
      Strings::offset('X\Y\Z\Com\Views\Dyn', 'Mi\\')
    );
  }

  public function testStrContains()
  {
    $this->assertTrue(Strings::contains('abcdef', 'bcd'));
    $this->assertTrue(Strings::contains('abcdef', 'bcd', false));
    $this->assertFalse(Strings::contains('abCdef', 'bcd'));
    $this->assertTrue(Strings::contains('aBcDeF', 'aBcDeF'));
    $this->assertTrue(Strings::contains('aBcDeF', 'BcD'));
    $this->assertTrue(Strings::contains('aBcDeF', 'bcd', false));
  }

  public function testContainsAny()
  {
    $this->assertTrue(Strings::containsAny('abcdef', ['x', 'y', 'bc']));
    $this->assertFalse(Strings::containsAny('abcdef', ['x', 'y', 'z']));
    $this->assertTrue(Strings::containsAny('aBCdef', ['x', 'y', 'bc'], false));
    $this->assertFalse(Strings::containsAny('aBCdef', ['x', 'y', 'bc']));
    $this->assertFalse(Strings::containsAny('abcdef', ['x', 'y', 'z']));
  }

  public function testExploded()
  {
    $defaults = ["a", "b", "c", "d"];
    $this->assertEquals(
      [1, 2, 3, 4],
      Strings::explode(",", "1,2,3,4", $defaults, 4)
    );
    $this->assertEquals(
      [1, 2, "3,4", "d"],
      Strings::explode(",", "1,2,3,4", $defaults, 3)
    );
    $this->assertEquals(
      [1, 2, "c", "d"],
      Strings::explode(",", "1,2", $defaults, 3)
    );
    $this->assertEquals(
      [1, 2, "c", "d"],
      Strings::explode(",", "1,2", $defaults)
    );
    $this->assertEquals(
      [1, 2, 3, 4, 5],
      Strings::explode(",", "1,2,3,4,5", $defaults)
    );
    $this->assertEquals(
      [1, 2, 3, '-', '-'],
      Strings::explode(",", "1,2,3", '-', 5)
    );
  }

  public function testConcat()
  {
    $this->assertEquals("ab", Strings::concat("a", "b"));
    $this->assertEquals("a-b", Strings::concat("a", "-", "b"));
  }

  public function testStartsWith()
  {
    $this->assertTrue(Strings::startsWith("abcdef", "ab", true));
    $this->assertTrue(Strings::startsWith("aBcdef", "aB", true));
    $this->assertTrue(Strings::startsWith("abcdef", "aB", false));
    $this->assertTrue(Strings::startsWith("aBcdef", ["cd", 'aB'], true));

    $this->assertFalse(Strings::startsWith("aBcdef", "ab", true));
    $this->assertFalse(Strings::startsWith("aBcdef", "cd", false));
    $this->assertFalse(Strings::startsWith("aBcdef", "cd", true));
  }

  public function testStartsWithAny()
  {
    $this->assertTrue(Strings::startsWithAny("abcdef", ["c", "ab"], true));
    $this->assertTrue(Strings::startsWithAny("aBcdef", ["c", "aB"], true));
    $this->assertFalse(Strings::startsWithAny("abcdef", ["c", "ef"], true));
    $this->assertTrue(Strings::startsWithAny("aBcdef", ["c", "aB"], false));
    $this->assertFalse(Strings::startsWithAny("aBcdef", ["c", "ef"], false));
  }

  public function testEndsWith()
  {
    $this->assertTrue(Strings::endsWith("abcdef", "f", true));
    $this->assertTrue(Strings::endsWith("aBcdeF", "eF", true));
    $this->assertTrue(Strings::endsWith("aBcdeF", "ef", false));
    $this->assertTrue(Strings::endsWith("aBcdeF", ["de", "ef"], false));

    $this->assertFalse(Strings::endsWith("aBcdef", "de", false));
    $this->assertFalse(Strings::endsWith("aBcdef", "eF", true));
  }

  public function testEndsWithAny()
  {
    $this->assertTrue(Strings::endsWithAny("abcdef", ["f", "yx"], true));
    $this->assertTrue(Strings::endsWithAny("aBcdeF", ["x", "eF"], true));
    $this->assertTrue(Strings::endsWithAny("aBcdef", ["c", "eF"], false));

    $this->assertFalse(Strings::endsWithAny("abcdef", ["c", "eF"], true));
    $this->assertFalse(Strings::endsWithAny("aBcdef", ["c", "ab"], false));
  }

  public function testStripStart()
  {
    $expectations = [
      ['Strings', "Strings", ""],
      ['Strings', "Stri", "ngs"],
      ['Apple', "Pear", "Apple"],
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals($expect[2], Strings::ltrim($expect[0], $expect[1]));
    }
  }

  /**
   * @param $pattern
   *
   * @dataProvider patternProvider
   */
  public function testPattern($pattern)
  {
    for($i = 0; $i < 100; $i++)
    {
      $this->assertTrue(
        Strings::verifyPattern($pattern, Strings::pattern($pattern))
      );
    }
  }

  public function patternProvider()
  {
    return [
      ['XX00-XX00-00XX-00XX-XXXX'],
      ['XX00-XX00-00XX-55XX-xxx5'],
      ['XX00-XX00-00XX-55XX-??!!'],
      ['XX00-XX00-00XX-55XX-?**!'],
    ];
  }
}
