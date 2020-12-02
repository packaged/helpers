<?php
namespace Packaged\Tests;

use Exception;
use Packaged\Helpers\RetryHelper;
use PHPUnit\Framework\TestCase;

class RetryTest extends TestCase
{
  public function testRetries()
  {
    self::expectException(Exception::class);
    self::expectExceptionMessage('fail 3');

    $count = 0;
    $callFn = function () use (&$count) {
      $count++;
      throw new Exception('fail ' . $count);
    };
    RetryHelper::retry(2, $callFn);
  }

  public function testNoRetries()
  {
    self::expectException(Exception::class);
    self::expectExceptionMessage('fail 1');

    $count = 0;
    $callFn = function () use (&$count) {
      $count++;
      throw new Exception('fail ' . $count);
    };
    RetryHelper::retry(0, $callFn);
  }

  public function testNegativeRetries()
  {
    self::expectException(Exception::class);
    self::expectExceptionMessage('Invalid value for retries');

    $count = 0;
    $callFn = function () use (&$count) {
      $count++;
      throw new Exception('fail ' . $count);
    };
    RetryHelper::retry(-1000, $callFn);
  }

  /**
   * Retry succeeds on third attempt (second/final retry)
   */
  public function testRetryReturn()
  {
    $count = 0;
    $callFn = function () use (&$count) {
      $count++;
      if($count == 3)
      {
        return 'response';
      }
      throw new Exception('fail ' . $count);
    };
    $response = RetryHelper::retry(2, $callFn);
    static::assertEquals('response', $response);
    static::assertEquals(3, $count);
  }
}
