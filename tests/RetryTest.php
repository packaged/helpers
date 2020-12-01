<?php
namespace Packaged\Tests;

use Exception;
use Packaged\Helpers\RetryHelper;
use PHPUnit\Framework\TestCase;

class RetryTest extends TestCase
{
  /**
   * @expectedException Exception
   * @expectedExceptionMessage fail 3
   */
  public function testRetries()
  {
    $count = 0;
    $callFn = function () use (&$count) {
      $count++;
      throw new Exception('fail ' . $count);
    };
    RetryHelper::retry(2, $callFn);
  }

  /**
   * @expectedException Exception
   * @expectedExceptionMessage fail 1
   */
  public function testNoRetries()
  {
    $count = 0;
    $callFn = function () use (&$count) {
      $count++;
      throw new Exception('fail ' . $count);
    };
    RetryHelper::retry(0, $callFn);
  }

  /**
   * @expectedException Exception
   * @expectedExceptionMessage Invalid value for retries
   */
  public function testNegativeRetries()
  {
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
