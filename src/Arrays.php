<?php
namespace Packaged\Helpers;

use Generator;
use InvalidArgumentException;
use function array_combine;
use function array_key_exists;
use function array_keys;
use function array_map;
use function array_merge;
use function array_pop;
use function array_shift;
use function array_slice;
use function array_values;
use function asort;
use function count;
use function end;
use function explode;
use function get_class;
use function gettype;
use function implode;
use function in_array;
use function is_array;
use function is_object;
use function is_scalar;
use function key;
use function reset;
use function shuffle;
use function strcasecmp;
use function strtolower;

class Arrays
{
  /**
   * Add an element between every two elements of some array. That is, given a
   * list `A, B, C, D`, and some element to interleave, `x`, this function
   * returns
   * `A, x, B, x, C, x, D`. This works like `implode()`, but does not
   * concatenate the list into a string. In particular:
   *
   *   implode('', array_interleave($x, $list));
   *
   * ...is equivalent to:
   *
   *   implode($x, $list);
   *
   * One case where this is useful is in rendering lists of HTML elements
   * separated by some character, like a middle dot:
   *
   *   phutil_tag(
   *     'div',
   *     array(),
   *     Arrays::interleave(" \xC2\xB7 ", $stuff));
   *
   * This function does not preserve keys.
   *
   * @param $interleave mixed  Element to interleave.
   * @param $array      array List of elements to be interleaved.
   *
   * @return array Original list with the new element interleaved.
   */
  public static function interleave($interleave, array $array)
  {
    $result = [];
    foreach($array as $item)
    {
      $result[] = $item;
      $result[] = $interleave;
    }
    array_pop($result);
    return $result;
  }

  /**
   * Simplifies a common use of `array_combine()`. Specifically, this:
   *
   *   COUNTEREXAMPLE:
   *   if ($list) {
   *     $result = array_combine($list, $list);
   *   } else {
   *     // Prior to PHP 5.4, array_combine() failed if given empty arrays.
   *     $result = array();
   *   }
   *
   * ...is equivalent to this:
   *
   *   $result = Arrays::fuse($list);
   *
   * @param   $list  array List of scalars.
   *
   * @return  array  Dictionary with inputs mapped to themselves.
   */
  public static function fuse(array $list)
  {
    return $list ? array_combine($list, $list) : [];
  }

  /**
   * Merge a vector of arrays performantly. This has the same semantics as
   * array_merge(), so these calls are equivalent:
   *
   *   array_merge($a, $b, $c);
   *   Arrays::mergev(array($a, $b, $c));
   *
   * However, when you have a vector of arrays, it is vastly more performant to
   * merge them with this function than by calling array_merge() in a loop,
   * because using a loop generates an intermediary array on each iteration.
   *
   * @param $arrayv array Vector of arrays to merge.
   *
   * @return array Arrays, merged with array_merge() semantics.
   */
  public static function mergev(array $arrayv)
  {
    if(!$arrayv)
    {
      return [];
    }

    return array_merge(...$arrayv);
  }

  /**
   * Returns the first element of an array. Exactly like reset(), but doesn't
   * choke if you pass it some non-referenceable value like the return value of
   * a function.
   *
   * @param array $arr Array to retrieve the first element from.
   *
   * @return   mixed  The first value of the array.
   */
  public static function first(array $arr)
  {
    return reset($arr);
  }

  /**
   * Returns the last element of an array. This is exactly like end() except
   * that it won't warn you if you pass some non-referencable array to
   * it -- e.g., the result of some other array operation.
   *
   * @param array $arr Array to retrieve the last element from.
   *
   * @return   mixed  The last value of the array.
   */
  public static function last(array $arr)
  {
    return end($arr);
  }

  /**
   * Returns the first key of an array.
   *
   * @param array $arr Array to retrieve the first key from.
   *
   * @return   int|string  The first key of the array.
   */
  public static function firstKey(array $arr)
  {
    reset($arr);
    return key($arr);
  }

  /**
   * Returns the last key of an array.
   *
   * @param array $arr Array to retrieve the last key from.
   *
   * @return   int|string  The last key of the array.
   */
  public static function lastKey(array $arr)
  {
    end($arr);
    return key($arr);
  }

  /**
   * Selects a list of keys from an array, returning a new array with only the
   * key-value pairs identified by the selected keys, in the specified order.
   *
   * Note that since this function orders keys in the result according to the
   * order they appear in the list of keys, there are effectively two common
   * uses: either reducing a large dictionary to a smaller one, or changing the
   * key order on an existing dictionary.
   *
   * @param  $dict array    Dictionary of key-value pairs to select from.
   * @param  $keys array List of keys to select.
   *
   * @return array    Dictionary of only those key-value pairs where the key was
   *                 present in the list of keys to select. Ordering is
   *                 determined by the list order.
   */
  public static function selectKeys(array $dict, array $keys)
  {
    $result = [];
    foreach($keys as $key)
    {
      if(array_key_exists($key, $dict))
      {
        $result[$key] = $dict[$key];
      }
    }
    return $result;
  }

