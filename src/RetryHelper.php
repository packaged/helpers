<?php
namespace Packaged\Helpers;

use Exception;
use function is_int;

class RetryHelper
{
  /**
   * Execute [$callFunction] a maximum of [$retries] times.
   *
   * If an exception is thrown, pass it to [$catchFunction]
   * - Throw [\Exception] to exit retries and throw this exception.
   * - Return [false] to exit retries and throw the exception.
   * - Return [true] to retry OR throw the exception.
   *
   * @param int           $retries
   * @param callable      $callFunction
   * @param callable|null $catchFunction
   *
   * @return mixed
   * @throws Exception
   */
  public static function retry(
    $retries, callable $callFunction, callable $catchFunction = null
  )
  {
    $return = null;
    if(!is_int($retries) || $retries < 0)
    {
      throw new Exception('Invalid value for retries');
    }

    while(true)
    {
      try
      {
        $return = $callFunction();
        break;
      }
      catch(Exception $e)
      {
        if(($catchFunction && !$catchFunction($e)) || $retries <= 0)
        {
          throw $e;
        }
        $retries--;
      }
    }
    return $return;
  }
}
