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
        foreach(explode('&', $value) as $set)
        {
          list($key, $val) = explode('=', $set, 2);
          $array[$key] = $val;
        }
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

  /**
   * Returns the first argument which is not strictly null, or ##null## if there
   * are no such arguments. Identical to the MySQL function of the same name.
   *
   * @param  ...         Zero or more arguments of any type.
   *
   * @return mixed       First non-##null## arg, or null if no such arg exists.
   */
  public static function coalesce( /* ... */)
  {
    $args = func_get_args();
    foreach($args as $arg)
    {
      if($arg !== null)
      {
        return $arg;
      }
    }
    return null;
  }

  /**
   * Similar to @{function:coalesce}, but less strict: returns the first
   * non-##empty()## argument, instead of the first argument that is strictly
   * non-##null##. If no argument is nonempty, it returns the last argument.
   * This is useful idiomatically for setting defaults:
   *
   *   $display_name = nonempty($user_name, $full_name, "Anonymous");
   *
   * @param  ...         Zero or more arguments of any type.
   *
   * @return mixed       First non-##empty()## arg, or last arg if no such arg
   *                     exists, or null if you passed in zero args.
   */
  public static function nonempty( /* ... */)
  {
    $args = func_get_args();
    $result = null;
    foreach($args as $arg)
    {
      $result = $arg;
      if($arg)
      {
        break;
      }
    }
    return $result;
  }
}
