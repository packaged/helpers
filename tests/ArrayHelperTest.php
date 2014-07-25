<?php

class ArrayHelperTest extends PHPUnit_Framework_TestCase
{
  public function testConstructors()
  {
    $arr = \Packaged\Helpers\ArrayHelper::create(new ObjectArrayHelper());
    $this->assertEquals('Object', $arr->getValue('name'));
    $this->assertEquals('One', $arr->getValue('test'));
    $this->assertEquals('default', $arr->getValue('missing', 'default'));

    $arr = \Packaged\Helpers\ArrayHelper::create(
      [
        'name' => 'Array',
        'test' => 'Two'
      ]
    );
    $this->assertEquals('Array', $arr->getValue('name'));
    $this->assertEquals('Two', $arr->getValue('test'));
    $this->assertEquals('default2', $arr->getValue('missing', 'default2'));

    $arr = \Packaged\Helpers\ArrayHelper::create('name=string&test=Three');
    $this->assertEquals('string', $arr->getValue('name'));
    $this->assertEquals('Three', $arr->getValue('test'));
    $this->assertEquals('default3', $arr->getValue('missing', 'default3'));
  }

  public function testUnsupported()
  {
    $this->setExpectedException(
      'Exception',
      'boolean is not currently supported'
    );
    \Packaged\Helpers\ArrayHelper::create(false);
  }

  public function testGetSet()
  {
    $arr = new \Packaged\Helpers\ArrayHelper(
      [
        'name' => 'Array',
        'test' => 'Four'
      ]
    );
    $this->assertEquals('Array', $arr->getValue('name'));
    $arr->setValue('name', 'TestGetSet');
    $this->assertEquals('TestGetSet', $arr->getValue('name'));
    $this->assertEquals(
      [
        'name' => 'TestGetSet',
        'test' => 'Four'
      ],
      $arr->getValues()
    );
  }
}

class ObjectArrayHelper
{
  public $name = 'Object';
  public $test = 'One';
}
