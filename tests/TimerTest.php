<?php

namespace Packaged\Tests;

use Packaged\Helpers\Timer;
use PHPUnit\Framework\TestCase;

class TimerTest extends TestCase
{
  public function testBasicTimer()
  {
    $timer = new Timer('test-key');
    usleep(1000); // 1ms
    $timer->complete();

    self::assertEquals('test-key', $timer->key());
    self::assertGreaterThan(0, $timer->duration());
    self::assertNotNull($timer->startTime());
    self::assertNotNull($timer->endTime());
  }

  public function testTimerWithoutKey()
  {
    $timer = new Timer();
    self::assertNull($timer->key());
  }

  public function testSetKey()
  {
    $timer = new Timer();
    $result = $timer->setKey('new-key');

    self::assertSame($timer, $result);
    self::assertEquals('new-key', $timer->key());
  }

  public function testDescription()
  {
    $timer = new Timer();
    self::assertEquals('', $timer->description());

    $result = $timer->setDescription('Test description');
    self::assertSame($timer, $result);
    self::assertEquals('Test description', $timer->description());
  }

  public function testDurationBeforeComplete()
  {
    $timer = new Timer();
    usleep(1000);
    $duration = $timer->duration();

    self::assertGreaterThan(0, $duration);
    self::assertNull($timer->endTime());
  }

  public function testCompleteThrowsOnDoubleComplete()
  {
    $timer = new Timer('test');
    $timer->complete();

    $this->expectException(\RuntimeException::class);
    $this->expectExceptionMessage('The timer `test` has already been completed');
    $timer->complete();
  }

  public function testCompleteAllowEndUpdate()
  {
    $timer = new Timer('test');
    $timer->complete();
    $firstEnd = $timer->endTime();

    usleep(1000);
    $timer->complete(true);
    $secondEnd = $timer->endTime();

    self::assertGreaterThan($firstEnd, $secondEnd);
  }
}