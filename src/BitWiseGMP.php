<?php
namespace Packaged\Helpers;

class BitWiseGmp
{
  /**
   * Check to see if an integer is a single bit, or a combination
   *
   * @param mixed $bit Bit to check
   *
   * @return bool
   */
  public static function isSingleBit($bit)
  {
    return
      (gmp_cmp($bit, 1) === 0)
      || ($bit > 0
        && gmp_cmp(gmp_mod($bit, 2), 0) === 0
        && gmp_cmp(gmp_and($bit, gmp_sub($bit, 1)), 0) === 0);
  }

  /**
   * @param $mask
   * @param $bit
   *
   * @return string
   */
  public static function remove($mask, $bit)
  {
    return gmp_strval(gmp_and($mask, gmp_com($bit)));
  }

  /**
   * @param $mask
   * @param $bit
   *
   * @return string
   */
  public static function add($mask, $bit)
  {
    return gmp_strval(gmp_or($mask, $bit));
  }

  /**
   * @param $mask
   * @param $bit
   *
   * @return string
   */
  public static function toggle($mask, $bit)
  {
    return gmp_strval(gmp_xor($mask, $bit));
  }

  /**
   * @param $mask
   * @param $bit
   *
   * @return bool
   */
  public static function has($mask, $bit)
  {
    return gmp_cmp(gmp_and($mask, $bit), $bit) === 0;
  }

  /**
   * @param $mask
   *
   * @return string
   */
  public static function getBits($mask)
  {
    $bits = [];
    for($i = gmp_init(1); gmp_cmp($mask, $i) >= 0; $i = gmp_mul($i, 2))
    {
      if(static::has($mask, $i))
      {
        $bits[] = gmp_strval($i);
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
