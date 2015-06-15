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
}
