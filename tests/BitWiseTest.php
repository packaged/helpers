<?php

use Packaged\Helpers\BitWise;

class BitWiseTest extends PHPUnit_Framework_TestCase
{
  /**
   * @large
   */
  public function testSingleBit()
  {
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
    for($i = 0; $i < 10000; $i++)
    {
      $checkBit = bcmul($checkBit, 2);
      $this->assertTrue(BitWise::isSingleBit($checkBit));
      $this->assertFalse(BitWise::isSingleBit(bcsub($checkBit, 3)));
    }
  }
}
