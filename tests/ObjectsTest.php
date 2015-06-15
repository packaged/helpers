<?php

use Packaged\Helpers\Objects;

class ObjectsTest extends PHPUnit_Framework_TestCase
{
  public function testNewv()
  {
    $expect = new Pancake('Blueberry', "Maple Syrup");
    $this->assertEquals(
      $expect,
      Objects::create('Pancake', ['Blueberry', "Maple Syrup"])
    );
    $expect = new Pancake();
    $this->assertEquals(
      $expect,
      Objects::create('Pancake', [])
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
      [new \Packaged\Helpers\Strings, "Strings"],
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
      [new \Packaged\Helpers\Strings, '\Packaged\Helpers'],
    ];
    foreach($expectations as $expect)
    {
      $this->assertEquals($expect[1], Objects::getNamespace($expect[0]));
    }
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
    return Objects::propertyValues($this);
  }
}
