<?php
namespace Packaged\Helpers;

class BitWise implements BitWiseInterface
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
    if(extension_loaded('gmp'))
    {
      return BitWiseGmp::isSingleBit($value);
    }
    else
    {
      return BitWiseInt::isSingleBit($value);
    }
  }

  /**
   * @param $value
   * @param $mask
   *
   * @return string
   */
  public static function remove($value, $mask)
  {
    if(extension_loaded('gmp'))
    {
      return BitWiseGmp::remove($value, $mask);
    }
    else
    {
      return BitWiseInt::remove($value, $mask);
    }
  }

  /**
   * @param $value
   * @param $mask
   *
   * @return string
   */
  public static function add($value, $mask)
  {
    if(extension_loaded('gmp'))
    {
      return BitWiseGmp::add($value, $mask);
    }
    else
    {
      return BitWiseInt::add($value, $mask);
    }
  }

  /**
   * @param $value
   * @param $mask
   *
   * @return string
   */
  public static function toggle($value, $mask)
  {
    if(extension_loaded('gmp'))
    {
      return BitWiseGmp::toggle($value, $mask);
    }
    else
    {
      return BitWiseInt::toggle($value, $mask);
    }
  }

  /**
   * @param $value
   * @param $mask
   *
   * @return bool
   */
  public static function has($value, $mask)
  {
    if(extension_loaded('gmp'))
    {
      return BitWiseGmp::has($value, $mask);
    }
    else
    {
      return BitWiseInt::has($value, $mask);
    }
  }

  /**
   * @param      $value
   * @param      $mask
   *
   * @return bool
   */
  public static function hasAny($value, $mask)
  {
    if(extension_loaded('gmp'))
    {
      return BitWiseGmp::hasAny($value, $mask);
    }
    else
    {
      return BitWiseInt::hasAny($value, $mask);
    }
  }

  /**
   * @param $value
   *
   * @return string
   */
  public static function getBits($value)
  {
    if(extension_loaded('gmp'))
    {
      return BitWiseGmp::getBits($value);
    }
    else
    {
      return BitWiseInt::getBits($value);
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
