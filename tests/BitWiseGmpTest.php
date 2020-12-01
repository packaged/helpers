<?php
namespace Packaged\Tests;

use Packaged\Helpers\BitWiseGmp;
use PHPUnit\Framework\TestCase;

/**
 * @requires extension gmp
 */
class BitWiseGmpTest extends TestCase
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
    static::assertTrue(BitWiseGmp::isSingleBit(1));
    static::assertTrue(BitWiseGmp::isSingleBit("1"));
    static::assertTrue(BitWiseGmp::isSingleBit(2));
    static::assertTrue(BitWiseGmp::isSingleBit("2"));
    static::assertTrue(BitWiseGmp::isSingleBit(4));

    $fails = [3, 5, 6, 7, 9, 10, 11, 13, 14, 15];
    foreach($fails as $checkBit)
    {
      static::assertFalse(BitWiseGmp::isSingleBit($checkBit));
    }

    $checkBit = 4;
    for($i = 0; $i < 10000; $i++)
    {
      $checkBit = gmp_mul($checkBit, 2);
      static::assertTrue(BitWiseGmp::isSingleBit($checkBit));
      static::assertFalse(BitWiseGmp::isSingleBit(gmp_sub($checkBit, 3)));
    }
  }

  public function testModify()
  {
    $state = 0;

    //Has
    static::assertFalse(BitWiseGmp::has($state, static::ONE));

    //Add
    $state = BitWiseGmp::add($state, static::ONE);
    static::assertTrue(BitWiseGmp::has($state, static::ONE));
    $state = BitWiseGmp::add($state, static::TWO);
    $state = BitWiseGmp::add($state, static::TWO);
    static::assertTrue(BitWiseGmp::has($state, static::ONE));
    static::assertTrue(BitWiseGmp::has($state, static::TWO));

    //Remove
    $state = BitWiseGmp::remove($state, static::ONE);
    $state = BitWiseGmp::remove($state, static::ONE);
    static::assertTrue(BitWiseGmp::has($state, static::TWO));
    static::assertFalse(BitWiseGmp::has($state, static::ONE));

    //Toggle
    $state = BitWiseGmp::toggle($state, static::ONE);
    static::assertTrue(BitWiseGmp::has($state, static::ONE));
    $state = BitWiseGmp::toggle($state, static::ONE);
    static::assertFalse(BitWiseGmp::has($state, static::ONE));

    //Highest
    $state = BitWiseGmp::add($state, static::FOUR);
    static::assertEquals(static::FOUR, BitWiseGmp::highest($state));
    $state = BitWiseGmp::add($state, static::SIX);
    static::assertEquals(static::SIX, BitWiseGmp::highest($state));

    //Get Bits
    static::assertEquals(
      [static::TWO, static::FOUR, static::SIX],
      BitWiseGmp::getBits($state)
    );
  }

  public function testHas()
  {
    $state = 0;
    $state = BitWiseGmp::add($state, static::TWO);
    $state = BitWiseGmp::add($state, static::FIVE);

    $mask = static::TWO | static::THREE;
    static::assertFalse(BitWiseGmp::has($state, $mask));
    static::assertTrue(BitWiseGmp::hasAny($state, $mask));
  }
}
