<?php
/**
 * Generic Helpers non namespaced
 *
 * @author  brooke.bryan
 */

if(!function_exists("json_pretty"))
{
  /**
   * Short cut for json_encode with JSON_PRETTY_PRINT
   *
   * @param $object
   *
   * @return string json encoded string
   *
   * @deprecated
   */
  function json_pretty($object)
  {
    return \Packaged\Helpers\Strings::jsonPretty($object);
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
   *
   * @deprecated
   */
  function class_shortname($class)
  {
    return \Packaged\Helpers\Objects::classShortname($class);
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
   *
   * @deprecated
   */
  function esc($string)
  {
    return \Packaged\Helpers\Strings::escape($string);
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
   *
   * @deprecated
   */
  function psort(array $list, $property)
  {
    return \Packaged\Helpers\Objects::psort($list, $property);
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
   *
   * @deprecated
   */
  function is_assoc(array $array)
  {
    return \Packaged\Helpers\Arrays::isAssoc($array);
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
   *
   * @deprecated
   */
  function shuffle_assoc($array)
  {
    return \Packaged\Helpers\Arrays::shuffleAssoc($array);
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
   *
   * @deprecated
   */
  function starts_with($haystack, $needle, $case = true)
  {
    return \Packaged\Helpers\Strings::startsWith($haystack, $needle, $case);
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
   *
   * @deprecated
   */
  function starts_with_any($haystack, array $needles, $case = true)
  {
    return \Packaged\Helpers\Strings::startsWithAny($haystack, $needles, $case);
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
   *
   * @deprecated
   */
  function ends_with($haystack, $needle, $case = true)
  {
    return \Packaged\Helpers\Strings::endsWith($haystack, $needle, $case);
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
   *
   * @deprecated
   */
  function ends_with_any($haystack, array $needles, $case = true)
  {
    return \Packaged\Helpers\Strings::endsWithAny($haystack, $needles, $case);
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
   *
   * @deprecated
   */
  function contains_any($haystack, array $needles, $case = true)
  {
    return \Packaged\Helpers\Strings::containsAny($haystack, $needles, $case);
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
   *
   * @deprecated
   */
  function str_contains($haystack, $needle, $case = true)
  {
    return \Packaged\Helpers\Strings::contains($haystack, $needle, $case);
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
   *
   * @deprecated
   */
  function strip_start($haystack, $needle)
  {
    return \Packaged\Helpers\Strings::ltrim($haystack, $needle);
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
   *
   * @deprecated
   */
  function string_from($haystack, $needle)
  {
    return \Packaged\Helpers\Strings::offset($haystack, $needle);
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
   *
   * @deprecated
   */
  function implode_list(array $pieces = [], $glue = ' , ', $finalGlue = ' & ')
  {
    return \Packaged\Helpers\Arrays::toList($pieces, $glue, $finalGlue);
  }
}

if(!function_exists("msleep"))
{
  /**
   * Sleep for X milliseconds
   *
   * @param $milliseconds
   *
   * @deprecated
   */
  function msleep($milliseconds)
  {
    \Packaged\Helpers\System::msleep($milliseconds);
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
   *
   * @deprecated
   */
  function get_namespace($source)
  {
    return \Packaged\Helpers\Objects::getNamespace($source);
  }
}

if(!function_exists('build_path'))
{
  /**
   * Concatenate any number of path sections and correctly
   * handle directory separators
   *
   * @return string
   *
   * @deprecated
   */
  function build_path( /* string... */)
  {
    return call_user_func_array(
      '\Packaged\Helpers\Path::build',
      func_get_args()
    );
  }
}

if(!function_exists('build_path_win'))
{
  /**
   * Concatenate a path with windows style path separators
   *
   * @return string
   *
   * @deprecated
   */
  function build_path_win( /* string... */)
  {
    return call_user_func_array(
      '\Packaged\Helpers\Path::buildWindows',
      func_get_args()
    );
  }
}

if(!function_exists('build_path_unix'))
{
  /**
   * Concatenate a path with unix style path separators
   *
   * @return string
   *
   * @deprecated
   */
  function build_path_unix( /* string... */)
  {
    return call_user_func_array(
      '\Packaged\Helpers\Path::buildUnix',
      func_get_args()
    );
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
   *
   * @deprecated
   */
  function build_path_custom($directorySeparator, array $pathComponents)
  {
    return \Packaged\Helpers\Path::buildCustom(
      $directorySeparator,
      $pathComponents
    );
  }
}

if(!function_exists('concat'))
{
  /**
   * Concatenate array items
   *
   * @return string
   *
   * @deprecated
   */
  function concat( /* string... */)
  {
    return call_user_func_array(
      '\Packaged\Helpers\Strings::concat',
      func_get_args()
    );
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
   *
   * @deprecated
   */
  function array_add_value(array $array, $value = true, $name = null)
  {
    return \Packaged\Helpers\Arrays::addValue($array, $value, $name);
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
   *
   * @deprecated
   */
  function idp($object, $property, $default = null)
  {
    return \Packaged\Helpers\Objects::property($object, $property, $default);
  }
}

if(!function_exists("get_public_properties"))
{
  /**
   * Return an array with only the public properties and their values.
   *
   * If calling get_object_vars withing a class,
   * will return protected and private properties,
   * this function fixes this instance
   *
   * @param object $object     Source object
   * @param bool   $returnKeys Return Property keys
   *
   * @return mixed
   *
   * @deprecated
   */
  function get_public_properties($object, $returnKeys = false)
  {
    return $returnKeys
      ? \Packaged\Helpers\Objects::properties($object)
      : \Packaged\Helpers\Objects::propertyValues($object);
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
   * @deprecated
   */
  function exploded($delimiter, $string, $defaults = null, $limit = null)
  {
    return \Packaged\Helpers\Strings::explode(
      $delimiter,
      $string,
      $defaults,
      $limit
    );
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
   *
   * @deprecated
   */
  function between($value, $lowest, $highest, $inclusive = true)
  {
    return \Packaged\Helpers\Numbers::between(
      $value,
      $lowest,
      $highest,
      $inclusive
    );
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
   * @param bool   $forceInt     [optional] force the output to be cast to an int when a numeric value is not available
   *
   * @return string A formatted version of number.
   *
   * @deprecated
   */
  function nformat(
    $number, $decimals = 0, $decPoint = '.', $thousandsSep = ',',
    $forceInt = false
  )
  {
    return \Packaged\Helpers\Numbers::format(
      $number,
      $decimals,
      $decPoint,
      $thousandsSep,
      $forceInt
    );
  }
}

if(!function_exists("nhumanize"))
{
  /**
   * Number format with suffix, for making large numbers human readable
   *
   * @param float $number
   * @param bool  $digital Use digital units of measurement
   *
   * @return string A formatted version of number.
   *
   * @deprecated
   */
  function nhumanize($number, $digital = false)
  {
    return \Packaged\Helpers\Numbers::humanize($number, $digital);
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
   *
   * @deprecated
   */
  function glob_recursive($baseDir, $pattern = '*', $flags = 0)
  {
    return \Packaged\Helpers\Path::globRecursive($baseDir, $pattern, $flags);
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
   *
   * @deprecated
   */
  function in_arrayi($needle, $haystack)
  {
    return \Packaged\Helpers\Arrays::contains($haystack, $needle);
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
   *
   * @deprecated
   */
  function hydrate($destination, $source, array $properties, $copyNull = true)
  {
    \Packaged\Helpers\Objects::hydrate(
      $destination,
      $source,
      $properties,
      $copyNull
    );
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
   *
   * @deprecated
   */
  function is_single_bit($bit)
  {
    return \Packaged\Helpers\BitWise::isSingleBit($bit);
  }
}

if(!function_exists('pnonempty'))
{
  /**
   * return the first non-empty property of an object from
   * a specified list of properties
   *
   * @param object $object
   * @param array  $properties
   * @param null   $default
   *
   * @return mixed
   *
   * @deprecated
   */
  function pnonempty($object, array $properties, $default = null)
  {
    return \Packaged\Helpers\Objects::pnonempty($object, $properties, $default);
  }
}

if(!function_exists('inonempty'))
{
  /**
   * return the first non-empty value of an array from
   * a specified list of keys
   *
   * @param array $array
   * @param array $properties
   * @param null  $default
   *
   * @return mixed
   *
   * @deprecated
   */
  function inonempty(array $array, array $properties, $default = null)
  {
    return \Packaged\Helpers\Arrays::inonempty($array, $properties, $default);
  }
}
