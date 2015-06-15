<?php
/**
 * The methods provided here are sourced from
 * https://raw.github.com/facebook/libphutil/master/src/utils/utils.php
 */

if(!function_exists('idx'))
{
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
   * @group      util
   *
   * @deprecated
   */
  function idx(array $array, $key, $default = null)
  {
    return \Packaged\Helpers\Arrays::value($array, $key, $default);
  }
}

if(!function_exists('mpull'))
{
  /**
   * Call a method on a list of objects. Short for "method pull", this function
   * works just like @{function:ipull}, except that it operates on a list of
   * objects instead of a list of arrays. This function simplifies a common type
   * of mapping operation:
   *
   *    COUNTEREXAMPLE
   *    $names = array();
   *    foreach ($objects as $key => $object) {
   *      $names[$key] = $object->getName();
   *    }
   *
   * You can express this more concisely with mpull():
   *
   *    $names = mpull($objects, 'getName');
   *
   * mpull() takes a third argument, which allows you to do the same but for
   * the array's keys:
   *
   *    COUNTEREXAMPLE
   *    $names = array();
   *    foreach ($objects as $object) {
   *      $names[$object->getID()] = $object->getName();
   *    }
   *
   * This is the mpull version():
   *
   *    $names = mpull($objects, 'getName', 'getID');
   *
   * If you pass ##null## as the second argument, the objects will be preserved:
   *
   *    COUNTEREXAMPLE
   *    $id_map = array();
   *    foreach ($objects as $object) {
   *      $id_map[$object->getID()] = $object;
   *    }
   *
   * With mpull():
   *
   *    $id_map = mpull($objects, null, 'getID');
   *
   * See also @{function:ipull}, which works similarly but accesses array indexes
   * instead of calling methods.
   *
   * @param   $list         array          Some list of objects.
   * @param   $method       string|null   Determines which **values**
   *                        will appear in the result array. Use a string like
   *                        'getName' to store the value of calling the named
   *                        method in each value, or
   *                        ##null## to preserve the original objects.
   * @param   $keyMethod    string|null   Determines how **keys** will be
   *                        assigned in the result array. Use a string like
   *                        'getID' to use the result of calling the named method
   *                        as each object's key, or ##null## to preserve the
   *                        original keys.
   *
   * @return  array          A dictionary with keys and values derived according
   *                        to whatever you passed as $method and $key_method.
   * @group   util
   *
   * @deprecated
   */
  function mpull(array $list, $method, $keyMethod = null)
  {
    return \Packaged\Helpers\Objects::mpull($list, $method, $keyMethod);
  }
}

if(!function_exists('ppull'))
{
  /**
   * Access a property on a list of objects. Short for "property pull", this
   * function works just like @{function:mpull}, except that it accesses object
   * properties instead of methods. This function simplifies a common type of
   * mapping operation:
   *
   *    COUNTEREXAMPLE
   *    $names = array();
   *    foreach ($objects as $key => $object) {
   *      $names[$key] = $object->name;
   *    }
   *
   * You can express this more concisely with ppull():
   *
   *    $names = ppull($objects, 'name');
   *
   * ppull() takes a third argument, which allows you to do the same but for
   * the array's keys:
   *
   *    COUNTEREXAMPLE
   *    $names = array();
   *    foreach ($objects as $object) {
   *      $names[$object->id] = $object->name;
   *    }
   *
   * This is the ppull version():
   *
   *    $names = ppull($objects, 'name', 'id');
   *
   * If you pass ##null## as the second argument, the objects will be preserved:
   *
   *    COUNTEREXAMPLE
   *    $id_map = array();
   *    foreach ($objects as $object) {
   *      $id_map[$object->id] = $object;
   *    }
   *
   * With ppull():
   *
   *    $id_map = ppull($objects, null, 'id');
   *
   * See also @{function:mpull}, which works similarly but calls object methods
   * instead of accessing object properties.
   *
   * @param   $list         array Some list of objects.
   * @param   $property     string|null   Determines which **values** will appear
   *                        in the result
   *                        array. Use a string like 'name' to store the value of
   *                        accessing the named property in each value, or
   *                        ##null## to preserve the original objects.
   * @param   $keyProperty  string|null   Determines how **keys** will be assigned
   *                        in the result
   *                        array. Use a string like 'id' to use the result of
   *                        accessing the named property as each object's key, or
   *                        ##null## to preserve the original keys.
   *
   * @return  array          A dictionary with keys and values derived according
   *                        to whatever you passed as $property and $key_property.
   * @group   util
   *
   * @deprecated
   */
  function ppull(array $list, $property, $keyProperty = null)
  {
    return \Packaged\Helpers\Objects::ppull($list, $property, $keyProperty);
  }
}

