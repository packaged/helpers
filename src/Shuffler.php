<?php
namespace Packaged\Helpers;

class Shuffler
{
  protected $_values = [];
  protected $_keys = [];

  public function addValue($value, $probability = 1)
  {
    $key = 'k' . base_convert(count($this->_values), 10, 32);
    $this->_values[$key] = $value;
    if($probability < 1 || $probability > 1000)
    {
      throw new \InvalidArgumentException("Shuffler probability must be between 1 and 1000");
    }
    for($i = 0; $i < $probability; $i++)
    {
      $this->_keys[$key . '_' . $i] = $key;
    }
    return $this;
  }

  /**
   * Read a random value from the shuffler
   *
   * @return mixed|null
   */
  public function read()
  {
    if(!empty($this->_values))
    {
      $key = array_rand($this->_keys);
      return $this->_values[$this->_keys[$key]];
    }
    return null;
  }

  /**
   * Read a random value from the shuffler, and remove it from the shuffle
   *
   * @return mixed|null
   */
  public function pop()
  {
    if(empty($this->_values))
    {
      return null;
    }
    $key = array_rand($this->_keys);
    $key = $this->_keys[$key];
    $return = $this->_values[$key];

    unset($this->_values[$key]);
    $this->_keys = array_diff($this->_keys, [0 => $key]);
    return $return;
  }
}
