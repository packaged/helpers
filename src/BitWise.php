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
    if(extension_loaded('gmp'))
    {
      return BitWiseGmp::isSingleBit($bit);
    }
    else
    {
      return BitWiseInt::isSingleBit($bit);
    }
  }

  /**
   * @param $mask
   * @param $bit
   *
   * @return string
   */
  public static function remove($mask, $bit)
  {
    if(extension_loaded('gmp'))
    {
      return BitWiseGmp::remove($mask, $bit);
    }
    else
    {
      return BitWiseInt::remove($mask, $bit);
    }
  }

  /**
   * @param $mask
   * @param $bit
   *
   * @return string
   */
  public static function add($mask, $bit)
  {
    if(extension_loaded('gmp'))
    {
      return BitWiseGmp::add($mask, $bit);
    }
    else
    {
      return BitWiseInt::add($mask, $bit);
    }
  }

  /**
   * @param $mask
   * @param $bit
   *
   * @return string
   */
  public static function toggle($mask, $bit)
  {
    if(extension_loaded('gmp'))
    {
      return BitWiseGmp::toggle($mask, $bit);
    }
    else
    {
      return BitWiseInt::toggle($mask, $bit);
    }
  }

  /**
   * @param $mask
   * @param $bit
   *
   * @return bool
   */
  public static function has($mask, $bit)
  {
    if(extension_loaded('gmp'))
    {
      return BitWiseGmp::has($mask, $bit);
    }
    else
    {
      return BitWiseInt::has($mask, $bit);
    }
  }

  /**
   * @param $mask
   *
   * @return string
   */
  public static function getBits($mask)
  {
    if(extension_loaded('gmp'))
    {
      return BitWiseGmp::getBits($mask);
    }
    else
    {
      return BitWiseInt::getBits($mask);
    }
  }

  /**
   * @param $mask
   *
   * @return string
   */
  public static function highest($mask)
  {
    if(extension_loaded('gmp'))
    {
      return BitWiseGmp::highest($mask);
    }
    else
    {
      return BitWiseInt::highest($mask);
    }
  }
}
