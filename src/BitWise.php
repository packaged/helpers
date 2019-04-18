<?php
namespace Packaged\Helpers;

class BitWise implements BitWiseInterface
{
  protected static $useGmp = true;

  /**
   * Check to see if an integer is a single bit, or a combination
   *
   * @param int $value Bit to check
   *
   * @return bool
   */
  public static function isSingleBit($value)
  {
    if(static::hasGmp())
    {
      return BitWiseGmp::isSingleBit($value);
    }
    return BitWiseInt::isSingleBit($value);
  }

  /**
   * @param $value
   * @param $mask
   *
   * @return string
   */
  public static function remove($value, $mask)
  {
    if(static::hasGmp())
    {
      return BitWiseGmp::remove($value, $mask);
    }
    return BitWiseInt::remove($value, $mask);
  }

  /**
   * @param $value
   * @param $mask
   *
   * @return string
   */
  public static function add($value, $mask)
  {
    if(static::hasGmp())
    {
      return BitWiseGmp::add($value, $mask);
    }
    return BitWiseInt::add($value, $mask);
  }

  /**
   * @param $value
   * @param $mask
   *
   * @return string
   */
  public static function toggle($value, $mask)
  {
    if(static::hasGmp())
    {
      return BitWiseGmp::toggle($value, $mask);
    }
    return BitWiseInt::toggle($value, $mask);
  }

  /**
   * @param $value
   * @param $mask
   *
   * @return bool
   */
  public static function has($value, $mask)
  {
    if(static::hasGmp())
    {
      return BitWiseGmp::has($value, $mask);
    }
    return BitWiseInt::has($value, $mask);
  }

  /**
   * @param      $value
   * @param      $mask
   *
   * @return bool
   */
  public static function hasAny($value, $mask)
  {
    if(static::hasGmp())
    {
      return BitWiseGmp::hasAny($value, $mask);
    }
    return BitWiseInt::hasAny($value, $mask);
  }

  /**
   * @param $value
   *
   * @return array
   */
  public static function getBits($value)
  {
    if(static::hasGmp())
    {
      return BitWiseGmp::getBits($value);
    }
    return BitWiseInt::getBits($value);
  }

  /**
   * @param $mask
   *
   * @return string
   */
  public static function highest($mask)
  {
    if(static::hasGmp())
    {
      return BitWiseGmp::highest($mask);
    }
    return BitWiseInt::highest($mask);
  }

  public static function hasGmp()
  {
    return static::$useGmp && extension_loaded('gmp');
  }

  public static function preferGmp($value = true)
  {
    static::$useGmp = $value;
  }
}
