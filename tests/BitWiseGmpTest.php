<?php

use Packaged\Helpers\BitWiseGmp;

class BitWiseGmpTest extends PHPUnit_Framework_TestCase
{
  const ONE = '1';
  const TWO = '2';
  const THREE = '4';
  const FOUR = '8';
  const FIVE = '16';
  const SIX = '32';

  protected function setUp()
  {
    if(!extension_loaded('gmp'))
    {
      $this->markTestSkipped('The GMP extension is not available.');
    }
  }

  /**
   * @large
   */
  public function testSingleBit()
  {
    $this->assertTrue(BitWiseGmp::isSingleBit(1));
    $this->assertTrue(BitWiseGmp::isSingleBit("1"));
    $this->assertTrue(BitWiseGmp::isSingleBit(2));
    $this->assertTrue(BitWiseGmp::isSingleBit("2"));
    $this->assertTrue(BitWiseGmp::isSingleBit(4));

    $fails = [3, 5, 6, 7, 9, 10, 11, 13, 14, 15];
    foreach($fails as $checkBit)
    {
      $this->assertFalse(BitWiseGmp::isSingleBit($checkBit));
    }

    $checkBit = 4;
    for($i = 0; $i < 10000; $i++)
    {
      $checkBit = gmp_mul($checkBit, 2);
      $this->assertTrue(BitWiseGmp::isSingleBit($checkBit));
      $this->assertFalse(BitWiseGmp::isSingleBit(gmp_sub($checkBit, 3)));
    }
  }

  public function testModify()
  {
    $state = 0;

    //Has
    $this->assertFalse(BitWiseGmp::has($state, static::ONE));

    //Add
    $state = BitWiseGmp::add($state, static::ONE);
    $this->assertTrue(BitWiseGmp::has($state, static::ONE));
    $state = BitWiseGmp::add($state, static::TWO);
    $state = BitWiseGmp::add($state, static::TWO);
    $this->assertTrue(BitWiseGmp::has($state, static::ONE));
    $this->assertTrue(BitWiseGmp::has($state, static::TWO));

    //Remove
    $state = BitWiseGmp::remove($state, static::ONE);
    $state = BitWiseGmp::remove($state, static::ONE);
    $this->assertTrue(BitWiseGmp::has($state, static::TWO));
    $this->assertFalse(BitWiseGmp::has($state, static::ONE));

    //Toggle
    $state = BitWiseGmp::toggle($state, static::ONE);
    $this->assertTrue(BitWiseGmp::has($state, static::ONE));
    $state = BitWiseGmp::toggle($state, static::ONE);
    $this->assertFalse(BitWiseGmp::has($state, static::ONE));

    //Highest
    $state = BitWiseGmp::add($state, static::FOUR);
    $this->assertEquals(static::FOUR, BitWiseGmp::highest($state));
    $state = BitWiseGmp::add($state, static::SIX);
    $this->assertEquals(static::SIX, BitWiseGmp::highest($state));

    //Get Bits
    $this->assertEquals(
      [static::TWO, static::FOUR, static::SIX],
      BitWiseGmp::getBits($state)
    );
  }
}
