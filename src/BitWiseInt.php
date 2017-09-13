<?php
namespace Packaged\Helpers;

class BitWiseInt implements BitWiseInterface
{
  /**
   * Check to see if an integer is a single bit, or a combination
   *
   * @param int $value Bit to check
   *
   * @return bool
   */
  public static function isSingleBit($value)
  {
    $value = (int)$value;
    return
      ($value === 1)
      || ($value > 0 && (($value % 2) == 0) && (($value & ($value - 1)) == 0));
  }

  /**
   * @param $value
   * @param $mask
   *
   * @return string
   */
  public static function remove($value, $mask)
  {
    return (int)$value & (~(int)$mask);
  }

  /**
   * @param $value
   * @param $mask
   *
   * @return string
   */
  public static function add($value, $mask)
  {
    return (int)$value | (int)$mask;
  }

  /**
   * @param $value
   * @param $mask
   *
   * @return string
   */
  public static function toggle($value, $mask)
  {
    return (int)$value ^ (int)$mask;
  }

  /**
   * @param $value
   * @param $mask
   *
   * @return bool
   */
  public static function has($value, $mask)
  {
    return ((int)$value & (int)$mask) === (int)$mask;
  }

  /**
   * @param $value
   * @param $mask
   *
   * @return bool
   */
  public static function hasAny($value, $mask)
  {
    return ((int)$value & (int)$mask) !== 0;
  }

  /**
   * @param $value
   *
   * @return array
   */
  public static function getBits($value)
  {
    $bits = [];
    for($i = 1; $i <= $value; $i *= 2)
    {
      if(static::has($value, $i))
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
