<?php
/**
 * Generic Helpers non namespaced
 *
 * @author  brooke.bryan
 */

/**
 * Short Directory separator tag
 */
defined("DS") or define("DS", DIRECTORY_SEPARATOR);

if(!function_exists("var_dump_json"))
{
  /**
   * Output an object as json in a pretty format
   *
   * @param mixed     $object object to json_encode
   * @param bool|null $echo   null will echo if running from cli
   *
   * @return string Pretty Printed JSON
   */
  function var_dump_json($object, $echo = null)
  {
    if($echo === true || (php_sapi_name() === 'cli' && $echo === null))
    {
      echo json_encode($object, JSON_PRETTY_PRINT);
    }
    else
    {
      var_dump(json_encode($object, JSON_PRETTY_PRINT));
    }
  }
}

if(!function_exists("json_pretty"))
{
  /**
   * Short cut for json_encode with JSON_PRETTY_PRINT
   *
   * @param $object
   *
   * @return string json encoded string
   */
  function json_pretty($object)
  {
    return json_encode($object, JSON_PRETTY_PRINT);
  }
}

if(!function_exists("class_shortname"))
{
  /**
   * Return a class name without the namespace prefix
   *
   * @param $class
   *
   * @return string Short class name
   */
  function class_shortname($class)
  {
    $class = is_object($class) ? get_class($class) : $class;
    return basename(str_replace('\\', '/', $class));
  }
}

if(!function_exists("esc"))
{
  /**
   * Escape HTML String
   *
   * @param $string
   *
   * @return string
   */
  function esc($string)
  {
    return \htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
  }
}

if(!function_exists("psort"))
{
  /**
   * Returns an array of objects ordered by the property param
   *
   * @param array $list
   * @param       $property
   *
   * @return array objects ordered by the property param
   */
  function psort(array $list, $property)
  {
    $surrogate = ppull($list, $property);

    asort($surrogate);

    $result = [];
    foreach($surrogate as $key => $value)
    {
      $result[$key] = $list[$key];
    }

    return $result;
  }
}

if(!function_exists("is_assoc"))
{
  /**
   * Check to see if an array is associative
   *
   * @param array $array
   *
   * @return bool
   */
  function is_assoc(array $array)
  {
    return ($array !== array_values($array));
  }
}

if(!function_exists('shuffle_assoc'))
{
  /**
   * Shuffles an array maintaining key association
   *
   * @param array $array
   *
   * @return array
   */
  function shuffle_assoc($array)
  {
    if(!is_array($array))
    {
      return $array;
    }
    $keys = array_keys($array);
    shuffle($keys);
    $return = [];
    foreach($keys as $key)
    {
      $return[$key] = $array[$key];
    }
    return $return;
  }
}

if(!function_exists("starts_with"))
{
  /**
   * Check a string starts with a specific string
   *
   * @param      $haystack
   * @param      $needle
   * @param bool $case
   *
   * @return bool
   */
  function starts_with($haystack, $needle, $case = true)
  {
    if(is_array($needle))
    {
      return starts_with_any($haystack, $needle, $case);
    }

    if(!$case)
    {
      return strncasecmp($haystack, $needle, strlen($needle)) == 0;
    }
    else
    {
      return strncmp($haystack, $needle, strlen($needle)) == 0;
    }
  }
}

if(!function_exists("starts_with_any"))
{
  /**
   * Check a string starts with one of the needles provided
   *
   * @param       $haystack
   * @param array $needles
   * @param bool  $case
   *
   * @return bool
   */
  function starts_with_any($haystack, array $needles, $case = true)
  {
    foreach($needles as $needle)
    {
      if(starts_with($haystack, $needle, $case))
      {
        return true;
      }
    }
    return false;
  }
}

if(!function_exists("ends_with"))
{
  /**
   * Check a string ends with a specific string
   *
   * @param      $haystack
   * @param      $needle
   * @param bool $case
   *
   * @return bool
   */
  function ends_with($haystack, $needle, $case = true)
  {
    if(is_array($needle))
    {
      return ends_with_any($haystack, $needle, $case);
    }
    return starts_with(strrev($haystack), strrev($needle), $case);
  }
}

if(!function_exists("ends_with_any"))
{
  /**
   * Check a string ends with one of the provided needles
   *
   * @param       $haystack
   * @param array $needles
   * @param bool  $case
   *
   * @return bool
   */
  function ends_with_any($haystack, array $needles, $case = true)
  {
    foreach($needles as $needle)
    {
      if(ends_with($haystack, $needle, $case))
      {
        return true;
      }
    }
    return false;
  }
}

if(!function_exists("contains_any"))
{
  /**
   * Check a string contains one of the provided needles
   *
   * @param       $haystack
   * @param array $needles
   * @param bool  $case
   *
   * @return bool
   */
  function contains_any($haystack, array $needles, $case = true)
  {
    foreach($needles as $needle)
    {
      if(str_contains($haystack, $needle, $case))
      {
        return true;
      }
    }
    return false;
  }
}

