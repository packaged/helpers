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
      $properties = static::properties($properties, true);
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
}