  /**
   * Checks if all values of array are instances of the passed class.
   * Throws InvalidArgumentException if it isn't true for any value.
   *
   * @param  $arr   array
   * @param  $class string  Name of the class or 'array' to check arrays.
   *
   * @return array   Returns passed array.
   * @throws InvalidArgumentException
   */
  public static function instancesOf(array $arr, $class)
  {
    $isArray = !strcasecmp($class, 'array');

    foreach($arr as $key => $object)
    {
      if($isArray)
      {
        if(!is_array($object))
        {
          $given = gettype($object);
          throw new InvalidArgumentException(
            "Array item with key '{$key}' must be of type array, " .
            "{$given} given."
          );
        }
      }
      else if(!($object instanceof $class))
      {
        $given = gettype($object);
        if(is_object($object))
        {
          $given = 'instance of ' . get_class($object);
        }
        throw new InvalidArgumentException(
          "Array item with key '{$key}' must be an instance of {$class}, " .
          "{$given} given."
        );
      }
    }

    return $arr;
  }

  /**
   * Short for 'array pull'.  Extracts specified items from a list of arrays
   * and returns them in an array keyed by the original key, or alternatively the
   * value of another item on the array.
   *
   * @param array[]     $list     A list of arrays.
   * @param string[]    $keys     Array of keys to extract.
   * @param string|null $keyIndex Determines how **keys** will be
   *                              assigned in the result array. Use a string like
   *                              'id' to use the specified index as each item's
   *                              key, or ##null## to preserve the original keys.
   *
   * @return array                An array keyed by $keyProperty populated by the
   *                              properties specified in $properties.
   */
  public static function apull(array $list, array $keys, $keyIndex = null)
  {
    $result = [];
    foreach($list as $key => $data)
    {
      if($keyIndex !== null)
      {
        $key = $data[$keyIndex];
      }

      $value = [];
      foreach($keys as $index)
      {
        $value[$index] = Arrays::value($data, $index);
      }

      $result[$key] = $value;
    }
    return $result;
  }

  /**
   * Access an array index, retrieving the value stored there if it exists or
   * a default if it does not. This function allows you to concisely access an
   * index which may or may not exist without raising a warning.
   *
   * @param   $array   array  Array to access.
   * @param   $key     mixed  Index to access in the array.
   * @param   $default mixed  Default value to return if the key is not
   *                   present in the array.
   *
   * @return  mixed  If $array[$key] exists, that value is returned. If not,
   *                  $default is returned without raising a warning.
   */
  public static function value(array $array, $key, $default = null)
  {
    // isset() is a micro-optimization - it is fast but fails for null values.
    if(isset($array[$key]))
    {
      return $array[$key];
    }

    // Comparing $default is also a micro-optimization.
    if($default === null || array_key_exists($key, $array))
    {
      return null;
    }

    return $default;
  }

  /**
   * Group a list of arrays by the value of some index. This function is the
   * same as @{function:mgroup}, except it operates on the values of array
   * indexes rather than the return values of method calls.
   *
   * @param   $list       array List of arrays to group by some index value.
   * @param   ...$by      string  Name of an index to select from each array in
   *                      order to determine which group it should be placed into.
   *                      Additional groups can be provided for sub grouping
   *
   * @return  array    Dictionary mapping distinct index values to lists of
   *                  all objects which had that value at the index.
   */
  public static function igroup(array $list, ...$by)
  {
    $groupBy = array_shift($by);
    $map = static::ipull($list, $groupBy);

    $groups = [];
    foreach($map as $group)
    {
      $groups[$group] = [];
    }

    foreach($map as $key => $group)
    {
      $groups[$group][$key] = $list[$key];
    }

    if($by)
    {
      foreach($groups as $groupKey => $grouped)
      {
        $groups[$groupKey] = self::igroup($grouped, ...$by);
      }
    }

    return $groups;
  }

  /**
   * Flip an array, and group by its original values
   *
   * @param array $list
   *
   * @return array
   */
  public static function flipGroup(array $list)
  {
    $groups = [];

    foreach($list as $k => $val)
    {
      if(!isset($groups[$val]))
      {
        $groups[$val] = [];
      }
      $groups[$val][$k] = $k;
    }

    return $groups;
  }

