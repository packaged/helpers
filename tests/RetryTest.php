<?php

class RetryTest extends PHPUnit_Framework_TestCase
{
  /**
   * @expectedException Exception
   * @expectedExceptionMessage fail 3
   */
  public function testRetries()
  {
    $count = 0;
    $callFn = function () use (&$count)
    {
      $count++;
      throw new Exception('fail ' . $count);
    };
    \Packaged\Helpers\RetryHelper::retry(2, $callFn);
  }

  /**
   * @expectedException Exception
   * @expectedExceptionMessage fail 1
   */
  public function testNoRetries()
  {
    $count = 0;
    $callFn = function () use (&$count)
    {
      $count++;
      throw new Exception('fail ' . $count);
    };
    \Packaged\Helpers\RetryHelper::retry(0, $callFn);
  }

  /**
   * @expectedException Exception
   * @expectedExceptionMessage Invalid value for retries
   */
  public function testNegativeRetries()
  {
    $count = 0;
    $callFn = function () use (&$count)
    {
      $count++;
      throw new Exception('fail ' . $count);
    };
    \Packaged\Helpers\RetryHelper::retry(-1000, $callFn);
  }

  /**
   * Retry succeeds on third attempt (second/final retry)
   */
  public function testRetryReturn()
  {
    $count = 0;
    $callFn = function () use (&$count)
    {
      $count++;
      if($count == 3)
      {
        return 'response';
      }
      throw new Exception('fail ' . $count);
    };
    $response = \Packaged\Helpers\RetryHelper::retry(2, $callFn);
    $this->assertEquals('response', $response);
    $this->assertEquals(3, $count);
  }
}
