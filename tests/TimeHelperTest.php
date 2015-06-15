<?php

use Packaged\Helpers\TimeHelper;

class TimeHelperTest extends PHPUnit_Framework_TestCase
{

  /**
   * @param $microtime
   * @param $uniqid
   * @param $hasEntropy
   *
   * @dataProvider uniqidProvider
   */
  public function testUniqid2microtime($microtime, $uniqid, $hasEntropy)
  {
    $time = TimeHelper::uniqidToMicroseconds($uniqid, $hasEntropy);
    $this->assertEquals($microtime, $time, '', 10);
  }

  public function uniqidProvider()
  {
    return [
      [TimeHelper::microseconds(), uniqid(), false],
      [TimeHelper::microseconds(), uniqid('PRE'), false],
      [TimeHelper::microseconds(), uniqid('', true), true],
      [TimeHelper::microseconds(), uniqid('PRE', true), true],
    ];
  }

  public function testMicroseconds()
  {
    $this->assertInternalType('int', TimeHelper::microseconds());
  }
}
