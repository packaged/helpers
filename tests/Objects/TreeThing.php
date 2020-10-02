<?php

namespace Packaged\Tests\Objects;

use JsonSerializable;

class TreeThing implements JsonSerializable
{
  private $_id;
  private $_parentId;
  private $_key;
  private $_data;

  public function __construct($id, $parentId, $key = null, $data = null)
  {
    $this->_id = $id;
    $this->_parentId = $parentId;
    $this->_key = $key;
    $this->_data = $data;
  }

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->_id;
  }

  /**
   * @return mixed
   */
  public function getParentId()
  {
    return $this->_parentId;
  }

  /**
   * @return mixed
   */
  public function getKey()
  {
    return $this->_key;
  }

  /**
   * @return mixed
   */
  public function getData()
  {
    return $this->_data;
  }

  /**
   * @inheritDoc
   */
  public function jsonSerialize()
  {
    return [
      'id'       => $this->_id,
      'parentId' => $this->_parentId,
      'key'      => $this->_key,
      'data'     => $this->_data,
    ];
  }
}
