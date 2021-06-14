<?php
namespace Packaged\Tests;

use Exception;
use Packaged\Helpers\ValueAs;
use PHPUnit\Framework\TestCase;
use stdClass;

class ValueAsTest extends TestCase
{
  /**
   * @dataProvider exactProvider
   *
   * @param $method
   * @param $value
   * @param $default
   * @param $expect
   */
  public function testExactConversions($method, $value, $default, $expect)
  {
    static::assertSame($expect, ValueAs::$method($value, $default));
  }

  /**
   * @dataProvider matchProvider
   *
   * @param $method
   * @param $value
   * @param $default
   * @param $expect
   */
  public function testEqualConversions($method, $value, $default, $expect)
  {
    static::assertEquals(
      $expect,
      ValueAs::$method($value, $default)
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
      ['bool', 'yes', null, true],
      ['bool', 0, null, false],
      ['bool', false, null, false],
      ['bool', 'false', null, false],
      ['bool', 'no', null, false],
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
      ['arr', "test.one=one.test", null, ["test.one" => 'one.test']],
      ['arr', "test=one", null, ["test" => 'one']],
      ['arr', "hello&world=two", null, ['hello' => '', 'world' => 'two']],
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
    static::assertEquals(
      'zebra',
      ValueAs::nonempty(false, null, 0, '', [], 'zebra')
    );

    static::assertEquals(
      null,
      ValueAs::nonempty()
    );

    static::assertEquals(
      false,
      ValueAs::nonempty(null, false)
    );

    static::assertEquals(
      null,
      ValueAs::nonempty(false, null)
    );
  }

  public function testCoalesce()
  {
    static::assertEquals(
      'zebra',
      ValueAs::coalesce(null, 'zebra')
    );

    static::assertEquals(
      null,
      ValueAs::coalesce()
    );

    static::assertEquals(
      false,
      ValueAs::coalesce(false, null)
    );

    static::assertEquals(
      false,
      ValueAs::coalesce(null, false)
    );
  }

  public function testExceptionIf()
  {
    $value = false;
    static::assertFalse(ValueAs::exceptionIf($value, Exception::class));
    $value = true;
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Test Exception');
    static::assertTrue(ValueAs::exceptionIf($value, Exception::class, 'Test Exception'));
  }

  public function testExceptionIfFixed()
  {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Test Exception');
    static::assertTrue(ValueAs::exceptionIf(true, new Exception('Test Exception')));
  }

  public function testTransformed()
  {
    $callback = function ($value) {
      if($value == '1')
      {
        return null;
      }
      return 'VAL' . strtoupper($value);
    };
    static::assertEquals('VALABC', ValueAs::transformed('AbC', $callback, 'ab'));
    static::assertEquals('ab', ValueAs::transformed('1', $callback, 'ab'));
    static::assertEquals('ab', ValueAs::transformed(null, $callback, 'ab'));
  }

  public function testCaught()
  {
    static::assertEquals('abc', ValueAs::caught(function () { throw new Exception('e'); }, 'abc'));
    static::assertEquals(
      'abcdef',
      ValueAs::caught(
        function () { throw new Exception('abc'); },
        function (Exception $e) { return $e->getMessage() . 'def'; }
      )
    );
    static::assertEquals('xyz', ValueAs::caught(function () { return 'xyz'; }, 'abc'));
  }
}
