<?php
namespace Packaged\Tests;

use Exception;
use InvalidArgumentException;
use Packaged\Helpers\Strings;
use PHPUnit\Framework\TestCase;

/**
 * @author  brooke.bryan
 */
class StringsTest extends TestCase
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
      static::assertEquals(
        $expect[1],
        Strings::splitOnCamelCase($expect[0])
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
      static::assertEquals(
        $expect[1],
        Strings::splitOnUnderscores($expect[0])
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
      static::assertEquals(
        $expect[1],
        Strings::stringToUnderScore($expect[0])
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
      static::assertEquals(
        $expect[1],
        Strings::stringToCamelCase($expect[0])
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
      static::assertEquals(
        $expect[1],
        Strings::stringToPascalCase($expect[0])
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
      static::assertEquals(
        $expect[1],
        Strings::titleize($expect[0], false)
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
      static::assertEquals(
        $expect[1],
        Strings::titleize($expect[0], true)
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
      static::assertEquals(
        $expect[1],
        Strings::humanize($expect[0], false)
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
      static::assertEquals(
        $expect[1],
        Strings::humanize($expect[0], true)
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
      static::assertEquals(
        $expect[1],
        Strings::hyphenate($expect[0])
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
      static::assertEquals(
        $expect[1],
        Strings::urlize($expect[0])
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
      static::assertEquals(
        $expect[1],
        Strings::stringToRange($expect[0])
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
      static::assertEquals(
        $expect[2],
        Strings::commonPrefix($expect[0], $expect[1])
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
      static::assertEquals(
        $expect[2],
        Strings::commonPrefix($expect[0], $expect[1], false)
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
      static::assertEquals(
        $expect[2],
        Strings::splitAt($expect[0], $expect[1])
      );
    }
  }

  public function testRandomString()
  {
    foreach([1, 10, 50, 100, 500] as $length)
    {
      static::assertEquals(
        $length,
        strlen(Strings::randomString($length))
      );
    }

    $types = [
      Strings::RANDOM_STRING_OPENSSL,
      Strings::RANDOM_STRING_URANDOM,
      Strings::RANDOM_STRING_CUSTOM,
      'invalid',
    ];
    if(PHP_MAJOR_VERSION >= 7)
    {
      $types[] = Strings::RANDOM_STRING_RANDOM_BYTES;
    }
    if(PHP_MAJOR_VERSION < 7 || (PHP_MAJOR_VERSION == 7 && PHP_MINOR_VERSION < 1))
    {
      $types[] = Strings::RANDOM_STRING_MCRYPT;
    }
    foreach($types as $type)
    {
      static::assertEquals(
        40,
        strlen(Strings::randomString(40, $type))
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
    static::assertEquals(
      $expect,
      Strings::excerpt(
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
   * @dataProvider betweenProvider
   */
  public function testBetween($string, $start, $end, $inclusive, $expect)
  {
    static::assertEquals(
      $expect,
      Strings::between($string, $start, $end, $inclusive)
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
    static::assertInstanceOf("InvalidArgumentException", $caught);

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
    static::assertInstanceOf("InvalidArgumentException", $caught);

    $caught = null;
    try
    {
      Strings::stringable(tmpfile());
    }
    catch(InvalidArgumentException $ex)
    {
      $caught = $ex;
    }
    static::assertInstanceOf("InvalidArgumentException", $caught);
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
      static::assertEquals(
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
      static::assertEquals(
        $expect,
        Strings::splitLines($input, $retainEndings = false),
        ("(Discarded) " . addcslashes($input, "\r\n\\"))
      );
    }
  }

  public function testJsonPretty()
  {
    static::assertEquals(
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
      static::assertEquals($expect[1], Strings::escape($expect[0]));
    }
  }

  public function testStringFrom()
  {
    static::assertEquals(
      'Views\Dyn',
      Strings::offset('X\Y\Z\Com\Views\Dyn', 'Com\\')
    );
    static::assertEquals(
      'X\Y\Z\Com\Views\Dyn',
      Strings::offset('X\Y\Z\Com\Views\Dyn', 'Mi\\')
    );
  }

  public function testStrContains()
  {
    static::assertTrue(Strings::contains('abcdef', 'bcd'));
    static::assertTrue(Strings::contains('abcdef', 'bcd', false));
    static::assertFalse(Strings::contains('abCdef', 'bcd'));
    static::assertTrue(Strings::contains('aBcDeF', 'aBcDeF'));
    static::assertTrue(Strings::contains('aBcDeF', 'BcD'));
    static::assertTrue(Strings::contains('aBcDeF', 'bcd', false));
  }

  public function testContainsAny()
  {
    static::assertTrue(Strings::containsAny('abcdef', ['x', 'y', 'bc']));
    static::assertFalse(Strings::containsAny('abcdef', ['x', 'y', 'z']));
    static::assertTrue(Strings::containsAny('aBCdef', ['x', 'y', 'bc'], false));
    static::assertFalse(Strings::containsAny('aBCdef', ['x', 'y', 'bc']));
    static::assertFalse(Strings::containsAny('abcdef', ['x', 'y', 'z']));
  }

  public function testExploded()
  {
    $defaults = ["a", "b", "c", "d"];
    static::assertEquals(
      [1, 2, 3, 4],
      Strings::explode(",", "1,2,3,4", $defaults, 4)
    );
    static::assertEquals(
      [1, 2, "3,4", "d"],
      Strings::explode(",", "1,2,3,4", $defaults, 3)
    );
    static::assertEquals(
      [1, 2, "c", "d"],
      Strings::explode(",", "1,2", $defaults, 3)
    );
    static::assertEquals(
      [1, 2, "c", "d"],
      Strings::explode(",", "1,2", $defaults)
    );
    static::assertEquals(
      [1, 2, 3, 4, 5],
      Strings::explode(",", "1,2,3,4,5", $defaults)
    );
    static::assertEquals(
      [1, 2, 3, '-', '-'],
      Strings::explode(",", "1,2,3", '-', 5)
    );
  }

  public function testConcat()
  {
    static::assertEquals("ab", Strings::concat("a", "b"));
    static::assertEquals("a-b", Strings::concat("a", "-", "b"));
  }

  public function testStartsWith()
  {
    static::assertTrue(Strings::startsWith("abcdef", "ab", true));
    static::assertTrue(Strings::startsWith("aBcdef", "aB", true));
    static::assertTrue(Strings::startsWith("abcdef", "aB", false));
    static::assertTrue(Strings::startsWith("aBcdef", ["cd", 'aB'], true));

    static::assertFalse(Strings::startsWith("aBcdef", "ab", true));
    static::assertFalse(Strings::startsWith("aBcdef", "cd", false));
    static::assertFalse(Strings::startsWith("aBcdef", "cd", true));
  }

  public function testStartsWithAny()
  {
    static::assertTrue(Strings::startsWithAny("abcdef", ["c", "ab"], true));
    static::assertTrue(Strings::startsWithAny("aBcdef", ["c", "aB"], true));
    static::assertFalse(Strings::startsWithAny("abcdef", ["c", "ef"], true));
    static::assertTrue(Strings::startsWithAny("aBcdef", ["c", "aB"], false));
    static::assertFalse(Strings::startsWithAny("aBcdef", ["c", "ef"], false));
  }

  public function testEndsWith()
  {
    static::assertTrue(Strings::endsWith("abcdef", "f", true));
    static::assertTrue(Strings::endsWith("aBcdeF", "eF", true));
    static::assertTrue(Strings::endsWith("aBcdeF", "ef", false));
    static::assertTrue(Strings::endsWith("aBcdeF", ["de", "ef"], false));

    static::assertFalse(Strings::endsWith("aBcdef", "de", false));
    static::assertFalse(Strings::endsWith("aBcdef", "eF", true));
  }

  public function testEndsWithAny()
  {
    static::assertTrue(Strings::endsWithAny("abcdef", ["f", "yx"], true));
    static::assertTrue(Strings::endsWithAny("aBcdeF", ["x", "eF"], true));
    static::assertTrue(Strings::endsWithAny("aBcdef", ["c", "eF"], false));

    static::assertFalse(Strings::endsWithAny("abcdef", ["c", "eF"], true));
    static::assertFalse(Strings::endsWithAny("aBcdef", ["c", "ab"], false));
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
      static::assertEquals($expect[2], Strings::ltrim($expect[0], $expect[1]));
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
      static::assertTrue(
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

  public function testNTrim()
  {
    $string = null;
    static::assertNull(Strings::ntrim($string, "abc"));
    $string = "abc";
    static::assertEquals("", Strings::ntrim($string, "abc"));
    $string = "cba";
    static::assertEquals("", Strings::ntrim($string, "abc"));
    $string = "abcde";
    static::assertEquals("d", Strings::ntrim($string, "abce"));
  }

  /**
   * @param $expect
   * @param $input
   * @param $width
   * @param $break
   * @param $cut
   *
   * @dataProvider wordWrapProvider
   */
  public function testWordwrap($expect, $input, $width, $break, $cut)
  {
    static::assertEquals(
      $expect,
      Strings::wordWrap($input, $width, $break, $cut)
    );
  }

  public function wordWrapProvider()
  {
    return [
      ["abc", "abc", "3", "\n", true],
      ["abc-def", "abcdef", "3", "-", true],
      ["a-b-c-d-e-f", "abcdef", "1", "-", true],
    ];
  }

  public function testWrap()
  {
    static::assertEquals("'hey'", Strings::wrap('hey', '\''));
    static::assertEquals("'hey'", Strings::wrap('hey\'', '\'', true));
    static::assertEquals("'hey'", Strings::wrap('hey\'\'', '\'', true));
  }

  /** @dataProvider urlB64DecodeProvider */
  public function testUrlsafeBase64Decode($expect, $raw)
  {
    static::assertEquals($expect, Strings::urlsafeBase64Decode($raw));
  }

  public function urlB64DecodeProvider()
  {
    return [
      ['f', 'Zg=='],
      ['f', 'Zg='],
      ['fo', 'Zm8=='],
      ['foo', 'Zm9v==='],
      ['foob', 'Zm9vYg=='],
      ['fooba', 'Zm9vYmE=='],
      ['foobar', 'Zm9vYmFy====='],
      ['foobar', 'Zm9vYmFy=='],
      ['foobar', 'Zm9vYmFy'],
      [base64_decode('0MB2wKB+L3yvIdzeggmJ+5WOSLaRLTUPXbpzqUe0yuo='), '0MB2wKB-L3yvIdzeggmJ-5WOSLaRLTUPXbpzqUe0yuo'],
    ];
  }

  public function testNamedSplit()
  {
    $label = Strings::namedSplit(':', 'label:value', 'label', 'value');
    static::assertSame(['label' => 'label', 'value' => 'value',], $label);
    $label = Strings::namedSplit(':', 'label:value:x', 'label', 'value');
    static::assertSame(['label' => 'label', 'value' => 'value',], $label);
    $label = Strings::namedSplit(':', 'label', 'label', 'value');
    static::assertSame(['label' => 'label',], $label);
    $label = Strings::namedSplit(':', 'label:x:value', 'label', null, 'value');
    static::assertSame(['label' => 'label', 'value' => 'value',], $label);
  }

  public function testAcrynym()
  {
    $expectations = [
      ["first name", "FN"],
      ["second_name", "SN"],
      ["thirdName", "TN"],
      ["Band Name", "BN"],
      ["First_Lane", "FL"],
      ["FirstPine", "FP"],
      ["First Name Third Name", "FNTN"],
      ["First_Name Band_Name", "FNBN"],
      ["FirstName XeroName", "FNXN"],
    ];
    foreach($expectations as $expect)
    {
      static::assertEquals(
        $expect[1],
        Strings::acronym($expect[0], false)
      );
    }
  }
}
