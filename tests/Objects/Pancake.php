<?php
namespace Packaged\Tests\Objects;

final class Pancake
{
  public $fruit;
  public $sauce;

  public function __construct($fruit = null, $sauce = null)
  {
    $this->fruit = $fruit;
    $this->sauce = $sauce;
  }

  public function getFruit()
  {
    return $this->fruit;
  }

  public function getSauce()
  {
    return $this->sauce;
  }
}