if(!function_exists('ipull'))
{
  /**
   * Choose an index from a list of arrays. Short for "index pull", this function
   * works just like @{function:mpull}, except that it operates on a list of
   * arrays and selects an index from them instead of operating on a list of
   * objects and calling a method on them.
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
   * @return  array          A dictionary with keys and values derived according
   *                        to whatever you passed for $index and $key_index.
   * @group   util
   *
   * @deprecated
   */
  function ipull(array $list, $index, $keyIndex = null)
  {
    return \Packaged\Helpers\Arrays::ipull($list, $index, $keyIndex);
  }
}

if(!function_exists('mgroup'))
{
  /**
   * Group a list of objects by the result of some method, similar to how
   * GROUP BY works in an SQL query. This function simplifies grouping objects
   * by some property:
   *
   *    COUNTEREXAMPLE
   *    $animals_by_species = array();
   *    foreach ($animals as $animal) {
   *      $animals_by_species[$animal->getSpecies()][] = $animal;
   *    }
   *
   * This can be expressed more tersely with mgroup():
   *
   *    $animals_by_species = mgroup($animals, 'getSpecies');
   *
   * In either case, the result is a dictionary which maps species (e.g., like
   * "dog") to lists of animals with that property, so all the dogs are grouped
   * together and all the cats are grouped together, or whatever super
   * businessesey thing is actually happening in your problem domain.
   *
   * See also @{function:igroup}, which works the same way but operates on
   * array indexes.
   *
   * @param   $list   array    List of objects to group by some property.
   * @param   $by     string  Name of a method, like 'getType', to call on
   *                  each object in order to determine which group it should be
   *                  placed into.
   * @param   ...     Zero or more additional method names, to subgroup the
   *                  groups.
   *
   * @return  array    Dictionary mapping distinct method returns to lists of
   *                  all objects which returned that value.
   * @group   util
   *
   * @deprecated
   */
  function mgroup(array $list, $by /* , ... */)
  {
    return call_user_func_array(
      '\Packaged\Helpers\Objects::mgroup',
      func_get_args()
    );
  }
}

if(!function_exists('igroup'))
{
  /**
   * Group a list of arrays by the value of some index. This function is the same
   * as @{function:mgroup}, except it operates on the values of array indexes
   * rather than the return values of method calls.
   *
   * @param   $list    array List of arrays to group by some index value.
   * @param   $by      string  Name of an index to select from each array in
   *                   order to determine which group it should be placed into.
   * @param   ...     Zero or more additional indexes names, to subgroup the
   *                   groups.
   *
   * @return  array    Dictionary mapping distinct index values to lists of
   *                  all objects which had that value at the index.
   * @group   util
   *
   * @deprecated
   */
  function igroup(array $list, $by /* , ... */)
  {
    return call_user_func_array(
      '\Packaged\Helpers\Arrays::igroup',
      func_get_args()
    );
  }
}

