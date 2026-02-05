<?php

namespace Packaged\Helpers;

use Generator;
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
  protected function __construct($object, ?string $parentId)
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
  public function pHydrate(array $objects, string $idProperty, string $parentIdProperty)
  {
    $newObjects = $appendObjects = [];
    foreach($objects as $object)
    {
      $key = $object->{$idProperty};
      $o = new static($object, $object->{$parentIdProperty});
      if($key !== null)
      {
        $newObjects[$key] = $o;
      }
      else
      {
        $appendObjects[] = $o;
      }
    }
    foreach($appendObjects as $o)
    {
      $newObjects[] = $o;
    }
    return $this->_hydrate($newObjects);
  }

  /**
   * @param array  $objects
   * @param string $idMethod
   * @param string $parentIdMethod
   *
   * @return static
   */
  public function mHydrate(array $objects, string $idMethod, string $parentIdMethod)
  {
    $newObjects = $appendObjects = [];
    foreach($objects as $object)
    {
      $key = $object->{$idMethod}();
      $o = new static($object, $object->{$parentIdMethod}());
      if($key !== null)
      {
        $newObjects[$key] = $o;
      }
      else
      {
        $appendObjects[] = $o;
      }
    }
    foreach($appendObjects as $o)
    {
      $newObjects[] = $o;
    }
    return $this->_hydrate($newObjects);
  }

  /**
   * @param array  $objects
   * @param string $idKey
   * @param string $parentIdKey
   *
   * @return static
   */
  public function iHydrate(array $objects, string $idKey, string $parentIdKey)
  {
    $newObjects = $appendObjects = [];
    foreach($objects as $object)
    {
      $key = $object[$idKey];
      $o = new static($object, $object[$parentIdKey]);
      if($key !== null)
      {
        $newObjects[$key] = $o;
      }
      else
      {
        $appendObjects[] = $o;
      }
    }
    foreach($appendObjects as $o)
    {
      $newObjects[] = $o;
    }
    return $this->_hydrate($newObjects);
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
      if($object->_parentId !== null && isset($objects[$object->_parentId]))
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
  #[\ReturnTypeWillChange]
  public function jsonSerialize()
  {
    if($this->_item)
    {
      return ['object' => $this->_item, 'children' => $this->_children];
    }
    return $this->_children;
  }

  /**
   * Perform a "pre-order" depth-first iteration
   *
   * @return Generator
   */
  public function iterate()
  {
    // @codeCoverageIgnoreStart
    foreach(self::_iterate($this) as $item)
    {
      yield $item;
    }
    // @codeCoverageIgnoreEnd
  }

  /**
   * Return an array of items in depth-first order
   *
   * @return array
   */
  public function flatten()
  {
    return iterator_to_array($this->iterate());
  }

  private static function _iterate(Branch $b)
  {
    // @codeCoverageIgnoreStart
    $item = $b->getItem();
    if($item)
    {
      yield $item;
    }
    if($b->hasChildren())
    {
      foreach($b->getChildren() as $child)
      {
        foreach(self::_iterate($child) as $yielded)
        {
          yield $yielded;
        }
      }
    }
    // @codeCoverageIgnoreEnd
  }
}
