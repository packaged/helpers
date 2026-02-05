<?php
namespace Packaged\Tests;

use Packaged\Helpers\DependencyArray;
use PHPUnit\Framework\TestCase;

class DependencyArrayTest extends TestCase
{
  public function testDependencies()
  {
    $darray = new DependencyArray();
    $darray->add(1, []);
    $darray->add(2, [1]);
    $darray->add(3, [4]);
    $darray->add(4, [2, 1]);

    $expect = [1, 2, 4, 3];
    static::assertEquals($expect, $darray->getLoadOrder());
  }

  public function testImpossibleDependencies()
  {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage("Impossible set of dependencies");
    $darray = new DependencyArray();
    $darray->add(1, [2]);
    $darray->add(2, [1]);
    $darray->add(3, [3]);
    $darray->getLoadOrder();
  }

  public function testResolve()
  {
    $darray = new DependencyArray();
    $darray->add(1, [2], 'three');
    $darray->add(2, [3], 'two');
    $darray->add(3, [], 'one');
    static::assertEquals('one,two,three', implode(',', $darray->resolved()));

    $darray = new DependencyArray();
    $darray->add(1, [2], 'three');
    $darray->add(2, [3]);
    $darray->add(3, [], 'one');
    static::assertEquals('one,,three', implode(',', $darray->resolved()));
  }

  public function testCachedLoadOrder()
  {
    $darray = new DependencyArray();
    $darray->add(1, []);
    $darray->add(2, [1]);

    // First call computes load order
    $first = $darray->getLoadOrder();
    // Second call should return cached result
    $second = $darray->getLoadOrder();

    static::assertEquals($first, $second);
    static::assertEquals([1, 2], $first);
  }
}
