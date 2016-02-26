<?php
namespace Packaged\Helpers;

class BitWiseGmp implements BitWiseInterface
{
  /**
   * Check to see if an integer is a single bit, or a combination
   *
   * @param mixed $value Bit to check
   *
   * @return bool
   */
  public static function isSingleBit($value)
  {
    return
      (gmp_cmp($value, 1) === 0)
      || (gmp_cmp($value, 0) > 0
        && gmp_cmp(gmp_mod($value, 2), 0) === 0
        && gmp_cmp(gmp_and($value, gmp_sub($value, 1)), 0) === 0);
  }

  /**
   * @param $value
   * @param $mask
   *
   * @return string
   */
  public static function remove($value, $mask)
  {
    return gmp_strval(gmp_and($value, gmp_com($mask)));
  }

  /**
   * @param $value
   * @param $mask
   *
   * @return string
   */
  public static function add($value, $mask)
  {
    return gmp_strval(gmp_or($value, $mask));
  }

  /**
   * @param $value
   * @param $mask
   *
   * @return string
   */
  public static function toggle($value, $mask)
  {
    return gmp_strval(gmp_xor($value, $mask));
  }

  /**
   * @param $value
   * @param $mask
   *
   * @return bool
   */
  public static function has($value, $mask)
  {
    return gmp_cmp(gmp_and($value, $mask), $mask) === 0;
  }

  /**
   * @param $value
   * @param $mask
   *
   * @return bool
   */
  public static function hasAny($value, $mask)
  {
    return gmp_cmp(gmp_and($value, $mask), 0) !== 0;
  }

  /**
   * @param $value
   *
   * @return string
   */
  public static function getBits($value)
  {
    $bits = [];
    for($i = gmp_init(1); gmp_cmp($value, $i) >= 0; $i = gmp_mul($i, 2))
    {
      if(static::has($value, $i))
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
