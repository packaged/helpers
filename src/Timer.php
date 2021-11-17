<?php
namespace Packaged\Helpers;

class Timer
{
  protected $_startTime;
  protected $_endTime;
  protected $_key;
  protected $_description = '';

  public function __construct($key = null)
  {
    $this->_startTime = microtime(true);
    $this->_key = $key;
  }

  /**
   * @return mixed
   */
  public function key()
  {
    return $this->_key;
  }

  /**
   * @param mixed $key
   *
   * @return \Packaged\Helpers\Timer
   */
  public function setKey(string $key)
  {
    $this->_key = $key;
    return $this;
  }

  /**
   * @return string
   */
  public function description(): string
  {
    return $this->_description;
  }

  /**
   * @param string $description
   *
   * @return \Packaged\Helpers\Timer
   */
  public function setDescription(string $description)
  {
    $this->_description = $description;
    return $this;
  }

  public function complete($allowEndUpdate = false)
  {
    if(!$allowEndUpdate && $this->_endTime !== null)
    {
      throw new \RuntimeException("The timer `$this->_key` has already been completed");
    }
    $this->_endTime = microtime(true);
    return $this;
  }

  public function duration()
  {
    return ($this->_endTime ?? microtime(true)) - $this->_startTime;
  }

  public function startTime()
  {
    return $this->_startTime;
  }

  public function endTime()
  {
    return $this->_endTime;
  }
}
