<?php
namespace Packaged\Helpers;

class DependencyArray
{
  protected $_items;
  protected $_depends;
  protected $_hasDependency;

  public function __construct()
  {
    $this->_items         = array();
    $this->_depends       = array();
    $this->_hasDependency = array();
  }

  public function add($item, $dependsOn = array())
  {
    $this->_items[] = $item;
    $dependsOn      = (array)$dependsOn;
    foreach($dependsOn as $dependsOnItem)
    {
      $this->_items[]                   = $dependsOnItem;
      $this->_depends[$dependsOnItem][] = $item;
    }

    $this->_items                = array_unique($this->_items);
    $this->_hasDependency[$item] = $dependsOn;
  }

  public function getLoadOrder()
  {
    $order    = array();
    $itmCount = count($this->_items);

    $hasChanged = true;
    while(count($order) < $itmCount && $hasChanged === true)
    {
      $hasChanged           = false;
      $this->_hasDependency = (array)$this->_hasDependency;
      foreach($this->_hasDependency as $item => $dependencies)
      {
        if($this->_satisfied($item, $order))
        {
          $order[] = $item;
          unset($this->_hasDependency[$item]);
          $hasChanged = true;
        }
      }
    }

    if(count($order) < $itmCount && $hasChanged === false)
    {
      throw new \Exception('Impossible set of dependencies');
    }

    return $order;
  }

  protected function _satisfied($item, $addedSoFar)
  {
    $dependencies = $this->_hasDependency[$item];
    $dependencies = (array)$dependencies;
    foreach($dependencies as $dependency)
    {
      if(!in_array($dependency, $addedSoFar))
      {
        return false;
      }
    }

    return true;
  }
}
