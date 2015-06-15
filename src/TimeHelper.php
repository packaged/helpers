<?php
namespace Packaged\Helpers;

class TimeHelper
{
  const SQL_DATETIME = 'Y-m-d H:i:s';
  const SQL_DATE = 'Y-m-d';
  const SQL_TIME = 'H:i:s';

  public static function uniqidToMicrotime($uniqid, $hasEntropy = false)
  {
    if($hasEntropy)
    {
      $uniqid = substr($uniqid, 0, -10);
    }
    $microtime = (int)hexdec(substr($uniqid, -5));
    $timestamp = (int)hexdec(substr($uniqid, -13, -5));
    return $timestamp . '.' . $microtime;
  }

  public static function microtime()
  {
    return floor(microtime(true) * 1000);
  }
}
