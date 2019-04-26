<?php
namespace Packaged\Helpers;

use ArrayAccess;
use Exception;
use Packaged\Helpers\Traits\ArrayAccessTrait;
use function array_key_exists;
use function gettype;
use function is_array;
use function is_object;
use function is_string;
use function json_decode;
use function json_encode;

class ArrayHelper implements ArrayAccess
{
  use ArrayAccessTrait;

  /**
   * @param array $data
   */
  public function __construct(array $data)
  {
    $this->_arrayAccessData = $data;
  }

  /**
   * @param $resource
   *
   * @return ArrayHelper
   *
   * @throws Exception
   */
  public static function create($resource)
  {
    if(is_object($resource))
    {
      return new static((array)$resource);
    }

    if(is_array($resource))
    {
      return new static($resource);
    }

    if(is_string($resource))
    {
      return new static(ValueAs::arr($resource));
    }

    throw new Exception(gettype($resource) . " is not currently supported");
  }

  /**
   * Cast an object to an assoc array
   *
   * @param $object
   *
   * @return mixed
   */
  public static function toArray($object)
  {
    return json_decode(json_encode($object), true);
  }

  /**
   * @param      $key
   * @param null $default
   *
   * @return null
   */
  public function getValue($key, $default = null)
  {
    // isset() is a micro-optimization - it is fast but fails for null values.
    if(isset($this->_arrayAccessData[$key]))
    {
      return $this->_arrayAccessData[$key];
    }

    // Comparing $default is also a micro-optimization.
    if($default === null || array_key_exists($key, $this->_arrayAccessData))
    {
      return null;
    }

    return $default;
  }

  /**
   * Set the value of an item on the array
   *
   * @param $key
   * @param $value
   *
   * @return $this
   */
  public function setValue($key, $value)
  {
    $this->_arrayAccessData[$key] = $value;
    return $this;
  }

  /**
   * return the raw array
   *
   * @return array
   */
  public function getValues()
  {
    return $this->_arrayAccessData;
  }
}
