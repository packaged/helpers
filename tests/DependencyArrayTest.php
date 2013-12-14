<?php
/**
 * @author  brooke.bryan
 */

class DependencyArrayTest extends PHPUnit_Framework_TestCase
{
  public function testDependencies()
  {
    $darray = new \Packaged\Helpers\DependencyArray();
    $darray->add(1, []);
    $darray->add(2, [1]);
    $darray->add(3, [4]);
    $darray->add(4, [2, 1]);

    $expect = [1, 2, 4, 3];
    $this->assertEquals($expect, $darray->getLoadOrder());
  }

  public function testImpossibleDependencies()
  {
    $this->setExpectedException("Exception", "Impossible set of dependencies");
    $darray = new \Packaged\Helpers\DependencyArray();
    $darray->add(1, [2]);
    $darray->add(2, [1]);
    $darray->add(3, [3]);
    $darray->getLoadOrder();
  }
}
