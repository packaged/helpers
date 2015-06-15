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
}
