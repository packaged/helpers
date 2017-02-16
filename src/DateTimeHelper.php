<?php
namespace Packaged\Helpers;

class DateTimeHelper
{
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
}
