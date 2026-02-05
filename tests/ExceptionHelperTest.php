<?php

namespace Packaged\Tests;

use Packaged\Helpers\ExceptionHelper;
use PHPUnit\Framework\TestCase;

class ExceptionHelperTest extends TestCase
{
  public function testExceptionTrace()
  {
    try
    {
      $this->_someException('test');
    }
    catch(\Throwable $e)
    {
      $trace = ExceptionHelper::getTraceAsString($e);
      static::assertStringContainsString('ExceptionHelperTest.php', $trace);
      static::assertStringContainsString('_someException', $trace);
      static::assertStringContainsString('{main}', $trace);
    }
  }

  public function testInternalFunction()
  {
    try
    {
      call_user_func([$this, '_throwException']);
    }
    catch(\Throwable $e)
    {
      $trace = ExceptionHelper::getTraceAsString($e);
      static::assertStringContainsString('[internal function]', $trace);
    }
  }

  private function _someException(...$args)
  {
    throw new \Exception('test exception');
  }

  private function _throwException()
  {
    throw new \Exception('internal test');
  }
}