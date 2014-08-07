<?php
namespace Packaged\Helpers;

class ArrayHelper implements \ArrayAccess
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
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Whether a offset exists
   * @link http://php.net/manual/en/arrayaccess.offsetexists.php
   *
   * @param mixed $offset <p>
   *                      An offset to check for.
   *                      </p>
   *
   * @return boolean true on success or false on failure.
   * </p>
   * <p>
   * The return value will be casted to boolean if non-boolean was returned.
   */
  public function offsetExists($offset)
  {
    return isset($this->_data[$offset]);
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Offset to retrieve
   * @link http://php.net/manual/en/arrayaccess.offsetget.php
   *
   * @param mixed $offset <p>
   *                      The offset to retrieve.
   *                      </p>
   *
   * @return mixed Can return all value types.
   */
  public function offsetGet($offset)
  {
    if(isset($this->_data[$offset]))
    {
      return $this->_data[$offset];
    }
    return null;
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Offset to set
   * @link http://php.net/manual/en/arrayaccess.offsetset.php
   *
   * @param mixed $offset <p>
   *                      The offset to assign the value to.
   *                      </p>
   * @param mixed $value  <p>
   *                      The value to set.
   *                      </p>
   *
   * @return void
   */
  public function offsetSet($offset, $value)
  {
    $this->_data[$offset] = $value;
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Offset to unset
   * @link http://php.net/manual/en/arrayaccess.offsetunset.php
   *
   * @param mixed $offset <p>
   *                      The offset to unset.
   *                      </p>
   *
   * @return void
   */
  public function offsetUnset($offset)
  {
    unset($this->_data[$offset]);
  }
}
