<?php
namespace Packaged\Helpers\Traits;

trait ArrayAccessTrait
{
  protected $_arrayAccessData;

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Whether a offset exists
   *
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
  #[\ReturnTypeWillChange]
  public function offsetExists( $offset)
  {
    return isset($this->_arrayAccessData[$offset]);
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Offset to retrieve
   *
   * @link http://php.net/manual/en/arrayaccess.offsetget.php
   *
   * @param mixed $offset <p>
   *                      The offset to retrieve.
   *                      </p>
   *
   * @return mixed Can return all value types.
   */
  #[\ReturnTypeWillChange]
  public function offsetGet($offset)
  {
    if(isset($this->_arrayAccessData[$offset]))
    {
      return $this->_arrayAccessData[$offset];
    }
    return null;
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Offset to set
   *
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
  #[\ReturnTypeWillChange]
  public function offsetSet($offset, $value)
  {
    $this->_arrayAccessData[$offset] = $value;
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Offset to unset
   *
   * @link http://php.net/manual/en/arrayaccess.offsetunset.php
   *
   * @param mixed $offset <p>
   *                      The offset to unset.
   *                      </p>
   *
   * @return void
   */
  #[\ReturnTypeWillChange]
  public function offsetUnset($offset)
  {
    unset($this->_arrayAccessData[$offset]);
  }
}
