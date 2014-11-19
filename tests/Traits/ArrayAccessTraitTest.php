<?php
namespace Traits;

use Packaged\Helpers\Traits\ArrayAccessTrait;

class ArrayAccessTraitTest extends \PHPUnit_Framework_TestCase
{
  public function testTrait()
  {
    $arr = new MockArrayAccessTrait();
    /**
     * @var $arr ArrayAccessTrait
     */
    $arr['one'] = 1;
    $this->assertArrayHasKey('one', $arr);
    $this->assertEquals(1, $arr['one']);
    $this->assertTrue(isset($arr['one']));
    unset($arr['one']);
    $this->assertNull($arr['one']);
    $this->assertArrayNotHasKey('one', $arr);
    $this->assertFalse(isset($arr['one']));
  }
}

class MockArrayAccessTrait implements \ArrayAccess
{
  use ArrayAccessTrait;
}