if(!function_exists("str_contains"))
{
  /**
   * Check a string contains another string
   *
   * @param       $haystack
   * @param array $needle
   * @param bool  $case
   *
   * @return bool
   */
  function str_contains($haystack, $needle, $case = true)
  {
    if($case)
    {
      return strstr($haystack, $needle) !== false;
    }
    else
    {
      return stristr($haystack, $needle) !== false;
    }
  }
}

if(!function_exists("strip_start"))
{
  /**
   * Strip off a specific string from the start of another, if an exact match
   * is not found, the original string (haystack) will be returned
   *
   * @param $haystack
   * @param $needle
   *
   * @return string
   */
  function strip_start($haystack, $needle)
  {
    if(starts_with($haystack, $needle))
    {
      $haystack = substr($haystack, strlen($needle));
    }
    return $haystack;
  }
}

if(!function_exists("string_from"))
{
  /**
   * Retrieve the final part of a string, after the first instance of the
   * needle has been located
   *
   * @param $haystack
   * @param $needle
   *
   * @return string
   */
  function string_from($haystack, $needle)
  {
    if(stristr($haystack, $needle))
    {
      $haystack = substr(
        $haystack,
        strpos($haystack, $needle) + strlen($needle)
      );
    }
    return $haystack;
  }
}

if(!function_exists('implode_list'))
{
  /**
   * Similar to the standard implode method, but allowing the last item to be
   * stuck with a separate glue e.g. apple , pear & grape
   *
   * @param array  $pieces
   * @param string $glue
   * @param string $finalGlue
   *
   * @return string
   */
  function implode_list(array $pieces = [], $glue = ' , ', $finalGlue = ' & ')
  {
    if(count($pieces) > 1)
    {
      $final = array_pop($pieces);
      return implode($finalGlue, [implode($glue, $pieces), $final]);
    }
    else
    {
      return implode($glue, $pieces);
    }
  }
}

if(!function_exists("msleep"))
{
  /**
   * Sleep for X milliseconds
   *
   * @param $milliseconds
   */
  function msleep($milliseconds)
  {
    usleep($milliseconds * 1000);
  }
}

if(!function_exists("get_namespace"))
{
  /**
   * This will return the namespace of the passed object/class
   *
   * @param object|string $source
   *
   * @return string
   */
  function get_namespace($source)
  {
    if($source === null)
    {
      return '';
    }
    $source = is_object($source) ? get_class($source) : $source;
    $source = explode('\\', $source);
    array_pop($source);
    if(count($source) < 1)
    {
      return '';
    }
    else
    {
      return '\\' . ltrim(implode('\\', $source), '\\');
    }
  }
}

if(!function_exists('build_path'))
{
  /**
   * Concatenate any number of path sections and correctly
   * handle directory separators
   *
   * @return string
   */
  function build_path( /* string... */)
  {
    return build_path_custom(DIRECTORY_SEPARATOR, func_get_args());
  }
}

if(!function_exists('build_path_win'))
{
  /**
   * Concatenate a path with windows style path separators
   *
   * @return string
   */
  function build_path_win( /* string... */)
  {
    return build_path_custom('\\', func_get_args());
  }
}

if(!function_exists('build_path_unix'))
{
  /**
   * Concatenate a path with unix style path separators
   *
   * @return string
   */
  function build_path_unix( /* string... */)
  {
    return build_path_custom('/', func_get_args());
  }
}

if(!function_exists('build_path_custom'))
{
  /**
   * Concatenate a path with a custom separator
   *
   * @param string   $directorySeparator
   * @param string[] $pathComponents
   *
   * @return string
   */
  function build_path_custom($directorySeparator, array $pathComponents)
  {
    $fullPath = "";
    foreach($pathComponents as $section)
    {
      if(!empty($section))
      {
        if($fullPath == "")
        {
          $fullPath = $section;
        }
        else
        {
          $fullPath = rtrim($fullPath, '/\\' . $directorySeparator) .
            $directorySeparator . ltrim($section, '/\\' . $directorySeparator);
        }
      }
    }

    return $fullPath;
  }
}

if(!function_exists('concat'))
{
  /**
   * Concatenate array items
   *
   * @return string
   */
  function concat( /* string... */)
  {
    return implode('', func_get_args());
  }
}

if(!function_exists("array_add_value"))
{
  /**
   * Add a new value to an array, by name or pushed onto the end
   *
   * When null is provided as the name, it will simply add your item onto the
   * end of the array
   *
   * @param array $array
   * @param bool  $value
   * @param null  $name
   *
   * @return array
   */
  function array_add_value(array $array, $value = true, $name = null)
  {
    if($name === null)
    {
      $array[] = $value;
    }
    else
    {
      $array[$name] = $value;
    }
    return $array;
  }
}

