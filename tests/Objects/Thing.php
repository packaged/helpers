<?php
namespace Packaged\Tests\Objects;

final class Thing
{
  private $_name;
  private $_type;
  private $_colour;
  private $_group;

  public function __construct($name, $type, $colour, $group)
  {
    $this->_name = $this->nameProperty = $name;
    $this->_type = $this->typeProperty = $type;
    $this->_colour = $this->colourProperty = $colour;
    $this->_group = $this->groupProperty = $group;
  }

  public $nameProperty;
  public $typeProperty;
  public $colourProperty;
  public $groupProperty;

  public function type()
  {
    return $this->_type;
  }

  public function group()
  {
    return $this->_group;
  }
}