  /**
   * Choose an index from a list of arrays. Short for "index pull", this
   * function works just like @{function:mpull}, except that it operates on a
   * list of arrays and selects an index from them instead of operating on a
   * list of objects and calling a method on them.
   *
   * This function simplifies a common type of mapping operation:
   *
   *    COUNTEREXAMPLE
   *    $names = array();
   *    foreach ($list as $key => $dict) {
   *      $names[$key] = $dict['name'];
   *    }
   *
   * With ipull():
   *
   *    $names = ipull($list, 'name');
   *
   * See @{function:mpull} for more usage examples.
   *
   * @param   $list         array Some list of arrays.
   * @param   $index        mixed|null   Determines which **values**
   *                        will appear in the result array. Use a scalar to
   *                        select that index from each array, or null to
   *                        preserve the arrays unmodified as values.
   * @param   $keyIndex     string|null   Determines which **keys** will
   *                        appear in the result array. Use a scalar to select
   *                        that index from each array, or null to preserve
   *                        the array keys.
   *
   * @return  array          A dictionary with keys and values derived
   *                         according
   *                        to whatever you passed for $index and $key_index.
   */
  public static function ipull(array $list, $index, $keyIndex = null)
  {
    $result = [];
    foreach($list as $key => $array)
    {
      if($keyIndex !== null)
      {
        $key = $array[$keyIndex];
      }
      if($index !== null)
      {
        $value = $array[$index];
      }
      else
      {
        $value = $array;
      }
      $result[$key] = $value;
    }
    return $result;
  }

  /**
   * Group a list of arrays by the value of some index by denomination.
   *
   * @param array       $list          List of objects to group by some property value.
   * @param string      $property      Name of a property to select from each object in
   *                                   order to determine which group it should be placed into.
   * @param array       $denominations Array containing group denominations ['from' => 'to']
   * @param null|string $defaultGroup  If property is not found in denomination, place it in this group
   *
   * @return array    Dictionary mapping distinct index values to lists of
   *                  all objects based on their denomination.
   */
  public static function xgroup(array $list, $property, $denominations, $defaultGroup = null)
  {
    $indices = [];
    $map = static::ipull($list, $property);
    foreach($map as $key => $group)
    {
      $target = Arrays::value($denominations, $group, $defaultGroup);
      if($target !== null)
      {
        if(!isset($indices[$target]))
        {
          $indices[$target] = [];
        }
        $indices[$target][$key] = $list[$key];
      }
    }

    return $indices;
  }

  /**
   * Sort a list of arrays by the value of some index. This method is identical
   * to
   * @{function:msort}, but operates on a list of arrays instead of a list of
   * objects.
   *
   * @param   $list   array    List of arrays to sort by some index value.
   * @param   $index  string  Index to access on each object; the return values
   *                  will be used to sort the list.
   *
   * @return  array    Arrays ordered by the index values.
   */
  public static function isort(array $list, $index)
  {
    $surrogate = static::ipull($list, $index);

    asort($surrogate);

    $result = [];
    foreach($surrogate as $key => $value)
    {
      $result[$key] = $list[$key];
    }

    return $result;
  }

  /**
   * Filter a list of arrays by removing the ones with an empty() value for
   * some
   * index. This function works just like @{function:mfilter}, except that it
   * operates on a list of arrays instead of a list of objects.
   *
   * For example, to remove all arrays without value for key 'username', do
   * this:
   *
   *   ifilter($list, 'username');
   *
   * The optional third parameter allows you to negate the operation and filter
   * out nonempty arrays. To remove all arrays that DO have value for key
   * 'username', do this:
   *
   *   ifilter($list, 'username', true);
   *
   * @param  $list        array        List of arrays to filter.
   * @param  $index       mixed       The index.
   * @param  $negate      bool         Optionally, pass true to drop arrays
   *                      which pass the filter instead of keeping them.
   *
   * @return array   List of arrays which pass the filter.
   * @throws InvalidArgumentException
   */
  public static function ifilter(array $list, $index, $negate = false)
  {
    if(!is_scalar($index))
    {
      throw new InvalidArgumentException('Argument index is not a scalar.');
    }

    $result = [];
    if(!$negate)
    {
      foreach($list as $key => $array)
      {
        if(!empty($array[$index]))
        {
          $result[$key] = $array;
        }
      }
    }
    else
    {
      foreach($list as $key => $array)
      {
        if(empty($array[$index]))
        {
          $result[$key] = $array;
        }
      }
    }

    return $result;
  }

  /**
   * return the first non-empty value of an array from
   * a specified list of keys
   *
   * @param array $array
   * @param array $properties
   * @param null  $default
   *
   * @return mixed
   */
  public static function inonempty(
    array $array, array $properties, $default = null
  )
  {
    foreach($properties as $prop)
    {
      if(isset($array[$prop]) && !empty($array[$prop]))
      {
        return $array[$prop];
      }
    }
    return $default;
  }

