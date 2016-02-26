<?php

use Packaged\Helpers\BitWiseInt;

class BitWiseIntTest extends PHPUnit_Framework_TestCase
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
    $this->assertTrue(BitWiseInt::isSingleBit(1));
    $this->assertTrue(BitWiseInt::isSingleBit("1"));
    $this->assertTrue(BitWiseInt::isSingleBit(2));
    $this->assertTrue(BitWiseInt::isSingleBit("2"));
    $this->assertTrue(BitWiseInt::isSingleBit(4));

    $fails = [3, 5, 6, 7, 9, 10, 11, 13, 14, 15];
    foreach($fails as $checkBit)
    {
      $this->assertFalse(BitWiseInt::isSingleBit($checkBit));
    }

    $checkBit = 4;
    for($i = 0; $i < 32; $i++)
    {
      $checkBit *= 2;
      $this->assertTrue(BitWiseInt::isSingleBit($checkBit));
      $this->assertFalse(BitWiseInt::isSingleBit($checkBit - 3));
    }
  }

  public function testModify()
  {
    $state = 0;

    //Has
    $this->assertFalse(BitWiseInt::has($state, static::ONE));

    //Add
    $state = BitWiseInt::add($state, static::ONE);
    $this->assertTrue(BitWiseInt::has($state, static::ONE));
    $state = BitWiseInt::add($state, static::TWO);
    $state = BitWiseInt::add($state, static::TWO);
    $this->assertTrue(BitWiseInt::has($state, static::ONE));
    $this->assertTrue(BitWiseInt::has($state, static::TWO));

    //Remove
    $state = BitWiseInt::remove($state, static::ONE);
    $state = BitWiseInt::remove($state, static::ONE);
    $this->assertTrue(BitWiseInt::has($state, static::TWO));
    $this->assertFalse(BitWiseInt::has($state, static::ONE));

    //Toggle
    $state = BitWiseInt::toggle($state, static::ONE);
    $this->assertTrue(BitWiseInt::has($state, static::ONE));
    $state = BitWiseInt::toggle($state, static::ONE);
    $this->assertFalse(BitWiseInt::has($state, static::ONE));

    //Highest
    $state = BitWiseInt::add($state, static::FOUR);
    $this->assertEquals(static::FOUR, BitWiseInt::highest($state));
    $state = BitWiseInt::add($state, static::SIX);
    $this->assertEquals(static::SIX, BitWiseInt::highest($state));

    //Get Bits
    $this->assertEquals(
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
    $this->assertFalse(BitWiseInt::has($state, $mask));
    $this->assertTrue(BitWiseInt::hasAny($state, $mask));
  }
}
