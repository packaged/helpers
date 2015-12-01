<?php
namespace Packaged\Helpers;

class RetryHelper
{
  /**
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
        if($retries <= 0 || ($catchFunction && !$catchFunction($e)))
        {
          throw $e;
        }
        $retries--;
      }
    }
    return null;
  }
}