if(!function_exists('pgroup'))
{
  /**
   * Group a list of arrays by the value of some property. This function is the
   * same as @{function:mgroup}, except it operates on the values of object
   * properties rather than the return values of method calls.
   *
   * @param   $list    array List of objects to group by some property value.
   * @param   $by      string  Name of a property to select from each object in
   *                   order to determine which group it should be placed into.
   * @param   ...     Zero or more additional property names, to subgroup the
   *                   groups.
   *
   * @return  array    Dictionary mapping distinct index values to lists of
   *                  all objects which had that value at the index.
   * @group   util
   *
   * @deprecated
   */
  function pgroup(array $list, $by /* , ... */)
  {
    return call_user_func_array(
      '\Packaged\Helpers\Objects::pgroup',
      func_get_args()
    );
  }
}

if(!function_exists('msort'))
{
  /**
   * Sort a list of objects by the return value of some method. In PHP, this is
   * often vastly more efficient than ##usort()## and similar.
   *
   *    // Sort a list of Duck objects by name.
   *    $sorted = msort($ducks, 'getName');
   *
   * It is usually significantly more efficient to define an ordering method
   * on objects and call ##msort()## than to write a comparator. It is often more
   * convenient, as well.
   *
   * NOTE: This method does not take the list by reference; it returns a new list.
   *
   * @param   $list   array    List of objects to sort by some property.
   * @param   $method string  Name of a method to call on each object; the return
   *                  values will be used to sort the list.
   *
   * @return  array    Objects ordered by the return values of the method calls.
   * @group   util
   *
   * @deprecated
   */
  function msort(array $list, $method)
  {
    return \Packaged\Helpers\Objects::msort($list, $method);
  }
}

if(!function_exists('isort'))
{
  /**
   * Sort a list of arrays by the value of some index. This method is identical to
   * @{function:msort}, but operates on a list of arrays instead of a list of
   * objects.
   *
   * @param   $list   array    List of arrays to sort by some index value.
   * @param   $index  string  Index to access on each object; the return values
   *                  will be used to sort the list.
   *
   * @return  array    Arrays ordered by the index values.
   * @group   util
   *
   * @deprecated
   */
  function isort(array $list, $index)
  {
    return \Packaged\Helpers\Arrays::isort($list, $index);
  }
}

if(!function_exists('mfilter'))
{
  /**
   * Filter a list of objects by executing a method across all the objects and
   * filter out the ones wth empty() results. this function works just like
   * @{function:ifilter}, except that it operates on a list of objects instead
   * of a list of arrays.
   *
   * For example, to remove all objects with no children from a list, where
   * 'hasChildren' is a method name, do this:
   *
   *   mfilter($list, 'hasChildren');
   *
   * The optional third parameter allows you to negate the operation and filter
   * out nonempty objects. To remove all objects that DO have children, do this:
   *
   *   mfilter($list, 'hasChildren', true);
   *
   * @param  $list        array        List of objects to filter.
   * @param  $method      string       A method name.
   * @param  $negate      bool         Optionally, pass true to drop objects
   *                      which pass the filter instead of keeping them.
   *
   * @return array   List of objects which pass the filter.
   * @throws InvalidArgumentException
   * @group  util
   *
   * @deprecated
   */
  function mfilter(array $list, $method, $negate = false)
  {
    return \Packaged\Helpers\Objects::mfilter($list, $method, $negate);
  }
}

if(!function_exists('ifilter'))
{
  /**
   * Filter a list of arrays by removing the ones with an empty() value for some
   * index. This function works just like @{function:mfilter}, except that it
   * operates on a list of arrays instead of a list of objects.
   *
   * For example, to remove all arrays without value for key 'username', do this:
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
   * @group  util
   *
   * @deprecated
   */
  function ifilter(array $list, $index, $negate = false)
  {
    return \Packaged\Helpers\Arrays::ifilter($list, $index, $negate);
  }
}

if(!function_exists('array_select_keys'))
{
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
   * @group   util
   *
   * @deprecated
   */
  function array_select_keys(array $dict, array $keys)
  {
    return \Packaged\Helpers\Arrays::selectKeys($dict, $keys);
  }
}

