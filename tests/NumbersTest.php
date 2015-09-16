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

  public function testFormatSuffix()
  {
    $this->assertEquals('100', Numbers::humanize(100));
    $this->assertEquals('1k', Numbers::humanize(1000));
    $this->assertEquals('12.5k', Numbers::humanize(12500));
    $this->assertEquals('1m', Numbers::humanize(1000000));
    $this->assertEquals('1b', Numbers::humanize(1000000000));
    $this->assertEquals('1t', Numbers::humanize(1000000000000));

    $this->assertEquals('100', Numbers::humanize(100, true));
    $this->assertEquals('1k', Numbers::humanize(1000, true));
    $this->assertEquals('12.5k', Numbers::humanize(12500, true));
    $this->assertEquals('1m', Numbers::humanize(1000000, true));
    $this->assertEquals('1g', Numbers::humanize(1000000000, true));
    $this->assertEquals('1t', Numbers::humanize(1000000000000, true));
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
