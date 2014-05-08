<?php

/**
 * @author  brooke.bryan
 */
class GlobalFunctionsTest extends PHPUnit_Framework_TestCase
{
  public function testDirectorySeparator()
  {
    $this->assertTrue(defined("DS"), "Ensure DS is defined");
    $this->assertEquals(
      DS,
      DIRECTORY_SEPARATOR,
      "DS matches DIRECTORY_SEPARATOR"
    );
  }

  public function testVarDumpJson()
  {
    $this->expectOutputString(
      '{
    "x": "y"
}string(16) "{
    "x": "y"
}"
'
    );
    var_dump_json(["x" => "y"], true);
    var_dump_json(["x" => "y"], false);
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
    $apple       = new stdClass();
    $apple->name = "apple";
    $pear        = new stdClass();
    $pear->name  = "pear";
    $grape       = new stdClass();
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

  public function testStartsWith()
  {
    $this->assertTrue(starts_with("abcdef", "ab", true));
    $this->assertTrue(starts_with("aBcdef", "aB", true));
    $this->assertTrue(starts_with("abcdef", "aB", false));
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
    $object       = new stdClass();
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
    $class  = new PropertyClass();
    $this->assertNotEquals($expect, $class->objectVars());
    $this->assertEquals($expect, $class->publicVars());
    $this->assertEquals($expect, get_object_vars($class));
    $this->assertEquals($expect, get_public_properties($class));
  }
}

class PropertyClass
{
  public $name;
  public $age;
  protected $_gender;
  private $_ryan;

  public function objectVars()
  {
    return get_object_vars($this);
  }

  public function publicVars()
  {
    return get_public_properties($this);
  }
}
