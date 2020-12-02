<?php
namespace Packaged\Tests\Traits;

use Packaged\Helpers\Traits\ArrayAccessTrait;
use PHPUnit\Framework\TestCase;

class ArrayAccessTraitTest extends TestCase
{
  public function testTrait()
  {
    $arr = new MockArrayAccessTrait();
    /**
     * @var $arr ArrayAccessTrait
     */
    $arr['one'] = 1;
    static::assertArrayHasKey('one', $arr);
    static::assertEquals(1, $arr['one']);
    static::assertTrue(isset($arr['one']));
    unset($arr['one']);
    static::assertNull($arr['one']);
    static::assertArrayNotHasKey('one', $arr);
    static::assertFalse(isset($arr['one']));
  }
}
