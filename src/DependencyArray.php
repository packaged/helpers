<?php
namespace Packaged\Helpers;

class DependencyArray
{
  protected $_items;
  protected $_itemData;
  protected $_depends;
  protected $_hasDependency;
  protected $_loadOrder;

  public function __construct()
  {
    $this->_items = [];
    $this->_itemData = [];
    $this->_depends = [];
    $this->_hasDependency = [];
    $this->_loadOrder = [];
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

  public function add($key, $dependsOnKeys = [], $itemData = null)
  {
    $this->_loadOrder = [];
    $this->_items[] = $key;
    $this->_itemData[$key] = $itemData;
    $dependsOnKeys = (array)$dependsOnKeys;
    foreach($dependsOnKeys as $dependsOnItem)
    {
      $this->_items[] = $dependsOnItem;
      $this->_depends[$dependsOnItem][] = $key;
    }

    $this->_items = array_unique($this->_items);
    $this->_hasDependency[$key] = $dependsOnKeys;
    return $this;
  }

  public function getLoadOrder()
  {
    if(!empty($this->_loadOrder))
    {
      return $this->_loadOrder;
    }

    $this->_loadOrder = [];
    $itmCount = count($this->_items);

    $hasChanged = true;
    while(count($this->_loadOrder) < $itmCount && $hasChanged === true)
    {
      $hasChanged = false;
      $this->_hasDependency = (array)$this->_hasDependency;
      foreach($this->_hasDependency as $item => $dependencies)
      {
        if($this->_satisfied($item, $this->_loadOrder))
        {
          $this->_loadOrder[] = $item;
          unset($this->_hasDependency[$item]);
          $hasChanged = true;
        }
      }
    }

    if(count($this->_loadOrder) < $itmCount && $hasChanged === false)
    {
      throw new \Exception('Impossible set of dependencies');
    }

    return $this->_loadOrder;
  }

  /**
   * @return array sorted in order with the item data or key
   *
   * @throws \Exception
   */
  public function resolved()
  {
    $return = [];
    foreach($this->getLoadOrder() as $key)
    {
      $return[$key] = isset($this->_itemData[$key]) ? $this->_itemData[$key] : null;
    }
    return $return;
  }

}
