<?php
namespace Packaged\Helpers;

class BitWiseInt
{
  /**
   * Check to see if an integer is a single bit, or a combination
   *
   * @param int $bit Bit to check
   *
   * @return bool
   */
  public static function isSingleBit($bit)
  {
    $bit = (int)$bit;
    return
      ($bit === 1)
      || ($bit > 0 && (($bit % 2) == 0) && (($bit & ($bit - 1)) == 0));
  }

  /**
   * @param $mask
   * @param $bit
   *
   * @return string
   */
  public static function remove($mask, $bit)
  {
    return (int)$mask & (~(int)$bit);
  }

  /**
   * @param $mask
   * @param $bit
   *
   * @return string
   */
  public static function add($mask, $bit)
  {
    return (int)$mask | (int)$bit;
  }

  /**
   * @param $mask
   * @param $bit
   *
   * @return string
   */
  public static function toggle($mask, $bit)
  {
    return (int)$mask ^ (int)$bit;
  }

  /**
   * @param $mask
   * @param $bit
   *
   * @return bool
   */
  public static function has($mask, $bit)
  {
    return ((int)$mask & (int)$bit) === (int)$bit;
  }

  /**
   * @param $mask
   *
   * @return string
   */
  public static function getBits($mask)
  {
    $bits = [];
    for($i = 1; $i <= $mask; $i *= 2)
    {
      if(static::has($mask, $i))
      {
        $bits[] = $i;
      }
    }
    return $bits;
  }

  /**
   * @param $mask
   *
   * @return string
   */
  public static function highest($mask)
  {
    $bits = static::getBits($mask);
    return end($bits);
  }
}
