<?php

use Packaged\Helpers\Numbers;

class NumbersTest extends PHPUnit_Framework_TestCase
{
  public function testFormat()
  {
    $this->assertEquals('-', Numbers::format('-'));
    $this->assertEquals(0, Numbers::format('-', 0, '.', ',', true));
    $this->assertEquals('10,000', Numbers::format('10000'));
  }

  public function testBetween()
  {
    $this->assertTrue(Numbers::between(2, 1, 3));
    $this->assertTrue(Numbers::between(2, 1, 2));

    $this->assertFalse(Numbers::between(3, 1, 2));
    $this->assertFalse(Numbers::between(3, 1, 2, true));

    $this->assertFalse(Numbers::between(2, 1, 2, false));
  }
}
