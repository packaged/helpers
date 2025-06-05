<?php
namespace Packaged\Tests\Objects;

use Packaged\Helpers\Objects;

#[\AllowDynamicProperties]
class PropertyClass
{
  public $name;
  public $age;
  protected $_gender;
  private $_ryan;

  public function objectVars()
  {
    return get_object_vars($this);
  }

  public function publicVars()
  {
    return Objects::propertyValues($this);
  }
}
