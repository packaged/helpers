<?php
namespace Packaged\Tests;

use Packaged\Helpers\FQDN;
use PHPUnit_Framework_TestCase;

class FQDNTest extends PHPUnit_Framework_TestCase
{
  /**
   * @dataProvider fqdnProvider
   *
   * @param $fqdn
   * @param $domain
   * @param $tld
   * @param $sub
   */

  public function testFqdn($fqdn, $domain, $tld, $sub)
  {
    $fq = new FQDN($fqdn);
    $this->assertEquals($domain, $fq->domain());
    $this->assertEquals($tld, $fq->tld());
    $this->assertEquals($sub, $fq->subDomain());
  }

  public function fqdnProvider()
  {
    return [
      ['localhost', 'localhost', null, null],
      ['domain.com', 'domain', 'com', null],
      ['my.domain.com', 'domain', 'com', 'my'],
      ['my.domain.co.uk', 'domain', 'co.uk', 'my'],
      ['my.domain.ltd.uk', 'domain', 'ltd.uk', 'my'],
      ['second.my.domain.com', 'domain', 'com', 'second.my'],
    ];
  }

  public function testDefinedTlds()
  {
    $fq = new FQDN('my.test.random.tld');

    $fq->defineTlds(['random.tld', 'x.y'], true);
    $this->assertEquals(['random.tld', 'x.y'], $fq->getDefinedTlds());

    $fq->defineTlds(['random.tld']);
    $this->assertEquals(['random.tld'], $fq->getDefinedTlds());

    $this->assertEquals('random.tld', $fq->tld());
    $this->assertEquals('test', $fq->domain());
    $this->assertEquals('my', $fq->subDomain());

    $this->assertEquals(['random.tld'], $fq->getDefinedTlds());

    $fq = new FQDN('my.test.source.google');
    $fq->defineTlds(['google']);
    $this->assertEquals('google', $fq->tld());
    $this->assertEquals('source', $fq->domain());
    $this->assertEquals('my.test', $fq->subDomain());
  }

  public function testDefinedStarTlds()
  {
    $fq = new FQDN('my.test.qa.random.tld');
    $fq->defineTlds(['*.random.tld', 'x.y'], true);
    $this->assertEquals('test', $fq->domain());
    $this->assertEquals('my', $fq->subDomain());
    $this->assertEquals('qa.random.tld', $fq->tld());
  }

  public function testUrl()
  {
    $fq = new FQDN('http://my.test.co.uk/webpage.html');
    $this->assertEquals("test", $fq->domain());
    $this->assertEquals("co.uk", $fq->tld());
    $this->assertEquals("my", $fq->subDomain());
  }
}