if(!function_exists('assert_instances_of'))
{
  /**
   * Checks if all values of array are instances of the passed class.
   * Throws InvalidArgumentException if it isn't true for any value.
   *
   * @param  $arr   array
   * @param  $class string  Name of the class or 'array' to check arrays.
   *
   * @return array   Returns passed array.
   * @group   util
   * @throws InvalidArgumentException
   *
   * @deprecated
   */
  function assert_instances_of(array $arr, $class)
  {
    return \Packaged\Helpers\Arrays::instancesOf($arr, $class);
  }
}

if(!function_exists('coalesce'))
{
  /**
   * Returns the first argument which is not strictly null, or ##null## if there
   * are no such arguments. Identical to the MySQL function of the same name.
   *
   * @param  ...         Zero or more arguments of any type.
   *
   * @return mixed       First non-##null## arg, or null if no such arg exists.
   * @group  util
   *
   * @deprecated
   */
  function coalesce( /* ... */)
  {
    return call_user_func_array(
      '\Packaged\Helpers\ValueAs::coalesce',
      func_get_args()
    );
  }
}

if(!function_exists('nonempty'))
{
  /**
   * Similar to @{function:coalesce}, but less strict: returns the first
   * non-##empty()## argument, instead of the first argument that is strictly
   * non-##null##. If no argument is nonempty, it returns the last argument. This
   * is useful idiomatically for setting defaults:
   *
   *   $display_name = nonempty($user_name, $full_name, "Anonymous");
   *
   * @param  ...         Zero or more arguments of any type.
   *
   * @return mixed       First non-##empty()## arg, or last arg if no such arg
   *                     exists, or null if you passed in zero args.
   * @group  util
   *
   * @deprecated
   */
  function nonempty( /* ... */)
  {
    return call_user_func_array(
      '\Packaged\Helpers\ValueAs::nonempty',
      func_get_args()
    );
  }
}

if(!function_exists('newv'))
{
  /**
   * Invokes the "new" operator with a vector of arguments. There is no way to
   * call_user_func_array() on a class constructor, so you can instead use this
   * function:
   *
   *   $obj = newv($class_name, $argv);
   *
   * That is, these two statements are equivalent:
   *
   *   $pancake = new Pancake('Blueberry', 'Maple Syrup', true);
   *   $pancake = newv('Pancake', array('Blueberry', 'Maple Syrup', true));
   *
   * DO NOT solve this problem in other, more creative ways! Three popular
   * alternatives are:
   *
   *   - Build a fake serialized object and unserialize it.
   *   - Invoke the constructor twice.
   *   - just use eval() lol
   *
   * These are really bad solutions to the problem because they can have side
   * effects (e.g., __wakeup()) and give you an object in an otherwise impossible
   * state. Please endeavor to keep your objects in possible states.
   *
   * If you own the classes you're doing this for, you should consider whether
   * or not restructuring your code (for instance, by creating static
   * construction methods) might make it cleaner before using newv(). Static
   * constructors can be invoked with call_user_func_array(), and may give your
   * class a cleaner and more descriptive API.
   *
   * @param  $className string  The name of a class.
   * @param  $argv      array Array of arguments to pass to its constructor.
   *
   * @return object     A new object of the specified class, constructed by
   *                  passing the argument vector to its constructor.
   * @group util
   *
   * @deprecated
   */
  function newv($className, array $argv)
  {
    return \Packaged\Helpers\Objects::create($className, $argv);
  }
}

if(!function_exists('head'))
{
  /**
   * Returns the first element of an array. Exactly like reset(), but doesn't
   * choke if you pass it some non-referenceable value like the return value of
   * a function.
   *
   * @param    array Array to retrieve the first element from.
   *
   * @return   mixed  The first value of the array.
   * @group util
   *
   * @deprecated
   */
  function head(array $arr)
  {
    return \Packaged\Helpers\Arrays::first($arr);
  }
}

