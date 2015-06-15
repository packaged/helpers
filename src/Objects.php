<?php
namespace Packaged\Helpers;

class Objects
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
   * effects (e.g., __wakeup()) and give you an object in an otherwise
   * impossible state. Please endeavor to keep your objects in possible states.
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
   */
  public static function create($className, array $argv)
  {
    $reflector = new \ReflectionClass($className);
    if($argv)
    {
      return $reflector->newInstanceArgs($argv);
    }
    else
    {
      return $reflector->newInstance();
    }
  }

  /**
   * return the first non-empty property of an object from
   * a specified list of properties
   *
   * @param object $object
   * @param array  $properties
   * @param null   $default
   *
   * @return mixed
   */
  public static function pnonempty($object, array $properties, $default = null)
  {
    foreach($properties as $prop)
    {
      if(isset($object->$prop) && !empty($object->$prop))
      {
        return $object->$prop;
      }
    }
    return $default;
  }

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
   * @throws \Exception
   */
  public static function hydrate(
    $destination, $source, array $properties = null, $copyNull = true
  )
  {
    if(!is_object($destination) || !is_object($source))
    {
      throw new \Exception("hydrate() must be given objects");
    }

    if($properties === null)
    {
      $properties = static::properties($source);
    }

    $properties = array_filter($properties);
    foreach($properties as $property)
    {
      $newVal = static::property($source, $property);
      if($newVal !== null || $copyNull)
      {
        $destination->$property = $newVal;
      }
    }
  }

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
  public static function properties($object)
  {
    return array_keys(get_object_vars($object));
  }

  /**
   * Return an array with only the public properties and their values.
   *
   * If calling get_object_vars withing a class,
   * will return protected and private properties,
   * this function fixes this instance
   *
   * @param object $object Source object
   *
   * @return mixed
   */
  public static function propertyValues($object)
  {
    return get_object_vars($object);
  }

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
  public static function property($object, $property, $default = null)
  {
    return isset($object->$property) ? $object->$property : $default;
  }

  /**
   * This will return the namespace of the passed object/class
   *
   * @param object|string $source
   *
   * @return string
   */
  public static function getNamespace($source)
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

  /**
   * Return a class name without the namespace prefix
   *
   * @param $class
   *
   * @return string Short class name
   */
  public static function classShortname($class)
  {
    $class = is_object($class) ? get_class($class) : $class;
    return basename(str_replace('\\', '/', $class));
  }

  /**
   * Call a method on a list of objects. Short for "method pull", this function
   * works just like @{function:ipull}, except that it operates on a list of
   * objects instead of a list of arrays. This function simplifies a common
   * type
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
   * If you pass ##null## as the second argument, the objects will be
   * preserved:
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
   * See also @{function:ipull}, which works similarly but accesses array
   * indexes instead of calling methods.
   *
   * @param   $list         array          Some list of objects.
   * @param   $method       string|null   Determines which **values**
   *                        will appear in the result array. Use a string like
   *                        'getName' to store the value of calling the named
   *                        method in each value, or
   *                        ##null## to preserve the original objects.
   * @param   $keyMethod    string|null   Determines how **keys** will be
   *                        assigned in the result array. Use a string like
   *                        'getID' to use the result of calling the named
   *                        method as each object's key, or ##null## to
   *                        preserve the original keys.
   *
   * @return  array          A dictionary with keys and values derived
   *                         according
   *                        to whatever you passed as $method and $key_method.
   */
  public static function mpull(array $list, $method, $keyMethod = null)
  {
    $result = [];
    foreach($list as $key => $object)
    {
      if($keyMethod !== null)
      {
        $key = $object->$keyMethod();
      }
      if($method !== null)
      {
        $value = $object->$method();
      }
      else
      {
        $value = $object;
      }
      $result[$key] = $value;
    }
    return $result;
  }

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
   * If you pass ##null## as the second argument, the objects will be
   * preserved:
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
   * @param   $property     string|null   Determines which **values** will
   *                        appear in the result array. Use a string like
   *                        'name' to store the value of accessing the named
   *                        property in each value, or
   *                        ##null## to preserve the original objects.
   * @param   $keyProperty  string|null   Determines how **keys** will be
   *                        assigned in the result array. Use a string like
   *                        'id' to use the result of accessing the named
   *                        property as each object's key, or
   *                        ##null## to preserve the original keys.
   *
   * @return  array          A dictionary with keys and values derived
   *                         according
   *                        to whatever you passed as $property and
   *                        $key_property.
   */
  public static function ppull(array $list, $property, $keyProperty = null)
  {
    $result = [];
    foreach($list as $key => $object)
    {
      if($keyProperty !== null)
      {
        $key = $object->$keyProperty;
      }
      if($property !== null)
      {
        $value = $object->$property;
      }
      else
      {
        $value = $object;
      }
      $result[$key] = $value;
    }
    return $result;
  }

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
   */
  public static function mgroup(array $list, $by /* , ... */)
  {
    $map = static::mpull($list, $by);

    $groups = [];
    foreach($map as $group)
    {
      // Can't array_fill_keys() here because 'false' gets encoded wrong.
      $groups[$group] = [];
    }

    foreach($map as $key => $group)
    {
      $groups[$group][$key] = $list[$key];
    }

    $args = func_get_args();
    $args = array_slice($args, 2);
    if($args)
    {
      array_unshift($args, null);
      foreach($groups as $groupKey => $grouped)
      {
        $args[0] = $grouped;
        $groups[$groupKey] = call_user_func_array(
          '\Packaged\Helpers\Objects::mgroup',
          $args
        );
      }
    }

    return $groups;
  }

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
   */
  public static function pgroup(array $list, $by /* , ... */)
  {
    $map = static::ppull($list, $by);

    $groups = [];
    foreach($map as $group)
    {
      $groups[$group] = [];
    }

    foreach($map as $key => $group)
    {
      $groups[$group][$key] = $list[$key];
    }

    $args = func_get_args();
    $args = array_slice($args, 2);
    if($args)
    {
      array_unshift($args, null);
      foreach($groups as $groupKey => $grouped)
      {
        $args[0] = $grouped;
        $groups[$groupKey] = call_user_func_array(
          '\Packaged\Helpers\Objects::pgroup',
          $args
        );
      }
    }

    return $groups;
  }

  /**
   * Sort a list of objects by the return value of some method. In PHP, this is
   * often vastly more efficient than ##usort()## and similar.
   *
   *    // Sort a list of Duck objects by name.
   *    $sorted = msort($ducks, 'getName');
   *
   * It is usually significantly more efficient to define an ordering method
   * on objects and call ##msort()## than to write a comparator. It is often
   * more convenient, as well.
   *
   * NOTE: This method does not take the list by reference; it returns a new
   * list.
   *
   * @param   $list   array    List of objects to sort by some property.
   * @param   $method string  Name of a method to call on each object; the
   *                  return values will be used to sort the list.
   *
   * @return  array    Objects ordered by the return values of the method
   *                   calls.
   */
  public static function msort(array $list, $method)
  {
    $surrogate = static::mpull($list, $method);

    asort($surrogate);

    $result = [];
    foreach($surrogate as $key => $value)
    {
      $result[$key] = $list[$key];
    }

    return $result;
  }

  /**
   * Returns an array of objects ordered by the property param
   *
   * @param array $list
   * @param       $property
   *
   * @return array objects ordered by the property param
   */
  public static function psort(array $list, $property)
  {
    $surrogate = static::ppull($list, $property);

    asort($surrogate);

    $result = [];
    foreach($surrogate as $key => $value)
    {
      $result[$key] = $list[$key];
    }

    return $result;
  }

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
   * @throws \InvalidArgumentException
   */

  public static function mfilter(array $list, $method, $negate = false)
  {
    if(!is_string($method))
    {
      throw new \InvalidArgumentException('Argument method is not a string.');
    }

    $result = [];
    foreach($list as $key => $object)
    {
      $value = $object->$method();

      if((!$negate && !empty($value)) || ($negate && empty($value)))
      {
        $result[$key] = $object;
      }
    }

    return $result;
  }
}
