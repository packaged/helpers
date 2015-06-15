<?php
namespace Packaged\Helpers;

class TimeHelper
{
  const SQL_DATETIME = 'Y-m-d H:i:s';
  const SQL_DATE = 'Y-m-d';
  const SQL_TIME = 'H:i:s';

  /**
   * Convert a unique ID to a microtime
   *
   * @param      $uniqid
   * @param bool $hasEntropy
   *
   * @return string
   */
  public static function uniqidToMicroseconds($uniqid, $hasEntropy = false)
  {
    if($hasEntropy)
    {
      $uniqid = substr($uniqid, 0, -10);
    }
    $microtime = (int)hexdec(substr($uniqid, -5));
    $microtime = str_pad($microtime, 6, '0', STR_PAD_LEFT);
    $timestamp = (int)hexdec(substr($uniqid, -13, -5));
    return (int)(($timestamp . '.' . $microtime) * 1000);
  }

  /**
   * Retrieve the microtime as an integer
   *
   * @return int
   */
  public static function microseconds()
  {
    return (int)floor(microtime(true) * 1000);
  }
}

