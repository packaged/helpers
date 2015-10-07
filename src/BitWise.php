<?php
namespace Packaged\Helpers;

class BitWise
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
    if($bit == 1)
    {
      return true;
    }
    return $bit > 0 && bcmod($bit, 2) == 0 && ($bit & ($bit - 1)) == 0;
  }

  public static function remove($mask, $bit)
  {
    return ($mask & $bit) ? ($mask ^ $bit) : $mask;
  }

  public static function add($mask, $bit)
  {
    return $mask | $bit;
  }

  public static function toggle($mask, $bit)
  {
    if($mask & $bit)
    {
      return $mask ^ $bit;
    }
    else
    {
      return $mask | $bit;
    }
  }

  public static function has($mask, $bit)
  {
    return ($mask & $bit) === $bit;
  }

  public static function getBits($mask)
  {
    $bits = [];
    for($i = 1; $i <= $mask; $i = $i * 2)
    {
      if($i & $mask)
      {
        $bits[] = $i;
      }
    }

    return $bits;
  }

  public static function highest($mask)
  {
    $bits = static::getBits($mask);
    return end($bits);
  }
}