if(!function_exists("idp"))
{
  /**
   * Access an object property, retrieving the value stored there
   * if it exists or a default if it does not.
   *
   * @param object $object   Source object
   * @param string $property Property name to pull from the object
   * @param mixed  $default  Default value if the property does not exist
   *
   * @return mixed
   */
  function idp($object, $property, $default = null)
  {
    return isset($object->$property) ? $object->$property : $default;
  }
}

if(!function_exists("get_public_properties"))
{
  /**
   * Return an array with only the public properties
   *
   * If calling get_object_vars withing a class,
   * will return protected and private properties,
   * this function fixes this instance
   *
   * @param object $object Source object
   *
   * @return mixed
   */
  function get_public_properties($object)
  {
    return get_object_vars($object);
  }
}

if(!function_exists("exploded"))
{
  /**
   * Explode a string, filling the remainder with provided defaults.
   *
   * @param string      $delimiter The boundary string
   * @param string      $string    The input string.
   * @param array|mixed $defaults  Array to return, with replacements made,
   *                               or a padding value
   * @param int|null    $limit     Passed through to the initial explode
   *
   * @return array
   *
   */
  function exploded($delimiter, $string, $defaults = null, $limit = null)
  {
    if($limit === null)
    {
      $parts = explode($delimiter, $string);
    }
    else
    {
      $parts = explode($delimiter, $string, $limit);
    }

    if(is_array($defaults))
    {
      return array_replace($defaults, $parts);
    }

    return array_pad($parts, $limit, $defaults);
  }
}

if(!function_exists("between"))
{
  /**
   * Return if a value is between two values
   *
   * @param int  $value     Value to compare against
   * @param int  $lowest    Lowest value to compare
   * @param int  $highest   Highest value to compare
   * @param bool $inclusive If the value can be equal to highest or lowest
   *
   * @return bool
   */
  function between($value, $lowest, $highest, $inclusive = true)
  {
    if($inclusive)
    {
      return $value >= $lowest && $value <= $highest;
    }
    else
    {
      return $value > $lowest && $value < $highest;
    }
  }
}

if(!function_exists("nformat"))
{
  /**
   * Number format integers only, any other string will be returned as is
   *
   * @param mixed  $number       The number being formatted.
   * @param int    $decimals     [optional] Sets the number of decimal points.
   * @param string $decPoint     [optional]
   * @param string $thousandsSep [optional]
   *
   * @return string A formatted version of number.
   */
  function nformat(
    $number, $decimals = 0, $decPoint = '.', $thousandsSep = ','
  )
  {
    if(is_numeric($number))
    {
      return number_format($number, $decimals, $decPoint, $thousandsSep);
    }
    else
    {
      return $number;
    }
  }
}

if(!function_exists('glob_recursive'))
{
  /**
   * Match all files within a directory to a pattern recursive
   *
   * @param     $baseDir
   * @param     $pattern
   * @param int $flags
   *
   * @return array
   */
  function glob_recursive($baseDir, $pattern = '*', $flags = 0)
  {
    $files = glob($baseDir . DS . $pattern, $flags);

    foreach(glob($baseDir . DS . '*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir)
    {
      $files = array_merge($files, glob_recursive($dir, $pattern, $flags));
    }
    return $files;
  }
}

if(!function_exists('in_arrayi'))
{
  /**
   * A case-insensitive in_array function
   * Checks to see if a value exists in an array
   *
   * @param mixed   $needle
   * @param mixed[] $haystack
   *
   * @return bool
   */
  function in_arrayi($needle, $haystack)
  {
    return in_array(
      strtolower($needle),
      array_map('strtolower', $haystack)
    );
  }
}

if(!function_exists('hydrate'))
{
  /**
   * Hydrate properties from the source object, into the destination
   *
   * @param object $destination object to write data to
   * @param object $source      object to read data from
   * @param array  $properties  properties to read
   * @param bool   $copyNull    Copy null values from source to destination
   *
   * @return void
   *
   * @throws Exception
   */
  function hydrate($destination, $source, array $properties, $copyNull = true)
  {
    if(!is_object($destination) || !is_object($source))
    {
      throw new Exception("hydrate() must be given objects");
    }

    foreach($properties as $property)
    {
      $newVal = idp($source, $property);
      if($newVal !== null || $copyNull)
      {
        $destination->$property = $newVal;
      }
    }
  }
}

if(!function_exists('is_single_bit'))
{
  /**
   * Check to see if an integer is a single bit, or a combination
   *
   * @param int $bit Bit to check
   *
   * @return bool
   */
  function is_single_bit($bit)
  {
    if($bit == 1)
    {
      return true;
    }
    return $bit > 0 && bcmod($bit, 2) == 0 && ($bit & ($bit - 1)) == 0;
  }
}
