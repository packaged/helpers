<?php
use Packaged\Helpers\ValueAs;

class ValueAsTest extends PHPUnit_Framework_TestCase
{
  /**
   * @dataProvider exactProvider
   */
  public function testExactConversions($method, $value, $default, $expect)
  {
    $this->assertSame(
      $expect,
      \Packaged\Helpers\ValueAs::$method($value, $default)
    );
  }

  /**
   * @dataProvider matchProvider
   */
  public function testEqualConversions($method, $value, $default, $expect)
  {
    $this->assertEquals(
      $expect,
      \Packaged\Helpers\ValueAs::$method($value, $default)
    );
  }

  public function exactProvider()
  {
    $objectTest = new stdClass();
    $objectTest->item = 'value';

    return [
      ['bool', 1, null, true],
      ['bool', true, null, true],
      ['bool', 'true', null, true],
      ['bool', 0, null, false],
      ['bool', false, null, false],
      ['bool', 'false', null, false],
      ['bool', null, null, null],
      ['int', null, null, null],
      ['int', 1, null, 1],
      ['int', "1", null, 1],
      ['int', "1a", null, 1],
      ['int', "a1", null, 0],
      ['float', null, null, null],
      ['float', 0.1, null, 0.1],
      ['float', "0.1", null, 0.1],
      ['string', null, null, null],
      ['string', "hello", null, "hello"],
      ['string', true, null, "true"],
      ['string', false, null, "false"],
      ['string', 123, null, "123"],
      ['normalisedString', null, null, null],
      ['normalisedString', "hey\r\nhow", null, "hey\nhow"],
      ['normalisedString', "hey\rhow", null, "hey\nhow"],
      ['arr', null, null, null],
      ['arr', ["hey"], null, ["hey"]],
      ['arr', "hey", null, ["hey"]],
      ['arr', "hello,world", null, ["hello", "world"]],
      ['arr', "test=one&unit=two", null, ["test" => 'one', "unit" => 'two']],
      ['arr', "", ["test"], ["test"]],
      ['arr', tmpfile(), ["test"], ["test"]],
      ['arr', $objectTest, ["test"], ["item" => "value"]],
      ['obj', null, null, null],
      ['obj', $objectTest, null, $objectTest],
    ];
  }

  public function matchProvider()
  {
    $objectTest = new stdClass();
    $objectTest->item = 'value';

    return [
      ['obj', ['item' => 'value'], null, $objectTest],
      ['obj', 'invalid', $objectTest, $objectTest],
    ];
  }

  public function testNonempty()
  {
    $this->assertEquals(
      'zebra',
      ValueAs::nonempty(false, null, 0, '', [], 'zebra')
    );

    $this->assertEquals(
      null,
      ValueAs::nonempty()
    );

    $this->assertEquals(
      false,
      ValueAs::nonempty(null, false)
    );

    $this->assertEquals(
      null,
      ValueAs::nonempty(false, null)
    );
  }

  public function testCoalesce()
  {
    $this->assertEquals(
      'zebra',
      ValueAs::coalesce(null, 'zebra')
    );

    $this->assertEquals(
      null,
      ValueAs::coalesce()
    );

    $this->assertEquals(
      false,
      ValueAs::coalesce(false, null)
    );

    $this->assertEquals(
      false,
      ValueAs::coalesce(null, false)
    );
  }
}
