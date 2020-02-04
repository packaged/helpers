<?php

namespace Packaged\Helpers;

use JsonSerializable;

class Branch implements JsonSerializable
{
  protected $_parent;
  protected $_parentId;
  protected $_children = [];
  protected $_item;

  /**
   * Branch constructor.
   *
   * @param mixed       $object
   * @param string|null $parentId
   */
  protected function __construct($object, $parentId)
  {
    $this->_item = $object;
    $this->_parentId = $parentId;
  }

  public static function trunk()
  {
    return new static(null, null);
  }

  /**
   * @param array  $objects
   * @param string $idProperty
   * @param string $parentIdProperty
   *
   * @return static
   */
  public function pHydrate(array $objects, $idProperty, $parentIdProperty)
  {
    $objects = Objects::ppull($objects, null, $idProperty);
    array_walk($objects, function (&$o) use ($parentIdProperty) { $o = new static($o, $o->{$parentIdProperty}); });
    return $this->_hydrate($objects);
  }

  /**
   * @param array  $objects
   * @param string $idMethod
   * @param string $parentIdMethod
   *
   * @return static
   */
  public function mHydrate(array $objects, $idMethod, $parentIdMethod)
  {
    $objects = Objects::mpull($objects, null, $idMethod);
    array_walk($objects, function (&$o) use ($parentIdMethod) { $o = new static($o, $o->{$parentIdMethod}()); });
    return $this->_hydrate($objects);
  }

  /**
   * @param array  $objects
   * @param string $idKey
   * @param string $parentIdKey
   *
   * @return static
   */
  public function iHydrate(array $objects, $idKey, $parentIdKey)
  {
    $objects = Arrays::ipull($objects, null, $idKey);
    array_walk($objects, function (&$o) use ($parentIdKey) { $o = new static($o, $o[$parentIdKey]); });
    return $this->_hydrate($objects);
  }

  /**
   * @param static[] $objects
   *
   * @return static
   */
  protected function _hydrate(array $objects)
  {
    foreach($objects as $object)
    {
      if($object->_parentId)
      {
        $object->_setParent($objects[$object->_parentId]);
        $objects[$object->_parentId]->_addChild($object);
      }
      else
      {
        $object->_setParent($this);
        $this->_addChild($object);
      }
    }
    return $this;
  }

  protected function _setParent(Branch $parent)
  {
    $this->_parent = $parent;
    return $this;
  }

  protected function _addChild(Branch $child)
  {
    $this->_children[] = $child;
    return $this;
  }

  /**
   * @return static[]
   */
  public function getChildren()
  {
    return $this->_children;
  }

  /**
   * @return bool
   */
  public function hasChildren()
  {
    return count($this->_children) > 0;
  }

  /**
   * @return mixed
   */
  public function getItem()
  {
    return $this->_item;
  }

  /**
   * @return static
   */
  public function getParent()
  {
    return $this->_parent;
  }

  /**
   * @inheritDoc
   */
  public function jsonSerialize()
  {
    if($this->_item)
    {
      return ['object' => $this->_item, 'children' => $this->_children];
    }
    return $this->_children;
  }
}
