<?php
namespace Packaged\Helpers;

interface BitWiseInterface
{
  /**
   * Check to see if an integer is a single bit, or a combination
   *
   * @param int $value Bit to check
   *
   * @return bool
   */
  public static function isSingleBit($value);

  /**
   * @param $value
   * @param $mask
   *
   * @return string
   */
  public static function remove($value, $mask);

  /**
   * @param $value
   * @param $mask
   *
   * @return string
   */
  public static function add($value, $mask);

  /**
   * @param $value
   * @param $mask
   *
   * @return string
   */
  public static function toggle($value, $mask);

  /**
   * @param      $value
   * @param      $mask
   *
   * @return bool
   */
  public static function has($value, $mask);

  /**
   * @param      $value
   * @param      $mask
   *
   * @return bool
   */
  public static function hasAny($value, $mask);

  /**
   * @param $value
   *
   * @return string
   */
  public static function getBits($value);

  /**
   * @param $mask
   *
   * @return string
   */
  public static function highest($mask);
}
