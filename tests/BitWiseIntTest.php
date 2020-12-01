<?php
namespace Packaged\Tests;

use Packaged\Helpers\BitWiseInt;
use PHPUnit\Framework\TestCase;

class BitWiseIntTest extends TestCase
{
  const ONE = '1';
  const TWO = '2';
  const THREE = '4';
  const FOUR = '8';
  const FIVE = '16';
  const SIX = '32';

  /**
   * @large
   */
  public function testSingleBit()
  {
    static::assertTrue(BitWiseInt::isSingleBit(1));
    static::assertTrue(BitWiseInt::isSingleBit("1"));
    static::assertTrue(BitWiseInt::isSingleBit(2));
    static::assertTrue(BitWiseInt::isSingleBit("2"));
    static::assertTrue(BitWiseInt::isSingleBit(4));

    $fails = [3, 5, 6, 7, 9, 10, 11, 13, 14, 15];
    foreach($fails as $checkBit)
    {
      static::assertFalse(BitWiseInt::isSingleBit($checkBit));
    }

    $checkBit = 4;
    for($i = 0; $i < 32; $i++)
    {
      $checkBit *= 2;
      static::assertTrue(BitWiseInt::isSingleBit($checkBit));
      static::assertFalse(BitWiseInt::isSingleBit($checkBit - 3));
    }
  }

  public function testModify()
  {
    $state = 0;

    //Has
    static::assertFalse(BitWiseInt::has($state, static::ONE));

    //Add
    $state = BitWiseInt::add($state, static::ONE);
    static::assertTrue(BitWiseInt::has($state, static::ONE));
    $state = BitWiseInt::add($state, static::TWO);
    $state = BitWiseInt::add($state, static::TWO);
    static::assertTrue(BitWiseInt::has($state, static::ONE));
    static::assertTrue(BitWiseInt::has($state, static::TWO));

    //Remove
    $state = BitWiseInt::remove($state, static::ONE);
    $state = BitWiseInt::remove($state, static::ONE);
    static::assertTrue(BitWiseInt::has($state, static::TWO));
    static::assertFalse(BitWiseInt::has($state, static::ONE));

    //Toggle
    $state = BitWiseInt::toggle($state, static::ONE);
    static::assertTrue(BitWiseInt::has($state, static::ONE));
    $state = BitWiseInt::toggle($state, static::ONE);
    static::assertFalse(BitWiseInt::has($state, static::ONE));

    //Highest
    $state = BitWiseInt::add($state, static::FOUR);
    static::assertEquals(static::FOUR, BitWiseInt::highest($state));
    $state = BitWiseInt::add($state, static::SIX);
    static::assertEquals(static::SIX, BitWiseInt::highest($state));

    //Get Bits
    static::assertEquals(
      [static::TWO, static::FOUR, static::SIX],
      BitWiseInt::getBits($state)
    );
  }

  public function testHas()
  {
    $state = 0;
    $state = BitWiseInt::add($state, static::TWO);
    $state = BitWiseInt::add($state, static::FIVE);

    $mask = static::TWO | static::THREE;
    static::assertFalse(BitWiseInt::has($state, $mask));
    static::assertTrue(BitWiseInt::hasAny($state, $mask));
  }
}
