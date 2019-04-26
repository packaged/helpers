<?php
namespace Packaged\Tests;

use Packaged\Helpers\BitWise;
use PHPUnit_Framework_TestCase;

class BitWiseTest extends PHPUnit_Framework_TestCase
{
  const ONE = '1';
  const TWO = '2';
  const THREE = '4';
  const FOUR = '8';
  const FIVE = '16';
  const SIX = '32';

  /**
   * @dataProvider gmpPreferenceProvider
   */
  public function testSingleBit($preferGmp)
  {
    BitWise::preferGmp($preferGmp);
    $this->assertTrue(BitWise::isSingleBit(1));
    $this->assertTrue(BitWise::isSingleBit("1"));
    $this->assertTrue(BitWise::isSingleBit(2));
    $this->assertTrue(BitWise::isSingleBit("2"));
    $this->assertTrue(BitWise::isSingleBit(4));

    $fails = [3, 5, 6, 7, 9, 10, 11, 13, 14, 15];
    foreach($fails as $checkBit)
    {
      $this->assertFalse(BitWise::isSingleBit($checkBit));
    }

    $checkBit = 4;
    for($i = 0; $i < 32; $i++)
    {
      $checkBit *= 2;
      $this->assertTrue(BitWise::isSingleBit($checkBit));
      $this->assertFalse(BitWise::isSingleBit($checkBit - 3));
    }
  }

  /**
   * @dataProvider gmpPreferenceProvider
   */
  public function testModify($preferGmp)
  {
    BitWise::preferGmp($preferGmp);
    $state = 0;

    //Has
    $this->assertFalse(BitWise::has($state, static::ONE));

    //Add
    $state = BitWise::add($state, static::ONE);
    $this->assertTrue(BitWise::has($state, static::ONE));
    $state = BitWise::add($state, static::TWO);
    $state = BitWise::add($state, static::TWO);
    $this->assertTrue(BitWise::has($state, static::ONE));
    $this->assertTrue(BitWise::has($state, static::TWO));

    //Remove
    $state = BitWise::remove($state, static::ONE);
    $state = BitWise::remove($state, static::ONE);
    $this->assertTrue(BitWise::has($state, static::TWO));
    $this->assertFalse(BitWise::has($state, static::ONE));

    //Toggle
    $state = BitWise::toggle($state, static::ONE);
    $this->assertTrue(BitWise::has($state, static::ONE));
    $state = BitWise::toggle($state, static::ONE);
    $this->assertFalse(BitWise::has($state, static::ONE));

    //Highest
    $state = BitWise::add($state, static::FOUR);
    $this->assertEquals(static::FOUR, BitWise::highest($state));
    $state = BitWise::add($state, static::SIX);
    $this->assertEquals(static::SIX, BitWise::highest($state));

    //Get Bits
    $this->assertEquals(
      [static::TWO, static::FOUR, static::SIX],
      BitWise::getBits($state)
    );
  }

  /**
   * @dataProvider gmpPreferenceProvider
   */
  public function testHas($preferGmp)
  {
    BitWise::preferGmp($preferGmp);
    $state = 0;
    $state = BitWise::add($state, static::TWO);
    $state = BitWise::add($state, static::FIVE);

    $mask = static::TWO | static::THREE;
    $this->assertFalse(BitWise::has($state, $mask));
    $this->assertTrue(BitWise::hasAny($state, $mask));
  }

  public function gmpPreferenceProvider()
  {
    if(extension_loaded('gmp'))
    {
      return [[true], [false]];
    }
    else
    {
      return [[false]];
    }
  }
}
