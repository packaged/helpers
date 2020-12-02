<?php
namespace Packaged\Tests;

use Packaged\Helpers\Numbers;
use PHPUnit\Framework\TestCase;

class NumbersTest extends TestCase
{
  public function testFormat()
  {
    static::assertEquals('-', Numbers::format('-'));
    static::assertEquals(0, Numbers::format('-', 0, '.', ',', true));
    static::assertEquals('10,000', Numbers::format('10000'));
  }

  public function testFormatSuffix()
  {
    static::assertEquals('100', Numbers::humanize(100));
    static::assertEquals('1k', Numbers::humanize(1000));
    static::assertEquals('12.5k', Numbers::humanize(12500));
    static::assertEquals('1m', Numbers::humanize(1000000));
    static::assertEquals('1b', Numbers::humanize(1000000000));
    static::assertEquals('1t', Numbers::humanize(1000000000000));

    static::assertEquals('-100', Numbers::humanize(-100));
    static::assertEquals('-1k', Numbers::humanize(-1000));
    static::assertEquals('-12.5k', Numbers::humanize(-12500));
    static::assertEquals('-1m', Numbers::humanize(-1000000));
    static::assertEquals('-1b', Numbers::humanize(-1000000000));
    static::assertEquals('-1t', Numbers::humanize(-1000000000000));

    static::assertEquals('100', Numbers::humanize(100, true));
    static::assertEquals('1k', Numbers::humanize(1000, true));
    static::assertEquals('12.5k', Numbers::humanize(12500, true));
    static::assertEquals('1m', Numbers::humanize(1000000, true));
    static::assertEquals('1g', Numbers::humanize(1000000000, true));
    static::assertEquals('1t', Numbers::humanize(1000000000000, true));
  }

  public function testBetween()
  {
    static::assertTrue(Numbers::between(2, 1, 3));
    static::assertTrue(Numbers::between(2, 1, 2));

    static::assertFalse(Numbers::between(3, 1, 2));
    static::assertFalse(Numbers::between(3, 1, 2, true));

    static::assertFalse(Numbers::between(2, 1, 2, false));
  }
}