if(!function_exists('last'))
{
  /**
   * Returns the last element of an array. This is exactly like end() except
   * that it won't warn you if you pass some non-referencable array to
   * it -- e.g., the result of some other array operation.
   *
   * @param    array Array to retrieve the last element from.
   *
   * @return   mixed  The last value of the array.
   * @group util
   *
   * @deprecated
   */
  function last(array $arr)
  {
    return \Packaged\Helpers\Arrays::last($arr);
  }
}

if(!function_exists('head_key'))
{
  /**
   * Returns the first key of an array.
   *
   * @param    array       Array to retrieve the first key from.
   *
   * @return   int|string  The first key of the array.
   * @group util
   *
   * @deprecated
   */
  function head_key(array $arr)
  {
    return \Packaged\Helpers\Arrays::firstKey($arr);
  }
}

if(!function_exists('last_key'))
{
  /**
   * Returns the last key of an array.
   *
   * @param    array       Array to retrieve the last key from.
   *
   * @return   int|string  The last key of the array.
   * @group util
   *
   * @deprecated
   */
  function last_key(array $arr)
  {
    return \Packaged\Helpers\Arrays::lastKey($arr);
  }
}

if(!function_exists('array_mergev'))
{
  /**
   * Merge a vector of arrays performantly. This has the same semantics as
   * array_merge(), so these calls are equivalent:
   *
   *   array_merge($a, $b, $c);
   *   array_mergev(array($a, $b, $c));
   *
   * However, when you have a vector of arrays, it is vastly more performant to
   * merge them with this function than by calling array_merge() in a loop,
   * because using a loop generates an intermediary array on each iteration.
   *
   * @param $arrayv array Vector of arrays to merge.
   *
   * @return array Arrays, merged with array_merge() semantics.
   * @group util
   *
   * @deprecated
   */
  function array_mergev(array $arrayv)
  {
    return \Packaged\Helpers\Arrays::mergev($arrayv);
  }
}

if(!function_exists('phutil_split_lines'))
{
  /**
   * Split a corpus of text into lines. This function splits on "\n", "\r\n", or
   * a mixture of any of them.
   *
   * NOTE: This function does not treat "\r" on its own as a newline because none
   * of SVN, Git or Mercurial do on any OS.
   *
   * @param $corpus        string Block of text to be split into lines.
   * @param $retainEndings bool If true, retain line endings in result strings.
   *
   * @return array List of lines.
   * @group util
   *
   * @deprecated
   */
  function phutil_split_lines($corpus, $retainEndings = true)
  {
    return \Packaged\Helpers\Strings::splitLines($corpus, $retainEndings);
  }
}

if(!function_exists('array_fuse'))
{
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
   *   $result = array_fuse($list);
   *
   * @param   $list  array List of scalars.
   *
   * @return  array  Dictionary with inputs mapped to themselves.
   * @group util
   *
   * @deprecated
   */
  function array_fuse(array $list)
  {
    return \Packaged\Helpers\Arrays::fuse($list);
  }
}

if(!function_exists('array_interleave'))
{
  /**
   * Add an element between every two elements of some array. That is, given a
   * list `A, B, C, D`, and some element to interleave, `x`, this function returns
   * `A, x, B, x, C, x, D`. This works like `implode()`, but does not concatenate
   * the list into a string. In particular:
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
   *     array_interleave(" \xC2\xB7 ", $stuff));
   *
   * This function does not preserve keys.
   *
   * @param $interleave mixed  Element to interleave.
   * @param $array      array List of elements to be interleaved.
   *
   * @return array Original list with the new element interleaved.
   * @group util
   *
   * @deprecated
   */
  function array_interleave($interleave, array $array)
  {
    return \Packaged\Helpers\Arrays::interleave($interleave, $array);
  }
}

/**
 * Assert that passed data can be converted to string.
 *
 * @param  string $parameter Assert that this data is valid.
 *
 * @return void
 *
 * @throws InvalidArgumentException
 *
 * @deprecated
 */
if(!function_exists('assert_stringlike'))
{
  function assert_stringlike($parameter)
  {
    \Packaged\Helpers\Strings::stringable($parameter);
  }
}
