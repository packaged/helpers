<?php

/**
 * @author  brooke.bryan
 */
class GlobalFunctionsTest extends PHPUnit_Framework_TestCase
{
  public static function setUpBeforeClass()
  {
    \Packaged\Helpers\PackagedHelpers::includeGlobalFunctions();
  }

  public function testDoubleInclude()
  {
    \Packaged\Helpers\PackagedHelpers::includeGlobalFunctions();
    $this->assertTrue(function_exists('idp'));
  }

  public function testJsonPretty()
  {
    $this->assertEquals(
      '{
    "x": "y"
}',
      json_pretty(["x" => "y"])
    );
  }

  public function testClassShortName()
  {
    $expectations = [
      ['Strings', "Strings"],
      ['\Packaged\Helpers\Strings', "Strings"],
      [new \Packaged\Helpers\Strings, "Strings"],
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals($expect[1], class_shortname($expect[0]));
    }
  }

  public function testEsc()
  {
    $expectations = [
      ['Strings', "Strings"],
      ['Stri"ngs', "Stri&quot;ngs"],
      ['Stri\'ngs', "Stri&#039;ngs"],
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals($expect[1], esc($expect[0]));
    }
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
      ]
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals($expect[2], psort($expect[0], $expect[1]));
    }
  }

  public function testIsAssociativeArray()
  {
    $this->assertTrue(is_assoc(["a" => "A", "b" => "B"]));
    $this->assertFalse(is_assoc(["A", "B"]));
  }

  public function testShuffleAssoc()
  {
    $this->assertEquals('string', shuffle_assoc('string'));

    $expected = ['x' => 'x', 'y' => 'y', 'z' => 'z'];
    $shuffled = shuffle_assoc($expected);
    $this->assertFalse((bool)array_diff_assoc($expected, $shuffled));
    $this->assertFalse((bool)array_diff_assoc($shuffled, $expected));

    srand(40);
    $expected = ['z' => 'z', 'y' => 'y', 'x' => 'x'];
    $shuffled = shuffle_assoc($expected);
    $this->assertEquals(json_encode($expected), json_encode($shuffled));
  }

  public function testStartsWith()
  {
    $this->assertTrue(starts_with("abcdef", "ab", true));
    $this->assertTrue(starts_with("aBcdef", "aB", true));
    $this->assertTrue(starts_with("abcdef", "aB", false));
    $this->assertTrue(starts_with("aBcdef", ["cd", 'aB'], true));

    $this->assertFalse(starts_with("aBcdef", "ab", true));
    $this->assertFalse(starts_with("aBcdef", "cd", false));
    $this->assertFalse(starts_with("aBcdef", "cd", true));
  }

  public function testStartsWithAny()
  {
    $this->assertTrue(starts_with_any("abcdef", ["c", "ab"], true));
    $this->assertTrue(starts_with_any("aBcdef", ["c", "aB"], true));
    $this->assertFalse(starts_with_any("abcdef", ["c", "ef"], true));
    $this->assertTrue(starts_with_any("aBcdef", ["c", "aB"], false));
    $this->assertFalse(starts_with_any("aBcdef", ["c", "ef"], false));
  }

  public function testEndsWith()
  {
    $this->assertTrue(ends_with("abcdef", "f", true));
    $this->assertTrue(ends_with("aBcdeF", "eF", true));
    $this->assertTrue(ends_with("aBcdeF", "ef", false));
    $this->assertTrue(ends_with("aBcdeF", ["de", "ef"], false));

    $this->assertFalse(ends_with("aBcdef", "de", false));
    $this->assertFalse(ends_with("aBcdef", "eF", true));
  }

  public function testEndsWithAny()
  {
    $this->assertTrue(ends_with_any("abcdef", ["f", "yx"], true));
    $this->assertTrue(ends_with_any("aBcdeF", ["x", "eF"], true));
    $this->assertTrue(ends_with_any("aBcdef", ["c", "eF"], false));

    $this->assertFalse(ends_with_any("abcdef", ["c", "eF"], true));
    $this->assertFalse(ends_with_any("aBcdef", ["c", "ab"], false));
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
      $this->assertEquals($expect[2], strip_start($expect[0], $expect[1]));
    }
  }

  public function testImplodeList()
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
        implode_list($expect[0], $expect[1], $expect[2])
      );
    }
  }

  public function testMsleep()
  {
    //Test no output
    $this->expectOutputString('');
    $time = microtime(true);
    msleep(1);
    $deltaMs = (microtime(true) - $time) * 1000;
    //Microtime appears to be a fairly unreliable way to check
    //Below assertion disabled due to flakey validation
    //$this->assertTrue($deltaMs > 0.1 && $deltaMs < 1.5);
  }

  public function testGetNamespace()
  {
    $expectations = [
      [null, ''],
      ['', ''],
      ['Strings', ''],
      ['\Packaged\Helpers\Strings', '\Packaged\Helpers'],
      [new \Packaged\Helpers\Strings, '\Packaged\Helpers'],
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals($expect[1], get_namespace($expect[0]));
    }
  }

  public function testBuildPath()
  {
    $this->assertEquals("a" . DIRECTORY_SEPARATOR . "b", build_path("a", "b"));
    $this->assertEquals("a" . DIRECTORY_SEPARATOR . "b", build_path("a", "b"));
  }

  public function testBuildWindowsPath()
  {
    $this->assertEquals("a\\b", build_path_win("a", "b"));
  }

  public function testBuildUnixPath()
  {
    $this->assertEquals("a/b", build_path_unix("a", "b"));
  }

  public function testBuildCustomPath()
  {
    $this->assertEquals("a|b", build_path_custom("|", ["a", "b"]));
    $this->assertEquals("a|b", build_path_custom("|", [0 => "a", 1 => "b"]));
  }

  public function testConcat()
  {
    $this->assertEquals("ab", concat("a", "b"));
    $this->assertEquals("a-b", concat("a", "-", "b"));
  }

  public function testArrayAdd()
  {
    $initialArray = ["a" => 1, "b" => 2];
    $this->assertEquals(
      $initialArray + ["x"],
      array_add_value($initialArray, "x")
    );
    $this->assertEquals(
      $initialArray + ["c" => 3],
      array_add_value($initialArray, 3, "c")
    );
  }

  public function testIdp()
  {
    $object = new stdClass();
    $object->name = "apple";
    $this->assertEquals("apple", idp($object, "name", "pear"));
    $this->assertEquals("orange", idp($object, "noprop", "orange"));
  }

  public function testExploded()
  {
    $defaults = ["a", "b", "c", "d"];
    $this->assertEquals([1, 2, 3, 4], exploded(",", "1,2,3,4", $defaults, 4));
    $this->assertEquals(
      [1, 2, "3,4", "d"],
      exploded(",", "1,2,3,4", $defaults, 3)
    );
    $this->assertEquals(
      [1, 2, "c", "d"],
      exploded(",", "1,2", $defaults, 3)
    );
    $this->assertEquals(
      [1, 2, "c", "d"],
      exploded(",", "1,2", $defaults)
    );
    $this->assertEquals(
      [1, 2, 3, 4, 5],
      exploded(",", "1,2,3,4,5", $defaults)
    );
    $this->assertEquals(
      [1, 2, 3, '-', '-'],
      exploded(",", "1,2,3", '-', 5)
    );
  }

  public function testBetween()
  {
    $this->assertTrue(between(2, 1, 3));
    $this->assertTrue(between(2, 1, 2));

    $this->assertFalse(between(3, 1, 2));
    $this->assertFalse(between(3, 1, 2, true));

    $this->assertFalse(between(2, 1, 2, false));
  }

  public function testProperties()
  {
    $expect = ['name' => null, 'age' => null];
    $class = new PropertyClass();
    $this->assertNotEquals($expect, $class->objectVars());
    $this->assertEquals($expect, $class->publicVars());
    $this->assertEquals($expect, get_object_vars($class));
    $this->assertEquals($expect, get_public_properties($class));
    $this->assertEquals(['name', 'age'], get_public_properties($class, true));
  }

  public function testStringFrom()
  {
    $this->assertEquals(
      'Views\Dyn',
      string_from('X\Y\Z\Com\Views\Dyn', 'Com\\')
    );
    $this->assertEquals(
      'X\Y\Z\Com\Views\Dyn',
      string_from('X\Y\Z\Com\Views\Dyn', 'Mi\\')
    );
  }

  public function testNFormat()
  {
    $this->assertEquals('-', nformat('-'));
    $this->assertEquals(0, nformat('-', 0, '.', ',', true));
    $this->assertEquals('10,000', nformat('10000'));
  }

  public function testNFormats()
  {
    $this->assertEquals('1k', nhumanize(1000));
    $this->assertEquals('1m', nhumanize(1000000));
    $this->assertEquals('1b', nhumanize(1000000000));
    $this->assertEquals('1t', nhumanize(1000000000000));

    $this->assertEquals('1k', nhumanize(1000, true));
    $this->assertEquals('1m', nhumanize(1000000, true));
    $this->assertEquals('1g', nhumanize(1000000000, true));
    $this->assertEquals('1t', nhumanize(1000000000000, true));
  }

  public function testStrContains()
  {
    $this->assertTrue(str_contains('abcdef', 'bcd'));
    $this->assertTrue(str_contains('abcdef', 'bcd', false));
    $this->assertFalse(str_contains('abCdef', 'bcd'));
    $this->assertTrue(str_contains('aBcDeF', 'aBcDeF'));
    $this->assertTrue(str_contains('aBcDeF', 'BcD'));
    $this->assertTrue(str_contains('aBcDeF', 'bcd', false));
  }

  public function testContainsAny()
  {
    $this->assertTrue(contains_any('abcdef', ['x', 'y', 'bc']));
    $this->assertFalse(contains_any('abcdef', ['x', 'y', 'z']));
    $this->assertTrue(contains_any('aBCdef', ['x', 'y', 'bc'], false));
    $this->assertFalse(contains_any('aBCdef', ['x', 'y', 'bc']));
    $this->assertFalse(contains_any('abcdef', ['x', 'y', 'z']));
  }

  public function testGlobRecursive()
  {
    $baseDir = dirname(__DIR__);
    $this->assertContains(
      build_path($baseDir, 'composer.json'),
      glob_recursive($baseDir, '*.json')
    );
    $this->assertContains(
      build_path($baseDir, 'phpunit.xml'),
      glob_recursive($baseDir, '*.xml')
    );
    $this->assertContains(
      build_path($baseDir, 'src', 'includes', 'GlobalFunctions.php'),
      glob_recursive(build_path($baseDir, 'src', 'includes'), '*.php')
    );
    $this->assertContains(
      build_path($baseDir, 'src', 'Traits', 'ArrayAccessTrait.php'),
      glob_recursive($baseDir, '*.php')
    );
  }

  public function testInArrayI()
  {
    $array = ['ab', 'cd', 'EF', "GH"];
    $this->assertTrue(in_arrayi('ab', $array));
    $this->assertTrue(in_arrayi('ef', $array));
    $this->assertTrue(in_arrayi('CD', $array));
    $this->assertFalse(in_arrayi('ij', $array));
  }

  public function testHydrate()
  {
    $dest = new stdClass();
    $dest->nullify = 'Please';

    $source = new PropertyClass();
    $source->name = 'Test';
    $source->age = 19;
    $source->nullify = null;

    hydrate($dest, $source, [null]);
    $this->assertEquals('Please', $dest->nullify);

    hydrate($dest, $source, ['nullify'], false);
    $this->assertEquals('Please', $dest->nullify);

    hydrate($dest, $source, ['nullify'], true);
    $this->assertNull($dest->nullify);

    hydrate($dest, $source, ['name']);

    $this->assertObjectHasAttribute('name', $dest);
    $this->assertEquals('Test', $dest->name);

    $this->assertObjectNotHasAttribute('age', $dest);
    hydrate($dest, $source, ['age']);

    $this->assertObjectHasAttribute('age', $dest);
    $this->assertEquals('19', $dest->age);

    $this->setExpectedException("Exception");
    hydrate(['' => ''], $source, []);
  }

  /**
   * @large
   */
  public function testSingleBit()
  {
    $this->assertTrue(is_single_bit(1));
    $this->assertTrue(is_single_bit("1"));
    $this->assertTrue(is_single_bit(2));
    $this->assertTrue(is_single_bit("2"));
    $this->assertTrue(is_single_bit(4));

    $fails = [3, 5, 6, 7, 9, 10, 11, 13, 14, 15];
    foreach($fails as $checkBit)
    {
      $this->assertFalse(is_single_bit($checkBit));
    }

    $checkBit = 4;
    for($i = 0; $i < 10000; $i++)
    {
      $checkBit = bcmul($checkBit, 2);
      $this->assertTrue(is_single_bit($checkBit));
      $this->assertFalse(is_single_bit(bcsub($checkBit, 3)));
    }
  }

  public function testPropertyNonEmpty()
  {
    $object = new PropertyClass();
    $object->name = 't_name';
    $object->age = 't_age';

    $this->assertEquals('t_age', pnonempty($object, ['miss', 'age', 'name']));
    $this->assertNull(pnonempty($object, ['miss1', 'miss2']));
    $this->assertNull(pnonempty($object, []));
    $this->assertEquals('no', pnonempty($object, ['miss1', 'miss2'], 'no'));
    $this->assertEquals('no', pnonempty($object, [], 'no'));
  }

  public function testArrayNonEmpty()
  {
    $array = ['name' => 't_name', 'age' => 't_age'];

    $this->assertEquals('t_age', inonempty($array, ['miss', 'age', 'name']));
    $this->assertNull(inonempty($array, ['miss1', 'miss2']));
    $this->assertNull(inonempty($array, []));
    $this->assertEquals('no', inonempty($array, ['miss1', 'miss2'], 'no'));
    $this->assertEquals('no', inonempty($array, [], 'no'));
  }
}
