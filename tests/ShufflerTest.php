<?php
namespace Packaged\Tests;

use Packaged\Helpers\Shuffler;
use Packaged\Helpers\Strings;
use PHPUnit_Framework_TestCase;
use stdClass;

class ShufflerTest extends PHPUnit_Framework_TestCase
{
  public function testShuffleOne()
  {
    $shuffler = new Shuffler();
    $this->assertEquals($shuffler, $shuffler->addValue('abc', 1));
    $this->assertEquals('abc', $shuffler->read());
  }

  public function testShuffleInvalidProbability()
  {
    $shuffler = new Shuffler();
    $this->setExpectedException("\InvalidArgumentException");
    $this->assertEquals($shuffler, $shuffler->addValue('abc', 10000000));
  }

  public function testShuffle()
  {
    $s1 = $this->_getShuffler();
    $s2 = clone $s1;

    $diffCount = 0;
    while($check = $s1->pop())
    {
      if($check !== $s2->pop())
      {
        $diffCount++;
      }
    }
    $this->assertGreaterThan(0, $diffCount);
    // assert they're both empty
    $this->assertNull($s1->read());
    $this->assertNull($s2->read());
    $this->assertNull($s1->pop());
    $this->assertNull($s2->pop());
  }

  public function testShuffleObj()
  {
    $shuffler = new Shuffler();

    $ob1 = new stdClass();
    $ob1->kenobi = true;
    $shuffler->addValue($ob1, 10);

    $ob2 = new stdClass();
    $ob2->random = 'string';
    $shuffler->addValue($ob2, 10);

    $this->assertContains($shuffler->read(), [$ob1, $ob2]);
    $this->assertContains($shuffler->pop(), [$ob1, $ob2]);
    $this->assertContains($shuffler->pop(), [$ob1, $ob2]);
    $this->assertNull($shuffler->read());
    $this->assertNull($shuffler->pop());
  }

  private function _getShuffler()
  {
    $shuffler = new Shuffler();
    $items = Strings::stringToRange('1-50');
    foreach($items as $item)
    {
      $shuffler->addValue($item);
    }
    return $shuffler;
  }
}
