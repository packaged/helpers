<?php
namespace Packaged\Tests;

use Packaged\Helpers\TimeHelper;
use PHPUnit_Framework_TestCase;

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
    $time = TimeHelper::uniqidToMilliseconds($uniqid, $hasEntropy);
    $this->assertEquals($microtime, $time, '', 10);
  }

  public function uniqidProvider()
  {
    return [
      [TimeHelper::milliseconds(), uniqid(), false],
      [TimeHelper::milliseconds(), uniqid('PRE'), false],
      [TimeHelper::milliseconds(), uniqid('', true), true],
      [TimeHelper::milliseconds(), uniqid('PRE', true), true],
    ];
  }

  public function testMilliseconds()
  {
    $this->assertInternalType('int', TimeHelper::milliseconds());
  }

  public function testToSeconds()
  {
    $this->assertEquals(TimeHelper::toSeconds(1466159101859), 1466159101);
  }
}
