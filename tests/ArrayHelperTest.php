<?php
namespace Packaged\Tests;

use Packaged\Helpers\ArrayHelper;
use PHPUnit\Framework\TestCase;
use stdClass;

class ArrayHelperTest extends TestCase
{
  public function testConstructors()
  {
    $arr = ArrayHelper::create(new Objects\ObjectArrayHelper());
    static::assertEquals('Object', $arr->getValue('name'));
    static::assertEquals('One', $arr->getValue('test'));
    static::assertEquals('default', $arr->getValue('missing', 'default'));

    $arr = ArrayHelper::create(
      [
        'name'    => 'Array',
        'test'    => 'Two',
        'nullify' => null,
      ]
    );
    static::assertEquals('Array', $arr->getValue('name'));
    static::assertNull($arr->getValue('nullify'));
    static::assertEquals('Two', $arr->getValue('test'));
    static::assertEquals('default2', $arr->getValue('missing', 'default2'));

    $arr = ArrayHelper::create('name=string&test=Three');
    static::assertEquals('string', $arr->getValue('name'));
    static::assertEquals('Three', $arr->getValue('test'));
    static::assertEquals('default3', $arr->getValue('missing', 'default3'));
  }

  public function testUnsupported()
  {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('boolean is not currently supported');
    ArrayHelper::create(false);
  }

  public function testGetSet()
  {
    $arr = new ArrayHelper(
      [
        'name' => 'Array',
        'test' => 'Four',
      ]
    );
    static::assertEquals('Array', $arr->getValue('name'));
    $arr->setValue('name', 'TestGetSet');
    static::assertEquals('TestGetSet', $arr->getValue('name'));
    static::assertEquals(
      [
        'name' => 'TestGetSet',
        'test' => 'Four',
      ],
      $arr->getValues()
    );
  }

  public function testArrayAccess()
  {
    $testClass = new stdClass();
    $testClass->value1 = 'value one';
    $testClass->value2 = 'value two';
    $testClass->array = ['test' => 'test1', 'test'];
    $array = ArrayHelper::create($testClass);
    static::assertEquals('value one', $array['value1']);
    static::assertTrue(isset($array['value2']));
    unset($array['value2']);
    static::assertFalse(isset($array['value2']));
    static::assertNull($array['value2']);
    $array['value2'] = 'value new';
    static::assertEquals('value new', $array['value2']);
  }

  public function testToArray()
  {
    $testClass = new stdClass();
    $testClass->value1 = 'value one';
    $testClass->array = ['test' => 'test1', 'test'];
    $testClass->nested = new stdClass();
    $testClass->nested->value1 = 'value one';
    $testClass->nested->array = ['test' => 'test1', 'test'];

    static::assertEquals(
      [
        'value1' => 'value one',
        'array'  => ['test' => 'test1', 'test'],
        'nested' => [
          'value1' => 'value one',
          'array'  => ['test' => 'test1', 'test'],
        ],
      ],
      ArrayHelper::toArray($testClass)
    );
  }
}
