<?php
namespace Packaged\Helpers;

class Numbers
{
  /**
   * Return if a value is between two values
   *
   * @param int  $value     Value to compare against
   * @param int  $lowest    Lowest value to compare
   * @param int  $highest   Highest value to compare
   * @param bool $inclusive If the value can be equal to highest or lowest
   *
   * @return bool
   */
  public static function between($value, $lowest, $highest, $inclusive = true)
  {
    if($inclusive)
    {
      return $value >= $lowest && $value <= $highest;
    }
    else
    {
      return $value > $lowest && $value < $highest;
    }
  }

  /**
   * Number format integers only, any other string will be returned as is
   *
   * @param mixed  $number       The number being formatted.
   * @param int    $decimals     [optional] Sets the number of decimal points.
   * @param string $decPoint     [optional]
   * @param string $thousandsSep [optional]
   * @param bool   $forceInt     [optional] force the output to be cast to an
   *                             int when a numeric value is not available
   *
   * @return string A formatted version of number.
   */
  public static function format(
    $number, $decimals = 0, $decPoint = '.', $thousandsSep = ',',
    $forceInt = false
  )
  {
    if(is_numeric($number))
    {
      return number_format($number, $decimals, $decPoint, $thousandsSep);
    }
    else
    {
      return $forceInt ? (int)$number : $number;
    }
  }

  /**
   * Number format with suffix, for making large numbers human readable
   *
   * @param float $number
   * @param bool  $digital Use digital units of measurement
   *
   * @return string A formatted version of number.
   */
  public static function humanize($number, $digital = false)
  {
    if($digital)
    {
      $suffixes = ['', 'k', 'm', 'g', 't'];
    }
    else
    {
      $suffixes = ['', 'k', 'm', 'b', 't'];
    }
    $suffixIndex = 0;

    while(abs($number) >= 1000 && $suffixIndex < sizeof($suffixes))
    {
      $suffixIndex++;
      $number /= 1000;
    }

    return (
    $number > 0
      // precision of 1 decimal places
      ? floor($number * 10) / 10
      : ceil($number * 10) / 10
    ) . $suffixes[$suffixIndex];
  }
}
