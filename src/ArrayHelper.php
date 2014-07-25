<?php
namespace Packaged\Helpers;

class ArrayHelper
{
  protected $_data;

  /**
   * @param $resource
   *
   * @return ArrayHelper
   *
   * @throws \Exception
   */
  public static function create($resource)
  {
    if(is_object($resource))
    {
      return new self((array)$resource);
    }

    if(is_array($resource))
    {
      return new self($resource);
    }

    if(is_string($resource))
    {
      return new self(ValueAs::arr($resource));
    }

    throw new \Exception(gettype($resource) . " is not currently supported");
  }

  /**
   * @param array $data
   */
  public function __construct(array $data)
  {
    $this->_data = $data;
  }

  /**
   * @param      $key
   * @param null $default
   *
   * @return null
   */
  public function getValue($key, $default = null)
  {
    return isset($this->_data[$key]) ? $this->_data[$key] : $default;
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
    $this->_data[$key] = $value;
    return $this;
  }

  /**
   * return the raw array
   *
   * @return array
   */
  public function getValues()
  {
    return $this->_data;
  }
}
