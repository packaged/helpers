<?php
namespace Packaged\Helpers;

/**
 * Class ValueAs
 * Retrieve your value back in a format you know you can deal with
 */
class ValueAs
{
  /**
   * Convert a value to bool
   *
   * @param mixed $value
   * @param bool  $default
   *
   * @return bool
   */
  public static function bool($value, $default = false)
  {
    if($value === null)
    {
      return $default;
    }

    if(strcasecmp($value, 'true') === 0)
    {
      return true;
    }

    if(strcasecmp($value, 'false') === 0)
    {
      return false;
    }

    return (bool)$value;
  }

  /**
   * Convert a value to an integer
   *
   * @param mixed $value
   * @param int   $default
   *
   * @return int
   */
  public static function int($value, $default = 0)
  {
    if($value === null)
    {
      return $default;
    }

    return (int)$value;
  }

  /**
   * Convert a value to a float
   *
   * @param mixed $value
   * @param float $default
   *
   * @return float
   */
  public static function float($value, $default = 0.0)
  {
    if($value === null)
    {
      return $default;
    }

    return (float)$value;
  }

  /**
   * Convert a value to a string
   *
   * @param mixed  $value
   * @param string $default
   *
   * @return string
   */
  public static function string($value, $default = "")
  {
    if($value === null)
    {
      return $default;
    }

    //Handle Bool Values
    if($value === true)
    {
      return 'true';
    }
    else if($value === false)
    {
      return 'false';
    }

    return (string)$value;
  }

  /**
   * Convert a value to a normalised string
   *
   * Normalises new line characters
   *
   * @param mixed  $value
   * @param string $default
   *
   * @return mixed|string
   */
  public static function normalisedString($value, $default = "")
  {
    if($value === null)
    {
      return $default;
    }

    // Normalize newlines.
    return \str_replace(
      ["\r\n", "\r"],
      "\n",
      $value
    );
  }

  /**
   * Converts a value to an array
   *
   * Comma separated strings will be exploded
   *
   * @param mixed $value
   * @param array $default
   *
   * @return array
   */
  public static function arr($value, $default = [])
  {
    if($value === null)
    {
      return $default;
    }

    if(is_array($value))
    {
      return $value;
    }

    if(empty($value))
    {
      return $default;
    }

    if(is_object($value))
    {
      return (array)$value;
    }

    if(is_string($value))
    {
      if(stristr($value, '=') && stristr($value, '&'))
      {
        $array = [];
        parse_str($value, $array);
        return $array;
      }
      if(stristr($value, ','))
      {
        return explode(',', $value);
      }
    }

    if(is_scalar($value))
    {
      return [$value];
    }

    return $default;
  }

  /**
   * Convert a value to an object
   *
   * @param mixed $value
   * @param null  $default
   *
   * @return null|object
   */
  public static function obj($value, $default = null)
  {
    if($value === null)
    {
      return $default;
    }

    if(is_object($value))
    {
      return $value;
    }

    if(is_array($value))
    {
      return (object)$value;
    }

    return $default;
  }
}
