<?php
namespace Packaged\Helpers;

class RetryHelper
{
  /**
   * Execute [$callFunction] a maximum of [$retries] times.
   *
   * If an exception is thrown, pass it to [$catchFunction]
   * - Return false or throw an exception to exit immediately.
   * - Return true to retry OR throw the original exception.
   * - Return an exception to retry OR throw the returned exception.
   *
   * @param int           $retries
   * @param callable      $callFunction
   * @param callable|null $catchFunction
   *
   * @return mixed
   * @throws \Exception
   */
  public static function retry(
    $retries, callable $callFunction, callable $catchFunction = null
  )
  {
    if(!is_int($retries) || $retries < 0)
    {
      throw new \Exception('Invalid value for retries');
    }

    while(true)
    {
      try
      {
        return $callFunction();
      }
      catch(\Exception $e)
      {
        $retryException = true;
        if($catchFunction)
        {
          $retryException = $catchFunction($e);
          if($retryException instanceof \Exception)
          {
            $e = $retryException;
          }
        }
        if((!$retryException) || $retries <= 0)
        {
          throw $e;
        }
        $retries--;
      }
    }
    return null;
  }
}