  /**
   * A case-insensitive in_array function
   * Checks to see if a value exists in an array
   *
   * @param mixed[] $haystack
   * @param mixed   $needle
   *
   * @return bool
   */
  public static function contains(array $haystack, $needle)
  {
    return in_array(
      strtolower($needle),
      array_map('strtolower', $haystack)
    );
  }

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
  public static function addValue(array $array, $value = true, $name = null)
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
  public static function toList(
    array $pieces = [], $glue = ', ', $finalGlue = ' & '
  )
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

  /**
   * Check to see if an array is associative
   *
   * @param array $array
   *
   * @return bool
   */
  public static function isAssoc(array $array)
  {
    return ($array !== array_values($array));
  }

  /**
   * Return a random item from an array
   *
   * @param array $items
   *
   * @return mixed
   */
  public static function randomItem(array $items)
  {
    $final = static::random($items, 1, false);
    return reset($final);
  }

  /**
   * Return a random slice from an array
   *
   * @param array $items
   * @param int   $limit
   * @param bool  $assoc
   *
   * @return array
   */
  public static function random(array $items, $limit = 1, $assoc = true)
  {
    if($assoc)
    {
      $items = static::shuffleAssoc($items);
    }
    else
    {
      shuffle($items);
    }
    return array_slice($items, 0, $limit, true);
  }

  /**
   * Shuffles an array maintaining key association
   *
   * @param array $array
   *
   * @return array
   */
  public static function shuffleAssoc($array)
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

  /**
   * Flatten a multi-dimensional associative array with separator.
   *
   * @param array  $array
   * @param string $prepend
   *
   * @param string $separator
   *
   * @return array
   */

  public static function flatten(array $array, $prepend = '', $separator = '.')
  {
    $results = [];
    foreach($array as $key => $value)
    {
      if(is_array($value) && !empty($value))
      {
        $results = array_merge($results, static::flatten($value, $prepend . $key . $separator));
      }
      else
      {
        $results[$prepend . $key] = $value;
      }
    }

    return $results;
  }

  /**
   * Expand a flattened array
   *
   * @param array  $array
   * @param string $separator
   *
   * @return array
   */
  public static function expand(array $array, $separator = '.')
  {
    $result = [];
    foreach($array as $key => $value)
    {
      static::expandValue($result, explode($separator, $key), $value);
    }
    return $result;
  }

  /**
   * @param       $array array to set a value on
   * @param array $parts array of keys to next e.g. [l1,l2,l3]
   * @param       $value
   *
   * @return mixed
   */
  public static function expandValue(&$array, array $parts, $value)
  {
    foreach($parts as $key)
    {
      $array = &$array[$key];
    }
    $array = $value;
    return $array;
  }

  /**
   * Transform an array through a callback, setting a default value on any null items
   *
   * @param          $value
   * @param callable $callback
   * @param null     $default
   *
   * @return mixed
   */
  public static function transformed(array $value, callable $callback, $default = null)
  {
    $return = [];
    foreach($value as $k => $v)
    {
      $return[$k] = ValueAs::transformed($v, $callback, $default);
    }
    return $return;
  }

  /**
   * @param iterable $iterator
   * @param callable $filter Filter the key/value of the array - be aware ARRAY_FILTER_USE_BOTH is set  fn($v, $k)
   * @param callable $apply  Transform an item of an array
   *
   * @return Generator
   */
  public static function filterTransform(iterable $iterator, callable $filter, callable $apply): Generator
  {
    foreach($iterator as $k => $v)
    {
      if($filter($v, $k))
      {
        yield $k => $apply($v);
      }
    }
  }

  /**
   * Returns the first array value which is not strictly null, or ##null## if there
   * are no such values.
   *
   * @param array $source Input array
   * @param array $keys   One or more arguments for the key names.
   *
   * @return mixed       First non-##null## value, or null if no values are set.
   */
  public static function coalesce(array $source, ...$keys)
  {
    foreach($keys as $key)
    {
      if(isset($source[$key]) && $source[$key] !== null)
      {
        return $source[$key];
      }
    }
    return null;
  }

  /**
   * Returns the first array value which is not empty, or ##null## if there
   * are no such values.
   *
   * @param array $source Input array
   * @param array $keys   One or more arguments for the key names.
   *
   * @return mixed       First non-##null## value, or null if no values are set.
   */
  public static function nonempty(array $source, ...$keys)
  {
    foreach($keys as $key)
    {
      if(isset($source[$key]) && $source[$key])
      {
        return $source[$key];
      }
    }
    return null;
  }

  /**
   * Build a nested tree of Branch objects containing the original array.
   * Uses indexes names to retrieve the object ID and parent ID.
   *
   * @param array  $source      Original objects to organise into a tree
   * @param string $idKey       Array key used as the ID of this object
   * @param string $parentIdKey Array key used as the parent ID of this object
   *
   * @return Branch
   */
  public static function iTree(array $source, string $idKey, string $parentIdKey)
  {
    return Branch::trunk()->iHydrate($source, $idKey, $parentIdKey);
  }
}
