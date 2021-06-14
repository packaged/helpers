<?php
namespace Packaged\Helpers;

use Exception;
use function explode;
use function filter_var;
use function is_array;
use function is_callable;
use function is_object;
use function is_scalar;
use function is_string;
use function str_replace;
use const FILTER_VALIDATE_BOOLEAN;

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
    if($value !== null && is_string($value))
    {
      $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
    return $value === null ? $default : (bool)$value;
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
    return str_replace(
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
      if(strpos($value, '=') !== false)
      {
        $array = [];
        foreach(explode('&', $value) as $pair)
        {
          [$key, $val] = array_pad(explode('=', $pair, 2), 2, '');
          $array[$key] = $val;
        }
        return $array;
      }

      if(strpos($value, ',') !== false)
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
   * Similar to @{function:coalesce}, but less strict: returns the first
   * non-##empty()## argument, instead of the first argument that is strictly
   * non-##null##. If no argument is nonempty, it returns the last argument.
   * This is useful idiomatically for setting defaults:
   *
   *   $display_name = nonempty($user_name, $full_name, "Anonymous");
   *
   * @param  ...$args         mixed Zero or more arguments of any type.
   *
   * @return mixed       First non-##empty()## arg, or last arg if no such arg
   *                     exists, or null if you passed in zero args.
   */
  public static function nonempty(...$args)
  {
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

  /**
   * Throw an exception if the value $if is true
   *
   * @param       $if
   * @param       $exception
   * @param mixed ...$parameters
   *
   * @return mixed
   * @throws Exception
   */
  public static function exceptionIf($if, $exception, ...$parameters)
  {
    if($if)
    {
      throw (is_string($exception) ? new $exception(...$parameters) : $exception);
    }
    return $if;
  }

  /**
   * Transform a value through a callback, returning a default is null is returned
   *
   * @param          $value
   * @param callable $callback
   * @param null     $default
   *
   * @return mixed
   */
  public static function transformed($value, callable $callback, $default = null)
  {
    return $value ? self::coalesce($callback($value), $default) : $default;
  }

  /**
   * Returns the first argument which is not strictly null, or ##null## if there
   * are no such arguments. Identical to the MySQL function of the same name.
   *
   * @param  ...$args         mixed Zero or more arguments of any type.
   *
   * @return mixed       First non-##null## arg, or null if no such arg exists.
   */
  public static function coalesce(...$args)
  {
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
   * Call a callable, catching any exception and returning default
   *
   * @param callable $callable
   * @param mixed    $default
   *
   * @return mixed
   */
  public static function caught(callable $callable, $default = null)
  {
    try
    {
      return $callable();
    }
    catch(Exception $e)
    {
      return is_callable($default) ? $default($e) : $default;
    }
  }
}
