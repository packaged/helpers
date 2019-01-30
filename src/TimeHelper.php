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
  public static function uniqidToMilliseconds($uniqid, $hasEntropy = false)
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
   * Get the current time in milliseconds since the UNIX epoch
   *
   * @return int
   */
  public static function milliseconds()
  {
    return (int)floor(microtime(true) * 1000);
  }

  /**
   * Convert a millisecond (or finer) timestamp to a UNIX timestamp
   * This crude method will work until 2037
   *
   * @param int $time
   *
   * @return int
   */
  public static function toSeconds($time)
  {
    while($time > 2147483647) // 2^31 - 1
    {
      $time = floor($time / 1000);
    }
    return (int)$time;
  }
}
