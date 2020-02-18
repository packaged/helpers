<?php

namespace Packaged\Tests;

use Packaged\Helpers\Branch;
use Packaged\Helpers\Objects;
use Packaged\Tests\Objects\TreeThing;
use PHPUnit\Framework\TestCase;

class BranchTest extends TestCase
{
  public function testArrayTree()
  {
    $test = [
      ['id' => 0, 'parentId' => null, 'key' => 'value', 'data' => ['root data']],
      ['id' => 1, 'parentId' => null, 'key' => 'value', 'data' => ['root data']],
      ['id' => 2, 'parentId' => 1, 'key' => 'value', 'data' => ['1 child 1']],
      ['id' => 3, 'parentId' => 1, 'key' => 'value', 'data' => ['1 child 2']],
      ['id' => 4, 'parentId' => 2, 'key' => 'value', 'data' => ['2 child 1']],
    ];

    $tree = Branch::trunk()->iHydrate($test, 'id', 'parentId');
    $this->assertTrue($tree->hasChildren());
    $this->assertEquals(2, count($tree->getChildren()));
    $this->assertEquals(
      '[{"object":{"id":0,"parentId":null,"key":"value","data":["root data"]},"children":[]},{"object":{"id":1,"parentId":null,"key":"value","data":["root data"]},"children":[{"object":{"id":2,"parentId":1,"key":"value","data":["1 child 1"]},"children":[{"object":{"id":4,"parentId":2,"key":"value","data":["2 child 1"]},"children":[]}]},{"object":{"id":3,"parentId":1,"key":"value","data":["1 child 2"]},"children":[]}]}]',
      json_encode($tree)
    );
    $this->assertNull($tree->getItem());
    $this->assertNull($tree->getParent());
    $this->assertEquals($tree, $tree->getChildren()[0]->getParent());
  }

  public function testStdClassTree()
  {
    $test = [
      (object)['id' => 0, 'parentId' => null, 'key' => 'value', 'data' => ['root data']],
      (object)['id' => 1, 'parentId' => null, 'key' => 'value', 'data' => ['root data']],
      (object)['id' => 2, 'parentId' => 1, 'key' => 'value', 'data' => ['1 child 1']],
      (object)['id' => 3, 'parentId' => 1, 'key' => 'value', 'data' => ['1 child 2']],
      (object)['id' => 4, 'parentId' => 2, 'key' => 'value', 'data' => ['2 child 1']],
    ];

    $tree = Branch::trunk()->pHydrate($test, 'id', 'parentId');
    $this->assertTrue($tree->hasChildren());
    $this->assertEquals(2, count($tree->getChildren()));
    $this->assertEquals(
      '[{"object":{"id":0,"parentId":null,"key":"value","data":["root data"]},"children":[]},{"object":{"id":1,"parentId":null,"key":"value","data":["root data"]},"children":[{"object":{"id":2,"parentId":1,"key":"value","data":["1 child 1"]},"children":[{"object":{"id":4,"parentId":2,"key":"value","data":["2 child 1"]},"children":[]}]},{"object":{"id":3,"parentId":1,"key":"value","data":["1 child 2"]},"children":[]}]}]',
      json_encode($tree)
    );
    $this->assertNull($tree->getItem());
    $this->assertNull($tree->getParent());
    $this->assertEquals($tree, $tree->getChildren()[0]->getParent());
  }

  public function testObjectTree()
  {
    $test = [
      Objects::create(TreeThing::class, [0, null, 'value', ['root data']]),
      Objects::create(TreeThing::class, [1, null, 'value', ['root data']]),
      Objects::create(TreeThing::class, [2, 1, 'value', ['1 child 1']]),
      Objects::create(TreeThing::class, [3, 1, 'value', ['1 child 2']]),
      Objects::create(TreeThing::class, [4, 2, 'value', ['2 child 1']]),
    ];

    $tree = Branch::trunk()->mHydrate($test, 'getId', 'getParentId');
    $this->assertTrue($tree->hasChildren());
    $this->assertEquals(2, count($tree->getChildren()));
    $this->assertEquals(
      '[{"object":{"id":0,"parentId":null,"key":"value","data":["root data"]},"children":[]},{"object":{"id":1,"parentId":null,"key":"value","data":["root data"]},"children":[{"object":{"id":2,"parentId":1,"key":"value","data":["1 child 1"]},"children":[{"object":{"id":4,"parentId":2,"key":"value","data":["2 child 1"]},"children":[]}]},{"object":{"id":3,"parentId":1,"key":"value","data":["1 child 2"]},"children":[]}]}]',
      json_encode($tree)
    );
    $this->assertNull($tree->getItem());
    $this->assertNull($tree->getParent());
    $this->assertEquals($tree, $tree->getChildren()[0]->getParent());
    $this->assertInstanceOf(TreeThing::class, $tree->getChildren()[0]->getItem());
  }

  public function testNonExistingParent()
  {
    $test = [
      Objects::create(TreeThing::class, ['myid', 'badparent', 'value', ['root data']]),
    ];

    $tree = Branch::trunk()->mHydrate($test, 'getId', 'getParentId');
    $this->assertTrue($tree->hasChildren());
    $this->assertEquals(1, count($tree->getChildren()));
    $this->assertEquals(
      '[{"object":{"id":"myid","parentId":"badparent","key":"value","data":["root data"]},"children":[]}]',
      json_encode($tree)
    );
    $this->assertNull($tree->getItem());
    $this->assertNull($tree->getParent());
    $this->assertEquals($tree, $tree->getChildren()[0]->getParent());
    $this->assertInstanceOf(TreeThing::class, $tree->getChildren()[0]->getItem());
  }
}
