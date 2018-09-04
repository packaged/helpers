<?php
namespace Packaged\Tests;

use Packaged\Helpers\Shuffler;

class ShufflerTest extends \PHPUnit_Framework_TestCase
{
  public function testShuffle()
  {
    $shuffler = new Shuffler();
    $this->assertEquals($shuffler, $shuffler->addValue('abc', 1));
    $this->assertEquals('abc', $shuffler->read());
    $shuffler->addValue('def', 100);
    $this->assertEquals('def', $shuffler->pop());
    $this->assertEquals('abc', $shuffler->pop());
    $this->assertNull($shuffler->read());
    $this->assertNull($shuffler->pop());
  }

  public function testShuffleObj()
  {
    $shuffler = new Shuffler();

    $ob1 = new \stdClass();
    $ob1->kenobi = true;
    $shuffler->addValue($ob1, 10);

    $ob2 = new \stdClass();
    $ob2->random = 'string';
    $shuffler->addValue($ob2, 10);

    $this->assertContains($shuffler->read(), [$ob1, $ob2]);
    $this->assertContains($shuffler->pop(), [$ob1, $ob2]);
    $this->assertContains($shuffler->pop(), [$ob1, $ob2]);
    $this->assertNull($shuffler->read());
    $this->assertNull($shuffler->pop());
  }
}
