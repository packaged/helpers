<?php

namespace Packaged\Tests;

use Packaged\Helpers\ExceptionHelper;
use Packaged\Tests\Objects\Thing;
use PHPUnit\Framework\TestCase;
use stdClass;

class ExceptionHelperTest extends TestCase
{
  /**
   * @dataProvider dataProvider
   *
   * @param $arguments
   * @param $expected
   */
  public function testExceptionTrace($arguments, $expected)
  {
    try
    {
      $this->_someException(...$arguments);
    }
    catch(\Throwable $e)
    {
      static::assertEquals(
        "#0 /tests/ExceptionHelperTest.php(22): Packaged\Tests\ExceptionHelperTest->_someException({$expected[0]})",
        $this->_normalize($e->getTraceAsString())
      );
      static::assertEquals(
        "#0 /tests/ExceptionHelperTest.php(22): Packaged\Tests\ExceptionHelperTest->_someException({$expected[1]})",
        $this->_normalize(ExceptionHelper::getTraceAsString($e))
      );
    }
  }

  public function dataProvider()
  {
    $res = tmpfile();
    $resOutput = (string)$res;
    return [
      [
        [12345, 'string', null, ['array'], $res],
        ["12345, 'string', NULL, Array, $resOutput", "12345, 'string', NULL, Array, $resOutput (stream)"],
      ],
      [
        [12345, '123456789012345678901234567890', ['array']],
        ["12345, '123456789012345...', Array", "12345, '123456789012345678901234567890', Array"],
      ],
      [['123456789012345678901234567890'], ["'123456789012345...'", "'123456789012345678901234567890'"]],
      [[new stdClass()], ["Object(stdClass)", "Object(stdClass)"]],
      [[new Thing('', '', '', '')], ["Object(Packaged\Tests\Objects\Thing)", "Object(Packaged\Tests\Objects\Thing)"]],
    ];
  }

  private function _someException(...$args)
  {
    throw new \Exception('test exception');
  }

  private function _normalize(string $str)
  {
    $str = implode("\n", array_slice(explode("\n", $str), 0, 1));
    return str_replace(dirname(__DIR__), '', $str);
  }
}
