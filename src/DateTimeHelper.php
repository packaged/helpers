<?php
namespace Packaged\Helpers;

use function array_slice;
use function count;
use function date;
use function explode;
use function implode;
use function preg_match;
use function strlen;
use function strtotime;

class DateTimeHelper
{
  const SQL_DATETIME = 'Y-m-d H:i:s';
  const SQL_DATE     = 'Y-m-d';
  const SQL_TIME     = 'H:i:s';

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

  /**
   * @param string $inputDates e.g. "2017-01-02-2017-01-05,2017-03-01-04-01"
   *
   * @return array array of dates
   */
  public static function dateRange($inputDates)
  {
    $finalDates = [];
    $dates = Strings::stringToRange($inputDates);
    foreach($dates as $date)
    {
      if(strlen($date) > 10)
      {
        $dp = explode('-', $date);
        $firstDate = implode('-', array_slice($dp, 0, 3));
        $secondDate = null;
        $sdp = array_slice($dp, 3);
        if(implode("", $sdp) !== "")
        {
          switch(count($sdp))
          {
            case 1:
              $secondDate = implode('-', [$dp[0], $dp[1], $sdp[0]]);
              break;
            case 2:
              $secondDate = implode('-', [$dp[0], $sdp[0], $sdp[1]]);
              break;
            case 3:
              $secondDate = implode('-', [$sdp[0], $sdp[1], $sdp[2]]);
              break;
          }
        }
        if($secondDate == null)
        {
          $finalDates[] = $firstDate;
        }
        else
        {
          $firstTime = strtotime($firstDate);
          $secondTime = strtotime($secondDate);

          if($firstTime > $secondTime)
          {
            $tmp = $secondTime;
            $secondTime = $firstTime;
            $firstTime = $tmp;
          }

          if($firstTime > 0 && $secondTime > 0)
          {
            $currentTime = $firstTime;
            while($currentTime <= $secondTime)
            {
              $finalDates[] = date("Y-m-d", $currentTime);
              $currentTime += 86400;
            }
          }
        }
      }
      else
      {
        $finalDates[] = $date;
      }
    }
    return $finalDates;
  }

  public static function stringToTimeRange($inputString)
  {
    $finalString = $current = '';
    for($char = 0; $char < strlen($inputString); $char++)
    {
      $cchar = $inputString[$char];
      switch($cchar)
      {
        case ',':
          $finalString .= self::_parseTime($current) . ",";
          $current = '';
          break;
        case '-':
          if(strlen($current) == 0
            || preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/", $current)
            || preg_match("/[0-9]{4}-[0-9]{2}/", $current)
            || preg_match("/[0-9]{4}/", $current)
          )
          {
            $current .= $cchar;
            break;
          }
          else
          {
            $finalString .= self::_parseTime($current) . "-";
            $current = '';
            break;
          }
        default:
          $current .= $cchar;
      }
    }
    $finalString .= self::_parseTime($current);

    return $finalString;
  }

  protected static function _parseTime($input)
  {
    return strtotime($input) > 0 ? date("Y-m-d", strtotime($input)) : $input;
  }
}
